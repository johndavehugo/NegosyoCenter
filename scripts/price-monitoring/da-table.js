/* ============================================================
   DA PRICE MONITORING TABLE
   Department of Agriculture
   Agency ID: 2
   ============================================================ */

'use strict';

/* ============================================================
   GLOBAL VARIABLES
   ============================================================ */

var priceMonitoringTable = null;
var priceMonitoringData = [];

var currentEditPriceId = null;
var currentEditCommodityId = null;

/* ============================================================
   DA CONFIGURATION
   ============================================================ */

var DA_AGENCY_ID = '2';
var DA_AGENCY_CODE = 'DA';

/*
 * IMPORTANT:
 * This is the ONLY function used to build the DA Price API URL.
 *
 * Expected URL:
 * http://localhost/NegosyoCenter/api/routes.php
 *     ?resource=price
 *     &agency_id=2
 *
 * With ID:
 * http://localhost/NegosyoCenter/api/routes.php
 *     ?resource=price
 *     &agency_id=2
 *     &id=123
 */

function buildDAPriceApiUrl(priceId) {

    var basePath = window.location.pathname.split('/pages/')[0];

    if (!basePath || basePath === '/') {
        basePath = '/NegosyoCenter';
    }

    var apiUrl =
        window.location.origin +
        basePath +
        '/api/routes.php';

    var params = new URLSearchParams();

    params.set('resource', 'price');
    params.set('agency_id', DA_AGENCY_ID);

    if (
        priceId !== undefined &&
        priceId !== null &&
        String(priceId).trim() !== ''
    ) {
        params.set('id', String(priceId));
    }

    return apiUrl + '?' + params.toString();
}

/* ============================================================
   SWEETALERT HELPER
   ============================================================ */

function showError(title, message) {

    if (typeof Swal !== 'undefined') {

        Swal.fire({
            icon: 'error',
            title: title || 'Error',
            text: message || 'An error occurred.'
        });

    } else {

        alert(
            (title || 'Error') +
            '\n\n' +
            (message || 'An error occurred.')
        );
    }
}

/* ============================================================
   MONEY FORMAT
   ============================================================ */

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

/* ============================================================
   STATUS BADGE
   ============================================================ */

function statusBadge(status) {

    var badgeClass = 'badge-secondary';
    var label = 'NO PRICE YET';

    switch (String(status || '').toUpperCase()) {

        case 'WITHIN_SRP':

            badgeClass = 'badge-compliant';
            label = 'WITHIN SRP';

            break;

        case 'OVERPRICED':

            badgeClass = 'badge-overpriced';
            label = 'OVERPRICED';

            break;

        case 'BELOW_SRP':

            badgeClass = 'badge-warning';
            label = 'BELOW SRP';

            break;
    }

    return `
        <span class="badge ${badgeClass}">
            ${label}
        </span>
    `;
}

/* ============================================================
   ESCAPE HTML
   ============================================================ */

