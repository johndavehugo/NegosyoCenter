$('#addCategoryModal').on('hidden.bs.modal', function () {
    document.getElementById('addCategoryForm').reset();
});

function addCategory() {
    const data = {
        action:    'add_category',
        name:      $('#addCategoryName').val().trim(),
        agency_id: $('#addAgencyType').val()
    };


    if (!data.name || !data.agency_id) {
        Swal.fire('Warning', 'Please fill in both Category Name and Agency.', 'warning');
        return;
    }

    fetch('../../api/routes.php/price-monitoring', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire('Success!', res.message, 'success');
            $('#addCategoryModal').modal('hide');
            $('#tblCategories').DataTable().ajax.reload();
            document.getElementById('addCategoryForm').reset();
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Network error', 'error');
    });
}