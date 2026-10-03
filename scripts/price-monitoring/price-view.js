/* ============================================================
   price-view.js  v5
   Public Price Monitor — category grid + establishment prices
   + Price History tab (mock data, frontend-only for now)
   ============================================================ */

var commoditiesCache     = [];
var currentCategoryItems = [];

/* ── API base ─────────────────────────────────────────────── */
function getApiBase() {
    var path = window.location.pathname;
    var base = '/NegosyoCenter/api/routes.php/';
    if (path.indexOf('/NegosyoCenter') !== -1) {
        base = path.substring(0, path.indexOf('/NegosyoCenter')) + '/NegosyoCenter/api/routes.php/';
    }
    return window.location.origin + base;
}

/* ── Peso formatter ───────────────────────────────────────── */
function formatPeso(value) {
    var amount = Number(value);
    if (value === null || value === undefined || value === '' || isNaN(amount)) return '-';
    return '\u20B1' + amount.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

/* ── HTML escape ──────────────────────────────────────────── */
function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;')
        .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

/* ══════════════════════════════════════════════════════════════
   CATEGORY GRID
   ══════════════════════════════════════════════════════════════ */
function loadCategories() {
    fetch(getApiBase() + 'price-monitoring?action=commodity_categories')
        .then(function (r) { return r.json(); })
        .then(function (result) {
            if (result.status !== 'success') throw new Error(result.message || 'Unable to load categories.');

            var grid = $('#categoryGrid');
            grid.empty();
            var cats = result.data || [];

            if (!cats.length) {
                grid.html('<div class="col-12 text-center text-muted"><p>No categories available yet.</p></div>');
                return;
            }

            cats.forEach(function (cat) {
                var catId = cat.category_id || cat.id;
                var count = commoditiesCache.filter(function (item) {
                    var cid = item.category_id || item.cat_id || item.commodity_category_id;
                    return String(cid) === String(catId);
                }).length;

                var countHtml = count > 0
                    ? '<div class="category-count"><span class="badge">' + count + ' item' + (count > 1 ? 's' : '') + '</span></div>'
                    : '';

                grid.append(
                    '<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">' +
                    '<div class="category-card" tabindex="0" role="button" ' +
                    'data-category-id="' + catId + '" ' +
                    'data-category-name="' + escapeHtml(cat.category_name || cat.name) + '">' +
                    '<div class="category-icon"><i class="fas fa-box-open"></i></div>' +
                    '<div class="category-name">' + escapeHtml(cat.category_name || cat.name) + '</div>' +
                    '<div class="category-agency">' + escapeHtml(cat.agency_name || '') + '</div>' +
                    countHtml + '</div></div>'
                );
            });
        })
        .catch(function (err) {
            console.error('[PRICE VIEW] categories:', err);
            $('#categoryGrid').html('<div class="col-12 text-center text-danger"><p>' + (err.message || 'Unable to load categories.') + '</p></div>');
        });
}

function loadCommodities() {
    fetch(getApiBase() + 'commodity?action=public')
        .then(function (r) { return r.json(); })
        .then(function (result) {
            if (result.status !== 'success') throw new Error(result.message || 'Unable to load commodities.');
            commoditiesCache = result.data || [];
            loadCategories();
        })
        .catch(function (err) {
            console.error('[PRICE VIEW] commodities:', err);
            loadCategories();
        });
}

/* ══════════════════════════════════════════════════════════════
   COMMODITY LIST
   ══════════════════════════════════════════════════════════════ */
function renderCommodityList(items) {
    var list = $('#commodityList');
    list.empty();

    if (!items || !items.length) {
        var searching = $('#commoditySearch').val().trim() !== '';
        list.append('<div class="commodity-empty">' +
            (searching ? 'No commodities match your search.' : 'No commodities available in this category yet.') +
            '</div>');
        return;
    }

    items.forEach(function (item) {
        var meta = [];
        if (item.brand_name)      meta.push(item.brand_name);
        if (item.unit_of_measure) meta.push(item.unit_of_measure);

        var commId   = item.commodity_id || item.id;
        var prodName = item.product_name || item.name || '-';
        var srpVal   = item.srp || item.price || 0;

        var row = $('<div>', {
            class: 'commodity-row',
            'data-commodity-id': commId,
            'data-product-name': prodName,
            'data-srp':  srpVal,
            'data-unit': item.unit_of_measure || ''
        });

        var info = $('<div>', { class: 'commodity-info' });
        info.append($('<div>', { class: 'commodity-name', text: prodName }));
        if (meta.length) info.append($('<div>', { class: 'commodity-meta', text: meta.join(' \u00B7 ') }));

        row.append(info);
        row.append('<div class="commodity-arrow"><small style="font-weight:normal;font-size:.85rem;">View Establishments <i class="fas fa-chevron-right ml-1"></i></small></div>');
        list.append(row);
    });
}

function openCategoryModal(catId, catName) {
    currentCategoryItems = commoditiesCache.filter(function (item) {
        var cid = item.category_id || item.cat_id || item.commodity_category_id;
        return String(cid) === String(catId);
    });
    $('#modalCategoryTitle').text(catName);
    $('#commoditySearch').val('');
    renderCommodityList(currentCategoryItems);
    $('#categoryCommoditiesModal').modal('show');
}

/* ══════════════════════════════════════════════════════════════
   MODAL TAB SWITCHER
   Simple, self-contained — no Bootstrap tab JS involved.
   ══════════════════════════════════════════════════════════════ */
function switchModalTab(tabKey) {
    /* Buttons */
    $('.modal-tab-btn').each(function () {
        var isActive = $(this).data('modal-tab') === tabKey;
        $(this).toggleClass('active', isActive).attr('aria-selected', String(isActive));
    });
    /* Panes */
    $('#pane-est, #pane-hist').removeClass('active');
    $('#pane-' + tabKey).addClass('active');

    /* If switching to history and chart hasn't been drawn yet — draw it */
    if (tabKey === 'hist' && !_phChartDrawn) {
        _phChartDrawn = true;
        phLoad(_phActiveRange);
    }
}

/* ══════════════════════════════════════════════════════════════
   ESTABLISHMENT DETAIL MODAL
   ══════════════════════════════════════════════════════════════ */
function openEstablishmentDetailModal(commodityId, productName, srp, unit) {
    /* Header */
    $('#modalCommodityTitle').text(productName);
    $('#modalCommoditySubtitle').text(unit ? 'Unit: ' + unit : '');

    /* Reset est pane */
    var tbody = $('#establishmentListBody');
    tbody.html('<tr><td colspan="5" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin mr-2"></i>Loading establishments...</td></tr>');
    $('#estCountBadge').hide();
    $('#srpReferenceBar').hide();
    $('#estLegend').hide();
    $('#estSummary').hide();

    /* Always open on tab 1; reset history state */
    switchModalTab('est');
    _phResetState();

    $('#categoryCommoditiesModal').modal('hide');
    $('#establishmentDetailModal').modal('show');

    fetch(getApiBase() + 'price-monitoring?action=commodity_establishments&commodity_id=' + commodityId)
        .then(function (r) { return r.json(); })
        .then(function (result) {
            tbody.empty();

            if (result.status === 'success' && result.data && result.data.length > 0) {
                var rows     = result.data;
                var prices   = rows.map(function (r) { return parseFloat(r.prevailing_price) || 0; });
                var minPrice = Math.min.apply(null, prices);
                var maxPrice = Math.max.apply(null, prices);
                var avgPrice = prices.reduce(function (a, b) { return a + b; }, 0) / prices.length;

                var srpNum = parseFloat(srp) || 0;
                if (srpNum > 0) { $('#srpReferenceValue').text(formatPeso(srpNum)); $('#srpReferenceBar').show(); }

                $('#estCountBadge').text(rows.length + ' establishment' + (rows.length > 1 ? 's' : '')).show();
                $('#estLegend').show();

                rows.sort(function (a, b) {
                    return (parseFloat(a.prevailing_price) || 0) - (parseFloat(b.prevailing_price) || 0);
                });

                rows.forEach(function (est, i) {
                    var dSrp  = est.srp != null ? parseFloat(est.srp) : srpNum;
                    var dPrev = est.prevailing_price != null ? parseFloat(est.prevailing_price) : dSrp;
                    var name  = est.establishment_name || est.name || 'Establishment';

                    var isLowest  = dPrev === minPrice;
                    var isHighest = dPrev === maxPrice && rows.length > 1;
                    var aboveSrp  = srpNum > 0 && dPrev > srpNum && !isHighest;

                    var rc = isLowest ? 'est-row-lowest' : (isHighest ? 'est-row-highest' : (aboveSrp ? 'est-row-above-srp' : ''));
                    var rk = i === 0 ? 'est-rank-1' : (i === rows.length - 1 && rows.length > 1 ? 'est-rank-last' : '');

                    var vsBadge = '';
                    if (srpNum > 0) {
                        var diff = dPrev - srpNum;
                        var pct  = ((diff / srpNum) * 100).toFixed(1);
                        if (Math.abs(diff) < 0.005) vsBadge = '<span class="est-vs-badge est-vs-at">At SRP</span>';
                        else if (diff < 0) vsBadge = '<span class="est-vs-badge est-vs-below">\u2212' + Math.abs(pct) + '%</span>';
                        else vsBadge = '<span class="est-vs-badge est-vs-above">+' + pct + '%</span>';
                    } else {
                        vsBadge = '<span class="est-vs-badge est-vs-at">\u2014</span>';
                    }

                    var tr = $('<tr class="' + rc + '">');
                    tr.append('<td class="pl-4"><span class="est-rank ' + rk + '">' + (i + 1) + '</span></td>');
                    tr.append('<td><strong>' + escapeHtml(name) + '</strong>' +
                        (est.branch ? '<br><small class="text-muted">' + escapeHtml(est.branch) + '</small>' : '') + '</td>');
                    tr.append('<td class="text-right text-muted">' + formatPeso(dSrp) + '</td>');
                    tr.append('<td class="text-right pr-4 font-weight-bold">' + formatPeso(dPrev) + '</td>');
                    tr.append('<td class="text-center pr-4">' + vsBadge + '</td>');
                    tbody.append(tr);
                });

                $('#estAvgPrice').text(formatPeso(avgPrice));
                $('#estLowestPrice').text(formatPeso(minPrice));
                $('#estHighestPrice').text(formatPeso(maxPrice));
                $('#estSummary').show();

            } else {
                tbody.html('<tr><td colspan="5" class="text-center text-muted py-4">No establishment details found for this item.</td></tr>');
            }
        })
        .catch(function (err) {
            console.error('Error fetching establishments:', err);
            tbody.html('<tr><td colspan="5" class="text-center text-danger py-4">Failed to load establishment data.</td></tr>');
        });
}

/* ══════════════════════════════════════════════════════════════
   PRICE HISTORY — mock data + Chart.js (frontend-only for now)
   ══════════════════════════════════════════════════════════════ */

var _phChart       = null;
var _phMockData    = [];
var _phActiveRange = '30d';
var _phChartDrawn  = false;   /* true once the chart has been rendered for the current modal open */

var _MONTHS = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

function _phFmtFull(d) {
    return _MONTHS[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear() + ' ' + d.toTimeString().slice(0, 5);
}
function _phFmtShort(d) { return _MONTHS[d.getMonth()] + ' ' + d.getDate(); }
function _phFmtMY(d)    { return _MONTHS[d.getMonth()] + ' ' + d.getFullYear(); }

/* Generate 365 mock daily snapshots */
function _phGenerateMock() {
    var data  = [];
    var today = new Date();
    var srp   = 52.00;
    var prev  = 49.50;

    for (var i = 364; i >= 0; i--) {
        var d = new Date(today);
        d.setDate(today.getDate() - i);
        d.setHours(8 + Math.floor(Math.random() * 4), Math.floor(Math.random() * 60), 0, 0);

        srp  = Math.max(40, Math.min(68, srp  + (Math.random() - 0.48) * 0.38));
        prev = Math.max(38, Math.min(66, prev + (Math.random() - 0.50) * 0.42));
        if (prev > srp + 2) prev = srp + Math.random() * 1.5;

        data.push({
            date:      d,
            dateFull:  _phFmtFull(d),
            dateShort: _phFmtShort(d),
            dateMY:    _phFmtMY(d),
            srp:       parseFloat(srp.toFixed(2)),
            prev:      parseFloat(prev.toFixed(2)),
            status:    Math.random() > 0.08 ? 'ACTIVE' : 'INACTIVE'
        });
    }
    return data;
}

function _phFilter(range) {
    if (range === '7d')  return _phMockData.slice(-7);
    if (range === '30d') return _phMockData.slice(-30);
    if (range === '90d') return _phMockData.slice(-90);
    return _phMockData;
}

/* Reset history UI state (called each time a new commodity modal opens) */
function _phResetState() {
    _phChartDrawn  = false;
    _phActiveRange = '30d';

    if (_phChart) { _phChart.destroy(); _phChart = null; }

    /* Reset pills */
    $('.ph-pill').removeClass('active');
    $('.ph-pill[data-range="30d"]').addClass('active');

    /* Show loading overlay, hide states */
    $('#phLoading').show();
    $('#phEmptyState').hide();
    $('#phBuildingNotice').hide();
    $('#phTableWrap').hide();

    /* Reset stat cards */
    $('#phStatCurrent, #phStatLow, #phStatHigh, #phStatChange, #phStatCount').text('—');
    $('#phStatChange').removeClass('ph-up ph-down');
    $('#phTableBody').empty();
}

/* Main chart + table render */
function phLoad(range) {
    _phActiveRange = range;

    /* Show loading overlay while we work */
    $('#phLoading').show();
    $('#phEmptyState').hide();
    $('#phBuildingNotice').hide();
    $('#phTableWrap').hide();

    if (_phChart) { _phChart.destroy(); _phChart = null; }

    /* requestAnimationFrame lets the browser paint the overlay first */
    requestAnimationFrame(function () {
        setTimeout(function () {

            var data = _phFilter(range);

            /* Empty */
            if (!data || data.length === 0) {
                $('#phLoading').hide();
                $('#phEmptyMsg').text('No price records in this period.');
                $('#phEmptyState').show();
                return;
            }

            /* Too few points for a chart */
            if (data.length < 3) {
                $('#phLoading').hide();
                $('#phBuildingNotice').show();
                _phRenderTable(data);
                $('#phTableWrap').show();
                return;
            }

            /* ── X-axis labels ── */
            var labels = data.map(function (d, i) {
                if (range === 'all') return (i % 30 === 0) ? d.dateMY : '';
                if (range === '90d') return (i % 15 === 0) ? d.dateShort : '';
                return d.dateShort;
            });

            var srpVals  = data.map(function (d) { return d.srp;  });
            var prevVals = data.map(function (d) { return d.prev; });

            /* ── Gradient fill ── */
            var canvas = document.getElementById('phChart');
            var ctx    = canvas.getContext('2d');
            var chartH = canvas.offsetHeight || 220;

            var grad = ctx.createLinearGradient(0, 0, 0, chartH);
            grad.addColorStop(0,   'rgba(2,128,144,0.20)');
            grad.addColorStop(0.65,'rgba(2,128,144,0.04)');
            grad.addColorStop(1,   'rgba(2,128,144,0)');

            var ptR = (range === 'all' || range === '90d') ? 0 : (range === '30d' ? 2.5 : 4);

            /* ── Draw chart ── */
            _phChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'SRP',
                            data: srpVals,
                            borderColor: '#3b82f6',
                            borderDash: [6, 4],
                            borderWidth: 1.6,
                            pointRadius: ptR * 0.7,
                            pointHoverRadius: 5,
                            pointBackgroundColor: '#3b82f6',
                            backgroundColor: 'transparent',
                            tension: 0.4,
                            order: 2
                        },
                        {
                            label: 'Prevailing Price',
                            data: prevVals,
                            borderColor: '#028090',
                            borderWidth: 2.8,
                            pointRadius: ptR,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#028090',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 1.5,
                            backgroundColor: grad,
                            fill: true,
                            tension: 0.4,
                            order: 1
                        }
                    ]
                },
                options: {
                    /* responsive:false + animation:false = stable, no-jump chart */
                    responsive: false,
                    maintainAspectRatio: false,
                    animation: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#f1f5f9',
                            bodyColor: '#94a3b8',
                            borderColor: '#1e293b',
                            borderWidth: 1,
                            padding: { x: 14, y: 10 },
                            cornerRadius: 10,
                            displayColors: true,
                            boxWidth: 10,
                            boxHeight: 10,
                            callbacks: {
                                title: function (items) {
                                    var idx = items[0].dataIndex;
                                    return data[idx] ? data[idx].dateFull : items[0].label;
                                },
                                label: function (item) {
                                    return '  ' + item.dataset.label + ':  \u20B1' +
                                        Number(item.raw).toLocaleString('en-PH', { minimumFractionDigits: 2 });
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            border: { display: false },
                            ticks: {
                                color: '#9ca3af',
                                font: { size: 10, family: 'Inter, sans-serif' },
                                maxRotation: 0, autoSkip: true,
                                maxTicksLimit: range === '7d' ? 7 : (range === '30d' ? 10 : 12)
                            }
                        },
                        y: {
                            position: 'right',
                            grid: { color: '#f1f5f9' },
                            border: { display: false },
                            ticks: {
                                color: '#9ca3af',
                                font: { size: 10, family: 'Inter, sans-serif' },
                                maxTicksLimit: 5,
                                callback: function (v) { return '\u20B1' + v.toFixed(2); }
                            }
                        }
                    }
                }
            });

            /* Force size to match CSS box exactly */
            var chartW = canvas.offsetWidth || 600;
            _phChart.resize(chartW, chartH);

            /* Hide loading overlay — chart is now painted */
            $('#phLoading').hide();

            /* ── Stat cards ── */
            var low     = Math.min.apply(null, prevVals);
            var high    = Math.max.apply(null, prevVals);
            var current = prevVals[prevVals.length - 1];
            var first   = prevVals[0];
            var change  = current - first;
            var chgPct  = first !== 0 ? ((change / first) * 100).toFixed(1) : '0.0';

            $('#phStatCurrent').text(formatPeso(current));
            $('#phStatLow').text(formatPeso(low));
            $('#phStatHigh').text(formatPeso(high));
            $('#phStatCount').text(data.length);

            var $chg = $('#phStatChange');
            if (Math.abs(change) < 0.01) {
                $chg.text('No change').removeClass('ph-up ph-down');
            } else if (change > 0) {
                $chg.html('\u2191 +\u20B1' + Math.abs(change).toFixed(2) +
                    ' <small style="font-size:.7rem;font-weight:500;">(+' + chgPct + '%)</small>')
                    .removeClass('ph-down').addClass('ph-up');
            } else {
                $chg.html('\u2193 \u2212\u20B1' + Math.abs(change).toFixed(2) +
                    ' <small style="font-size:.7rem;font-weight:500;">(\u2212' + Math.abs(chgPct) + '%)</small>')
                    .removeClass('ph-up').addClass('ph-down');
            }

            /* ── History table ── */
            _phRenderTable(data);
            $('#phTableWrap').show();

        }, 60);
    });
}

