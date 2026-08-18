<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>San Carlos City | Price Monitoring</title>

    <link rel="stylesheet" href="../../dist/css/font.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../dist/css/user_defined.css">
    <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">

    <style>
        .price-header {
            background: linear-gradient(135deg, #07549b, #0d5da8);
            color: white;
            border-radius: 10px;
            padding: 25px 30px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .price-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .price-header-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .price-header-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .price-header h1 {
            font-size: 32px;
            font-weight: 800;
            margin: 0;
        }

        .price-header p {
            margin: 4px 0 0;
            color: #dbeafe;
            font-size: 15px;
        }

        .price-header-badge {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            height: 100%;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            color: white;
        }

        .stat-icon.icon-cyan { background: #20a5ba; }
        .stat-icon.icon-green { background: #28a745; }
        .stat-icon.icon-red { background: #dc3545; }
        .stat-icon.icon-yellow { background: #ffc107; color: #212529; }

        .stat-label {
            font-size: 13px;
            color: #6c757d;
            margin: 0;
        }

        .stat-value {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            color: #212529;
        }

        #tblPriceMonitoring thead th {
            background: #343a40;
            color: white;
            text-align: center;
            vertical-align: middle;
        }

        #tblPriceMonitoring tbody td {
            vertical-align: middle;
        }

        .monitoring-card {
            border-top: 3px solid #007bff;
        }

        .status-badge {
            font-size: 11px;
            font-weight: bold;
        }
    </style>
</head>

<body class="sidebar-mini layout-fixed">

<div class="wrapper">

    
    <nav class="main-header navbar navbar-expand navbar-dark sticky-top">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
    </nav>

    
    <?php include '../../pages/sidebar/sidebar.php'; ?>

    <!-- Content -->
    <div class="content-wrapper">

        <section class="content pt-3">
            <div class="container-fluid">

                <!-- Header -->
                <div class="price-header">
                    <div class="price-header-left">
                        <div class="price-header-icon">
                            <img src="../../dist/img/logosan.jpg" alt="City Seal">
                        </div>
                        <div>
                            <h1 id="agency_title">DOE Price Monitoring</h1>
                            <p id="agency_subtitle">DOE Price Monitoring System</p>
                        </div>
                    </div>

                    <div class="price-header-badge">
                        <i class="fas fa-map-marker-alt"></i>
                        City of San Carlos
                    </div>
                </div>

                
                <div class="row">

                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon icon-cyan">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div>
                                <p class="stat-label">Monitored Items</p>
                                <p class="stat-value" id="total_monitored">0</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon icon-green">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <p class="stat-label">Active Items</p>
                                <p class="stat-value" id="total_active">0</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon icon-red">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div>
                                <p class="stat-label">Inactive Items</p>
                                <p class="stat-value" id="total_inactive">0</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon icon-yellow">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <p class="stat-label">Selected Agency</p>
                                <p class="stat-value" id="selected_agency_name">DTI</p>
                            </div>
                        </div>
                    </div>

                </div>

                
                <div class="card monitoring-card">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tags mr-1"></i>
                            Price Monitoring Records
                        </h3>
                    </div>

                    <div class="card-body">

                        
                        <div class="row mb-3">

                            <div class="col-md-4">
                                <label for="price_agency">
                                    Select Agency
                                </label>

                                <select id="price_agency"
                                        class="form-control">
                                    <option value="1">DTI</option>
                                    <option value="2">DA</option>
                                    <option value="3" selected>DOE</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="filter_category">
                                    Filter by Category
                                </label>

                                <select id="filter_category"
                                        class="form-control">
                                    <option value="">All Categories</option>
                                </select>
                            </div>

                        </div>

                        
                        <div class="table-responsive">

                            <table id="tblPriceMonitoring"
                                   class="table table-bordered table-striped w-100">

                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Brand / Unit</th>
                                        <th>Agency</th>
                                        <th>SRP (₱)</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody></tbody>

                            </table>

                        </div>

                    </div>
                </div>

            </div>
        </section>

    </div>

    
    <footer class="main-footer">

        <div class="float-right d-none d-sm-inline">
            All rights reserved
        </div>

        <strong>
            Copyright &copy; 2026
            <a href="http://lguscc.gov.ph/">
                Local Government of San Carlos City
            </a>.
        </strong>

    </footer>

</div>


<div class="modal fade" id="priceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="priceModalLabel">Edit Product Price & Status</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form id="priceForm">
                <div class="modal-body">
                    <input type="hidden" id="priceId">
                    <input type="hidden" id="priceCommodityId">

                    <div class="form-group">
                        <label for="priceSrp">SRP (₱)</label>
                        <input type="number" step="0.01" class="form-control" id="priceSrp" required>
                    </div>

                    <div class="form-group">
                        <label for="priceStatus">Status</label>
                        <select class="form-control" id="priceStatus" required>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="savePrice()">Save</button>
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


<script src="../../scripts/price-monitoring/price-monitoring.js"></script>

</body>
</html>