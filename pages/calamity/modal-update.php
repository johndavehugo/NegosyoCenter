<form id="updateIncidentForm" method="POST">
<div class="modal fade" id="updateIncidentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content msme-modal-content">

            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="material-icons text-primary align-middle mr-2" style="font-size:22px;">edit_note</i>
                    Update Calamity Incident
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="updateIncidentId" name="updateIncidentId">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="msme-label">Type of Calamity <span class="text-danger">*</span></label>
                            <select id="updateIncidentCalamity" name="updateIncidentCalamity" style="width:100%">
                                <option value=""></option>
                            </select>
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
                            <select id="updateIncidentJuridical" name="updateIncidentJuridical" style="width:100%">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="msme-label">Nature of Damage <span class="text-danger">*</span></label>
                            <select id="updateIncidentNature" name="updateIncidentNature" style="width:100%">
                                <option value=""></option>
                                <option value="PARTIAL">Partial</option>
                                <option value="TOTAL">Total</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card msme-section-card mb-3">
                    <div class="card-header msme-section-header">
                        <i class="material-icons text-primary align-middle mr-1" style="font-size:16px;">monetization_on</i>
                        Damage Details
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="msme-label">Estimated Cost of Damages (PHP) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text msme-input-prefix">₱</span>
                                        </div>
                                        <input type="number" step="0.01" min="0" class="form-control msme-input"
                                            id="updateIncidentCost" name="updateIncidentCost">
                                    </div>
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
                </div>

            </div>

            <div class="modal-footer msme-modal-footer">
                <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-raised-primary d-flex align-items-center" id="btnUpdateIncident" onclick="updateIncident()">
                    <i class="material-icons mr-1" style="font-size:18px;">save</i>Update
                </button>
            </div>

        </div>
    </div>
</div>
</form>
