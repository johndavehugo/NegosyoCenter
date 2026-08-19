$('#editCalamityModal').on('hidden.bs.modal', function () {
    document.getElementById('editCalamityForm').reset();
});

function fillEditCalamity(calamityId) {
    $.getJSON('../../api/routes.php/calamity?action=calamity_detail&calamity_id=' + calamityId)
        .done(function (res) {
            if (res.status === 'success' && res.data) {
                var d = res.data;
                $('#editCalamityId').val(d.id);
                $('#editCalamityName').val(d.name);
                $('#editCalamityType').val(d.calamity_type);
                $('#editCalamityDate').val(d.declaration_date);
                $('#editCalamityDescription').val(d.description || '');
                $('#editCalamityModal').modal('show');
            } else {
                Swal.fire('Error', res.message || 'Calamity not found.', 'error');
            }
        });
}

function updateCalamity() {
    const id               = $('#editCalamityId').val();
    const name             = $('#editCalamityName').val().trim();
    const calamity_type    = $('#editCalamityType').val();
    const declaration_date = $('#editCalamityDate').val();
    const description      = $('#editCalamityDescription').val().trim();

    const data = { type: 'calamity', id, name, calamity_type, declaration_date, description };

    fetch('../../api/routes.php/calamity', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            // Update the row directly in the DataTable without reloading
            calamityTable.rows().every(function () {
                var d = this.data();
                if (String(d.id) === String(id)) {
                    d.name             = name;
                    d.calamity_type    = calamity_type;
                    d.declaration_date = declaration_date;
                    d.description      = description;
                    this.data(d).draw(false);
                }
            });
            $('#editCalamityModal').modal('hide');
            Swal.fire({ icon: 'success', title: 'Updated!', text: res.message, timer: 1500, showConfirmButton: false });
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Network error', 'error');
    });
}
