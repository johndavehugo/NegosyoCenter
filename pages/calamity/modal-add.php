<form id="addIncidentForm" method="POST">
<div class="modal fade" id="addIncidentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color: #1a3a6b; color: white;">

            <div class="modal-header border-secondary">
                <h5 class="modal-title">Add Calamity Incident</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <style>
                /* Select2 inside dark modal */
                #addIncidentModal .select2-container--default .select2-selection--single {
                    background-color: #fff;
                    border: 1px solid #ced4da;
                    border-radius: 4px;
                    height: 38px;
                    line-height: 38px;
                }
                #addIncidentModal .select2-container--default .select2-selection--single .select2-selection__rendered {
                    color: #343a40;
                    line-height: 36px;
                    padding-left: 10px;
                    padding-right: 30px;
                }
                #addIncidentModal .select2-container--default .select2-selection--single .select2-selection__arrow {
                    height: 36px;
                }
                #addIncidentModal .select2-container--default .select2-selection--single .select2-selection__placeholder {
                    color: #6c757d;
                }
                #addIncidentModal .select2-container--default .select2-selection--single .select2-selection__clear {
                    color: #dc3545;
                    font-size: 18px;
                    font-weight: bold;
                    margin-right: 6px;
                    cursor: pointer;
                }
            </style>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Type of Calamity <span class="text-danger">*</span></label>
                            <select id="addIncidentCalamity" name="addIncidentCalamity" style="width:100%">
                                <option value=""></option>
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
                            <select id="addIncidentJuridical" name="addIncidentJuridical" style="width:100%">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nature of Damage <span class="text-danger">*</span></label>
                            <select id="addIncidentNature" name="addIncidentNature" style="width:100%">
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
                <button type="button" class="btn btn-danger" id="btnSaveIncident" onclick="addIncident()">Save</button>
            </div>

        </div>
    </div>
</div>
</form>
