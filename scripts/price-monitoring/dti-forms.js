var priceMonitoringReferences = {
    agencies: [],
    commodities: []
};


/* =========================================================
   API URL
   ========================================================= */

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


/* =========================================================
   LOAD AGENCIES
   ========================================================= */

function renderAgencyOptions(agencies) {

    var html = '<option value="">-- Select Agency --</option>';

    agencies.forEach(function (agency) {

        var label =
            agency.code ||
            agency.name ||
            ('Agency #' + agency.id);

        html +=
            '<option value="' + agency.id + '">' +
            label +
            '</option>';
    });

    $('#add_monitored_by_agency_id')
        .html(html);
}


/* =========================================================
   LOAD COMMODITIES
   ========================================================= */

function renderCommodityOptions(commodities) {

    var html =
        '<option value="">-- Select Commodity --</option>';

    commodities.forEach(function (commodity) {

        var label =
            commodity.product_name ||
            commodity.item_description ||
            ('Commodity #' + commodity.id);

        if (commodity.category_name) {
            label +=
                ' (' + commodity.category_name + ')';
        }

        html +=
            '<option value="' + commodity.id + '">' +
            label +
            '</option>';
    });

    $('#add_commodity_id')
        .html(html);
}


/* =========================================================
   LOAD REFERENCE DATA
   ========================================================= */

function loadReferenceData() {

    Promise.all([

        fetch(
            getPriceApiUrl('?action=agencies')
        ).then(function (response) {

            if (!response.ok) {
                throw new Error(
                    'Unable to load agencies.'
                );
            }

            return response.json();
        }),

        fetch(
            getPriceApiUrl('?action=commodities')
        ).then(function (response) {

            if (!response.ok) {
                throw new Error(
                    'Unable to load commodities.'
                );
            }

            return response.json();
        })

    ])

    .then(function (responses) {

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

        /*
         * Select2 ONLY for Add form.
         *
         * Edit form uses normal text inputs.
         */
        $('#add_monitored_by_agency_id, #add_commodity_id')
            .select2({
                theme: 'bootstrap4',
                width: '100%'
            });

    })

    .catch(function (error) {

        console.error(
            'Unable to load reference data:',
            error
        );

        Swal.fire(
            'Error',
            'Unable to load DTI reference data.',
            'error'
        );
    });
}


/* =========================================================
   RESET ADD FORM
   ========================================================= */

