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
                msme.alert({
                    icon: 'error',
                    title: 'Could Not Load Record',
                    text: data.message || 'The business record could not be retrieved.'
                });
            }
        })
        .catch(error => {
            console.error(error);
            msme.alert({
                icon: 'error',
                title: 'Request Failed',
                text: 'A network error occurred. Please check your connection and try again.'
            });
        });
}

// Called by the radio buttons in modal-status.php to preview the selected state
function previewStatusChange(value) {
    // Visual feedback is handled entirely by the CSS radio-card styles —
    // nothing extra needed here. Function kept so the onchange attr doesn't throw.
}

function changeBusinessStatus() {
    const newStatus = $('input[name="statusNewStatus"]:checked').val() || '';
    const name      = $('#statusBusName').val()     || 'this business';
    const entityNo  = $('#statusBusEntityNo').val() || '—';

    if (!newStatus) {
        msme.alert({
            icon: 'warning',
            title: 'No Status Selected',
            text: 'Please select a new status before continuing.'
        });
        return;
    }

    // Build confirm options based on the chosen status
    var isDeactivating = newStatus === 'INACTIVE';

    msme.confirm({
        icon:    isDeactivating ? 'warning' : 'question',
        danger:  isDeactivating,
        title:   isDeactivating ? 'Deactivate Business?' : 'Activate Business?',
        html:    '<strong>' + name + '</strong>'
               + ' <span style="color:#6c757d;font-size:.875em;">(' + entityNo + ')</span>'
               + ' will be marked as <strong>'
               + (isDeactivating ? 'Inactive' : 'Active') + '</strong>.',
        confirmButtonText: isDeactivating ? 'Deactivate' : 'Activate',
        cancelButtonText:  'Cancel'
    }).then(result => {
        if (!result.isConfirmed) return;

        const data = {
            juri_entity_no:  entityNo,
            juri_bus_status: newStatus
        };

        fetch('../../api/routes.php/business/status', {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(res => {
                if (res.status === 'success') {
                    $('#statusBusinessModal').modal('hide');
                    $('#tblBusiness').DataTable().ajax.reload();
                    msme.toast({
                        icon: 'success',
                        title: 'Status Updated',
                        text: res.message || name + ' is now ' + newStatus.toLowerCase() + '.'
                    });
                } else {
                    msme.alert({
                        icon: 'error',
                        title: 'Update Failed',
                        text: res.message || 'An error occurred while updating the status.'
                    });
                }
            })
            .catch(error => {
                console.error(error);
                msme.alert({
                    icon: 'error',
                    title: 'Request Failed',
                    text: 'A network error occurred. Please check your connection and try again.'
                });
            });
    });
}
