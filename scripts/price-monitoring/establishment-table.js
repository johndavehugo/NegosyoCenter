$(function () {

    const table = $('#tblEstablishment').DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        processing: true,
        destroy: true,

        ajax: {
            url: '../../api/routes.php/establishment',
            type: 'GET',
            dataSrc: function (response) {
                if (response.status === 'success') {
                    return response.data || [];
                }
                Swal.fire('Error', response.message || 'Unable to load establishments.', 'error');
                return [];
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                Swal.fire('Error', 'Failed to connect to establishment API.', 'error');
            }
        },

        columns: [
            { data: 'id', defaultContent: '-' },
            { data: 'name', defaultContent: '-' },
            { data: 'branch', defaultContent: '-' },
            { data: 'address', defaultContent: '-' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <button
                            class="btn btn-warning btn-sm btn-edit-establishment"
                            data-id="${row.id}"
                            title="Edit Establishment">
                            <i class="fas fa-edit"></i>
                        </button>

                        <button
                            class="btn btn-danger btn-sm btn-delete-establishment"
                            data-id="${row.id}"
                            title="Delete Establishment">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ]
    });

    window.establishmentTable = table;

    // Reload establishment dropdown too (used in Section 2)
    function refreshEverything() {
        table.ajax.reload(null, false);
        if (typeof loadEstablishmentDropdown === 'function') {
            loadEstablishmentDropdown();
        }
    }

    window.refreshEstablishmentTable = refreshEverything;

    // Delete
    $(document).on('click', '.btn-delete-establishment', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Delete Establishment?',
            text: 'This cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (!result.isConfirmed) return;

            fetch('../../api/routes.php/establishment?id=' + encodeURIComponent(id), {
                method: 'DELETE'
            })
                .then(response => response.json())
                .then(res => {
                    if (res.status === 'success') {
                        Swal.fire('Deleted!', res.message || 'Establishment deleted successfully.', 'success');
                        refreshEverything();
                    } else {
                        Swal.fire('Error', res.message || 'Unable to delete establishment.', 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'Network error.', 'error');
                });
        });
    });

});
