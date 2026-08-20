/* ─────────────────────────────────────────────────────────────────────────
   msme.Swal — shared SweetAlert2 presets for the entire MSME module.
   Defined here because business-add.js is the first MSME script loaded.
   All other scripts reference window.msme.* directly.
   ───────────────────────────────────────────────────────────────────────── */
window.msme = window.msme || {};

// Base mixin — shared font, button shape and focus ring
msme._base = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-sm swal-confirm-btn',
        cancelButton:  'btn btn-sm btn-text-secondary swal-cancel-btn',
        popup:         'swal-msme-popup'
    },
    buttonsStyling: false   // let our CSS classes drive button appearance
});

/**
 * msme.toast(options)
 * Auto-closing top-end toast for non-blocking success/info feedback.
 * Usage: msme.toast({ icon: 'success', title: 'Record saved.' });
 */
msme.toast = function (opts) {
    return Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        customClass: { popup: 'swal-msme-toast' },
        didOpen: function (toast) {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    }).fire(opts);
};

/**
 * msme.alert(options)
 * Centered dialog for errors, warnings and neutral notices.
 * Usage: msme.alert({ icon: 'error', title: 'Request Failed', text: '...' });
 */
msme.alert = function (opts) {
    return msme._base.fire(Object.assign({
        showCancelButton: false,
        confirmButtonText: 'OK'
    }, opts));
};

/**
 * msme.confirm(options) → Promise<SweetAlertResult>
 * Confirm-before-proceed dialog. Resolves with .isConfirmed.
 * Usage: msme.confirm({ title: '...', text: '...', danger: true }).then(r => { if (r.isConfirmed) ... });
 *
 * Options:
 *   danger (bool)           — red confirm button (destructive actions)
 *   confirmButtonText (str) — label for the confirm button (default "Confirm")
 */
msme.confirm = function (opts) {
    var isDanger = !!opts.danger;
    var btnClass = isDanger ? 'btn btn-sm swal-confirm-btn-danger' : 'btn btn-sm swal-confirm-btn';
    return Swal.mixin({
        customClass: {
            confirmButton: btnClass,
            cancelButton:  'btn btn-sm btn-text-secondary swal-cancel-btn',
            popup:         'swal-msme-popup'
        },
        buttonsStyling: false
    }).fire(Object.assign({
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
        reverseButtons: true            // Cancel left, Confirm right
    }, opts, { danger: undefined }));   // strip non-Swal key before passing
};

