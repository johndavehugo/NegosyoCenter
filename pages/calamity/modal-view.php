<style>
    /* Affected businesses DataTable — scoped to view modal */
    #viewAffectedBusinessesModal #tblAffectedBusinesses.dataTable thead th {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #495057;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        text-align: center;
    }
    #viewAffectedBusinessesModal #tblAffectedBusinesses.dataTable tbody td {
        text-align: center;
        vertical-align: middle;
        font-size: 0.875rem;
    }
    #viewAffectedBusinessesModal #tblAffectedBusinesses.dataTable tbody tr {
        transition: background-color 0.1s ease;
    }
    #viewAffectedBusinessesModal #tblAffectedBusinesses.dataTable tbody tr:hover > td {
        background-color: rgba(0, 123, 255, 0.04) !important;
    }
</style>

<div class="modal fade" id="viewAffectedBusinessesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content msme-modal-content">

            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="material-icons text-primary align-middle mr-2" style="font-size:22px;">domain</i>
                    Affected Businesses
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <!-- Calamity context chip -->
                <div class="d-flex align-items-center mb-3">
                    <span class="msme-entity-chip">
                        <i class="material-icons" style="font-size:14px;vertical-align:middle;">cloud</i>
                        Calamity: <strong id="viewCalamityName" class="ml-1">—</strong>
                    </span>
                </div>

                <!-- Affected businesses table -->
                <div class="card msme-section-card">
                    <div class="card-header msme-section-header">
                        <i class="material-icons text-primary align-middle mr-1" style="font-size:16px;">list_alt</i>
                        Incident Records
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="tblAffectedBusinesses" class="table table-striped table-bordered mb-0">
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
                </div>

            </div>

            <div class="modal-footer msme-modal-footer">
                <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
