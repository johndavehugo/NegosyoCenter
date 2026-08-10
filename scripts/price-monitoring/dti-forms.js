var priceMonitoringReferences = {
    agencies: [],
    commodities: []
};

var DTI_CONFIG = {
    resource: 'price',
    addModal: '#modalAddPrice',
    editModal: '#modalEditPrice'
};

function getPriceApiUrl(query) {
    var pathBase = window.location.pathname.split('/pages/')[0];

    if (!pathBase) {
        pathBase = window.location.pathname
            .split('/')
            .slice(0, 2)
            .join('/');
    }

    query = query || '';

    if (query.charAt(0) === '/') {
        query = '&id=' + query.substring(1);
    } else if (query.charAt(0) === '?') {
        query = '&' + query.substring(1);
    }

    return window.location.origin +
        pathBase +
        '/api/routes.php?resource=' +
        DTI_CONFIG.resource +
        query;
}

function apiRequest(url, options) {
    options = options || {};

    options.headers = Object.assign({
        'Accept': 'application/json'
    }, options.headers || {});

    return fetch(url, options)
        .then(function(response) {
            if (!response.ok) {
                throw new Error(
                    'Server returned HTTP ' + response.status
                );
            }

            return response.text();
        })
        .then(function(text) {
            if (!text) {
                throw new Error(
                    'The server returned an empty response.'
                );
            }

            try {
                return JSON.parse(text);
            } catch (error) {
                console.error(
                    'Invalid API response:',
                    text
                );

                throw new Error(
                    'The server returned an invalid response.'
                );
            }
        });
}

function getValue(selector) {
    return $(selector).val();
}

function setValue(selector, value) {
    $(selector).val(
        value === null || value === undefined
            ? ''
            : value
    );
}

function showMessage(title, message, icon) {
    Swal.fire(
        title,
        message,
        icon
    );
}

function refreshPriceTable() {
    if (typeof loadPriceData === 'function') {
        loadPriceData();
    }
}

function calculateDTIStatus(srp, prevailing) {
    srp = parseFloat(srp);
    prevailing = parseFloat(prevailing);

    if (isNaN(srp) || isNaN(prevailing)) {
        return '';
    }

    if (prevailing > srp) {
        return 'OVERPRICED';
    }

    if (prevailing < srp) {
        return 'BELOW_SRP';
    }

    return 'WITHIN_SRP';
}

function renderAgencyOptions(agencies) {
    var html =
        '<option value="">-- Select Agency --</option>';

    agencies.forEach(function(agency) {
        var label =
            agency.code ||
            agency.name ||
            'Agency #' + agency.id;

        html +=
            '<option value="' + agency.id + '">' +
            label +
            '</option>';
    });

    $('#add_monitored_by_agency_id').html(html);
}

function renderCommodityOptions(commodities) {
    var html =
        '<option value="">-- Select Commodity --</option>';

    commodities.forEach(function(commodity) {
        var label =
            commodity.product_name ||
            commodity.item_description ||
            'Commodity #' + commodity.id;

        if (commodity.category_name) {
            label +=
                ' (' + commodity.category_name + ')';
        }

        html +=
            '<option value="' + commodity.id + '">' +
            label +
            '</option>';
    });

    $('#add_commodity_id').html(html);
}

function loadReferenceData() {
    Promise.all([
        apiRequest(
            getPriceApiUrl('?action=agencies')
        ),
        apiRequest(
            getPriceApiUrl('?action=commodities')
        )
    ])
    .then(function(responses) {
        var agencies =
            responses[0].data || [];

        var commodities =
            responses[1].data || [];

        priceMonitoringReferences.agencies =
            agencies;

        priceMonitoringReferences.commodities =
            commodities;

        renderAgencyOptions(agencies);
        renderCommodityOptions(commodities);

        $('#add_monitored_by_agency_id, #add_commodity_id')
            .select2({
                theme: 'bootstrap4',
                width: '100%'
            });
    })
    .catch(function(error) {
        console.error(
            'Reference data error:',
            error
        );

        showMessage(
            'Error',
            'Unable to load DTI reference data.',
            'error'
        );
    });
}

