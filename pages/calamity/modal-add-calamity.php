<form id="addCalamityForm" method="POST">
<div class="modal fade" id="addCalamityModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 900px;">
        <div class="modal-content" style="background-color: #1a3a6b; color: white; border: 2px solid #b91c1c; border-radius: 10px;">

            <div class="modal-header border-0" style="background: linear-gradient(90deg, #1a3a6b 60%, #b91c1c 100%); border-radius: 8px 8px 0 0;">
                <h5 class="modal-title"><i class="fas fa-cloud-rain mr-2"></i>Add Calamity</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Calamity Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="addCalamityName" name="addCalamityName">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Type of Calamity <span class="text-danger">*</span></label>
                            <select class="form-control" id="addCalamityType" name="addCalamityType">
                                <option value="" hidden>Select Type</option>
                                <option value="TYPHOON">Typhoon</option>
                                <option value="FLOOD">Flood</option>
                                <option value="EARTHQUAKE">Earthquake</option>
                                <option value="FIRE">Fire</option>
                                <option value="LANDSLIDE">Landslide</option>
                                <option value="OTHER">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Declaration Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="addCalamityDate" name="addCalamityDate">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" id="addCalamityDescription" name="addCalamityDescription" rows="1"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0" style="background: rgba(0,0,0,0.15); border-radius: 0 0 8px 8px;">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                <button type="button" class="btn" style="background:#b91c1c;color:#fff;border:none;" id="btnSaveCalamity" onclick="addCalamity()">
                    <i class="fas fa-save mr-1"></i>Save
                </button>
            </div>

        </div>
    </div>
</div>
</form>