/* ── Shared SweetAlert2 CSS injected once — alignment + visual polish ─── */
(function injectSwalStyles() {
    if (document.getElementById('msme-swal-styles')) return;
    var style = document.createElement('style');
    style.id = 'msme-swal-styles';
    style.textContent = [

        /* ── Popup container ─────────────────────────────────────────── */
        '.swal-msme-popup {',
        '  font-family: "Roboto", sans-serif !important;',
        '  border-radius: 10px !important;',
        '  padding: 2rem 1.75rem 1.5rem !important;',
        '  max-width: 420px !important;',
        '  box-shadow: 0 8px 32px rgba(0,0,0,.18), 0 2px 8px rgba(0,0,0,.10) !important;',
        '}',

        /* ── Icon — centered, consistent size ────────────────────────── */
        '.swal-msme-popup .swal2-icon {',
        '  width: 4rem !important; height: 4rem !important;',
        '  margin: 0 auto 1rem !important;',
        '}',
        '.swal-msme-popup .swal2-icon .swal2-icon-content { font-size: 2.1rem !important; }',

        /* ── Title ───────────────────────────────────────────────────── */
        '.swal-msme-popup .swal2-title {',
        '  font-size: 1.05rem !important;',
        '  font-weight: 700 !important;',
        '  color: #1a1a2e !important;',
        '  padding: 0 0 .25rem !important;',
        '  line-height: 1.3 !important;',
        '  text-align: center !important;',
        '}',

        /* ── Body text ───────────────────────────────────────────────── */
        '.swal-msme-popup .swal2-html-container,',
        '.swal-msme-popup .swal2-content {',
        '  font-size: .875rem !important;',
        '  color: #495057 !important;',
        '  line-height: 1.55 !important;',
        '  text-align: center !important;',
        '  margin: 0 0 .25rem !important;',
        '  padding: 0 !important;',
        '}',

        /* ── Actions row ─────────────────────────────────────────────── */
        '.swal-msme-popup .swal2-actions {',
        '  margin-top: 1.25rem !important;',
        '  gap: 8px !important;',
        '  justify-content: center !important;',
        '}',

        /* ── Hide default close button inside center dialogs ─────────── */
        '.swal-msme-popup .swal2-close { display: none !important; }',

        /* ── Confirm button — primary blue (btn-raised-primary) ──────── */
        '.swal-confirm-btn {',
        '  background-color: #007bff !important;',
        '  border-color: #007bff !important;',
        '  color: #fff !important;',
        '  border-radius: 6px !important;',
        '  padding: .4rem 1.2rem !important;',
        '  font-size: .875rem !important;',
        '  font-weight: 600 !important;',
        '  box-shadow: 0 2px 4px rgba(0,123,255,.35) !important;',
        '  transition: background-color .15s ease, box-shadow .15s ease !important;',
        '}',
        '.swal-confirm-btn:hover, .swal-confirm-btn:focus {',
        '  background-color: #0069d9 !important;',
        '  box-shadow: 0 4px 10px rgba(0,123,255,.45) !important;',
        '}',

        /* ── Danger confirm button (btn-raised-danger) ───────────────── */
        '.swal-confirm-btn-danger {',
        '  background-color: #dc3545 !important;',
        '  border-color: #dc3545 !important;',
        '  color: #fff !important;',
        '  border-radius: 6px !important;',
        '  padding: .4rem 1.2rem !important;',
        '  font-size: .875rem !important;',
        '  font-weight: 600 !important;',
        '  box-shadow: 0 2px 4px rgba(220,53,69,.35) !important;',
        '  transition: background-color .15s ease, box-shadow .15s ease !important;',
        '}',
        '.swal-confirm-btn-danger:hover, .swal-confirm-btn-danger:focus {',
        '  background-color: #c82333 !important;',
        '  box-shadow: 0 4px 10px rgba(220,53,69,.45) !important;',
        '}',

        /* ── Cancel button — flat grey (btn-text-secondary) ──────────── */
        '.swal-cancel-btn {',
        '  color: #6c757d !important;',
        '  background-color: transparent !important;',
        '  border-color: transparent !important;',
        '  border-radius: 6px !important;',
        '  padding: .4rem 1rem !important;',
        '  font-size: .875rem !important;',
        '  font-weight: 500 !important;',
        '  transition: background-color .15s ease, color .15s ease !important;',
        '}',
        '.swal-cancel-btn:hover, .swal-cancel-btn:focus {',
        '  background-color: rgba(108,117,125,.1) !important;',
        '  color: #495057 !important;',
        '}',

        /* ── Toast ───────────────────────────────────────────────────── */
        '.swal-msme-toast {',
        '  font-family: "Roboto", sans-serif !important;',
        '  border-radius: 8px !important;',
        '  padding: .65rem .9rem !important;',
        '  box-shadow: 0 4px 16px rgba(0,0,0,.14), 0 1px 4px rgba(0,0,0,.08) !important;',
        '  min-width: 260px !important;',
        '  border-left: 4px solid transparent !important;',
        '}',

        /* Toast icon — inline with text, no oversized circle */
        '.swal-msme-toast .swal2-icon {',
        '  width: 1.6rem !important; height: 1.6rem !important;',
        '  margin: 0 .55rem 0 0 !important;',
        '  flex-shrink: 0 !important;',
        '}',
        '.swal-msme-toast .swal2-icon .swal2-icon-content { font-size: .95rem !important; }',

        /* Toast title — bold, dark, no extra margin */
        '.swal-msme-toast .swal2-title {',
        '  font-size: .875rem !important;',
        '  font-weight: 700 !important;',
        '  color: #1a1a2e !important;',
        '  text-align: left !important;',
        '  padding: 0 !important;',
        '  margin: 0 !important;',
        '}',

        /* Toast body text — muted, left-aligned */
        '.swal-msme-toast .swal2-html-container,',
        '.swal-msme-toast .swal2-content {',
        '  font-size: .78rem !important;',
        '  color: #6c757d !important;',
        '  text-align: left !important;',
        '  margin: 1px 0 0 !important;',
        '  padding: 0 !important;',
        '  line-height: 1.4 !important;',
        '}',

        /* Toast left-border accent by icon type */
        '.swal-msme-toast.swal2-icon-success { border-left-color: #28a745 !important; }',
        '.swal-msme-toast.swal2-icon-error    { border-left-color: #dc3545 !important; }',
        '.swal-msme-toast.swal2-icon-warning  { border-left-color: #ffc107 !important; }',
        '.swal-msme-toast.swal2-icon-info     { border-left-color: #17a2b8 !important; }',

        /* Toast progress bar colour */
        '.swal-msme-toast .swal2-timer-progress-bar { background: rgba(0,0,0,.15) !important; }',

    ].join('\n');
    document.head.appendChild(style);
}());

/* ──────────────────────────────────────────────────────────────────────── */

var stepper;

address.cascade('#addBusRegion', '#addBusProvince', '#addBusCity', '#addBusBarangay');
address.cascade('#addEmpRegion', '#addEmpProvince', '#addEmpCity', '#addEmpBarangay');

bindCurrencyInput($('#addCapitalization'));

$('#addBusinessModal').on('shown.bs.modal', function () {
    stepper = new Stepper($('.bs-stepper')[0]);

    if (!$('#scimsBusSearch').data('select2')) {
        $('#scimsBusSearch').select2({
            placeholder: 'Search business name',
            allowClear: true,
            minimumInputLength: 1,
            dropdownParent: $('#addBusinessModal'),
            ajax: {
                url: '../../server-side/business-handler.php',
                dataType: 'json',
                delay: 300,
                data: function (params) { return { action: 'scims_businesses', q: params.term }; },
                processResults: function (data) {
                    return {
                        results: (data.data || []).map(function (o) {
                            return { id: o.juri_name, text: o.juri_name + ' (' + o.juri_entity_no + ')', business: o };
                        })
                    };
                }
            }
        });
    }

});

