/**
 * swal-helper.js
 * Centralised SweetAlert2 wrapper for the Negosyo Center MSME module.
 * All visual personality lives here — action scripts just call ncSwal.*
 *
 * Requires: SweetAlert2 v11  (already loaded in msme.php)
 */

/* ── Inject scoped SweetAlert2 styles ───────────────────────────── */
(function injectStyles() {
    var id = 'nc-swal-styles';
    if (document.getElementById(id)) return;

    var css = `

/* ══════════════════════════════════════════════════════════════════
   TOAST
   Compact, tinted, floats 70px below the fixed navbar
   ══════════════════════════════════════════════════════════════════ */

.nc-swal-toast.swal2-container {
    top: 66px !important;
    right: 16px !important;
    bottom: auto !important;
    left: auto !important;
    padding: 0 !important;
}

.nc-swal-toast {
    font-family: 'Roboto', sans-serif !important;
    display: flex !important;
    align-items: center !important;
    flex-direction: row !important;
    border-radius: 10px !important;
    padding: 13px 16px 13px 14px !important;
    min-width: 260px !important;
    max-width: 340px !important;
    width: auto !important;
    box-shadow:
        0 10px 32px rgba(0,0,0,.16),
        0 2px 8px  rgba(0,0,0,.10) !important;
    border: none !important;
    gap: 0 !important;
}

/* Type-tinted backgrounds */
.nc-toast-success { background: #f0fdf4 !important; border-left: 4px solid #198754 !important; }
.nc-toast-error   { background: #fff5f5 !important; border-left: 4px solid #dc3545 !important; }
.nc-toast-warning { background: #fffbeb !important; border-left: 4px solid #e67e22 !important; }
.nc-toast-info    { background: #eff6ff !important; border-left: 4px solid #007bff !important; }

/* Icon — compact, no border circle, inline with text */
.nc-swal-toast .swal2-icon {
    width:  32px !important;
    height: 32px !important;
    min-width: 32px !important;
    border-width: 2px !important;
    margin: 0 12px 0 0 !important;
    flex-shrink: 0 !important;
    font-size: 1rem !important;
}
.nc-swal-toast .swal2-icon .swal2-icon-content {
    font-size: 1.05rem !important;
    line-height: 1 !important;
}

/* Text block */
.nc-swal-toast .swal2-title {
    font-family: 'Roboto', sans-serif !important;
    font-size: .875rem !important;
    font-weight: 700 !important;
    color: #1a1a2e !important;
    padding: 0 !important;
    margin: 0 !important;
    text-align: left !important;
    line-height: 1.35 !important;
    flex: 1 !important;
    white-space: normal !important;
    word-break: break-word !important;
}
.nc-swal-toast .swal2-html-container {
    font-family: 'Roboto', sans-serif !important;
    font-size: .78rem !important;
    color: #6c757d !important;
    margin: 3px 0 0 44px !important; /* 44px = icon(32) + gap(12) */
    padding: 0 !important;
    text-align: left !important;
    line-height: 1.5 !important;
    white-space: normal !important;
    word-break: break-word !important;
}

/* Progress bar */
.nc-swal-toast .swal2-timer-progress-bar-container {
    border-radius: 0 0 10px 10px !important;
}
.nc-swal-toast .swal2-timer-progress-bar {
    height: 3px !important;
    border-radius: 0 0 10px 10px !important;
    opacity: .45 !important;
}
.nc-toast-success .swal2-timer-progress-bar { background: #198754 !important; }
.nc-toast-error   .swal2-timer-progress-bar { background: #dc3545 !important; }
.nc-toast-warning .swal2-timer-progress-bar { background: #e67e22 !important; }
.nc-toast-info    .swal2-timer-progress-bar { background: #007bff !important; }


/* ══════════════════════════════════════════════════════════════════
   POPUP (error / warning / confirm)
   ══════════════════════════════════════════════════════════════════ */

.nc-swal-popup {
    font-family: 'Roboto', sans-serif !important;
    border-radius: 14px !important;
    padding: 36px 32px 28px !important;
    box-shadow:
        0 24px 64px rgba(0,0,0,.18),
        0 4px 16px  rgba(0,0,0,.10) !important;
    border: none !important;
    max-width: 420px !important;
    width: 100% !important;
}

/* Top accent strip */
.nc-swal-popup.nc-popup-error   { border-top: 4px solid #dc3545 !important; }
.nc-swal-popup.nc-popup-danger  { border-top: 4px solid #dc3545 !important; }
.nc-swal-popup.nc-popup-warning { border-top: 4px solid #e67e22 !important; }
.nc-swal-popup.nc-popup-success { border-top: 4px solid #198754 !important; }
.nc-swal-popup.nc-popup-info    { border-top: 4px solid #007bff !important; }

/* Icon */
.nc-swal-popup .swal2-icon {
    margin: 0 auto 18px !important;
    width: 68px !important;
    height: 68px !important;
    border-width: 3px !important;
}
.nc-swal-popup .swal2-icon .swal2-icon-content {
    font-size: 2.1rem !important;
}

/* Title */
.nc-swal-popup .swal2-title {
    font-size: 1.15rem !important;
    font-weight: 700 !important;
    color: #1a1a2e !important;
    padding: 0 0 6px !important;
    line-height: 1.3 !important;
    letter-spacing: -.01em !important;
}

/* Body */
.nc-swal-popup .swal2-html-container {
    font-size: .9rem !important;
    color: #6c757d !important;
    margin: 0 0 24px !important;
    line-height: 1.65 !important;
}

/* Actions */
.nc-swal-popup .swal2-actions {
    margin-top: 0 !important;
    gap: 8px !important;
    justify-content: center !important;
    width: 100% !important;
}

/* ── All popup buttons — base ── */
.nc-swal-popup .swal2-confirm,
.nc-swal-popup .swal2-cancel {
    font-family: 'Roboto', sans-serif !important;
    font-size: .875rem !important;
    font-weight: 500 !important;
    padding: 9px 28px !important;
    border-radius: 6px !important;
    border: none !important;
    letter-spacing: .02em !important;
    cursor: pointer !important;
    transition:
        background-color .15s ease,
        box-shadow .15s ease,
        transform .1s ease !important;
    outline: none !important;
    line-height: 1.5 !important;
    min-width: 96px !important;
}
.nc-swal-popup .swal2-confirm:active,
.nc-swal-popup .swal2-cancel:active {
    transform: scale(.97) !important;
}

/* Cancel */
.nc-swal-popup .swal2-cancel {
    background-color: #f1f3f5 !important;
    color: #495057 !important;
    box-shadow: 0 1px 3px rgba(0,0,0,.08) !important;
}
.nc-swal-popup .swal2-cancel:hover {
    background-color: #e2e6ea !important;
    color: #212529 !important;
}

/* Confirm variants */
.nc-btn-danger {
    background-color: #dc3545 !important;
    color: #fff !important;
    box-shadow: 0 2px 8px rgba(220,53,69,.38) !important;
}
.nc-btn-danger:hover {
    background-color: #bb2d3b !important;
    box-shadow: 0 4px 14px rgba(220,53,69,.48) !important;
}
.nc-btn-success {
    background-color: #198754 !important;
    color: #fff !important;
    box-shadow: 0 2px 8px rgba(25,135,84,.38) !important;
}
.nc-btn-success:hover {
    background-color: #157347 !important;
    box-shadow: 0 4px 14px rgba(25,135,84,.48) !important;
}
.nc-btn-primary {
    background-color: #007bff !important;
    color: #fff !important;
    box-shadow: 0 2px 8px rgba(0,123,255,.38) !important;
}
.nc-btn-primary:hover {
    background-color: #0069d9 !important;
    box-shadow: 0 4px 14px rgba(0,123,255,.48) !important;
}
.nc-btn-warning {
    background-color: #e67e22 !important;
    color: #fff !important;
    box-shadow: 0 2px 8px rgba(230,126,34,.38) !important;
}
.nc-btn-warning:hover {
    background-color: #cf6d17 !important;
    box-shadow: 0 4px 14px rgba(230,126,34,.48) !important;
}


/* ══════════════════════════════════════════════════════════════════
   LOADING
   ══════════════════════════════════════════════════════════════════ */
.nc-swal-loading .swal2-loader {
    border-color: #007bff transparent #007bff transparent !important;
    width: 54px !important;
    height: 54px !important;
    border-width: 4px !important;
}
.nc-swal-loading .swal2-title {
    font-size: .975rem !important;
    font-weight: 500 !important;
    color: #6c757d !important;
    margin-top: 14px !important;
    letter-spacing: .01em !important;
}


/* ══════════════════════════════════════════════════════════════════
   CONFIRM BODY HELPERS (chips, status pills)
   ══════════════════════════════════════════════════════════════════ */
.nc-confirm-meta {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f1f3f5;
    color: #495057;
    font-size: .8rem;
    font-weight: 500;
    padding: 5px 12px;
    border-radius: 20px;
    margin-bottom: 10px;
}
.nc-confirm-meta strong { color: #1a1a2e; font-weight: 700; }

.nc-status-active {
    display: inline-block;
    background: #d1e7dd; color: #0a3622;
    font-weight: 700; font-size: .78rem;
    padding: 3px 10px; border-radius: 20px; letter-spacing: .03em;
}
.nc-status-inactive {
    display: inline-block;
    background: #e9ecef; color: #495057;
    font-weight: 700; font-size: .78rem;
    padding: 3px 10px; border-radius: 20px; letter-spacing: .03em;
}
    `;

    var style = document.createElement('style');
    style.id = id;
    style.textContent = css;
    document.head.appendChild(style);
}());


