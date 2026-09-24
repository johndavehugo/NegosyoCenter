<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>San Carlos City | Commodities</title>

    <!-- Google Font: Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <style>
        /* Multi-select establishments tag styling */
        .select2-container--bootstrap4 .select2-selection--multiple {
            min-height: calc(1.5em + .75rem + 2px);
        }
        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff;
            border-color: #0062cc;
            color: #fff;
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 0.8rem;
        }
        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
            color: rgba(255,255,255,0.8);
            margin-right: 4px;
        }
        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #fff;
        }
    </style>
    <!-- AdminLTE + shared styles -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../dist/css/user_defined.css?v=5">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">
    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">

    <style>
        #tblCommodity.dataTable thead th {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #495057;
            font-size: .78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            text-align: center;
        }
        #tblCommodity.dataTable tbody td {
            text-align: center;
            vertical-align: middle;
            font-size: .9rem;
        }
        #tblCommodity.dataTable tbody tr:hover > td {
            background-color: rgba(0,123,255,.04) !important;
        }
    </style>
</head>

<body class="sidebar-mini layout-fixed" style="height:auto;">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img src="../../dist/img/itcsologo.webp" alt="Loading" height="60" width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar sticky-top navbar-expand navbar-dark">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="material-icons" style="font-size:20px;vertical-align:middle;">menu</i>
                </a>
            </li>
        </ul>
        <div class="collapse navbar-collapse justify-content-end text-sm" id="navbarNav">
            <ul class="navbar-nav navbar-sidebar justify-content-end">
                <li class="nav-item">
                    <a class="nav-link text-sm" data-widget="fullscreen" href="#" role="button">
                        <i class="material-icons text-white" style="font-size:20px;vertical-align:middle;">fullscreen</i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link text-sm pt-0 pb-0" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false" role="button">
                        <div class="image pt-0 pb-0">
                            <img src="../../dist/img/default.jfif"
                                 class="img-circle portrait-sidebar elevation-2" alt="User">
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right"
                         style="background-color:#495057!important">
                        <div class="user-panel d-flex">
                            <div class="image">
                                <img src="../../dist/img/default.jfif"
                                     class="img-circle elevation-2" alt="User">
                            </div>
                            <div class="info">
                                <a href="#" class="d-block text-white text-sm">BEN GANAGANAG</a>
                            </div>
                        </div>
                        <hr class="mt-1 mb-1">
                        <a class="nav-link text-sm sidebar-franchise-user-panel"
                           style="padding-left:13px;" role="button">
                            <i class="material-icons"
                               style="font-size:18px;vertical-align:middle;background:rgba(16,16,16,.42);border-radius:22px;padding:6px;">
                                manage_accounts
                            </i>&nbsp;Edit Profile
                        </a>
                        <a class="nav-link text-sm" style="padding-left:13px;"
                           onclick="logout()" role="button">
                            <i class="material-icons"
                               style="font-size:18px;vertical-align:middle;background:rgba(16,16,16,.42);border-radius:22px;padding:6px;">
                                logout
                            </i>&nbsp;Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <?php include '../../pages/sidebar/sidebar.php'; ?>

    <!-- Content -->
    <div id="body_wrapper" class="content-wrapper">
        <div class="content pt-4 pb-2">
            <div class="container-fluid">

                <!-- Page header card -->
                <div class="card card-raised mb-3">
                    <div class="card-body d-flex align-items-center justify-content-between px-4 py-3">
                        <div>
                            <h5 class="mb-0 font-weight-bold">Commodities</h5>
                            <small class="text-muted">Manage monitored commodities and their pricing</small>
                        </div>
                        <button type="button" class="btn btn-raised-primary btn-sm ml-auto"
                                id="btn_add_commodity"
                                data-toggle="modal" data-target="#addCommodityModal">
                            <i class="material-icons icon-sm leading-icon">add</i>Add Commodity
                        </button>
                    </div>
                </div>

                <!-- Table card -->
                <div class="card card-raised no-hover">
                    <div class="card-body px-3 pt-3 pb-0">
                        <table id="tblCommodity" class="table table-striped table-bordered mb-0"
                               style="width:100%;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Commodity Name</th>
