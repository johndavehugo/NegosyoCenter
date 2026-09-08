<div class="modal fade" id="viewAffectedBusinessesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" style="background-color: #1a3a6b; color: white; border: 2px solid #b91c1c; border-radius: 10px;">

            <div class="modal-header border-0" style="background: linear-gradient(90deg, #1a3a6b 60%, #b91c1c 100%); border-radius: 8px 8px 0 0;">
                <h5 class="modal-title"><i class="fas fa-building mr-2"></i>Affected Businesses</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <p style="font-size:0.95rem;">
                    <i class="fas fa-exclamation-triangle mr-1" style="color:#ef4444;"></i>
                    <strong>Calamity:</strong> <span id="viewCalamityName" style="color:#fca5a5;">—</span>
                </p>
                <div class="table-responsive">
                    <table id="tblAffectedBusinesses" class="table table-striped table-bordered">
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

            <div class="modal-footer border-0" style="background: rgba(0,0,0,0.15); border-radius: 0 0 8px 8px;">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
