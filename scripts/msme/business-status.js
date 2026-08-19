function fillStatusModal(id) {
    fetch('../../api/routes.php/business/' + id)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const business = data.data;
                const name     = business.juridical.name          || '—';
                const entityNo = business.juridical.entity_no     || '—';
                const status   = (business.juridical.business_status || '—').toUpperCase();

                // Populate hidden inputs (used by changeBusinessStatus)
                $('#statusBusName').val(name);
                $('#statusBusEntityNo').val(entityNo);
                $('#statusCurStatus').val(status);

                // Populate display elements immediately — no need to wait for shown.bs.modal
                $('#statusBusNameDisplay').text(name);
                $('#statusBusEntityNoDisplay').text(entityNo);

                // Render current status badge
                var badgeCls = status === 'ACTIVE'
                    ? 'badge badge-pill msme-badge-status-active'
                    : 'badge badge-pill msme-badge-status-inactive';
                $('#statusCurStatusBadge').html(
                    '<span class="' + badgeCls + '">' + status + '</span>'
                );

                // Reset radio selection
                $('input[name="statusNewStatus"]').prop('checked', false);

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
        juri_bus_status: $('input[name="statusNewStatus"]:checked').val() || '',
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