<th>Category</th>
<th>Brand Name</th>
<th>Unit of Measure</th>
<th>SRP (₱)</th>
<th>Prevailing Price (₱)</th>
<th>Establishments</th>
<th>Agency</th>
<th>Options</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <div class="p-3"><h5>Title</h5><p>Sidebar content</p></div>
    </aside>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline">All rights reserved</div>
        <strong>Copyright &copy; <?= date('Y') ?> ITCSO.
            <a href="http://lguscc.gov.ph/">Local Government of San Carlos City</a>
        </strong>
    </footer>

</div><!-- /wrapper -->

<!-- ── Add Commodity modal ──────────────────────────────────────────── -->
<div class="modal fade" id="addCommodityModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content msme-modal-content">

            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center mb-0">
                    <i class="material-icons text-primary mr-2" style="font-size:22px;">inventory_2</i>
                    Add Commodity
                </h5>
                <button type="button" class="close ml-auto" data-dismiss="modal">&times;</button>
            </div>

            <!-- Tabs -->
            <div class="px-3 pt-2" style="border-bottom:1px solid #e9ecef;">
                <ul class="nav nav-tabs border-0" id="addCommodityTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active px-3 py-2" id="add-tab-info-link"
                           data-toggle="tab" href="#addTabInfo" role="tab">
                            <i class="material-icons mr-1" style="font-size:16px;vertical-align:middle;">inventory_2</i>
                            Commodity Info
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2" id="add-tab-est-link"
                           data-toggle="tab" href="#addTabEst" role="tab">
                            <i class="material-icons mr-1" style="font-size:16px;vertical-align:middle;">store</i>
                            Establishment Prices
                            <span class="badge badge-primary ml-1" id="addEstBadge" style="display:none;"></span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="tab-content">

                <!-- ── Add Tab 1: Commodity Info ── -->
                <div class="tab-pane fade show active" id="addTabInfo" role="tabpanel">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="msme-label">Commodity Name <span class="text-danger">*</span></label>
                                    <input type="text" id="product_name" name="product_name"
                                           class="form-control msme-input"
                                           placeholder="Enter commodity name" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="msme-label">Category <span class="text-danger">*</span></label>
                                    <select id="category_id" name="category_id" class="form-control msme-input">
                                        <option value="">-- Select Category --</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="msme-label">Brand</label>
                                    <input type="text" id="brand_name" name="brand_name"
                                           class="form-control msme-input"
                                           placeholder="Enter brand name" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="msme-label">Unit of Measure <span class="text-danger">*</span></label>
                                    <input type="text" id="unit_of_measure" name="unit_of_measure"
                                           class="form-control msme-input"
                                           placeholder="e.g. kg, pcs, liter" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="msme-label">Establishments</label>
                            <select id="establishments" name="establishments" class="form-control msme-input" multiple>
                            </select>
                            <small class="form-text text-muted">
                                <i class="material-icons" style="font-size:13px;vertical-align:middle;">info</i>
                                Select establishments, then go to <strong>Establishment Prices</strong> tab to set individual prices.
                            </small>
                        </div>

                    </div>
                    <div class="modal-footer msme-modal-footer">
                        <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-raised-primary d-flex align-items-center"
                                id="btnNextToEstPrices">
                            Next: Set Prices
                            <i class="material-icons ml-1" style="font-size:18px;">arrow_forward</i>
                        </button>
                    </div>
                </div>

                <!-- ── Add Tab 2: Establishment Prices ── -->
                <div class="tab-pane fade" id="addTabEst" role="tabpanel">
                    <div class="modal-body p-0">

                        <!-- Empty state -->
                        <div id="addEstPriceEmpty" class="text-center text-muted py-5">
                            <i class="material-icons" style="font-size:40px;color:#dee2e6;">store_mall_directory</i>
                            <p class="mt-2 mb-0">No establishments selected yet.</p>
                            <small>Go back to <strong>Commodity Info</strong> and select establishments first.</small>
                        </div>

                        <!-- Prices table -->
                        <div id="addEstPriceTableWrap" style="display:none;">
                            <table class="table mb-0" id="tblAddEstPrices" style="font-size:0.88rem;">
                                <thead>
                                    <tr style="background:#f8f9fa;">
                                        <th class="pl-3" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;border-top:none;width:40%;">Establishment</th>
                                        <th style="font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;border-top:none;width:30%;">SRP (₱)</th>
                                        <th style="font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;border-top:none;width:30%;">Prevailing Price (₱)</th>
                                    </tr>
                                </thead>
                                <tbody id="addEstPriceTableBody"></tbody>
                            </table>
                        </div>

                    </div>
                    <div class="modal-footer msme-modal-footer justify-content-between">
                        <button type="button" class="btn btn-text-secondary"
                                id="btnBackToInfo">
                            <i class="material-icons mr-1" style="font-size:18px;">arrow_back</i>Back
                        </button>
                        <div>
                            <button type="button" class="btn btn-text-secondary mr-1" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-raised-success d-flex align-items-center d-inline-flex"
                                    id="btnSaveCommodity">
                                <i class="material-icons mr-1" style="font-size:18px;">save</i>Save Commodity
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ── Edit Commodity modal ─────────────────────────────────────────── -->
<div class="modal fade" id="updateCommodityModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content msme-modal-content">

            <div class="modal-header msme-modal-header">
                <div>
                    <h5 class="modal-title d-flex align-items-center mb-0">
                        <i class="material-icons text-primary mr-2" style="font-size:22px;">edit</i>
                        Edit Commodity
                    </h5>
                    <small class="text-muted" id="updateCommoditySubtitle"></small>
                </div>
                <button type="button" class="close ml-auto" data-dismiss="modal">&times;</button>
            </div>

            <!-- Tabs -->
            <div class="px-3 pt-2" style="border-bottom:1px solid #e9ecef;">
                <ul class="nav nav-tabs border-0" id="editCommodityTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active px-3 py-2" id="tab-info-link"
                           data-toggle="tab" href="#tabCommodityInfo" role="tab">
                            <i class="material-icons mr-1" style="font-size:16px;vertical-align:middle;">inventory_2</i>
                            Commodity Info
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2" id="tab-est-link"
                           data-toggle="tab" href="#tabEstPrices" role="tab">
                            <i class="material-icons mr-1" style="font-size:16px;vertical-align:middle;">store</i>
                            Establishment Prices
                            <span class="badge badge-primary ml-1" id="estPriceBadge" style="display:none;"></span>
                        </a>
                    </li>
                </ul>
            </div>

            <form id="updateCommodityForm">
                <div class="tab-content">

                    <!-- ── Tab 1: Commodity Info ── -->
                    <div class="tab-pane fade show active" id="tabCommodityInfo" role="tabpanel">
                        <div class="modal-body">
                            <input type="hidden" id="updateCommodityId">

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="msme-label">Commodity Name <span class="text-danger">*</span></label>
                                        <input type="text" id="updateCommodityProductName"
                                               class="form-control msme-input"
                                               placeholder="Enter commodity name" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="msme-label">Category <span class="text-danger">*</span></label>
                                        <select id="updateCommodityCategory" class="form-control msme-input">
                                            <option value="">-- Select Category --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="msme-label">Brand</label>
                                        <input type="text" id="updateCommodityBrand"
                                               class="form-control msme-input"
                                               placeholder="Enter brand name" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="msme-label">Unit of Measure <span class="text-danger">*</span></label>
                                        <input type="text" id="updateCommodityUnit"
                                               class="form-control msme-input"
                                               placeholder="e.g. kg, pcs, liter" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="msme-label">SRP (₱)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text msme-input-prefix">₱</span>
                                            </div>
                                            <input type="number" step="0.01" min="0" id="updateCommoditySrp"
                                                   class="form-control msme-input" placeholder="0.00" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="msme-label">Prevailing Price (₱)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text msme-input-prefix">₱</span>
                                            </div>
                                            <input type="number" step="0.01" min="0" id="updateCommodityPrevailingPrice"
                                                   class="form-control msme-input" placeholder="0.00" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label class="msme-label">Establishments</label>
                                <select id="updateCommodityEstablishments" class="form-control msme-input" multiple>
                                    <option value="">-- Select Establishment --</option>
                                </select>
                                <small class="form-text text-muted">
                                    <i class="material-icons" style="font-size:13px;vertical-align:middle;">info</i>
                                    After saving, go to the <strong>Establishment Prices</strong> tab to set individual prices.
                                </small>
                            </div>
                        </div>

                        <div class="modal-footer msme-modal-footer">
                            <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-raised-primary d-flex align-items-center"
                                    onclick="updateCommodity()">
                                <i class="material-icons mr-1" style="font-size:18px;">save</i>Save Info
                            </button>
                        </div>
                    </div>

                    <!-- ── Tab 2: Establishment Prices ── -->
                    <div class="tab-pane fade" id="tabEstPrices" role="tabpanel">
                        <div class="modal-body p-0">

                            <!-- Empty state -->
                            <div id="estPriceEmpty" class="text-center text-muted py-5" style="display:none;">
                                <i class="material-icons" style="font-size:40px;color:#dee2e6;">store_mall_directory</i>
                                <p class="mt-2 mb-0">No establishments linked to this commodity yet.</p>
                                <small>Go to <strong>Commodity Info</strong> tab and select establishments first.</small>
                            </div>

                            <!-- Prices table — no Action column, single Save All at footer -->
                            <div id="estPriceTableWrap">
                                <table class="table mb-0" id="tblEstPrices" style="font-size:0.88rem;">
                                    <thead>
                                        <tr style="background:#f8f9fa;">
                                            <th class="pl-3" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;border-top:none;width:40%;">Establishment</th>
                                            <th style="font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;border-top:none;width:30%;">SRP (₱)</th>
                                            <th style="font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;border-top:none;width:30%;">Prevailing Price (₱)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="estPriceTableBody"></tbody>
                                </table>
                            </div>

                        </div>

                        <div class="modal-footer msme-modal-footer justify-content-between">
                            <small class="text-muted">
                                <i class="material-icons" style="font-size:13px;vertical-align:middle;">info</i>
                                Fill in prices then click <strong>Save All Prices</strong>.
                            </small>
                            <div>
                                <button type="button" class="btn btn-text-secondary mr-1" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-raised-primary d-inline-flex align-items-center"
                                        id="btnSaveAllPrices" onclick="saveAllEstPrices()">
                                    <i class="material-icons mr-1" style="font-size:18px;">save</i>Save All Prices
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>

