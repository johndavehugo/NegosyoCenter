(function ($, EconomicMap) {
    'use strict';

    var shared = EconomicMap.shared;
    var OPP_COMPONENTS = [
        ['commercial',     'Commercial potential'],
        ['growth',         'Growth momentum'],
        ['tourism',        'Tourism potential'],
        ['agriculture',    'Agriculture potential'],
        ['livelihood',     'Livelihood gap'],
        ['infrastructure', 'Infrastructure gap'],
        ['diversity',      'Sector diversity gap']
    ];
    var mapOpportunity = null;
    var oppData = [];
    var oppLayer = null;

    function initOpportunityMap() {
        mapOpportunity = L.map('mapOpportunity', {
            scrollWheelZoom: false
        }).setView([10.4824, 123.4183], 12);
        shared.setMap('opportunity', mapOpportunity);
        shared.baseTile(mapOpportunity);

        shared.constants.BARANGAYS.forEach(function (b) {
            L.circleMarker([b[1], b[2]], {
                radius: 4, color: '#adb5bd', weight: 1,
                fillColor: '#adb5bd', fillOpacity: .9
            }).addTo(mapOpportunity)
              .bindTooltip(b[0], { direction: 'top', opacity: .85 });
        });
    }

    function loadOpportunity() {
        $.getJSON(shared.constants.HANDLER, { action: 'economic_opportunity' }, function (res) {
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
        $('#oppHighCount').text(shared.fmt((lv['Very High'] || 0) + (lv.High || 0)));
        $('#oppTotalAreas').text(shared.fmt(oppData.length));
        $('#oppBadge').text(shared.fmt(oppData.length) + ' barangays assessed');
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
                   shared.fmt(row.total) + '</b></td></tr>' +
                   '<tr><td>New registrations</td><td style="text-align:right;"><b>' +
                   shared.fmt(row.new) + '</b></td></tr>' +
                   '<tr><td>Population</td><td style="text-align:right;"><b>' +
                   shared.fmt(row.population) + '</b></td></tr>' +
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

    shared.register('opportunity', {
        initMap: initOpportunityMap,
        load: loadOpportunity
    });

}(jQuery, window.EconomicMap));