function escapeHtml(value) {

    if (
        value === null ||
        value === undefined
    ) {
        return '';
    }

    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

/* ============================================================
   ACTION BUTTONS
   ============================================================ */

function renderActionButtons(row) {

    /*
     * No price record yet.
     * Allow user to add monitoring price.
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
                data-commodity-id="${escapeHtml(
                    row.commodity_id || ''
                )}"
                title="Add Price">

                <i class="fas fa-plus"></i>
            </button>
        `;
    }

    /*
     * Existing price record.
     */

    return `
        <button
            type="button"
            class="btn btn-primary btn-sm btn-edit"
            data-id="${escapeHtml(row.id)}"
            title="Edit">

            <i class="fas fa-edit"></i>
        </button>
    `;
}

/* ============================================================
   CATEGORY OPTIONS
   ============================================================ */

function buildCategoryOptions(data) {

    var categories = {};

    data.forEach(function (row) {

        if (
            row.category_name !== null &&
            row.category_name !== undefined &&
            String(row.category_name).trim() !== ''
        ) {

            categories[
                String(row.category_name).trim()
            ] = true;
        }
    });

    var html =
        '<option value="">All Agri Categories</option>';

    Object.keys(categories)
        .sort()
        .forEach(function (category) {

            html +=
                '<option value="' +
                escapeHtml(category) +
                '">' +
                escapeHtml(category) +
                '</option>';
        });

    var filter = $('#filter_category');

    if (!filter.length) {
        return;
    }

    /*
     * Destroy Select2 before replacing options.
     */

    if (
        $.fn.select2 &&
        filter.hasClass('select2-hidden-accessible')
    ) {
        filter.select2('destroy');
    }

    filter.html(html);

    /*
     * Reinitialize Select2.
     */

    if ($.fn.select2) {

        filter.select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    }
}

/* ============================================================
   STATISTICS
   ============================================================ */

function refreshStats(data) {

    var total = data.length;

    var compliant =
        data.filter(function (row) {

            return String(row.status || '').toUpperCase() ===
                'WITHIN_SRP';

        }).length;

    var overpriced =
        data.filter(function (row) {

            return String(row.status || '').toUpperCase() ===
                'OVERPRICED';

        }).length;

    $('#stat_total_items')
        .text(total + ' Items');

    $('#stat_compliant_stores')
        .text(compliant + ' Stores');

    $('#stat_overpriced_alerts')
        .text(overpriced + ' Items');
}

/* ============================================================
   CREATE DATATABLE
   ============================================================ */

function createPriceTable() {

    if (!$('#tblPriceMonitoring').length) {

        console.error(
            '[DA] Table #tblPriceMonitoring was not found.'
        );

        return;
    }

    if (
        $.fn.DataTable.isDataTable('#tblPriceMonitoring')
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
            pageLength: 10,

            language: {

                emptyTable:
                    'No agricultural price monitoring records found.',

                processing:
                    'Loading price data...',

                search:
                    'Search:'
            },

            columns: [

                /*
                ====================================================
                PRODUCT NAME
                ====================================================
                */

                {
                    data: 'product_name',

                    defaultContent: '-',

                    render: function (value) {

                        return escapeHtml(
                            value || '-'
                        );
                    }
                },

                /*
                ====================================================
                CATEGORY
                ====================================================
                */

                {
                    data: 'category_name',

                    defaultContent: '-',

                    render: function (value) {

                        return escapeHtml(
                            value || '-'
                        );
                    }
                },

                /*
                ====================================================
                BRAND / UNIT
                SAME AS DOE
                ====================================================
                */

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

                            return (
                                '<strong>' +
                                escapeHtml(brand) +
                                '</strong>' +
                                '<br>' +
                                '<small class="text-muted">' +
                                escapeHtml(unit) +
                                '</small>'
                            );
                        }

                        return escapeHtml(
                            brand ||
                            unit ||
                            '-'
                        );
                    }
                },

                /*
                ====================================================
                AGENCY
                SAME AS DOE
                ====================================================
                */

                {
                    data: null,

                    render: function (
                        data,
                        type,
                        row
                    ) {

                        /*
                        * DA agency name
                        */

                        if (
                            row.agency_name
                        ) {

                            return escapeHtml(
                                row.agency_name
                            );
                        }

                        /*
                        * Fallback
                        */

                        return 'Department of Agriculture';
                    }
                },

                /*
                ====================================================
                SRP
                ====================================================
                */

                {
                    data: 'srp',

                    defaultContent: null,

                    render: function (value) {

                        return formatMoney(value);
                    }
                },

                /*
                ====================================================
                PREVAILING PRICE
                ====================================================
                */

                {
                    data: 'prevailing_price',

                    defaultContent: null,

                    render: function (value) {

                        if (
                            value === null ||
                            value === undefined ||
                            value === ''
                        ) {

                            return (
                                '<span class="text-muted">' +
                                'Not Monitored' +
                                '</span>'
                            );
                        }

                        return formatMoney(value);
                    }
                },

                /*
                ====================================================
                STATUS
                ====================================================
                */

                {
                    data: null,

                    render: function (
                        data,
                        type,
                        row
                    ) {

                        if (!row.status) {

                            return (
                                '<span class="badge badge-secondary">' +
                                'NO PRICE YET' +
                                '</span>'
                            );
                        }

                        return statusBadge(
                            row.status
                        );
                    }
                },

                /*
                ====================================================
                ACTIONS
                ====================================================
                */

                {
                    data: null,

                    orderable: false,

                    searchable: false,

                    className:
                        'text-center',

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
/* ============================================================
   LOAD PRICE DATA
   ============================================================ */

function loadPriceData() {

    /*
     * THIS is the critical part.
     *
     * agency_id=2 is ALWAYS generated here.
     */

    var url =
        buildDAPriceApiUrl();

    console.log(
        '[DA] =================================================='
    );

    console.log(
        '[DA] Loading price data'
    );

    console.log(
        '[DA] Agency ID:',
        DA_AGENCY_ID
    );

    console.log(
        '[DA] Agency Code:',
        DA_AGENCY_CODE
    );

    console.log(
        '[DA] API URL:',
        url
    );

    /*
     * HARD VALIDATION
     */

    var parsedUrl;

    try {

        parsedUrl =
            new URL(url);

    } catch (error) {

        console.error(
            '[DA] Invalid API URL:',
            error
        );

        showError(
            'Configuration Error',
            'Invalid DA Price API URL.'
        );

        return;
    }

    var agencyFromUrl =
        parsedUrl.searchParams.get(
            'agency_id'
        );

    console.log(
        '[DA] agency_id from URL:',
        agencyFromUrl
    );

    if (
        String(agencyFromUrl) !==
        String(DA_AGENCY_ID)
    ) {

        console.error(
            '[DA] ❌ CRITICAL: agency_id=2 is missing from URL!'
        );

        console.error(
            '[DA] Actual URL:',
            url
        );

        showError(
            'Configuration Error',
            'Agency ID is missing from the DA Price API URL.'
        );

        return;
    }

    console.log(
        '[DA] ✅ agency_id=2 confirmed in URL'
    );

    /*
     * FETCH API
     */

    fetch(url, {

        method: 'GET',

        headers: {
            'Accept': 'application/json'
        }

    })

    .then(function (response) {

        console.log(
            '[DA] HTTP Status:',
            response.status
        );

        if (!response.ok) {

            throw new Error(
                'HTTP ' +
                response.status +
                ' - Unable to load price data.'
            );
        }

        return response.text();
    })

    .then(function (text) {

        console.log(
            '[DA] API RAW RESPONSE:',
            text
        );

        var result;

        try {

            result =
                JSON.parse(text);

        } catch (error) {

            console.error(
                '[DA] JSON PARSE ERROR:',
                error
            );

            throw new Error(
                'Server returned invalid JSON. Check your PHP API for errors.'
            );
        }

        console.log(
            '[DA] API RESPONSE:',
            result
        );

        if (
            !result ||
            result.status !== 'success'
        ) {

            throw new Error(
                result &&
                result.message
                    ? result.message
                    : 'Unable to load price data.'
            );
        }

        var rows =
            Array.isArray(result.data)
                ? result.data
                : [];

        console.log(
            '[DA] Total Rows:',
            rows.length
        );

        /*
         * Store data globally.
         */

        priceMonitoringData =
            rows;

        /*
         * Create DataTable if necessary.
         */

        if (!priceMonitoringTable) {

            createPriceTable();
        }

        if (!priceMonitoringTable) {

            throw new Error(
                'Price monitoring table could not be initialized.'
            );
        }

        /*
         * Update table.
         */

        priceMonitoringTable
            .clear();

        priceMonitoringTable
            .rows
            .add(rows);

        priceMonitoringTable
            .draw();

        /*
         * Update category filter.
         */

        buildCategoryOptions(
            rows
        );

        /*
         * Update statistics.
         */

        refreshStats(
            rows
        );

    })

    .catch(function (error) {

        console.error(
            '[DA] ❌ Price API Error:',
            error
        );

        showError(
            'Unable to Load Price Data',
            error.message ||
            'Unable to load DA price data.'
        );
    });
}

/* ============================================================
   CATEGORY FILTER
   ============================================================ */

function applyCategoryFilter(category) {

    if (!priceMonitoringTable) {
        return;
    }

    if (category) {

        var escaped =
            $.fn.dataTable.util.escapeRegex(
                category
            );

        priceMonitoringTable
            .column(1)
            .search(
                '^' +
                escaped +
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

/* ============================================================
   ADD PRICE BUTTON
   ============================================================ */

$(document).on(
    'click',
    '.btn-add-price',
    function () {

        var commodityId =
            $(this).data(
                'commodity-id'
            );

        console.log(
            '[DA] Add Price - Commodity ID:',
            commodityId
        );

        if (!commodityId) {

            showError(
                'Error',
                'Commodity ID is missing.'
            );

            return;
        }

        var row =
            priceMonitoringData.find(
                function (item) {

                    return String(
                        item.commodity_id
                    ) ===
                    String(
                        commodityId
                    );
                }
            );

        if (!row) {

            showError(
                'Error',
                'Commodity information could not be found.'
            );

            return;
        }

        var form =
            $('#formAddPrice');

        /*
         * Store commodity ID.
         */

        form.data(
            'commodity-id',
            row.commodity_id
        );

        /*
         * Agency ID.
         */

        form.find(
            'input[name="agency_id"]'
        ).val(
            DA_AGENCY_ID
        );

        /*
         * Category.
         */

        form.find(
            'select[name="category"]'
        ).val(
            row.category_name || ''
        );

        /*
         * Unit.
         */

        form.find(
            'input[name="unit"]'
        ).val(
            row.unit_of_measure || ''
        );

        /*
         * Commodity / Product.
         */

        form.find(
            'input[name="item_description"]'
        ).val(
            row.product_name || ''
        );

        /*
         * Store starts empty.
         */

        form.find(
            'input[name="store_name"]'
        ).val('');

        /*
         * SRP.
         */

        form.find(
            'input[name="srp_price"]'
        ).val(
            row.srp !== null &&
            row.srp !== undefined
                ? row.srp
                : ''
        );


        console.log(
    '[DA] ROW SRP FROM API:',
    row.srp
);

console.log(
    '[DA] EDIT FORM SRP AFTER SET:',
    form.find('input[name="srp_price"]').val()
);
        /*
         * Prevailing price starts empty.
         */

        form.find(
            'input[name="prevailing_price"]'
        ).val('');

        /*
         * Show modal.
         */

        $('#modalAddPrice')
            .modal('show');
    }
);

/* ============================================================
   ADD PRICE FORM SUBMIT
   ============================================================ */

$(document).on(
    'submit',
    '#formAddPrice',
    function (e) {

        e.preventDefault();

        var form =
            this;

        var $form =
            $(form);

        var commodityId =
            $form.data(
                'commodity-id'
            );

        console.log(
            '[DA] =================================================='
        );

        console.log(
            '[DA] ADD PRICE SUBMIT'
        );

        console.log(
            '[DA] Commodity ID:',
            commodityId
        );

        /*
         * Validate commodity.
         */

        if (
            !commodityId ||
            String(commodityId) === '0'
        ) {

            showError(
                'Unable to Save',
                'Commodity ID is missing.'
            );

            return;
        }

        var commodity =
            $form.find(
                'input[name="item_description"]'
            ).val();

        var category =
            $form.find(
                'select[name="category"]'
            ).val();

        var unit =
            $form.find(
                'input[name="unit"]'
            ).val();

        var storeName =
            $form.find(
                'input[name="store_name"]'
            ).val();

        var srpPrice =
            $form.find(
                'input[name="srp_price"]'
            ).val();

        var prevailingPrice =
            $form.find(
                'input[name="prevailing_price"]'
            ).val();

        commodity =
            commodity
                ? commodity.trim()
                : '';

        category =
            category
                ? category.trim()
                : '';

        unit =
            unit
                ? unit.trim()
                : '';

        storeName =
            storeName
                ? storeName.trim()
                : '';

        /*
         * Validation.
         */

        if (!commodity) {

            showError(
                'Unable to Save',
                'Commodity/Product Description is required.'
            );

            return;
        }

        if (!category) {

            showError(
                'Unable to Save',
                'Category is required.'
            );

            return;
        }

        if (!unit) {

            showError(
                'Unable to Save',
                'Unit measurement is required.'
            );

            return;
        }

        
        if (
            prevailingPrice === '' ||
            prevailingPrice === null ||
            prevailingPrice === undefined
        ) {

            showError(
                'Unable to Save',
                'Prevailing Price is required.'
            );

            return;
        }

        /*
         * FormData.
         */

        var formData =
            new FormData();

        formData.append(
            'commodity_id',
            commodityId
        );

        formData.append(
            'agency_id',
            DA_AGENCY_ID
        );

        formData.append(
            'monitored_by_agency_id',
            DA_AGENCY_ID
        );

        formData.append(
            'agency',
            DA_AGENCY_CODE
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
            'commodity',
            commodity
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

        /*
         * API URL.
         */

        var apiUrl =
            buildDAPriceApiUrl();

        console.log(
            '[DA] ADD URL:',
            apiUrl
        );

        /*
         * Validate URL again.
         */

        var checkUrl =
            new URL(apiUrl);

        if (
            checkUrl.searchParams.get(
                'agency_id'
            ) !== DA_AGENCY_ID
        ) {

            showError(
                'Configuration Error',
                'Agency ID 2 is missing from the API URL.'
            );

            return;
        }

        /*
         * Submit button.
         */

        var submitButton =
            $form.find(
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

        /*
         * POST.
         */

        fetch(apiUrl, {

            method: 'POST',

            body: formData,

            headers: {
                'Accept': 'application/json'
            }

        })

        .then(function (response) {

            console.log(
                '[DA] ADD HTTP STATUS:',
                response.status
            );

            return response.text();
        })

        .then(function (text) {

            console.log(
                '[DA] ADD RAW RESPONSE:',
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

            if (
                !result ||
                result.status !== 'success'
            ) {

                throw new Error(
                    result.message ||
                    'Unable to save price entry.'
                );
            }

            /*
             * Close modal.
             */

            $('#modalAddPrice')
                .modal('hide');

            /*
             * Reset form.
             */

            form.reset();

            $form.removeData(
                'commodity-id'
            );

            /*
             * Success message.
             */

            if (typeof Swal !== 'undefined') {

                Swal.fire({

                    icon: 'success',

                    title: 'Saved!',

                    text:
                        result.message ||
                        'DA price entry saved successfully.',

                    timer: 1800,

                    showConfirmButton: false
                });
            }

            /*
             * Reload table.
             */

            loadPriceData();
        })

        .catch(function (error) {

            console.error(
                '[DA] ADD ERROR:',
                error
            );

            showError(
                'Unable to Save',
                error.message ||
                'An error occurred while saving.'
            );
        })

        .finally(function () {

            submitButton
                .prop(
                    'disabled',
                    false
                )
                .html(
                    '<i class="fa-solid fa-floppy-disk mr-1"></i> Save DA Entry'
                );
        });
    }
);

/* ============================================================
   EDIT PRICE BUTTON
   ============================================================ */

$(document).on(
    'click',
    '.btn-edit',
    function () {

        var priceId =
            $(this).data('id');

        console.log(
            '[DA] Edit Price ID:',
            priceId
        );

        if (
            priceId === undefined ||
            priceId === null ||
            priceId === ''
        ) {

            showError(
                'Error',
                'Price record ID is missing.'
            );

            return;
        }

        var row =
            priceMonitoringData.find(
                function (item) {

                    return String(
                        item.id
                    ) ===
                    String(
                        priceId
                    );
                }
            );

        if (!row) {

            showError(
                'Error',
                'Price record could not be found.'
            );

            return;
        }

        currentEditPriceId =
            row.id;

        currentEditCommodityId =
            row.commodity_id;

        var form =
            $('#formEditPrice');

        /*
         * IDs.
         */

        form.find(
            'input[name="price_id"]'
        ).val(
            row.id
        );

        form.find(
            'input[name="commodity_id"]'
        ).val(
            row.commodity_id || ''
        );

        /*
         * Agency.
         */

        form.find(
            'input[name="agency_id"]'
        ).val(
            row.monitored_by_agency_id ||
            row.agency_id ||
            DA_AGENCY_ID
        );

        /*
         * Category.
         */

        form.find(
            'input[name="category"]'
        ).val(
            row.category_name || ''
        );

        /*
         * Unit.
         */

        form.find(
            'input[name="unit"]'
        ).val(
            row.unit_of_measure || ''
        );

        /*
         * Product.
         */

        form.find(
            'input[name="item_description"]'
        ).val(
            row.product_name || ''
        );

        /*
         * Store.
         */

        form.find(
            'input[name="store_name"]'
        ).val(
            row.store_name || ''
        );

        /*
         * SRP.
         */

        form.find(
            'input[name="srp_price"]'
        ).val(
            row.srp !== null &&
            row.srp !== undefined
                ? row.srp
                : ''
        );

        /*
         * Prevailing price.
         */

        form.find(
            'input[name="prevailing_price"]'
        ).val(
            row.prevailing_price !== null &&
            row.prevailing_price !== undefined
                ? row.prevailing_price
                : ''
        );

        /*
         * Show modal.
         */

        $('#modalEditPrice')
            .modal('show');
    }
);

/* ============================================================
   EDIT PRICE FORM SUBMIT
   ============================================================ */

$(document).on(
    'submit',
    '#formEditPrice',
    function (e) {

        e.preventDefault();

        var form =
            this;

        var $form =
            $(form);

        var priceId =
            currentEditPriceId ||
            $form.find(
                'input[name="price_id"]'
            ).val();

        var commodityId =
            currentEditCommodityId ||
            $form.find(
                'input[name="commodity_id"]'
            ).val();

        console.log(
            '[DA] =================================================='
        );

        console.log(
            '[DA] UPDATE PRICE'
        );

        console.log(
            '[DA] Price ID:',
            priceId
        );

        console.log(
            '[DA] Commodity ID:',
            commodityId
        );

        if (!priceId) {

            showError(
                'Unable to Update',
                'Price record ID is missing.'
            );

            return;
        }

        var agencyId =
            $form.find(
                'input[name="agency_id"]'
            ).val() ||
            DA_AGENCY_ID;

        var commodity =
            $form.find(
                'input[name="item_description"]'
            ).val();

        var category =
            $form.find(
                'input[name="category"]'
            ).val();

        var unit =
            $form.find(
                'input[name="unit"]'
            ).val();

        var storeName =
            $form.find(
                'input[name="store_name"]'
            ).val();

        var srpPrice =
            $form.find(
                'input[name="srp_price"]'
            ).val();

        var prevailingPrice =
            $form.find(
                'input[name="prevailing_price"]'
            ).val();

        commodity =
            commodity
                ? commodity.trim()
                : '';

        category =
            category
                ? category.trim()
                : '';

        unit =
            unit
                ? unit.trim()
                : '';

        storeName =
            storeName
                ? storeName.trim()
                : '';

        /*
         * Validation.
         */

        if (!commodity) {

            showError(
                'Unable to Update',
                'Commodity/Product Description is required.'
            );

            return;
        }

        if (!category) {

            showError(
                'Unable to Update',
                'Category is required.'
            );

            return;
        }

        if (!unit) {

            showError(
                'Unable to Update',
                'Unit measurement is required.'
            );

            return;
        }

        

        if (
            prevailingPrice === '' ||
            prevailingPrice === null ||
            prevailingPrice === undefined
        ) {

            showError(
                'Unable to Update',
                'Prevailing Price is required.'
            );

            return;
        }

        /*
         * Update object.
         */

        /* ============================================================
   CALCULATE STATUS AUTOMATICALLY
   ============================================================ */

var srpValue = parseFloat(srpPrice);
var prevailingValue = parseFloat(prevailingPrice);

if (isNaN(srpValue)) {
    showError(
        'Unable to Update',
        'SRP must be a valid number.'
    );
    return;
}

if (isNaN(prevailingValue)) {
    showError(
        'Unable to Update',
        'Prevailing Price must be a valid number.'
    );
    return;
}

var calculatedStatus = '';

if (prevailingValue > srpValue) {

    calculatedStatus = 'OVERPRICED';

} else if (prevailingValue < srpValue) {

    calculatedStatus = 'BELOW_SRP';

} else {

    calculatedStatus = 'WITHIN_SRP';
}


/* ============================================================
   UPDATE OBJECT
   ============================================================ */

var updateData = {

    id: priceId,

    commodity_id: commodityId || null,

    agency_id: agencyId,

    monitored_by_agency_id: agencyId,

    agency: DA_AGENCY_CODE,

    commodity: commodity,

    category: category,

    unit: unit,

    item_description: commodity,

    store_name: storeName,

    /* IMPORTANT */
    srp: srpValue,

    srp_price: srpValue,

    prevailing_price: prevailingValue,

    /* AUTOMATIC STATUS */
    status: calculatedStatus
};

console.log(
    '[DA] SRP:',
    srpValue
);

console.log(
    '[DA] Prevailing Price:',
    prevailingValue
);

console.log(
    '[DA] Calculated Status:',
    calculatedStatus
);

console.log(
    '[DA] FINAL UPDATE DATA:',
    updateData
);

        /*
         * API URL WITH ID.
         */

        var apiUrl =
            buildDAPriceApiUrl(
                priceId
            );

        console.log(
            '[DA] UPDATE URL:',
            apiUrl
        );

        /*
         * Verify URL.
         */

        var checkUrl =
            new URL(apiUrl);

        console.log(
            '[DA] UPDATE agency_id:',
            checkUrl.searchParams.get(
                'agency_id'
            )
        );

        console.log(
            '[DA] UPDATE id:',
            checkUrl.searchParams.get(
                'id'
            )
        );

        if (
            checkUrl.searchParams.get(
                'agency_id'
            ) !== DA_AGENCY_ID
        ) {

            showError(
                'Configuration Error',
                'Agency ID 2 is missing from the API URL.'
            );

            return;
        }

        /*
         * Submit button.
         */

        var submitButton =
            $form.find(
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

        /*
         * PUT request.
         */

        fetch(apiUrl, {

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

        })

        .then(function (response) {

            console.log(
                '[DA] UPDATE HTTP STATUS:',
                response.status
            );

            return response.text();
        })

        .then(function (text) {

            console.log(
                '[DA] UPDATE RAW RESPONSE:',
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

            if (
                !result ||
                result.status !== 'success'
            ) {

                throw new Error(
                    result.message ||
                    'Unable to update price entry.'
                );
            }

            /*
             * Close modal.
             */

            $('#modalEditPrice')
                .modal('hide');

            /*
             * Clear edit state.
             */

            currentEditPriceId =
                null;

            currentEditCommodityId =
                null;

            /*
             * Success.
             */

            if (typeof Swal !== 'undefined') {

                Swal.fire({

                    icon: 'success',

                    title: 'Updated!',

                    text:
                        result.message ||
                        'DA price entry updated successfully.',

                    timer: 1800,

                    showConfirmButton: false
                });
            }

            /*
             * Reload data.
             */

            loadPriceData();
        })

        .catch(function (error) {

            console.error(
                '[DA] UPDATE ERROR:',
                error
            );

            showError(
                'Unable to Update',
                error.message ||
                'An error occurred while updating.'
            );
        })

        .finally(function () {

            submitButton
                .prop(
                    'disabled',
                    false
                )
                .html(
                    '<i class="fa-solid fa-rotate mr-1"></i> Update Entry'
                );
        });
    }
);

/* ============================================================
   PAGE READY
   ============================================================ */

$(document).ready(function () {

    console.log(
        '[DA] =================================================='
    );

    console.log(
        '[DA] da-table.js LOADED'
    );

    console.log(
        '[DA] Agency ID:',
        DA_AGENCY_ID
    );

    console.log(
        '[DA] Agency Code:',
        DA_AGENCY_CODE
    );

    /*
     * ==========================================================
     * API URL TEST
     * ==========================================================
     */

    var testUrl =
        buildDAPriceApiUrl();

    console.log(
        '[DA] API TEST URL:',
        testUrl
    );

    /*
     * Parse URL.
     */

    try {

        var parsedTestUrl =
            new URL(testUrl);

        var testAgencyId =
            parsedTestUrl.searchParams.get(
                'agency_id'
            );

        console.log(
            '[DA] API TEST agency_id:',
            testAgencyId
        );

        if (
            String(testAgencyId) !==
            String(DA_AGENCY_ID)
        ) {

            console.error(
                '[DA] ❌ CRITICAL: agency_id is missing!'
            );

            console.error(
                '[DA] Actual URL:',
                testUrl
            );

            return;
        }

        console.log(
            '[DA] ✅ API URL IS CORRECT'
        );

    } catch (error) {

        console.error(
            '[DA] ❌ API URL PARSE ERROR:',
            error
        );

        return;
    }

    /*
     * ==========================================================
     * CREATE TABLE
     * ==========================================================
     */

    createPriceTable();

    /*
     * ==========================================================
     * LOAD DATA
     * ==========================================================
     */

    loadPriceData();

    /*
     * ==========================================================
     * CATEGORY FILTER
     * ==========================================================
     */

    $('#filter_category').on(
        'change',
        function () {

            applyCategoryFilter(
                $(this).val()
            );
        }
    );

    /*
     * ==========================================================
     * SELECT2
     * ==========================================================
     */

    if ($.fn.select2) {

        var categoryFilter =
            $('#filter_category');

        if (
            categoryFilter.length &&
            !categoryFilter.hasClass(
                'select2-hidden-accessible'
            )
        ) {

            categoryFilter.select2({

                theme:
                    'bootstrap4',

                width:
                    '100%'
            });
        }
    }

    console.log(
        '[DA] =================================================='
    );

});