<form id="addIncidentForm" method="POST">
<div class="modal fade" id="addIncidentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color: #343a40; color: white;">

            <div class="modal-header border-secondary">
                <h5 class="modal-title">Add Calamity Incident</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Type of Calamity <span class="text-danger">*</span></label>
                            <select class="form-control" id="addIncidentCalamity" name="addIncidentCalamity">
                                <option value="" hidden>Select Calamity</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date of Occurrence <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="addIncidentDate" name="addIncidentDate">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Business Affected <span class="text-danger">*</span></label>
                            <select class="form-control" id="addIncidentJuridical" name="addIncidentJuridical">
                                <option value="" hidden>Select Business</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nature of Damage <span class="text-danger">*</span></label>
                            <select class="form-control" id="addIncidentNature" name="addIncidentNature">
                                <option value="" hidden>Select Nature of Damage</option>
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
                            <input type="number" step="0.01" min="0" class="form-control" id="addIncidentCost" name="addIncidentCost">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" id="addIncidentStatus" name="addIncidentStatus">
                                <option value="PENDING_VERIFICATION">Pending Verification</option>
                                <option value="VERIFIED" selected>Verified</option>
                                <option value="AID_RELEASED">Aid Released</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea class="form-control" id="addIncidentRemarks" name="addIncidentRemarks" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="btnSaveIncident" onclick="addIncident()">Save</button>
            </div>

        </div>
    </div>
</div>
</form>
