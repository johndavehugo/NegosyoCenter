(function ($, window) {
    'use strict';

    var EconomicMap = window.EconomicMap = window.EconomicMap || {};
    var shared = EconomicMap.shared = EconomicMap.shared || {};
    var state = shared.state = shared.state || {};
    var registry = shared.registry = shared.registry || {};

    var HANDLER = '../../api/routes.php/economic-map';
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
    var EXTRA_PALETTE = ['#0ea5e9', '#84cc16', '#f43f5e', '#8b5cf6', '#f59e0b', '#10b981', '#ec4899', '#6366f1'];
    var TILE_URL  = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
    var TILE_ATTR = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';

    shared.constants = shared.constants || {};
    shared.constants.HANDLER = HANDLER;
    shared.constants.BARANGAYS = BARANGAYS;
    shared.constants.FALLBACK_SECTORS = FALLBACK_SECTORS;
    shared.constants.TILE_URL = TILE_URL;
    shared.constants.TILE_ATTR = TILE_ATTR;

    state.categories = state.categories || FALLBACK_SECTORS.slice();
    state.categoryColors = state.categoryColors || $.extend({}, BASE_CAT_COLORS);
    state.maps = state.maps || {
        hotspot: null,
        distribution: null,
        risk: null,
        opportunity: null
    };
    state.distributionPieChart = state.distributionPieChart || null;

    shared.register = function (name, api) {
        registry[name] = api;
    };
    shared.get = function (name) {
        return registry[name];
    };
    shared.setMap = function (name, map) {
        state.maps[name] = map;
    };
    shared.getMap = function (name) {
        return state.maps[name];
    };

    function normSector(s) {
        return String(s == null ? '' : s).toUpperCase();
    }

    function sectorColor(sector) {
        var key = normSector(sector);
        if (state.categoryColors[key]) return state.categoryColors[key];
        var h = 0;
        for (var i = 0; i < key.length; i++) {
            h = (h * 31 + key.charCodeAt(i)) % 997;
        }
        var c = EXTRA_PALETTE[h % EXTRA_PALETTE.length];
        state.categoryColors[key] = c;
        return c;
    }

    function fmt(n) {
        return parseInt(n, 10).toLocaleString('en-US');
    }

    function baseTile(map) {
        L.tileLayer(TILE_URL, {
            maxZoom: 19,
            attribution: TILE_ATTR
        }).addTo(map);
    }

    function dominantCategory(row) {
        var best = state.categories[0] || 'OTHER COMMUNITY, SOCIAL AND PERSONAL SERVICE ACTIVITIES';
        var bestN = -1;
        $.each(row.categories, function (cat, n) {
            if (n > bestN) { best = cat; bestN = n; }
        });
        return best;
    }

    shared.normSector = normSector;
    shared.sectorColor = sectorColor;
    shared.fmt = fmt;
    shared.baseTile = baseTile;
    shared.dominantCategory = dominantCategory;

    function updatePageTitle() {
        var title = $('#emapTabs .nav-link.active').attr('data-page-title');
        if (!title) return;
        document.title = title;
        $('#economicMapTitle').text(title);
    }

    function syncActiveTab() {
        var hash = window.location.hash;
        showTabFromHash(hash);
        updatePageTitle();
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
        state.categories.forEach(function (cat) {
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

    function flyToCoords(coords, barangayName) {
        if (!coords || !coords[0] || !coords[1]) {
            flyToBarangay(barangayName);
            return;
        }

        var maps = [
            { map: state.maps.hotspot,     id: 'pane-hotspot' },
            { map: state.maps.distribution, id: 'pane-distribution' },
            { map: state.maps.risk,         id: 'pane-risk' },
            { map: state.maps.opportunity,  id: 'pane-opportunity' }
        ];

        maps.forEach(function (entry) {
            if ($('#' + entry.id).hasClass('active') || $('#' + entry.id).hasClass('show')) {
                var m = entry.map;
                if (!m) return;
                m.flyTo(coords, 18, { duration: 1.5 });

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
            { map: state.maps.hotspot,  id: 'pane-hotspot' },
            { map: state.maps.distribution, id: 'pane-distribution' },
            { map: state.maps.risk,     id: 'pane-risk' },
            { map: state.maps.opportunity, id: 'pane-opportunity' }
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

    shared.flyToBarangay = flyToBarangay;

    $(function () {
        var hotspot = shared.get('hotspot');
        var distribution = shared.get('distribution');
        var risk = shared.get('risk');
        var opportunity = shared.get('opportunity');

        hotspot.initMap();
        distribution.initMap();
        risk.initMap();
        opportunity.initMap();
        hotspot.load();
        distribution.loadSectors(function () {
            distribution.load();
        });
        risk.load();
        opportunity.load();
        initAreaSearch();
        syncActiveTab();
        syncSidebar();

        $('#riskCalamity').on('change', function () {
            risk.load(parseInt(this.value, 10) || 0);
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
            updatePageTitle();
            syncSidebar();
            var href = $(this).attr('href');
            setTimeout(function () {
                if (href === '#pane-hotspot') {
                    state.maps.hotspot.invalidateSize();
                } else if (href === '#pane-distribution') {
                    state.maps.distribution.invalidateSize();
                    if (state.distributionPieChart) { state.distributionPieChart.resize(); }
                } else if (href === '#pane-risk') {
                    state.maps.risk.invalidateSize();
                } else if (href === '#pane-opportunity') {
                    state.maps.opportunity.invalidateSize();
                }
            }, 200);
        });
    });

}(jQuery, window));