function resetAddForm() {

    var form =
        $('#formAddPrice')[0];

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


/* =========================================================
   RESET EDIT FORM
   ========================================================= */

function resetEditForm() {

    var form =
        $('#formEditPrice')[0];

    if (form) {
        form.reset();
    }

    $('#edit_entry_id').val('');

    $('#edit_category').val('');

    $('#edit_unit').val('');

    $('#edit_item_description').val('');

    $('#edit_srp_price').val('');

    $('#edit_prevailing_price').val('');

    $('#edit_monitored_by_agency_id').val('');

    $('#edit_commodity_id').val('');
}


/* =========================================================
   DETERMINE STATUS
   ========================================================= */

function calculateDTIStatus(srp, prevailing) {

    srp = parseFloat(srp);
    prevailing = parseFloat(prevailing);


    if (
        Number.isNaN(srp) ||
        Number.isNaN(prevailing)
    ) {

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

/* =========================================================
   OPEN EDIT MODAL
   ========================================================= */

function openEditModal(id) {

    console.log(
        'Opening DTI Edit Modal. ID =',
        id
    );

    fetch(
        getPriceApiUrl('/' + id)
    )

    .then(function (response) {

        console.log(
            'HTTP Status:',
            response.status
        );

        if (!response.ok) {

            throw new Error(
                'Server returned status ' +
                response.status
            );
        }

        return response.json();
    })

    .then(function (result) {

        console.log(
            'DTI Edit API Response:',
            result
        );

        if (
            result.status !== 'success'
        ) {

            Swal.fire(
                'Error',
                result.message ||
                'Unable to load DTI price entry.',
                'error'
            );

            return;
        }

        var row =
            result.data || {};

            console.log(
    '[DTI] COMPLETE EDIT ROW:',
    JSON.stringify(row, null, 2)
);

        /*
         * Basic IDs
         */

        $('#edit_entry_id')
            .val(row.id || '');

        $('#edit_monitored_by_agency_id')
            .val(
                row.monitored_by_agency_id || ''
            );

        $('#edit_commodity_id')
            .val(
                row.commodity_id || ''
            );


        /*
         * CATEGORY
         *
         * Support multiple possible API
         * field names.
         */

        $('#edit_category')
            .val(
                row.category_name ||
                row.category ||
                ''
            );


        /*
         * UNIT / PACKAGING
         */

        $('#edit_unit')
            .val(
                row.unit_of_measure ||
                row.unit ||
                row.packaging ||
                ''
            );


        /*
         * PRODUCT NAME
         */

        $('#edit_item_description')
            .val(
                row.product_name ||
                row.item_description ||
                row.commodity_name ||
                ''
            );


        /*
         * DTI SRP
         *
         * IMPORTANT:
         * This is NOT readonly anymore.
         */

        $('#edit_srp_price')
            .val(
                row.srp !== undefined
                    ? row.srp
                    : (
                        row.srp_price !== undefined
                            ? row.srp_price
                            : ''
                    )
            );


        /*
         * PREVAILING PRICE
         */

        $('#edit_prevailing_price')
            .val(
                row.prevailing_price !== undefined
                    ? row.prevailing_price
                    : ''
            );


        /*
         * Show modal
         */

        $('#modalEditPrice')
            .modal('show');

    })

    .catch(function (error) {

        console.error(
            'Unable to open DTI edit modal:',
            error
        );

        Swal.fire(
            'Error',
            error.message ||
            'Unable to load DTI price entry.',
            'error'
        );
    });
}


/* =========================================================
   SUBMIT ADD PRICE
   ========================================================= */

function submitAddPrice(event) {

    event.preventDefault();

    var payload = {

        commodity_id:
            $('#add_commodity_id').val(),

        monitored_by_agency_id:
            $('#add_monitored_by_agency_id').val(),

        prevailing_price:
            $('#formAddPrice')
                .find('[name="prevailing_price"]')
                .val(),

        status:
            $('#formAddPrice')
                .find('[name="status"]')
                .val()
    };


    if (!payload.commodity_id) {

        Swal.fire(
            'Required',
            'Please select a commodity.',
            'warning'
        );

        return;
    }


    if (!payload.monitored_by_agency_id) {

        Swal.fire(
            'Required',
            'Please select an agency.',
            'warning'
        );

        return;
    }


    if (!payload.prevailing_price) {

        Swal.fire(
            'Required',
            'Please enter the prevailing price.',
            'warning'
        );

        return;
    }


    fetch(
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

    .then(function (response) {

        return response.json();
    })

    .then(function (result) {

        if (
            result.status === 'success'
        ) {

            Swal.fire(
                'Saved',
                result.message ||
                'DTI price entry saved successfully.',
                'success'
            );

            $('#modalAddPrice')
                .modal('hide');

            resetAddForm();

            if (
                typeof loadPriceData ===
                'function'
            ) {

                loadPriceData();
            }

        } else {

            Swal.fire(
                'Error',
                result.message ||
                'Unable to save DTI price entry.',
                'error'
            );
        }
    })

    .catch(function (error) {

        console.error(
            'Add request failed:',
            error
        );

        Swal.fire(
            'Error',
            'Network error while saving DTI price entry.',
            'error'
        );
    });
}


/* =========================================================
   SUBMIT EDIT PRICE
========================================================= */

function submitEditPrice(event) {

    event.preventDefault();

    var id =
        $('#edit_entry_id').val();

    var commodityId =
        $('#edit_commodity_id').val();

    var agencyId =
        $('#edit_monitored_by_agency_id').val();

    var category =
        $('#edit_category').val().trim();

    var unit =
        $('#edit_unit').val().trim();

    var productName =
        $('#edit_item_description').val().trim();

    var srp =
        parseFloat($('#edit_srp_price').val());

    var prevailing =
        parseFloat($('#edit_prevailing_price').val());


    console.log('[DTI] UPDATE VALUES:', {
        id: id,
        commodity_id: commodityId,
        monitored_by_agency_id: agencyId,
        category: category,
        unit: unit,
        product_name: productName,
        srp: srp,
        prevailing_price: prevailing
    });


    /* =====================================================
       VALIDATION
    ===================================================== */

    if (!id) {

        Swal.fire(
            'Error',
            'Price entry ID is missing.',
            'error'
        );

        return;
    }


    if (!commodityId) {

        Swal.fire(
            'Error',
            'Commodity ID is missing.',
            'error'
        );

        return;
    }


    if (!agencyId) {

        Swal.fire(
            'Error',
            'Agency ID is missing.',
            'error'
        );

        return;
    }


    if (!category) {

        Swal.fire(
            'Required',
            'Please enter the category.',
            'warning'
        );

        $('#edit_category').focus();

        return;
    }


    if (!unit) {

        Swal.fire(
            'Required',
            'Please enter the unit / packaging.',
            'warning'
        );

        $('#edit_unit').focus();

        return;
    }


    if (!productName) {

        Swal.fire(
            'Required',
            'Please enter the commodity / product description.',
            'warning'
        );

        $('#edit_item_description').focus();

        return;
    }


    if (isNaN(srp) || srp < 0) {

        Swal.fire(
            'Required',
            'Please enter a valid DTI SRP / guide price.',
            'warning'
        );

        $('#edit_srp_price').focus();

        return;
    }


    if (isNaN(prevailing) || prevailing < 0) {

        Swal.fire(
            'Required',
            'Please enter a valid prevailing monitored price.',
            'warning'
        );

        $('#edit_prevailing_price').focus();

        return;
    }


    /* =====================================================
       CALCULATE STATUS
    ===================================================== */

    var status =
        calculateDTIStatus(
            srp,
            prevailing
        );


    console.log(
        '[DTI] Calculated Status:',
        status
    );


    /* =====================================================
       BUILD COMPLETE PAYLOAD
    ===================================================== */

    var payload = {

        id: id,

        commodity_id:
            commodityId,

        monitored_by_agency_id:
            agencyId,

        category:
            category,

        unit:
            unit,

        item_description:
            productName,

        product_name:
            productName,

        srp:
            srp,

        srp_price:
            srp,

        prevailing_price:
            prevailing,

        status:
            status
    };


    console.log(
        '[DTI] UPDATE PAYLOAD:',
        payload
    );


    /* =====================================================
       SEND PUT REQUEST
    ===================================================== */

    fetch(
        getPriceApiUrl(),
        {
            method: 'PUT',

            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },

            body:
                JSON.stringify(payload)
        }
    )

    .then(function(response) {

        console.log(
            '[DTI] UPDATE HTTP STATUS:',
            response.status
        );

        return response.text();
    })

    .then(function(text) {

        console.log(
            '[DTI] UPDATE RAW RESPONSE:',
            text
        );


        if (!text) {

            throw new Error(
                'The server returned an empty response.'
            );
        }


        var result;

        try {

            result =
                JSON.parse(text);

        } catch (error) {

            console.error(
                '[DTI] INVALID JSON FROM SERVER:',
                text
            );

            throw new Error(
                'PHP returned an invalid response. Check routes.php and PriceMonitoringController.php.'
            );
        }


        return result;
    })

    .then(function(result) {

        console.log(
            '[DTI] UPDATE RESULT:',
            result
        );


        if (
            result.status === 'success'
        ) {

            Swal.fire(
                'Updated',
                result.message ||
                'DTI price entry updated successfully.',
                'success'
            );


            $('#modalEditPrice')
                .modal('hide');


            resetEditForm();


            if (
                typeof loadPriceData ===
                'function'
            ) {

                loadPriceData();
            }


        } else {

            console.error(
                '[DTI] UPDATE FAILED:',
                result
            );


            Swal.fire(
                'Update Failed',
                result.message ||
                'Unable to update DTI price entry.',
                'error'
            );
        }

    })

    .catch(function(error) {

        console.error(
            '[DTI] UPDATE ERROR:',
            error
        );


        Swal.fire(
            'Error',
            error.message ||
            'Network error while updating DTI price entry.',
            'error'
        );
    });
}



/* =========================================================
   DOCUMENT READY
========================================================= */

$(function () {

    /*
     * Load dropdown/reference data
     */
    loadReferenceData();


    /*
     * ADD
     */
    $('#formAddPrice').on(
        'submit',
        submitAddPrice
    );


    /*
     * EDIT
     */
    $('#formEditPrice').on(
        'submit',
        submitEditPrice
    );


    /*
     * OPEN EDIT MODAL
     */
    $(document).on(
        'click',
        '.btn-edit',
        function () {

            var id = $(this).data('id');

            if (!id) {

                Swal.fire(
                    'Error',
                    'No price entry ID was provided.',
                    'error'
                );

                return;
            }

            openEditModal(id);
        }
    );


    /*
     * RECALCULATE STATUS WHILE TYPING
     *
     * This does NOT save automatically.
     */
    $('#edit_srp_price, #edit_prevailing_price').on(
        'input',
        function () {

            var srp = parseFloat(
                $('#edit_srp_price').val()
            );

            var prevailing = parseFloat(
                $('#edit_prevailing_price').val()
            );

            var status = calculateDTIStatus(
                srp,
                prevailing
            );

            console.log(
                '[DTI] SRP:',
                srp,
                'Prevailing:',
                prevailing,
                'Status:',
                status
            );
        }
    );

});