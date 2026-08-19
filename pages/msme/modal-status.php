<div class="modal fade" id="statusBusinessModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content msme-modal-content">

            <!-- Header -->
            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="material-icons text-primary mr-2" style="font-size:22px;">toggle_on</i>
                    Change Business Status
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt-3 pb-2">

                <!-- Business summary card -->
                <div class="status-summary-card mb-3">
                    <div class="status-summary-icon">
                        <i class="material-icons">storefront</i>
                    </div>
                    <div class="status-summary-info">
                        <div class="status-summary-name" id="statusBusNameDisplay">—</div>
                        <div class="status-summary-entity" id="statusBusEntityNoDisplay">—</div>
                    </div>
                </div>

                <!-- Current status display -->
                <div class="status-current-row mb-3">
                    <span class="msme-label mb-0 mr-2">Current Status</span>
                    <span id="statusCurStatusBadge">—</span>
                </div>

                <!-- Arrow indicator -->
                <div class="status-arrow-row mb-3">
                    <i class="material-icons status-arrow-icon">arrow_downward</i>
                    <span class="status-arrow-label">Change to</span>
                </div>

                <!-- New status selector -->
                <div class="form-group mb-0">
                    <label class="msme-label">New Status</label>
                    <div class="status-option-group">
                        <label class="status-option" id="optActive">
                            <input type="radio" name="statusNewStatus" id="statusNewStatus"
                                   value="ACTIVE" onchange="previewStatusChange(this.value)">
                            <span class="status-option-inner">
                                <i class="material-icons">check_circle</i>
                                <span>Active</span>
                            </span>
                        </label>
                        <label class="status-option" id="optInactive">
                            <input type="radio" name="statusNewStatus"
                                   value="INACTIVE" onchange="previewStatusChange(this.value)">
                            <span class="status-option-inner">
                                <i class="material-icons">cancel</i>
                                <span>Inactive</span>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Hidden inputs to preserve existing JS bindings -->
                <input type="hidden" id="statusBusName">
                <input type="hidden" id="statusBusEntityNo">
                <input type="hidden" id="statusCurStatus">

            </div>

            <!-- Footer -->
            <div class="modal-footer msme-modal-footer">
                <button type="button" class="btn btn-text-secondary" data-dismiss="modal">
                    Cancel
                </button>
                <button type="button" class="btn btn-raised-primary d-flex align-items-center"
                        id="btnStatusBusiness" onclick="changeBusinessStatus()">
                    <i class="material-icons mr-1" style="font-size:18px;">save</i>Update Status
                </button>
            </div>

        </div>
    </div>
</div>

<style>
/* ── Status modal — scoped styles ── */

/* Business summary card */
.status-summary-card {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-left: 4px solid #007bff;
    border-radius: 8px;
    padding: 12px 16px;
}
.status-summary-icon {
    width: 42px; height: 42px; min-width: 42px;
    border-radius: 50%;
    background-color: #007bff;
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    font-size: 1.1rem;
}
.status-summary-icon .material-icons { font-size: 20px; }
.status-summary-name {
    font-weight: 700; font-size: .95rem; color: #1a1a2e; line-height: 1.3;
}
.status-summary-entity {
    font-size: .75rem; color: #9ca3af; font-weight: 500; margin-top: 2px;
}

/* Current status row */
.status-current-row {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 8px 14px;
}

/* Arrow divider */
.status-arrow-row {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 0 4px;
}
.status-arrow-icon {
    font-size: 18px !important;
    color: #adb5bd;
}
.status-arrow-label {
    font-size: .72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #adb5bd;
}

/* Radio card options */
.status-option-group {
    display: flex;
    gap: 10px;
}
.status-option {
    flex: 1;
    cursor: pointer;
    margin: 0;
}
.status-option input[type="radio"] {
    display: none; /* hidden — card itself is the toggle */
}
.status-option-inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 14px 10px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    background: #fff;
    transition: border-color .15s, background .15s, box-shadow .15s;
    font-size: .82rem;
    font-weight: 600;
    color: #6c757d;
}
.status-option-inner .material-icons {
    font-size: 26px !important;
    color: #adb5bd;
    transition: color .15s;
}

/* Hover */
.status-option:hover .status-option-inner {
    border-color: #adb5bd;
    background: #f8f9fa;
}

/* Active selected */
.status-option input[value="ACTIVE"]:checked ~ .status-option-inner {
    border-color: #198754;
    background: #f0fdf4;
    color: #0a3622;
    box-shadow: 0 0 0 3px rgba(25,135,84,.12);
}
.status-option input[value="ACTIVE"]:checked ~ .status-option-inner .material-icons {
    color: #198754;
}

/* Inactive selected */
.status-option input[value="INACTIVE"]:checked ~ .status-option-inner {
    border-color: #dc3545;
    background: #fff5f5;
    color: #842029;
    box-shadow: 0 0 0 3px rgba(220,53,69,.12);
}
.status-option input[value="INACTIVE"]:checked ~ .status-option-inner .material-icons {
    color: #dc3545;
}
</style>

<script>
// Reset radio selection whenever modal closes, so it opens fresh next time
$(document).on('hidden.bs.modal', '#statusBusinessModal', function () {
    $('input[name="statusNewStatus"]').prop('checked', false);
});
</script>
