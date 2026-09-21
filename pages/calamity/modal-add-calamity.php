<form id="addCalamityForm" method="POST">
<div class="modal fade" id="addCalamityModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 900px;">
        <div class="modal-content msme-modal-content">

            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="material-icons text-primary mr-2" style="font-size:22px;">cloud</i>Add Calamity
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="msme-label">Calamity Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control msme-input" id="addCalamityName" name="addCalamityName">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="msme-label">Type of Calamity <span class="text-danger">*</span></label>
                            <select class="form-control msme-input" id="addCalamityType" name="addCalamityType">
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
                            <label class="msme-label">Declaration Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control msme-input" id="addCalamityDate" name="addCalamityDate">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label class="msme-label">Description</label>
                            <textarea class="form-control msme-input" id="addCalamityDescription" name="addCalamityDescription" rows="1"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer msme-modal-footer">
                <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-raised-primary d-flex align-items-center" id="btnSaveCalamity" onclick="addCalamity()">
                    <i class="material-icons mr-1" style="font-size:18px;">save</i>Save
                </button>
            </div>

        </div>
    </div>
</div>
</form>