<style>
/* ── Shared tab styles (Add + Edit modals) ── */
#addCommodityTabs .nav-link,
#editCommodityTabs .nav-link {
    font-size: 0.82rem;
    font-weight: 500;
    color: #6c757d;
    border: none;
    border-bottom: 2px solid transparent;
    border-radius: 0;
    padding: 8px 14px;
    transition: color .15s, border-color .15s;
}
#addCommodityTabs .nav-link:hover,
#editCommodityTabs .nav-link:hover {
    color: #007bff;
    border-bottom-color: #b3d1ff;
}
#addCommodityTabs .nav-link.active,
#editCommodityTabs .nav-link.active {
    color: #007bff;
    font-weight: 600;
    border-bottom: 2px solid #007bff;
    background: transparent;
}

/* ── Shared establishment price table styles ── */
#tblEstPrices tbody tr,
#tblAddEstPrices tbody tr {
    transition: background .1s;
}
#tblEstPrices tbody tr:hover,
#tblAddEstPrices tbody tr:hover {
    background: rgba(0,123,255,.03);
}
#tblEstPrices td,
#tblAddEstPrices td {
    vertical-align: middle;
    padding: 10px 8px;
}
#tblEstPrices td:first-child,
#tblAddEstPrices td:first-child { padding-left: 16px; }

.est-price-input {
    height: 34px;
    font-size: 0.85rem;
    border-radius: 6px !important;
    border: 1px solid #dee2e6;
    padding: 4px 10px;
    width: 100%;
    transition: border-color .15s, box-shadow .15s;
}
.est-price-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,.15);
    outline: none;
}
/* Saving / saved row states */
.est-row-saving td { opacity: .6; pointer-events: none; }
.est-row-saved td  { background: rgba(40,167,69,.06) !important; }
</style>

<!-- Scripts -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/select2/js/select2.full.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>
<script src="../../scripts/common/alert.js"></script>
<script src="../../scripts/price-monitoring/commodity-table.js"></script>
<script src="../../scripts/price-monitoring/commodity-update.js"></script>

</body>
</html>