function fillRenewModal(id) {
    fetch('../../api/routes.php/business/' + id)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const business = data.data;
                $('#renewBusName').val(business.juridical.name);
                $('#renewBusEntityNo').val(business.juridical.entity_no);
                $('#renewRegType').val(business.juridical.registration_type);
                $('#renewBusinessModal').modal('show');
            } else {
                App.alert({
                    icon: 'error',
                    title: 'Could Not Load Record',
                    text: data.message || 'The business record could not be retrieved.'
                });
            }
        })
        .catch(error => {
            console.error(error);
            App.alert({
                icon: 'error',
                title: 'Request Failed',
                text: 'A network error occurred. Please check your connection and try again.'
            });
        });
}


function renewBusiness() {
    const name     = $('#renewBusName').val()     || 'this business';
    const entityNo = $('#renewBusEntityNo').val() || '—';

    App.confirm({
        icon: 'question',
        title: 'Renew Registration?',
        html: 'This will renew the registration for <strong>' + name + '</strong>'
            + ' <span style="color:#6c757d;font-size:.875em;">(' + entityNo + ')</span>.'
            + '<br><small style="color:#9ca3af;">Continue with renewal?</small>',
        confirmButtonText: 'Yes, Renew',
        cancelButtonText: 'Cancel'
    }).then(result => {
        if (!result.isConfirmed) return;

        const data = {
            juri_entity_no: entityNo
        };

        fetch('../../api/routes.php/business/renew', {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(res => {
                if (res.status === 'success') {
                    $('#renewBusinessModal').modal('hide');
                    $('#tblBusiness').DataTable().ajax.reload();
                    App.toast({
                        icon: 'success',
                        title: 'Registration Renewed',
                        text: res.message || name + ' has been successfully renewed.'
                    });
                } else {
                    App.alert({
                        icon: 'error',
                        title: 'Renewal Failed',
                        text: res.message || 'An error occurred while renewing the registration.'
                    });
                }
            })
            .catch(error => {
                console.error(error);
                App.alert({
                    icon: 'error',
                    title: 'Request Failed',
                    text: 'A network error occurred. Please check your connection and try again.'
                });
            });
    });
}
