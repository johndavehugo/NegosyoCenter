<div class="modal fade" id="renewBusinessModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background-color: #343a40; color: white;">

            <div class="modal-header border-secondary">
                <h5 class="modal-title">Renew Business Registration</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Business Name</label>
                    <input type="text" class="form-control" id="renewBusName" readonly>
                </div>
                <div class="form-group">
                    <label>Entity No</label>
                    <input type="text" class="form-control" id="renewBusEntityNo" readonly>
                </div>
                <div class="form-group mb-0">
                    <label>Registration Type</label>
                    <input type="text" class="form-control" id="renewRegType" readonly>
                </div>
            </div>

            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="btnRenewBusiness" onclick="renewBusiness()">Renew</button>
            </div>

        </div>
    </div>
</div>