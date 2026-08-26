<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>San Carlos City | Negosyo Center — Business View</title>

    <!-- Google Font: Roboto (Material) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../dist/css/user_defined.css?v=5">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">

    <style>
        /* ── Business View Page — scoped styles ─────────────────────── */

        /* Skeleton pulse animation for loading state */
        @keyframes bv-skeleton-pulse {
            0%   { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .bv-skeleton {
            background: linear-gradient(90deg, #e9ecef 25%, #f8f9fa 50%, #e9ecef 75%);
            background-size: 200% 100%;
            animation: bv-skeleton-pulse 1.4s ease infinite;
            border-radius: 4px;
            display: inline-block;
            min-height: 14px;
        }
        .bv-skeleton-text  { width: 120px; }
        .bv-skeleton-sm    { width: 80px; }
        .bv-skeleton-lg    { width: 180px; height: 20px; }
        .bv-skeleton-title { width: 200px; height: 22px; }

        /* ── Left panel cards ───────────────────────────────────────── */
        .bv-profile-card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,.10), 0 1px 3px rgba(0,0,0,.07);
        }

        /* Business icon circle (replaces photo in ViewTemplate) */
        .bv-business-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #e7f0ff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }
        .bv-business-icon .material-icons {
            font-size: 38px;
            color: #007bff;
        }

        .bv-business-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1a1a2e;
            line-height: 1.3;
            text-align: center;
        }

        .bv-entity-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background-color: #f1f3f5;
            color: #6c757d;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 3px 10px 3px 8px;
            border-radius: 20px;
        }
        .bv-entity-chip .material-icons {
            font-size: 13px !important;
            color: #adb5bd;
        }

        /* List group info rows */
        .bv-info-list .list-group-item {
            padding: 8px 12px;
            border-left: none;
            border-right: none;
            font-size: 0.875rem;
        }
        .bv-info-list .list-group-item:first-child { border-top: none; }
        .bv-info-list .list-group-item b {
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #9ca3af;
        }
        .bv-info-list .list-group-item .float-right {
            color: #1a1a2e;
            font-weight: 400;
            max-width: 60%;
            text-align: right;
        }

        /* Address card below profile */
        .bv-address-card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,.10), 0 1px 3px rgba(0,0,0,.07);
        }
        .bv-address-label {
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9ca3af;
            margin-bottom: 4px;
        }
        .bv-address-value {
            font-size: 0.875rem;
            color: #495057;
            line-height: 1.5;
            margin-bottom: 0;
        }

        /* ── Right panel ────────────────────────────────────────────── */
        .bv-menu-card,
        .bv-content-card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,.10), 0 1px 3px rgba(0,0,0,.07);
        }

        .bv-menu-item {
            cursor: pointer;
            user-select: none;
            padding: 13px 16px;
            font-size: 0.9rem;
            color: #212529;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background-color 0.12s ease;
        }
        .bv-menu-item:hover { background-color: #f8f9fa; }
        .bv-menu-item .bv-menu-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #e7f0ff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .bv-menu-item .bv-menu-icon .material-icons {
            font-size: 17px;
            color: #007bff;
        }
        .bv-menu-item .bv-chevron .material-icons {
            font-size: 16px;
            color: #adb5bd;
        }

        /* Content panel header */
        .bv-content-header {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-bottom: 1px solid #e9ecef;
        }
        .bv-back-btn {
            color: #6c757d;
            background: transparent;
            border: none;
            padding: 4px 8px 4px 0;
            line-height: 1;
            cursor: pointer;
            transition: color 0.12s ease;
        }
        .bv-back-btn:hover { color: #212529; }
        .bv-content-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0;
            flex-grow: 1;
        }

        /* Content section inner spacing */
        .bv-section-body { padding: 16px; }

        /* Field blocks inside content sections */
        .bv-field {
            margin-bottom: 1rem;
        }
        .bv-field-label {
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9ca3af;
            margin-bottom: 3px;
            display: block;
        }
        .bv-field-value {
            font-size: 0.9rem;
            color: #1a1a2e;
            font-weight: 400;
        }

        /* Entity chip inside content sections */
        .bv-owner-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background-color: #e9ecef;
            color: #495057;
            font-size: 0.78rem;
            font-weight: 500;
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: 1rem;
        }
        .bv-owner-chip .material-icons {
            font-size: 13px !important;
            color: #6c757d;
        }

        /* Address detail grid inside section */
        .bv-addr-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 24px;
        }
        @media (max-width: 576px) {
            .bv-addr-grid { grid-template-columns: 1fr; }
        }

        /* Section divider */
        .bv-section-divider {
            border: none;
            border-top: 1px solid #e9ecef;
            margin: 12px 0;
        }

        /* Back to list link */
        .bv-back-to-list {
            font-size: 0.875rem;
            color: #6c757d;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: color 0.12s ease;
        }
        .bv-back-to-list:hover { color: #007bff; text-decoration: none; }

        /* Not-found state */
        .bv-not-found {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }
        .bv-not-found .material-icons {
            font-size: 56px;
            color: #dee2e6;
            display: block;
            margin-bottom: 12px;
        }
        .bv-not-found h5 { color: #495057; font-weight: 600; margin-bottom: 6px; }
        .bv-not-found p  { font-size: 0.875rem; margin-bottom: 20px; }
    </style>
</head>

<body class="sidebar-mini layout-fixed" style="height: auto;">

    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img src="../../dist/img/itcsologo.webp" alt="Logo" height="60" width="60">
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
            <div class="collapse navbar-collapse justify-content-end text-sm" id="navbarSupportedContent">
                <ul class="navbar-nav navbar-sidebar justify-content-end">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                            <i class="material-icons text-white" style="font-size:20px;vertical-align:middle;">fullscreen</i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link pt-0 pb-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                            <div class="image pt-0 pb-0">
                                <img src="../../dist/img/default.jfif" class="img-circle portrait-sidebar elevation-2" alt="User Image">
                            </div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="background-color:#495057 !important">
                            <div class="user-panel d-flex">
                                <div class="image">
                                    <img src="../../dist/img/default.jfif" class="img-circle elevation-2" alt="User Image">
                                </div>
                                <div class="info">
                                    <a href="#" class="d-block text-white text-sm">BEN GANAGANAG</a>
                                </div>
                            </div>
                            <hr class="mt-1 mb-1">
                            <a class="nav-link text-sm" style="padding-left:13px;" role="button">
                                <i class="material-icons" style="font-size:18px;vertical-align:middle;background-color:rgba(16,16,16,0.42);border-radius:22px;padding:6px;">manage_accounts</i>&nbsp;Edit Profile
                            </a>
                            <a class="nav-link text-sm" style="padding-left:13px;" onclick="logout()" role="button">
                                <i class="material-icons" style="font-size:18px;vertical-align:middle;background-color:rgba(16,16,16,0.42);border-radius:22px;padding:6px;">logout</i>&nbsp;Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <?php include '../../pages/sidebar/sidebar.php' ?>

        <!-- Page content -->
        <div id="body_wrapper" class="content-wrapper">
            <div class="content pt-4 pb-2">
                <div class="container-fluid">

                    <!-- Back to list -->
                    <div class="mb-3">
                        <a href="msme.php" class="bv-back-to-list">
                            <i class="material-icons" style="font-size:18px;vertical-align:middle;">arrow_back</i>
                            Back to MSME List
                        </a>
                    </div>

                    <!-- Not-found state (hidden until JS decides to show it) -->
                    <div id="bvNotFound" class="d-none">
                        <div class="card card-raised bv-profile-card">
                            <div class="card-body bv-not-found">
                                <i class="material-icons">search_off</i>
                                <h5>Business Not Found</h5>
                                <p>The record you are looking for does not exist or could not be loaded.</p>
                                <a href="msme.php" class="btn btn-raised-primary btn-sm">
                                    <i class="material-icons leading-icon" style="font-size:16px;vertical-align:middle;">arrow_back</i>
                                    Return to List
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Main two-panel row (hidden until data loads) -->
                    <div class="row d-none" id="bvMain">

                        <!-- ── LEFT PANEL ───────────────────────────────────── -->
                        <div class="col-md-4 col-lg-3 mb-3">

                            <!-- Profile card -->
                            <div class="card bv-profile-card mb-3">
                                <div class="card-body pt-4 pb-3">

                                    <!-- Icon -->
                                    <div class="bv-business-icon">
                                        <i class="material-icons">storefront</i>
                                    </div>

                                    <!-- Business name -->
                                    <div class="bv-business-name mb-1" id="bvBusinessName">
                                        <span class="bv-skeleton bv-skeleton-title">&nbsp;</span>
                                    </div>

                                    <!-- Entity no chip -->
                                    <div class="text-center mb-3">
                                        <span class="bv-entity-chip">
                                            <i class="material-icons">tag</i>
                                            <span id="bvEntityNo"><span class="bv-skeleton bv-skeleton-sm">&nbsp;</span></span>
                                        </span>
                                    </div>

                                    <!-- Info list -->
                                    <ul class="list-group list-group-unbordered bv-info-list">
                                        <li class="list-group-item">
                                            <b>Owner</b>
                                            <span class="float-right" id="bvOwner">
                                                <span class="bv-skeleton bv-skeleton-text">&nbsp;</span>
                                            </span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Capitalization</b>
                                            <span class="float-right" id="bvCapitalization">
                                                <span class="bv-skeleton bv-skeleton-sm">&nbsp;</span>
                                            </span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Contact No.</b>
                                            <span class="float-right" id="bvContactNo">
                                                <span class="bv-skeleton bv-skeleton-text">&nbsp;</span>
                                            </span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Email</b>
                                            <span class="float-right" id="bvEmail">
                                                <span class="bv-skeleton bv-skeleton-text">&nbsp;</span>
                                            </span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Business Status</b>
                                            <span class="float-right" id="bvBusStatus">
                                                <span class="bv-skeleton bv-skeleton-sm">&nbsp;</span>
                                            </span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Application Status</b>
                                            <span class="float-right" id="bvAppStatus">
                                                <span class="bv-skeleton bv-skeleton-sm">&nbsp;</span>
                                            </span>
                                        </li>
                                    </ul>

                                </div>
                            </div>

                            <!-- Address card -->
                            <div class="card bv-address-card">
                                <div class="card-body">
                                    <p class="bv-address-label">
                                        <i class="material-icons" style="font-size:14px;vertical-align:middle;color:#adb5bd;">storefront</i>
                                        Business Address
                                    </p>
                                    <p class="bv-address-value" id="bvBusAddressShort">
                                        <span class="bv-skeleton bv-skeleton-lg">&nbsp;</span>
                                    </p>

                                    <hr class="bv-section-divider">

                                    <p class="bv-address-label">
                                        <i class="material-icons" style="font-size:14px;vertical-align:middle;color:#adb5bd;">home</i>
                                        Owner Address
                                    </p>
                                    <p class="bv-address-value" id="bvOwnerAddressShort">
                                        <span class="bv-skeleton bv-skeleton-lg">&nbsp;</span>
                                    </p>
                                </div>
                            </div>

                        </div>
                        <!-- ── END LEFT PANEL ──────────────────────────────── -->

                        <!-- ── RIGHT PANEL ─────────────────────────────────── -->
                        <div class="col-md-8 col-lg-9 mb-3">

                            <!-- Menu list card -->
                            <div class="card bv-menu-card" id="bvMenuList">
                                <div class="list-group list-group-flush" style="border-radius:8px;">

                                    <div class="bv-menu-item list-group-item list-group-item-action"
                                         data-target="bvSectionClassification" data-title="Classification">
                                        <div class="d-flex align-items-center">
                                            <span class="bv-menu-icon">
                                                <i class="material-icons">category</i>
                                            </span>
                                            Classification
                                        </div>
                                        <span class="bv-chevron"><i class="material-icons">chevron_right</i></span>
                                    </div>

                                    <div class="bv-menu-item list-group-item list-group-item-action"
                                         data-target="bvSectionOwner" data-title="Owner Details">
                                        <div class="d-flex align-items-center">
                                            <span class="bv-menu-icon">
                                                <i class="material-icons">person</i>
                                            </span>
                                            Owner Details
                                        </div>
                                        <span class="bv-chevron"><i class="material-icons">chevron_right</i></span>
                                    </div>

                                    <div class="bv-menu-item list-group-item list-group-item-action"
                                         data-target="bvSectionAddress" data-title="Business Address">
                                        <div class="d-flex align-items-center">
                                            <span class="bv-menu-icon">
                                                <i class="material-icons">location_on</i>
                                            </span>
                                            Business Address
                                        </div>
                                        <span class="bv-chevron"><i class="material-icons">chevron_right</i></span>
                                    </div>

                                </div>
                            </div>

                            <!-- Content area card (hidden until a menu item is clicked) -->
                            <div class="card bv-content-card d-none" id="bvContentCard">

                                <!-- Content header -->
                                <div class="bv-content-header">
                                    <button class="bv-back-btn" id="bvContentBack" title="Back to menu">
                                        <i class="material-icons" style="font-size:20px;vertical-align:middle;">chevron_left</i>
                                    </button>
                                    <h4 class="bv-content-title" id="bvContentTitle">Section</h4>
                                </div>

                                <!-- Section: Classification -->
                                <div id="bvSectionClassification" class="bv-section-body bv-content-section d-none">
                                    <div class="row">
                                        <div class="col-sm-4 mb-3">
                                            <span class="bv-field-label">Enterprise Class</span>
                                            <div id="bvClassification" class="bv-field-value">—</div>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <span class="bv-field-label">Sector / Product Line</span>
                                            <div id="bvSector" class="bv-field-value">—</div>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <span class="bv-field-label">Special Sector</span>
                                            <div id="bvSpecialSector" class="bv-field-value">—</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section: Owner Details -->
                                <div id="bvSectionOwner" class="bv-section-body bv-content-section d-none">
                                    <div class="mb-3">
                                        <span class="bv-owner-chip">
                                            <i class="material-icons">tag</i>
                                            Owner Entity No: <strong id="bvOwnerEntityNo">—</strong>
                                        </span>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 mb-3">
                                            <span class="bv-field-label">Full Name</span>
                                            <div id="bvOwnerName" class="bv-field-value">—</div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <span class="bv-field-label">Special Category</span>
                                            <div id="bvSpecialCategory" class="bv-field-value">—</div>
                                        </div>
                                    </div>
                                    <hr class="bv-section-divider">
                                    <p class="bv-field-label mb-2">
                                        <i class="material-icons" style="font-size:13px;vertical-align:middle;color:#adb5bd;">home</i>
                                        Owner Address
                                    </p>
                                    <div class="bv-addr-grid">
                                        <div>
                                            <span class="bv-field-label">Region</span>
                                            <div id="bvOwnerRegion" class="bv-field-value">—</div>
                                        </div>
                                        <div>
                                            <span class="bv-field-label">Province</span>
                                            <div id="bvOwnerProvince" class="bv-field-value">—</div>
                                        </div>
                                        <div>
                                            <span class="bv-field-label">City / Municipality</span>
                                            <div id="bvOwnerCity" class="bv-field-value">—</div>
                                        </div>
                                        <div>
                                            <span class="bv-field-label">Barangay</span>
                                            <div id="bvOwnerBarangay" class="bv-field-value">—</div>
                                        </div>
                                        <div>
                                            <span class="bv-field-label">Street</span>
                                            <div id="bvOwnerStreet" class="bv-field-value">—</div>
                                        </div>
                                        <div>
                                            <span class="bv-field-label">Subdivision</span>
                                            <div id="bvOwnerSubdivision" class="bv-field-value">—</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section: Business Address -->
                                <div id="bvSectionAddress" class="bv-section-body bv-content-section d-none">
                                    <div class="bv-addr-grid">
                                        <div>
                                            <span class="bv-field-label">Region</span>
                                            <div id="bvBusRegion" class="bv-field-value">—</div>
                                        </div>
                                        <div>
                                            <span class="bv-field-label">Province</span>
                                            <div id="bvBusProvince" class="bv-field-value">—</div>
                                        </div>
                                        <div>
                                            <span class="bv-field-label">City / Municipality</span>
                                            <div id="bvBusCity" class="bv-field-value">—</div>
                                        </div>
                                        <div>
                                            <span class="bv-field-label">Barangay</span>
                                            <div id="bvBusBarangay" class="bv-field-value">—</div>
                                        </div>
                                        <div>
                                            <span class="bv-field-label">Street</span>
                                            <div id="bvBusStreet" class="bv-field-value">—</div>
                                        </div>
                                        <div>
                                            <span class="bv-field-label">Subdivision</span>
                                            <div id="bvBusSubdivision" class="bv-field-value">—</div>
                                        </div>
                                        <div>
                                            <span class="bv-field-label">UPBLB No.</span>
                                            <div id="bvBusUpblb" class="bv-field-value">—</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /.bv-content-card -->

                        </div>
                        <!-- ── END RIGHT PANEL ─────────────────────────────── -->

                    </div>
                    <!-- /.row#bvMain -->

                </div>
            </div>
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">All rights reserved</div>
            <strong>Copyright &copy; 2024 ITCSO. <a href="http://lguscc.gov.ph/">Local Government of San Carlos City</a></strong>.
        </footer>

    </div>
    <!-- /.wrapper -->

    <!-- Scripts -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>
    <script src="../../scripts/common/alert.js"></script>
    <script src="../../scripts/common/currency.js"></script>
    <script src="../../scripts/msme/business-view-page.js"></script>

</body>
</html>