/* ── ncSwal public API ───────────────────────────────────────────── */
window.ncSwal = (function () {
    'use strict';

    /* ── Toast ──────────────────────────────────────────────────── */
    function toast(type, title, text) {
        Swal.fire({
            toast:             true,
            position:          'top-end',
            icon:              type,
            title:             title || '',
            html:              text  || undefined,
            showConfirmButton: false,
            timer:             3000,
            timerProgressBar:  true,
            customClass: {
                container: 'nc-swal-toast',
                popup:     'nc-toast-' + type
            },
            didOpen: function (el) {
                el.addEventListener('mouseenter', Swal.stopTimer);
                el.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    }

    /* ── Error popup ────────────────────────────────────────────── */
    function error(title, text) {
        Swal.fire({
            icon:              'error',
            title:             title || 'Something went wrong',
            html:              text  || 'An unexpected error occurred. Please try again.',
            confirmButtonText: 'OK',
            customClass: {
                popup:         'nc-swal-popup nc-popup-error',
                confirmButton: 'nc-btn-danger'
            },
            buttonsStyling: false
        });
    }

    /* ── Warning popup ──────────────────────────────────────────── */
    function warning(title, text) {
        Swal.fire({
            icon:              'warning',
            title:             title || 'Heads up',
            html:              text  || '',
            confirmButtonText: 'Got it',
            customClass: {
                popup:         'nc-swal-popup nc-popup-warning',
                confirmButton: 'nc-btn-warning'
            },
            buttonsStyling: false
        });
    }

    /* ── Loading ────────────────────────────────────────────────── */
    function loading(title) {
        Swal.fire({
            title:             title || 'Processing...',
            allowOutsideClick: false,
            allowEscapeKey:    false,
            showConfirmButton: false,
            didOpen:           function () { Swal.showLoading(); },
            customClass: {
                popup: 'nc-swal-popup nc-swal-loading'
            }
        });
    }

    /* ── Close ──────────────────────────────────────────────────── */
    function close() {
        Swal.close();
    }

    /* ── Confirm ────────────────────────────────────────────────── */
    function confirm(opts) {
        opts = opts || {};
        var variant    = opts.confirmVariant || 'primary';
        var popVariant = opts.popupVariant   || 'info';

        return Swal.fire({
            icon:              'question',
            title:             opts.title       || 'Are you sure?',
            html:              opts.html        || '',
            showCancelButton:  true,
            confirmButtonText: opts.confirmText || 'Confirm',
            cancelButtonText:  'Cancel',
            reverseButtons:    true,
            focusCancel:       true,
            zIndex:            9999,
            customClass: {
                popup:         'nc-swal-popup nc-popup-' + popVariant,
                confirmButton: 'nc-btn-' + variant,
                cancelButton:  ''
            },
            buttonsStyling: false
        }).then(function (result) {
            return result.isConfirmed;
        });
    }

    return { toast: toast, error: error, warning: warning, loading: loading, close: close, confirm: confirm };
}());
