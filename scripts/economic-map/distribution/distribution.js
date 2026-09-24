(function ($, EconomicMap) {
    'use strict';

    var shared = EconomicMap.shared;
    var mapDist = null;
    var distData = [];
    var distLayer = null;
    var msmeLayer = null;
    var activeCategory = 'all';
    var distPieChart = null;
    var distCatTotals = {};
    var distSectorCounts = {};

    function categories() {
        return shared.state.categories;
    }

    function initDistMap() {
        mapDist = L.map('mapDistribution', {
            scrollWheelZoom: false
        }).setView([10.4824, 123.4183], 12);
        shared.setMap('distribution', mapDist);
        shared.baseTile(mapDist);

        shared.constants.BARANGAYS.forEach(function (b) {
            L.circleMarker([b[1], b[2]], {
                radius: 4, color: '#adb5bd', weight: 1,
                fillColor: '#adb5bd', fillOpacity: .9
            }).addTo(mapDist)
              .bindTooltip(b[0], { direction: 'top', opacity: .85 });
        });

        renderCategoryChips();
    }

    function loadSectors(done) {
        renderCategoryChips();
        $.getJSON(shared.constants.HANDLER, { action: 'sectors' }, function (res) {
            if (res.status === 'success' && Array.isArray(res.sectors) && res.sectors.length) {
                shared.state.categories = res.sectors.map(shared.normSector);
                categories().forEach(function (cat) { shared.sectorColor(cat); });
            } else {
                shared.state.categories = shared.constants.FALLBACK_SECTORS.slice();
            }
            renderCategoryChips();
            distSectorCounts = {};
            renderCategoryTotals({});
        }).fail(function (xhr) {
            console.error('sectors request failed, using fallback list:', xhr.statusText);
            shared.state.categories = shared.constants.FALLBACK_SECTORS.slice();
            distSectorCounts = {};
            renderCategoryChips();
            renderCategoryTotals({});
        }).always(function () {
            if (typeof done === 'function') done();
        });
    }

    function loadDistribution() {
        $.getJSON(shared.constants.HANDLER, { action: 'msme_distribution' }, function (res) {
            if (res.status !== 'success') {
                console.error('msme_distribution error:', res.message);
                $('#distBadge').text('failed to load registered MSMEs');
                return;
            }
            var normCats = {};
            if (res.categories) {
                Object.keys(res.categories).forEach(function (cat) {
                    var key = shared.normSector(cat);
                    normCats[key] = (normCats[key] || 0) + (res.categories[cat] || 0);
                    if (categories().indexOf(key) === -1) {
                        categories().push(key);
                        shared.sectorColor(key);
                    }
                });
                res.categories = normCats;
            }
            (res.data || []).forEach(function (row) {
                if (!row.categories) return;
                var nc = {};
                Object.keys(row.categories).forEach(function (k) {
                    var key = shared.normSector(k);
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
            $('#distTotal').text(shared.fmt(res.total) + ' MSME' + (res.total !== 1 ? 's' : ''));
            $('#distBadge').text(shared.fmt(res.total) + ' registered MSME' + (res.total !== 1 ? 's' : ''));
            if (!res.total) {
                $('#distBadge').text('No registered MSMEs found');
            }
        }).fail(function (xhr) {
            console.error('msme_distribution request failed:', xhr.statusText);
            $('#distBadge').text('failed to load registered MSMEs');
        });
    }

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
        var q = shared.normSector($.trim($('#distSectorSearch').val() || ''));
        var visible = 0;
        $('#distChips .sector-row').each(function () {
            var match = !q || shared.normSector($(this).data('cat')).indexOf(q) !== -1;
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

        categories().forEach(function (cat) {
            var color = shared.sectorColor(cat);
            var n = distSectorCounts[cat];
            var countTxt = (n == null) ? '—' : shared.fmt(n);
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
        categories().forEach(function (cat) {
            var color = shared.sectorColor(cat);
            html += '<div class="d-flex justify-content-between align-items-center mb-1">' +
                    '<span><span class="legend-dot" style="background:' + color + ';"></span>' +
                    $('<span>').text(cat).html() + '</span>' +
                    '<b>' + shared.fmt(cats[cat] || 0) + '</b></div>';
        });
        $('#distCategoryTotals').html(html);

        var legendHtml = '';
        categories().forEach(function (cat) {
            legendHtml += '<div><span class="legend-dot" style="background:' + shared.sectorColor(cat) +
                          ';"></span>' + $('<span>').text(cat).html() + '</div>';
        });
        $('#distLegend').html(legendHtml);
    }

    function renderDistPieChart(cats) {
        distCatTotals = cats;

        var labels       = [];
        var values       = [];
        var colors       = [];
        var borderColors = [];
        var total        = 0;

        categories().forEach(function (cat) {
            var n = cats[cat] || 0;
            total += n;
            if (n > 0) {
                labels.push(cat);
                values.push(n);
                colors.push(shared.sectorColor(cat) + 'cc');
                borderColors.push(shared.sectorColor(cat));
            }
        });

        $('#distPieBadge').text(shared.fmt(total) + ' MSME' + (total !== 1 ? 's' : '') + ' across ' + labels.length + ' sectors');

        var legendHtml = '<div class="row">';
        labels.forEach(function (lbl, i) {
            var n   = values[i];
            var pct = total > 0 ? ((n / total) * 100).toFixed(1) : '0.0';
            var col = shared.sectorColor(lbl);
            legendHtml +=
                '<div class="col-md-6 mb-3 pie-legend-item">' +
                  '<div class="d-flex justify-content-between align-items-start mb-1" style="gap:10px;">' +
                    '<span class="small" style="font-weight:600;line-height:1.45;flex:1;min-width:0;word-break:break-word;">' +
                      '<span class="legend-dot" style="background:' + col + ';"></span>' + $('<span>').text(lbl).html() +
                    '</span>' +
                    '<span class="small text-muted" style="white-space:nowrap;flex-shrink:0;margin-left:8px;">' + shared.fmt(n) + ' <b>(' + pct + '%)</b></span>' +
                  '</div>' +
                  '<div style="height:5px;border-radius:3px;background:#f1f3f5;margin-top:4px;">' +
                    '<div style="height:5px;border-radius:3px;width:' + pct + '%;background:' + col + ';transition:width .4s;"></div>' +
                  '</div>' +
                '</div>';
        });
        legendHtml += '</div>';
        $('#distPieLegend').html(legendHtml);

        var ctx = document.getElementById('distPieChart');
        if (!ctx) return;

        if (distPieChart) {
            distPieChart.destroy();
            distPieChart = null;
            shared.state.distributionPieChart = null;
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
                                return ' ' + context.label + ': ' + shared.fmt(context.parsed) + ' (' + pct + '%)';
                            }
                        }
                    }
                },
                animation: { duration: 600 }
            }
        });
        shared.state.distributionPieChart = distPieChart;
    }

    function updateDistPieChart() {
        if (!distPieChart) return;

        var dataset = distPieChart.data.datasets[0];
        var labels  = distPieChart.data.labels;

        if (activeCategory === 'all') {
            dataset.backgroundColor = labels.map(function (lbl) {
                return shared.sectorColor(lbl) + 'cc';
            });
            dataset.borderWidth = labels.map(function () { return 2; });
            $('#distPieLegend .pie-legend-item').css('opacity', '1');
        } else {
            dataset.backgroundColor = labels.map(function (lbl) {
                return lbl === activeCategory ? shared.sectorColor(lbl) : shared.sectorColor(lbl) + '33';
            });
            dataset.borderWidth = labels.map(function (lbl) {
                return lbl === activeCategory ? 3 : 1;
            });
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
                var dom = shared.dominantCategory(row);
                var domColor = shared.sectorColor(dom);
                var radius = Math.max(6, 5 + 9 * Math.sqrt(row.total / maxTotal));
                var mk = L.circleMarker([row.lat, row.lng], {
                    radius: radius,
                    color: domColor,
                    weight: 2,
                    fillColor: domColor,
                    fillOpacity: .5
                }).addTo(distLayer);

                mk.bindTooltip(
                    '<b>' + row.barangay + '</b><br>' + shared.fmt(row.total) +
                    ' MSME' + (row.total !== 1 ? 's' : '') + ' &middot; ' + dom,
                    { direction: 'top', opacity: .9 }
                );
                mk.bindPopup(buildBreakdownPopup(row));
            } else {
                var n = row.categories[activeCategory] || 0;
                if (n === 0) return;
                var actColor = shared.sectorColor(activeCategory);
                var r2 = Math.max(6, 5 + 9 * Math.sqrt(n / (maxCat[activeCategory] || 1)));
                var mk2 = L.circleMarker([row.lat, row.lng], {
                    radius: r2,
                    color: actColor,
                    weight: 2,
                    fillColor: actColor,
                    fillOpacity: .55
                }).addTo(distLayer);

                mk2.bindTooltip(
                    '<b>' + row.barangay + '</b><br>' + shared.fmt(n) + ' MSME' +
                    (n !== 1 ? 's' : '') + ' &middot; ' + activeCategory,
                    { direction: 'top', opacity: .9 }
                );
                mk2.bindPopup(buildBreakdownPopup(row, activeCategory));
            }
        });
    }

    function clearMsmeLayer() {
        if (msmeLayer) {
            mapDist.removeLayer(msmeLayer);
            msmeLayer = null;
        }
    }

    function renderSectorMsmes(sector) {
        clearMsmeLayer();

        var color  = shared.sectorColor(sector);
        var $badge = $('#distBadge');
        $badge.text('loading registered MSMEs…');

        $.getJSON(shared.constants.HANDLER, { action: 'sector_businesses', sector: sector }, function (res) {
            if (res.status !== 'success') {
                console.error('sector_businesses error:', res.message);
                $badge.text(res.message || 'error');
                return;
            }

            $badge.text(shared.fmt(res.total) + ' registered MSME' + (res.total !== 1 ? 's' : '') +
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
                   shared.fmt(row.total) + ' MSME' + (row.total !== 1 ? 's' : '') + '</span>' +
                   '<table class="brgy-breakdown mt-1">';

        categories().forEach(function (cat) {
            var n = row.categories[cat] || 0;
            if (n === 0) return;
            var color = shared.sectorColor(cat);
            var isSel = (highlight === cat) || (!highlight && shared.dominantCategory(row) === cat);
            html += '<tr>' +
                    '<td style="width:42%;white-space:nowrap;">' +
                    (isSel ? '<b>' : '') + cat + (isSel ? '</b>' : '') + '</td>' +
                    '<td style="width:12%;text-align:right;"><b>' + shared.fmt(n) + '</b></td>' +
                    '<td style="padding-left:8px;">' +
                    '<span class="breakdown-bar" style="width:' +
                    Math.round((n / maxN) * 100) + '%;background:' + color + ';"></span>' +
                    '</td></tr>';
        });

        html += '</table></div>';
        return html;
    }

    shared.register('distribution', {
        initMap: initDistMap,
        loadSectors: loadSectors,
        load: loadDistribution
    });

}(jQuery, window.EconomicMap));
