<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>San Carlos City | Price Monitoring</title>

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
        /* ── Nav tabs — mirrors economic-map style ── */
        .pm-tabs .nav-link {
            font-size: .85rem;
            font-weight: 600;
            color: #495057;
            border-radius: 6px 6px 0 0;
            padding: .65rem 1.15rem;
            border: 1px solid transparent;
            transition: color .15s ease;
        }
        .pm-tabs .nav-link:hover { color: #028090; }
        .pm-tabs .nav-link.active {
            color: #028090;
            background: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }
        .pm-tabs .nav-link i {
            font-size: 17px;
            vertical-align: middle;
            margin-right: 4px;
        }

        /* ── Stat cards ── */
        .pm-stat-card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,.10), 0 1px 3px rgba(0,0,0,.07);
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .pm-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0,0,0,.13);
        }
        .pm-stat-icon {
            width: 52px; height: 52px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; color: #fff; flex-shrink: 0;
        }
        .pm-stat-value { font-size: 2rem; font-weight: 700; line-height: 1; color: #1a1a2e; }
        .pm-stat-label {
            font-size: .72rem; font-weight: 500; text-transform: uppercase;
            letter-spacing: .06em; color: #9ca3af; margin-top: 3px;
        }
        .pm-accent-total    { border-left: 4px solid #007bff !important; }
        .pm-accent-active   { border-left: 4px solid #28a745 !important; }
        .pm-accent-inactive { border-left: 4px solid #dc3545 !important; }
        .pm-accent-agency   { border-left: 4px solid #e67e22 !important; }
        .pm-icon-total      { background: #007bff; }
        .pm-icon-active     { background: #28a745; }
        .pm-icon-inactive   { background: #dc3545; }
        .pm-icon-agency     { background: #e67e22; }

        /* ── Filter label ── */
        .pm-filter-label {
            font-size: .72rem; font-weight: 500; text-transform: uppercase;
            letter-spacing: .05em; color: #9ca3af; margin-bottom: 4px;
        }

        /* ── Price monitoring table ── */
        #tblPriceMonitoring.dataTable thead th {
            background-color: #f8f9fa; border-color: #dee2e6; color: #495057;
            font-size: .78rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: .04em; text-align: center;
        }
        #tblPriceMonitoring.dataTable tbody td {
            text-align: center; vertical-align: middle; font-size: .9rem;
        }

        /* ── Agency table ── */
        #tblAgency.dataTable thead th,
        #tblCommodity.dataTable thead th,
        #tblCategories.dataTable thead th {
            background-color: #f8f9fa; border-color: #dee2e6; color: #495057;
            font-size: .78rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: .04em; text-align: center;
        }
        #tblAgency.dataTable tbody td,
        #tblCommodity.dataTable tbody td,
        #tblCategories.dataTable tbody td {
            text-align: center; vertical-align: middle; font-size: .9rem;
        }
        #tblAgency.dataTable tbody tr:hover > td,
        #tblCommodity.dataTable tbody tr:hover > td,
        #tblCategories.dataTable tbody tr:hover > td {
            background-color: rgba(0,123,255,.04) !important;
        }

        /* ── Commodity modal ── */
        .com-modal .select2-container--bootstrap4 .select2-selection--single {
            height: 38px; border: 1px solid #ced4da; border-radius: 4px;
        }
        .com-modal .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 36px; padding-left: 10px; color: #343a40;
        }
        .com-modal .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder { color: #6c757d; }
        .com-modal .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow { height: 36px; }

        /* ── Establishment table inside commodity modals ── */
        .com-est-table thead th {
            background-color: #f8f9fa; color: #495057; font-size: 0.75rem;
            font-weight: 600; text-transform: uppercase; letter-spacing: .04em;
            border-color: #dee2e6; white-space: nowrap;
        }
        .com-est-table tbody td { vertical-align: middle; font-size: 0.875rem; }
        .com-est-table .est-price-input { height: 32px; font-size: 0.85rem; padding: 4px 8px; }
        .com-est-table .btn-remove-est {
            color: #dc3545; background: none; border: none;
            padding: 2px 6px; cursor: pointer; font-size: 16px; line-height: 1;
        }
        .com-est-table .btn-remove-est:hover { color: #9b1c2a; }

        /* ── Est price table (commodity Tab 2) ── */
        #tblEstPrices tbody tr { transition: background .1s; }
        #tblEstPrices tbody tr:hover { background: rgba(0,123,255,.03); }
        #tblEstPrices td { vertical-align: middle; padding: 10px 8px; }
        #tblEstPrices td:first-child { padding-left: 16px; }
        .est-price-inp {
            height: 34px; font-size: 0.85rem; border-radius: 6px !important;
            border: 1px solid #dee2e6; padding: 4px 10px; width: 100%;
            transition: border-color .15s, box-shadow .15s;
        }
        .est-price-inp:focus { border-color: #007bff; box-shadow: 0 0 0 2px rgba(0,123,255,.15); outline: none; }
        .est-row-saved td { background: rgba(40,167,69,.06) !important; }

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
                    <div class="dropdown-menu dropdown-menu-right" style="background-color:#495057!important">
                        <div class="user-panel d-flex">
                            <div class="image">
                                <img src="../../dist/img/default.jfif" class="img-circle elevation-2" alt="User">
                            </div>
                            <div class="info">
                                <a href="#" class="d-block text-white text-sm">BEN GANAGANAG</a>
                            </div>
                        </div>
                        <hr class="mt-1 mb-1">
                        <a class="nav-link text-sm sidebar-franchise-user-panel" style="padding-left:13px;" role="button">
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

    <!-- Content -->
    <div class="content-wrapper">
        <div class="content pt-4 pb-4">
            <div class="container-fluid">

                <!-- Page header -->
                <div class="mb-3">
                    <p class="mb-0" style="font-weight:700;font-size:1.15rem;color:#1a1a2e;">
                        <i class="material-icons align-middle mr-1" style="font-size:22px;color:#007bff;vertical-align:middle;">local_offer</i>
                        Price Monitoring
                    </p>
                    <small style="color:#9ca3af;font-size:.8rem;">Price Monitoring System &mdash; San Carlos City Negosyo Center</small>
                </div>

                <!-- ── Nav Tabs ── -->
                <ul class="nav nav-tabs pm-tabs mb-0" id="pmTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-pm" data-toggle="tab" href="#pane-pm"
                           role="tab" aria-selected="true">
                            <i class="material-icons">local_offer</i>Price Monitoring
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-categories" data-toggle="tab" href="#pane-categories"
                           role="tab" aria-selected="false">
                            <i class="material-icons">category</i>Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-commodities" data-toggle="tab" href="#pane-commodities"
                           role="tab" aria-selected="false">
                            <i class="material-icons">inventory_2</i>Commodities
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-agencies" data-toggle="tab" href="#pane-agencies"
                           role="tab" aria-selected="false">
                            <i class="material-icons">apartment</i>Agencies
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="pmTabContent" style="border:1px solid #dee2e6;border-top:none;border-radius:0 0 6px 6px;background:#fff;">

                    <!-- ══════════════════════════════════════════════════
                         TAB 1 — PRICE MONITORING
                    ══════════════════════════════════════════════════ -->
                    <div class="tab-pane fade show active p-4" id="pane-pm" role="tabpanel">

                        <!-- Stat cards -->
                        <div class="row mb-3">
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="card pm-stat-card pm-accent-total h-100">
                                    <div class="card-body d-flex align-items-center" style="gap:16px;">
                                        <div class="pm-stat-icon pm-icon-total"><i class="material-icons">inventory_2</i></div>
                                        <div>
                                            <div class="pm-stat-value" id="total_monitored">0</div>
                                            <div class="pm-stat-label">Monitored Items</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="card pm-stat-card pm-accent-active h-100">
                                    <div class="card-body d-flex align-items-center" style="gap:16px;">
                                        <div class="pm-stat-icon pm-icon-active"><i class="material-icons">check_circle</i></div>
                                        <div>
                                            <div class="pm-stat-value" id="total_active">0</div>
                                            <div class="pm-stat-label">Active Items</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="card pm-stat-card pm-accent-inactive h-100">
                                    <div class="card-body d-flex align-items-center" style="gap:16px;">
                                        <div class="pm-stat-icon pm-icon-inactive"><i class="material-icons">cancel</i></div>
                                        <div>
                                            <div class="pm-stat-value" id="total_inactive">0</div>
                                            <div class="pm-stat-label">Inactive Items</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="card pm-stat-card pm-accent-agency h-100">
                                    <div class="card-body d-flex align-items-center" style="gap:16px;">
                                        <div class="pm-stat-icon pm-icon-agency"><i class="material-icons">domain</i></div>
                                        <div>
                                            <div class="pm-stat-value" id="selected_agency_name" style="font-size:1.2rem;">—</div>
                                            <div class="pm-stat-label">Selected Agency</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filter bar + table -->
                        <div class="card card-raised no-hover">
                            <div class="card-body px-4 pt-3 pb-3">
                                <div class="row align-items-end mb-3">
                                    <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                                        <div class="pm-filter-label">Agency</div>
                                        <select id="price_agency" class="form-control msme-input">
                                            <option value="">Loading agencies…</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                                        <div class="pm-filter-label">Category</div>
                                        <select id="filter_category" class="form-control msme-input">
                                            <option value="">All Categories</option>
                                        </select>
                                    </div>
                                </div>
                                <table id="tblPriceMonitoring" class="table table-striped table-bordered mb-0 w-100">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Brand / Unit</th>
                                            <th>Establishments</th>
                                            <th>Agency</th>
                                            <th>SRP (₱)</th>
                                            <th>Prevailing Price (₱)</th>
                                            <th>Status</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                    </div><!-- /pane-pm -->

                    <!-- ══════════════════════════════════════════════════
                         TAB 2 — CATEGORIES
                    ══════════════════════════════════════════════════ -->
                    <div class="tab-pane fade p-4" id="pane-categories" role="tabpanel">

                        <div class="card card-raised mb-0">
                            <div class="card-body d-flex align-items-center justify-content-between px-4 py-3">
                                <div>
                                    <h5 class="mb-0 font-weight-bold">Categories</h5>
                                    <small class="text-muted">Manage price monitoring categories by agency</small>
                                </div>
                                <button type="button" class="btn btn-raised-primary btn-sm ml-auto"
                                        id="btn_add_category" data-toggle="modal" data-target="#addCategoryModal">
                                    <i class="material-icons icon-sm leading-icon">add</i>Add Category
                                </button>
                            </div>
                        </div>

                        <div class="card card-raised no-hover mt-3">
                            <div class="card-body px-3 pt-3 pb-0">
                                <table id="tblCategories" class="table table-striped table-bordered mb-0" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Category Name</th>
                                            <th>Agency Name</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                    </div><!-- /pane-categories -->

                    <!-- ══════════════════════════════════════════════════
                         TAB 3 — COMMODITIES
                    ══════════════════════════════════════════════════ -->
                    <div class="tab-pane fade p-4" id="pane-commodities" role="tabpanel">

                        <div class="card card-raised mb-0">
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

                        <div class="card card-raised no-hover mt-3">
                            <div class="card-body px-3 pt-3 pb-0">
                                <table id="tblCommodity" class="table table-striped table-bordered mb-0" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Commodity Name</th>
                                            <th>Category</th>
                                            <th>Brand Name</th>
                                            <th>Unit of Measure</th>
                                            <th>Establishments</th>
                                            <th>Agency</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                    </div><!-- /pane-commodities -->

                    <!-- ══════════════════════════════════════════════════
                         TAB 4 — AGENCIES
                    ══════════════════════════════════════════════════ -->
                    <div class="tab-pane fade p-4" id="pane-agencies" role="tabpanel">

                        <div class="card card-raised mb-0">
                            <div class="card-body d-flex align-items-center justify-content-between px-4 py-3">
                                <div>
                                    <h5 class="mb-0 font-weight-bold">Agencies</h5>
                                    <small class="text-muted">Manage Philippine government agencies used across price monitoring</small>
                                </div>
                                <button type="button" class="btn btn-raised-primary btn-sm ml-auto"
                                        id="btn_add_agency" data-toggle="modal" data-target="#addAgencyModal">
                                    <i class="material-icons icon-sm leading-icon">add</i>Add Agency
                                </button>
                            </div>
                        </div>

                        <div class="card card-raised no-hover mt-3">
                            <div class="card-body px-3 pt-3 pb-0">
                                <table id="tblAgency" class="table table-striped table-bordered mb-0" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Code</th>
                                            <th>Agency Name</th>
                                            <th>Coverage</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                    </div><!-- /pane-agencies -->

                </div><!-- /tab-content -->

            </div>
        </div>
    </div><!-- /content-wrapper -->

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

<!-- ═══════════════════════════════════════════════════════════════════
     MODALS — shared across all tabs
═══════════════════════════════════════════════════════════════════ -->

<!-- ── Price (add / edit) modal ─────────────────────────────────────── -->
<div class="modal fade" id="priceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content msme-modal-content">
            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="material-icons text-primary mr-2" style="font-size:22px;">local_offer</i>
                    <span id="priceModalLabel">Set Price &amp; Status</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="priceForm">
                <div class="modal-body">
                    <input type="hidden" id="priceId">
                    <input type="hidden" id="priceCommodityId">
                    <div class="form-group">
                        <label class="msme-label">SRP (₱) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control msme-input" id="priceSrp" required>
                    </div>
                    <div class="form-group">
                        <label class="msme-label">Prevailing Price (₱) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control msme-input" id="pricePrevailingPrice" required>
                    </div>
                    <div class="form-group mb-0">
                        <label class="msme-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control msme-input" id="priceStatus" required>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer msme-modal-footer">
                    <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-raised-primary d-flex align-items-center" onclick="PM.savePrice()">
                        <i class="material-icons mr-1" style="font-size:18px;">save</i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ── Add Category modal ────────────────────────────────────────────── -->
<form id="addCategoryForm">
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content msme-modal-content">
                <div class="modal-header msme-modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="material-icons text-primary mr-2" style="font-size:22px;">category</i>
                        Add Category
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="msme-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control msme-input" id="addCategoryName" placeholder="Enter category name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="msme-label">Agency <span class="text-danger">*</span></label>
                                <select class="form-control msme-input" id="addAgencyType">
                                    <option value="" hidden>Select Agency</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer msme-modal-footer">
                    <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-raised-success d-flex align-items-center" id="btnSaveCategory" onclick="PM.addCategory()">
                        <i class="material-icons mr-1" style="font-size:18px;">save</i>Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- ── Edit Category modal ───────────────────────────────────────────── -->
<form id="updateCategoryForm">
    <div class="modal fade" id="updateCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content msme-modal-content">
                <div class="modal-header msme-modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="material-icons text-primary mr-2" style="font-size:22px;">edit</i>
                        Update Category
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="updateCategoryId">
                    <div class="form-group">
                        <label class="msme-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control msme-input" id="updateCategoryName" placeholder="Enter category name" required>
                    </div>
                    <div class="form-group mb-0">
                        <label class="msme-label">Agency <span class="text-danger">*</span></label>
                        <select class="form-control msme-input" id="updateCategoryAgency" required>
                            <option value="" hidden>Select Agency</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer msme-modal-footer">
                    <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-raised-primary d-flex align-items-center" onclick="PM.updateCategory()">
                        <i class="material-icons mr-1" style="font-size:18px;">save</i>Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- ── Add Agency modal ──────────────────────────────────────────────── -->
<div class="modal fade" id="addAgencyModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content msme-modal-content">
            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="material-icons text-primary mr-2" style="font-size:22px;">apartment</i>
                    Add Agency
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="msme-label">Agency Code <span class="text-danger">*</span></label>
                    <input type="text" id="agency_code" class="form-control msme-input" placeholder="e.g. DTI" maxlength="20" autocomplete="off">
                </div>
                <div class="form-group">
                    <label class="msme-label">Agency Name <span class="text-danger">*</span></label>
                    <input type="text" id="agency_name" class="form-control msme-input" placeholder="e.g. Department of Trade and Industry" maxlength="150" autocomplete="off">
                </div>
                <div class="form-group mb-0">
                    <label class="msme-label">Coverage</label>
                    <input type="text" id="agency_coverage" class="form-control msme-input" placeholder="e.g. Basic Necessities and Prime Commodities (BNPC)" maxlength="255" autocomplete="off">
                </div>
            </div>
            <div class="modal-footer msme-modal-footer">
                <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-raised-success d-flex align-items-center" id="btnSaveAgency" onclick="PM.saveAgency()">
                    <i class="material-icons mr-1" style="font-size:18px;">save</i>Save
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ── Edit Agency modal ─────────────────────────────────────────────── -->
<div class="modal fade" id="updateAgencyModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content msme-modal-content">
            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="material-icons text-primary mr-2" style="font-size:22px;">edit</i>
                    Edit Agency
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="updateAgencyForm">
                <div class="modal-body">
                    <input type="hidden" id="updateAgencyId">
                    <div class="form-group">
                        <label class="msme-label">Agency Code <span class="text-danger">*</span></label>
                        <input type="text" id="updateAgencyCode" class="form-control msme-input" placeholder="e.g. DTI" maxlength="20" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label class="msme-label">Agency Name <span class="text-danger">*</span></label>
                        <input type="text" id="updateAgencyName" class="form-control msme-input" placeholder="e.g. Department of Trade and Industry" maxlength="150" autocomplete="off">
                    </div>
                    <div class="form-group mb-0">
                        <label class="msme-label">Coverage</label>
                        <input type="text" id="updateAgencyCoverage" class="form-control msme-input" placeholder="e.g. Basic Necessities and Prime Commodities (BNPC)" maxlength="255" autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer msme-modal-footer">
                    <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-raised-primary d-flex align-items-center" onclick="PM.updateAgency()">
                        <i class="material-icons mr-1" style="font-size:18px;">save</i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ── Add Commodity modal ───────────────────────────────────────────── -->
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
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="msme-label">Commodity Name <span class="text-danger">*</span></label>
                            <input type="text" id="product_name" class="form-control msme-input" placeholder="Enter commodity name" autocomplete="off">
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
                            <input type="text" id="brand_name" class="form-control msme-input" placeholder="Brand name" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="msme-label">Unit <span class="text-danger">*</span></label>
                            <input type="text" id="unit_of_measure" class="form-control msme-input" placeholder="e.g. kg, pcs" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-2">
                            <label class="msme-label">Establishments <span class="text-muted" style="font-size:.72rem;text-transform:none;letter-spacing:0;">(search &amp; select to add)</span></label>
                            <select id="addEstSelect" style="width:100%"><option value=""></option></select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="msme-label">Selected Establishments
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
                <button type="button" class="btn btn-raised-success d-inline-flex align-items-center" id="btnSaveCommodity" onclick="PM.saveCommodity()">
                    <i class="material-icons mr-1" style="font-size:18px;">save</i>Save Commodity
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ── Edit Commodity modal ──────────────────────────────────────────── -->
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
            <form id="updateCommodityForm">
                <div class="modal-body">
                    <input type="hidden" id="updateCommodityId">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="msme-label">Commodity Name <span class="text-danger">*</span></label>
                                <input type="text" id="updateCommodityProductName" class="form-control msme-input" placeholder="Enter commodity name" autocomplete="off">
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
                                <input type="text" id="updateCommodityBrand" class="form-control msme-input" placeholder="Brand name" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="msme-label">Unit <span class="text-danger">*</span></label>
                                <input type="text" id="updateCommodityUnit" class="form-control msme-input" placeholder="e.g. kg, pcs" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="msme-label">Establishments <span class="text-muted" style="font-size:.72rem;text-transform:none;letter-spacing:0;">(search &amp; select to add)</span></label>
                        <select id="editEstSelect" style="width:100%"><option value=""></option></select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="msme-label">Selected Establishments
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
                    <button type="button" class="btn btn-raised-primary d-inline-flex align-items-center" onclick="PM.updateCommodity()">
                        <i class="material-icons mr-1" style="font-size:18px;">save</i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ── Scripts ─────────────────────────────────────────────────────────── -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/select2/js/select2.full.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>
<script src="../../scripts/common/alert.js"></script>
<script src="../../scripts/price-monitoring/price-monitoring-app.js?v=5"></script>

<script>
function logout() {
    if (confirm('Are you sure you want to logout?')) window.location.href = '../../index.php';
}
</script>

</body>
</html>