function resetAddForm() {
    var form = $('#formAddPrice')[0];

    if (form) {
        form.reset();
    }

    $('#add_monitored_by_agency_id')
        .val('')
        .trigger('change');

    $('#add_commodity_id')
        .val('')
        .trigger('change');
}

function resetEditForm() {
    var form = $('#formEditPrice')[0];

    if (form) {
        form.reset();
    }

    setValue('#edit_entry_id', '');
    setValue('#edit_monitored_by_agency_id', '');
    setValue('#edit_commodity_id', '');
    setValue('#edit_category', '');
    setValue('#edit_unit', '');
    setValue('#edit_item_description', '');
    setValue('#edit_srp_price', '');
    setValue('#edit_prevailing_price', '');
}

function fillEditForm(row) {
    setValue(
        '#edit_entry_id',
        row.id
    );

    setValue(
        '#edit_monitored_by_agency_id',
        row.monitored_by_agency_id
    );

    setValue(
        '#edit_commodity_id',
        row.commodity_id
    );

    setValue(
        '#edit_category',
        row.category_name ||
        row.category ||
        ''
    );

    setValue(
        '#edit_unit',
        row.unit_of_measure ||
        row.unit ||
        row.packaging ||
        ''
    );

    setValue(
        '#edit_item_description',
        row.product_name ||
        row.item_description ||
        row.commodity_name ||
        ''
    );

    setValue(
        '#edit_srp_price',
        row.srp !== undefined &&
        row.srp !== null
            ? row.srp
            : (
                row.srp_price !== undefined
                    ? row.srp_price
                    : ''
            )
    );

    setValue(
        '#edit_prevailing_price',
        row.prevailing_price
    );
}

function openEditModal(id) {
    if (!id) {
        showMessage(
            'Error',
            'No price entry ID was provided.',
            'error'
        );

        return;
    }

    apiRequest(
        getPriceApiUrl('/' + id)
    )
    .then(function(result) {
        if (result.status !== 'success') {
            throw new Error(
                result.message ||
                'Unable to load DTI price entry.'
            );
        }

        fillEditForm(
            result.data || {}
        );

        $(DTI_CONFIG.editModal)
            .modal('show');
    })
    .catch(function(error) {
        console.error(
            'Open edit error:',
            error
        );

        showMessage(
            'Error',
            error.message ||
            'Unable to open DTI price entry.',
            'error'
        );
    });
}

function getAddPricePayload() {
    return {
        commodity_id:
            getValue('#add_commodity_id'),

        monitored_by_agency_id:
            getValue('#add_monitored_by_agency_id'),

        prevailing_price:
            $('#formAddPrice')
                .find('[name="prevailing_price"]')
                .val(),

        status:
            $('#formAddPrice')
                .find('[name="status"]')
                .val()
    };
}

function validateAddPrice(payload) {
    if (!payload.commodity_id) {
        showMessage(
            'Required',
            'Please select a commodity.',
            'warning'
        );

        return false;
    }

    if (!payload.monitored_by_agency_id) {
        showMessage(
            'Required',
            'Please select an agency.',
            'warning'
        );

        return false;
    }

    if (!payload.prevailing_price) {
        showMessage(
            'Required',
            'Please enter the prevailing price.',
            'warning'
        );

        return false;
    }

    return true;
}

function submitAddPrice(event) {
    event.preventDefault();

    var payload =
        getAddPricePayload();

    if (!validateAddPrice(payload)) {
        return;
    }

    apiRequest(
        getPriceApiUrl(),
        {
            method: 'POST',

            headers: {
                'Content-Type':
                    'application/json'
            },

            body:
                JSON.stringify(payload)
        }
    )
    .then(function(result) {
        if (result.status !== 'success') {
            throw new Error(
                result.message ||
                'Unable to save DTI price entry.'
            );
        }

        showMessage(
            'Saved',
            result.message ||
            'DTI price entry saved successfully.',
            'success'
        );

        $(DTI_CONFIG.addModal)
            .modal('hide');

        resetAddForm();
        refreshPriceTable();
    })
    .catch(function(error) {
        console.error(
            'Add price error:',
            error
        );

        showMessage(
            'Error',
            error.message ||
            'Unable to save DTI price entry.',
            'error'
        );
    });
}

