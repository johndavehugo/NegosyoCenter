function fillStatusModal(id) {
    fetch('../../api/routes.php/business/' + id)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const business = data.data;
                const name     = business.juridical.name            || '—';
                const entityNo = business.juridical.entity_no       || '—';
                const status   = (business.juridical.business_status || '—').toUpperCase();

                // Populate hidden inputs (used by changeBusinessStatus)
                $('#statusBusName').val(name);
                $('#statusBusEntityNo').val(entityNo);
                $('#statusCurStatus').val(status);

                // Populate display elements
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
                ncSwal.error('Could Not Load Record', data.message || 'The business record could not be fetched. Please try again.');
            }
        })
        .catch(error => {
            console.error(error);
            ncSwal.error('Network Error', 'Could not reach the server. Please check your connection and try again.');
        });
}

// Called by the status radio cards to refresh visual state (kept for UI feedback)
function previewStatusChange(value) {
    // Visual feedback is handled by the CSS :checked selectors — nothing extra needed here
}

function changeBusinessStatus() {
    var busName   = $('#statusBusName').val().trim();
    var entityNo  = $('#statusBusEntityNo').val().trim();
    var newStatus = $('input[name="statusNewStatus"]:checked').val() || '';

    // Validation — must select a status before confirming
    if (!newStatus) {
        ncSwal.warning('No Status Selected', 'Please select a new status — <strong>Active</strong> or <strong>Inactive</strong> — before saving.');
        return;
    }

    // Build status pill HTML for the confirm body
    var pillHtml = newStatus === 'ACTIVE'
        ? '<span class="nc-status-active">ACTIVE</span>'
        : '<span class="nc-status-inactive">INACTIVE</span>';

    // Confirm variant mirrors the chosen status colour
    var confirmVariant = newStatus === 'ACTIVE' ? 'success' : 'danger';
    var popupVariant   = newStatus === 'ACTIVE' ? 'success' : 'danger';
    var confirmLabel   = newStatus === 'ACTIVE' ? 'Yes, Set Active' : 'Yes, Set Inactive';

    ncSwal.confirm({
        title:          'Change Business Status?',
        html:
            '<div class="nc-confirm-meta mb-2">' +
                '<span>&#35;</span>' +
                '<strong>' + entityNo + '</strong>' +
            '</div>' +
            '<p style="margin:0;font-size:.9rem;color:#495057;">' +
                'The status of <strong>' + busName + '</strong> will be changed to ' + pillHtml + '.' +
            '</p>',
        confirmText:    confirmLabel,
        confirmVariant: confirmVariant,
        popupVariant:   popupVariant
    }).then(function (confirmed) {
        if (!confirmed) return;

        // Show loading while request is in flight
        ncSwal.loading('Updating status...');

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
            .then(data => {
                ncSwal.close();
                if (data.status === 'success') {
                    $('#statusBusinessModal').modal('hide');
                    $('#tblBusiness').DataTable().ajax.reload();
                    // Toast colour matches the new status
                    var toastType = newStatus === 'ACTIVE' ? 'success' : 'warning';
                    ncSwal.toast(
                        toastType,
                        'Status Updated',
                        busName
                            ? '<strong>' + busName + '</strong> is now ' + newStatus.toLowerCase() + '.'
                            : 'Business status updated successfully.'
                    );
                } else {
                    ncSwal.error('Status Change Failed', data.message || 'The status could not be updated. Please try again.');
                }
            })
            .catch(error => {
                ncSwal.close();
                console.error(error);
                ncSwal.error('Network Error', 'Could not reach the server. Please check your connection and try again.');
            });
    });
}

// Reset radio selection whenever modal closes, so it opens fresh next time
$(document).on('hidden.bs.modal', '#statusBusinessModal', function () {
    $('input[name="statusNewStatus"]').prop('checked', false);
});
