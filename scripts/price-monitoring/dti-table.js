var priceMonitoringTable = null;
var priceMonitoringData = [];

function formatMoney(amount) {
    var value = Number(amount);
    if (isNaN(value)) return '₱0.00';

    return value.toLocaleString('en-PH', {
        style: 'currency',
        currency: 'PHP'
    });
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

/* ============================================================
   FIX: Missing function that caused the error
============================================================ */

function renderActionButtons(row) {
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

function getPriceApiUrl(query) {

    var pathBase = window.location.pathname.split('/pages/')[0];

    if (!pathBase) {
        pathBase = window.location.pathname
            .split('/')
            .slice(0, 2)
            .join('/');
    }

    query = query || '';

    if (query.startsWith('/')) {
        query = '&id=' + query.substring(1);
    }
    else if (query.startsWith('?')) {
        query = '&' + query.substring(1);
    }

    return window.location.origin +
        pathBase +
        '/api/routes.php?resource=price' +
        query;
}

function buildCategoryOptions(data) {

    var categories = {};

    data.forEach(function (row) {
        if (row.category_name) {
            categories[row.category_name] = true;
        }
    });

    var html = '<option value="">All Categories</option>';

    Object.keys(categories)
        .sort()
        .forEach(function (category) {
            html +=
                '<option value="' +
                category +
                '">' +
                category +
                '</option>';
        });

    $('#filter_category').html(html);
}

function refreshStats(data) {

    var total = data.length;

    var compliant = data.filter(function (row) {
        return row.status === 'WITHIN_SRP';
    }).length;

    var overpriced = data.filter(function (row) {
        return row.status === 'OVERPRICED';
    }).length;

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
                render: function (row) {

                    var brand = row.brand_name || '';
                    var unit = row.unit_of_measure || '';

                    if (brand && unit)
                        return brand + ' / ' + unit;

                    return brand || unit || '-';
                }
            },

            {
                data: 'srp',
                render: function (value) {
                    return formatMoney(value);
                }
            },

            {
                data: 'prevailing_price',
                render: function (value) {
                    return formatMoney(value);
                }
            },

            {
                data: null,
                render: function (row) {
                    return statusBadge(row.status);
                }
            },

            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (row) {
                    return renderActionButtons(row);
                }
            }

        ]

    });

}

function loadPriceData() {

    var url = getPriceApiUrl();

    console.log('Loading:', url);

    fetch(url)

        .then(function (response) {

            if (!response.ok) {
                throw new Error("HTTP " + response.status);
            }

            return response.json();

        })

        .then(function (result) {

            console.log(result);

            if (!result || result.status !== 'success') {

                Swal.fire(
                    'Error',
                    result.message || 'Invalid API response.',
                    'error'
                );

                return;
            }

            var rows = result.data || [];

            priceMonitoringData = rows;

            priceMonitoringTable.clear();
            priceMonitoringTable.rows.add(rows);
            priceMonitoringTable.draw();

            buildCategoryOptions(rows);

            refreshStats(rows);

        })

        .catch(function (err) {

            console.error(err);

            Swal.fire(
                'Error',
                err.message,
                'error'
            );

        });

}

function applyCategoryFilter(category) {

    if (!priceMonitoringTable)
        return;

    if (category) {

        priceMonitoringTable
            .column(1)
            .search("^" + category + "$", true, false)
            .draw();

    } else {

        priceMonitoringTable
            .column(1)
            .search("")
            .draw();

    }

}

$(document).ready(function () {

    createPriceTable();

    loadPriceData();

    $('#filter_category').on('change', function () {

        applyCategoryFilter($(this).val());

    });

});