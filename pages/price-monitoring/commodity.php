<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>San Carlos City | Negosyo Center</title>

    <link rel="stylesheet" href="../../dist/css/font.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../dist/css/user_defined.css">
    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">

    <style>
        #tblCommodity.dataTable thead th {
            background-color: #343a40;
            border-color: #4b545c;
            color: white;
            text-align: center;
        }

        #tblCommodity.dataTable tbody td {
            text-align: center;
            vertical-align: middle !important;
        }

        #tblCommodity .btn {
            margin: 2px;
        }

        .modal-header {
            background-color: #343a40;
            color: white;
        }

        .modal-header .close {
            color: white;
        }

        .modal-footer {
            justify-content: flex-end;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <div class="preloader flex-column justify-content-center align-items-center">
        <img src="../../dist/img/itcsologo.webp" alt="AdminLTELogo" height="60" width="60">
    </div>

    <nav class="main-header navbar navbar-expand navbar-dark navbar-dark">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <div class="collapse navbar-collapse justify-content-end text-sm" id="navbarSupportedContent">
            <ul class="navbar-nav navbar-sidebar justify-content-end">
                <li class="nav-item">
                    <a class="nav-link text-sm" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt text-white"></i>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link text-sm pt-0 pb-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <div class="image pt-0 pb-0">
                            <img src="../../dist/img/default.jfif" class="img-circle portrait-sidebar elevation-2" alt="User Image">
                        </div>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" style="background-color: #495057 !important;">
                        <div class="user-panel d-flex">
                            <div class="image">
                                <img src="../../dist/img/default.jfif" class="img-circle elevation-2" alt="User Image">
                            </div>
                            <div class="info">
                                <a href="#" class="d-block text-white text-sm">BEN GANAGANAG</a>
                            </div>
                        </div>

                        <hr class="mt-1 mb-1">

                        <a class="nav-link text-sm" style="padding-left: 13px;" onclick="logout()" role="button" href="#">
                            <i class="fas fa-sign-out-alt" style="background-color: rgb(16 16 16 / 42%); border-radius: 22px; padding: 9px !important;"></i>
                            &nbsp; Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <?php include '../../pages/sidebar/sidebar.php'; ?>

    <div id="body_wrapper" class="content-wrapper">
        <section class="content pt-3">
            <div class="container-fluid">

                <div class="card">
                    <div class="card-body text-center">
                        <h2 class="mt-3 mb-3 display-4">Commodities</h2>
                    </div>
                </div>

                <div class="text-center mb-3">
                    <button type="button" class="btn btn-primary btn-sm" id="btn_add_calamity" data-toggle="modal" data-target="#addCalamityModal">
                        <i class="fas fa-plus"></i> Add Commodity
                    </button>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tblCommodity" class="table table-striped table-bordered" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Commodity Name</th>
                                        <th>Category</th>
                                        <th>Brand Name</th>
                                        <th>Unit of Measure</th>
                                        <th>Price</th>
                                        <th>Agency Name</th>
                                        <th>Options</th>
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

    <div class="modal fade" id="addCalamityModal" tabindex="-1" role="dialog" aria-labelledby="commodityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="commodityModalLabel">Add Commodity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="product_name">Commodity Name</label>
                        <input type="text" id="product_name" name="product_name" class="form-control" placeholder="Enter commodity name" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select id="category_id" name="category_id" class="form-control">
                            <option value="">-- Select Category --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="brand_name">Brand</label>
                        <input type="text" id="brand_name" name="brand_name" class="form-control" placeholder="Enter brand name" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="unit_of_measure">Unit of Measure</label>
                        <input type="text" id="unit_of_measure" name="unit_of_measure" class="form-control" placeholder="Example: kg, pcs, liter" autocomplete="off">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btnSaveCommodity">Save</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="updateCommodityModal" tabindex="-1" role="dialog" aria-labelledby="updateCommodityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="updateCommodityModalLabel">Edit Commodity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="updateCommodityForm">
                    <div class="modal-body">
                        <input type="hidden" id="updateCommodityId">

                        <div class="form-group">
                            <label for="updateCommodityProductName">Commodity Name</label>
                            <input type="text" id="updateCommodityProductName" class="form-control" placeholder="Enter commodity name" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="updateCommodityCategory">Category</label>
                            <select id="updateCommodityCategory" class="form-control">
                                <option value="">-- Select Category --</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="updateCommodityBrand">Brand</label>
                            <input type="text" id="updateCommodityBrand" class="form-control" placeholder="Enter brand name" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="updateCommodityUnit">Unit of Measure</label>
                            <input type="text" id="updateCommodityUnit" class="form-control" placeholder="Example: kg, pcs, liter" autocomplete="off">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="updateCommodity()">Update Commodity</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <aside class="control-sidebar control-sidebar-dark">
        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>

    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline">All rights reserved</div>
        <strong>
            Copyright &copy; 2024 ITCSO.
            <a href="http://lguscc.gov.ph/">Local Government of San Carlos City</a>
        </strong>.
    </footer>

</div>

<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>
<script src="../../scripts/price-monitoring/commodity-table.js"></script>
<script src="../../scripts/price-monitoring/commodity-update.js"></script>

</body>
</html>
