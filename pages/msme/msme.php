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

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="../../dist/css/font.css">
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
    <link rel="stylesheet" href="../../dist/css/user_defined.css">
    <link rel="stylesheet" href="../../plugins/dropzone/min/dropzone.min.css" type="text/css" />
    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../../plugins/ekko-lightbox/ekko-lightbox.css">


    <!-- new -->
    <link rel="stylesheet" href="https://unpkg.com/intro.js/minified/introjs.min.css">


    <!-- Toastr -->
    <style>
        /* Center text in DataTables */

        /* form */


        /* submit button */


        .custom-submit-from-button {

            font-family: inherit;
            font-size: 26px;
            background: #4169e100;
            padding: 0.7em 1.2em;
            padding-left: 1.2em;
            padding-left: 0.9em;
            display: flex;
            font-weight: bold;
            align-items: center;
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.2s;
            cursor: pointer;
            background: #f00;
            color: white;
            height: 60px;
        }


        .swal2-image {
            animation: fly-1 2s ease-in-out infinite alternate;
            /* Bouncing effect */
            width: 100px;
            margin-top: 40px;
            margin-bottom: -32px;
            /* Adjust the size as needed */
            height: 100px;
        }



        /* .custom-submit-from-button-clicked {

            font-family: inherit;
            font-size: 26px;
            background: #4169e100;

            padding: 0.7em 2em;
            padding-left: 0.9em;
            display: flex;
            font-weight: bold;
            align-items: center;
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.2s;
            cursor: pointer;

        } */


        /* #custom-submit-from-button span {
            display: block;
            margin-left: 0.3em;
            transition: all 0.4s ease-in-out;
            z-index: 1;
        } */

        /* #custom-submit-from-button img {
            width: 47px;
            image-rendering: crisp-edges;
            display: block;
            transform-origin: center center;
            transition: opacity 0.4s ease-in-out;
            z-index: 2;
        } */

        /* #custom-submit-from-button .svg-wrapper {
            transition: opacity 1.5s ease-in-out, transform 1.5s ease-in-out;

        } */

        /* Styles for the road */
        /* .road {
            width: 250px;
            height: 1.5px;
            background-color: black;
            margin-top: 3px;
            position: absolute;
            z-index: 1;
            left: 130px;
        } */




        /* #custom-submit-from-button:hover .svg-wrapper {
            animation: fly-1 3s ease-in-out infinite alternate;

        }

 
        #custom-submit-from-button:hover img {
            transform: translateX(3.5em) rotate(0deg);
            z-index: 2;
        }

        #custom-submit-from-button:hover span {
            transform: translate(-1.9em, 0px);
            font-weight: bold;
            z-index: 1;
        } */

        /* #custom-submit-from-button:active {
            transform: scale(0.95);
        } */


        .animate-fly {
            animation: fly-1 2s ease-in-out infinite alternate;
        }


        @keyframes fly-1 {
            from {
                transform: translate(1em, 4px) rotate(-3deg);

            }

            to {
                transform: translate(1em, 3px) rotate(3deg);
            }
        }






        /* submit button */


        .datatable-header {
            min-width: 100%;
        }

        .datatable-body {
            min-width: 100%;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            /* Above Bootstrap's modal overlay */
        }

        .overlay-content {
            position: absolute;
            top: 50%;
            left: 60%;
            transform: translate(-50%, -50%);
        }

        /* Ensure .btn-close is visible on the dark background */
        .imageSpinner {
            filter: invert(1);
            mix-blend-mode: multiply;
            width: 30%;
        }

        /* animate */
        .overlay {
            /* Other styles */
            display: none;
            /* Hidden by default */
            opacity: 0;
            transition: opacity .3s ease-in-out;
        }

        .overlay.active {
            display: block;
            /* Show overlay */
            opacity: 1;
        }


        #unit-masterlist-table-view tr td .view-modal {
            background-color: transparent;
        }



        .nav-icon {
            margin-bottom: 2px;
        }

        .text {
            font-size: 14px !important;
            color: #fff;
        }

        .dropdown .nav-item .nav-link {
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        }


        .portrait {
            height: 100px !important;
        }

        .portrait-sidebar {
            height: 32px !important;
        }

        #table-body-unit {
            font-size: 15px;
            /* font-weight: bold; */
            text-align: center;
        }

        .rounded-circle {
            border-radius: 30px !important;
        }



        .table td {
            vertical-align: middle !important;
        }

        .row-chassis {
            font-size: 12px;
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

        .name {
            display: inline-block;
            width: 180px;
            white-space: nowrap;
            overflow: hidden !important;
            text-overflow: ellipsis;
        }

        .ribbon-wrapper {
            height: 57px !important;
            right: -1px !important;
        }

        .dz-upload {
            background-color: green;
            display: block;
            height: 10px;
            width: 0%;
        }


        #container-image {
            display: inline-block;
            overflow: hidden;
            /* clip the excess when child gets bigger than parent */
        }

        #container-image img {
            display: block;
            transition: transform .4s;
            /* smoother zoom */
        }

        #container-image:hover img {
            transform: scale(1.3);
            transform-origin: 50% 50%;
        }

        .nav-tabs .nav-item .nav-link {
            color: black
        }



        .user-panel .info {
            display: inline-block;
            padding: 8px 5px 10px 10px;
        }

        .user-panel img {
            height: 33px;
        }

        .portrait-sidebar {
            height: 32px !important;
            width: 33px;
        }

        #upload-adtl-file {
            width: 240px
        }


        #table_masterlist.dataTable thead th,
        #table_pending_franchise.dataTable thead th,
        #table_expired_franchise.dataTable thead th,
        #table_about_expire_franchise.dataTable thead th,
        #table_active_franchise.dataTable thead th,
        #table_history_franchise.dataTable thead th,
        #table_data_entry_franchise.dataTable thead th {
            background-color: #343a40;
            border-color: #4b545c;
            /* Change this to your desired header color */
            color: white;
            text-align: center;
            /* Optional: change text color */
        }

        #table_masterlist.dataTable tbody td,
        #table_pending_franchise.dataTable tbody td,
        #table_expired_franchise.dataTable tbody td,
        #table_about_expire_franchise.dataTable tbody td,
        #table_history_franchise.dataTable tbody td,
        #unit-masterlist-table-view.dataTable tbody td,
        #unit-masterlist-table-view.dataTable thead th {

            text-align: center;
            /* Optional: change text color */
        }




        .introjs-skipbutton {
            color: black;
            border-radius: 5px;
            border: none;
            cursor: pointer;


            font-size: 17px;
            font-weight: lighter;
        }


        /* Position the tooltip below the target element */
        .introjs-tooltip {
            position: absolute;
            top: 104% !important;
            /* Position below the target element */
            left: 10% !important;
            transform: translateX(-50%);
            /* Center the tooltip horizontally */
            max-width: 90vw;
            /* Ensure the tooltip doesn't overflow the viewport */
            width: auto;
            /* Adjust width to content */
            height: auto;
            /* Adjust height to content */
            padding: 20px;
            /* Add padding for content */
            box-sizing: border-box;
            /* Include padding in size calculations */
            overflow: hidden;
            /* Hide overflow content */
        }

        /* Ensure tooltip text wraps properly */
        .introjs-tooltip .introjs-tooltiptext {
            word-wrap: break-word;
            white-space: normal;
        }

        /* Position buttons within the tooltip */
        .introjs-tooltipbuttons {
            text-align: right;
            /* Align buttons to the right */
            margin-top: 10px;
            /* Add space between content and buttons */
        }

        /* Ensure the tooltip is positioned correctly on small screens */
        @media (max-width: 768px) {
            .introjs-tooltip {
                padding: 15px;
                /* Reduce padding */
                font-size: 14px;
                /* Adjust font size */
                max-width: 80vw;
                /* Adjust max width on small screens */
            }
        }





        #edit_application .nav .active {
            background-color: #6c757d;
            font-weight: bold;
            color: white;
            border-bottom: 0;
        }

        #openViewModalBody .nav .active {
            background-color: #6c757d;
            font-weight: bold;
            color: white;
            margin-left: -1px;
            border-bottom: 0;
        }

        /* #table_expired_franchise.dataTable tbody td {} */
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
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <div class="collapse navbar-collapse justify-content-end text-sm" id="navbarSupportedContent">
                <ul class="navbar-nav navbar-sidebar justify-content-end">
                    <!-- Notifications Dropdown Menu -->
                    <li class="nav-item">
                        <a class="nav-link text-sm" data-widget="fullscreen" href="#" role="button">
                            <i class="fas fa-expand-arrows-alt text-white"></i>
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
                                <i class="fa-solid fa-user-pen" style="background-color: rgb(16 16 16 / 42%); border-radius: 22px;padding: 7px 5px 5px 9px !important; height: 31px;"></i> &nbsp Edit Profile
                            </a>
                            <a class="nav-link text-sm" style="padding-left: 13px;" onclick="logout()" role="button">
                                <i class="fa-solid p-1 fa-right-from-bracket" style="background-color: rgb(16 16 16 / 42%); border-radius: 22px ; padding: 9px !important;"></i> &nbsp Logout
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
            <div class="content mt-3">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body text-center">
                            <h2 class="mt-4 mb-3 display-4">MSME Master List</h2>
                        </div>
                        
                    </div>                      
                    <div>
                        <div class="text-center mb-2">
        <button type="button" class="btn btn-primary btn-sm" id="btn_add_business" data-toggle="modal" data-target="#addBusinessModal">
            <i class="fas fa-plus mr-1"></i>Add Business
        </button>
    </div>
                        <table id="tblBusiness" class="table table-striped table-bordered">
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
                        </table></div>
                    
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

    
    <?php include 'modal-view.php'; ?>
     <?php include 'modal-add.php'; ?>

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- BS-Stepper -->
    <script src="../../plugins/bs-stepper/js/bs-stepper.min.js"></script>


    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>


    <!-- <script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script> -->
    <script src="../../plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../../plugins/dropzone/min/dropzone.min.js"></script>
    <script src="../../plugins/validate.js-master/validate.min.js"></script>
    <script src="../../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script src="../../plugins/fontawesomekit/a757e6f388.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>
    <script src="../../plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <!-- new  -->
    <script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>



    <script src="../../scripts/msme/business-table.js"> </script>

    <script src="../../scripts/msme/business-add.js"></script>


</body>

</html>