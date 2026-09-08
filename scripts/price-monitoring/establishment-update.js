$(document).on('click', '.btn-edit-establishment', function () {
    const row = $('#tblEstablishment').DataTable().row($(this).closest('tr')).data();

    if (!row || !row.id) {
        Swal.fire('Error', 'Unable to retrieve Establishment ID.', 'error');
        return;
    }

    $('#updateEstablishmentId').val(row.id);
    $('#updateEstablishmentName').val(row.name);
    $('#updateEstablishmentBranch').val(row.branch);
    $('#updateEstablishmentAddress').val(row.address);
    $('#updateEstablishmentModal').appendTo('body').modal('show');
});

// Reset form when modal is closed
$('#updateEstablishmentModal').on('hidden.bs.modal', function () {
    document.getElementById('updateEstablishmentForm').reset();
});

// Sends the PUT request to save updates
function updateEstablishment() {
    const data = {
        id: $('#updateEstablishmentId').val(),
        name: $('#updateEstablishmentName').val().trim(),
        branch: $('#updateEstablishmentBranch').val().trim(),
        address: $('#updateEstablishmentAddress').val().trim()
    };

    if (!data.name) {
        Swal.fire('Warning', 'Please enter the Establishment Name.', 'warning');
        return;
    }

    fetch('../../api/routes.php/establishment', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire('Success!', res.message || 'Establishment updated successfully.', 'success');
            $('#updateEstablishmentModal').modal('hide');
            if (typeof refreshEstablishmentTable === 'function') {
                refreshEstablishmentTable();
            }
        } else {
            Swal.fire('Error', res.message || 'Unable to update establishment.', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Network error', 'error');
    });
}
