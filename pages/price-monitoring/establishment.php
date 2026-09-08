<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>San Carlos City | Establishments</title>

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
        #tblEstablishment.dataTable thead th,
        #tblEstablishmentPrices.dataTable thead th {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #495057;
            font-size: .78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            text-align: center;
        }
        #tblEstablishment.dataTable tbody td,
        #tblEstablishmentPrices.dataTable tbody td {
            text-align: center;
            vertical-align: middle;
            font-size: .9rem;
        }
        #tblEstablishment.dataTable tbody tr:hover > td,
        #tblEstablishmentPrices.dataTable tbody tr:hover > td {
            background-color: rgba(0,123,255,.04) !important;
        }
        .table-success td {
            font-weight: 600;
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
                            <h5 class="mb-0 font-weight-bold">Establishments</h5>
                            <small class="text-muted">Manage stores (Gaisano, Puregold, NCCC, etc.) used for price comparison</small>
                        </div>
                        <button type="button" class="btn btn-raised-primary btn-sm ml-auto"
                                id="btn_add_establishment"
                                data-toggle="modal" data-target="#addEstablishmentModal">
                            <i class="material-icons icon-sm leading-icon">add</i>Add Establishment
                        </button>
                    </div>
                </div>

                <!-- ===================== SECTION 1: MANAGE ESTABLISHMENTS ===================== -->
                <div class="card card-raised no-hover mb-3">
                    <div class="card-body px-3 pt-3 pb-0">
                        <table id="tblEstablishment" class="table table-striped table-bordered mb-0"
                               style="width:100%;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Branch</th>
                                    <th>Address</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- ===================== SECTION 2: BROWSE & COMPARE PRICES ===================== -->
                <div class="card card-raised no-hover mb-3">
                    <div class="card-body px-4 py-3">
                        <h5 class="mb-0 font-weight-bold">Browse Products by Establishment</h5>
                        <small class="text-muted d-block mb-3">Select a store to view and set its product prices, or compare a product across all stores.</small>

                        <div class="form-group" style="max-width: 400px;">
                            <label class="msme-label">Select Establishment</label>
                            <select id="filter_establishment" class="form-control"></select>
                        </div>
                    </div>
                    <div class="card-body px-3 pt-0 pb-0">
                        <table id="tblEstablishmentPrices" class="table table-striped table-bordered mb-0"
                               style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Brand / Net Weight</th>
                                    <th>Price (P)</th>
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

<!-- ── Add Establishment modal ─────────────────────────────────────── -->
<div class="modal fade" id="addEstablishmentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content msme-modal-content">

            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="material-icons text-primary mr-2" style="font-size:22px;">store</i>
                    Add Establishment
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label class="msme-label">Name <span class="text-danger">*</span></label>
                    <input type="text" id="establishment_name" class="form-control msme-input"
                           placeholder="e.g. Gaisano Fiestamart" autocomplete="off" maxlength="150">
                </div>
                <div class="form-group">
                    <label class="msme-label">Branch</label>
                    <input type="text" id="establishment_branch" class="form-control msme-input"
                           placeholder="e.g. San Carlos City" autocomplete="off" maxlength="150">
                </div>
                <div class="form-group mb-0">
                    <label class="msme-label">Address</label>
                    <input type="text" id="establishment_address" class="form-control msme-input"
                           placeholder="Full address" autocomplete="off" maxlength="255">
                </div>
            </div>

            <div class="modal-footer msme-modal-footer">
                <button type="button" class="btn btn-text-secondary"
                        data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-raised-success d-flex align-items-center"
                        id="btnSaveEstablishment">
                    <i class="material-icons mr-1" style="font-size:18px;">save</i>Save
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ── Edit Establishment modal ────────────────────────────────────── -->
<div class="modal fade" id="updateEstablishmentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content msme-modal-content">

            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="material-icons text-primary mr-2" style="font-size:22px;">edit</i>
                    Edit Establishment
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form id="updateEstablishmentForm">
                <div class="modal-body">
                    <input type="hidden" id="updateEstablishmentId">

                    <div class="form-group">
                        <label class="msme-label">Name <span class="text-danger">*</span></label>
                        <input type="text" id="updateEstablishmentName"
                               class="form-control msme-input" autocomplete="off" maxlength="150">
                    </div>
                    <div class="form-group">
                        <label class="msme-label">Branch</label>
                        <input type="text" id="updateEstablishmentBranch"
                               class="form-control msme-input" autocomplete="off" maxlength="150">
                    </div>
                    <div class="form-group mb-0">
                        <label class="msme-label">Address</label>
                        <input type="text" id="updateEstablishmentAddress"
                               class="form-control msme-input" autocomplete="off" maxlength="255">
                    </div>
                </div>

                <div class="modal-footer msme-modal-footer">
                    <button type="button" class="btn btn-text-secondary"
                            data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-raised-primary d-flex align-items-center"
                            onclick="updateEstablishment()">
                        <i class="material-icons mr-1" style="font-size:18px;">save</i>Update
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ── Set / Edit Price modal ───────────────────────────────────────── -->
<div class="modal fade" id="editPriceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content msme-modal-content">

            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center" id="editPriceModalLabel">
                    <i class="material-icons text-primary mr-2" style="font-size:22px;">sell</i>
                    Set / Edit Price
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="priceCommodityId">
                <input type="hidden" id="priceEstablishmentId">

                <div class="form-group">
                    <label class="msme-label">Price (P) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" id="priceValue" class="form-control msme-input">
                </div>
                <div class="form-group mb-0">
                    <label class="msme-label">Status</label>
                    <select id="priceStatusValue" class="form-control msme-input">
                        <option value="ACTIVE">ACTIVE</option>
                        <option value="INACTIVE">INACTIVE</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer msme-modal-footer">
                <button type="button" class="btn btn-text-secondary"
                        data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-raised-success d-flex align-items-center"
                        onclick="saveEstablishmentPrice()">
                    <i class="material-icons mr-1" style="font-size:18px;">save</i>Save
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ── Compare Prices modal ─────────────────────────────────────────── -->
<div class="modal fade" id="comparePriceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content msme-modal-content">

            <div class="modal-header msme-modal-header">
                <h5 class="modal-title d-flex align-items-center" id="comparePriceModalLabel">
                    <i class="material-icons text-primary mr-2" style="font-size:22px;">balance</i>
                    Compare Prices
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered mb-0" id="tblCompare">
                    <thead>
                        <tr>
                            <th>Establishment</th>
                            <th>Branch</th>
                            <th>Price (P)</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="modal-footer msme-modal-footer">
                <button type="button" class="btn btn-text-secondary"
                        data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<!-- Scripts -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="../../plugins/select2/js/select2.full.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>
<script src="../../scripts/common/alert.js"></script>
<script src="../../scripts/price-monitoring/establishment-table.js"></script>
<script src="../../scripts/price-monitoring/establishment-add.js"></script>
<script src="../../scripts/price-monitoring/establishment-update.js"></script>
<script src="../../scripts/price-monitoring/establishment-prices.js"></script>

</body>
</html>