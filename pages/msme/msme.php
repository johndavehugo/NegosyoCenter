<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->

<html lang="en">


<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>San Carlos City | Negosyo Center</title>

    <!-- Google Font: Roboto (Material) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="../../plugins/bs-stepper/css/bs-stepper.min.css">


    <!-- DataTables -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.css">


    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../dist/css/user_defined.css?v=5">
    <link rel="stylesheet" href="../../plugins/dropzone/min/dropzone.min.css" type="text/css" />
    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../../plugins/ekko-lightbox/ekko-lightbox.css">


    <!-- new -->
    <link rel="stylesheet" href="https://unpkg.com/intro.js/minified/introjs.min.css">


    <!-- Toastr -->
    <style>
        /* page-level overrides only — shared styles live in user_defined.css */

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 9999;
        }
        .overlay-content {
            position: absolute;
            top: 50%;
            left: 60%;
            transform: translate(-50%, -50%);
        }
        .imageSpinner {
            filter: invert(1);
            mix-blend-mode: multiply;
            width: 30%;
        }
        .overlay {
            display: none;
            opacity: 0;
            transition: opacity .3s ease-in-out;
        }
        .overlay.active {
            display: block;
            opacity: 1;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type="number"] {
            -moz-appearance: textfield;
            appearance: textfield;
        }
    </style>

</head>
<!-- oncontextmenu="return false" -->


<body class="sidebar-mini layout-fixed" style="height: auto">



    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="" src="../../dist/img/itcsologo.webp" alt="AdminLTELogo" height="60" width="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar sticky-top navbar-expand navbar-dark navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="material-icons" style="font-size:20px;vertical-align:middle;">menu</i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <div class="collapse navbar-collapse justify-content-end text-sm" id="navbarSupportedContent">
                <ul class="navbar-nav navbar-sidebar justify-content-end">
                    <!-- Notifications Dropdown Menu -->
                    <li class="nav-item">
                        <a class="nav-link text-sm" data-widget="fullscreen" href="#" role="button">
                                <i class="material-icons text-white" style="font-size:20px;vertical-align:middle;">fullscreen</i>
                            </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link text-sm pt-0 pb-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                            <div class="image pt-0 pb-0">
                                <img src="../../dist/img/default.jfif" class="img-circle portrait-sidebar elevation-2" alt="User Image">
                            </div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="background-color: #495057 !important">
                            <div class="user-panel d-flex">
                                <div class="image">
                                    <img src="../../dist/img/default.jfif" class="img-circle elevation-2" alt="User Image">
                                </div>
                                <div class="info">
                                    <a href="#" class="d-block text-white text-sm">BEN GANAGANAG</a>
                                </div>
                            </div>
                            <hr class="mt-1 mb-1">
                            <a class="nav-link text-sm sidebar-franchise-user-panel" style="padding-left: 13px;" role="button">
                            <i class="material-icons" style="font-size:18px;vertical-align:middle;background-color:rgba(16,16,16,0.42);border-radius:22px;padding:6px;">manage_accounts</i> &nbsp;Edit Profile
                            </a>
                            <a class="nav-link text-sm" style="padding-left: 13px;" onclick="logout()" role="button">
                                <i class="material-icons" style="font-size:18px;vertical-align:middle;background-color:rgba(16,16,16,0.42);border-radius:22px;padding:6px;">logout</i> &nbsp;Logout
                            </a>
                        </div>
                    </li>

                </ul>
            </div>
        </nav>
        <!-- /.navbar -->

        <?php include '../../pages/sidebar/sidebar.php' ?>

        <!-- body content -->

        <div id="body_wrapper" class="content-wrapper">
            <!-- PUT THE CONTENTS HERE -->
            <div class="content pt-4 pb-2">
                <div class="container-fluid">
                    <div class="card card-raised mb-3">
                        <div class="card-body d-flex align-items-center justify-content-between px-4 py-3">
                            <div>
                                <h5 class="mb-0 font-weight-bold">MSME Master List</h5>
                                <small class="text-muted">Micro, Small and Medium Enterprises</small>
                            </div>
                            <button type="button" class="btn btn-raised-primary btn-sm ml-auto" id="btn_add_business" data-toggle="modal" data-target="#addBusinessModal">
                                <i class="material-icons icon-sm leading-icon">add</i>Add Business
                            </button>
                        </div>
                    </div>
                    <div class="card card-raised no-hover">
                        <div class="card-body px-3 pt-3 pb-0">
                        <table id="tblBusiness" class="table table-striped table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Entity No.</th>
                                <th>Business Name</th>
                                <th>Organization Type</th>
                                <th>Employer Name</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>






    <div class="overlay" id="myOverlay">
        <div class="overlay-content">
            <img src="../../dist/img/load.gif" class="imageSpinner" alt="" srcset="">
            <!-- Your content here -->
        </div>
    </div>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">
            All rights reserved
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2024 ITCSO. <a href="http://lguscc.gov.ph/">Local Goverment of San Carlos City</a></strong>.
    </footer>
    </div>
    <!-- ./wrapper -->

    
    <?php include 'modal-add.php'; ?>
    <?php include 'modal-update.php'; ?>
    <?php include 'modal-renew.php'; ?>
    <?php include 'modal-status.php'; ?>

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- BS-Stepper -->
    <script src="../../plugins/bs-stepper/js/bs-stepper.min.js"></script>


    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script>



    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>


    <!-- <script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script> -->
    <script src="../../plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../../plugins/dropzone/min/dropzone.min.js"></script>
    <script src="../../plugins/validate.js-master/validate.min.js"></script>
    <script src="../../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script src="../../plugins/fontawesomekit/a757e6f388.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>
    <script src="../../scripts/common/alert.js"></script>
    <script src="../../plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <!-- new  -->
    <script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>
    <script src="../../plugins/select2/js/select2.min.js"></script>


    
    <script src="../../scripts/common/address.js"> </script>
    <script src="../../scripts/common/currency.js"> </script>
    <script src="../../scripts/msme/business-table.js"> </script>
    <script src="../../scripts/msme/business-add.js"></script>
    <script src="../../scripts/msme/business-update.js"></script>
    <script src="../../scripts/msme/business-renew.js"></script>
    <script src="../../scripts/msme/business-status.js?v=2"></script>


</body>

</html>