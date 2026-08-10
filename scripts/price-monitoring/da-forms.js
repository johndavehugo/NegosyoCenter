'use strict';

var DA_FORMS_CONFIG = {
    api: '../../api/routes.php',
    agencyId: '2',
    agencyCode: 'DA'
};

function daGetFormValue(form, name) {
    return $.trim(
        $(form).find('[name="' + name + '"]').val() || ''
    );
}

function daSetFormValue(form, name, value) {
    $(form)
        .find('[name="' + name + '"]')
        .val(value === null || value === undefined ? '' : value);
}

function daShowFormError(title, message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'warning',
            title: title || 'Invalid Input',
            text: message || 'Please check the form.'
        });
        return;
    }

    alert(
        (title || 'Invalid Input') +
        '\n\n' +
        (message || 'Please check the form.')
    );
}

function daShowFormSuccess(title, message) {
    if (typeof Swal !== 'undefined') {
        return Swal.fire({
            icon: 'success',
            title: title || 'Success',
            text: message || 'Operation completed successfully.',
            timer: 1800,
            showConfirmButton: false
        });
    }

    alert(
        (title || 'Success') +
        '\n\n' +
        (message || 'Operation completed successfully.')
    );

    return Promise.resolve();
}

function daParseResponse(text) {
    var response;

    try {
        response = JSON.parse(text);
    } catch (error) {
        throw new Error(
            'Server returned invalid JSON.'
        );
    }

    if (
        !response ||
        response.status !== 'success'
    ) {
        throw new Error(
            response && response.message
                ? response.message
                : 'The server could not complete the request.'
        );
    }

    return response;
}

function daValidatePrice(price, fieldName) {
    if (
        price === '' ||
        price === null ||
        price === undefined ||
        isNaN(Number(price))
    ) {
        daShowFormError(
            'Invalid Price',
            fieldName + ' must be a valid number.'
        );

        return false;
    }

    if (Number(price) < 0) {
        daShowFormError(
            'Invalid Price',
            fieldName + ' cannot be negative.'
        );

        return false;
    }

    return true;
}

function daCalculateStatus(srp, prevailingPrice) {
    if (prevailingPrice > srp) {
        return 'OVERPRICED';
    }

    if (prevailingPrice < srp) {
        return 'BELOW_SRP';
    }

    return 'WITHIN_SRP';
}

function daReloadTable() {
    if (
        typeof window.daTable !== 'undefined' &&
        window.daTable
    ) {
        window.daTable.ajax.reload(null, false);
        return;
    }

    if (
        typeof loadDAPriceData === 'function'
    ) {
        loadDAPriceData();
        return;
    }

    if (
        typeof loadPriceData === 'function'
    ) {
        loadPriceData();
        return;
    }

    if (
        $.fn.DataTable &&
        $.fn.DataTable.isDataTable('#tblPriceMonitoring')
    ) {
        $('#tblPriceMonitoring')
            .DataTable()
            .ajax
            .reload(null, false);
    }
}

function daSetSubmitButton(button, loadingText, defaultText) {
    if (!button || !button.length) {
        return function() {};
    }

    var originalHtml = button.html();

    button
        .prop('disabled', true)
        .html(
            '<i class="fas fa-spinner fa-spin mr-1"></i>' +
            loadingText
        );

    return function() {
        button
            .prop('disabled', false)
            .html(
                defaultText || originalHtml
            );
    };
}

function daSubmitRequest(url, method, data, contentType) {
    var options = {
        url: url,
        method: method,
        headers: {
            Accept: 'application/json'
        }
    };

    if (contentType === 'json') {
        options.headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(data);
    } else {
        options.body = data;
    }

    return fetch(url, options)
        .then(function(response) {
            return response.text();
        })
        .then(function(text) {
            return daParseResponse(text);
        });
}

