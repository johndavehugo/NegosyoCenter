<form id="addCalamityForm" method="POST">
<div class="modal fade" id="addCalamityModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color: #1a3a6b; color: white;">

            <div class="modal-header border-secondary">
                <h5 class="modal-title">Add Calamity</h5>
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

            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="btnSaveCalamity" onclick="addCalamity()">Save</button>
            </div>

        </div>
    </div>
</div>
</form>
