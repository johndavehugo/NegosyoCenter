function fillStatusModal(id) {
    fetch('../../api/routes.php/business/' + id)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const business = data.data;
                $('#statusBusName').val(business.juridical.name);
                $('#statusBusEntityNo').val(business.juridical.entity_no);
                $('#statusCurStatus').val(business.juridical.business_status);
                $('#statusNewStatus').val('');
                $('#statusBusinessModal').modal('show');
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            alert('Error: ' + error);
        });
}


function changeBusinessStatus() {
    const data = {
        juri_entity_no: $('#statusBusEntityNo').val(),
        juri_bus_status: $('#statusNewStatus').val(),
    };

    fetch('../../api/routes.php/business/status', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                $('#statusBusinessModal').modal('hide');
                $('#tblBusiness').DataTable().ajax.reload();
            }
        })
        .catch(error => {
            alert('Error: ' + error);
        });
}