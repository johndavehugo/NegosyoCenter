var priceMonitoringTable = null;
var priceMonitoringData = [];

function formatMoney(amount) {
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
<<<<<<< Updated upstream
    return '<div class="d-flex justify-content-center">' +
        '<button type="button" class="btn btn-info btn-sm mr-1" onclick="openEditModal(' + row.id + ')"><i class="fas fa-pen"></i></button>' +
        '<button type="button" class="btn btn-danger btn-sm" onclick="deletePrice(' + row.id + ')"><i class="fas fa-trash"></i></button>' +
        '</div>';
=======
    return '<button type="button" class="btn btn-info btn-sm" onclick="openEditModal(' + row.id + ')"><i class="fas fa-pen"></i></button>';
>>>>>>> Stashed changes
}

function getPriceApiUrl(query) {
    var pathBase = window.location.pathname.split('/pages/')[0];
    if (!pathBase) {
        pathBase = window.location.pathname.split('/').slice(0, 2).join('/');
    }
    return window.location.origin + pathBase + '/api/routes.php/price' + (query || '');
}

function buildCategoryOptions(data) {
    var categories = data.reduce(function (set, row) {
        if (row.category_name) {
            set[row.category_name] = true;
        }
        return set;
    }, {});

    var options = '<option value="">All Categories</option>';
    Object.keys(categories).sort().forEach(function (category) {
        options += '<option value="' + category + '">' + category + '</option>';
    });

    $('#filter_category').html(options);
}

function refreshStats(data) {
    var total = data.length;
    var compliant = data.filter(function (row) { return row.status === 'WITHIN_SRP'; }).length;
    var overpriced = data.filter(function (row) { return row.status === 'OVERPRICED'; }).length;

    $('#stat_total_items').text(total + ' Items');
    $('#stat_compliant_stores').text(compliant + ' Logs');
    $('#stat_overpriced_alerts').text(overpriced + ' Logs');
}

function createPricedTable() {
    priceMonitoringTable = $('#tblPriceMonitoring').DataTable({
        data: [],
        responsive: true,
        autoWidth: false,
        processing: true,
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        columns: [
            { data: 'product_name' },
            { data: 'category_name' },
            { data: 'agency_code' },
            {
                data: null,
                render: function (data) {
                    var brand = data.brand_name || '';
                    var unit = data.unit_of_measure || '';
                    return brand && unit ? brand + ' / ' + unit : (brand || unit || '-');
                }
            },
            {
                data: 'srp',
                render: function (data) { return formatMoney(data); }
            },
            {
                data: 'prevailing_price',
                render: function (data) { return formatMoney(data); }
            },
            {
                data: null,
                render: function (data) {
                    return statusBadge(data.status);
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data) {
                    return renderActionButtons(data);
                }
            }
        ]
    });
}

function loadPriceData() {
    var url = getPriceApiUrl();
    console.log('DTI loadPriceData URL:', url);
    fetch(url)
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Server returned status ' + response.status);
            }
            return response.json();
        })
        .then(function (result) {
            if (!result || result.status !== 'success') {
                var message = (result && result.message) ? result.message : 'Invalid API response.';
                console.error('Price API response error:', message, result);
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error loading data', message, 'error');
                }
                return;
            }

            var rows = result.data || [];
            priceMonitoringData = rows;

            if (priceMonitoringTable) {
                priceMonitoringTable.clear();
                priceMonitoringTable.rows.add(rows);
                priceMonitoringTable.draw();
            }

            buildCategoryOptions(rows);
            refreshStats(rows);
        })
        .catch(function (error) {
            console.error('Unable to load price data:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error', 'Unable to load price data. Check the API route and browser console.', 'error');
            }
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

$(function () {
    createPricedTable();
    loadPriceData();

    $('#filter_category').on('change', function () {
        applyCategoryFilter($(this).val());
    });
});
