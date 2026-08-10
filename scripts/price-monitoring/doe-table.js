

/*
 * Currently edited DOE price record
 *
 * These are deliberately kept outside the modal/form.
 * This prevents the ID from becoming undefined when
 * the form is submitted.
 */
var currentEditPriceId = null;
var currentEditCommodityId = null;

/* =========================================================
   CONFIGURATION
========================================================= */

var DOE_AGENCY_ID = '3';
var DOE_AGENCY_CODE = 'DOE';


/* =========================================================
   MONEY FORMAT
========================================================= */

function formatMoney(amount) {

    if (
        amount === null ||
        amount === undefined ||
        amount === ''
    ) {
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


/* =========================================================
   STATUS BADGE
========================================================= */

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

    return '<span class="badge ' +
        badgeClass +
        '">' +
        label +
        '</span>';
}


/* =========================================================
   API URL
========================================================= */

function getPriceApiUrl(priceId) {

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
        '/api/routes.php?resource=price&agency_id=' +
        DOE_AGENCY_ID;

    if (
        priceId !== undefined &&
        priceId !== null &&
        priceId !== ''
    ) {

        url += '&id=' +
            encodeURIComponent(priceId);
    }

    return url;
}


/* =========================================================
   ACTION BUTTONS
========================================================= */

function renderActionButtons(row) {

    /*
     * No existing price record
     * -> ADD PRICE
     */

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


    /*
     * Existing price record
     * -> EDIT
     */

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


/* =========================================================
   CATEGORY OPTIONS
========================================================= */

function buildCategoryOptions(data) {

    var categories = {};

    data.forEach(function (row) {

        if (row.category_name) {

            categories[row.category_name] = true;
        }
    });


    var html =
        '<option value="">All Categories</option>';


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


/* =========================================================
   STATISTICS
========================================================= */

function refreshStats(data) {

    var total =
        data.length;


    var compliant =
        data.filter(function (row) {

            return row.status === 'WITHIN_SRP';

        }).length;


    var overpriced =
        data.filter(function (row) {

            return row.status === 'OVERPRICED';

        }).length;


    $('#stat_total_items')
        .text(total + ' Items');


    $('#stat_compliant_stores')
        .text(compliant + ' Logs');


    $('#stat_overpriced_alerts')
        .text(overpriced + ' Logs');
}


/* =========================================================
   CREATE DATATABLE
========================================================= */

function createPriceTable() {

    if (
        $.fn.DataTable.isDataTable(
            '#tblPriceMonitoring'
        )
    ) {

        $('#tblPriceMonitoring')
            .DataTable()
            .destroy();
    }


    priceMonitoringTable =
        $('#tblPriceMonitoring').DataTable({

            data: [],

            responsive: true,

            autoWidth: false,

            processing: true,

            paging: true,

            searching: true,

            ordering: true,

            lengthChange: true,


            columns: [

                /* =========================================
                   1. PRODUCT
                ========================================= */

                {
                    data: 'product_name',
                    defaultContent: '-'
                },


                /* =========================================
                   2. CATEGORY
                ========================================= */

                {
                    data: 'category_name',
                    defaultContent: '-'
                },


                /* =========================================
                   3. BRAND / UNIT
                ========================================= */

                {
                    data: null,

                    render: function (
                        data,
                        type,
                        row
                    ) {

                        var brand =
                            row.brand_name || '';

                        var unit =
                            row.unit_of_measure || '';


                        if (
                            brand &&
                            unit
                        ) {

                            return `
                                <strong>${brand}</strong>
                                <br>
                                <small class="text-muted">
                                    ${unit}
                                </small>
                            `;
                        }


                        return (
                            brand ||
                            unit ||
                            '-'
                        );
                    }
                },


                /* =========================================
                   4. AGENCY
                ========================================= */

                {
                    data: null,

                    render: function (
                        data,
                        type,
                        row
                    ) {

                        if (row.agency_name) {

                            return row.agency_name;
                        }


                        if (row.agency_code) {

                            return row.agency_code;
                        }


                        return `
                            <span class="text-muted">
                                ${DOE_AGENCY_CODE}
                            </span>
                        `;
                    }
                },


                /* =========================================
                   5. SRP
                ========================================= */

                {
                    data: 'srp',

                    render: function (value) {

                        return formatMoney(value);
                    }
                },


                /* =========================================
                   6. PREVAILING PRICE
                ========================================= */

                {
                    data: 'prevailing_price',

                    render: function (value) {

                        if (
                            value === null ||
                            value === undefined ||
                            value === ''
                        ) {

                            return `
                                <span class="text-muted">
                                    Not Monitored
                                </span>
                            `;
                        }


                        return formatMoney(value);
                    }
                },


                /* =========================================
                   7. STATUS
                ========================================= */

                {
                    data: null,

                    render: function (
                        data,
                        type,
                        row
                    ) {

                        if (!row.status) {

                            return `
                                <span class="badge badge-secondary">
                                    NO PRICE YET
                                </span>
                            `;
                        }


                        return statusBadge(
                            row.status
                        );
                    }
                },


                /* =========================================
                   8. ACTION
                ========================================= */

                {
                    data: null,

                    orderable: false,

                    searchable: false,

                    className: 'text-center',

                    render: function (
                        data,
                        type,
                        row
                    ) {

                        return renderActionButtons(
                            row
                        );
                    }
                }

            ]

        });
}


/* =========================================================
   LOAD PRICE DATA
========================================================= */

function loadPriceData() {

    var url =
        getPriceApiUrl();


    console.log(
        '[DOE] Loading price data:',
        url
    );


    fetch(url)

        .then(function (response) {

            if (!response.ok) {

                throw new Error(
                    'HTTP ' +
                    response.status
                );
            }


            return response.json();
        })


        .then(function (result) {

            console.log(
                '[DOE] API RESPONSE:',
                result
            );


            if (
                !result ||
                result.status !== 'success'
            ) {

                throw new Error(
                    result.message ||
                    'Invalid API response.'
                );
            }


            var rows =
                result.data || [];


            console.log(
                '[DOE] Rows:',
                rows
            );


            priceMonitoringData =
                rows;


            priceMonitoringTable
                .clear();


            priceMonitoringTable
                .rows
                .add(rows);


            priceMonitoringTable
                .draw();


            buildCategoryOptions(
                rows
            );


            refreshStats(
                rows
            );
        })


        .catch(function (error) {

            console.error(
                '[DOE] Price API Error:',
                error
            );


            Swal.fire(
                'Error',
                error.message ||
                'Unable to load price data.',
                'error'
            );
        });
}


/* =========================================================
   CATEGORY FILTER
========================================================= */

function applyCategoryFilter(category) {

    if (!priceMonitoringTable) {

        return;
    }


    if (category) {

        priceMonitoringTable
            .column(1)
            .search(
                '^' +
                category +
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


/* =========================================================
   ADD PRICE
========================================================= */

$(document).on(
    'click',
    '.btn-add-price',
    function () {

        var commodityId =
            $(this).data('commodity-id');


        console.log(
            '[DOE] Add price clicked. Commodity ID:',
            commodityId
        );


        var row =
            priceMonitoringData.find(
                function (item) {

                    return String(
                        item.commodity_id
                    ) === String(
                        commodityId
                    );
                }
            );


        if (!row) {

            Swal.fire(
                'Error',
                'Commodity information could not be found.',
                'error'
            );

            return;
        }


        console.log(
            '[DOE] Selected commodity:',
            row
        );


        /*
         * Fill ADD modal
         */

        $('#formAddPrice')
            .data(
                'commodity-id',
                row.commodity_id
            );


        $('#formAddPrice input[name="agency_id"]')
            .val(DOE_AGENCY_ID);


        $('#formAddPrice input[name="category"]')
            .val(
                row.category_name || ''
            );


        $('#formAddPrice input[name="unit"]')
            .val(
                row.unit_of_measure || ''
            );


        $('#formAddPrice input[name="item_description"]')
            .val(
                row.product_name || ''
            );


        $('#formAddPrice input[name="store_name"]')
            .val('');


        $('#formAddPrice input[name="srp_price"]')
            .val(
                row.srp !== null &&
                row.srp !== undefined
                    ? row.srp
                    : ''
            );


        $('#formAddPrice input[name="prevailing_price"]')
            .val('');


        $('#modalAddPrice')
            .modal('show');
    }
);


/* =========================================================
   ADD PRICE FORM SUBMIT
========================================================= */

$(document).on(
    'submit',
    '#formAddPrice',
    function (e) {

        e.preventDefault();


        var form = this;


        var commodityId =
            $(form).data('commodity-id');


        console.log(
            '[DOE] Add Price form submitted'
        );


        console.log(
            '[DOE] Commodity ID:',
            commodityId
        );


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


        var commodity =
            $(form)
                .find(
                    'input[name="item_description"]'
                )
                .val();


        var category =
            $(form)
                .find(
                    'input[name="category"]'
                )
                .val();


        var unit =
            $(form)
                .find(
                    'input[name="unit"]'
                )
                .val();


        var storeName =
            $(form)
                .find(
                    'input[name="store_name"]'
                )
                .val();


        var srpPrice =
            $(form)
                .find(
                    'input[name="srp_price"]'
                )
                .val();


        var prevailingPrice =
            $(form)
                .find(
                    'input[name="prevailing_price"]'
                )
                .val();


        /* ================================================
           VALIDATION
        ================================================ */

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


        /* ================================================
           FORM DATA
        ================================================ */

        var formData =
            new FormData();


        formData.append(
            'commodity_id',
            commodityId
        );

        formData.append(
            'commodity',
            commodity
        );

        formData.append(
            'agency_id',
            DOE_AGENCY_ID
        );

        formData.append(
            'agency',
            DOE_AGENCY_CODE
        );

        formData.append(
            'category',
            category
        );

        formData.append(
            'unit',
            unit
        );

        formData.append(
            'item_description',
            commodity
        );

        formData.append(
            'store_name',
            storeName
        );

        formData.append(
            'srp_price',
            srpPrice
        );

        formData.append(
            'prevailing_price',
            prevailingPrice
        );


        console.log(
            '[DOE] Add form data:'
        );


        for (
            var pair of formData.entries()
        ) {

            console.log(
                pair[0] + ':',
                pair[1]
            );
        }


        var submitButton =
            $(form)
                .find(
                    'button[type="submit"]'
                );


        submitButton
            .prop(
                'disabled',
                true
            )
            .html(
                '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...'
            );


        /* ================================================
           POST
        ================================================ */

        var apiUrl =
            getPriceApiUrl();


        console.log(
            '[DOE] Saving price to:',
            apiUrl
        );


        fetch(
            apiUrl,
            {
                method: 'POST',
                body: formData
            }
        )


        .then(function (response) {

            return response.text();
        })


        .then(function (text) {

            console.log(
                '[DOE] Save API raw response:',
                text
            );


            var result;


            try {

                result =
                    JSON.parse(text);

            } catch (error) {

                throw new Error(
                    'Server returned invalid JSON. Check PHP errors.'
                );
            }


            console.log(
                '[DOE] Save API response:',
                result
            );


            if (
                !result ||
                result.status !== 'success'
            ) {

                throw new Error(
                    result.message ||
                    'Unable to save price entry.'
                );
            }


            $('#modalAddPrice')
                .modal('hide');


            form.reset();


            $(form)
                .removeData(
                    'commodity-id'
                );


            Swal.fire({

                icon: 'success',

                title: 'Saved!',

                text:
                    result.message ||
                    'Price entry saved successfully.',

                timer: 1800,

                showConfirmButton: false

            });


            loadPriceData();
        })


        .catch(function (error) {

            console.error(
                '[DOE] Save Price Error:',
                error
            );


            Swal.fire({

                icon: 'error',

                title: 'Unable to Save',

                text:
                    error.message ||
                    'An error occurred while saving the price entry.'

            });
        })


        .finally(function () {

            submitButton
                .prop(
                    'disabled',
                    false
                )
                .html(
                    '<i class="fa-solid fa-floppy-disk mr-1"></i> Save Price Entry'
                );
        });
    }
);


/* =========================================================
   EDIT PRICE BUTTON
========================================================= */

$(document).on(
    'click',
    '.btn-edit',
    function () {

        var priceId =
            $(this).data('id');

        console.log(
            '[DOE] Edit clicked. Price ID:',
            priceId
        );

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

        var row =
            priceMonitoringData.find(
                function (item) {

                    return String(item.id) ===
                        String(priceId);
                }
            );

        console.log(
            '[DOE] Selected price record:',
            row
        );

        if (!row) {

            Swal.fire(
                'Error',
                'Price record could not be found.',
                'error'
            );

            return;
        }

        /*
         * Store IDs globally
         */
        currentEditPriceId =
            row.id;

        currentEditCommodityId =
            row.commodity_id;

        console.log(
            '[DOE] Stored edit IDs:',
            {
                priceId: currentEditPriceId,
                commodityId: currentEditCommodityId
            }
        );


        /*
         * IMPORTANT:
         * Also store IDs directly on the form.
         */
        $('#formEditPrice')
            .data(
                'price-id',
                row.id
            )
            .data(
                'commodity-id',
                row.commodity_id
            );


        /*
         * Hidden inputs
         */
        $('#formEditPrice input[name="price_id"]')
            .val(row.id);

        $('#formEditPrice input[name="commodity_id"]')
            .val(row.commodity_id || '');


        /*
         * Agency
         */
        $('#formEditPrice input[name="agency_id"]')
            .val(
                row.monitored_by_agency_id ||
                row.agency_id ||
                DOE_AGENCY_ID
            );


        /*
         * Category
         */
        $('#formEditPrice input[name="category"]')
            .val(
                row.category_name || ''
            );


        /*
         * Unit
         */
        $('#formEditPrice input[name="unit"]')
            .val(
                row.unit_of_measure || ''
            );


        /*
         * Product
         */
        $('#formEditPrice input[name="item_description"]')
            .val(
                row.product_name || ''
            );


        /*
         * Store
         */
        $('#formEditPrice input[name="store_name"]')
            .val(
                row.store_name || ''
            );


        /*
         * SRP
         */
        $('#formEditPrice input[name="srp_price"]')
            .val(
                row.srp !== null &&
                row.srp !== undefined
                    ? row.srp
                    : ''
            );


        /*
         * Prevailing price
         */
        $('#formEditPrice input[name="prevailing_price"]')
            .val(
                row.prevailing_price !== null &&
                row.prevailing_price !== undefined
                    ? row.prevailing_price
                    : ''
            );


        console.log(
            '[DOE] Edit modal populated:',
            {
                priceId: row.id,
                commodityId: row.commodity_id,
                agencyId:
                    row.monitored_by_agency_id ||
                    row.agency_id ||
                    DOE_AGENCY_ID
            }
        );


        $('#modalEditPrice')
            .modal('show');
    }
);


/* =========================================================
   EDIT PRICE FORM SUBMIT
========================================================= */

$(document).on(
    'submit',
    '#formEditPrice',
    function (e) {

        e.preventDefault();


        console.log(
            '[DOE] Edit Price form submitted'
        );


        var form = this;


        /*
         * Get price ID from data attribute.
         */

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


        /*
         * Fallback to hidden input.
         */

        if (
            !commodityId
        ) {

            commodityId =
                $(form)
                    .find(
                        'input[name="commodity_id"]'
                    )
                    .val();
        }


        console.log(
            '[DOE] Price ID:',
            priceId
        );


        console.log(
            '[DOE] Commodity ID:',
            commodityId
        );


        /*
         * PRICE ID IS REQUIRED
         */

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


        /*
         * Read values
         */

        var agencyId =
            $(form)
                .find(
                    'input[name="agency_id"]'
                )
                .val() ||
            DOE_AGENCY_ID;


        var commodity =
            $(form)
                .find(
                    'input[name="item_description"]'
                )
                .val();


        var category =
            $(form)
                .find(
                    'input[name="category"]'
                )
                .val();


        var unit =
            $(form)
                .find(
                    'input[name="unit"]'
                )
                .val();


        var storeName =
            $(form)
                .find(
                    'input[name="store_name"]'
                )
                .val();


        var srpPrice =
            $(form)
                .find(
                    'input[name="srp_price"]'
                )
                .val();


        var prevailingPrice =
            $(form)
                .find(
                    'input[name="prevailing_price"]'
                )
                .val();


        console.log(
            '[DOE] Edit values:',
            {
                priceId: priceId,
                commodityId: commodityId,
                agencyId: agencyId,
                commodity: commodity,
                category: category,
                unit: unit,
                storeName: storeName,
                srpPrice: srpPrice,
                prevailingPrice: prevailingPrice
            }
        );


        /* ================================================
           VALIDATION
        ================================================ */

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


        /* ================================================
           IMPORTANT
           
           PUT REQUEST MUST USE JSON
           
           Your routes.php reads:
           
           file_get_contents('php://input')
           
           and json_decode().
           
           PHP does not automatically put
           PUT FormData into $_POST.
        ================================================ */

        var updateData = {

            id: priceId,

            commodity_id:
                commodityId || null,

            agency_id:
                agencyId,

            agency:
                DOE_AGENCY_CODE,

            commodity:
                commodity,

            category:
                category,

            unit:
                unit,

            item_description:
                commodity,

            store_name:
                storeName,

            srp_price:
                srpPrice,

            prevailing_price:
                prevailingPrice
        };


        console.log(
            '[DOE] Update JSON:',
            updateData
        );


        /* ================================================
           DISABLE BUTTON
        ================================================ */

        var submitButton =
            $(form)
                .find(
                    'button[type="submit"]'
                );


        submitButton
            .prop(
                'disabled',
                true
            )
            .html(
                '<i class="fas fa-spinner fa-spin mr-1"></i> Updating...'
            );


        /* ================================================
           API URL
        ================================================ */

        var apiUrl =
            getPriceApiUrl(priceId);


        console.log(
            '[DOE] Updating price:',
            apiUrl
        );


        /* ================================================
           PUT REQUEST
           
           JSON instead of FormData
        ================================================ */

        fetch(
            apiUrl,
            {
                method: 'PUT',

                headers: {
                    'Content-Type':
                        'application/json',

                    'Accept':
                        'application/json'
                },

                body:
                    JSON.stringify(
                        updateData
                    )
            }
        )


        /* ================================================
           READ RESPONSE
        ================================================ */

        .then(function (response) {

            console.log(
                '[DOE] Update HTTP status:',
                response.status
            );


            return response.text();
        })


        /* ================================================
           PROCESS RESPONSE
        ================================================ */

        .then(function (text) {

            console.log(
                '[DOE] Update raw response:',
                text
            );


            var result;


            try {

                result =
                    JSON.parse(text);

            } catch (error) {

                console.error(
                    '[DOE] Invalid JSON response:',
                    text
                );


                throw new Error(
                    'Server returned invalid JSON. Check PHP errors.'
                );
            }


            console.log(
                '[DOE] Update API response:',
                result
            );


            if (
                !result ||
                result.status !== 'success'
            ) {

                throw new Error(
                    result.message ||
                    'Unable to update price entry.'
                );
            }


            /* =========================================
               SUCCESS
            ========================================= */

            $('#modalEditPrice')
                .modal('hide');


            Swal.fire({

                icon: 'success',

                title: 'Updated!',

                text:
                    result.message ||
                    'Price entry updated successfully.',

                timer: 1800,

                showConfirmButton: false

            });


            /*
             * Reload table
             */

            loadPriceData();
        })


        /* ================================================
           ERROR
        ================================================ */

        .catch(function (error) {

            console.error(
                '[DOE] Update Price Error:',
                error
            );


            Swal.fire({

                icon: 'error',

                title: 'Unable to Update',

                text:
                    error.message ||
                    'An error occurred while updating the price entry.'

            });
        })


        /* ================================================
           RE-ENABLE BUTTON
        ================================================ */

        .finally(function () {

            submitButton
                .prop(
                    'disabled',
                    false
                )
                .html(
                    '<i class="fa-solid fa-floppy-disk mr-1"></i> Update Price Entry'
                );
        });
    }
);


/* =========================================================
   PAGE READY
========================================================= */

$(document).ready(function () {

    console.log(
        '[DOE] doe-table.js loaded'
    );


    /* ================================================
       CREATE TABLE
    ================================================ */

    createPriceTable();


    /* ================================================
       LOAD DATA
    ================================================ */

    loadPriceData();


    /* ================================================
       CATEGORY FILTER
    ================================================ */

    $('#filter_category')
        .on(
            'change',
            function () {

                applyCategoryFilter(
                    $(this).val()
                );
            }
        );
});