<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>San Carlos City | Categories</title>

    <!-- Google Font: Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        #tblCategories.dataTable thead th {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #495057;
            font-size: .78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            text-align: center;
        }
        #tblCategories.dataTable tbody td {
            text-align: center;
            vertical-align: middle;
            font-size: .9rem;
        }
        #tblCategories.dataTable tbody tr:hover > td {
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
                            <h5 class="mb-0 font-weight-bold">Categories</h5>
                            <small class="text-muted">Manage price monitoring categories by agency</small>
                        </div>
                        <button type="button" class="btn btn-raised-primary btn-sm ml-auto"
                                id="btn_add_category"
                                data-toggle="modal" data-target="#addCategoryModal">
                            <i class="material-icons icon-sm leading-icon">add</i>Add Category
                        </button>
                    </div>
                </div>

                <!-- Table card -->
                <div class="card card-raised no-hover">
                    <div class="card-body px-3 pt-3 pb-0">
                        <table id="tblCategories" class="table table-striped table-bordered mb-0">
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

<?php include 'category-add.php'; ?>
<?php include 'category-update.php'; ?>

<!-- Scripts -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>
<script src="../../scripts/common/alert.js"></script>
<script src="../../scripts/price-monitoring/category-table.js"></script>
<script src="../../scripts/price-monitoring/category-add.js"></script>
<script src="../../scripts/price-monitoring/category-update.js"></script>

</body>
</html>
