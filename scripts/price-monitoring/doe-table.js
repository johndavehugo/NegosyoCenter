var currentEditPriceId = null;
var currentEditCommodityId = null;

var DOE_AGENCY_ID = '3';
var DOE_AGENCY_CODE = 'DOE';

var priceMonitoringTable = null;
var priceMonitoringData = [];

function formatMoney(amount) {
    if (amount === null || amount === undefined || amount === '') {
        return '<span class="text-muted">-</span>';
    }

    var value = Number(amount);

    if (isNaN(value)) {
        return '<span class="text-muted">-</span>';
    }

    return value.toLocaleString('en-PH', {
        style: 'currency',
        currency: 'PHP'
    });
}

function statusBadge(status) {
    var badgeClass = 'badge-secondary';
    var label = status || 'NO PRICE YET';

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

function getPriceApiUrl(priceId) {
    var pathBase = window.location.pathname.split('/pages/')[0];

    if (!pathBase) {
        pathBase = window.location.pathname.split('/').slice(0, 2).join('/');
    }

    var url = window.location.origin +
        pathBase +
        '/api/routes.php?resource=price&agency_id=' +
        encodeURIComponent(DOE_AGENCY_ID);

    if (
        priceId !== undefined &&
        priceId !== null &&
        priceId !== ''
    ) {
        url += '&id=' + encodeURIComponent(priceId);
    }

    return url;
}

function renderActionButtons(row) {
    if (
        row.id === null ||
        row.id === undefined ||
        row.id === ''
    ) {
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
                $('<div>').text(category).html() +
                '">' +
                $('<div>').text(category).html() +
                '</option>';
        });

    $('#filter_category').html(html);

    if ($('#filter_category').hasClass('select2-hidden-accessible')) {
        $('#filter_category').trigger('change.select2');
    }
}

function refreshStats(data) {
    var total = data.length;

    var compliant = data.filter(function(row) {
        return row.status === 'WITHIN_SRP';
    }).length;

    var overpriced = data.filter(function(row) {
        return row.status === 'OVERPRICED';
    }).length;

    $('#stat_total_items').text(total + ' Items');
    $('#stat_compliant_stores').text(compliant + ' Outlets');
    $('#stat_overpriced_alerts').text(overpriced + ' Items');
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
                data: null,
                render: function(data, type, row) {
                    var brand = row.brand_name || '';
                    var unit = row.unit_of_measure || '';

                    if (brand && unit) {
                        return `
                            <strong>${brand}</strong>
                            <br>
                            <small class="text-muted">${unit}</small>
                        `;
                    }

                    return brand || unit || '-';
                }
            },

            {
                data: null,
                render: function(data, type, row) {
                    if (row.agency_name) {
                        return row.agency_name;
                    }

                    if (row.agency_code) {
                        return row.agency_code;
                    }

                    return '<span class="text-muted">' +
                        DOE_AGENCY_CODE +
                        '</span>';
                }
            },

            {
                data: 'srp',
                defaultContent: null,
                render: function(value) {
                    return formatMoney(value);
                }
            },

            {
                data: 'prevailing_price',
                defaultContent: null,
                render: function(value) {
                    if (
                        value === null ||
                        value === undefined ||
                        value === ''
                    ) {
                        return '<span class="text-muted">Not Monitored</span>';
                    }

                    return formatMoney(value);
                }
            },

            {
                data: null,
                render: function(data, type, row) {
                    return statusBadge(row.status);
                }
            },

            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    return renderActionButtons(row);
                }
            }
        ]
    });
}

