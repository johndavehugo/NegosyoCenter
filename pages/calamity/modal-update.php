<style>
    .cal-update-modal .select2-container--default .select2-selection--single {
        background-color: #fff; border: 1px solid #ced4da;
        border-radius: 4px; height: 38px;
    }
    .cal-update-modal .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #343a40; line-height: 36px; padding-left: 10px; padding-right: 30px;
    }
    .cal-update-modal .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px; }
    .cal-update-modal .select2-container--default .select2-selection--single .select2-selection__placeholder { color: #6c757d; }
    .cal-update-modal .select2-container--default .select2-selection--single .select2-selection__clear {
        color: #dc3545; font-size: 18px; font-weight: bold; margin-right: 6px; cursor: pointer;
    }
    .cal-update-modal .select2-results__options { max-height: 250px; overflow-y: auto; }
</style>

<form id="updateIncidentForm" method="POST">
<div class="modal fade cal-update-modal" id="updateIncidentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color: #1a3a6b; color: white; border: 2px solid #b91c1c; border-radius: 10px;">

            <div class="modal-header border-0" style="background: linear-gradient(90deg, #1a3a6b 60%, #b91c1c 100%); border-radius: 8px 8px 0 0;">
                <h5 class="modal-title"><i class="fas fa-pen mr-2"></i>Update Calamity Incident</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="updateIncidentId" name="updateIncidentId">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Type of Calamity <span class="text-danger">*</span></label>
                            <select id="updateIncidentCalamity" name="updateIncidentCalamity" style="width:100%">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date of Occurrence <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="updateIncidentDate" name="updateIncidentDate">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Business Affected <span class="text-danger">*</span></label>
                            <select id="updateIncidentJuridical" name="updateIncidentJuridical" style="width:100%">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nature of Damage <span class="text-danger">*</span></label>
                            <select id="updateIncidentNature" name="updateIncidentNature" style="width:100%">
                                <option value=""></option>
                                <option value="PARTIAL">Partial</option>
                                <option value="TOTAL">Total</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Estimated Cost of Damages (PHP) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control" id="updateIncidentCost" name="updateIncidentCost">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" id="updateIncidentStatus" name="updateIncidentStatus">
                                <option value="PENDING_VERIFICATION">Pending Verification</option>
                                <option value="VERIFIED">Verified</option>
                                <option value="AID_RELEASED">Aid Released</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea class="form-control" id="updateIncidentRemarks" name="updateIncidentRemarks" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0" style="background: rgba(0,0,0,0.15); border-radius: 0 0 8px 8px;">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                <button type="button" class="btn" style="background:#b91c1c;color:#fff;border:none;" id="btnUpdateIncident" onclick="updateIncident()">
                    <i class="fas fa-save mr-1"></i>Save
                </button>
            </div>

        </div>
    </div>
</div>
</form>
