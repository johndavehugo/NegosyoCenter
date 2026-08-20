<div class="modal fade" id="renewBusinessModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content msme-modal-content">

            <!-- Header -->
            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="material-icons text-primary mr-2" style="font-size:22px;">autorenew</i>
                    Renew Business Registration
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt-3 pb-2">

                <!-- Business summary card — mirrors status modal pattern -->
                <div class="renew-summary-card mb-3">
                    <div class="renew-summary-icon">
                        <i class="material-icons">storefront</i>
                    </div>
                    <div class="renew-summary-info">
                        <div class="renew-summary-name" id="renewBusNameDisplay">—</div>
                        <div class="renew-summary-entity" id="renewBusEntityNoDisplay">—</div>
                    </div>
                </div>

                <!-- Current registration type row -->
                <div class="renew-current-row mb-3">
                    <span class="msme-label mb-0 mr-2">Current Registration</span>
                    <span id="renewRegTypeBadge">—</span>
                </div>

                <!-- Arrow + what will change -->
                <div class="renew-arrow-row mb-3">
                    <i class="material-icons renew-arrow-icon">arrow_downward</i>
                    <span class="renew-arrow-label">Will be updated to</span>
                </div>

                <!-- New registration type preview card -->
                <div class="renew-new-type-card mb-1">
                    <i class="material-icons renew-new-type-icon">autorenew</i>
                    <span class="renew-new-type-label">RENEWAL</span>
                </div>

                <!-- Hidden inputs — preserve existing JS bindings -->
                <input type="hidden" id="renewBusName">
                <input type="hidden" id="renewBusEntityNo">
                <input type="hidden" id="renewRegType">

            </div>

            <!-- Footer -->
            <div class="modal-footer msme-modal-footer">
                <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-raised-success d-flex align-items-center"
                        id="btnRenewBusiness" onclick="renewBusiness()">
                    <i class="material-icons mr-1" style="font-size:18px;">autorenew</i>Renew
                </button>
            </div>

        </div>
    </div>
</div>

<style>
/* ── Renew modal — scoped styles ── */

/* Business summary card */
.renew-summary-card {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-left: 4px solid #198754;
    border-radius: 8px;
    padding: 12px 16px;
}
.renew-summary-icon {
    width: 42px; height: 42px; min-width: 42px;
    border-radius: 50%;
    background-color: #198754;
    display: flex; align-items: center; justify-content: center;
    color: #fff;
}
.renew-summary-icon .material-icons { font-size: 20px; }
.renew-summary-name {
    font-weight: 700; font-size: .95rem; color: #1a1a2e; line-height: 1.3;
}
.renew-summary-entity {
    font-size: .75rem; color: #9ca3af; font-weight: 500; margin-top: 2px;
}

/* Current registration type row */
.renew-current-row {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 8px 14px;
}

/* Arrow divider */
.renew-arrow-row {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 0 4px;
}
.renew-arrow-icon {
    font-size: 18px !important;
    color: #adb5bd;
}
.renew-arrow-label {
    font-size: .72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #adb5bd;
}

/* New registration type preview */
.renew-new-type-card {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: 2px solid #198754;
    border-radius: 8px;
    background: #f0fdf4;
    padding: 14px 10px;
    color: #0a3622;
}
.renew-new-type-icon {
    font-size: 24px !important;
    color: #198754;
}
.renew-new-type-label {
    font-size: .95rem;
    font-weight: 700;
    letter-spacing: .04em;
    color: #0a3622;
}
</style>

<!-- Reset is handled in business-renew.js to avoid listener conflicts -->
