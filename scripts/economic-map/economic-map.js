/**
 * economic-map.js
 * Renders the Economic Hotspot Map and the MSME Distribution Map
 * using Leaflet. Data comes from server-side/business-handler.php.
 *
 * Dependencies: jQuery 3+, Leaflet 1.9.x
 */
(function ($) {
    'use strict';

    var HANDLER = '../../server-side/economic-map-handler.php';

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

    var CATEGORIES = [
        'Retail',
        'Food services',
        'Manufacturing',
        'Agriculture-related',
        'Transportation',
        'Tourism',
        'Construction',
        'Other services'
    ];

    var CAT_COLORS = {
        'Retail':              '#007bff',
        'Food services':       '#fd7e14',
        'Manufacturing':       '#6f42c1',
        'Agriculture-related': '#28a745',
        'Transportation':      '#17a2b8',
        'Tourism':             '#e83e8c',
        'Construction':        '#795548',
        'Other services':      '#6c757d'
    };

    // Hotspot severity levels (relative to the top barangay)
    var HOTSPOT_LEVELS = [
        { min: 0.60, label: 'Very High', color: '#7b1fa2' },
        { min: 0.30, label: 'High',      color: '#dc3545' },
        { min: 0.15, label: 'Moderate',  color: '#fd7e14' },
        { min: 0.00, label: 'Low',       color: '#ffc107' }
    ];

    var TILE_URL = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    var TILE_ATTR = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';

    var mapHotspot = null;
    var mapDist = null;
    var mapRisk = null;
    var mapOpportunity = null;
    var distData = [];       // per-barangay rows
    var distLayer = null;
    var activeCategory = 'all';
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
        loadDistribution();
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
                if (href === '#pane-hotspot') mapHotspot.invalidateSize();
                else if (href === '#pane-distribution') mapDist.invalidateSize();
                else if (href === '#pane-risk') mapRisk.invalidateSize();
                else if (href === '#pane-opportunity') mapOpportunity.invalidateSize();
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
            maxZoom: 18,
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
        var best = 'Other services';
        var bestN = -1;
        $.each(row.categories, function (cat, n) {
            if (n > bestN) { best = cat; bestN = n; }
        });
        return best;
    }

    // ── Economic Hotspot Map ───────────────────────────────────────────
    function initHotspotMap() {
        mapHotspot = L.map('mapHotspot', {
            scrollWheelZoom: false
        }).setView([10.4824, 123.4183], 12);
        baseTile(mapHotspot);

        // Static context dots for every barangay
        BARANGAYS.forEach(function (b) {
            L.circleMarker([b[1], b[2]], {
                radius: 4, color: '#adb5bd', weight: 1,
                fillColor: '#adb5bd', fillOpacity: .9
            }).addTo(mapHotspot)
              .bindTooltip(b[0], { direction: 'top', opacity: .85 });
        });
    }

    function loadHotspots() {
        $.getJSON(HANDLER, { action: 'economic_hotspots' }, function (res) {
            if (res.status !== 'success') {
                console.error('economic_hotspots error:', res.message);
                return;
            }

            var max = 1;
            res.mapped.forEach(function (r) { if (r.count > max) max = r.count; });

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
            });

            $('#hotspotTotal').text(fmt(res.total));
            $('#hotspotBadge').text(fmt(res.total) + ' MSME' + (res.total !== 1 ? 's' : '') +
                                  (res.source === 'scims' ? ' \u00b7 SCIMS' : ''));
            $('#hotspotWithBusiness').text(fmt(res.mapped.length));

            var top = res.mapped[0];
            $('#hotspotTopBrgy').text(top ? top.barangay : '—');

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

    function loadDistribution() {
        $.getJSON(HANDLER, { action: 'msme_distribution' }, function (res) {
            if (res.status !== 'success') {
                console.error('msme_distribution error:', res.message);
                return;
            }
            distData = res.data || [];
            renderCategoryChips();
            renderCategoryTotals(res.categories || {});
            renderDistMarkers();
            $('#distTotal').text(fmt(res.total) + ' MSME' + (res.total !== 1 ? 's' : ''));
            $('#distBadge').text(fmt(res.total) + ' MSME' + (res.total !== 1 ? 's' : '') +
                                 (res.source === 'scims' ? ' \u00b7 SCIMS' : ''));
        }).fail(function (xhr) {
            console.error('msme_distribution request failed:', xhr.statusText);
        });
    }

    function renderCategoryChips() {
        var $wrap = $('#distChips');
        // Keep the "All sectors" chip as the first child.
        $wrap.find('button[data-cat!="all"]').remove();
        CATEGORIES.forEach(function (cat) {
            var color = CAT_COLORS[cat];
            var $chip = $('<button>', {
                'class': 'cat-chip' + (activeCategory === cat ? ' active' : ''),
                'data-cat': cat,
                style: 'background:' + color + ';color:#fff;'
            }).html('<i class="fas fa-circle" style="font-size:7px;"></i>' + cat)
              .appendTo($wrap);

            $chip.on('click', function () {
                activeCategory = (activeCategory === cat) ? 'all' : cat;
                renderCategoryChips();
                renderDistMarkers();
            });
        });
        $wrap.find('button[data-cat="all"]').toggleClass('active', activeCategory === 'all')
                                            .toggleClass('dimmed', activeCategory !== 'all');
    }

    function renderCategoryTotals(cats) {
        var html = '';
        CATEGORIES.forEach(function (cat) {
            var color = CAT_COLORS[cat];
            html += '<div class="d-flex justify-content-between align-items-center mb-1">' +
                    '<span><span class="legend-dot" style="background:' + color + ';"></span>' +
                    cat + '</span>' +
                    '<b>' + fmt(cats[cat] || 0) + '</b></div>';
        });
        $('#distCategoryTotals').html(html);

        var legendHtml = '';
        CATEGORIES.forEach(function (cat) {
            legendHtml += '<div><span class="legend-dot" style="background:' + CAT_COLORS[cat] +
                          ';"></span>' + cat + '</div>';
        });
        $('#distLegend').html(legendHtml);
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
                var radius = Math.max(6, 5 + 9 * Math.sqrt(row.total / maxTotal));
                var mk = L.circleMarker([row.lat, row.lng], {
                    radius: radius,
                    color: CAT_COLORS[dom],
                    weight: 2,
                    fillColor: CAT_COLORS[dom],
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
                var r2 = Math.max(6, 5 + 9 * Math.sqrt(n / (maxCat[activeCategory] || 1)));
                var mk2 = L.circleMarker([row.lat, row.lng], {
                    radius: r2,
                    color: CAT_COLORS[activeCategory],
                    weight: 2,
                    fillColor: CAT_COLORS[activeCategory],
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
            var color = CAT_COLORS[cat];
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

    function renderRiskSelector(calamities, selected) {
        var $sel = $('#riskCalamity');
        if (!$sel.length) return;
        if ($sel.find('option').length - 1 !== calamities.length) {
            var html = '<option value="0">All calamities</option>';
            calamities.forEach(function (c) {
                html += '<option value="' + c.id + '">' + c.name +
                        ' (' + c.calamity_type + ')</option>';
            });
            $sel.html(html).val(selected);
        }
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
                type: $this.data('type'),
                barangay: $this.data('barangay'),
                street: $this.data('street'),
                lat: $this.data('lat'),
                lng: $this.data('lng')
            };
            $('#areaSearch').val(match.street ? match.street + ' \u00b7 ' + match.barangay : match.barangay);
            $('#areaMatches').addClass('d-none').empty();
            loadAreaSummary(match);
            if (match.lat && match.lng) {
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
                          '<td style="width:44%;white-space:nowrap;">' + cat + '</td>' +
                          '<td style="width:10%;text-align:right;"><b>' + fmt(n) + '</b></td>' +
                          '<td style="padding-left:8px;"><span class="breakdown-bar" style="width:' +
                          pct + '%;background:' + CAT_COLORS[cat] + ';"></span></td></tr>';
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
            { map: mapHotspot,  id: 'pane-hotspot' },
            { map: mapDist,     id: 'pane-distribution' },
            { map: mapRisk,     id: 'pane-risk' },
            { map: mapOpportunity, id: 'pane-opportunity' }
        ];

        maps.forEach(function (entry) {
            if ($('#' + entry.id).hasClass('active') || $('#' + entry.id).hasClass('show')) {
                var m = entry.map;
                if (!m) return;
                m.flyTo(coords, 17, { duration: 1.2 });

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
                if (marker) {
                    setTimeout(function () { marker.openPopup(); }, 1300);
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