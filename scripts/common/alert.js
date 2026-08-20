/* ═══════════════════════════════════════════════════════════════════════════
   App.alert — shared SweetAlert2 presets for the entire application.
   Load this file once, after sweetalert2.all.min.js, before any module
   scripts. All modules reference window.App.toast / .alert / .confirm.
   ═══════════════════════════════════════════════════════════════════════════ */

window.App = window.App || {};

/* ── Internal base mixin ─────────────────────────────────────────────────── */
App._base = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-sm swal-confirm-btn',
        cancelButton:  'btn btn-sm swal-cancel-btn',
        popup:         'swal-app-popup'
    },
    buttonsStyling: false
});

/* ───────────────────────────────────────────────────────────────────────────
   App.toast(options)
   Auto-closing top-end toast for non-blocking success / info feedback.

   Usage:
     App.toast({ icon: 'success', title: 'Record saved.' });
     App.toast({ icon: 'error',   title: 'Failed', text: 'Details here.' });
─────────────────────────────────────────────────────────────────────────── */
App.toast = function (opts) {
    return Swal.mixin({
        toast:              true,
        position:           'top-end',
        showConfirmButton:  false,
        timer:              3000,
        timerProgressBar:   true,
        customClass: { popup: 'swal-app-toast' },
        didOpen: function (toast) {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    }).fire(opts);
};

/* ───────────────────────────────────────────────────────────────────────────
   App.alert(options)
   Centered dialog for errors, warnings and neutral notices.

   Usage:
     App.alert({ icon: 'error',   title: 'Request Failed', text: '...' });
     App.alert({ icon: 'warning', title: 'Not Found',      text: '...' });
─────────────────────────────────────────────────────────────────────────── */
App.alert = function (opts) {
    return App._base.fire(Object.assign({
        showCancelButton:   false,
        confirmButtonText:  'OK'
    }, opts));
};

/* ───────────────────────────────────────────────────────────────────────────
   App.confirm(options) → Promise<SweetAlertResult>
   Confirm-before-proceed dialog. Resolves with .isConfirmed.

   Extra option:
     danger {bool} — uses red confirm button for destructive actions.

   Usage:
     App.confirm({
         title: 'Delete Record?',
         text:  'This cannot be undone.',
         danger: true,
         confirmButtonText: 'Yes, Delete'
     }).then(function (result) {
         if (result.isConfirmed) { ... }
     });
─────────────────────────────────────────────────────────────────────────── */
App.confirm = function (opts) {
    var isDanger = !!opts.danger;
    var btnClass = isDanger
        ? 'btn btn-sm swal-confirm-btn-danger'
        : 'btn btn-sm swal-confirm-btn';

    return Swal.mixin({
        customClass: {
            confirmButton: btnClass,
            cancelButton:  'btn btn-sm swal-cancel-btn',
            popup:         'swal-app-popup'
        },
        buttonsStyling: false
    }).fire(Object.assign({
        icon:               'question',
        showCancelButton:   true,
        confirmButtonText:  'Confirm',
        cancelButtonText:   'Cancel',
        reverseButtons:     true        // Cancel left, Confirm right
    }, opts, { danger: undefined }));   // strip non-Swal key before passing
};

/* ───────────────────────────────────────────────────────────────────────────
   Shared CSS — injected once into <head>.
   All visual polish lives here so it applies to every page that loads this
   file, with no separate stylesheet required.
─────────────────────────────────────────────────────────────────────────── */
(function injectAppSwalStyles() {
    if (document.getElementById('app-swal-styles')) return;
    var style = document.createElement('style');
    style.id = 'app-swal-styles';
    style.textContent = [

        /* ── Popup container ─────────────────────────────────────────── */
        '.swal-app-popup {',
        '  font-family: "Roboto", sans-serif !important;',
        '  border-radius: 10px !important;',
        '  padding: 2rem 1.75rem 1.5rem !important;',
        '  max-width: 420px !important;',
        '  box-shadow: 0 8px 32px rgba(0,0,0,.18), 0 2px 8px rgba(0,0,0,.10) !important;',
        '}',

        /* ── Icon — centered, consistent size ────────────────────────── */
        '.swal-app-popup .swal2-icon {',
        '  width: 4rem !important; height: 4rem !important;',
        '  margin: 0 auto 1rem !important;',
        '}',
        '.swal-app-popup .swal2-icon .swal2-icon-content { font-size: 2.1rem !important; }',

        /* ── Title ───────────────────────────────────────────────────── */
        '.swal-app-popup .swal2-title {',
        '  font-size: 1.05rem !important;',
        '  font-weight: 700 !important;',
        '  color: #1a1a2e !important;',
        '  padding: 0 0 .25rem !important;',
        '  line-height: 1.3 !important;',
        '  text-align: center !important;',
        '}',

        /* ── Body text ───────────────────────────────────────────────── */
        '.swal-app-popup .swal2-html-container,',
        '.swal-app-popup .swal2-content {',
        '  font-size: .875rem !important;',
        '  color: #495057 !important;',
        '  line-height: 1.55 !important;',
        '  text-align: center !important;',
        '  margin: 0 0 .25rem !important;',
        '  padding: 0 !important;',
        '}',

        /* ── Actions row ─────────────────────────────────────────────── */
        '.swal-app-popup .swal2-actions {',
        '  margin-top: 1.25rem !important;',
        '  gap: 8px !important;',
        '  justify-content: center !important;',
        '}',

        /* ── Hide default close × button inside center dialogs ───────── */
        '.swal-app-popup .swal2-close { display: none !important; }',

        /* ── Confirm button — primary blue (matches btn-raised-primary) ─ */
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
        '.swal-confirm-btn:hover,',
        '.swal-confirm-btn:focus {',
        '  background-color: #0069d9 !important;',
        '  box-shadow: 0 4px 10px rgba(0,123,255,.45) !important;',
        '}',

        /* ── Danger confirm button (matches btn-raised-danger) ───────── */
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
        '.swal-confirm-btn-danger:hover,',
        '.swal-confirm-btn-danger:focus {',
        '  background-color: #c82333 !important;',
        '  box-shadow: 0 4px 10px rgba(220,53,69,.45) !important;',
        '}',

        /* ── Cancel button — flat grey (matches btn-text-secondary) ──── */
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
        '.swal-cancel-btn:hover,',
        '.swal-cancel-btn:focus {',
        '  background-color: rgba(108,117,125,.1) !important;',
        '  color: #495057 !important;',
        '}',

        /* ── Toast container ─────────────────────────────────────────── */
        '.swal-app-toast {',
        '  font-family: "Roboto", sans-serif !important;',
        '  border-radius: 8px !important;',
        '  padding: .65rem .9rem !important;',
        '  box-shadow: 0 4px 16px rgba(0,0,0,.14), 0 1px 4px rgba(0,0,0,.08) !important;',
        '  min-width: 260px !important;',
        '  border-left: 4px solid transparent !important;',
        '}',

        /* Toast icon — inline with text, scaled down */
        '.swal-app-toast .swal2-icon {',
        '  width: 1.6rem !important; height: 1.6rem !important;',
        '  margin: 0 .55rem 0 0 !important;',
        '  flex-shrink: 0 !important;',
        '}',
        '.swal-app-toast .swal2-icon .swal2-icon-content { font-size: .95rem !important; }',

        /* Toast title — bold, dark, left-aligned */
        '.swal-app-toast .swal2-title {',
        '  font-size: .875rem !important;',
        '  font-weight: 700 !important;',
        '  color: #1a1a2e !important;',
        '  text-align: left !important;',
        '  padding: 0 !important;',
        '  margin: 0 !important;',
        '}',

        /* Toast body — muted, left-aligned */
        '.swal-app-toast .swal2-html-container,',
        '.swal-app-toast .swal2-content {',
        '  font-size: .78rem !important;',
        '  color: #6c757d !important;',
        '  text-align: left !important;',
        '  margin: 1px 0 0 !important;',
        '  padding: 0 !important;',
        '  line-height: 1.4 !important;',
        '}',

        /* Toast left-border accent by icon type */
        '.swal-app-toast.swal2-icon-success { border-left-color: #28a745 !important; }',
        '.swal-app-toast.swal2-icon-error    { border-left-color: #dc3545 !important; }',
        '.swal-app-toast.swal2-icon-warning  { border-left-color: #ffc107 !important; }',
        '.swal-app-toast.swal2-icon-info     { border-left-color: #17a2b8 !important; }',

        /* Toast progress bar */
        '.swal-app-toast .swal2-timer-progress-bar { background: rgba(0,0,0,.15) !important; }',

    ].join('\n');
    document.head.appendChild(style);
}());
