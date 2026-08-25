<style>
    #viewAffectedBusinessesModal .dataTables_wrapper {
        background: transparent; color: #e2e8f0;
    }
    #viewAffectedBusinessesModal .dataTables_wrapper .dataTables_length,
    #viewAffectedBusinessesModal .dataTables_wrapper .dataTables_filter,
    #viewAffectedBusinessesModal .dataTables_wrapper .dataTables_info,
    #viewAffectedBusinessesModal .dataTables_wrapper .dataTables_paginate {
        color: #94a3b8 !important;
    }
    #viewAffectedBusinessesModal .dataTables_wrapper .dataTables_filter input,
    #viewAffectedBusinessesModal .dataTables_wrapper .dataTables_length select {
        background: #0f2a52; color: #e2e8f0; border: 1px solid rgba(255,255,255,.15);
        border-radius: 4px; padding: 4px 8px;
    }
    #viewAffectedBusinessesModal .dataTables_wrapper .dataTables_paginate .paginate_button {
        color: #94a3b8 !important; background: transparent !important;
        border: 1px solid rgba(255,255,255,.1) !important; border-radius: 4px;
    }
    #viewAffectedBusinessesModal .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: rgba(255,255,255,.1) !important; color: #fff !important;
        border-color: rgba(255,255,255,.2) !important;
    }
    #viewAffectedBusinessesModal .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #b91c1c !important; color: #fff !important;
        border-color: #b91c1c !important;
    }
    #viewAffectedBusinessesModal table.dataTable {
        color: #e2e8f0 !important; background: transparent !important;
        border-color: rgba(255,255,255,.1) !important;
    }
    #viewAffectedBusinessesModal table.dataTable thead th,
    #viewAffectedBusinessesModal table.dataTable thead td {
        background: #0f2a52 !important; color: #94a3b8 !important;
        border-bottom: 2px solid rgba(255,255,255,.08) !important;
        font-size: .75rem; text-transform: uppercase; letter-spacing: .04em;
    }
    #viewAffectedBusinessesModal table.dataTable tbody tr {
        background: transparent !important; color: #e2e8f0 !important;
    }
    #viewAffectedBusinessesModal table.dataTable tbody tr:nth-child(odd) {
        background: rgba(255,255,255,.03) !important;
    }
    #viewAffectedBusinessesModal table.dataTable tbody tr:nth-child(even) {
        background: transparent !important;
    }
    #viewAffectedBusinessesModal table.dataTable tbody td {
        background: transparent !important; color: #e2e8f0 !important;
        border-color: rgba(255,255,255,.08) !important;
    }
    #viewAffectedBusinessesModal table.dataTable tbody tr:hover td,
    #viewAffectedBusinessesModal table.dataTable tbody tr:hover {
        background: rgba(59,130,246,.15) !important; color: #fff !important;
    }
    #viewAffectedBusinessesModal table.dataTable tbody td::selection,
    #viewAffectedBusinessesModal table.dataTable tbody td::-moz-selection {
        background: rgba(59,130,246,.3); color: #fff;
    }
    #viewAffectedBusinessesModal table.dataTable row-selected td {
        background: rgba(59,130,246,.2) !important; color: #fff !important;
    }
    #viewAffectedBusinessesModal table.dataTable .sorting_asc,
    #viewAffectedBusinessesModal table.dataTable .sorting_desc {
        color: #60a5fa !important;
    }
    #viewAffectedBusinessesModal table.dataTable .sorting {
        color: #64748b !important;
    }
    #viewAffectedBusinessesModal table.dataTable thead .sorting:after,
    #viewAffectedBusinessesModal table.dataTable thead .sorting_asc:after,
    #viewAffectedBusinessesModal table.dataTable thead .sorting_desc:after {
        opacity: .5;
    }
</style>

<div class="modal fade" id="viewAffectedBusinessesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 1400px;">
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
                <div class="table-responsive" style="border-radius:6px;overflow:hidden;">
                    <table id="tblAffectedBusinesses" class="table table-bordered" style="width:100%;">
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

            <div class="modal-footer border-0" style="background:rgba(0,0,0,.15);border-top:1px solid rgba(255,255,255,.06);border-radius:0 0 8px 8px;">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
