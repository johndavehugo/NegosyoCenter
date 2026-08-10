$(document).ready(function () {

    let editingCommodityId = null;

    // =========================================================
    // LOAD CATEGORY DROPDOWN
    // =========================================================
    loadCategories();


    // =========================================================
    // INITIALIZE COMMODITY DATATABLE
    // =========================================================
    const table = $('#tblCommodity').DataTable({

        processing: true,
        destroy: true,

        ajax: {
            url: '../../api/routes.php?resource=commodity',
            type: 'GET',
            dataType: 'json',

            dataSrc: function (json) {

                console.log('Commodity API Response:', json);

                if (json.status === 'success') {
                    return json.data || [];
                }

                Swal.fire(
                    'Error',
                    json.message || 'Unable to load commodities.',
                    'error'
                );

                return [];
            },

            error: function (xhr) {

                console.error(
                    'Commodity API Error:',
                    xhr.responseText
                );

                Swal.fire(
                    'Error',
                    'Failed to connect to commodity API.',
                    'error'
                );
            }
        },

        columns: [

            // =================================================
            // ID
            // =================================================
            {
                data: 'id',
                defaultContent: '-'
            },

            // =================================================
            // PRODUCT NAME
            // =================================================
            {
                data: 'product_name',
                defaultContent: '-'
            },

            // =================================================
            // CATEGORY
            // =================================================
            {
                data: 'category_name',
                defaultContent: '-'
            },

            // =================================================
            // BRAND
            // =================================================
            {
                data: 'brand_name',
                defaultContent: '-'
            },

            // =================================================
            // UNIT
            // =================================================
            {
                data: 'unit_of_measure',
                defaultContent: '-'
            },

            // =================================================
            // SRP / PRICE
            // =================================================
            {
                data: 'srp',
                defaultContent: '0.00',

                render: function (data) {

                    if (
                        data === null ||
                        data === undefined ||
                        data === ''
                    ) {
                        return '₱0.00';
                    }

                    const amount = Number(data);

                    if (isNaN(amount)) {
                        return '₱0.00';
                    }

                    return '₱' + amount.toLocaleString(
                        'en-PH',
                        {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }
                    );
                }
            },

            // =================================================
            // AGENCY
            // =================================================
            {
                data: 'agency_name',
                defaultContent: '-'
            },

            // =================================================
            // OPTIONS
            // =================================================
            {
                data: null,
                orderable: false,
                searchable: false,

                render: function (row) {

                    return `
                        <button
                            type="button"
                            class="btn btn-warning btn-sm edit"
                            data-id="${row.id}"
                            title="Edit">

                            <i class="fas fa-edit"></i>

                        </button>

                        <button
                            type="button"
                            class="btn btn-danger btn-sm delete"
                            data-id="${row.id}"
                            title="Delete">

                            <i class="fas fa-trash"></i>

                        </button>
                    `;
                }
            }

        ]

    });


    // =========================================================
    // OPEN ADD COMMODITY MODAL
    // =========================================================
    $('#btn_add_calamity').on('click', function () {

        // Reset edit mode
        editingCommodityId = null;

        // Clear fields
        $('#product_name').val('');
        $('#category_id').val('');
        $('#brand_name').val('');
        $('#unit_of_measure').val('');

        // Change modal to ADD mode
        $('#commodityModalLabel').text('Add Commodity');
        $('#btnSaveCommodity').text('Save');

    });


    // =========================================================
    // EDIT COMMODITY
    // =========================================================
    $(document).on('click', '.edit', function () {

        const id = $(this).data('id');

        console.log('Editing commodity ID:', id);

        $.ajax({

            url:
                '../../api/routes.php?resource=commodity&id=' +
                encodeURIComponent(id),

            type: 'GET',
            dataType: 'json',

            success: function (response) {

                console.log(
                    'Get Commodity Response:',
                    response
                );

                if (response.status !== 'success') {

                    Swal.fire(
                        'Error',
                        response.message ||
                        'Unable to load commodity.',
                        'error'
                    );

                    return;
                }

                const item = response.data;

                if (!item) {

                    Swal.fire(
                        'Error',
                        'Commodity record not found.',
                        'error'
                    );

                    return;
                }


                // =================================================
                // STORE COMMODITY ID
                // =================================================

                editingCommodityId = item.id;

                console.log(
                    'Editing Commodity ID stored:',
                    editingCommodityId
                );


                // =================================================
                // LOAD FORM VALUES
                // =================================================

                $('#product_name').val(
                    item.product_name || ''
                );

                $('#category_id').val(
                    item.category_id || ''
                );

                $('#brand_name').val(
                    item.brand_name || ''
                );

                $('#unit_of_measure').val(
                    item.unit_of_measure || ''
                );


                // =================================================
                // SRP IS NOT DISPLAYED IN THE FORM
                // =================================================
                //
                // We intentionally DO NOT put an SRP input
                // in the modal.
                //
                // The existing SRP will be preserved by PHP.
                //


                console.log(
                    'Loaded commodity:',
                    item
                );


                // =================================================
                // CHANGE MODAL TO EDIT MODE
                // =================================================

                $('#commodityModalLabel')
                    .text('Edit Commodity');

                $('#btnSaveCommodity')
                    .text('Update Commodity');


                // =================================================
                // SHOW MODAL
                // =================================================

                $('#addCalamityModal').modal('show');

            },

            error: function (xhr) {

                console.error(
                    'Get Commodity Error:',
                    xhr.responseText
                );

                Swal.fire(
                    'Error',
                    'Unable to load commodity.',
                    'error'
                );
            }

        });

    });


    // =========================================================
    // DELETE COMMODITY
    // =========================================================
    $(document).on('click', '.delete', function () {

        const id = $(this).data('id');

        Swal.fire({

            title: 'Delete Commodity?',
            text: 'This cannot be undone.',
            icon: 'warning',

            showCancelButton: true,

            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'

        }).then(function (result) {

            if (!result.isConfirmed) {
                return;
            }

            $.ajax({

                url:
                    '../../api/routes.php?resource=commodity&id=' +
                    encodeURIComponent(id),

                type: 'DELETE',

                dataType: 'json',

                success: function (response) {

                    console.log(
                        'Delete Commodity Response:',
                        response
                    );

                    if (response.status === 'success') {

                        Swal.fire(
                            'Deleted!',
                            response.message ||
                            'Commodity deleted successfully.',
                            'success'
                        );

                        table.ajax.reload(
                            null,
                            false
                        );

                    } else {

                        Swal.fire(
                            'Error',
                            response.message ||
                            'Unable to delete commodity.',
                            'error'
                        );
                    }
                },

                error: function (xhr) {

                    console.error(
                        'Delete Commodity Error:',
                        xhr.responseText
                    );

                    Swal.fire(
                        'Error',
                        'Unable to delete commodity.',
                        'error'
                    );
                }

            });

        });

    });


    // =========================================================
    // SAVE / UPDATE COMMODITY
    // =========================================================
    $('#btnSaveCommodity').on('click', function () {

        console.log('========== SAVE / UPDATE ==========');


        // =====================================================
        // GET FORM VALUES SAFELY
        // =====================================================

        const productName =
            String($('#product_name').val() || '').trim();

        const categoryId =
            $('#category_id').val() || '';

        const brandName =
            String($('#brand_name').val() || '').trim();

        const unitOfMeasure =
            String($('#unit_of_measure').val() || '').trim();


        console.log(
            'COMMODITY FORM VALUES:',
            {
                editingCommodityId: editingCommodityId,
                productName: productName,
                categoryId: categoryId,
                brandName: brandName,
                unitOfMeasure: unitOfMeasure
            }
        );


        // =====================================================
        // VALIDATION
        // =====================================================

        if (!productName) {

            Swal.fire(
                'Required Field',
                'Please enter the Commodity Name.',
                'warning'
            );

            return;
        }


        if (!categoryId) {

            Swal.fire(
                'Required Field',
                'Please select a Category.',
                'warning'
            );

            return;
        }


        if (!unitOfMeasure) {

            Swal.fire(
                'Required Field',
                'Please enter the Unit of Measure.',
                'warning'
            );

            return;
        }


        // =====================================================
        // CHECK ADD OR EDIT
        // =====================================================

        const isEditing =
            editingCommodityId !== null &&
            editingCommodityId !== undefined &&
            editingCommodityId !== '';


        const requestType =
            isEditing ? 'PUT' : 'POST';


        console.log(
            'Request Type:',
            requestType
        );


        // =====================================================
        // DATA
        // =====================================================

        const commodityData = {

            product_name:
                productName,

            category_id:
                categoryId,

            brand_name:
                brandName,

            unit_of_measure:
                unitOfMeasure

        };


        // =====================================================
        // ONLY SEND ID DURING UPDATE
        // =====================================================

        if (isEditing) {

            commodityData.id =
                editingCommodityId;
        }


        console.log(
            'Sending Commodity Data:',
            JSON.stringify(commodityData)
        );


        // =====================================================
        // AJAX SAVE / UPDATE
        // =====================================================

        $.ajax({

            url:
                '../../api/routes.php?resource=commodity',

            type:
                requestType,

            contentType:
                'application/json',

            dataType:
                'json',

            data:
                JSON.stringify(commodityData),

            success: function (response) {

                console.log(
                    'Save/Update Response:',
                    response
                );


                if (response.status === 'success') {

                    Swal.fire(
                        'Success',
                        response.message ||
                        (
                            isEditing
                                ? 'Commodity updated successfully.'
                                : 'Commodity added successfully.'
                        ),
                        'success'
                    );


                    // =================================================
                    // CLOSE MODAL
                    // =================================================

                    $('#addCalamityModal')
                        .modal('hide');


                    // =================================================
                    // RESET FORM
                    // =================================================

                    $('#product_name').val('');
                    $('#category_id').val('');
                    $('#brand_name').val('');
                    $('#unit_of_measure').val('');


                    // =================================================
                    // RESET EDIT MODE
                    // =================================================

                    editingCommodityId = null;


                    $('#commodityModalLabel')
                        .text('Add Commodity');

                    $('#btnSaveCommodity')
                        .text('Save');


                    // =================================================
                    // RELOAD TABLE
                    // =================================================

                    table.ajax.reload(
                        null,
                        false
                    );


                } else {

                    Swal.fire(
                        'Error',
                        response.message ||
                        'Unable to save commodity.',
                        'error'
                    );
                }

            },

            error: function (xhr) {

                console.error(
                    'Save/Update HTTP Error:',
                    xhr.status
                );

                console.error(
                    'Server Response:',
                    xhr.responseText
                );


                let message =
                    'Unable to save/update commodity.';


                try {

                    const errorResponse =
                        JSON.parse(xhr.responseText);

                    if (errorResponse.message) {

                        message =
                            errorResponse.message;
                    }

                } catch (e) {

                    console.error(
                        'Could not parse server response.'
                    );
                }


                Swal.fire(
                    'Error',
                    message,
                    'error'
                );
            }

        });

    });


    // =========================================================
    // LOAD DOE CATEGORIES
    // =========================================================
    function loadCategories() {

        $.ajax({

            url:
                '../../api/routes.php' +
                '?resource=price-monitoring' +
                '&action=commodity_categories' +
                '&agency_id=3',

            type: 'GET',

            dataType: 'json',

            success: function (response) {

                console.log(
                    'DOE Categories:',
                    response
                );


                if (response.status !== 'success') {

                    Swal.fire(
                        'Error',
                        response.message ||
                        'Unable to load DOE categories.',
                        'error'
                    );

                    return;
                }


                let html =
                    '<option value="">-- Select Category --</option>';


                $.each(
                    response.data || [],
                    function (i, item) {

                        html += `
                            <option value="${item.category_id}">
                                ${item.category_name}
                            </option>
                        `;
                    }
                );


                $('#category_id').html(html);


                console.log(
                    'DOE categories loaded successfully.'
                );

            },

            error: function (xhr) {

                console.error(
                    'DOE Category API Error:',
                    xhr.responseText
                );

                Swal.fire(
                    'Error',
                    'Unable to load DOE categories.',
                    'error'
                );
            }

        });

    }

});