function loadPriceData() {
    var url = getPriceApiUrl();

    console.log('[DOE] Loading price data:', url);

    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(function(response) {
        return response.text();
    })
    .then(function(text) {
        console.log('[DOE] Raw API response:', text);

        var result;

        try {
            result = JSON.parse(text);
        } catch (error) {
            console.error('[DOE] Invalid JSON:', text);
            throw new Error(
                'Server returned invalid JSON. Check PHP errors.'
            );
        }

        if (
            !result ||
            result.status !== 'success'
        ) {
            throw new Error(
                result && result.message
                    ? result.message
                    : 'Unable to load DOE price data.'
            );
        }

        var rows = Array.isArray(result.data)
            ? result.data
            : [];

        console.log('[DOE] Loaded rows:', rows);

        priceMonitoringData = rows;

        if (!priceMonitoringTable) {
            createPriceTable();
        }

        priceMonitoringTable.clear();
        priceMonitoringTable.rows.add(rows);
        priceMonitoringTable.draw();

        buildCategoryOptions(rows);
        refreshStats(rows);
    })
    .catch(function(error) {
        console.error('[DOE] Price API Error:', error);

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

    if (category) {
        priceMonitoringTable
            .column(1)
            .search(
                '^' +
                $.fn.dataTable.util.escapeRegex(category) +
                '$',
                true,
                false
            )
            .draw();
    } else {
        priceMonitoringTable
            .column(1)
            .search('')
            .draw();
    }
}

$(document).on('click', '.btn-add-price', function() {
    var commodityId = $(this).data('commodity-id');

    console.log(
        '[DOE] Add price clicked. Commodity ID:',
        commodityId
    );

    var row = priceMonitoringData.find(function(item) {
        return String(item.commodity_id) === String(commodityId);
    });

    if (!row) {
        Swal.fire(
            'Error',
            'Commodity information could not be found.',
            'error'
        );

        return;
    }

    $('#formAddPrice')
        .data('commodity-id', row.commodity_id);

    $('#formAddPrice input[name="agency_id"]')
        .val(DOE_AGENCY_ID);

    $('#formAddPrice input[name="category"]')
        .val(row.category_name || '');

    $('#formAddPrice input[name="unit"]')
        .val(row.unit_of_measure || '');

    $('#formAddPrice input[name="item_description"]')
        .val(row.product_name || '');

    $('#formAddPrice input[name="srp_price"]')
        .val(
            row.srp !== null &&
            row.srp !== undefined
                ? row.srp
                : ''
        );

    $('#formAddPrice input[name="prevailing_price"]')
        .val('');

    $('#modalAddPrice').modal('show');
});

$(document).on('submit', '#formAddPrice', function(e) {
    e.preventDefault();

    var form = this;

    var commodityId = $(form).data('commodity-id');

    if (
        !commodityId ||
        commodityId === '0'
    ) {
        Swal.fire(
            'Unable to Save',
            'Commodity ID is missing.',
            'error'
        );

        return;
    }

    var commodity = $(form)
        .find('input[name="item_description"]')
        .val()
        .trim();

    var category = $(form)
        .find('input[name="category"]')
        .val()
        .trim();

    var unit = $(form)
        .find('input[name="unit"]')
        .val()
        .trim();

    var storeName = $(form)
        .find('input[name="store_name"]')
        .val();

    var srpPrice = $(form)
        .find('input[name="srp_price"]')
        .val();

    var prevailingPrice = $(form)
        .find('input[name="prevailing_price"]')
        .val();

    if (!commodity) {
        Swal.fire(
            'Unable to Save',
            'Commodity/Product Description is required.',
            'error'
        );

        return;
    }

    if (
        prevailingPrice === '' ||
        prevailingPrice === null ||
        prevailingPrice === undefined
    ) {
        Swal.fire(
            'Unable to Save',
            'Prevailing Pump Price is required.',
            'error'
        );

        return;
    }

    var payload = {
        commodity_id: commodityId,
        commodity: commodity,
        agency_id: DOE_AGENCY_ID,
        agency: DOE_AGENCY_CODE,
        category: category,
        unit: unit,
        item_description: commodity,
        store_name: storeName,
        srp_price: srpPrice,
        prevailing_price: prevailingPrice
    };

    console.log('[DOE] Add payload:', payload);

    var submitButton = $(form)
        .find('button[type="submit"]');

    submitButton
        .prop('disabled', true)
        .html(
            '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...'
        );

    fetch(getPriceApiUrl(), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(function(response) {
        return response.text();
    })
    .then(function(text) {
        console.log('[DOE] Add raw response:', text);

        var result;

        try {
            result = JSON.parse(text);
        } catch (error) {
            throw new Error(
                'Server returned invalid JSON. Check PHP errors.'
            );
        }

        if (
            !result ||
            result.status !== 'success'
        ) {
            throw new Error(
                result && result.message
                    ? result.message
                    : 'Unable to save price entry.'
            );
        }

        $('#modalAddPrice').modal('hide');

        form.reset();

        $(form).removeData('commodity-id');

        Swal.fire({
            icon: 'success',
            title: 'Saved!',
            text: result.message ||
                'Price entry saved successfully.',
            timer: 1800,
            showConfirmButton: false
        });

        loadPriceData();
    })
    .catch(function(error) {
        console.error('[DOE] Save Price Error:', error);

        Swal.fire({
            icon: 'error',
            title: 'Unable to Save',
            text: error.message ||
                'An error occurred while saving the price entry.'
        });
    })
    .finally(function() {
        submitButton
            .prop('disabled', false)
            .html(
                '<i class="fa-solid fa-floppy-disk mr-1"></i> Save Price Entry'
            );
    });
});

$(document).on('click', '.btn-edit', function() {
    var priceId = $(this).data('id');

    if (
        priceId === undefined ||
        priceId === null ||
        priceId === ''
    ) {
        Swal.fire(
            'Error',
            'Price record ID is missing.',
            'error'
        );

        return;
    }

    var row = priceMonitoringData.find(function(item) {
        return String(item.id) === String(priceId);
    });

    if (!row) {
        Swal.fire(
            'Error',
            'Price record could not be found.',
            'error'
        );

        return;
    }

    currentEditPriceId = row.id;
    currentEditCommodityId = row.commodity_id;

    $('#formEditPrice')
        .data('price-id', row.id)
        .data('commodity-id', row.commodity_id);

    $('#formEditPrice input[name="price_id"]')
        .val(row.id);

    $('#formEditPrice input[name="commodity_id"]')
        .val(row.commodity_id || '');

    $('#formEditPrice input[name="agency_id"]')
        .val(
            row.monitored_by_agency_id ||
            row.agency_id ||
            DOE_AGENCY_ID
        );

    $('#formEditPrice input[name="category"]')
        .val(row.category_name || '');

    $('#formEditPrice input[name="unit"]')
        .val(row.unit_of_measure || '');

    $('#formEditPrice input[name="item_description"]')
        .val(row.product_name || '');

    $('#formEditPrice input[name="store_name"]')
        .val(row.store_name || '');

    $('#formEditPrice input[name="srp_price"]')
        .val(
            row.srp !== null &&
            row.srp !== undefined
                ? row.srp
                : ''
        );

    $('#formEditPrice input[name="prevailing_price"]')
        .val(
            row.prevailing_price !== null &&
            row.prevailing_price !== undefined
                ? row.prevailing_price
                : ''
        );

    $('#modalEditPrice').modal('show');
});

$(document).on('submit', '#formEditPrice', function(e) {
    e.preventDefault();

    var form = this;

    var priceId =
        $(form).data('price-id') ||
        currentEditPriceId ||
        $(form)
            .find('input[name="price_id"]')
            .val();

    var commodityId =
        $(form).data('commodity-id') ||
        currentEditCommodityId ||
        $(form)
            .find('input[name="commodity_id"]')
            .val();

    if (
        priceId === undefined ||
        priceId === null ||
        priceId === ''
    ) {
        Swal.fire(
            'Unable to Update',
            'Price record ID is missing.',
            'error'
        );

        return;
    }

    var agencyId =
        $(form)
            .find('input[name="agency_id"]')
            .val() ||
        DOE_AGENCY_ID;

    var commodity = $(form)
        .find('input[name="item_description"]')
        .val()
        .trim();

    var category = $(form)
        .find('input[name="category"]')
        .val()
        .trim();

    var unit = $(form)
        .find('input[name="unit"]')
        .val()
        .trim();

    var storeName = $(form)
        .find('input[name="store_name"]')
        .val();

    var srpPrice = $(form)
        .find('input[name="srp_price"]')
        .val();

    var prevailingPrice = $(form)
        .find('input[name="prevailing_price"]')
        .val();

    if (!commodity) {
        Swal.fire(
            'Unable to Update',
            'Commodity/Product Description is required.',
            'error'
        );

        return;
    }

    if (
        prevailingPrice === '' ||
        prevailingPrice === null ||
        prevailingPrice === undefined
    ) {
        Swal.fire(
            'Unable to Update',
            'Prevailing Pump Price is required.',
            'error'
        );

        return;
    }

    var payload = {
        id: priceId,
        commodity_id: commodityId || null,
        agency_id: agencyId,
        agency: DOE_AGENCY_CODE,
        commodity: commodity,
        category: category,
        unit: unit,
        item_description: commodity,
        store_name: storeName,
        srp_price: srpPrice,
        prevailing_price: prevailingPrice
    };

    console.log('[DOE] Update payload:', payload);

    var submitButton = $(form)
        .find('button[type="submit"]');

    submitButton
        .prop('disabled', true)
        .html(
            '<i class="fas fa-spinner fa-spin mr-1"></i> Updating...'
        );

    fetch(getPriceApiUrl(priceId), {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(function(response) {
        return response.text();
    })
    .then(function(text) {
        console.log('[DOE] Update raw response:', text);

        var result;

        try {
            result = JSON.parse(text);
        } catch (error) {
            throw new Error(
                'Server returned invalid JSON. Check PHP errors.'
            );
        }

        if (
            !result ||
            result.status !== 'success'
        ) {
            throw new Error(
                result && result.message
                    ? result.message
                    : 'Unable to update price entry.'
            );
        }

        $('#modalEditPrice').modal('hide');

        Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: result.message ||
                'Price entry updated successfully.',
            timer: 1800,
            showConfirmButton: false
        });

        loadPriceData();
    })
    .catch(function(error) {
        console.error('[DOE] Update Price Error:', error);

        Swal.fire({
            icon: 'error',
            title: 'Unable to Update',
            text: error.message ||
                'An error occurred while updating the price entry.'
        });
    })
    .finally(function() {
        submitButton
            .prop('disabled', false)
            .html(
                '<i class="fa-solid fa-floppy-disk mr-1"></i> Update Price Entry'
            );
    });
});

$(document).ready(function() {
    console.log('[DOE] doe-table.js loaded');

    createPriceTable();

    loadPriceData();

    $('#filter_category').on('change', function() {
        applyCategoryFilter($(this).val());
    });
});