$('#addBusinessModal').on('hidden.bs.modal', function () {
    if (stepper) stepper.reset();
    document.getElementById('addBusinessForm').reset();

    $('#scimsBusSearch').val(null).trigger('change');

    ['addBusProvince', 'addBusCity', 'addBusBarangay',
        'addEmpProvince', 'addEmpCity', 'addEmpBarangay'].forEach(function (id) {
            var $sel = $('#' + id);
            $sel.find('option').not('[value=""]').remove();
            $sel.val('').trigger('change');
        });

    $('#addBusinessModal').data('scimsBusiness', null);
});


$('#scimsBusSearch').on('select2:select', function (e) {
    $('#addBusinessModal').data('scimsBusiness', e.params.data.business);
    var b = e.params.data.business;

    //Business
    $('#addBusinessName').val(b.juri_name || '');
    $('#addBusEntityNo').val(b.juri_entity_no || '');
    $('#addContactNo').val(b.juri_contact_no || '');
    $('#addEmail').val(b.juri_contact_email || '');
    $('#addBusStreet').val(b.juri_street || '');
    $('#addBusSubdivision').val(b.juri_subdivision || '');
    $('#addBusUpblb').val(b.juri_upblb_num || '');
    $('#addIndustry').val(b.line_of_industry || '');

    //Owner
    $('#addOwnerName').val(b.emp_name || '');
    $('#addEmpEntityNo').val(b.emp_entity_no || '');
    $('#addEmpStreet').val(b.emp_street || '');
    $('#addEmpSubdivision').val(b.emp_subdivision || '');
    $('#addEmpUpblb').val(b.emp_upblb_num || '');

    if (b.juri_region || b.juri_province || b.juri_city || b.juri_barangay) {
        address.prefill('#addBusRegion', '#addBusProvince', '#addBusCity', '#addBusBarangay',
            { region: b.juri_region, province: b.juri_province, city: b.juri_city, barangay: b.juri_barangay });
    }
    if (b.emp_region || b.emp_province || b.emp_city || b.emp_barangay) {
        address.prefill('#addEmpRegion', '#addEmpProvince', '#addEmpCity', '#addEmpBarangay',
            { region: b.emp_region, province: b.emp_province, city: b.emp_city, barangay: b.emp_barangay });
    }
});


function addBusiness() {
    var b = $('#addBusinessModal').data('scimsBusiness') || {};
    const data = {

        //employer address
        emp_region: $('select[name=addEmpRegion]').val(),
        emp_province: $('select[name=addEmpProvince]').val(),
        emp_city: $('select[name=addEmpCity]').val(),
        emp_barangay: $('select[name=addEmpBarangay]').val(),
        emp_street: $('input[name=addEmpStreet]').val(),
        emp_subdivision: $('input[name=addEmpSubdivision]').val(),
        emp_upblb_num: $('input[name=addEmpUpblb]').val(),
        emp_address_id: b.emp_address_id,


        //employer
        emp_name: $('input[name=addOwnerName]').val(),
        emp_entity_no: $('input[name=addEmpEntityNo]').val(),
        emp_special_category: $('select[name=addSpecialCategory]').val(),
        emp_id: b.emp_id,

        //juridical address
        juri_region: $('select[name=addBusRegion]').val(),
        juri_province: $('select[name=addBusProvince]').val(),
        juri_city: $('select[name=addBusCity]').val(),
        juri_barangay: $('select[name=addBusBarangay]').val(),
        juri_street: $('input[name=addBusStreet]').val(),
        juri_subdivision: $('input[name=addBusSubdivision]').val(),
        juri_upblb_num: $('input[name=addBusUpblb]').val(),
        juri_address_id: b.juri_address_id,

        //juridical
        juri_name: $('input[name=addBusinessName]').val(),
        juri_entity_no: $('input[name=addBusEntityNo]').val(),
        juri_line_of_industry: $('select[name=addIndustry]').val(),
        juri_capitalization: currencyParse($('input[name=addCapitalization]').val()),
        juri_contact_no: $('input[name=addContactNo]').val(),
        juri_contact_email: $('input[name=addEmail]').val(),

    };

    fetch('../../api/routes.php/business', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
        .then(r => r.json())
        .then(res => {
            if (res.status === 'success') {
                var name = $('input[name=addBusinessName]').val() || 'Business';
                $('#addBusinessModal').modal('hide');
                $('#tblBusiness').DataTable().ajax.reload();
                msme.toast({
                    icon: 'success',
                    title: 'Business Added',
                    text: name + ' has been registered successfully.'
                });
            } else {
                msme.alert({
                    icon: 'error',
                    title: 'Could Not Save',
                    text: res.message || 'An error occurred while saving the business record.'
                });
            }
        })
        .catch(err => {
            console.error(err);
            msme.alert({
                icon: 'error',
                title: 'Request Failed',
                text: 'A network error occurred. Please check your connection and try again.'
            });
        });
};