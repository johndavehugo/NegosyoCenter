<?php
// dti.php
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DTI Price Monitoring | City of San Carlos</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="../../dist/css/font.css">

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">

    <!-- DataTables -->
    <link rel="stylesheet"
          href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.css">

    <!-- Font Awesome - Local -->
    <link rel="stylesheet"
          href="../../plugins/fontawesome-free/css/all.min.css">

    <!-- Font Awesome - CDN fallback -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous"
          referrerpolicy="no-referrer">

    <!-- Select2 -->
    <link rel="stylesheet"
          href="../../plugins/select2/css/select2.min.css">

    <link rel="stylesheet"
          href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet"
          href="../../dist/css/adminlte.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet"
          href="../../dist/css/user_defined.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">

    <!-- Favicon -->
    <link rel="icon"
          type="image/png"
          sizes="40x16"
          href="../../dist/img/splogo.png">


    <style>

        /* =========================================================
           GENERAL
        ========================================================= */

        .table td {
            vertical-align: middle !important;
        }


        /* =========================================================
           STATUS BADGES
        ========================================================= */

        .badge-overpriced {
            background-color: #dc3545;
            color: #ffffff;
        }

        .badge-compliant {
            background-color: #28a745;
            color: #ffffff;
        }

        .badge-below {
            background-color: #ffc107;
            color: #1f2d3d;
        }


        /* =========================================================
           PAGE HEADER
        ========================================================= */

        .page-header-banner {
            background: linear-gradient(
                135deg,
                #0056b3 0%,
                #003366 100%
            );

            color: #ffffff;

            border-radius: 8px;

            box-shadow:
                0 4px 6px rgba(0, 0, 0, 0.10);
        }


        .header-logo {
            max-height: 75px;
            width: auto;
            object-fit: contain;
        }


        /* =========================================================
           USER IMAGE
        ========================================================= */

        .portrait-sidebar {
            width: 38px;
            height: 38px;
            object-fit: cover;
        }


        /* =========================================================
           TABLE
        ========================================================= */

        #tblPriceMonitoring {
            width: 100% !important;
        }


        #tblPriceMonitoring th {
            white-space: nowrap;
            vertical-align: middle;
        }


        #tblPriceMonitoring td {
            vertical-align: middle;
        }


        /* =========================================================
           SELECT2
        ========================================================= */

        .select2-container {
            width: 100% !important;
        }


        .select2-container--bootstrap4
        .select2-selection {
            min-height: 38px;
        }


        /* =========================================================
           MODAL
        ========================================================= */

        .modal-header .close {
            outline: none;
        }


        /* =========================================================
           QUICK STATS
        ========================================================= */

        .info-box {
            min-height: 90px;
        }


        .info-box-number {
            font-size: 20px;
        }

    </style>

</head>


<body class="hold-transition sidebar-mini layout-fixed">


<!-- =========================================================
     PRELOADER
========================================================= -->

<div class="preloader flex-column justify-content-center align-items-center">

    <img
        src="../../dist/img/splogo.png"
        alt="Logo"
        height="60"
        width="60"
    >

</div>


<!-- =========================================================
     NAVBAR
========================================================= -->

<nav class="main-header navbar navbar-expand navbar-dark sticky-top">

    <!-- LEFT NAVIGATION -->
    <ul class="navbar-nav">

        <li class="nav-item">

            <a
                class="nav-link"
                data-widget="pushmenu"
                href="#"
                role="button"
            >

                <i class="fas fa-bars"></i>

            </a>

        </li>

    </ul>


    <!-- RIGHT NAVIGATION -->
    <ul class="navbar-nav ml-auto">

        <!-- FULLSCREEN -->
        <li class="nav-item">

            <a
                class="nav-link"
                data-widget="fullscreen"
                href="#"
                role="button"
            >

                <i class="fas fa-expand-arrows-alt text-white"></i>

            </a>

        </li>


        <!-- USER DROPDOWN -->
        <li class="nav-item dropdown">

            <a
                class="nav-link"
                data-toggle="dropdown"
                href="#"
                role="button"
            >

                <div class="image">

                    <img
                        src="../../dist/img/default.jfif"
                        class="img-circle portrait-sidebar elevation-2"
                        alt="User"
                    >

                </div>

            </a>


            <div
                class="dropdown-menu dropdown-menu-right"
                style="background-color:#495057;"
            >

                <div class="user-panel d-flex p-2">

                    <div class="image">

                        <img
                            src="../../dist/img/default.jfif"
                            class="img-circle elevation-2"
                            alt="User"
                        >

                    </div>


                    <div class="info">

                        <a
                            href="#"
                            class="d-block text-white"
                        >
                            Username
                        </a>

                    </div>

                </div>


                <div class="dropdown-divider"></div>


                <a
                    class="dropdown-item text-white"
                    href="#"
                    style="padding-left:13px;"
                    onclick="logout(); return false;"
                >

                    <i
                        class="fa-solid fa-right-from-bracket p-1"
                        style="
                            background-color:rgba(0,0,0,0.42);
                            border-radius:22px;
                            padding:9px;
                        "
                    ></i>

                    &nbsp; Logout

                </a>

            </div>

        </li>

    </ul>