function getEditPricePayload() {
    var srp =
        parseFloat(
            getValue('#edit_srp_price')
        );

    var prevailing =
        parseFloat(
            getValue('#edit_prevailing_price')
        );

    return {
        id:
            getValue('#edit_entry_id'),

        commodity_id:
            getValue('#edit_commodity_id'),

        monitored_by_agency_id:
            getValue('#edit_monitored_by_agency_id'),

        category:
            getValue('#edit_category').trim(),

        unit:
            getValue('#edit_unit').trim(),

        item_description:
            getValue('#edit_item_description').trim(),

        product_name:
            getValue('#edit_item_description').trim(),

        srp:
            srp,

        srp_price:
            srp,

        prevailing_price:
            prevailing,

        status:
            calculateDTIStatus(
                srp,
                prevailing
            )
    };
}

function validateEditPrice(payload) {
    if (!payload.id) {
        showMessage(
            'Error',
            'Price entry ID is missing.',
            'error'
        );

        return false;
    }

    if (!payload.commodity_id) {
        showMessage(
            'Error',
            'Commodity ID is missing.',
            'error'
        );

        return false;
    }

    if (!payload.monitored_by_agency_id) {
        showMessage(
            'Error',
            'Agency ID is missing.',
            'error'
        );

        return false;
    }

    if (!payload.category) {
        showMessage(
            'Required',
            'Please enter the category.',
            'warning'
        );

        $('#edit_category').focus();

        return false;
    }

    if (!payload.unit) {
        showMessage(
            'Required',
            'Please enter the unit / packaging.',
            'warning'
        );

        $('#edit_unit').focus();

        return false;
    }

    if (!payload.product_name) {
        showMessage(
            'Required',
            'Please enter the commodity / product description.',
            'warning'
        );

        $('#edit_item_description').focus();

        return false;
    }

    if (isNaN(payload.srp) || payload.srp < 0) {
        showMessage(
            'Required',
            'Please enter a valid DTI SRP / guide price.',
            'warning'
        );

        $('#edit_srp_price').focus();

        return false;
    }

    if (
        isNaN(payload.prevailing_price) ||
        payload.prevailing_price < 0
    ) {
        showMessage(
            'Required',
            'Please enter a valid prevailing monitored price.',
            'warning'
        );

        $('#edit_prevailing_price').focus();

        return false;
    }

    return true;
}

function submitEditPrice(event) {
    event.preventDefault();

    var payload =
        getEditPricePayload();

    if (!validateEditPrice(payload)) {
        return;
    }

    apiRequest(
        getPriceApiUrl(),
        {
            method: 'PUT',

            headers: {
                'Content-Type':
                    'application/json'
            },

            body:
                JSON.stringify(payload)
        }
    )
    .then(function(result) {
        if (result.status !== 'success') {
            throw new Error(
                result.message ||
                'Unable to update DTI price entry.'
            );
        }

        showMessage(
            'Updated',
            result.message ||
            'DTI price entry updated successfully.',
            'success'
        );

        $(DTI_CONFIG.editModal)
            .modal('hide');

        resetEditForm();
        refreshPriceTable();
    })
    .catch(function(error) {
        console.error(
            'Update price error:',
            error
        );

        showMessage(
            'Update Failed',
            error.message ||
            'Unable to update DTI price entry.',
            'error'
        );
    });
}

function updateDTIStatusPreview() {
    var srp =
        parseFloat(
            getValue('#edit_srp_price')
        );

    var prevailing =
        parseFloat(
            getValue('#edit_prevailing_price')
        );

    return calculateDTIStatus(
        srp,
        prevailing
    );
}

function handleEditButtonClick() {
    openEditModal(
        $(this).data('id')
    );
}

function initializeDTIForms() {
    loadReferenceData();

    $('#formAddPrice')
        .on('submit', submitAddPrice);

    $('#formEditPrice')
        .on('submit', submitEditPrice);

    $(document)
        .on(
            'click',
            '.btn-edit',
            handleEditButtonClick
        );

    $('#edit_srp_price, #edit_prevailing_price')
        .on(
            'input',
            updateDTIStatusPreview
        );
}

$(function() {
    initializeDTIForms();
});