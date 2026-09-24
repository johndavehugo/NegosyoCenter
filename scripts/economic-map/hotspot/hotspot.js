(function ($, EconomicMap) {
    'use strict';

    var shared = EconomicMap.shared;
    var HOTSPOT_LEVELS = [
        { min: 0.60, label: 'Very High', color: '#7b1fa2' },
        { min: 0.30, label: 'High',      color: '#dc3545' },
        { min: 0.15, label: 'Moderate',  color: '#fd7e14' },
        { min: 0.00, label: 'Low',       color: '#ffc107' }
    ];
    var mapHotspot = null;
    var hotspotMarkers = {};
    var hotspotRankSelected = null;
    var hotspotBusinessLayer = null;
    var hotspotLocationsBarangay = null;
    var hotspotLocationsRequest = 0;

    function hotspotLevel(count, max) {
        var ratio = count / (max || 1);
        for (var i = 0; i < HOTSPOT_LEVELS.length; i++) {
            if (ratio >= HOTSPOT_LEVELS[i].min) {
                return HOTSPOT_LEVELS[i];
            }
        }
        return { label: 'Low', color: '#ffc107' };
    }

    function escapeHtml(value) {
        return $('<div>').text(value == null ? '' : String(value)).html();
    }

    function barangayCenter(name) {
        var center = null;
        shared.constants.BARANGAYS.forEach(function (barangay) {
            if (barangay[0].toLowerCase() === String(name).toLowerCase()) {
                center = [barangay[1], barangay[2]];
            }
        });
        return center;
    }

    function removeHotspotBusinessLayer() {
        if (hotspotBusinessLayer && mapHotspot) {
            mapHotspot.removeLayer(hotspotBusinessLayer);
        }
        hotspotBusinessLayer = null;
    }

    function clearHotspotLocations() {
        hotspotLocationsRequest++;
        removeHotspotBusinessLayer();
        hotspotLocationsBarangay = null;
        $('#hotspotLocationsBadge').text('None');
        $('#hotspotLocationsStatus').text('Select a hotspot or barangay ranking to load its registered MSMEs.');
        $('#hotspotLocationsClear').prop('disabled', true);
    }

    function businessClassColor(value) {
        switch (String(value || '').toLowerCase()) {
            case 'micro': return '#28a745';
            case 'small': return '#fd7e14';
            case 'medium': return '#6f42c1';
            case 'large': return '#dc3545';
            default: return '#6c757d';
        }
    }

    function buildHotspotBusinessPopup(biz) {
        var name = escapeHtml(biz.name || 'Unnamed Business');
        var street = biz.street
            ? '<div class="hotspot-business-address"><i class="material-icons">location_on</i><span>' +
              escapeHtml(biz.street) + '</span></div>'
            : '';
        var entityNo = escapeHtml(biz.entity_no || '—');
        var industry = escapeHtml(biz.industry || '—');
        var barangay = escapeHtml(biz.barangay || '—');
        var registration = biz.reg_type
            ? (String(biz.reg_type).toUpperCase() === 'NEW' ? 'New' : 'Renewal')
            : '—';
        var businessClass = biz.category || 'Unknown';
        var classColor = businessClassColor(businessClass);

        return '<div class="hotspot-business-popup-content">' +
               '<div class="hotspot-business-header">' +
               '<div class="hotspot-business-heading">' +
               '<div class="hotspot-business-name">' + name + '</div>' + street +
               '</div>' +
               '<div class="hotspot-business-class-row">' +
               '<span class="badge hotspot-class-badge" style="background:' + classColor + ';color:#fff;">MSME Class: ' +
               escapeHtml(businessClass) + '</span>' +
               '</div>' +
               '</div>' +
               '<div class="hotspot-business-details">' +
               '<div class="hotspot-business-detail"><span>Entity No.</span><strong>' + entityNo + '</strong></div>' +
               '<div class="hotspot-business-detail"><span>Industry</span><strong>' + industry + '</strong></div>' +
               '<div class="hotspot-business-detail"><span>Registration</span><strong>' + registration + '</strong></div>' +
               '<div class="hotspot-business-detail"><span>Barangay</span><strong>' + barangay + '</strong></div>' +
               '</div></div>';
    }

    function loadHotspotLocations(name) {
        if (!name || !mapHotspot) return;
        if (hotspotLocationsBarangay === name && hotspotBusinessLayer) return;

        var requestId = ++hotspotLocationsRequest;
        removeHotspotBusinessLayer();
        hotspotLocationsBarangay = null;
        $('#hotspotLocationsBadge').text('Loading…');
        $('#hotspotLocationsStatus').text('Loading registered MSME locations for ' + name + '…');
        $('#hotspotLocationsClear').prop('disabled', true);

        $.getJSON(shared.constants.HANDLER, {
            action: 'barangay_businesses',
            barangay: name
        }).done(function (res) {
            if (requestId !== hotspotLocationsRequest) return;
            if (res.status !== 'success') {
                $('#hotspotLocationsBadge').text('Error');
                $('#hotspotLocationsStatus').text(res.message || 'Unable to load MSME locations.');
                $('#hotspotLocationsClear').prop('disabled', false);
                return;
            }

            var data = Array.isArray(res.data) ? res.data : [];
            hotspotBusinessLayer = L.layerGroup().addTo(mapHotspot);
            data.forEach(function (biz) {
                if (biz.lat == null || biz.lng == null) return;
                var marker = L.circleMarker([biz.lat, biz.lng], {
                    radius: 5,
                    color: '#1d4ed8',
                    weight: 1.5,
                    fillColor: '#60a5fa',
                    fillOpacity: 0.9
                }).addTo(hotspotBusinessLayer);
                var nameLabel = escapeHtml(biz.name || 'Unnamed Business');
                marker.bindTooltip('<b>' + nameLabel + '</b>', {
                    direction: 'top',
                    opacity: 0.92
                });
                marker.bindPopup(buildHotspotBusinessPopup(biz), {
                    className: 'hotspot-business-popup',
                    maxWidth: 430
                });
            });

            var count = typeof res.total === 'number' ? res.total : data.length;
            var source = res.source === 'scims' ? 'SCIMS registry' : 'Local database';
            hotspotLocationsBarangay = name;
            $('#hotspotLocationsBadge').text(shared.fmt(count));
            $('#hotspotLocationsStatus').text(
                name + ' · ' + shared.fmt(count) + ' registered MSME' +
                (count !== 1 ? 's' : '') + ' shown · ' + source
            );
            $('#hotspotLocationsClear').prop('disabled', false);

            var center = barangayCenter(name);
            if (center) {
                mapHotspot.flyTo(center, 16, { duration: 1.2 });
            }
        }).fail(function (xhr) {
            if (requestId !== hotspotLocationsRequest) return;
            console.error('barangay_businesses request failed:', xhr.statusText);
            $('#hotspotLocationsBadge').text('Error');
            $('#hotspotLocationsStatus').text('Unable to load MSME locations for ' + name + '.');
            $('#hotspotLocationsClear').prop('disabled', false);
        });
    }

    function initHotspotMap() {
        mapHotspot = L.map('mapHotspot', {
            scrollWheelZoom: false,
            zoomControl: false,
            preferCanvas: true
        }).setView([10.4824, 123.4183], 12);
        shared.setMap('hotspot', mapHotspot);
        shared.baseTile(mapHotspot);
        L.control.zoom({ position: 'topleft' }).addTo(mapHotspot);
        L.control.scale({ imperial: false, position: 'bottomright' }).addTo(mapHotspot);

        shared.constants.BARANGAYS.forEach(function (b) {
            L.circleMarker([b[1], b[2]], {
                radius: 5, color: '#fff', weight: 1.5,
                fillColor: '#94a3b8', fillOpacity: .6
            }).addTo(mapHotspot)
              .bindTooltip(b[0], { direction: 'top', opacity: .9, className: 'emap-tooltip' });
        });

        $('#hotspotLocationsClear')
            .off('click.hotspotLocations')
            .on('click.hotspotLocations', clearHotspotLocations);
    }

    function loadHotspots() {
        $.getJSON(shared.constants.HANDLER, { action: 'economic_hotspots' }, function (res) {
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
                    '<b>' + r.barangay + '</b><br>' + shared.fmt(r.count) +
                    ' MSME' + (r.count !== 1 ? 's' : '') +
                    ' &middot; ' + lvl.label,
                    { direction: 'top', opacity: .9 }
                );
                layer.bindPopup(
                    '<div style="min-width:150px;">' +
                    '<b>' + r.barangay + '</b><br>' +
                    '<span class="badge msme-badge-unknown">' + lvl.label + ' hotspot</span><br>' +
                    '<span style="font-size:1.1rem;font-weight:700;color:#dc3545;">' +
                    shared.fmt(r.count) + '</span> registered MSMEs' +
                    '</div>'
                );
                hotspotMarkers[r.barangay] = layer;
                layer.on('click', function () {
                    loadHotspotLocations(r.barangay);
                });
            });

            $('#hotspotTotal').text(shared.fmt(res.total));
            $('#hotspotBadge').text(shared.fmt(res.total) + ' registered MSME' + (res.total !== 1 ? 's' : ''));
            $('#hotspotWithBusiness').text(shared.fmt(res.mapped.length));

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
        html += '<div><span class="legend-dot" style="background:#60a5fa;"></span>Individual MSME</div>';
        var legend = L.control({ position: 'bottomright' });
        legend.onAdd = function () {
            var div = L.DomUtil.create('div', 'map-legend');
            div.innerHTML = html;
            return div;
        };
        legend.addTo(mapHotspot);
    }

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
                    '<span class="opt-count">' + shared.fmt(r.count) + '</span>' +
                    '<i class="material-icons opt-check">check</i>' +
                    '</button>';
        });
        $list.html(html);

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

        $list.find('.modern-dd-opt').off('click.hotspotRank').on('click.hotspotRank', function () {
            var name = $(this).data('barangay');
            hotspotRankSelected = name;
            $list.find('.modern-dd-opt').removeClass('selected');
            $(this).addClass('selected');
            $('#hotspotRankBtnText').text(name);
            closeRankDropdown();

            var rank = 0, rec = null;
            mapped.forEach(function (r, i) {
                if (r.barangay === name) { rank = i + 1; rec = r; }
            });
            if (rec) {
                var lvl = hotspotLevel(rec.count, max);
                $('#hotspotRankDetail').html(
                    '<span class="rank-num">' + rank + '</span>' +
                    '<span class="rank-info"><b>' + $('<span>').text(rec.barangay).html() + '</b><br>' +
                    shared.fmt(rec.count) + ' MSME' + (rec.count !== 1 ? 's' : '') +
                    ' &middot; <span style="font-weight:700;color:' + lvl.color + ';">' + lvl.label + '</span></span>'
                ).removeClass('d-none');
            }
            shared.flyToBarangay(name);
            loadHotspotLocations(name);
            var marker = hotspotMarkers[name];
            if (marker) {
                setTimeout(function () { marker.openPopup(); }, 1300);
            }
        });
    }

    shared.register('hotspot', {
        initMap: initHotspotMap,
        load: loadHotspots
    });

}(jQuery, window.EconomicMap));
