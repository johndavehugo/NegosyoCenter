<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>San Carlos City | DA Price Monitoring Module</title>

    <!-- Fonts & Plugins -->
    <link rel="stylesheet" href="../../dist/css/font.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap4.css">
    
    <!-- FontAwesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../dist/css/user_defined.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">
    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">

    <style>
        .table td { vertical-align: middle !important; }
        .badge-overpriced { background-color: #dc3545; color: white; }
        .badge-compliant { background-color: #28a745; color: white; }
        
        /* Banner Styling */
        .page-header-banner {
            background: linear-gradient(135deg, #0056b3 0%, #003366 100%);
            color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header-logo {
            max-height: 75px;
            width: auto;
            object-fit: contain;
        }
    </style>
</head>

<body class="sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar sticky-top navbar-expand navbar-dark">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fa-solid fa-bars"></i></a>
                </li>
            </ul>
        </nav>

        <!-- Sidebar Include -->
        <?php include '../../pages/sidebar/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="body_wrapper" class="content-wrapper">
            <div class="content mt-3">
                <div class="container-fluid">

                    <!-- Page Title Banner -->
                    <div class="card page-header-banner mb-3 border-0">
                        <div class="card-body py-3">
                            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between text-center text-md-left">
                                <div class="d-flex align-items-center mb-2 mb-md-0">
                                    <img src="../../dist/img/splogo.png" alt="San Carlos Logo" class="header-logo mr-md-3 mb-2 mb-md-0">
                                    <div>
                                        <h2 class="m-0 font-weight-bold">DA Price Monitoring Module</h2>
                                        <p class="mb-0 text-white-50">Department of Agriculture • Agricultural Commodities Price Tracking</p>
                                    </div>
                                </div>
                                <div class="mt-2 mt-md-0 text-md-right">
                                    <span class="badge badge-light p-2 font-weight-normal">
                                        <i class="fa-solid fa-location-dot mr-1 text-primary"></i> City of San Carlos
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Info Cards (Quick Stats) -->
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fa-solid fa-boxes-stacked"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Agri Monitored Items</span>
                                    <span class="info-box-number" id="stat_total_items">0 Items</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fa-solid fa-circle-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Compliant Stalls / Stores</span>
                                    <span class="info-box-number" id="stat_compliant_stores">0 Stores</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Overpriced Alerts</span>
                                    <span class="info-box-number" id="stat_overpriced_alerts">0 Items</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fa-solid fa-building-flag"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Jurisdiction</span>
                                    <span class="info-box-number">DA - Agri Products</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Data Card -->
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title mt-1">
                                <i class="fa-solid fa-seedling mr-1"></i> Agricultural Commodity Prevailing Price Monitoring
                            </h3>
                            <div class="card-tools">

                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Category Filter Options -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="filter_category">Filter by DA Category:</label>
                                    <select id="filter_category" class="form-control select2">
                                        <option value="">All Agri Categories</option>
                                        <option value="Rice">Rice (Local & Imported)</option>
                                        <option value="Livestock & Poultry">Livestock & Poultry (Pork, Beef, Chicken)</option>
                                        <option value="Fish & Seafood">Fish & Seafood</option>
                                        <option value="Vegetables">Vegetables (Highland & Lowland)</option>
                                        <option value="Fruits">Fruits</option>
                                        <option value="Spices & Seasoning">Spices & Seasoning (Onion, Garlic, Ginger)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Data Table -->
                            <div class="table-responsive">
                                <table id="tblPriceMonitoring" class="table table-bordered table-striped datatable w-100">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Commodity / Item</th>
                                            <th>Agency</th>
                                            <th>SRP (₱)</th>
                                            <th>Prevailing Price (₱)</th>
                                            <th>Variance / Status</th>
                                            <th class="text-center" style="width: 120px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data loaded via DataTables AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Main Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">All rights reserved</div>
            <strong>Copyright &copy; 2026 <a href="http://lguscc.gov.ph/">Local Government of San Carlos City</a>.</strong>
        </footer>
    </div>

    <!-- ==================== MODALS ==================== -->

<!-- Add Price Entry Modal -->
<div class="modal fade" id="modalAddPrice" tabindex="-1" role="dialog" aria-labelledby="modalAddPriceLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalAddPriceLabel"><i class="fa-solid fa-circle-plus mr-1"></i> Add New DA Price Entry</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formAddPrice">
                <!-- Default strictly to DA -->
                <input type="hidden" name="agency" value="DA">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Agency Jurisdiction</label>
                            <input type="text" class="form-control" value="DA - Agricultural Commodities" readonly>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-control" required>
                                <option value="">-- Select Category --</option>
                                <option value="Rice">Rice</option>
                                <option value="Livestock & Poultry">Livestock & Poultry</option>
                                <option value="Fish & Seafood">Fish & Seafood</option>
                                <option value="Vegetables">Vegetables</option>
                                <option value="Fruits">Fruits</option>
                                <option value="Spices & Seasoning">Spices & Seasoning</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="col-md-8 form-group">
                            <label>Commodity / Item Description <span class="text-danger">*</span></label>
                            <input type="text" name="item_description" class="form-control" placeholder="e.g., Well-Milled Rice, Red Onion (Local), Pork Liempo" required>
                        </div>
                        <!-- ADDED: Unit Field -->
                        <div class="col-md-4 form-group">
                            <label>Unit Measurement <span class="text-danger">*</span></label>
                            <input type="text" name="unit" class="form-control" placeholder="e.g., kg, pc, pack, sack" required>
                        </div>
                        <
                        <div class="col-md-6 form-group">
                            <label>Official DA SRP (₱) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="srp_price" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Prevailing Monitored Price (₱) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="prevailing_price" class="form-control" placeholder="0.00" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk mr-1"></i> Save DA Entry</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Price Entry Modal -->
<div class="modal fade" id="modalEditPrice" tabindex="-1" role="dialog" aria-labelledby="modalEditPriceLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalEditPriceLabel"><i class="fa-solid fa-pen-to-square mr-1"></i> Update DA Price Entry</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditPrice">
                <input type="hidden" id="edit_entry_id" name="entry_id">
                <input type="hidden" id="edit_agency" name="agency" value="DA">

                <input type="hidden"
       id="edit_commodity_id"
       name="commodity_id">

<input type="hidden"
       id="edit_monitored_by_agency_id"
       name="monitored_by_agency_id"
       value="2">

<input type="hidden"
       id="edit_status"
       name="status">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Agency Jurisdiction</label>
                            <input type="text" class="form-control" value="DA - Agricultural Commodities" readonly>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Category</label>
                            <input type="text" id="edit_category" name="category" class="form-control" required>
                        </div>
                        <div class="col-md-8 form-group">
                            <label>Commodity / Item Description</label>
                            <input type="text" id="edit_item_description" name="item_description" class="form-control" required>
                        </div>
                        <!-- ADDED: Unit Field for Edit -->
                        <div class="col-md-4 form-group">
                            <label>Unit Measurement</label>
                            <input type="text" id="edit_unit" name="unit" class="form-control" placeholder="e.g., kg, pc, pack" required>
                        </div>
                        
                        <div class="col-md-6 form-group">
                            <label>DA SRP (₱)</label>
<input
    type="number"
    step="0.01"
    min="0"
    id="edit_srp_price"
    name="srp_price"
    class="form-control"
    required
>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Prevailing Monitored Price (₱)</label>
                            <input type="number" step="0.01" id="edit_prevailing_price" name="prevailing_price" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info"><i class="fa-solid fa-rotate mr-1"></i> Update Entry</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- REQUIRED SCRIPTS -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/select2/js/select2.full.min.js"></script>
    <script src="../../dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>
    <script src="../../scripts/price-monitoring/da-table.js?v=20260808"></script>
<script src="../../scripts/price-monitoring/da-actions.js"></script>

<script src="../../scripts/price-monitoring/da-forms.js"></script>

    