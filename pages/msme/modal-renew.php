<div class="modal fade" id="renewBusinessModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content msme-modal-content">

            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="material-icons text-primary mr-2" style="font-size:22px;">autorenew</i>Renew Business Registration
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label class="msme-label">
                        <i class="material-icons msme-readonly-icon">lock</i>Business Name
                    </label>
                    <input type="text" class="form-control msme-input" id="renewBusName" readonly>
                </div>
                <div class="form-group">
                    <label class="msme-label">
                        <i class="material-icons msme-readonly-icon">lock</i>Entity No
                    </label>
                    <input type="text" class="form-control msme-input" id="renewBusEntityNo" readonly>
                </div>
                <div class="form-group mb-0">
                    <label class="msme-label">
                        <i class="material-icons msme-readonly-icon">lock</i>Registration Type
                    </label>
                    <input type="text" class="form-control msme-input" id="renewRegType" readonly>
                </div>
            </div>

            <div class="modal-footer msme-modal-footer">
                <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-raised-success d-flex align-items-center" id="btnRenewBusiness" onclick="renewBusiness()">
                    <i class="material-icons mr-1" style="font-size:18px;">autorenew</i>Renew
                </button>
            </div>

        </div>
    </div>
</div>
