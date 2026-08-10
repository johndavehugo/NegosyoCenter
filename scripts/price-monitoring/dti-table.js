var priceMonitoringTable = null;
var priceMonitoringData = [];

function formatMoney(amount) {
    var value = Number(amount);

    if (isNaN(value)) {
        return '₱0.00';
    }

    return value.toLocaleString('en-PH', {
        style: 'currency',
        currency: 'PHP'
    });
}

function statusBadge(status) {
    var badges = {
        WITHIN_SRP: ['badge-compliant', 'WITHIN SRP'],
        OVERPRICED: ['badge-overpriced', 'OVERPRICED'],
        BELOW_SRP: ['badge-below', 'BELOW SRP']
    };

    var badge = badges[status] || [
        'badge-secondary',
        status || 'UNKNOWN'
    ];

    return '<span class="badge ' + badge[0] + '">' +
        badge[1] +
        '</span>';
}

function renderActionButtons(row) {
    if (row.id) {
        return `
            <button
                type="button"
                class="btn btn-primary btn-sm btn-edit"
                data-id="${row.id}"
                title="Edit">
                <i class="fas fa-edit"></i>
            </button>
        `;
    }

    return `
        <button
            type="button"
            class="btn btn-success btn-sm btn-add-price"
            data-commodity-id="${row.commodity_id || ''}"
            title="Add Price">
            <i class="fas fa-plus"></i>
        </button>
    `;
}

function getPriceApiUrl(query) {
    var pathBase = window.location.pathname.split('/pages/')[0];

    query = query || '';

    if (query.startsWith('/')) {
        query = '&id=' + query.substring(1);
    } else if (query.startsWith('?')) {
        query = '&' + query.substring(1);
    }

    return window.location.origin +
        pathBase +
        '/api/routes.php?resource=price' +
        query;
}

function buildCategoryOptions(data) {
    var categories = {};

    data.forEach(function(row) {
        if (row.category_name) {
            categories[row.category_name] = true;
        }
    });

    var html = '<option value="">All Categories</option>';

    Object.keys(categories)
        .sort()
        .forEach(function(category) {
            html += '<option value="' +
                category +
                '">' +
                category +
                '</option>';
        });

    $('#filter_category').html(html);
}

function refreshStats(data) {
    var stats = {
        total: data.length,
        compliant: data.filter(function(row) {
            return row.status === 'WITHIN_SRP';
        }).length,
        overpriced: data.filter(function(row) {
            return row.status === 'OVERPRICED';
        }).length
    };

    $('#stat_total_items').text(stats.total + ' Items');
    $('#stat_compliant_stores').text(stats.compliant + ' Logs');
    $('#stat_overpriced_alerts').text(stats.overpriced + ' Logs');
}

function createPriceTable() {
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
            {
                data: 'product_name',
                defaultContent: '-'
            },
            {
                data: 'category_name',
                defaultContent: '-'
            },
            {
                data: 'agency_code',
                defaultContent: '-'
            },
            {
                data: null,
                render: function(row) {
                    var brand = row.brand_name || '';
                    var unit = row.unit_of_measure || '';

                    if (brand && unit) {
                        return brand + ' / ' + unit;
                    }

                    return brand || unit || '-';
                }
            },
            {
                data: 'srp',
                render: formatMoney
            },
            {
                data: 'prevailing_price',
                render: function(value) {
                    return value === null ||
                        value === undefined ||
                        value === ''
                        ? '<span class="text-muted">Not Monitored</span>'
                        : formatMoney(value);
                }
            },
            {
                data: null,
                render: function(row) {
                    return row.status
                        ? statusBadge(row.status)
                        : '<span class="badge badge-secondary">NO PRICE YET</span>';
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: renderActionButtons
            }
        ]
    });
}

function loadPriceData() {
    var url = getPriceApiUrl('?agency_id=1');

    fetch(url)
        .then(function(response) {
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }

            return response.json();
        })
        .then(function(result) {
            if (!result || result.status !== 'success') {
                throw new Error(
                    result && result.message
                        ? result.message
                        : 'Invalid API response.'
                );
            }

            priceMonitoringData = result.data || [];

            priceMonitoringTable
                .clear()
                .rows
                .add(priceMonitoringData)
                .draw();

            buildCategoryOptions(priceMonitoringData);
            refreshStats(priceMonitoringData);
        })
        .catch(function(error) {
            console.error('[DTI] Load error:', error);

            Swal.fire(
                'Error',
                error.message || 'Unable to load price data.',
                'error'
            );
        });
}

function applyCategoryFilter(category) {
    if (!priceMonitoringTable) {
        return;
    }

    priceMonitoringTable
        .column(1)
        .search(
            category
                ? '^' + category + '$'
                : '',
            true,
            false
        )
        .draw();
}

$(document).on('click', '.btn-add-price', function() {
    var commodityId = $(this).data('commodity-id');

    if (commodityId && $('#add_commodity_id').length) {
        $('#add_commodity_id')
            .val(commodityId)
            .trigger('change');
    }

    $('#modalAddPrice').modal('show');
});

$(document).on('click', '.btn-edit', function() {
    var id = $(this).data('id');

    if (!id) {
        Swal.fire(
            'Error',
            'Price record ID is missing.',
            'error'
        );
        return;
    }

    if (typeof openEditModal === 'function') {
        openEditModal(id);
        return;
    }

    Swal.fire(
        'Error',
        'Edit function is not loaded.',
        'error'
    );
});

$(function() {
    createPriceTable();
    loadPriceData();

    $('#filter_category').on('change', function() {
        applyCategoryFilter($(this).val());
    });
});