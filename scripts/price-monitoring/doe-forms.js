var priceMonitoringReferences = {
    agencies: [],
    commodities: []
};

function renderAgencyOptions(agencies) {
    var html = '<option value="">-- Select Agency --</option>';

    agencies.forEach(function(agency) {
        var label = agency.code ||
            agency.name ||
            'Agency #' + agency.id;

        html += '<option value="' +
            agency.id +
            '">' +
            label +
            '</option>';
    });

    $('#add_monitored_by_agency_id, #edit_monitored_by_agency_id')
        .html(html);
}

function renderCommodityOptions(commodities) {
    var html = '<option value="">-- Select Commodity --</option>';

    commodities.forEach(function(commodity) {
        var label = commodity.product_name ||
            'Commodity #' + commodity.id;

        if (commodity.category_name) {
            label += ' (' +
                commodity.category_name +
                ')';
        }

        html += '<option value="' +
            commodity.id +
            '">' +
            label +
            '</option>';
    });

    $('#add_commodity_id, #edit_commodity_id')
        .html(html);
}

function loadReferenceData() {
    var pathBase =
        window.location.pathname.split('/pages/')[0];

    if (!pathBase) {
        pathBase =
            window.location.pathname
                .split('/')
                .slice(0, 2)
                .join('/');
    }

    var baseUrl =
        window.location.origin +
        pathBase +
        '/api/routes.php';

    Promise.all([
        fetch(
            baseUrl +
            '?resource=price&action=agencies'
        ).then(function(response) {
            if (!response.ok) {
                throw new Error(
                    'Unable to load agencies. HTTP ' +
                    response.status
                );
            }

            return response.json();
        }),

        fetch(
            baseUrl +
            '?resource=price&action=commodities&agency_id=3'
        ).then(function(response) {
            if (!response.ok) {
                throw new Error(
                    'Unable to load commodities. HTTP ' +
                    response.status
                );
            }

            return response.json();
        })
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

        var selectFields =
            $('#add_monitored_by_agency_id, ' +
              '#add_commodity_id, ' +
              '#edit_monitored_by_agency_id, ' +
              '#edit_commodity_id');

        if (
            selectFields.length &&
            $.fn.select2
        ) {
            selectFields.each(function() {
                if (
                    $(this).hasClass(
                        'select2-hidden-accessible'
                    )
                ) {
                    $(this).select2('destroy');
                }

                $(this).select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });
            });
        }

        console.log(
            '[DOE] Reference data loaded:',
            {
                agencies: agencies,
                commodities: commodities
            }
        );
    })
    .catch(function(error) {
        console.error(
            '[DOE] Unable to load reference data:',
            error
        );
    });
}

function resetAddForm() {
    var form =
        $('#formAddPrice')[0];

    if (!form) {
        return;
    }

    form.reset();

    $('#add_monitored_by_agency_id, #add_commodity_id')
        .val('')
        .trigger('change');

    $('#formAddPrice')
        .removeData('commodity-id');
}

function resetEditForm() {
    var form =
        $('#formEditPrice')[0];

    if (!form) {
        return;
    }

    form.reset();

    $('#edit_monitored_by_agency_id, #edit_commodity_id')
        .val('')
        .trigger('change');

    $('#formEditPrice')
        .removeData('price-id')
        .removeData('commodity-id');
}

function openEditModal(id) {
    if (
        id === undefined ||
        id === null ||
        id === ''
    ) {
        Swal.fire(
            'Error',
            'Price record ID is missing.',
            'error'
        );

        return;
    }

    if (
        typeof priceMonitoringData !== 'undefined' &&
        Array.isArray(priceMonitoringData)
    ) {
        var row =
            priceMonitoringData.find(function(item) {
                return String(item.id) ===
                    String(id);
            });

        if (row) {
            $('#formEditPrice')
                .data('price-id', row.id)
                .data(
                    'commodity-id',
                    row.commodity_id
                );

            $('#formEditPrice input[name="price_id"]')
                .val(row.id);

            $('#formEditPrice input[name="commodity_id"]')
                .val(row.commodity_id || '');

            $('#formEditPrice input[name="agency_id"]')
                .val(
                    row.monitored_by_agency_id ||
                    row.agency_id ||
                    3
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

            return;
        }
    }

    var pathBase =
        window.location.pathname.split('/pages/')[0];

    if (!pathBase) {
        pathBase =
            window.location.pathname
                .split('/')
                .slice(0, 2)
                .join('/');
    }

    var url =
        window.location.origin +
        pathBase +
        '/api/routes.php?resource=price&agency_id=3&id=' +
        encodeURIComponent(id);

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
        var result;

        try {
            result = JSON.parse(text);
        } catch (error) {
            throw new Error(
                'Server returned invalid JSON.'
            );
        }

        if (
            !result ||
            result.status !== 'success'
        ) {
            throw new Error(
                result && result.message
                    ? result.message
                    : 'Unable to load entry.'
            );
        }

        var row = result.data;

        $('#formEditPrice')
            .data('price-id', row.id)
            .data(
                'commodity-id',
                row.commodity_id
            );

        $('#formEditPrice input[name="price_id"]')
            .val(row.id);

        $('#formEditPrice input[name="commodity_id"]')
            .val(row.commodity_id || '');

        $('#formEditPrice input[name="agency_id"]')
            .val(
                row.monitored_by_agency_id ||
                row.agency_id ||
                3
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
    })
    .catch(function(error) {
        console.error(
            '[DOE] Open edit error:',
            error
        );

        Swal.fire(
            'Error',
            error.message ||
                'Unable to load entry.',
            'error'
        );
    });
}

$(document).ready(function() {
    console.log('[DOE] doe-forms.js loaded');

    loadReferenceData();

    $(document).on(
        'click',
        '.btn-edit',
        function() {
            var id =
                $(this).data('id');

            if (
                typeof currentEditPriceId !==
                'undefined'
            ) {
                currentEditPriceId = id;
            }

            openEditModal(id);
        }
    );
});