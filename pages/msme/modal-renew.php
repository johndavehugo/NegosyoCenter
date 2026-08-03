<div class="modal fade" id="renewBusinessModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-ncims">

            <!-- Header -->
            <div class="modal-header modal-ncims-header">
                <div class="d-flex align-items-center">
                    <div class="modal-icon-circle bg-green mr-3">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0">Renew Business Registration</h5>
                        <small style="color:rgba(255,255,255,.65);">Confirm registration renewal below</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <!-- Body -->
            <div class="modal-body modal-ncims-body">

                <!-- Summary card (display only) -->
                <div class="renew-summary-card">
                    <div class="renew-summary-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="renew-summary-info">
                        <div class="renew-summary-name" id="renewBusNameDisplay">—</div>
                        <div class="renew-summary-meta">
                            <span id="renewBusEntityNoDisplay">—</span>
                            <span class="renew-summary-dot">•</span>
                            <span id="renewRegTypeDisplay">—</span>
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs — keep original IDs for existing JS (.val()) -->
                <input type="hidden" id="renewBusName">
                <input type="hidden" id="renewBusEntityNo">
                <input type="hidden" id="renewRegType">

                <div class="renew-notice">
                    <i class="fas fa-info-circle mr-2"></i>
                    This will renew the business registration. Please confirm to proceed.
                </div>

            </div>

            <!-- Footer -->
            <div class="modal-footer modal-ncims-footer">
                <button type="button" class="btn btn-ncims-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-ncims-save" id="btnRenewBusiness" onclick="renewConfirm()">
                    <i class="fas fa-sync-alt mr-2"></i>Confirm Renewal
                </button>
            </div>

        </div>
    </div>
</div>