/* Render history table rows, newest first */
function _phRenderTable(data) {
    var tbody    = $('#phTableBody');
    var reversed = data.slice().reverse();
    tbody.empty();

    reversed.forEach(function (row, i) {
        var prevRow  = reversed[i + 1];
        var changeTd = '<span class="ph-chg-flat">\u2014</span>';

        if (prevRow) {
            var diff = row.prev - prevRow.prev;
            if (Math.abs(diff) < 0.01) {
                changeTd = '<span class="ph-chg-flat">= same</span>';
            } else if (diff > 0) {
                changeTd = '<span class="ph-chg-up">\u2191 +\u20B1' + Math.abs(diff).toFixed(2) + '</span>';
            } else {
                changeTd = '<span class="ph-chg-down">\u2193 \u2212\u20B1' + Math.abs(diff).toFixed(2) + '</span>';
            }
        }

        var badge = row.status === 'ACTIVE'
            ? '<span class="ph-badge-active">Active</span>'
            : '<span class="ph-badge-inactive">Inactive</span>';

        var tr = $('<tr>');
        tr.append('<td>' + escapeHtml(row.dateFull) + '</td>');
        tr.append('<td class="text-right font-weight-bold">' + formatPeso(row.prev) + '</td>');
        tr.append('<td class="text-right text-muted">' + formatPeso(row.srp) + '</td>');
        tr.append('<td class="text-center">' + changeTd + '</td>');
        tr.append('<td class="text-center">' + badge + '</td>');
        tbody.append(tr);
    });
}

