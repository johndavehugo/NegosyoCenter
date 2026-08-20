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
            $('#addCalamityModal').modal('hide');
            reloadCalamityTable();
            document.getElementById('addCalamityForm').reset();
            App.toast({
                icon: 'success',
                title: 'Calamity Added',
                text: res.message || 'The calamity record has been saved.'
            });
        } else {
            App.alert({
                icon: 'error',
                title: 'Could Not Save',
                text: res.message || 'An error occurred while saving the calamity record.'
            });
        }
    })
    .catch(err => {
        console.error(err);
        App.alert({
            icon: 'error',
            title: 'Request Failed',
            text: 'A network error occurred. Please check your connection and try again.'
        });
    });
}
