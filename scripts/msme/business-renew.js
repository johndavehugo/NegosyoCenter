/**
 * business-renew.js
 * Lifecycle:
 *   1. fillRenewModal(id)  — fetch data, populate modal, show modal
 *   2. renewBusiness()     — called by Renew button inside modal
 *                            hides modal → waits for close → shows Swal confirm
 *                            → loading → PATCH → success toast / error popup
 *   3. Normal close        — Cancel / × resets display & hidden inputs
 *
 * The _renewTriggered flag prevents the normal-close reset from firing
 * when renewBusiness() hides the modal programmatically.
 */

var _renewTriggered = false;

// ── Normal close reset — only when user dismisses, not when renewBusiness() hides it
$(document).on('hidden.bs.modal', '#renewBusinessModal', function () {
    if (_renewTriggered) {
        // Programmatic hide from renewBusiness() — do NOT reset, data is still needed
        _renewTriggered = false;
        return;
    }
    // User clicked Cancel or × — safe to reset
    $('#renewBusNameDisplay').text('—');
    $('#renewBusEntityNoDisplay').text('—');
    $('#renewRegTypeBadge').html('—');
    $('#renewBusName').val('');
    $('#renewBusEntityNo').val('');
    $('#renewRegType').val('');
});


function fillRenewModal(id) {
    fetch('../../api/routes.php/business/' + id)
        .then(function (response) { return response.json(); })
        .then(function (data) {
            if (data.status === 'success') {
                var business = data.data;
                var name     = business.juridical.name     || '—';
                var entityNo = business.juridical.entity_no || '—';

                // registration_type can be null — default to 'NEW' for first-time records
                var rawReg  = business.juridical.registration_type;
                var regType = (rawReg && rawReg.toString().trim() !== '')
                    ? rawReg.toString().trim().toUpperCase()
                    : 'NEW';

                // Hidden inputs — read by renewBusiness() after modal closes
                $('#renewBusName').val(name);
                $('#renewBusEntityNo').val(entityNo);
                $('#renewRegType').val(regType);

                // Visible display elements
                $('#renewBusNameDisplay').text(name);
                $('#renewBusEntityNoDisplay').text(entityNo);

                // Registration type badge
                var badgeCls = regType === 'RENEWAL'
                    ? 'msme-badge-status-approved'
                    : regType === 'NEW'
                        ? 'msme-badge-status-new'
                        : 'msme-badge-unknown';
                $('#renewRegTypeBadge').html(
                    '<span class="badge badge-pill ' + badgeCls + '">' + regType + '</span>'
                );

                $('#renewBusinessModal').modal('show');

            } else {
                ncSwal.error('Could Not Load Record', data.message || 'The business record could not be fetched. Please try again.');
            }
        })
        .catch(function (err) {
            console.error(err);
            ncSwal.error('Network Error', 'Could not reach the server. Please check your connection and try again.');
        });
}


function renewBusiness() {
    // Read values NOW — before hiding the modal (reset script must not run first)
    var busName  = $('#renewBusName').val().trim();
    var entityNo = $('#renewBusEntityNo').val().trim();
    var regType  = $('#renewRegType').val().trim();

    // Flag: tell the hidden.bs.modal listener this was a programmatic hide
    _renewTriggered = true;

    // Hide Bootstrap modal first — Swal confirm must open on a clear screen
    $('#renewBusinessModal').modal('hide');

    // Wait for Bootstrap's fade-out to finish, then show the confirm
    $('#renewBusinessModal').one('hidden.bs.modal', function () {

        var currentBadgeCls = regType === 'RENEWAL'
            ? 'msme-badge-status-approved'
            : regType === 'NEW'
                ? 'msme-badge-status-new'
                : 'msme-badge-unknown';

        ncSwal.confirm({
            title: 'Renew Registration?',
            html:
                // Business name chip
                '<div class="nc-confirm-meta mb-3" style="width:100%;justify-content:center;">' +
                    '<i class="material-icons" style="font-size:15px;color:#198754;">storefront</i>' +
                    '<strong style="color:#1a1a2e;">' + busName + '</strong>' +
                '</div>' +

                // Current → RENEWAL transition strip
                '<div style="display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:12px;">' +
                    '<div style="text-align:center;">' +
                        '<div style="font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:4px;">Current</div>' +
                        '<span class="badge badge-pill ' + currentBadgeCls + '" style="font-size:.75rem;padding:5px 12px;">' + regType + '</span>' +
                    '</div>' +
                    '<i class="material-icons" style="font-size:20px;color:#adb5bd;margin-top:16px;">arrow_forward</i>' +
                    '<div style="text-align:center;">' +
                        '<div style="font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:4px;">New</div>' +
                        '<span class="badge badge-pill msme-badge-status-approved" style="font-size:.75rem;padding:5px 12px;">RENEWAL</span>' +
                    '</div>' +
                '</div>' +

                // Entity no footnote
                '<p style="margin:0;font-size:.78rem;color:#adb5bd;">' +
                    'Entity No: <strong style="color:#6c757d;">' + entityNo + '</strong>' +
                '</p>',
            confirmText:    'Yes, Renew',
            confirmVariant: 'success',
            popupVariant:   'success'
        }).then(function (confirmed) {
            if (!confirmed) return;

            ncSwal.loading('Renewing registration...');

            fetch('../../api/routes.php/business/renew', {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ juri_entity_no: entityNo })
            })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    ncSwal.close();
                    if (data.status === 'success') {
                        $('#tblBusiness').DataTable().ajax.reload();
                        ncSwal.toast(
                            'success',
                            'Registration Renewed',
                            busName
                                ? '<strong>' + busName + '</strong> has been renewed successfully.'
                                : 'Registration renewed successfully.'
                        );
                    } else {
                        ncSwal.error('Renewal Failed', data.message || 'The renewal could not be completed. Please try again.');
                    }
                })
                .catch(function (err) {
                    ncSwal.close();
                    console.error(err);
                    ncSwal.error('Network Error', 'Could not reach the server. Please check your connection and try again.');
                });
        });
    });
}
