// Loads agency options into the target dropdown
function loadAgencyOptions(selectId) {
    return new Promise(function (resolve, reject) {
        $.getJSON('../../api/routes.php/price?action=agencies')
            .done(function (res) {
                var opts = '<option value="" hidden>Select Agency</option>';
                if (res.status === 'success' && Array.isArray(res.data)) {
                    res.data.forEach(function (a) {
                        opts += `<option value="${a.id}">${a.name} (${a.code || ''})</option>`;
                    });
                }
                $('#' + selectId).html(opts);
                resolve();
            })
            .fail(reject);
    });
}

$(document).on('click', '.btn-edit', function () {
    var row = $('#tblCategories').DataTable().row($(this).closest('tr')).data();
    if (!row || !row.category_id) {
        Swal.fire('Error', 'Unable to retrieve Category ID.', 'error');
        return;
    }

    loadAgencyOptions('updateCategoryAgency')
        .then(function () {
            $('#updateCategoryId').val(row.category_id);
            $('#updateCategoryName').val(row.category_name);
            $('#updateCategoryAgency').val(row.agency_id);
            $('#updateCategoryModal').appendTo("body").modal('show');
        })
        .catch(function (err) {
        console.error(err);
        Swal.fire('Error', 'Failed to load agencies.', 'error');
    });
});
// Reset form when modal is closed
$('#updateCategoryModal').on('hidden.bs.modal', function () {
    document.getElementById('updateCategoryForm').reset();
});

// Sends the PUT request to save updates
function updateCategory() {
    const data = {
        action:      'update_category',
        category_id: $('#updateCategoryId').val(),
        name:        $('#updateCategoryName').val().trim(),
        agency_id:   $('#updateCategoryAgency').val()
    };

    if (!data.name || !data.agency_id) {
        Swal.fire('Warning', 'Please fill in all required fields.', 'warning');
        return;
    }

    fetch('../../api/routes.php/price-monitoring', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire('Success!', res.message, 'success');
            $('#updateCategoryModal').modal('hide');
            $('#tblCategories').DataTable().ajax.reload(null, false);
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Network error', 'error');
    });
}