</nav>


<!-- =========================================================
     SIDEBAR
========================================================= -->

<?php include '../../pages/sidebar/sidebar.php'; ?>


<!-- =========================================================
     CONTENT WRAPPER
========================================================= -->

<div
    id="body_wrapper"
    class="content-wrapper"
>


    <!-- CONTENT -->
    <div class="content pt-3">

        <div class="container-fluid">


            <!-- =================================================
                 PAGE HEADER
            ================================================== -->

            <div class="card page-header-banner mb-3 border-0">

                <div class="card-body py-3">

                    <div
                        class="
                            d-flex
                            flex-column
                            flex-md-row
                            align-items-center
                            justify-content-between
                            text-center
                            text-md-left
                        "
                    >


                        <!-- TITLE -->
                        <div
                            class="
                                d-flex
                                align-items-center
                                mb-2
                                mb-md-0
                            "
                        >

                            <img
                                src="../../dist/img/splogo.png"
                                alt="San Carlos"
                                class="
                                    header-logo
                                    mr-md-3
                                    mb-2
                                    mb-md-0
                                "
                            >


                            <div>

                                <h2 class="m-0 font-weight-bold">
                                    DTI Price Monitoring Module
                                </h2>

                                <p class="mb-0 text-white-50">
                                    E-Presyo System • Basic Necessities & Prime Commodities (BNPC)
                                </p>

                            </div>

                        </div>


                        <!-- LOCATION -->
                        <div class="mt-2 mt-md-0 text-md-right">

                            <span
                                class="
                                    badge
                                    badge-light
                                    p-2
                                    font-weight-normal
                                "
                            >

                                <i
                                    class="
                                        fa-solid
                                        fa-location-dot
                                        mr-1
                                        text-primary
                                    "
                                ></i>

                                City of San Carlos

                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 QUICK STATISTICS
            ================================================== -->

            <div class="row">


                <!-- MONITORED ITEMS -->
                <div class="col-md-3 col-sm-6">

                    <div class="info-box bg-info">

                        <span class="info-box-icon">

                            <i class="fa-solid fa-boxes-stacked"></i>

                        </span>


                        <div class="info-box-content">

                            <span class="info-box-text">
                                Monitored Items
                            </span>

                            <span
                                class="info-box-number"
                                id="stat_total_items"
                            >
                                0 Items
                            </span>

                        </div>

                    </div>

                </div>


                <!-- COMPLIANT -->
                <div class="col-md-3 col-sm-6">

                    <div class="info-box bg-success">

                        <span class="info-box-icon">

                            <i class="fa-solid fa-circle-check"></i>

                        </span>


                        <div class="info-box-content">

                            <span class="info-box-text">
                                Compliant Logs
                            </span>

                            <span
                                class="info-box-number"
                                id="stat_compliant_stores"
                            >
                                0 Logs
                            </span>

                        </div>

                    </div>

                </div>


                <!-- OVERPRICED -->
                <div class="col-md-3 col-sm-6">

                    <div class="info-box bg-danger">

                        <span class="info-box-icon">

                            <i class="fa-solid fa-triangle-exclamation"></i>

                        </span>


                        <div class="info-box-content">

                            <span class="info-box-text">
                                Overpriced Alerts
                            </span>

                            <span
                                class="info-box-number"
                                id="stat_overpriced_alerts"
                            >
                                0 Logs
                            </span>

                        </div>

                    </div>

                </div>


                <!-- JURISDICTION -->
                <div class="col-md-3 col-sm-6">

                    <div class="info-box bg-warning">

                        <span class="info-box-icon">

                            <i class="fa-solid fa-building-flag"></i>

                        </span>


                        <div class="info-box-content">

                            <span class="info-box-text">
                                Jurisdiction
                            </span>

                            <span class="info-box-number">
                                DTI - BNPC
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 MAIN CARD
            ================================================== -->

            <div class="card card-primary card-outline">


                <!-- CARD HEADER -->
                <div class="card-header">

                    <h3 class="card-title mt-1">

                        <i class="fa-solid fa-store mr-1"></i>

                        DTI Commodity Prevailing Price Monitoring

                    </h3>


                    <div class="card-tools">

                        <!-- Add button can be inserted by dti-actions.js -->

                    </div>

                </div>


                <!-- CARD BODY -->
                <div class="card-body">


                    <!-- CATEGORY FILTER -->
                    <div class="row mb-3">

                        <div class="col-md-4">

                            <label for="filter_category">
                                Filter by Category:
                            </label>


                            <select
                                id="filter_category"
                                class="form-control select2"
                            >

                                <option value="">
                                    All Categories
                                </option>

                            </select>

                        </div>

                    </div>


                    <!-- =================================================
                         TABLE
                    ================================================== -->

                    <div class="table-responsive">

                        <table
                            id="tblPriceMonitoring"
                            class="
                                table
                                table-bordered
                                table-striped
                                datatable
                                w-100
                            "
                        >

                            <thead>

                                <tr>

                                    <th>
                                        Product Name
                                    </th>

                                    <th>
                                        Category
                                    </th>

                                    <th>
                                        Agency
                                    </th>

                                    <th>
                                        Brand / Unit
                                    </th>

                                    <th>
                                        SRP (₱)
                                    </th>

                                    <th>
                                        Prevailing Price (₱)
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th
                                        class="text-center"
                                        style="width:120px;"
                                    >
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody>
                                <!-- DataTables loads records here -->
                            </tbody>

                        </table>

                    </div>


                </div>
                <!-- /.card-body -->


            </div>
            <!-- /.card -->


        </div>
        <!-- /.container-fluid -->


    </div>
    <!-- /.content -->


