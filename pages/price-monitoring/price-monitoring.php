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
        /* ── Stat cards — mirror MSME dashboard accent pattern ── */
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
        .pm-stat-value {
            font-size: 2rem; font-weight: 700; line-height: 1; color: #1a1a2e;
        }
        .pm-stat-label {
            font-size: .72rem; font-weight: 500; text-transform: uppercase;
            letter-spacing: .06em; color: #9ca3af; margin-top: 3px;
        }
        .pm-accent-total  { border-left: 4px solid #007bff !important; }
        .pm-accent-active { border-left: 4px solid #28a745 !important; }
        .pm-accent-inactive { border-left: 4px solid #dc3545 !important; }
        .pm-accent-agency { border-left: 4px solid #e67e22 !important; }

        .pm-icon-total   { background: #007bff; }
        .pm-icon-active  { background: #28a745; }
        .pm-icon-inactive{ background: #dc3545; }
        .pm-icon-agency  { background: #e67e22; }

        /* ── Filter label ── */
        .pm-filter-label {
            font-size: .72rem; font-weight: 500; text-transform: uppercase;
            letter-spacing: .05em; color: #9ca3af; margin-bottom: 4px;
        }

        /* ── Table header ── */
        #tblPriceMonitoring.dataTable thead th {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #495057;
            font-size: .78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            text-align: center;
        }
        #tblPriceMonitoring.dataTable tbody td {
            text-align: center;
            vertical-align: middle;
            font-size: .9rem;
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
    <div class="content-wrapper">
        <div class="content pt-4 pb-4">
            <div class="container-fluid">

                <!-- Page header card -->
                <div class="card card-raised mb-3">
                    <div class="card-body d-flex align-items-center justify-content-between px-4 py-3">
                        <div class="d-flex align-items-center" style="gap:14px;">
                            <img src="../../dist/img/logosan.jpg" alt="City Seal"
                                 style="width:48px;height:48px;border-radius:50%;object-fit:cover;flex-shrink:0;box-shadow:0 2px 6px rgba(0,0,0,.15);">
                            <div>
                                <h5 class="mb-0 font-weight-bold" id="agency_title">Price Monitoring</h5>
                                <small class="text-muted" id="agency_subtitle">Price Monitoring System — San Carlos City</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stat cards -->
                <div class="row mb-3">
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card pm-stat-card pm-accent-total h-100">
                            <div class="card-body d-flex align-items-center" style="gap:16px;">
                                <div class="pm-stat-icon pm-icon-total">
                                    <i class="material-icons">inventory_2</i>
                                </div>
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
                                <div class="pm-stat-icon pm-icon-active">
                                    <i class="material-icons">check_circle</i>
                                </div>
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
                                <div class="pm-stat-icon pm-icon-inactive">
                                    <i class="material-icons">cancel</i>
                                </div>
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
                                <div class="pm-stat-icon pm-icon-agency">
                                    <i class="material-icons">domain</i>
                                </div>
                                <div>
                                    <div class="pm-stat-value" id="selected_agency_name" style="font-size:1.2rem;">DOE</div>
                                    <div class="pm-stat-label">Selected Agency</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter + table card -->
                <div class="card card-raised no-hover">
                    <div class="card-body px-4 pt-3 pb-3">

                        <!-- Filter bar -->
                        <div class="row align-items-end mb-3">
                            <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                                <div class="pm-filter-label">Agency</div>
                                <select id="price_agency" class="form-control msme-input">
                                    <option value="1">DTI</option>
                                    <option value="2">DA</option>
                                    <option value="3" selected>DOE</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                                <div class="pm-filter-label">Category</div>
                                <select id="filter_category" class="form-control msme-input">
                                    <option value="">All Categories</option>
                                </select>
                            </div>
                        </div>

                        <!-- Table -->
                        <table id="tblPriceMonitoring" class="table table-striped table-bordered mb-0 w-100">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Brand / Unit</th>
                                    <th>Agency</th>
                                    <th>SRP (₱)</th>
                                    <th>Status</th>
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

<!-- Price modal -->
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
                        <input type="number" step="0.01" min="0"
                               class="form-control msme-input" id="priceSrp" required>
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
                    <button type="button" class="btn btn-text-secondary"
                            data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-raised-primary d-flex align-items-center"
                            onclick="savePrice()">
                        <i class="material-icons mr-1" style="font-size:18px;">save</i>Save
                    </button>
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
<script src="../../scripts/price-monitoring/price-monitoring.js"></script>

</body>
</html>