function daPrepareCommonFields(form) {
    return {
        commodity: daGetFormValue(form, 'item_description'),
        category: daGetFormValue(form, 'category'),
        unit: daGetFormValue(form, 'unit'),
        store_name: daGetFormValue(form, 'store_name'),
        srp: daGetFormValue(form, 'srp_price'),
        prevailing_price: daGetFormValue(form, 'prevailing_price')
    };
}

function daValidateCommonFields(fields) {
    if (!fields.commodity) {
        daShowFormError(
            'Required',
            'Commodity / Item Description is required.'
        );
        return false;
    }

    if (!fields.category) {
        daShowFormError(
            'Required',
            'Category is required.'
        );
        return false;
    }

    if (!fields.unit) {
        daShowFormError(
            'Required',
            'Unit Measurement is required.'
        );
        return false;
    }

    if (!daValidatePrice(fields.srp, 'SRP')) {
        return false;
    }

    if (!daValidatePrice(
        fields.prevailing_price,
        'Prevailing Price'
    )) {
        return false;
    }

    return true;
}

function daBuildAddData(form) {
    var fields = daPrepareCommonFields(form);

    return {
        commodity_id: $(form).data('commodity-id'),
        agency_id: DA_FORMS_CONFIG.agencyId,
        monitored_by_agency_id: DA_FORMS_CONFIG.agencyId,
        agency: DA_FORMS_CONFIG.agencyCode,
        commodity: fields.commodity,
        item_description: fields.commodity,
        category: fields.category,
        unit: fields.unit,
        store_name: fields.store_name,
        srp_price: Number(fields.srp),
        srp: Number(fields.srp),
        prevailing_price: Number(fields.prevailing_price),
        status: daCalculateStatus(
            Number(fields.srp),
            Number(fields.prevailing_price)
        )
    };
}

function daBuildEditData(form) {
    var fields = daPrepareCommonFields(form);

    return {
        id: daGetFormValue(form, 'entry_id'),
        commodity_id: daGetFormValue(form, 'commodity_id'),
        agency_id: DA_FORMS_CONFIG.agencyId,
        monitored_by_agency_id:
            daGetFormValue(
                form,
                'monitored_by_agency_id'
            ) || DA_FORMS_CONFIG.agencyId,
        agency: DA_FORMS_CONFIG.agencyCode,
        commodity: fields.commodity,
        item_description: fields.commodity,
        category: fields.category,
        unit: fields.unit,
        store_name: fields.store_name,
        srp_price: Number(fields.srp),
        srp: Number(fields.srp),
        prevailing_price: Number(fields.prevailing_price),
        status: daCalculateStatus(
            Number(fields.srp),
            Number(fields.prevailing_price)
        )
    };
}

function daValidateAddData(data) {
    if (
        !data.commodity_id ||
        String(data.commodity_id) === '0'
    ) {
        daShowFormError(
            'Invalid Commodity',
            'Commodity ID is missing.'
        );
        return false;
    }

    return true;
}

function daValidateEditData(data) {
    if (
        !data.id ||
        !/^\d+$/.test(String(data.id))
    ) {
        daShowFormError(
            'Invalid Record',
            'Price record ID is missing or invalid.'
        );
        return false;
    }

    if (
        !data.commodity_id ||
        !/^\d+$/.test(String(data.commodity_id))
    ) {
        daShowFormError(
            'Invalid Commodity',
            'Commodity ID is missing or invalid.'
        );
        return false;
    }

    return true;
}

function daResetAddForm(form) {
    form.reset();
    $(form).removeData('commodity-id');

    daSetFormValue(
        form,
        'agency',
        DA_FORMS_CONFIG.agencyCode
    );
}

function daResetEditForm(form) {
    daSetFormValue(form, 'entry_id', '');
    daSetFormValue(form, 'commodity_id', '');
    daSetFormValue(
        form,
        'monitored_by_agency_id',
        DA_FORMS_CONFIG.agencyId
    );
    daSetFormValue(
        form,
        'agency',
        DA_FORMS_CONFIG.agencyCode
    );
    daSetFormValue(form, 'status', '');
}

