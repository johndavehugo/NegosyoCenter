var priceTable;

const agencies = {
    1: 'DTI',
    2: 'DA',
    3: 'DOE'
};

function loadPrices() {
    const agencyId = $('#price_agency').val();

    const base = window.location.pathname.split('/pages/')[0];
    const url = window.location.origin +
        base +
        '/api/routes.php/price?agency_id=' +
        agencyId;

    console.log('[PRICE] Loading:', url);

    fetch(url)
        .then(response => response.json())
        .then(result => {

            if (result.status !== 'success') {
                throw new Error(result.message || 'Unable to load price data.');
            }

            priceTable.clear();
            priceTable.rows.add(result.data || []);
            priceTable.draw();

            loadCategories(result.data || []);
            updateSummaryCards(result.data || []);

        })
        .catch(error => {
            console.error('[PRICE] Error:', error);

            Swal.fire(
                'Error',
                error.message || 'Unable to load price data.',
                'error'
            );
        });
}

function loadCategories(data) {
    const categories = [...new Set(
        data.map(row => row.category_name).filter(Boolean)
    )];

    const select = $('#filter_category');

    select.html('<option value="">All Categories</option>');

    categories.sort().forEach(category => {
        select.append(
            $('<option>', {
                value: category,
                text: category
            })
        );
    });
}

function updateSummaryCards(data) {
    const monitored = data.length;

    const active = data.filter(
        row => row.status === 'ACTIVE'
    ).length;

    const inactive = data.filter(
        row => row.status === 'INACTIVE'
    ).length;

    $('#total_monitored').text(monitored);
    $('#total_active').text(active);
    $('#total_inactive').text(inactive);
}

$(document).ready(function () {

    $('#price_agency').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    $('#filter_category').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    priceTable = $('#tblPriceMonitoring').DataTable({
        responsive: true,
        autoWidth: false,
        columns: [
            { data: 'product_name', defaultContent: '-' },
            { data: 'category_name', defaultContent: '-' },
            {
                data: null,
                render: function (data, type, row) {
                    var brand = row.brand_name || '';
                    var unit = row.unit_of_measure || '';

                    if (brand && unit) {
                        return brand + ' / ' + unit;
                    }

                    return brand || unit || '-';
                }
            },
            {
                data: null,
                render: function (data, type, row) {
                    return row.agency_name || row.agency_code || '-';
                }
            },
            {
                data: 'srp',
                render: function (data) {
                    return data
                        ? '₱' + Number(data).toLocaleString('en-PH', {
                            minimumFractionDigits: 2
                        })
                        : '-';
                }
            },
            {
                data: 'status',
                render: function (data) {
                    if (data === 'ACTIVE')
                        return '<span class="badge badge-success">ACTIVE</span>';

                    if (data === 'INACTIVE')
                        return '<span class="badge badge-secondary">INACTIVE</span>';

                    return '<span class="badge badge-secondary">INACTIVE</span>';
                }
            },
            {
                data: null,
                orderable: false,
                render: function (data, type, row) {
                    if (!row.id) {
                        return `
                            <button class="btn btn-success btn-sm btn-add-price"
                                    data-id="${row.commodity_id}">
                                <i class="fas fa-plus"></i>
                            </button>
                        `;
                    }

                    return `
                        <button class="btn btn-primary btn-sm btn-edit-price"
                                data-id="${row.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                    `;
                }
            }
        ]
    });

    function updateAgencyUI() {
        const agency = agencies[$('#price_agency').val()] || 'DOE';

        $('#agency_title').text(agency + ' Price Monitoring');
        $('#agency_subtitle').text(agency + ' Price Monitoring System');
        $('#selected_agency_name').text(agency);
    }

    $('#price_agency').on('change', function () {
        updateAgencyUI();
        loadPrices();
    });

    $('#filter_category').on('change', function () {
        priceTable
            .column(1)
            .search(this.value)
            .draw();
    });

    updateAgencyUI();
    loadPrices();

    // =====================================================
    // ADD PRICE / INITIAL STATUS
    // =====================================================
    $(document).on('click', '.btn-add-price', function () {
        const commodityId = $(this).data('id');

        $('#priceForm')[0].reset();
        $('#priceId').val('');
        $('#priceCommodityId').val(commodityId);
        $('#priceStatus').val('ACTIVE');

        $('#priceModalLabel').text('Add Price / Set Status');
        $('#priceModal').appendTo('body').modal('show');
    });

    // =====================================================
    // EDIT PRICE & STATUS
    // =====================================================
    $(document).on('click', '.btn-edit-price', function () {
    const row = priceTable.row($(this).closest('tr')).data();

    if (!row) {
        Swal.fire('Error', 'Unable to retrieve row data.', 'error');
        return;
    }

    $('#priceForm')[0].reset();
    $('#priceId').val(row.id || 0);
    $('#priceCommodityId').val(row.commodity_id || '');
    $('#priceSrp').val(row.srp || '');

    // Select correct status in dropdown
    const currentStatus = String(row.status || 'ACTIVE').toUpperCase();
    $('#priceStatus').val(currentStatus);

    $('#priceModalLabel').text('Edit SRP & Status');
    $('#priceModal').appendTo('body').modal('show');
});

    // Reset form when modal is closed
    $('#priceModal').on('hidden.bs.modal', function () {
        document.getElementById('priceForm').reset();
    });

});

// =========================================================
// SAVE / UPDATE PRICE & STATUS
// =========================================================
function savePrice() {
    const id = $('#priceId').val();
const isEditing = id !== '' && id !== null && id !== '0' && id !== 0;

const data = {
    commodity_id: $('#priceCommodityId').val(),
    agency_id: $('#price_agency').val(),
    monitored_by_agency_id: $('#price_agency').val(),
    srp: $('#priceSrp').val(),
    status: $('#priceStatus').val()
};

if (isEditing) {
    data.id = id;
}
    fetch('../../api/routes.php/price', {
        method: isEditing ? 'PUT' : 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            $('#priceModal').modal('hide');
            Swal.fire('Success!', res.message || 'Price record updated successfully.', 'success').then(() => {
                // Call loadPrices directly instead of ajax.reload
                if (typeof loadPrices === 'function') {
                    loadPrices();
                } else {
                    location.reload();
                }
            });
        } else {
            Swal.fire('Error', res.message || 'Unable to save price.', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Network error', 'error');
    });
}