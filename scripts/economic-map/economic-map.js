/**
 * economic-map.js
 * Renders the Economic Hotspot Map and the MSME Distribution Map
 * using Leaflet. Data comes from server-side/business-handler.php.
 *
 * Dependencies: jQuery 3+, Leaflet 1.9.x
 */
(function ($) {
    'use strict';

    var HANDLER = '../../api/routes.php/economic-map';

    // Approximate centroids of the 18 barangays of San Carlos City
    var BARANGAYS = [
        ['Bagonbon',    10.5820, 123.3989],
        ['Barangay I',  10.4939, 123.4273],
        ['Barangay II', 10.4842, 123.4111],
        ['Barangay III',10.4844, 123.4236],
        ['Barangay IV', 10.4826, 123.4172],
        ['Barangay V',  10.4792, 123.4127],
        ['Barangay VI', 10.4800, 123.4222],
        ['Buluangan',   10.3874, 123.3376],
        ['Codcod',      10.4574, 123.2173],
        ['Ermita',      10.4435, 123.4186],
        ['Guadalupe',   10.4541, 123.3696],
        ['Nataban',     10.4973, 123.3049],
        ['Palampas',    10.5135, 123.4106],
        ['Prosperidad', 10.5122, 123.2785],
        ['Punao',       10.5305, 123.4329],
        ['Quezon',      10.4360, 123.2604],
        ['Rizal',       10.4970, 123.3599],
        ['San Juan',    10.4627, 123.4398]
    ];

    // Sectors come from global/industries.php via API (action=sectors).
    // Canonical form is STRTOUPPER of that file (matching how MSME
    // registration stores line_of_industry). FALLBACK_SECTORS mirrors the
    // file so all 17 filter buttons render even if the API is unreachable.
    var FALLBACK_SECTORS = [
        'AGRICULTURE',
        'FISHING',
        'MINING AND QUARRYING',
        'MANUFACTURING',
        'ELECTRICITY, GAS, AND WATER SUPPLY',
        'CONSTRUCTION',
        'WHOLESALE AND RETAIL TRADE',
        'HOTELS AND RESTAURANTS',
        'TRANSPORT, STORAGE, AND COMMUNICATION',
        'FINANCIAL INTERMEDIATION',
        'REAL ESTATE, RENTING, AND BUSINESS ACTIVITIES',
        'PUBLIC ADMINISTRATION AND DEFENSE',
        'EDUCATION',
        'HEALTH AND SOCIAL WORKER',
        'OTHER COMMUNITY, SOCIAL AND PERSONAL SERVICE ACTIVITIES',
        'ACTIVITIES OF PRIVATE HOUSEHOLDS AS EMPLOYERS...',
        'EXTRA-TERRITORIAL ORGANIZATIONS AND BODIES'
    ];

    var CATEGORIES = FALLBACK_SECTORS.slice();

    var BASE_CAT_COLORS = {
        'AGRICULTURE':                                             '#28a745',
        'FISHING':                                                 '#17a2b8',
        'MINING AND QUARRYING':                                    '#795548',
        'MANUFACTURING':                                           '#6f42c1',
        'ELECTRICITY, GAS, AND WATER SUPPLY':                      '#fd7e14',
        'CONSTRUCTION':                                            '#e65100',
        'WHOLESALE AND RETAIL TRADE':                              '#007bff',
        'HOTELS AND RESTAURANTS':                                  '#e83e8c',
        'TRANSPORT, STORAGE, AND COMMUNICATION':                   '#20c997',
        'FINANCIAL INTERMEDIATION':                                '#ffc107',
        'REAL ESTATE, RENTING, AND BUSINESS ACTIVITIES':           '#6610f2',
        'PUBLIC ADMINISTRATION AND DEFENSE':                       '#343a40',
        'EDUCATION':                                               '#0dcaf0',
        'HEALTH AND SOCIAL WORKER':                                '#dc3545',
        'OTHER COMMUNITY, SOCIAL AND PERSONAL SERVICE ACTIVITIES': '#6c757d',
        'ACTIVITIES OF PRIVATE HOUSEHOLDS AS EMPLOYERS...':        '#adb5bd',
        'EXTRA-TERRITORIAL ORGANIZATIONS AND BODIES':              '#495057'
    };

    var CAT_COLORS = $.extend({}, BASE_CAT_COLORS);

    function normSector(s) {
        return String(s == null ? '' : s).toUpperCase();
    }

    // Deterministic color for any sector not in the base palette
    // (e.g. if global/industries.php gains new sectors later).
    var EXTRA_PALETTE = ['#0ea5e9', '#84cc16', '#f43f5e', '#8b5cf6', '#f59e0b', '#10b981', '#ec4899', '#6366f1'];
    function sectorColor(sector) {
        var key = normSector(sector);
        if (CAT_COLORS[key]) return CAT_COLORS[key];
        var h = 0;
        for (var i = 0; i < key.length; i++) {
            h = (h * 31 + key.charCodeAt(i)) % 997;
        }
        var c = EXTRA_PALETTE[h % EXTRA_PALETTE.length];
        CAT_COLORS[key] = c;
        return c;
    }

    // Hotspot severity levels (relative to the top barangay)
    var HOTSPOT_LEVELS = [
        { min: 0.60, label: 'Very High', color: '#7b1fa2' },
        { min: 0.30, label: 'High',      color: '#dc3545' },
        { min: 0.15, label: 'Moderate',  color: '#fd7e14' },
        { min: 0.00, label: 'Low',       color: '#ffc107' }
    ];

    // OpenStreetMap standard tiles — no API key required.
    // (CARTO basemaps now enforce API keys and render "API key required"
    // error tiles, so they are no longer used here.)
    var TILE_URL  = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
    var TILE_ATTR = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';

    var mapHotspot = null;
    var mapDist = null;
    var mapRisk = null;
    var mapOpportunity = null;
    var distData = [];       // per-barangay rows
    var distLayer = null;
    var msmeLayer = null;    // individual MSME pins for a selected sector
    var activeCategory = 'all';
    var distPieChart = null;   // Chart.js instance for sector pie
    var distCatTotals = {};    // cached category totals from last load
    var distSectorCounts = {}; // totals shown beside each filter row
    var riskData = [];
    var riskLayer = null;
    var oppData = [];
    var oppLayer = null;

    // ── Bootstrap ──────────────────────────────────────────────────────
    $(function () {
        initHotspotMap();
        initDistMap();
        initRiskMap();
        initOpportunityMap();
        loadHotspots();
        // Sectors first (global/industries.php via API), then distribution
        // so chips/totals/legend always match registered MSME industries.
        loadSectors(function () {
            loadDistribution();
        });
        loadRisk();
        loadOpportunity();
        initAreaSearch();
        syncActiveTab();
        syncSidebar();

        $('#riskCalamity').on('change', function () {
            loadRisk(parseInt(this.value, 10) || 0);
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
            syncSidebar();
            var href = $(this).attr('href');
            setTimeout(function () {
                if (href === '#pane-hotspot') {
                    mapHotspot.invalidateSize();
                } else if (href === '#pane-distribution') {
                    mapDist.invalidateSize();
                    // Chart was created while the tab was hidden (display:none),
                    // so its canvas had zero dimensions. Resize now that it's visible.
                    if (distPieChart) { distPieChart.resize(); }
                } else if (href === '#pane-risk') {
                    mapRisk.invalidateSize();
                } else if (href === '#pane-opportunity') {
                    mapOpportunity.invalidateSize();
                }
            }, 200);
        });
    });

    // ── Tab & sidebar sync ─────────────────────────────────────────────
    function syncActiveTab() {
        var hash = window.location.hash;
        showTabFromHash(hash);
        $(window).on('hashchange', function () {
            showTabFromHash(window.location.hash);
        });
    }

    function showTabFromHash(h) {
        if ((h === '#distribution' || h === '#pane-distribution') && !$('#tab-distribution').hasClass('active')) {
            $('#tab-distribution').tab('show');
        } else if ((h === '#risk' || h === '#pane-risk') && !$('#tab-risk').hasClass('active')) {
            $('#tab-risk').tab('show');
        } else if ((h === '#opportunity' || h === '#pane-opportunity') && !$('#tab-opportunity').hasClass('active')) {
            $('#tab-opportunity').tab('show');
        } else if ((h === '#hotspot' || h === '#pane-hotspot') && !$('#tab-hotspot').hasClass('active')) {
            $('#tab-hotspot').tab('show');
        }
    }

    function syncSidebar() {
        var $li = $('#module_economic_map');
        $li.addClass('menu-open').find('> .nav-link').addClass('active');
        var onOpp = $('#tab-opportunity').hasClass('active');
        var onRisk = $('#tab-risk').hasClass('active');
        var onDist = $('#tab-distribution').hasClass('active');
        $li.find('a[href*="#opportunity"]').toggleClass('active', onOpp);
        $li.find('a[href*="#risk"]').toggleClass('active', !onOpp && onRisk);
        $li.find('a[href*="#distribution"]').toggleClass('active', !onOpp && !onRisk && onDist);
        $li.find('a[href*="#hotspot"]').toggleClass('active', !onOpp && !onRisk && !onDist);
    }

    // ── Helpers ────────────────────────────────────────────────────────
    function fmt(n) {
        return parseInt(n, 10).toLocaleString('en-US');
    }

    function baseTile(map) {
        L.tileLayer(TILE_URL, {
            maxZoom: 19,
            attribution: TILE_ATTR
        }).addTo(map);
    }

    function hotspotLevel(count, max) {
        var ratio = count / (max || 1);
        for (var i = 0; i < HOTSPOT_LEVELS.length; i++) {
            if (ratio >= HOTSPOT_LEVELS[i].min) {
                return HOTSPOT_LEVELS[i];
            }
        }
        return { label: 'Low', color: '#ffc107' };
    }

    function dominantCategory(row) {
        var best = CATEGORIES[0] || 'OTHER COMMUNITY, SOCIAL AND PERSONAL SERVICE ACTIVITIES';
        var bestN = -1;
        $.each(row.categories, function (cat, n) {
            if (n > bestN) { best = cat; bestN = n; }
        });
        return best;
    }

    // ── Economic Hotspot Map ───────────────────────────────────────────
    function initHotspotMap() {
        mapHotspot = L.map('mapHotspot', {
            scrollWheelZoom: false,
            zoomControl: false,
            preferCanvas: true
        }).setView([10.4824, 123.4183], 12);
        baseTile(mapHotspot);
        L.control.zoom({ position: 'topleft' }).addTo(mapHotspot);
        L.control.scale({ imperial: false, position: 'bottomright' }).addTo(mapHotspot);

        // Static context dots for every barangay
        BARANGAYS.forEach(function (b) {
            L.circleMarker([b[1], b[2]], {
                radius: 5, color: '#fff', weight: 1.5,
                fillColor: '#94a3b8', fillOpacity: .6
            }).addTo(mapHotspot)
              .bindTooltip(b[0], { direction: 'top', opacity: .9, className: 'emap-tooltip' });
        });
    }

    var hotspotMarkers = {}; // barangay -> Leaflet layer (for ranking clicks)

    function loadHotspots() {
        $.getJSON(HANDLER, { action: 'economic_hotspots' }, function (res) {
            if (res.status !== 'success') {
                console.error('economic_hotspots error:', res.message);
                $('#hotspotRanking').html('<div class="modern-dd-empty">Failed to load ranking.</div>');
                $('#hotspotRankBtnText').text('Failed to load ranking');
                return;
            }

            var max = 1;
            res.mapped.forEach(function (r) { if (r.count > max) max = r.count; });

            hotspotMarkers = {};
            res.mapped.forEach(function (r) {
                var lvl = hotspotLevel(r.count, max);
                var radius = Math.max(7, 6 + 8 * Math.sqrt(r.count / max));
                var layer = L.circleMarker([r.lat, r.lng], {
                    radius: radius,
                    color: lvl.color,
                    weight: 2,
                    fillColor: lvl.color,
                    fillOpacity: .45
                }).addTo(mapHotspot);

                layer.bindTooltip(
                    '<b>' + r.barangay + '</b><br>' + fmt(r.count) +
                    ' MSME' + (r.count !== 1 ? 's' : '') +
                    ' &middot; ' + lvl.label,
                    { direction: 'top', opacity: .9 }
                );
                layer.bindPopup(
                    '<div style="min-width:150px;">' +
                    '<b>' + r.barangay + '</b><br>' +
                    '<span class="badge msme-badge-unknown">' + lvl.label + ' hotspot</span><br>' +
                    '<span style="font-size:1.1rem;font-weight:700;color:#dc3545;">' +
                    fmt(r.count) + '</span> registered MSMEs' +
                    '</div>'
                );
                hotspotMarkers[r.barangay] = layer;
            });

            $('#hotspotTotal').text(fmt(res.total));
            $('#hotspotBadge').text(fmt(res.total) + ' registered MSME' + (res.total !== 1 ? 's' : ''));
            $('#hotspotWithBusiness').text(fmt(res.mapped.length));

            var top = res.mapped[0];
            $('#hotspotTopBrgy').text(top ? top.barangay : '—');

            renderHotspotRanking(res.mapped || [], max);

            if (res.unmapped && res.unmapped.length) {
                var names = res.unmapped.map(function (u) {
                    return u.barangay + ' (' + u.count + ')';
                }).join(', ');
                console.warn('Unmapped barangays (no coordinates):', names);
            }

            renderHotspotLegend();
        }).fail(function (xhr) {
            console.error('economic_hotspots request failed:', xhr.statusText);
        });
    }

    function renderHotspotLegend() {
        var html = '<h6>Concentration</h6>';
        html += '<div><span class="legend-dot" style="background:#7b1fa2;"></span>Very High (60%+ of top)</div>';
        html += '<div><span class="legend-dot" style="background:#dc3545;"></span>High (30%+)</div>';
        html += '<div><span class="legend-dot" style="background:#fd7e14;"></span>Moderate (15%+)</div>';
        html += '<div><span class="legend-dot" style="background:#ffc107;"></span>Low</div>';
        var legend = L.control({ position: 'bottomright' });
        legend.onAdd = function () {
            var div = L.DomUtil.create('div', 'map-legend');
            div.innerHTML = html;
            return div;
        };
        legend.addTo(mapHotspot);
    }

    // ── Hotspot ranking dropdown: top barangay → lowest ─────────────
    // res.mapped from the API is already sorted highest-count first.
    var hotspotRankSelected = null;

    function closeRankDropdown() {
        $('#hotspotRankDD').removeClass('open');
        $('#hotspotRankPanel').addClass('d-none');
    }

    function renderHotspotRanking(mapped, max) {
        var $list = $('#hotspotRanking');
        if (!$list.length) return;
        hotspotRankSelected = null;
        $('#hotspotRankBtnText').text('Select barangay…');
        $('#hotspotRankDetail').addClass('d-none').empty();
        closeRankDropdown();

        if (!mapped.length) {
            $list.html('<div class="modern-dd-empty">No registered MSMEs found.</div>');
            $('#hotspotRankBadge').text('0 barangays');
            return;
        }

        $('#hotspotRankBadge').text(mapped.length + ' ranked');

        var html = '';
        mapped.forEach(function (r, i) {
            var rank = i + 1;
            var lvl = hotspotLevel(r.count, max);
            html += '<button type="button" class="modern-dd-opt opt-' + rank + '" ' +
                    'data-barangay="' + $('<span>').text(r.barangay).html() + '">' +
                    '<span class="rank-num">' + rank + '</span>' +
                    '<span class="legend-dot" style="background:' + lvl.color + ';margin-right:0;"></span>' +
                    '<span class="opt-name">' + $('<span>').text(r.barangay).html() + '</span>' +
                    '<span class="opt-count">' + fmt(r.count) + '</span>' +
                    '<i class="material-icons opt-check">check</i>' +
                    '</button>';
        });
        $list.html(html);

        // Open / close
        $('#hotspotRankBtn').off('click.hotspotRank').on('click.hotspotRank', function (e) {
            e.stopPropagation();
            var $dd = $('#hotspotRankDD');
            var opening = !$dd.hasClass('open');
            closeRankDropdown();
            if (opening) {
                $dd.addClass('open');
                $('#hotspotRankPanel').removeClass('d-none');
                $('#hotspotRankSearch').val('').focus();
                $list.find('.modern-dd-opt').show();
                $list.find('.modern-dd-empty').remove();
            }
        });
        $(document).off('click.hotspotRankClose').on('click.hotspotRankClose', function (e) {
            if (!$(e.target).closest('#hotspotRankDD').length) {
                closeRankDropdown();
            }
        });
        $(document).off('keydown.hotspotRankEsc').on('keydown.hotspotRankEsc', function (e) {
            if (e.key === 'Escape') closeRankDropdown();
        });

        // Search filter
        $('#hotspotRankSearch').off('input.hotspotRank').on('input.hotspotRank', function () {
            var q = $.trim(this.value).toLowerCase();
            var visible = 0;
            $list.find('.modern-dd-opt').each(function () {
                var match = !q || String($(this).data('barangay')).toLowerCase().indexOf(q) !== -1;
                $(this).toggle(match);
                if (match) visible++;
            });
            $list.find('.modern-dd-empty').remove();
            if (!visible) {
                $list.append('<div class="modern-dd-empty">No barangays match your search.</div>');
            }
        });

        // Pick a barangay
        $list.find('.modern-dd-opt').off('click.hotspotRank').on('click.hotspotRank', function () {
            var name = $(this).data('barangay');
            hotspotRankSelected = name;
            $list.find('.modern-dd-opt').removeClass('selected');
            $(this).addClass('selected');
            $('#hotspotRankBtnText').text(name);
            closeRankDropdown();

            // Show rank detail card for the selected barangay
            var rank = 0, rec = null;
            mapped.forEach(function (r, i) {
                if (r.barangay === name) { rank = i + 1; rec = r; }
            });
            if (rec) {
                var lvl = hotspotLevel(rec.count, max);
                $('#hotspotRankDetail').html(
                    '<span class="rank-num">' + rank + '</span>' +
                    '<span class="rank-info"><b>' + $('<span>').text(rec.barangay).html() + '</b><br>' +
                    fmt(rec.count) + ' MSME' + (rec.count !== 1 ? 's' : '') +
                    ' &middot; <span style="font-weight:700;color:' + lvl.color + ';">' + lvl.label + '</span></span>'
                ).removeClass('d-none');
            }
            flyToBarangay(name);
            var marker = hotspotMarkers[name];
            if (marker) {
                setTimeout(function () { marker.openPopup(); }, 1300);
            }
        });
    }

    // ── MSME Distribution Map ──────────────────────────────────────────
    function initDistMap() {
        mapDist = L.map('mapDistribution', {
            scrollWheelZoom: false
        }).setView([10.4824, 123.4183], 12);
        baseTile(mapDist);

        BARANGAYS.forEach(function (b) {
            L.circleMarker([b[1], b[2]], {
                radius: 4, color: '#adb5bd', weight: 1,
                fillColor: '#adb5bd', fillOpacity: .9
            }).addTo(mapDist)
              .bindTooltip(b[0], { direction: 'top', opacity: .85 });
        });

        renderCategoryChips();
    }

    // ── Sectors (global/industries.php via API, STRTOUPPER) ──────────
    // Always renders all 17 filter buttons, even before distribution loads.
    function loadSectors(done) {
        renderCategoryChips();
        $.getJSON(HANDLER, { action: 'sectors' }, function (res) {
            if (res.status === 'success' && Array.isArray(res.sectors) && res.sectors.length) {
                CATEGORIES = res.sectors.map(normSector);
                // Ensure every sector has a color
                CATEGORIES.forEach(function (cat) { sectorColor(cat); });
            } else {
                CATEGORIES = FALLBACK_SECTORS.slice();
            }
            renderCategoryChips();
            // Totals/legend shells: show all 17 immediately (zeros until data loads)
            distSectorCounts = {};
            renderCategoryTotals({});
        }).fail(function (xhr) {
            console.error('sectors request failed, using fallback list:', xhr.statusText);
            CATEGORIES = FALLBACK_SECTORS.slice();
            distSectorCounts = {};
            renderCategoryChips();
            renderCategoryTotals({});
        }).always(function () {
            if (typeof done === 'function') done();
        });
    }

    function loadDistribution() {
        $.getJSON(HANDLER, { action: 'msme_distribution' }, function (res) {
            if (res.status !== 'success') {
                console.error('msme_distribution error:', res.message);
                $('#distBadge').text('failed to load registered MSMEs');
                return;
            }
            // Normalize API category keys to UPPERCASE and adopt any new
            // sector keys (keeps map in sync when global/industries.php changes).
            var normCats = {};
            if (res.categories) {
                Object.keys(res.categories).forEach(function (cat) {
                    var key = normSector(cat);
                    normCats[key] = (normCats[key] || 0) + (res.categories[cat] || 0);
                    if (CATEGORIES.indexOf(key) === -1) {
                        CATEGORIES.push(key);
                        sectorColor(key);
                    }
                });
                res.categories = normCats;
            }
            // Normalize per-barangay category keys too (backend already sends
            // uppercase, but this guards mixed-case responses).
            (res.data || []).forEach(function (row) {
                if (!row.categories) return;
                var nc = {};
                Object.keys(row.categories).forEach(function (k) {
                    var key = normSector(k);
                    nc[key] = (nc[key] || 0) + (row.categories[k] || 0);
                });
                row.categories = nc;
            });
            distData = res.data || [];
            distSectorCounts = res.categories || {};
            renderCategoryChips();
            renderCategoryTotals(res.categories || {});
            renderDistPieChart(res.categories || {});
            renderDistMarkers();
            $('#distTotal').text(fmt(res.total) + ' MSME' + (res.total !== 1 ? 's' : ''));
            $('#distBadge').text(fmt(res.total) + ' registered MSME' + (res.total !== 1 ? 's' : ''));
            if (!res.total) {
                $('#distBadge').text('No registered MSMEs found');
            }
        }).fail(function (xhr) {
            console.error('msme_distribution request failed:', xhr.statusText);
            $('#distBadge').text('failed to load registered MSMEs');
        });
    }

    // ── Sector filter: simple organized list (search + rows w/ counts) ──
    function selectSector(cat) {
        activeCategory = (activeCategory === cat) ? 'all' : cat;
        renderCategoryChips();
        renderDistMarkers();
        updateDistPieChart();
        if (activeCategory === 'all') {
            clearMsmeLayer();
        } else {
            renderSectorMsmes(activeCategory);
        }
    }

    function applySectorSearch() {
        var q = normSector($.trim($('#distSectorSearch').val() || ''));
        var visible = 0;
        $('#distChips .sector-row').each(function () {
            var match = !q || normSector($(this).data('cat')).indexOf(q) !== -1;
            $(this).toggle(match);
            if (match) visible++;
        });
        $('#distChips .sector-empty').remove();
        if (!visible) {
            $('#distChips').append('<div class="sector-empty">No sectors match your search.</div>');
        }
    }

    function renderCategoryChips() {
        var $wrap = $('#distChips');
        var prevSearch = $.trim($('#distSectorSearch').val() || '');
        $wrap.empty();

        CATEGORIES.forEach(function (cat) {
            var color = sectorColor(cat);
            var n = distSectorCounts[cat];
            var countTxt = (n == null) ? '—' : fmt(n);
            var $row = $('<button>', {
                'class': 'sector-row' + (activeCategory === cat ? ' active' : ''),
                'data-cat': cat,
                type: 'button',
                title: cat
            }).html(
                '<span class="legend-dot" style="background:' + color + ';margin-right:0;"></span>' +
                '<span class="sector-name">' + $('<span>').text(cat).html() + '</span>' +
                '<span class="sector-count">' + countTxt + '</span>'
            ).appendTo($wrap);

            $row.on('click', function () {
                selectSector(cat);
            });
        });

        // "All sectors" reset button (static markup in PHP)
        $('.sector-all-btn[data-cat="all"]')
             .toggleClass('active', activeCategory === 'all')
             .toggleClass('dimmed', activeCategory !== 'all')
             .off('click.allsectors')
             .on('click.allsectors', function () {
                 activeCategory = 'all';
                 renderCategoryChips();
                 renderDistMarkers();
                 updateDistPieChart();
                 clearMsmeLayer();
             });

        // Search box filters rows (bound once)
        $('#distSectorSearch')
            .off('input.sectorfilter')
            .on('input.sectorfilter', applySectorSearch);

        if (prevSearch) {
            $('#distSectorSearch').val(prevSearch);
        }
        applySectorSearch();
    }

    function renderCategoryTotals(cats) {
        var html = '';
        CATEGORIES.forEach(function (cat) {
            var color = sectorColor(cat);
            html += '<div class="d-flex justify-content-between align-items-center mb-1">' +
                    '<span><span class="legend-dot" style="background:' + color + ';"></span>' +
                    $('<span>').text(cat).html() + '</span>' +
                    '<b>' + fmt(cats[cat] || 0) + '</b></div>';
        });
        $('#distCategoryTotals').html(html);

        var legendHtml = '';
        CATEGORIES.forEach(function (cat) {
            legendHtml += '<div><span class="legend-dot" style="background:' + sectorColor(cat) +
                          ';"></span>' + $('<span>').text(cat).html() + '</div>';
        });
        $('#distLegend').html(legendHtml);
    }

    // ── Distribution sector pie chart ──────────────────────────────────
    function renderDistPieChart(cats) {
        distCatTotals = cats;

        // Filter out zero-value categories so the chart stays clean
        var labels       = [];
        var values       = [];
        var colors       = [];
        var borderColors = [];
        var total        = 0;

        CATEGORIES.forEach(function (cat) {
            var n = cats[cat] || 0;
            total += n;
            if (n > 0) {
                labels.push(cat);
                values.push(n);
                colors.push(sectorColor(cat) + 'cc');   // 80% opacity fill
                borderColors.push(sectorColor(cat));
            }
        });

        // ── Badge ──
        $('#distPieBadge').text(fmt(total) + ' MSME' + (total !== 1 ? 's' : '') + ' across ' + labels.length + ' sectors');

        // ── Inline legend with percentage bars (full-width rows for sidebar) ──
        var legendHtml = '<div class="row">';
        labels.forEach(function (lbl, i) {
            var n   = values[i];
            var pct = total > 0 ? ((n / total) * 100).toFixed(1) : '0.0';
            var col = sectorColor(lbl);
            legendHtml +=
                '<div class="col-md-6 mb-3 pie-legend-item">' +
                  '<div class="d-flex justify-content-between align-items-start mb-1" style="gap:10px;">' +
                    '<span class="small" style="font-weight:600;line-height:1.45;flex:1;min-width:0;word-break:break-word;">' +
                      '<span class="legend-dot" style="background:' + col + ';"></span>' + $('<span>').text(lbl).html() +
                    '</span>' +
                    '<span class="small text-muted" style="white-space:nowrap;flex-shrink:0;margin-left:8px;">' + fmt(n) + ' <b>(' + pct + '%)</b></span>' +
                  '</div>' +
                  '<div style="height:5px;border-radius:3px;background:#f1f3f5;margin-top:4px;">' +
                    '<div style="height:5px;border-radius:3px;width:' + pct + '%;background:' + col + ';transition:width .4s;"></div>' +
                  '</div>' +
                '</div>';
        });
        legendHtml += '</div>';
        $('#distPieLegend').html(legendHtml);

        // ── Chart ──
        var ctx = document.getElementById('distPieChart');
        if (!ctx) return;

        if (distPieChart) {
            distPieChart.destroy();
            distPieChart = null;
        }

        distPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    borderColor: borderColors,
                    borderWidth: 2,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '58%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                var t = context.dataset.data.reduce(function (a, b) { return a + b; }, 0);
                                var pct = t > 0 ? ((context.parsed / t) * 100).toFixed(1) : '0.0';
                                return ' ' + context.label + ': ' + fmt(context.parsed) + ' (' + pct + '%)';
                            }
                        }
                    }
                },
                animation: { duration: 600 }
            }
        });
    }

    // Highlight the active sector slice (dim the rest) without re-fetching data
    function updateDistPieChart() {
        if (!distPieChart) return;

        var dataset = distPieChart.data.datasets[0];
        var labels  = distPieChart.data.labels;

        if (activeCategory === 'all') {
            // Restore all slices to normal opacity
            dataset.backgroundColor = labels.map(function (lbl) {
                return sectorColor(lbl) + 'cc';
            });
            dataset.borderWidth = labels.map(function () { return 2; });
            // Restore legend bars
            $('#distPieLegend .pie-legend-item').css('opacity', '1');
        } else {
            // Dim every slice except the active one
            dataset.backgroundColor = labels.map(function (lbl) {
                return lbl === activeCategory ? sectorColor(lbl) : sectorColor(lbl) + '33';
            });
            dataset.borderWidth = labels.map(function (lbl) {
                return lbl === activeCategory ? 3 : 1;
            });
            // Dim legend bars that aren't selected
            $('#distPieLegend .pie-legend-item').each(function () {
                var isActive = $(this).text().indexOf(activeCategory) !== -1;
                $(this).css('opacity', isActive ? '1' : '0.25');
            });
        }

        distPieChart.update();
    }

    function renderDistMarkers() {
        if (distLayer) {
            mapDist.removeLayer(distLayer);
        }
        distLayer = L.layerGroup().addTo(mapDist);

        var maxTotal = 1;
        var maxCat = {};
        distData.forEach(function (row) {
            if (row.total > maxTotal) maxTotal = row.total;
            $.each(row.categories, function (cat, n) {
                if (n > (maxCat[cat] || 0)) maxCat[cat] = n;
            });
        });

        distData.forEach(function (row) {
            if (activeCategory === 'all') {
                var dom = dominantCategory(row);
                var domColor = sectorColor(dom);
                var radius = Math.max(6, 5 + 9 * Math.sqrt(row.total / maxTotal));
                var mk = L.circleMarker([row.lat, row.lng], {
                    radius: radius,
                    color: domColor,
                    weight: 2,
                    fillColor: domColor,
                    fillOpacity: .5
                }).addTo(distLayer);

                mk.bindTooltip(
                    '<b>' + row.barangay + '</b><br>' + fmt(row.total) +
                    ' MSME' + (row.total !== 1 ? 's' : '') + ' &middot; ' + dom,
                    { direction: 'top', opacity: .9 }
                );
                mk.bindPopup(buildBreakdownPopup(row));
            } else {
                var n = row.categories[activeCategory] || 0;
                if (n === 0) return;
                var actColor = sectorColor(activeCategory);
                var r2 = Math.max(6, 5 + 9 * Math.sqrt(n / (maxCat[activeCategory] || 1)));
                var mk2 = L.circleMarker([row.lat, row.lng], {
                    radius: r2,
                    color: actColor,
                    weight: 2,
                    fillColor: actColor,
                    fillOpacity: .55
                }).addTo(distLayer);

                mk2.bindTooltip(
                    '<b>' + row.barangay + '</b><br>' + fmt(n) + ' MSME' +
                    (n !== 1 ? 's' : '') + ' &middot; ' + activeCategory,
                    { direction: 'top', opacity: .9 }
                );
                mk2.bindPopup(buildBreakdownPopup(row, activeCategory));
            }
        });
    }

    // ── Individual MSME pins for a selected sector ────────────────────
    function clearMsmeLayer() {
        if (msmeLayer) {
            mapDist.removeLayer(msmeLayer);
            msmeLayer = null;
        }
    }

    function renderSectorMsmes(sector) {
        clearMsmeLayer();

        var color  = sectorColor(sector);
        var $badge = $('#distBadge');
        $badge.text('loading registered MSMEs…');

        $.getJSON(HANDLER, { action: 'sector_businesses', sector: sector }, function (res) {
            if (res.status !== 'success') {
                console.error('sector_businesses error:', res.message);
                $badge.text(res.message || 'error');
                return;
            }

            $badge.text(fmt(res.total) + ' registered MSME' + (res.total !== 1 ? 's' : '') +
                        ' \u00b7 ' + sector);
            if (!res.total) {
                if (res.unmapped_count > 0) {
                    $badge.text('0 mapped \u00b7 ' + res.unmapped_count + ' outside city map \u00b7 ' + sector);
                }
                return;
            }

            msmeLayer = L.layerGroup().addTo(mapDist);

            res.data.forEach(function (biz) {
                var marker = L.circleMarker([biz.lat, biz.lng], {
                    radius:      7,
                    color:       '#fff',
                    weight:      1.5,
                    fillColor:   color,
                    fillOpacity: 0.88
                }).addTo(msmeLayer);

                var street = biz.street ? '<br><span class="text-muted" style="font-size:.75rem;">' +
                             biz.street + '</span>' : '';
                var regBadge = biz.reg_type === 'NEW'
                    ? '<span class="badge badge-success ml-1" style="font-size:.65rem;">New</span>'
                    : '<span class="badge badge-secondary ml-1" style="font-size:.65rem;">Renewal</span>';

                marker.bindTooltip(
                    '<b>' + biz.name + '</b><br>' + biz.barangay,
                    { direction: 'top', opacity: 0.92 }
                );
                marker.bindPopup(
                    '<div style="min-width:220px;">' +
                    '<b>' + biz.name + '</b>' + regBadge + street +
                    '<hr class="my-1" style="border-top:1px solid #e9ecef;">' +
                    '<table style="font-size:.8rem;width:100%;">' +
                    '<tr><td class="text-muted">Entity No.</td>' +
                    '<td style="text-align:right;"><b>' + (biz.entity_no || '—') + '</b></td></tr>' +
                    '<tr><td class="text-muted">Industry</td>' +
                    '<td style="text-align:right;">' + (biz.industry || '—') + '</td></tr>' +
                    '<tr><td class="text-muted">MSME Class</td>' +
                    '<td style="text-align:right;">' + (biz.category || '—') + '</td></tr>' +
                    '<tr><td class="text-muted">Barangay</td>' +
                    '<td style="text-align:right;">' + biz.barangay + '</td></tr>' +
                    '</table></div>'
                );
            });
        }).fail(function () {
            $badge.text('failed to load');
        });
    }

    function buildBreakdownPopup(row, highlight) {
        var maxN = 1;
        $.each(row.categories, function (_, n) { if (n > maxN) maxN = n; });

        var html = '<div style="min-width:230px;">' +
                   '<b>' + row.barangay + '</b> &middot; <span class="text-muted">' +
                   fmt(row.total) + ' MSME' + (row.total !== 1 ? 's' : '') + '</span>' +
                   '<table class="brgy-breakdown mt-1">';

        CATEGORIES.forEach(function (cat) {
            var n = row.categories[cat] || 0;
            if (n === 0) return;
            var color = sectorColor(cat);
            var isSel = (highlight === cat) || (!highlight && dominantCategory(row) === cat);
            html += '<tr>' +
                    '<td style="width:42%;white-space:nowrap;">' +
                    (isSel ? '<b>' : '') + cat + (isSel ? '</b>' : '') + '</td>' +
                    '<td style="width:12%;text-align:right;"><b>' + fmt(n) + '</b></td>' +
                    '<td style="padding-left:8px;">' +
                    '<span class="breakdown-bar" style="width:' +
                    Math.round((n / maxN) * 100) + '%;background:' + color + ';"></span>' +
                    '</td></tr>';
        });

        html += '</table></div>';
        return html;
    }

    // ── Economic Risk Map ──────────────────────────────────────────────
    function initRiskMap() {
        mapRisk = L.map('mapRisk', {
            scrollWheelZoom: false
        }).setView([10.4824, 123.4183], 12);
        baseTile(mapRisk);

        BARANGAYS.forEach(function (b) {
            L.circleMarker([b[1], b[2]], {
                radius: 4, color: '#adb5bd', weight: 1,
                fillColor: '#adb5bd', fillOpacity: .9
            }).addTo(mapRisk)
              .bindTooltip(b[0], { direction: 'top', opacity: .85 });
        });
    }

    function loadRisk(calamityId) {
        var params = { action: 'economic_risk' };
        if (calamityId) params.calamity_id = calamityId;

        $.getJSON(HANDLER, params, function (res) {
            if (res.status !== 'success') {
                console.error('economic_risk error:', res.message);
                return;
            }
            riskData = res.data || [];
            renderRiskSelector(res.calamities || [], calamityId || 0);
            renderRiskStats(res);
            renderRiskLegend();
            renderRiskMarkers();
        }).fail(function (xhr) {
            console.error('economic_risk request failed:', xhr.statusText);
        });
    }

    // Calamity dropdown: "Select calamity" placeholder + real events only.
    function renderRiskSelector(calamities, selected) {
        var $sel = $('#riskCalamity');
        if (!$sel.length) return;
        if ($sel.find('option[value!=""]').length !== calamities.length) {
            var html = '<option value="" disabled>Select Calamity</option>';
            calamities.forEach(function (c) {
                html += '<option value="' + c.id + '">' + $('<span>').text(c.name).html() +
                        ' (' + $('<span>').text(c.calamity_type).html() + ')</option>';
            });
            if (!calamities.length) {
                html = '<option value="" disabled>No calamity events</option>';
            }
            $sel.html(html);
        }
        $sel.val(selected || '');
        $sel.prop('disabled', calamities.length === 0);
    }

    function riskColor(level) {
        switch (level) {
            case 'Critical': return '#dc3545';
            case 'High':     return '#fd7e14';
            case 'Moderate': return '#ffc107';
            default:         return '#28a745';
        }
    }

    function renderRiskStats(res) {
        var lv = res.levels || {};
        $('#riskCriticalCount').text(fmt(lv.Critical || 0));
        $('#riskHighCount').text(fmt(lv.High || 0));
        $('#riskTotalAreas').text(fmt(riskData.length));
        $('#riskBadge').text((res.calamity_name || 'All calamities') + ' \u00b7 ' +
                              fmt(res.total_msmes || 0) + ' MSME' +
                              ((res.total_msmes || 0) !== 1 ? 's' : '') + ' assessed');
    }

    var riskLegend = null;

    function renderRiskLegend() {
        var existing = mapRisk.getContainer().querySelector('.risk-legend-control');
        if (existing) existing.remove();
        var levels = [
            ['Critical', '#dc3545'],
            ['High', '#fd7e14'],
            ['Moderate', '#ffc107'],
            ['Low', '#28a745']
        ];
        var html = '<h6>Risk Level</h6>';
        levels.forEach(function (l) {
            html += '<div><span class="legend-dot" style="background:' + l[1] +
                    ';"></span>' + l[0] + '</div>';
        });
        riskLegend = L.control({ position: 'bottomright' });
        riskLegend.onAdd = function () {
            var div = L.DomUtil.create('div', 'map-legend risk-legend-control');
            div.innerHTML = html;
            return div;
        };
        riskLegend.addTo(mapRisk);
    }

    function renderRiskMarkers() {
        if (riskLayer) {
            mapRisk.removeLayer(riskLayer);
        }
        riskLayer = L.layerGroup().addTo(mapRisk);

        riskData.forEach(function (row) {
            var color = riskColor(row.risk_level);
            var radius = Math.max(9, 9 + 12 * Math.sqrt(row.risk_score));
            var mk = L.circleMarker([row.lat, row.lng], {
                radius: radius,
                color: color,
                weight: 2,
                fillColor: color,
                fillOpacity: .55
            }).addTo(riskLayer);

            mk.bindTooltip(
                '<b>' + row.barangay + '</b><br>' + row.risk_level + ' economic risk',
                { direction: 'top', opacity: .9 }
            );
            mk.bindPopup(buildRiskPopup(row));
        });
    }

    function buildRiskPopup(row) {
        var color = riskColor(row.risk_level);
        var html = '<div style="min-width:230px;">' +
                   '<b>' + row.barangay + '</b><br>' +
                   '<span class="badge msme-badge-unknown" style="background:' + color + ';color:#fff;">' +
                   row.risk_level + ' risk</span>' +
                   '<hr class="my-2" style="border-top:1px solid #e9ecef;">' +
                   '<table class="brgy-breakdown">' +
                   '<tr><td style="width:60%;">MSMEs (exposure)</td><td style="text-align:right;"><b>' +
                   fmt(row.business_count) + '</b></td></tr>' +
                   '<tr><td>Hazard level</td><td style="text-align:right;"><b>' +
                   row.hazard_label + ' (' + row.hazard_level + '/4)</b></td></tr>' +
                   '<tr><td>Affected businesses (history)</td><td style="text-align:right;"><b>' +
                   fmt(row.affected_count) + '</b></td></tr>' +
                   '<tr><td>Historical damage</td><td style="text-align:right;"><b>' +
                   fmt(row.total_damage) + '</b></td></tr>' +
                   '</table>' +
                   '<hr class="my-2" style="border-top:1px solid #e9ecef;">' +
                   '<div class="d-flex justify-content-between align-items-center">' +
                   '<span class="text-muted">Risk score</span>' +
                   '<b style="font-size:1.05rem;color:' + color + ';">' +
                   row.risk_score.toFixed(3) + '</b></div>' +
                   '</div>';
        return html;
    }

    // ── Economic Opportunity Map ───────────────────────────────────────
    var OPP_COMPONENTS = [
        ['commercial',     'Commercial potential'],
        ['growth',         'Growth momentum'],
        ['tourism',        'Tourism potential'],
        ['agriculture',    'Agriculture potential'],
        ['livelihood',     'Livelihood gap'],
        ['infrastructure', 'Infrastructure gap'],
        ['diversity',      'Sector diversity gap']
    ];

    function initOpportunityMap() {
        mapOpportunity = L.map('mapOpportunity', {
            scrollWheelZoom: false
        }).setView([10.4824, 123.4183], 12);
        baseTile(mapOpportunity);

        BARANGAYS.forEach(function (b) {
            L.circleMarker([b[1], b[2]], {
                radius: 4, color: '#adb5bd', weight: 1,
                fillColor: '#adb5bd', fillOpacity: .9
            }).addTo(mapOpportunity)
              .bindTooltip(b[0], { direction: 'top', opacity: .85 });
        });
    }

    function loadOpportunity() {
        $.getJSON(HANDLER, { action: 'economic_opportunity' }, function (res) {
            if (res.status !== 'success') {
                console.error('economic_opportunity error:', res.message);
                return;
            }
            oppData = res.data || [];
            renderOpportunityStats(res);
            renderOpportunityHighlights(res.highlights || []);
            renderOpportunityLegend();
            renderOpportunityMarkers();
        }).fail(function (xhr) {
            console.error('economic_opportunity request failed:', xhr.statusText);
        });
    }

    function oppColor(level) {
        switch (level) {
            case 'Very High': return '#198754';
            case 'High':     return '#8BC34A';
            case 'Moderate': return '#ffc107';
            default:         return '#6c757d';
        }
    }

    function renderOpportunityStats(res) {
        var lv = res.levels || {};
        $('#oppHighCount').text(fmt((lv['Very High'] || 0) + (lv.High || 0)));
        $('#oppTotalAreas').text(fmt(oppData.length));
        $('#oppBadge').text(fmt(oppData.length) + ' barangays assessed');
    }

    function renderOpportunityHighlights(highlights) {
        var html = '';
        highlights.forEach(function (h) {
            html += '<div class="highlight-item">' +
                    '<i class="material-icons">location_on</i>' +
                    '<div class="d-flex justify-content-between w-100">' +
                    '<span class="hi-label">' + h.label + '</span>' +
                    '<span class="hi-barangay">' + h.barangay + '</span>' +
                    '</div></div>';
        });
        $('#oppHighlights').html(html);
    }

    function renderOpportunityLegend() {
        var levels = [
            ['Very High', '#198754'],
            ['High', '#28a745'],
            ['Moderate', '#ffc107'],
            ['Low', '#6c757d']
        ];
        var html = '<h6>Opportunity Level</h6>';
        levels.forEach(function (l) {
            html += '<div><span class="legend-dot" style="background:' + l[1] +
                    ';"></span>' + l[0] + '</div>';
        });
        var legend = L.control({ position: 'bottomright' });
        legend.onAdd = function () {
            var div = L.DomUtil.create('div', 'map-legend');
            div.innerHTML = html;
            return div;
        };
        legend.addTo(mapOpportunity);
    }

    function renderOpportunityMarkers() {
        if (oppLayer) {
            mapOpportunity.removeLayer(oppLayer);
        }
        oppLayer = L.layerGroup().addTo(mapOpportunity);

        oppData.forEach(function (row) {
            var color = oppColor(row.level);
            var radius = Math.max(9, 9 + 12 * Math.sqrt(row.score));
            var mk = L.circleMarker([row.lat, row.lng], {
                radius: radius,
                color: color,
                weight: 2,
                fillColor: color,
                fillOpacity: .55
            }).addTo(oppLayer);

            mk.bindTooltip(
                '<b>' + row.barangay + '</b><br>' + row.level + ' opportunity',
                { direction: 'top', opacity: .9 }
            );
            mk.bindPopup(buildOpportunityPopup(row));
        });
    }

    function buildOpportunityPopup(row) {
        var color = oppColor(row.level);
        var bars = '';
        OPP_COMPONENTS.forEach(function (c) {
            var v = row.components[c[0]] || 0;
            bars += '<tr><td style="width:52%;white-space:nowrap;">' + c[1] + '</td>' +
                    '<td style="width:12%;text-align:right;"><b>' + v.toFixed(2) + '</b></td>' +
                    '<td style="padding-left:8px;"><span class="breakdown-bar" style="width:' +
                    Math.round(v * 100) + '%;background:' + color + ';"></span></td></tr>';
        });

        var html = '<div style="min-width:240px;">' +
                   '<b>' + row.barangay + '</b><br>' +
                   '<span class="badge msme-badge-unknown" style="background:' + color + ';color:#fff;">' +
                   row.level + ' opportunity</span>' +
                   '<hr class="my-2" style="border-top:1px solid #e9ecef;">' +
                   '<table class="brgy-breakdown mb-0">' +
                   '<tr><td style="width:52%;">MSMEs</td><td style="text-align:right;"><b>' +
                   fmt(row.total) + '</b></td></tr>' +
                   '<tr><td>New registrations</td><td style="text-align:right;"><b>' +
                   fmt(row.new) + '</b></td></tr>' +
                   '<tr><td>Population</td><td style="text-align:right;"><b>' +
                   fmt(row.population) + '</b></td></tr>' +
                   '</table>' +
                   '<hr class="my-2" style="border-top:1px solid #e9ecef;">' +
                   '<div class="text-muted" style="font-size:.7rem;font-weight:600;' +
                   'text-transform:uppercase;letter-spacing:.04em;">Opportunity drivers</div>' +
                   '<table class="brgy-breakdown mt-1">' + bars + '</table>' +
                   '<hr class="my-2" style="border-top:1px solid #e9ecef;">' +
                   '<div class="d-flex justify-content-between align-items-center">' +
                   '<span class="text-muted">Opportunity score</span>' +
                   '<b style="color:' + color + ';">' + row.score.toFixed(3) + '</b></div>' +
                   '</div>';
        return html;
    }