$(document).off(
    'submit.daForms',
    '#formAddPrice'
);

$(document).on(
    'submit.daForms',
    '#formAddPrice',
    function(e) {
        e.preventDefault();

        var form = this;
        var $form = $(form);
        var fields = daPrepareCommonFields(form);

        if (!daValidateCommonFields(fields)) {
            return;
        }

        var data = daBuildAddData(form);

        if (!daValidateAddData(data)) {
            return;
        }

        var button = $form.find(
            'button[type="submit"]'
        );

        var restoreButton = daSetSubmitButton(
            button,
            'Saving...',
            '<i class="fa-solid fa-floppy-disk mr-1"></i> Save DA Entry'
        );

        var apiUrl;

        if (
            typeof buildDAPriceApiUrl === 'function'
        ) {
            apiUrl = buildDAPriceApiUrl();
        } else {
            apiUrl = DA_FORMS_CONFIG.api +
                '?resource=price&agency_id=' +
                DA_FORMS_CONFIG.agencyId;
        }

        daSubmitRequest(
            apiUrl,
            'POST',
            new URLSearchParams(data)
        )
            .then(function(response) {
                $('#modalAddPrice').modal('hide');

                daResetAddForm(form);

                return daShowFormSuccess(
                    'Saved!',
                    response.message ||
                    'DA price entry saved successfully.'
                );
            })
            .then(function() {
                daReloadTable();
            })
            .catch(function(error) {
                daShowFormError(
                    'Unable to Save',
                    error.message ||
                    'An error occurred while saving.'
                );
            })
            .finally(function() {
                restoreButton();
            });
    }
);

$(document).off(
    'submit.daForms',
    '#formEditPrice'
);

$(document).on(
    'submit.daForms',
    '#formEditPrice',
    function(e) {
        e.preventDefault();

        var form = this;
        var $form = $(form);
        var fields = daPrepareCommonFields(form);

        if (!daValidateCommonFields(fields)) {
            return;
        }

        var data = daBuildEditData(form);

        if (!daValidateEditData(data)) {
            return;
        }

        var status = data.status;

        daSetFormValue(
            form,
            'status',
            status
        );

        var button = $form.find(
            'button[type="submit"]'
        );

        var restoreButton = daSetSubmitButton(
            button,
            'Updating...',
            '<i class="fa-solid fa-rotate mr-1"></i> Update Entry'
        );

        var apiUrl;

        if (
            typeof buildDAPriceApiUrl === 'function'
        ) {
            apiUrl = buildDAPriceApiUrl(data.id);
        } else {
            apiUrl =
                DA_FORMS_CONFIG.api +
                '?resource=price' +
                '&agency_id=' +
                DA_FORMS_CONFIG.agencyId +
                '&id=' +
                encodeURIComponent(data.id);
        }

        daSubmitRequest(
            apiUrl,
            'PUT',
            data,
            'json'
        )
            .then(function(response) {
                $('#modalEditPrice').modal('hide');

                daResetEditForm(form);

                return daShowFormSuccess(
                    'Updated!',
                    response.message ||
                    'DA price entry updated successfully.'
                );
            })
            .then(function() {
                daReloadTable();
            })
            .catch(function(error) {
                daShowFormError(
                    'Unable to Update',
                    error.message ||
                    'An error occurred while updating.'
                );
            })
            .finally(function() {
                restoreButton();
            });
    }
);

$('#modalAddPrice').off(
    'hidden.bs.modal.daForms'
);

$('#modalAddPrice').on(
    'hidden.bs.modal.daForms',
    function() {
        var form = document.getElementById(
            'formAddPrice'
        );

        if (form) {
            daResetAddForm(form);
        }
    }
);

$('#modalEditPrice').off(
    'hidden.bs.modal.daForms'
);

$('#modalEditPrice').on(
    'hidden.bs.modal.daForms',
    function() {
        var form = document.getElementById(
            'formEditPrice'
        );

        if (form) {
            daResetEditForm(form);
        }
    }
);