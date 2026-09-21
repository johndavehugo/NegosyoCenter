<style>
    /* ── Modern select wrapper (icon + select2, single visual box) ── */
    .cal-update-modal .modern-select-wrap {
        display: flex; align-items: center; gap: 10px;
        border: 1.5px solid #e2e8f0; border-radius: 12px; background: #fff;
        padding: 5px 10px 5px 12px;
        transition: border-color .15s, box-shadow .15s;
    }
    .cal-update-modal .modern-select-wrap:focus-within {
        border-color: #1e4db7;
        box-shadow: 0 0 0 3px rgba(30,77,183,.12);
    }
    .cal-update-modal .modern-select-wrap > i { font-size: 20px; color: #1e4db7; flex-shrink: 0; }
    .cal-update-modal .modern-select-body { flex: 1; min-width: 0; }
    .cal-update-modal .modern-select-body .select2-container--bootstrap4 .select2-selection {
        border-color: transparent !important; box-shadow: none !important;
        background: transparent !important; min-height: 34px;
    }
    .cal-update-modal .modern-select-body .select2-container--bootstrap4 .select2-selection--single { height: 36px; }
    .cal-update-modal .modern-select-body .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
        color: #1e293b; font-size: .82rem; font-weight: 500;
        line-height: 34px; padding-left: 2px; padding-right: 26px;
    }
    .cal-update-modal .modern-select-body .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow { height: 34px; right: 2px; }
    .cal-update-modal .modern-select-body .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder { color: #9ca3af; }
    .cal-update-modal .modern-select-body .select2-container--bootstrap4 .select2-selection--single .select2-selection__clear {
        color: #dc3545; font-size: 18px; font-weight: bold; margin-right: 4px; cursor: pointer;
    }
    /* Dropdown panel */
    .cal-update-modal .select2-dropdown {
        border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;
        box-shadow: 0 12px 32px rgba(15,23,42,.16);
    }
    .cal-update-modal .select2-search--dropdown { padding: 8px; border-bottom: 1px solid #f1f5f9; }
    .cal-update-modal .select2-search--dropdown .select2-search__field {
        border: 1px solid #e2e8f0; border-radius: 8px;
        padding: 6px 10px; font-size: .8rem; outline: none;
    }
    .cal-update-modal .select2-search--dropdown .select2-search__field:focus { border-color: #1e4db7; }
    .cal-update-modal .select2-results__options { max-height: 250px; overflow-y: auto; padding: 6px; }
    .cal-update-modal .select2-results__option {
        border-radius: 8px; padding: .5rem .65rem;
        font-size: .8rem; color: #1e293b;
    }
    .cal-update-modal .select2-results__option--highlighted {
        background: #eff6ff !important; color: #1e4db7 !important;
    }
    .cal-update-modal .select2-results__option[aria-selected="true"] {
        background: #e0e7ff !important; color: #1e40af !important; font-weight: 600;
    }
    .cal-update-modal .biz-opt-name { display: block; font-weight: 600; color: #1e293b; line-height: 1.35; }
    .cal-update-modal .biz-opt-sub { display: block; font-size: .72rem; color: #64748b; margin-top: 1px; }
</style>

<form id="updateIncidentForm" method="POST">
<div class="modal fade cal-update-modal" id="updateIncidentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content msme-modal-content">

            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="material-icons text-primary mr-2" style="font-size:22px;">edit</i>Update Calamity Incident
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="updateIncidentId" name="updateIncidentId">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="msme-label">Type of Calamity <span class="text-danger">*</span></label>
                            <div class="modern-select-wrap">
                                <i class="material-icons">cloud</i>
                                <div class="modern-select-body">
                                    <select class="form-control msme-input" id="updateIncidentCalamity" name="updateIncidentCalamity" style="width:100%">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="msme-label">Date of Occurrence <span class="text-danger">*</span></label>
                            <input type="date" class="form-control msme-input" id="updateIncidentDate" name="updateIncidentDate">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="msme-label">Business Affected <span class="text-danger">*</span></label>
                            <div class="modern-select-wrap">
                                <i class="material-icons">storefront</i>
                                <div class="modern-select-body">
                                    <select class="form-control msme-input" id="updateIncidentJuridical" name="updateIncidentJuridical" style="width:100%">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="msme-label">Nature of Damage <span class="text-danger">*</span></label>
                            <div class="modern-select-wrap">
                                <i class="material-icons">category</i>
                                <div class="modern-select-body">
                                    <select class="form-control msme-input" id="updateIncidentNature" name="updateIncidentNature" style="width:100%">
                                        <option value=""></option>
                                        <option value="PARTIAL">Partial</option>
                                        <option value="TOTAL">Total</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="msme-label">Estimated Cost of Damages (PHP) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control msme-input" id="updateIncidentCost" name="updateIncidentCost">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="msme-label">Status</label>
                            <select class="form-control msme-input" id="updateIncidentStatus" name="updateIncidentStatus">
                                <option value="PENDING_VERIFICATION">Pending Verification</option>
                                <option value="VERIFIED">Verified</option>
                                <option value="AID_RELEASED">Aid Released</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-0">
                            <label class="msme-label">Remarks</label>
                            <textarea class="form-control msme-input" id="updateIncidentRemarks" name="updateIncidentRemarks" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer msme-modal-footer">
                <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-raised-primary d-flex align-items-center" id="btnUpdateIncident" onclick="updateIncident()">
                    <i class="material-icons mr-1" style="font-size:18px;">save</i>Save
                </button>
            </div>

        </div>
    </div>
</div>
</form>
