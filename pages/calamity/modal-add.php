<style>
    .cal-modal .select2-container--bootstrap4 .select2-selection--single {
        background-color: #fff; border: 1px solid #ced4da;
        border-radius: 4px; height: 38px;
    }
    .cal-modal .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
        color: #343a40; line-height: 36px; padding-left: 10px; padding-right: 30px;
    }
    .cal-modal .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow { height: 36px; }
    .cal-modal .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder { color: #6c757d; }
    .cal-modal .select2-container--bootstrap4 .select2-selection--single .select2-selection__clear {
        color: #dc3545; font-size: 18px; font-weight: bold; margin-right: 6px; cursor: pointer;
    }
    .cal-modal .select2-container--bootstrap4 .select2-selection--multiple {
        background-color: #fff; border: 1px solid #ced4da; border-radius: 4px; min-height: 38px;
    }
    .cal-modal .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__rendered { padding: 3px 8px; }
    .cal-modal .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
        background-color: #1a3a6b; border: 1px solid #0f2a52; color: #fff;
        border-radius: 4px; padding: 2px 6px; margin-top: 3px; font-size: 13px;
        display: none;
    }
    .cal-modal .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove { color: #f87171; margin-right: 5px; }
    .cal-modal .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__placeholder { color: #6c757d; margin-top: 7px; }
    .cal-modal .select2-results__options { max-height: 250px; overflow-y: auto; }
    .cal-modal .affected-table { background: #fff; color: #343a40; }
    .cal-modal .affected-table thead th { background: #1a3a6b; color: #fff; border: none; font-size: 13px; white-space: nowrap; }
    .cal-modal .affected-table tbody td { font-size: 13px; vertical-align: middle; }
    .cal-modal .btn-remove-business { color: #b91c1c; background: none; border: none; cursor: pointer; font-size: 15px; }
    .cal-modal .btn-remove-business:hover { color: #7f1212; }
</style>

<form id="addIncidentForm" method="POST">
<div class="modal fade cal-modal" id="addIncidentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 1400px;">
        <div class="modal-content" style="background-color: #1a3a6b; color: white; border: 2px solid #b91c1c; border-radius: 10px;">

            <div class="modal-header border-0" style="background: linear-gradient(90deg, #1a3a6b 60%, #b91c1c 100%); border-radius: 8px 8px 0 0;">
                <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i>Add Calamity Incident</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Type of Calamity <span class="text-danger">*</span></label>
                            <select id="addIncidentCalamity" name="addIncidentCalamity" style="width:100%">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Business Affected <span class="text-danger">*</span></label>
                            <select id="addIncidentJuridical" name="addIncidentJuridical" multiple="multiple" style="width:100%">
                            </select>
                            <small class="text-white-50">Type to search businesses from MSME records. You can select multiple.</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Selected Affected Businesses <span class="text-danger">*</span></label>
                        <div class="table-responsive" style="border-radius: 6px; overflow: hidden;">
                            <table class="table table-bordered table-hover affected-table mb-0" id="affectedBusinessesTable">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>Business Name</th>
                                        <th>Entity No.</th>
                                        <th>Owner</th>
                                        <th style="min-width:140px;">Date of Occurrence</th>
                                        <th style="min-width:110px;">Nature of Damage</th>
                                        <th style="min-width:150px;">Status</th>
                                        <th style="min-width:150px;">Estimated Damage (PHP)</th>
                                        <th style="min-width:200px;">Remarks</th>
                                        <th style="width:60px; text-align:center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="7" style="text-align:right;">Total Estimated Damages:</th>
                                        <th id="affectedBusinessesTotal" style="text-align:right;">0.00</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <small class="text-white-50" id="affectedBusinessesEmpty">No businesses selected yet.</small>
                        <small class="text-white-50" style="display:block;">Enter the date, nature of damage, status, estimated damage, and remarks for each affected business.</small>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0" style="background: rgba(0,0,0,0.15); border-radius: 0 0 8px 8px;">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                <button type="button" class="btn" style="background:#b91c1c;color:#fff;border:none;" id="btnSaveIncident" onclick="addIncident()">
                    <i class="fas fa-save mr-1"></i>Save
                </button>
            </div>

        </div>
    </div>
</div>
</form>
