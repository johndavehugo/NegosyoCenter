<style>
    /* ── Add Incident Modal — scoped styles ─────────────────── */

    /* Affected businesses inline table */
    #addIncidentModal .affected-table {
        background: #fff;
        color: #343a40;
        font-size: 0.85rem;
        table-layout: auto;
    }
    #addIncidentModal .affected-table thead th {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #495057;
        font-size: 0.70rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
        vertical-align: middle;
        text-align: center;
        padding: 8px 10px;
    }
    #addIncidentModal .affected-table tbody td {
        font-size: 0.84rem;
        vertical-align: middle;
        text-align: center;
        padding: 6px 8px;
    }
    /* Left-align text-heavy columns */
    #addIncidentModal .affected-table tbody td.col-name,
    #addIncidentModal .affected-table tbody td.col-owner {
        text-align: left;
        white-space: normal;
        max-width: 160px;
        word-break: break-word;
    }
    #addIncidentModal .affected-table tfoot th {
        background-color: #f8f9fa;
        font-size: 0.85rem;
        color: #495057;
        padding: 8px 10px;
    }
    /* Uniform compact form controls inside the table */
    #addIncidentModal .affected-table .form-control-sm {
        height: 32px;
        font-size: 0.82rem;
        padding: 3px 8px;
        border-radius: 4px;
    }
    #addIncidentModal .affected-table .date-input    { min-width: 130px; }
    #addIncidentModal .affected-table .nature-select { min-width: 110px; }
    #addIncidentModal .affected-table .status-select { min-width: 140px; }
    #addIncidentModal .affected-table .damage-input  { min-width: 110px; text-align: right; }
    #addIncidentModal .affected-table .remarks-input { min-width: 130px; }

    /* Remove button */
    #addIncidentModal .btn-remove-business {
        color: #dc3545;
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px 6px;
        border-radius: 4px;
        transition: background-color 0.1s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    #addIncidentModal .btn-remove-business:hover {
        background-color: #fff5f5;
        color: #b91c1c;
    }
    #addIncidentModal .btn-remove-business .material-icons {
        font-size: 17px !important;
    }
</style>

<form id="addIncidentForm" method="POST">
<div class="modal fade" id="addIncidentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content msme-modal-content">

            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="material-icons text-primary align-middle mr-2" style="font-size:22px;">add_circle</i>
                    Add Calamity Incident
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <!-- Calamity + Business selects -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="msme-label">Type of Calamity <span class="text-danger">*</span></label>
                            <select id="addIncidentCalamity" name="addIncidentCalamity" style="width:100%">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="msme-label">Business Affected <span class="text-danger">*</span></label>
                            <select id="addIncidentJuridical" name="addIncidentJuridical" multiple="multiple" style="width:100%"></select>
                            <small class="form-text text-muted">Type to search businesses. You can select multiple.</small>
                        </div>
                    </div>
                </div>

                <!-- Affected businesses table -->
                <div class="card msme-section-card mt-2">
                    <div class="card-header msme-section-header">
                        <i class="material-icons text-primary align-middle mr-1" style="font-size:16px;">list_alt</i>
                        Selected Affected Businesses <span class="text-danger">*</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover affected-table mb-0" id="affectedBusinessesTable">
                                <thead>
                                    <tr>
                                        <th style="width:36px;">#</th>
                                        <th style="min-width:130px; text-align:left;">Business Name</th>
                                        <th style="min-width:110px;">Entity No.</th>
                                        <th style="min-width:120px; text-align:left;">Owner</th>
                                        <th style="min-width:140px;">Date of Occurrence</th>
                                        <th style="min-width:120px;">Nature of Damage</th>
                                        <th style="min-width:150px;">Status</th>
                                        <th style="min-width:140px;">Est. Damage (PHP)</th>
                                        <th style="min-width:140px;">Remarks</th>
                                        <th style="width:52px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="7" class="text-right" style="background:#f8f9fa; font-size:0.82rem; color:#495057; padding:8px 10px;">
                                            Total Estimated Damages:
                                        </th>
                                        <th id="affectedBusinessesTotal" class="text-right" style="background:#f8f9fa; font-size:0.85rem; color:#212529; padding:8px 10px;">
                                            0.00
                                        </th>
                                        <th colspan="2" style="background:#f8f9fa;"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="px-3 py-2 border-top" style="background:#fafafa;">
                            <small class="text-muted" id="affectedBusinessesEmpty">No businesses selected yet.</small>
                            <small class="text-muted d-block">Enter the date, nature of damage, status, estimated damage, and remarks for each business.</small>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer msme-modal-footer">
                <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-raised-success d-flex align-items-center" id="btnSaveIncident" onclick="addIncident()">
                    <i class="material-icons mr-1" style="font-size:18px;">save</i>Save
                </button>
            </div>

        </div>
    </div>
</div>
</form>
