$(function () {

    const table = $('#tblAgency').DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        processing: true,
        destroy: true,

        ajax: {
            url: '../../api/routes.php/agency',
            type: 'GET',
            dataSrc: function (response) {
                if (response.status === 'success') {
                    return response.data || [];
                }
                Swal.fire('Error', response.message || 'Unable to load agencies.', 'error');
                return [];
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                Swal.fire('Error', 'Failed to connect to agency API.', 'error');
            }
        },

        columns: [
            { data: 'id', defaultContent: '-' },
            { data: 'code', defaultContent: '-' },
            { data: 'name', defaultContent: '-' },
            { data: 'coverage', defaultContent: '-' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <button
                            class="btn btn-warning btn-sm btn-edit"
                            data-id="${row.id}"
                            title="Edit Agency">
                            <i class="fas fa-edit"></i>
                        </button>

                        <button
                            class="btn btn-danger btn-sm btn-delete"
                            data-id="${row.id}"
                            title="Delete Agency">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ]
    });


    // Open Add modal
    $('#btn_add_agency').on('click', function () {
        $('#agency_code').val('');
        $('#agency_name').val('');
        $('#agency_coverage').val('');
    });


    // Editing handled in agency-update.js (.btn-edit handler + updateAgency())


    // Delete
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Delete Agency?',
            text: 'This cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (!result.isConfirmed) return;

            fetch('../../api/routes.php/agency?id=' + encodeURIComponent(id), {
                method: 'DELETE'
            })
                .then(response => response.json())
                .then(res => {
                    if (res.status === 'success') {
                        Swal.fire('Deleted!', res.message || 'Agency deleted successfully.', 'success');
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire('Error', res.message || 'Unable to delete agency.', 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'Network error.', 'error');
                });
        });
    });


    // Save (Add only — edits are handled in agency-update.js)
    $('#btnSaveAgency').on('click', function () {
        const agencyCode = String($('#agency_code').val() || '').trim();
        const agencyName = String($('#agency_name').val() || '').trim();
        const agencyCoverage = String($('#agency_coverage').val() || '').trim();

        if (!agencyCode) {
            Swal.fire('Required Field', 'Please enter the Agency Code.', 'warning');
            return;
        }
        if (!agencyName) {
            Swal.fire('Required Field', 'Please enter the Agency Name.', 'warning');
            return;
        }

        const agencyData = {
            code: agencyCode,
            name: agencyName,
            coverage: agencyCoverage
        };

        $.ajax({
            url: '../../api/routes.php/agency',
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(agencyData),
            success: function (response) {
                if (response.status !== 'success') {
                    Swal.fire('Error', response.message || 'Unable to save agency.', 'error');
                    return;
                }

                Swal.fire('Success', response.message || 'Agency added successfully.', 'success');

                $('#addAgencyModal').modal('hide');

                $('#agency_code').val('');
                $('#agency_name').val('');
                $('#agency_coverage').val('');

                table.ajax.reload(null, false);
            },
            error: function (xhr) {
                console.error(xhr.responseText);

                let message = 'Unable to save agency.';
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

});