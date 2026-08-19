$('#addCalamityModal').on('hidden.bs.modal', function () {
    document.getElementById('addCalamityForm').reset();
});

function addCalamity() {
    const data = {
        type:               'calamity',
        name:               $('input[name=addCalamityName]').val(),
        calamity_type:      $('select[name=addCalamityType]').val(),
        declaration_date:   $('input[name=addCalamityDate]').val(),
        description:        $('textarea[name=addCalamityDescription]').val(),
    };

    fetch('../../api/routes.php/calamity', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire('Success!', res.message, 'success');
            $('#addCalamityModal').modal('hide');
            reloadCalamityTable();
            document.getElementById('addCalamityForm').reset();
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Network error', 'error');
    });
}
