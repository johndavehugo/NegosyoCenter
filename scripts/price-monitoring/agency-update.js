$(document).on('click', '.btn-edit', function () {
    var row = $('#tblAgency').DataTable().row($(this).closest('tr')).data();
    if (!row || !row.id) {
        Swal.fire('Error', 'Unable to retrieve Agency ID.', 'error');
        return;
    }

    $('#updateAgencyId').val(row.id);
    $('#updateAgencyCode').val(row.code);
    $('#updateAgencyName').val(row.name);
    $('#updateAgencyCoverage').val(row.coverage);
    $('#updateAgencyModal').appendTo('body').modal('show');
});

// Reset form when modal is closed
$('#updateAgencyModal').on('hidden.bs.modal', function () {
    document.getElementById('updateAgencyForm').reset();
});

// Sends the PUT request to save updates
function updateAgency() {
    const data = {
        id: $('#updateAgencyId').val(),
        code: $('#updateAgencyCode').val().trim(),
        name: $('#updateAgencyName').val().trim(),
        coverage: $('#updateAgencyCoverage').val().trim()
    };

    if (!data.code || !data.name) {
        Swal.fire('Warning', 'Please fill in all required fields.', 'warning');
        return;
    }

    fetch('../../api/routes.php/agency', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire('Success!', res.message || 'Agency updated successfully.', 'success');
            $('#updateAgencyModal').modal('hide');
            $('#tblAgency').DataTable().ajax.reload(null, false);
        } else {
            Swal.fire('Error', res.message || 'Unable to update agency.', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Network error', 'error');
    });
}