<div class="modal fade" id="viewAffectedBusinessesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 1400px;">
        <div class="modal-content msme-modal-content">

            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="material-icons text-primary mr-2" style="font-size:22px;">business</i>Affected Businesses
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <p style="font-size:0.95rem;">
                    <i class="material-icons align-middle mr-1" style="font-size:18px;color:#dc3545;vertical-align:middle;">warning</i>
                    <strong>Calamity:</strong> <span id="viewCalamityName">—</span>
                </p>
                <div class="table-responsive" style="border-radius:6px;overflow:hidden;">
                    <table id="tblAffectedBusinesses" class="table table-striped table-bordered mb-0" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Business</th>
                                <th>Entity No.</th>
                                <th>Owner</th>
                                <th>Declaration Date</th>
                                <th>Nature of Damage</th>
                                <th>Estimated Cost of Damages</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer msme-modal-footer">
                <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
