var priceMonitoringTable = null;
var priceMonitoringData = [];

function formatMoney(amount) {
    if (amount === null || amount === undefined || amount === '') return '₱0.00';
    var value = Number(amount);
    if (isNaN(value)) return '₱0.00';
    return value.toLocaleString('en-PH', { style: 'currency', currency: 'PHP' });
}

function statusBadge(status) {
    var badgeClass = 'badge-secondary';
    var label = status || 'UNKNOWN';
    switch (status) {
        case 'WITHIN_SRP':
            badgeClass = 'badge-compliant';
            label = 'WITHIN SRP';
            break;
        case 'OVERPRICED':
            badgeClass = 'badge-overpriced';
            label = 'OVERPRICED';
            break;
        case 'BELOW_SRP':
            badgeClass = 'badge-below';
            label = 'BELOW SRP';
            break;
    }
    return '<span class="badge ' + badgeClass + '">' + label + '</span>';
}

function renderActionButtons(row) {
    if (row.id) {
        return `<button type="button" class="btn btn-primary btn-sm btn-edit" data-id="${row.id}" title="Edit"><i class="fas fa-edit"></i></button>`;
    }
    return `<button type="button" class="btn btn-success btn-sm btn-add-price" data-commodity-id="${row.commodity_id || ''}" title="Add Price"><i class="fas fa-plus"></i></button>`;
}

function getPriceApiUrl(query) {
    var pathBase = window.location.pathname.split('/pages/')[0];
    if (!pathBase) {
        pathBase = window.location.pathname.split('/').slice(0, 2).join('/');
    }
    query = query || '';
    if (query.startsWith('/')) {
        query = '&id=' + query.substring(1);
    } else if (query.startsWith('?')) {
        query = '&' + query.substring(1);
    }
    return window.location.origin + pathBase + '/api/routes.php?resource=price' + query;
}

function buildCategoryOptions(data) {
    var categories = {};
    data.forEach(function (row) {
        if (row.category_name) categories[row.category_name] = true;
    });
    var html = '<option value="">All Categories</option>';
    Object.keys(categories).sort().forEach(function (category) {
        html += '<option value="' + category + '">' + category + '</option>';
    });
    $('#filter_category').html(html);
}

function refreshStats(data) {
    var total = data.length;
    var compliant = data.filter(function (row) { return row.status === 'WITHIN_SRP'; }).length;
    var overpriced = data.filter(function (row) { return row.status === 'OVERPRICED'; }).length;
    $('#stat_total_items').text(total + ' Items');
    $('#stat_compliant_stores').text(compliant + ' Logs');
    $('#stat_overpriced_alerts').text(overpriced + ' Logs');
}

function createPriceTable() {
    if ($.fn.DataTable.isDataTable('#tblPriceMonitoring')) {
        $('#tblPriceMonitoring').DataTable().destroy();
    }
    priceMonitoringTable = $('#tblPriceMonitoring').DataTable({
        data: [],
        responsive: true,
        autoWidth: false,
        processing: true,
        paging: true,
        searching: true,
        ordering: true,
        lengthChange: true,
        columns: [
            { data: 'product_name', defaultContent: '-' },
            { data: 'category_name', defaultContent: '-' },
            { data: 'agency_code', defaultContent: '-' },
            {
                data: null,
                render: function (row) {
                    return row.commodity_name || row.item_description || row.product_name || '-';
                }
            },
            {
                data: 'srp',
                render: function (value) { return formatMoney(value); }
            },
            {
                data: 'prevailing_price',
                render: function (value) {
                    if (value === null || value === undefined || value === '') {
                        return `<span class="text-muted">Not Monitored</span>`;
                    }
                    return formatMoney(value);
                }
            },
            {
                data: null,
                render: function (row) {
                    if (!row.status) {
                        return `<span class="badge badge-secondary">NO PRICE YET</span>`;
                    }
                    return statusBadge(row.status);
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function (row) { return renderActionButtons(row); }
            }
        ]
    });
}

function loadPriceData() {
    var url = getPriceApiUrl('?agency_id=1');
    console.log('[DTI] Loading:', url);
    fetch(url)
        .then(function (response) {
            if (!response.ok) throw new Error('HTTP ' + response.status);
            return response.json();
        })
        .then(function (result) {
            console.log('[DTI] API Response:', result);
            if (!result || result.status !== 'success') {
                Swal.fire('Error', result && result.message ? result.message : 'Invalid API response.', 'error');
                return;
            }
            var rows = result.data || [];
            priceMonitoringData = rows;
            console.log('[DTI] Rows received:', rows.length);
            priceMonitoringTable.clear();
            priceMonitoringTable.rows.add(rows);
            priceMonitoringTable.draw();
            console.log('[DTI] Rows in DataTable:', priceMonitoringTable.rows().count());
            buildCategoryOptions(rows);
            refreshStats(rows);
            console.log('[DTI] Complete API rows:', rows);
        })
        .catch(function (error) {
            console.error('[DTI] Load error:', error);
            Swal.fire('Error', error.message || 'Unable to load price data.', 'error');
        });
}

function applyCategoryFilter(category) {
    if (!priceMonitoringTable) return;
    if (category) {
        priceMonitoringTable.column(1).search('^' + category + '$', true, false).draw();
    } else {
        priceMonitoringTable.column(1).search('').draw();
    }
}

$(document).on('click', '.btn-add-price', function () {
    var commodityId = $(this).data('commodity-id');
    console.log('[DTI] Add Price - Commodity ID:', commodityId);
    if ($('#add_commodity_id').length && commodityId) {
        $('#add_commodity_id').val(commodityId).trigger('change');
    }
    if ($('#modalAddPrice').length) {
        $('#modalAddPrice').modal('show');
    }
});

$(document).on('click', '.btn-edit', function () {
    var id = $(this).data('id');
    console.log('[DTI] Edit button clicked. ID:', id);
    if (!id) {
        Swal.fire('Error', 'Price record ID is missing.', 'error');
        return;
    }
    if (typeof openEditModal === 'function') {
        openEditModal(id);
    } else {
        console.error('[DTI] openEditModal() is not available.');
        Swal.fire('Error', 'Edit function is not loaded.', 'error');
    }
});

$(document).ready(function () {
    console.log('[DTI] dti-table.js loaded');
    createPriceTable();
    loadPriceData();
    $('#filter_category').on('change', function () {
        applyCategoryFilter($(this).val());
    });
});