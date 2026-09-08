var establishmentPricesTable;

function loadEstablishmentDropdown() {
    fetch('../../api/routes.php/establishment')
        .then(response => response.json())
        .then(result => {
            if (result.status !== 'success') {
                throw new Error(result.message || 'Unable to load establishments.');
            }

            const select = $('#filter_establishment');
            const previousValue = select.val();

            select.empty();

            if (!result.data || result.data.length === 0) {
                select.append($('<option>', { value: '', text: 'No establishments available' }));
                return;
            }

            result.data.forEach(est => {
                const label = est.branch ? `${est.name} - ${est.branch}` : est.name;
                select.append($('<option>', { value: est.id, text: label }));
            });

            // Keep previous selection if it still exists, otherwise pick the first
            if (previousValue && select.find(`option[value="${previousValue}"]`).length) {
                select.val(previousValue);
            } else {
                select.val(result.data[0].id);
            }

            loadEstablishmentPrices();
        })
        .catch(error => {
            console.error('[ESTABLISHMENT DROPDOWN] Error:', error);
            Swal.fire('Error', error.message || 'Unable to load establishments.', 'error');
        });
}

function loadEstablishmentPrices() {
    const establishmentId = $('#filter_establishment').val();

    if (!establishmentId) {
        return;
    }

    fetch('../../api/routes.php/establishment-price?establishment_id=' + encodeURIComponent(establishmentId))
        .then(response => response.json())
        .then(result => {
            if (result.status !== 'success') {
                throw new Error(result.message || 'Unable to load products.');
            }

            establishmentPricesTable.clear();
            establishmentPricesTable.rows.add(result.data || []);
            establishmentPricesTable.draw();
        })
        .catch(error => {
            console.error('[ESTABLISHMENT PRICES] Error:', error);
            Swal.fire('Error', error.message || 'Unable to load products.', 'error');
        });
}

function saveEstablishmentPrice() {
    const data = {
        commodity_id: $('#priceCommodityId').val(),
        establishment_id: $('#priceEstablishmentId').val(),
        price: $('#priceValue').val(),
        status: $('#priceStatusValue').val()
    };

    if (!data.price || isNaN(data.price) || Number(data.price) < 0) {
        Swal.fire('Warning', 'Please enter a valid price.', 'warning');
        return;
    }

    fetch('../../api/routes.php/establishment-price', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
        .then(r => r.json())
        .then(res => {
            if (res.status === 'success') {
                $('#editPriceModal').modal('hide');
                Swal.fire('Success!', res.message || 'Price saved successfully.', 'success').then(() => {
                    loadEstablishmentPrices();
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

function openCompareModal(commodityId, productName) {
    fetch('../../api/routes.php/price-comparison?commodity_id=' + encodeURIComponent(commodityId))
        .then(response => response.json())
        .then(result => {
            if (result.status !== 'success') {
                throw new Error(result.message || 'Unable to load comparison.');
            }

            const tbody = $('#tblCompare tbody');
            tbody.empty();

            const rows = result.data || [];

            if (rows.length === 0) {
                tbody.append('<tr><td colspan="3" class="text-center text-muted">No prices recorded yet for this product at any establishment.</td></tr>');
            } else {
                const lowestPrice = Math.min(...rows.map(r => Number(r.price)));

                rows.forEach(r => {
                    const isLowest = Number(r.price) === lowestPrice;
                    const priceText = '₱' + Number(r.price).toLocaleString('en-PH', { minimumFractionDigits: 2 });

                    tbody.append(`
                        <tr class="${isLowest ? 'table-success' : ''}">
                            <td>${r.establishment_name}${isLowest ? ' <span class="badge badge-success">Cheapest</span>' : ''}</td>
                            <td>${r.branch || '-'}</td>
                            <td>${priceText}</td>
                        </tr>
                    `);
                });
            }

            $('#comparePriceModalLabel').text('Compare Prices: ' + productName);
            $('#comparePriceModal').appendTo('body').modal('show');
        })
        .catch(error => {
            console.error('[COMPARE] Error:', error);
            Swal.fire('Error', error.message || 'Unable to load comparison.', 'error');
        });
}

$(document).ready(function () {

    $('#filter_establishment').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    establishmentPricesTable = $('#tblEstablishmentPrices').DataTable({
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
                    if (brand && unit) return brand + ' / ' + unit;
                    return brand || unit || '-';
                }
            },
            {
                data: 'price',
                render: function (data) {
                    return data
                        ? '₱' + Number(data).toLocaleString('en-PH', { minimumFractionDigits: 2 })
                        : '<span class="text-muted">Not set</span>';
                }
            },
            {
                data: 'status',
                render: function (data) {
                    if (data === 'ACTIVE')
                        return '<span class="badge badge-success">ACTIVE</span>';
                    return '<span class="badge badge-secondary">INACTIVE</span>';
                }
            },
            {
                data: null,
                orderable: false,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-primary btn-sm btn-set-price"
                                data-commodity-id="${row.commodity_id}"
                                data-price="${row.price || ''}"
                                data-status="${row.status || 'ACTIVE'}"
                                title="Set / Edit Price">
                            <i class="fas fa-tag"></i>
                        </button>
                        <button class="btn btn-info btn-sm btn-compare-price"
                                data-commodity-id="${row.commodity_id}"
                                data-product-name="${row.product_name}"
                                title="Compare across establishments">
                            <i class="fas fa-balance-scale"></i> Compare
                        </button>
                    `;
                }
            }
        ]
    });

    loadEstablishmentDropdown();

    $('#filter_establishment').on('change', function () {
        loadEstablishmentPrices();
    });

        $(document).on('click', '.btn-set-price', function () {
        const commodityId = $(this).data('commodity-id');
        const price = $(this).data('price');
        const status = $(this).data('status');

        $('#priceCommodityId').val(commodityId);
        $('#priceEstablishmentId').val($('#filter_establishment').val());
        $('#priceValue').val(price || '');

        // If there's no existing price yet, always default to ACTIVE.
        // (The placeholder status is INACTIVE only because nothing was set yet,
        // not because someone intentionally deactivated it.)
        const hasExistingPrice = price !== undefined && price !== null && price !== '';
        $('#priceStatusValue').val(hasExistingPrice ? (status || 'ACTIVE') : 'ACTIVE');

        $('#editPriceModalLabel').text('Set / Edit Price');
        $('#editPriceModal').appendTo('body').modal('show');
    });

    $(document).on('click', '.btn-compare-price', function () {
        const commodityId = $(this).data('commodity-id');
        const productName = $(this).data('product-name');
        openCompareModal(commodityId, productName);
    });

});