/* ══════════════════════════════════════════════════════════════
   DOCUMENT READY
   ══════════════════════════════════════════════════════════════ */
$(document).ready(function () {

    /* Generate mock history once on page load */
    _phMockData = _phGenerateMock();

    loadCommodities();

    /* Category card */
    $(document).on('click keydown', '.category-card', function (e) {
        if (e.type === 'keydown' && e.key !== 'Enter' && e.key !== ' ') return;
        if (e.type === 'keydown') e.preventDefault();
        openCategoryModal($(this).data('category-id'), $(this).data('category-name'));
    });

    /* Commodity row */
    $(document).on('click', '.commodity-row', function () {
        openEstablishmentDetailModal(
            $(this).data('commodity-id'),
            $(this).data('product-name'),
            $(this).data('srp'),
            $(this).data('unit')
        );
    });

    /* Modal inner tab buttons */
    $(document).on('click', '.modal-tab-btn', function () {
        switchModalTab($(this).data('modal-tab'));
    });

    /* Price history range pills */
    $(document).on('click', '.ph-pill', function () {
        var range = $(this).data('range');
        if (range === _phActiveRange) return;
        _phActiveRange = range;
        $('.ph-pill').removeClass('active');
        $(this).addClass('active');
        /* Re-render with new range — always allowed since chart area is visible */
        _phChartDrawn = false;
        phLoad(range);
    });

    /* Back buttons (both tabs) */
    $('#btnBackToCommodities, #btnBackToCommoditiesHist').on('click', function () {
        $('#establishmentDetailModal').modal('hide');
        $('#categoryCommoditiesModal').modal('show');
    });

    /* Commodity search */
    $('#commoditySearch').on('input', function () {
        var q = $(this).val().trim().toLowerCase();
        if (!q) { renderCommodityList(currentCategoryItems); return; }
        renderCommodityList(currentCategoryItems.filter(function (item) {
            return [item.product_name || item.name, item.brand_name, item.unit_of_measure]
                .filter(Boolean).join(' ').toLowerCase().indexOf(q) !== -1;
        }));
    });

    /* Reset search when category modal closes */
    $('#categoryCommoditiesModal').on('hidden.bs.modal', function () {
        $('#commoditySearch').val('');
    });

    /* Destroy chart on modal close to free memory */
    $('#establishmentDetailModal').on('hidden.bs.modal', function () {
        if (_phChart) { _phChart.destroy(); _phChart = null; }
    });
});