</div>
<!-- /.content-wrapper -->


<!-- =========================================================
     CONTROL SIDEBAR
========================================================= -->

<aside class="control-sidebar control-sidebar-dark">

    <div class="p-3">

        <h5>
            Title
        </h5>

        <p>
            Sidebar content
        </p>

    </div>

</aside>


<!-- =========================================================
     FOOTER
========================================================= -->

<footer class="main-footer">

    <div class="float-right d-none d-sm-inline">

        All rights reserved

    </div>


    <strong>

        Copyright &copy; 2026

        <a href="http://lguscc.gov.ph/">

            Local Government of San Carlos City

        </a>

    </strong>

</footer>


<!-- =========================================================
     ADD PRICE MODAL
========================================================= -->

<div
    class="modal fade"
    id="modalAddPrice"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalAddPriceLabel"
    aria-hidden="true"
>


    <div
        class="modal-dialog modal-lg"
        role="document"
    >

        <div class="modal-content">


            <!-- HEADER -->
            <div class="modal-header bg-primary text-white">

                <h5
                    class="modal-title"
                    id="modalAddPriceLabel"
                >

                    <i class="fa-solid fa-circle-plus mr-1"></i>

                    Add New DTI Price Entry

                </h5>


                <button
                    type="button"
                    class="close text-white"
                    data-dismiss="modal"
                    aria-label="Close"
                >

                    <span aria-hidden="true">
                        &times;
                    </span>

                </button>

            </div>


            <!-- FORM -->
            <form id="formAddPrice">


                <div class="modal-body">

                    <div class="row">


                        <!-- AGENCY -->
                        <div class="col-md-6 form-group">

                            <label>
                                Monitoring Agency
                                <span class="text-danger">*</span>
                            </label>


                            <select
                                name="monitored_by_agency_id"
                                id="add_monitored_by_agency_id"
                                class="form-control select2-modal"
                                required
                            >

                                <option value="">
                                    -- Select Agency --
                                </option>

                            </select>

                        </div>


                        <!-- COMMODITY -->
                        <div class="col-md-6 form-group">

                            <label>
                                Commodity / Item
                                <span class="text-danger">*</span>
                            </label>


                            <select
                                name="commodity_id"
                                id="add_commodity_id"
                                class="form-control select2-modal"
                                required
                            >

                                <option value="">
                                    -- Select Commodity --
                                </option>

                            </select>

                        </div>


                        <!-- PRICE -->
                        <div class="col-md-6 form-group">

                            <label>
                                Prevailing Monitored Price (₱)
                                <span class="text-danger">*</span>
                            </label>

    
                           <input
    type="number"
    step="0.01"
    min="0"
    id="add_prevailing_price"
    name="prevailing_price"
    class="form-control"
    placeholder="0.00"
    required
