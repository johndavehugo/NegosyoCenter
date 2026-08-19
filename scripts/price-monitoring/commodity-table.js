$(function () {

    loadCategories();

    const table = $('#tblCommodity').DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        processing: true,
        destroy: true,

        ajax: {
            url: '../../api/routes.php/commodity',
            type: 'GET',
            dataSrc: function (response) {
                if (response.status === 'success') {
                    return response.data || [];
                }
                Swal.fire('Error', response.message || 'Unable to load commodities.', 'error');
                return [];
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                Swal.fire('Error', 'Failed to connect to commodity API.', 'error');
            }
        },

        columns: [
            { data: 'id', defaultContent: '-' },
            { data: 'product_name', defaultContent: '-' },
            { data: 'category_name', defaultContent: '-' },
            { data: 'brand_name', defaultContent: '-' },
            { data: 'unit_of_measure', defaultContent: '-' },
            {
                data: 'srp',
                defaultContent: '0.00',
                render: function (data) {
                    const amount = Number(data);
                    if (data === null || data === undefined || data === '' || isNaN(amount)) {
                        return '₱0.00';
                    }
                    return '₱' + amount.toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            },
            { data: 'agency_name', defaultContent: '-' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <button
                            class="btn btn-warning btn-sm btn-edit"
                            data-id="${row.id}"
                            title="Edit Commodity">
                            <i class="fas fa-edit"></i>
                        </button>

                        <button
                            class="btn btn-danger btn-sm btn-delete"
                            data-id="${row.id}"
                            title="Delete Commodity">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ]
    });


    // Open Add modal
    $('#btn_add_calamity').on('click', function () {
        $('#product_name').val('');
        $('#category_id').val('');
        $('#brand_name').val('');
        $('#unit_of_measure').val('');

        $('#commodityModalLabel').text('Add Commodity');
        $('#btnSaveCommodity').text('Save');
    });


    // Editing now handled in commodity-update.js (.btn-edit handler + updateCommodity())


    // Delete
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Delete Commodity?',
            text: 'This cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (!result.isConfirmed) return;

            fetch('../../api/routes.php/commodity?id=' + encodeURIComponent(id), {
                method: 'DELETE'
            })
                .then(response => response.json())
                .then(res => {
                    if (res.status === 'success') {
                        Swal.fire('Deleted!', res.message || 'Commodity deleted successfully.', 'success');
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire('Error', res.message || 'Unable to delete commodity.', 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'Network error.', 'error');
                });
        });
    });


    // Save (Add only — edits are handled in commodity-update.js)
    $('#btnSaveCommodity').on('click', function () {
        const productName = String($('#product_name').val() || '').trim();
        const categoryId = $('#category_id').val() || '';
        const brandName = String($('#brand_name').val() || '').trim();
        const unitOfMeasure = String($('#unit_of_measure').val() || '').trim();

        if (!productName) {
            Swal.fire('Required Field', 'Please enter the Commodity Name.', 'warning');
            return;
        }
        if (!categoryId) {
            Swal.fire('Required Field', 'Please select a Category.', 'warning');
            return;
        }
        if (!unitOfMeasure) {
            Swal.fire('Required Field', 'Please enter the Unit of Measure.', 'warning');
            return;
        }

        const commodityData = {
            product_name: productName,
            category_id: categoryId,
            brand_name: brandName,
            unit_of_measure: unitOfMeasure
        };

        $.ajax({
            url: '../../api/routes.php/commodity',
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(commodityData),
            success: function (response) {
                if (response.status !== 'success') {
                    Swal.fire('Error', response.message || 'Unable to save commodity.', 'error');
                    return;
                }

                Swal.fire('Success', response.message || 'Commodity added successfully.', 'success');

                $('#addCalamityModal').modal('hide');

                $('#product_name').val('');
                $('#category_id').val('');
                $('#brand_name').val('');
                $('#unit_of_measure').val('');

                table.ajax.reload(null, false);
            },
            error: function (xhr) {
                console.error(xhr.responseText);

                let message = 'Unable to save commodity.';
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.message) message = errorResponse.message;
                } catch (e) {
                    console.error('Could not parse server response.');
                }

                Swal.fire('Error', message, 'error');
            }
        });
    });


    // Load categories for the dropdown
    function loadCategories() {
        $.ajax({
            url: '../../api/routes.php/price-monitoring?action=commodity_categories&agency_id=3',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status !== 'success') {
                    Swal.fire('Error', response.message || 'Unable to load DOE categories.', 'error');
                    return;
                }

                let html = '<option value="">-- Select Category --</option>';

                $.each(response.data || [], function (i, item) {
                    html += `<option value="${item.category_id}">${item.category_name}</option>`;
                });

                $('#category_id').html(html);
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                Swal.fire('Error', 'Unable to load DOE categories.', 'error');
            }
        });
    }

});
