<div class="modal fade" id="statusBusinessModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background-color: #343a40; color: white;">

            <div class="modal-header border-secondary">
                <h5 class="modal-title"><i class="fas fa-exchange-alt mr-2 text-info"></i>Change Business Status</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Business Name</label>
                    <input type="text" class="form-control" id="statusBusName" readonly>
                </div>
                <div class="form-group">
                    <label>Entity No</label>
                    <input type="text" class="form-control" id="statusBusEntityNo" readonly>
                </div>
                <div class="form-group">
                    <label>Current Status</label>
                    <input type="text" class="form-control" id="statusCurStatus" readonly>
                </div>
                <div class="form-group mb-0">
                    <label>New Status</label>
                    <select class="form-control" id="statusNewStatus" name="statusNewStatus">
                        <option value="" hidden>Select Status</option>
                        <option value="ACTIVE">ACTIVE</option>
                        <option value="INACTIVE">INACTIVE</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="btnStatusBusiness" onclick="changeBusinessStatus()">Update Status</button>
            </div>

        </div>
    </div>
</div>