>

                        </div>


                        <!-- STATUS -->
                        <div class="col-md-6 form-group">

                            <label>
                                Compliance Status
                                <span class="text-danger">*</span>
                            </label>


                            <select
                                name="status"
                                class="form-control"
                                required
                            >

                                <option value="WITHIN_SRP">
                                    WITHIN_SRP
                                </option>

                                <option value="OVERPRICED">
                                    OVERPRICED
                                </option>

                                <option value="BELOW_SRP">
                                    BELOW_SRP
                                </option>

                            </select>

                        </div>


                    </div>

                </div>


                <!-- FOOTER -->
                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >

                        Cancel

                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="fa-solid fa-floppy-disk mr-1"></i>

                        Save Price Entry

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>


<!-- =========================================================
     EDIT DTI PRICE MODAL
========================================================= -->

<div
    class="modal fade"
    id="modalEditPrice"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalEditPriceLabel"
    aria-hidden="true"
>

    <div
        class="modal-dialog modal-lg"
        role="document"
    >

        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-info text-white">

                <h5
                    class="modal-title"
                    id="modalEditPriceLabel"
                >
                    <i class="fa-solid fa-pen-to-square mr-1"></i>
                    Update DTI Price Entry
                </h5>

                <button
                    type="button"
                    class="close text-white"
                    data-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>

            </div>


            <!-- FORM -->
            <form id="formEditPrice">

                <!-- RECORD ID -->
                <input
                    type="hidden"
                    id="edit_entry_id"
                    name="id"
                >

                <!-- AGENCY ID -->
                <input
                    type="hidden"
                    id="edit_monitored_by_agency_id"
                    name="monitored_by_agency_id"
                >

                <!-- COMMODITY ID -->
                <input
                    type="hidden"
                    id="edit_commodity_id"
                    name="commodity_id"
                >


                <div class="modal-body">

                    <div class="row">

                        <!-- AGENCY JURISDICTION -->
                        <div class="col-md-6 form-group">

                            <label>
                                Agency Jurisdiction
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="DTI - Basic Necessities & Prime Commodities"
                                readonly
                            >

                        </div>


                        <!-- CATEGORY -->
                        <div class="col-md-6 form-group">

                            <label>
                                Category
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="edit_category"
                                name="category"
                                class="form-control"
                                required
                            >

                        </div>


                        <!-- UNIT -->
                        <div class="col-md-4 form-group">

                            <label>
                                Unit / Packaging
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="edit_unit"
                                name="unit"
                                class="form-control"
                                placeholder="e.g. kg, pc, can, pack"
                                required
                            >

                        </div>


                        <!-- PRODUCT -->
                        <div class="col-md-8 form-group">

                            <label>
                                Commodity / Product Description
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="edit_item_description"
                                name="item_description"
                                class="form-control"
                                placeholder="e.g. Well-Milled Rice, Sardines, Sugar"
                                required
                            >

                        </div>


                        <!-- =================================================
                             DTI SRP — EDITABLE
                        ================================================== -->

                        <div class="col-md-6 form-group">

                            <label>
                                DTI SRP / Guide Price (₱)
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="edit_srp_price"
                                name="srp_price"
                                class="form-control"
                                placeholder="0.00"
                                autocomplete="off"
                                required
                            >

                        </div>


                        <!-- PREVAILING PRICE -->
                        <div class="col-md-6 form-group">

                            <label>
                                Prevailing Monitored Price (₱)
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="edit_prevailing_price"
                                name="prevailing_price"
                                class="form-control"
                                placeholder="0.00"
                                autocomplete="off"
                                required
                            >

                        </div>

                    </div>

                </div>


                <!-- FOOTER -->
                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-info"
                    >
                        <i class="fa-solid fa-rotate mr-1"></i>
                        Update Entry
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- =========================================================
     REQUIRED SCRIPTS
========================================================= -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>

<!-- Popper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

<!-- Bootstrap -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE -->
<script src="../../dist/js/adminlte.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

<!-- Select2 -->
<script src="../../plugins/select2/js/select2.full.min.js"></script>


<!-- ==========================================sv===============
     DTI PRICE MONITORING JAVASCRIPT
========================================================= -->

<script src="../../scripts/price-monitoring/dti-actions.js"></script>

<script src="../../scripts/price-monitoring/dti-table.js"></script>

<script src="../../scripts/price-monitoring/dti-forms.js"></script>


</body>

</html>