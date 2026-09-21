<style>
    /* ── Modern select wrapper (icon + select2, single visual box) ── */
    .cal-modal .modern-select-wrap {
        display: flex; align-items: center; gap: 10px;
        border: 1.5px solid #e2e8f0; border-radius: 12px; background: #fff;
        padding: 5px 10px 5px 12px;
        transition: border-color .15s, box-shadow .15s;
    }
    .cal-modal .modern-select-wrap:focus-within {
        border-color: #1e4db7;
        box-shadow: 0 0 0 3px rgba(30,77,183,.12);
    }
    .cal-modal .modern-select-wrap > i { font-size: 20px; color: #1e4db7; flex-shrink: 0; }
    .cal-modal .modern-select-body { flex: 1; min-width: 0; }
    .cal-modal .modern-select-body .select2-container--bootstrap4 .select2-selection {
        border-color: transparent !important; box-shadow: none !important;
        background: transparent !important; min-height: 34px;
    }
    .cal-modal .modern-select-body .select2-container--bootstrap4 .select2-selection--single { height: 36px; }
    .cal-modal .modern-select-body .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
        color: #1e293b; font-size: .82rem; font-weight: 500;
        line-height: 34px; padding-left: 2px; padding-right: 26px;
    }
    .cal-modal .modern-select-body .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow { height: 34px; right: 2px; }
    .cal-modal .modern-select-body .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder { color: #9ca3af; }
    .cal-modal .modern-select-body .select2-container--bootstrap4 .select2-selection--single .select2-selection__clear {
        color: #dc3545; font-size: 18px; font-weight: bold; margin-right: 4px; cursor: pointer;
    }
    .cal-modal .modern-select-body .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__rendered {
        padding: 2px 4px; font-size: .8rem;
    }
    .cal-modal .modern-select-body .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__placeholder {
        color: #9ca3af; margin-top: 5px;
    }
    /* Selected pills (multi) — visible blue pills */
    .cal-modal .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
        background: linear-gradient(135deg, #1e4db7, #2563eb) !important;
        border: none !important; color: #fff !important;
        border-radius: 20px !important; padding: 2px 8px 2px 12px !important;
        margin-top: 4px !important; font-size: .74rem !important; font-weight: 600;
    }
    .cal-modal .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
        color: #bfdbfe !important; margin-right: 6px !important;
        border: none !important; background: transparent !important; font-weight: 400;
    }
    .cal-modal .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #fff !important; background: transparent !important;
    }
    .cal-modal .select2-container--bootstrap4 .select2-selection--multiple .select2-search__field { font-size: .8rem; margin-top: 6px; }
    /* Dropdown panel */
    .cal-modal .select2-dropdown {
        border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;
        box-shadow: 0 12px 32px rgba(15,23,42,.16);
    }
    .cal-modal .select2-search--dropdown { padding: 8px; border-bottom: 1px solid #f1f5f9; }
    .cal-modal .select2-search--dropdown .select2-search__field {
        border: 1px solid #e2e8f0; border-radius: 8px;
        padding: 6px 10px; font-size: .8rem; outline: none;
    }
    .cal-modal .select2-search--dropdown .select2-search__field:focus { border-color: #1e4db7; }
    .cal-modal .select2-results__options { max-height: 250px; overflow-y: auto; padding: 6px; }
    .cal-modal .select2-results__option {
        border-radius: 8px; padding: .5rem .65rem;
        font-size: .8rem; color: #1e293b;
    }
    .cal-modal .select2-results__option--highlighted {
        background: #eff6ff !important; color: #1e4db7 !important;
    }
    .cal-modal .select2-results__option[aria-selected="true"] {
        background: #e0e7ff !important; color: #1e40af !important; font-weight: 600;
    }
    .cal-modal .biz-opt-name { display: block; font-weight: 600; color: #1e293b; line-height: 1.35; }
    .cal-modal .biz-opt-sub { display: block; font-size: .72rem; color: #64748b; margin-top: 1px; }
    .cal-modal .affected-table thead th { font-size: 13px; white-space: nowrap; }
    .cal-modal .affected-table tbody td { font-size: 13px; vertical-align: middle; }
    .cal-modal .btn-remove-business { color: #dc3545; background: none; border: none; cursor: pointer; font-size: 15px; }
    .cal-modal .btn-remove-business:hover { color: #7f1212; }
</style>

<form id="addIncidentForm" method="POST">
<div class="modal fade cal-modal" id="addIncidentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 1400px;">
        <div class="modal-content msme-modal-content">

            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="material-icons text-primary mr-2" style="font-size:22px;">add_circle</i>Add Calamity Incident
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="msme-label">Type of Calamity <span class="text-danger">*</span></label>
                            <div class="modern-select-wrap">
                                <i class="material-icons">cloud</i>
                                <div class="modern-select-body">
                                    <select class="form-control msme-input" id="addIncidentCalamity" name="addIncidentCalamity" style="width:100%">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="msme-label">Business Affected <span class="text-danger">*</span></label>
                            <div class="modern-select-wrap">
                                <i class="material-icons">storefront</i>
                                <div class="modern-select-body">
                                    <select class="form-control msme-input" id="addIncidentJuridical" name="addIncidentJuridical" multiple="multiple" style="width:100%">
                                    </select>
                                </div>
                            </div>
                            <small class="form-text text-muted">Type to search businesses from MSME records. You can select multiple.</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="msme-label">Selected Affected Businesses <span class="text-danger">*</span></label>
                        <div class="table-responsive" style="border-radius: 6px; overflow: hidden;">
                            <table class="table table-striped table-bordered table-hover affected-table mb-0" id="affectedBusinessesTable">
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
                        <small class="form-text text-muted" id="affectedBusinessesEmpty">No businesses selected yet.</small>
                        <small class="form-text text-muted" style="display:block;">Enter the date, nature of damage, status, estimated damage, and remarks for each affected business.</small>
                    </div>
                </div>
            </div>

            <div class="modal-footer msme-modal-footer">
                <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-raised-primary d-flex align-items-center" id="btnSaveIncident" onclick="addIncident()">
                    <i class="material-icons mr-1" style="font-size:18px;">save</i>Save
                </button>
            </div>

        </div>
    </div>
</div>
</form>
