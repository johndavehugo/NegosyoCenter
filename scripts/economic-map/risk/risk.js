(function ($, EconomicMap) {
    'use strict';

    var shared = EconomicMap.shared;
    var mapRisk = null;
    var riskData = [];
    var riskLayer = null;
    var riskLegend = null;

    function initRiskMap() {
        mapRisk = L.map('mapRisk', {
            scrollWheelZoom: false
        }).setView([10.4824, 123.4183], 12);
        shared.setMap('risk', mapRisk);
        shared.baseTile(mapRisk);

        shared.constants.BARANGAYS.forEach(function (b) {
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

        $.getJSON(shared.constants.HANDLER, params, function (res) {
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
        $('#riskCriticalCount').text(shared.fmt(lv.Critical || 0));
        $('#riskHighCount').text(shared.fmt(lv.High || 0));
        $('#riskTotalAreas').text(shared.fmt(riskData.length));
        $('#riskBadge').text((res.calamity_name || 'All calamities') + ' \u00b7 ' +
                              shared.fmt(res.total_msmes || 0) + ' MSME' +
                              ((res.total_msmes || 0) !== 1 ? 's' : '') + ' assessed');
    }

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
                   shared.fmt(row.business_count) + '</b></td></tr>' +
                   '<tr><td>Hazard level</td><td style="text-align:right;"><b>' +
                   row.hazard_label + ' (' + row.hazard_level + '/4)</b></td></tr>' +
                   '<tr><td>Affected businesses (history)</td><td style="text-align:right;"><b>' +
                   shared.fmt(row.affected_count) + '</b></td></tr>' +
                   '<tr><td>Historical damage</td><td style="text-align:right;"><b>' +
                   shared.fmt(row.total_damage) + '</b></td></tr>' +
                   '</table>' +
                   '<hr class="my-2" style="border-top:1px solid #e9ecef;">' +
                   '<div class="d-flex justify-content-between align-items-center">' +
                   '<span class="text-muted">Risk score</span>' +
                   '<b style="font-size:1.05rem;color:' + color + ';">' +
                   row.risk_score.toFixed(3) + '</b></div>' +
                   '</div>';
        return html;
    }

    shared.register('risk', {
        initMap: initRiskMap,
        load: loadRisk
    });

}(jQuery, window.EconomicMap));
