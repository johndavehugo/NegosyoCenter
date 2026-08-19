/**
 * msme-dashboard.js
 * All stats come from server-side/business-handler.php.
 * No counts are calculated client-side.
 *
 * Dependencies: jQuery 3+, Chart.js 2.9.x
 */
(function ($) {
    'use strict';

    var HANDLER = '../../server-side/business-handler.php';

    // Chart.js colour palette — mirrors msme-badge-* colours in user_defined.css
    var CAT_COLORS = {
        Micro:   { bg: 'rgba(8,  66,152,.75)', bd: '#084298' },
        Small:   { bg: 'rgba(10, 54, 34,.75)', bd: '#0a3622' },
        Medium:  { bg: 'rgba(125,58,  0,.75)', bd: '#7d3a00' },
        Large:   { bg: 'rgba(59, 10,110,.75)', bd: '#3b0a6e' },
        Other:   { bg: 'rgba(108,117,125,.55)', bd: '#6c757d' }
    };

    var SECTOR_PALETTE = [
        'rgba(0,123,255,.72)','rgba(40,167,69,.72)','rgba(255,193,7,.85)',
        'rgba(220,53,69,.72)','rgba(111,66,193,.72)','rgba(23,162,184,.72)',
        'rgba(253,126,20,.72)','rgba(102,117,124,.72)',
        'rgba(52,133,89,.72)','rgba(214,140,10,.72)'
    ];

    // Chart instances — kept so we can destroy before redraw
    var chartClass  = null;
    var chartSector = null;

    // ── Bootstrap ──────────────────────────────────────────────────────
    $(function () {
        loadSectors();   // populate sector dropdown once
        loadStats();     // initial stats load

        $('#btnApply').on('click', loadStats);
        $('#btnReset').on('click', function () {
            $('#filterClassification, #filterSector, #filterStatus').val('');
            loadStats();
        });

        $('#exportCSV').on('click', function (e) {
            e.preventDefault();
            triggerExport('csv');
        });
        $('#exportPDF').on('click', function (e) {
            e.preventDefault();
            triggerExport('pdf');
        });
    });

    // ── Load sector dropdown (server returns distinct values) ──────────
    function loadSectors() {
        $.getJSON(HANDLER, { action: 'dashboard_sectors' }, function (res) {
            if (res.status !== 'success') return;
            var $sel = $('#filterSector');
            $sel.find('option:not(:first)').remove();
            $.each(res.sectors, function (_, s) {
                $sel.append($('<option>').val(s).text(s));
            });
        });
    }

    // ── Load stats from server ─────────────────────────────────────────
    function loadStats() {
        setLoading(true);

        var params = {
            action:         'dashboard_stats',
            classification: $('#filterClassification').val(),
            status:         $('#filterStatus').val(),
            sector:         $('#filterSector').val()
        };

        $.getJSON(HANDLER, params, function (res) {
            setLoading(false);
            if (res.status !== 'success') {
                console.error('dashboard_stats error:', res.message);
                return;
            }
            renderStatCards(res);
            renderClassChart(res.byCategory  || []);
            renderSectorChart(res.bySector   || []);
        }).fail(function (xhr) {
            setLoading(false);
            console.error('Stats request failed:', xhr.statusText);
        });
    }

    // ── Stat cards ─────────────────────────────────────────────────────
    function setLoading(on) {
        ['#statTotal','#statMicro','#statSmall','#statMedLg'].forEach(function (id) {
            var $el = $(id);
            if (on) { $el.addClass('loading-pulse').text('—'); }
            else    { $el.removeClass('loading-pulse'); }
        });
    }

    function renderStatCards(res) {
        // Build a label→count map from server's byCategory array
        var catMap = {};
        $.each(res.byCategory, function (_, row) {
            catMap[row.label] = parseInt(row.cnt, 10);
        });

        var medLg = (catMap['Medium'] || 0) + (catMap['Large'] || 0);

        $('#statTotal').text(fmt(res.total));
        $('#statMicro').text(fmt(catMap['Micro']  || 0));
        $('#statSmall').text(fmt(catMap['Small']  || 0));
        $('#statMedLg').text(fmt(medLg));
    }

    // ── Classification bar chart ───────────────────────────────────────
    function renderClassChart(byCategory) {
        var labels = [], values = [], bgs = [], bds = [];
        var total = 0;

        $.each(byCategory, function (_, row) {
            if (row.label === 'Other') return; // skip catch-all
            labels.push(row.label);
            var v = parseInt(row.cnt, 10);
            values.push(v);
            total += v;
            var c = CAT_COLORS[row.label] || CAT_COLORS.Other;
            bgs.push(c.bg);
            bds.push(c.bd);
        });

        $('#badgeClassTotal').text(fmt(total) + ' record' + (total !== 1 ? 's' : ''));

        if (chartClass) { chartClass.destroy(); chartClass = null; }

        var $wrap = $('#wrapChartClass');
        if (!labels.length) {
            $wrap.html('<div class="no-data-msg"><i class="material-icons">info_outline</i>No data for selected filters</div>');
            return;
        }
        $wrap.html('<canvas id="chartClassification" height="240"></canvas>');

        chartClass = new Chart(
            document.getElementById('chartClassification').getContext('2d'),
            {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Businesses',
                        data: values,
                        backgroundColor: bgs,
                        borderColor: bds,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    legend: { display: false },
                    scales: {
                        yAxes: [{
                            ticks: { beginAtZero: true, precision: 0,
                                     fontSize: 11, fontColor: '#6c757d' },
                            gridLines: { color: 'rgba(0,0,0,.05)' }
                        }],
                        xAxes: [{
                            ticks: { fontSize: 12, fontColor: '#495057', fontStyle: 'bold' },
                            gridLines: { display: false }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function (t) {
                                return ' ' + fmt(t.yLabel) + ' businesses';
                            }
                        }
                    }
                }
            }
        );
    }

    // ── Sector horizontal bar chart ────────────────────────────────────
    function renderSectorChart(bySector) {
        var labels = [], values = [], bgs = [];

        $.each(bySector, function (i, row) {
            labels.push(row.label);
            values.push(parseInt(row.cnt, 10));
            bgs.push(SECTOR_PALETTE[i % SECTOR_PALETTE.length]);
        });

        $('#badgeSectorTotal').text(labels.length + ' sector' + (labels.length !== 1 ? 's' : ''));

        if (chartSector) { chartSector.destroy(); chartSector = null; }

        var $wrap = $('#wrapChartSector');
        if (!labels.length) {
            $wrap.html('<div class="no-data-msg"><i class="material-icons">info_outline</i>No data for selected filters</div>');
            return;
        }

        // 28px per bar, min 180, max 340 — keeps chart balanced at any count
        var h = Math.min(340, Math.max(180, labels.length * 28));
        $wrap.html('<canvas id="chartSectors" height="' + h + '"></canvas>');

        chartSector = new Chart(
            document.getElementById('chartSectors').getContext('2d'),
            {
                type: 'horizontalBar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Businesses',
                        data: values,
                        backgroundColor: bgs,
                        borderWidth: 1,
                        barThickness: 18,      // fixed bar height in px
                        maxBarThickness: 22    // hard cap so bars never bloat
                    }]
                },
                options: {
                    responsive: true,
                    legend: { display: false },
                    scales: {
                        xAxes: [{
                            ticks: { beginAtZero: true, precision: 0,
                                     fontSize: 11, fontColor: '#6c757d' },
                            gridLines: { color: 'rgba(0,0,0,.05)' }
                        }],
                        yAxes: [{
                            ticks: {
                                fontSize: 11, fontColor: '#495057',
                                callback: function (v) {
                                    return v.length > 30 ? v.substring(0, 28) + '…' : v;
                                }
                            },
                            gridLines: { display: false }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function (t) {
                                return ' ' + fmt(t.xLabel) + ' businesses';
                            },
                            title: function (items, data) {
                                // Show full label (not truncated) in tooltip
                                return data.labels[items[0].index];
                            }
                        }
                    }
                }
            }
        );
    }

    // ── Export — server streams the file ──────────────────────────────
    function triggerExport(format) {
        var params = new URLSearchParams({
            action:         'export_report',
            classification: $('#filterClassification').val(),
            status:         $('#filterStatus').val(),
            sector:         $('#filterSector').val(),
            format:         format
        });
        // Open in a new tab — browser will trigger the file download
        window.open(HANDLER + '?' + params.toString(), '_blank');
    }

    // ── Utility ────────────────────────────────────────────────────────
    function fmt(n) {
        return parseInt(n, 10).toLocaleString('en-US');
    }

}(jQuery));
