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
    <!-- AdminLTE + shared styles -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../dist/css/user_defined.css?v=5">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">
    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">

    <style>
        /* ── Main table ── */
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

        /* ── Select2 inside commodity modals ── */
        .com-modal .select2-container--bootstrap4 .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        .com-modal .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
            padding-left: 10px;
            color: #343a40;
        }
        .com-modal .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder {
            color: #6c757d;
        }
        .com-modal .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        /* ── Establishment table inside modals ── */
        .com-est-table thead th {
            background-color: #f8f9fa;
            color: #495057;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            border-color: #dee2e6;
            white-space: nowrap;
        }
        .com-est-table tbody td {
            vertical-align: middle;
            font-size: 0.875rem;
        }
        .com-est-table .est-price-input {
            height: 32px;
            font-size: 0.85rem;
            padding: 4px 8px;
        }
        .com-est-table .btn-remove-est {
            color: #dc3545;
            background: none;
            border: none;
            padding: 2px 6px;
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
        }
        .com-est-table .btn-remove-est:hover { color: #9b1c2a; }

        /* ── Tab styles ── */
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
        #editCommodityTabs .nav-link:hover  { color: #007bff; border-bottom-color: #b3d1ff; }
        #editCommodityTabs .nav-link.active { color: #007bff; font-weight: 600; border-bottom: 2px solid #007bff; background: transparent; }

        /* ── Price table (Edit Tab 2) ── */
        #tblEstPrices tbody tr { transition: background .1s; }
        #tblEstPrices tbody tr:hover { background: rgba(0,123,255,.03); }
        #tblEstPrices td { vertical-align: middle; padding: 10px 8px; }
        #tblEstPrices td:first-child { padding-left: 16px; }
        .est-price-inp {
            height: 34px; font-size: 0.85rem;
            border-radius: 6px !important; border: 1px solid #dee2e6;
            padding: 4px 10px; width: 100%;
            transition: border-color .15s, box-shadow .15s;
        }
        .est-price-inp:focus { border-color: #007bff; box-shadow: 0 0 0 2px rgba(0,123,255,.15); outline: none; }
        .est-row-saved td  { background: rgba(40,167,69,.06) !important; }
    </style>
</head>

<body class="sidebar-mini layout-fixed" style="height:auto;">
<div class="wrapper">

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
                    <a class="nav-link text-sm pt-0 pb-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                        <div class="image pt-0 pb-0">
                            <img src="../../dist/img/default.jfif" class="img-circle portrait-sidebar elevation-2" alt="User">
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" style="background-color:#495057!important">
                        <div class="user-panel d-flex">
                            <div class="image"><img src="../../dist/img/default.jfif" class="img-circle elevation-2" alt="User"></div>
                            <div class="info"><a href="#" class="d-block text-white text-sm">BEN GANAGANAG</a></div>
                        </div>
                        <hr class="mt-1 mb-1">
                        <a class="nav-link text-sm" style="padding-left:13px;" role="button">
                            <i class="material-icons" style="font-size:18px;vertical-align:middle;background:rgba(16,16,16,.42);border-radius:22px;padding:6px;">manage_accounts</i>&nbsp;Edit Profile
                        </a>
                        <a class="nav-link text-sm" style="padding-left:13px;" onclick="logout()" role="button">
                            <i class="material-icons" style="font-size:18px;vertical-align:middle;background:rgba(16,16,16,.42);border-radius:22px;padding:6px;">logout</i>&nbsp;Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <?php include '../../pages/sidebar/sidebar.php'; ?>

    <div id="body_wrapper" class="content-wrapper">
        <div class="content pt-4 pb-2">
            <div class="container-fluid">

                <div class="card card-raised mb-3">
                    <div class="card-body d-flex align-items-center justify-content-between px-4 py-3">
                        <div>
                            <h5 class="mb-0 font-weight-bold">Commodities</h5>
                            <small class="text-muted">Manage monitored commodities and their pricing</small>
                        </div>
                        <button type="button" class="btn btn-raised-primary btn-sm ml-auto"
                                id="btn_add_commodity" data-toggle="modal" data-target="#addCommodityModal">
                            <i class="material-icons icon-sm leading-icon">add</i>Add Commodity
                        </button>
                    </div>
                </div>

                <div class="card card-raised no-hover">
                    <div class="card-body px-3 pt-3 pb-0">
                        <table id="tblCommodity" class="table table-striped table-bordered mb-0" style="width:100%;">
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

    <aside class="control-sidebar control-sidebar-dark">
        <div class="p-3"><h5>Title</h5><p>Sidebar content</p></div>
    </aside>

    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline">All rights reserved</div>
        <strong>Copyright &copy; <?= date('Y') ?> ITCSO.
            <a href="http://lguscc.gov.ph/">Local Government of San Carlos City</a>
        </strong>
    </footer>

</div>

<!-- ═══════════════════════════════════════════════════════════════════
     ADD COMMODITY MODAL
════════════════════════════════════════════════════════════════════ -->
<div class="modal fade com-modal" id="addCommodityModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content msme-modal-content">

            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center mb-0">
                    <i class="material-icons text-primary mr-2" style="font-size:22px;">inventory_2</i>
                    Add Commodity
                </h5>
                <button type="button" class="close ml-auto" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <!-- Row 1: basic info -->
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="msme-label">Commodity Name <span class="text-danger">*</span></label>
                            <input type="text" id="product_name" class="form-control msme-input"
                                   placeholder="Enter commodity name" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="msme-label">Category <span class="text-danger">*</span></label>
                            <select id="category_id" class="form-control msme-input">
                                <option value="">-- Select Category --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="msme-label">Brand</label>
                            <input type="text" id="brand_name" class="form-control msme-input"
                                   placeholder="Brand name" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="msme-label">Unit <span class="text-danger">*</span></label>
                            <input type="text" id="unit_of_measure" class="form-control msme-input"
                                   placeholder="e.g. kg, pcs" autocomplete="off">
                        </div>
                    </div>
                </div>

                <!-- Row 2: establishment selector -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-2">
                            <label class="msme-label">Establishments <span class="text-muted" style="font-size:.72rem;text-transform:none;letter-spacing:0;">(search & select to add)</span></label>
                            <select id="addEstSelect" style="width:100%">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Row 3: establishments table -->
                <div class="row">
                    <div class="col-md-12">
                        <label class="msme-label">
                            Selected Establishments
                            <span class="badge badge-primary ml-1" id="addEstTableCount" style="display:none;"></span>
                        </label>
                        <div class="table-responsive" style="border-radius:6px;overflow:hidden;">
                            <table class="table table-bordered com-est-table mb-0" id="addEstTable">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>Establishment Name</th>
                                        <th style="width:180px;">SRP (₱)</th>
                                        <th style="width:180px;">Prevailing Price (₱)</th>
                                        <th style="width:60px;text-align:center;">Remove</th>
                                    </tr>
                                </thead>
                                <tbody id="addEstTableBody"></tbody>
                            </table>
                        </div>
                        <small class="text-muted" id="addEstTableEmpty">No establishments added yet.</small>
                    </div>
                </div>

            </div>

            <div class="modal-footer msme-modal-footer">
                <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-raised-success d-inline-flex align-items-center"
                        id="btnSaveCommodity">
                    <i class="material-icons mr-1" style="font-size:18px;">save</i>Save Commodity
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════════
     EDIT COMMODITY MODAL
════════════════════════════════════════════════════════════════════ -->
<div class="modal fade com-modal" id="updateCommodityModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
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

                    <!-- Tab 1: Commodity Info -->
                    <div class="tab-pane fade show active" id="tabCommodityInfo" role="tabpanel">
                        <div class="modal-body">
                            <input type="hidden" id="updateCommodityId">

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="msme-label">Commodity Name <span class="text-danger">*</span></label>
                                        <input type="text" id="updateCommodityProductName"
                                               class="form-control msme-input" placeholder="Enter commodity name" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="msme-label">Category <span class="text-danger">*</span></label>
                                        <select id="updateCommodityCategory" class="form-control msme-input">
                                            <option value="">-- Select Category --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="msme-label">Brand</label>
                                        <input type="text" id="updateCommodityBrand"
                                               class="form-control msme-input" placeholder="Brand name" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="msme-label">Unit <span class="text-danger">*</span></label>
                                        <input type="text" id="updateCommodityUnit"
                                               class="form-control msme-input" placeholder="e.g. kg, pcs" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="msme-label">SRP (₱)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text msme-input-prefix">₱</span></div>
                                            <input type="number" step="0.01" min="0" id="updateCommoditySrp"
                                                   class="form-control msme-input" placeholder="0.00" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="msme-label">Prevailing Price (₱)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text msme-input-prefix">₱</span></div>
                                            <input type="number" step="0.01" min="0" id="updateCommodityPrevailingPrice"
                                                   class="form-control msme-input" placeholder="0.00" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Establishment selector -->
                            <div class="form-group mb-2">
                                <label class="msme-label">Establishments <span class="text-muted" style="font-size:.72rem;text-transform:none;letter-spacing:0;">(search & select to add)</span></label>
                                <select id="editEstSelect" style="width:100%">
                                    <option value=""></option>
                                </select>
                            </div>

                            <!-- Establishments table -->
                            <div class="form-group mb-0">
                                <label class="msme-label">
                                    Selected Establishments
                                    <span class="badge badge-primary ml-1" id="editEstTableCount" style="display:none;"></span>
                                </label>
                                <div class="table-responsive" style="border-radius:6px;overflow:hidden;">
                                    <table class="table table-bordered com-est-table mb-0" id="editEstTable">
                                        <thead>
                                            <tr>
                                                <th style="width:40px;">#</th>
                                                <th>Establishment Name</th>
                                                <th style="width:180px;">SRP (₱)</th>
                                                <th style="width:180px;">Prevailing Price (₱)</th>
                                                <th style="width:60px;text-align:center;">Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody id="editEstTableBody"></tbody>
                                    </table>
                                </div>
                                <small class="text-muted" id="editEstTableEmpty">No establishments added yet.</small>
                            </div>

                        </div>
                        <div class="modal-footer msme-modal-footer">
                            <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-raised-primary d-inline-flex align-items-center"
                                    onclick="updateCommodity()">
                                <i class="material-icons mr-1" style="font-size:18px;">save</i>Save
                            </button>
                        </div>
                    </div>

                    <!-- Tab 2: Establishment Prices (read-only view with editable prices) -->
                    <div class="tab-pane fade" id="tabEstPrices" role="tabpanel">
                        <div class="modal-body p-0">

                            <div id="estPriceEmpty" class="text-center text-muted py-5" style="display:none;">
                                <i class="material-icons" style="font-size:40px;color:#dee2e6;">store_mall_directory</i>
                                <p class="mt-2 mb-0">No establishments linked yet.</p>
                                <small>Go to <strong>Commodity Info</strong> tab and add establishments first.</small>
                            </div>

                            <div id="estPriceTableWrap">
                                <table class="table mb-0" id="tblEstPrices" style="font-size:.88rem;">
                                    <thead>
                                        <tr style="background:#f8f9fa;">
                                            <th class="pl-3" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;border-top:none;width:40%;">Establishment</th>
                                            <th style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;border-top:none;width:30%;">SRP (₱)</th>
                                            <th style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;border-top:none;width:30%;">Prevailing Price (₱)</th>
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

<script>
function logout() {
    if (confirm('Are you sure you want to logout?')) window.location.href = '../../index.php';
}
</script>

</body>
</html>