// ── Area / street search ──────────────────────────────────────────
    var areaSearchTimer = null;

    function renderBarangayList(filter) {
        var q = (filter || '').toLowerCase();
        var html = '';
        BARANGAYS.forEach(function (b) {
            if (q && b[0].toLowerCase().indexOf(q) === -1) return;
            html += '<div class="area-match-item" data-type="barangay" ' +
                    'data-barangay="' + $('<span>').text(b[0]).html() + '" ' +
                    'data-street="">' +
                    '<i class="material-icons ami-icon">location_city</i>' +
                    '<span>' + $('<span>').text(b[0]).html() + '</span>' +
                    '<span class="ami-type">Barangay</span>' +
                    '</div>';
        });
        return html;
    }

    function initAreaSearch() {
        $('#areaSearch').on('focus', function () {
            var q = $.trim(this.value);
            if (q.length < 2) {
                var html = renderBarangayList('');
                if (html) {
                    $('#areaMatches').html(html).removeClass('d-none');
                    bindMatchClicks();
                }
            }
        });

        $('#areaSearch').on('input', function () {
            clearTimeout(areaSearchTimer);
            var q = $.trim(this.value);
            if (q.length < 1) {
                var html = renderBarangayList('');
                if (html) {
                    $('#areaMatches').html(html).removeClass('d-none');
                    bindMatchClicks();
                } else {
                    $('#areaMatches').addClass('d-none').empty();
                }
                return;
            }
            var localHtml = renderBarangayList(q);
            if (q.length < 2) {
                if (localHtml) {
                    $('#areaMatches').html(localHtml).removeClass('d-none');
                    bindMatchClicks();
                } else {
                    $('#areaMatches').addClass('d-none').empty();
                }
                return;
            }
            areaSearchTimer = setTimeout(function () {
                $.getJSON(HANDLER, { action: 'area_search', q: q }, function (res) {
                    var streetHtml = '';
                    if (res.status === 'success') {
                        res.matches.forEach(function (m) {
                            if (m.type !== 'street') return;
                            streetHtml += '<div class="area-match-item" data-type="street" ' +
                                    'data-barangay="' + $('<span>').text(m.barangay).html() + '" ' +
                                    'data-street="' + $('<span>').text(m.street || '').html() + '" ' +
                                    'data-lat="' + (m.lat || '') + '" ' +
                                    'data-lng="' + (m.lng || '') + '">' +
                                    '<i class="material-icons ami-icon">streetview</i>' +
                                    '<span>' + $('<span>').text(m.label).html() + '</span>' +
                                    '<span class="ami-type">Street</span>' +
                                    '</div>';
                        });
                    }
                    var combined = localHtml + streetHtml;
                    if (combined) {
                        $('#areaMatches').html(combined).removeClass('d-none');
                        bindMatchClicks();
                    } else {
                        $('#areaMatches').addClass('d-none').empty();
                    }
                }).fail(function () {
                    if (localHtml) {
                        $('#areaMatches').html(localHtml).removeClass('d-none');
                        bindMatchClicks();
                    } else {
                        $('#areaMatches').addClass('d-none').empty();
                    }
                });
            }, 250);
        });

        $('#areaSearchClear').on('click', function () {
            clearTimeout(areaSearchTimer);
            $('#areaSearch').val('');
            $('#areaMatches').addClass('d-none').empty();
            $('#areaSummaryWrap').addClass('d-none');
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('#areaMatches, #areaSearch').length) {
                $('#areaMatches').addClass('d-none');
            }
        });
    }

    function bindMatchClicks() {
        $('#areaMatches .area-match-item').off('click').on('click', function () {
            var $this = $(this);
            var match = {
                type:     $this.data('type'),
                barangay: $this.data('barangay'),
                street:   $this.data('street'),
                lat:      $this.data('lat'),
                lng:      $this.data('lng')
            };
            $('#areaSearch').val(match.street ? match.street + ' \u00b7 ' + match.barangay : match.barangay);
            $('#areaMatches').addClass('d-none').empty();
            loadAreaSummary(match);

            if (match.type === 'street' && match.street) {
                // Geocode the exact street using Nominatim
                var query = match.street + ', ' + match.barangay + ', San Carlos City, Negros Occidental, Philippines';
                $('#searchLocating').show();
                $.getJSON('https://nominatim.openstreetmap.org/search', {
                    q: query,
                    format: 'json',
                    limit: 1,
                    countrycodes: 'ph'
                }).done(function (results) {
                    $('#searchLocating').hide();
                    if (results && results.length > 0) {
                        flyToCoords([parseFloat(results[0].lat), parseFloat(results[0].lon)], match.barangay);
                    } else {
                        // Fallback: try without barangay in query
                        $('#searchLocating').show();
                        $.getJSON('https://nominatim.openstreetmap.org/search', {
                            q: match.street + ', San Carlos City, Philippines',
                            format: 'json',
                            limit: 1,
                            countrycodes: 'ph'
                        }).done(function (r2) {
                            $('#searchLocating').hide();
                            if (r2 && r2.length > 0) {
                                flyToCoords([parseFloat(r2[0].lat), parseFloat(r2[0].lon)], match.barangay);
                            } else {
                                flyToBarangay(match.barangay);
                            }
                        }).fail(function () { $('#searchLocating').hide(); flyToBarangay(match.barangay); });
                    }
                }).fail(function () { $('#searchLocating').hide(); flyToBarangay(match.barangay); });
            } else if (match.lat && match.lng) {
                flyToCoords([parseFloat(match.lat), parseFloat(match.lng)], match.barangay);
            } else {
                flyToBarangay(match.barangay);
            }
        });
    }

    function loadAreaSummary(match) {
        var params = { action: 'area_summary', barangay: match.barangay };
        if (match.street) params.street = match.street;

        $('#asSubtitle').text('Loading…');
        $('#areaSummaryWrap').removeClass('d-none');

        $.getJSON(HANDLER, params, function (res) {
            if (res.status !== 'success') {
                $('#asSubtitle').text(res.message || 'No data');
                return;
            }
            renderAreaSummary(res);
        }).fail(function (xhr) {
            console.error('area_summary request failed:', xhr.statusText);
            $('#asSubtitle').text('Request failed');
        });
    }

    function renderAreaSummary(res) {
        var title = res.barangay;
        if (res.street) title += ' \u00b7 ' + res.street;
        $('#asTitle').text(title);
        $('#asSubtitle').text(
            (res.source === 'scims' ? 'SCIMS registry' : 'Local database') +
            ' \u00b7 Classification by workforce (DTI bands)'
        );

        var risk = res.economic_risk || {};
        $('#asRiskBadge').html(
            '<span class="badge msme-badge-unknown" style="background:' +
            (risk.color || '#6c757d') + ';color:#fff;font-size:.72rem;">' +
            (risk.level || '—') + ' economic risk</span>'
        );

        $('#asTotal').text(fmt(res.total));
        var cls = res.classification || {};
        $('#asMicro').text(fmt(cls.Micro || 0));
        $('#asSmall').text(fmt(cls.Small || 0));
        $('#asMedium').text(fmt(cls.Medium || 0));
        $('#asLarge').text(fmt(cls.Large || 0));

        $('#asTopIndustry').text(
            (res.top_industry || '—') +
            (res.top_industry ? ' (' + fmt(res.top_industry_count || 0) + ')' : '')
        );

        var act = res.economic_activity || {};
        $('#asNew').text(fmt(act.new || 0));

        var indHtml = '';
        (act.industries || []).forEach(function (ind) {
            indHtml += '<li>' + $('<span>').text(ind.name).html() +
                       ' <b>' + fmt(ind.count) + '</b></li>';
        });
        $('#asIndustries').html(indHtml || '<li class="text-muted">—</li>');

        var sectorHtml = '';
        CATEGORIES.forEach(function (cat) {
            var n = (act.sectors || {})[cat] || 0;
            if (n === 0) return;
            var pct = res.total ? Math.round(n / res.total * 100) : 0;
            sectorHtml += '<tr>' +
                          '<td style="width:44%;white-space:nowrap;">' + $('<span>').text(cat).html() + '</td>' +
                          '<td style="width:10%;text-align:right;"><b>' + fmt(n) + '</b></td>' +
                          '<td style="padding-left:8px;"><span class="breakdown-bar" style="width:' +
                          pct + '%;background:' + sectorColor(cat) + ';"></span></td></tr>';
        });
        $('#asSectors').html(sectorHtml || '<tr><td colspan="3" class="text-muted small">No businesses recorded</td></tr>');
    }

    // ── Fly to searched barangay ────────────────────────────────────────
    function flyToCoords(coords, barangayName) {
        if (!coords || !coords[0] || !coords[1]) {
            flyToBarangay(barangayName);
            return;
        }

        var maps = [
            { map: mapHotspot,     id: 'pane-hotspot' },
            { map: mapDist,        id: 'pane-distribution' },
            { map: mapRisk,        id: 'pane-risk' },
            { map: mapOpportunity, id: 'pane-opportunity' }
        ];

        maps.forEach(function (entry) {
            if ($('#' + entry.id).hasClass('active') || $('#' + entry.id).hasClass('show')) {
                var m = entry.map;
                if (!m) return;
                m.flyTo(coords, 18, { duration: 1.5 });

                // Drop a temporary search pin
                if (m._searchMarker) { m.removeLayer(m._searchMarker); }
                m._searchMarker = L.marker(coords, {
                    icon: L.divIcon({
                        className: '',
                        html: '<div style="background:#b91c1c;width:14px;height:14px;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,0.4);"></div>',
                        iconSize: [14, 14],
                        iconAnchor: [7, 7]
                    })
                }).addTo(m);
                setTimeout(function () {
                    if (m._searchMarker) { m.removeLayer(m._searchMarker); m._searchMarker = null; }
                }, 6000);

                var marker = null;
                var minDist = Infinity;
                m.eachLayer(function (layer) {
                    if (layer instanceof L.CircleMarker && layer.getLatLng) {
                        var d = Math.abs(layer.getLatLng().lat - coords[0]) +
                                Math.abs(layer.getLatLng().lng - coords[1]);
                        if (d < minDist && layer.getPopup()) {
                            minDist = d;
                            marker = layer;
                        }
                    }
                });
                if (marker && minDist < 0.01) {
                    setTimeout(function () { marker.openPopup(); }, 1600);
                }
            }
        });
    }

    function flyToBarangay(name) {
        var coords = null;
        for (var i = 0; i < BARANGAYS.length; i++) {
            if (BARANGAYS[i][0].toLowerCase() === name.toLowerCase()) {
                coords = [BARANGAYS[i][1], BARANGAYS[i][2]];
                break;
            }
        }
        if (!coords) return;

        var maps = [
            { map: mapHotspot,  id: 'pane-hotspot' },
            { map: mapDist,     id: 'pane-distribution' },
            { map: mapRisk,     id: 'pane-risk' },
            { map: mapOpportunity, id: 'pane-opportunity' }
        ];

        maps.forEach(function (entry) {
            if ($('#' + entry.id).hasClass('active') || $('#' + entry.id).hasClass('show')) {
                var m = entry.map;
                if (!m) return;
                m.flyTo(coords, 15, { duration: 1.2 });

                m.eachLayer(function (layer) {
                    if (layer instanceof L.CircleMarker &&
                        layer.getLatLng &&
                        Math.abs(layer.getLatLng().lat - coords[0]) < 0.0001 &&
                        Math.abs(layer.getLatLng().lng - coords[1]) < 0.0001 &&
                        layer.getPopup()) {
                        setTimeout(function () { layer.openPopup(); }, 1300);
                    }
                });
            }
        });
    }

}(jQuery));