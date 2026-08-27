<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>San Carlos City | Negosyo Center - Business View</title>

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

    <style>
        /* Page-level overrides — shared styles live in user_defined.css */
        
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

        /* ── Business View Page Styles ─────────────────────── */

        /* Page fills the full content-wrapper height */
        .bv-page-content {
            min-height: calc(100vh - 57px);
        }

        /* Loading state — full height, centered */
        #bvLoading {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 57px - 80px); /* viewport minus navbar minus panel padding */
        }
        .bv-loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
        }
        .bv-loading-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background-color: #e7f0ff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            animation: bv-pulse 1.2s ease-in-out infinite;
        }
        .bv-loading-icon .material-icons {
            font-size: 32px;
            color: #007bff;
        }
        .bv-loading-text {
            font-size: 0.9rem;
            color: #007bff;
        }
        @keyframes bv-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(0,123,255,0.25); }
            50%       { box-shadow: 0 0 0 10px rgba(0,123,255,0); }
        }

        /* Back to list link */
        .bv-back-to-list {
            font-size: 0.875rem;
            color: #6c757d;
            text-decoration: none;
            border: none;
            background: none;
            padding: 8px 0;
            display: inline-flex;
            align-items: center;
            transition: color 0.15s ease;
        }
        .bv-back-to-list:hover {
            color: #007bff;
            text-decoration: none;
        }

        /* Profile card */
        .bv-profile-card {
            border: 1px solid #e3e6f0;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        }
        .bv-business-icon {
            text-align: center;
            margin: 0 auto 12px;
        }
        .bv-business-icon .material-icons { font-size: 34px; color: #007bff; }

        .bv-business-name {
            font-size: 1.125rem;
            font-weight: 500;
            color: #374151;
            text-align: center;
            margin-bottom: 0;
        }

        .bv-entity-chip {
            background-color: #f8fafc;
            color: #64748b;
            padding: 4px 8px;
            border-radius: 20px;
        }
        .bv-entity-chip .material-icons { font-size: 13px !important; color: #adb5bd; }

        /* Info list */
        .bv-info-list .list-group-item {
            border: none;
            padding: 8px 0;
            color: #6c757d;
        }
        .bv-info-list .list-group-item b {
            color: #374151;
        }

        /* Address cards */
        .bv-address-card {
            border: 1px solid #e3e6f0;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        }
        .bv-address-label {
            font-size: 0.75rem;
            color: #9ca3af;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0 0 4px 0;
        }
        .bv-address-value {
            font-size: 0.875rem;
            color: #374151;
            margin: 0;
        }
        .bv-section-divider {
            margin: 16px 0;
            border-color: #f1f5f9;
        }

        /* Menu card */
        .bv-menu-card {
            border: 1px solid #e3e6f0;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .bv-menu-item {
            border: none !important;
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            background: #fff;
        }
        .bv-menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, #007bff 0%, #28a745 100%);
            transform: scaleY(0);
            transition: transform 0.2s ease;
        }
        .bv-menu-item:hover {
            background: linear-gradient(135deg, #fafbff 0%, #f1f5f9 100%);
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0,123,255,0.1);
        }
        .bv-menu-item:hover::before {
            transform: scaleY(1);
        }
        .bv-menu-item .bv-menu-icon {
            margin-right: 16px;
            width: 24px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .bv-menu-item .bv-menu-icon .material-icons { 
            font-size: 20px; 
            color: #007bff;
            transition: all 0.2s ease;
        }
        .bv-menu-item:hover .bv-menu-icon .material-icons {
            color: #0056b3;
            transform: scale(1.1);
        }
        .bv-menu-item .d-flex span {
            font-weight: 500;
            color: #495057;
            font-size: 0.95rem;
            transition: color 0.2s ease;
        }
        .bv-menu-item:hover .d-flex span {
            color: #007bff;
        }
        .bv-menu-item .bv-chevron .material-icons { 
            font-size: 18px; 
            color: #adb5bd;
            transition: all 0.2s ease;
        }
        .bv-menu-item:hover .bv-chevron .material-icons {
            color: #007bff;
            transform: translateX(4px);
        }

        /* Content card header */
        .bv-content-header {
            padding: 20px 24px;
            border-bottom: 2px solid #f1f5f9;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #fafbff 0%, #f1f5f9 100%);
            position: relative;
        }
        .bv-content-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 24px;
            right: 24px;
            height: 3px;
            background: linear-gradient(90deg, #007bff 0%, #0056b3 50%, #28a745 100%);
            border-radius: 2px;
        }
        .bv-back-btn {
            border: none;
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
            margin-right: 16px;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.2s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .bv-back-btn:hover {
            background: rgba(0, 123, 255, 0.2);
            color: #0056b3;
            transform: translateX(-2px);
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
        }
        .bv-content-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #374151;
            margin: 0;
            text-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        /* Section body */
        .bv-section-body {
            padding: 24px;
        }
        .bv-owner-chip .material-icons { font-size: 13px !important; color: #6c757d; }
        .bv-addr-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        .bv-field-value {
            font-size: 0.875rem;
            color: #374151;
            margin: 0;
        }

        /* Content card styling */
        .bv-content-card {
            border: 1px solid #e3e6f0;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        }

        /* Enhanced form styling */
        .bv-section-body .form-control {
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 0.875rem;
            background-color: #fff;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .bv-section-body .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1), 0 2px 8px rgba(0,0,0,0.08);
            outline: 0;
            background-color: #fafbff;
        }
        .bv-section-body .form-control:hover:not(:focus) {
            border-color: #c6d0f5;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .bv-section-body .form-control::placeholder {
            color: #adb5bd;
            font-size: 0.875rem;
            font-style: italic;
        }
        .bv-section-body select.form-control {
            padding: 12px 40px 12px 16px;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px 12px;
            cursor: pointer;
        }

        /* Enhanced field labels */
        .bv-field-label {
            font-size: 0.75rem;
            color: #495057;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: block;
            margin-bottom: 8px;
            position: relative;
            padding-bottom: 6px;
        }
        .bv-field-label::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 32px;
            height: 3px;
            background: linear-gradient(90deg, #007bff 0%, #0056b3 50%, #28a745 100%);
            border-radius: 2px;
            box-shadow: 0 1px 3px rgba(0, 123, 255, 0.3);
            animation: blueLine 2s ease-in-out infinite alternate;
        }
        @keyframes blueLine {
            0% { 
                width: 32px;
                box-shadow: 0 1px 3px rgba(0, 123, 255, 0.3);
            }
            100% { 
                width: 40px;
                box-shadow: 0 2px 6px rgba(0, 123, 255, 0.5);
            }
        }

        /* Special enhanced lines for required fields */
        .bv-field-label:contains('*')::after {
            background: linear-gradient(90deg, #007bff 0%, #dc3545 100%);
            animation: requiredField 1.5s ease-in-out infinite alternate;
        }
        @keyframes requiredField {
            0% { 
                background: linear-gradient(90deg, #007bff 0%, #dc3545 100%);
                width: 32px;
            }
            100% { 
                background: linear-gradient(90deg, #0056b3 0%, #c82333 100%);
                width: 36px;
            }
        }

        /* Enhanced save buttons */
        .btn-enhanced-save {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 500;
            font-size: 0.875rem;
            color: white;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
            transition: all 0.2s ease-in-out;
            position: relative;
            overflow: hidden;
        }
        .btn-enhanced-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
        }
        .btn-enhanced-save:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
        }
        .btn-enhanced-save:disabled {
            opacity: 0.7;
            transform: none;
            cursor: not-allowed;
        }

        /* Success state */
        .btn-enhanced-success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        }

        /* Form validation feedback */
        .form-control.is-valid {
            border-color: #28a745;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='m2.3 6.73.94-.94-.94-.94-1.36 1.36L2.3 6.73z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px 16px;
        }
        .form-control.is-invalid {
            border-color: #dc3545;
        }

        /* Loading overlay */
        .bv-section-loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            z-index: 10;
        }
        .bv-section-loading .spinner {
            width: 32px;
            height: 32px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Section headers with icons */
        .bv-section-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #007bff;
            padding: 20px 24px;
            border-radius: 8px 8px 0 0;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            overflow: hidden;
        }
        .bv-section-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #007bff 0%, #28a745 100%);
        }
        .bv-section-header .material-icons {
            font-size: 22px;
            color: #007bff;
            background: rgba(0, 123, 255, 0.1);
            padding: 8px;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.2);
        }
        .bv-section-header h6 {
            margin: 0;
            font-weight: 600;
            color: #495057;
            font-size: 1rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        /* Two-column responsive layout improvement */
        .bv-form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .bv-form-grid-full {
            grid-column: 1 / -1;
        }

        /* Progress indicator */
        .bv-progress-indicator {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: #e9ecef;
            z-index: 1000;
        }
        .bv-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #007bff, #0056b3);
            transition: width 0.3s ease;
            width: 0%;
        }

        /* Floating action button style for save */
        .bv-floating-save {
            position: sticky;
            bottom: 24px;
            float: right;
            clear: both;
            margin-top: 24px;
        }

        /* Text display fixes */
        .bv-section-body h6,
        .bv-section-body label,
        .bv-section-body .form-text {
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Ensure proper text rendering */
        .bv-field-label,
        .bv-section-header h6 {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        /* Fix any potential text overlap */
        .bv-form-grid > div {
            position: relative;
            z-index: 1;
        }
    </style>
</head>

<body class="sidebar-mini layout-fixed" style="height: auto;">
    <div class="wrapper">

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
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="user-panel text-white">
                                <div class="image">
                                    <img src="../../dist/img/splogo.png" class="img-circle elevation-1" alt="User Image">
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="background-color: #495057 !important">
                            <div class="user-panel d-flex">
                                <div class="image">
                                    <img src="../../dist/img/splogo.png" class="img-circle elevation-2" alt="User Image">
                                </div>
                                <div class="info">
                                    <a href="#" class="d-block text-white">User Name</a>
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

        <!-- Sidebar -->
        <?php include '../sidebar/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper bv-page-content">
            <!-- Progress Indicator -->
            <div class="bv-progress-indicator">
                <div class="bv-progress-bar" id="progressBar"></div>
            </div>
            
            <div class="content pt-4 pb-2">
                <div class="container-fluid">

                    <!-- Back to MSME List -->
                    <div class="mb-3">
                        <a href="/NegosyoCenter/pages/msme/msme.php" class="bv-back-to-list">
                            <i class="material-icons" style="font-size:18px;vertical-align:middle;">arrow_back</i>
                            Back to MSME List
                        </a>
                    </div>

                    <!-- Loading state -->
                    <div id="bvLoading">
                        <div class="bv-loading">
                            <div class="bv-loading-icon">
                                <i class="material-icons">storefront</i>
                            </div>
                            <span class="bv-loading-text">Loading Business Info, please wait...</span>
                        </div>
                    </div>

                    <!-- Not found state -->
                    <div id="bvNotFound" class="d-none">
                        <div class="bv-loading">
                            <div class="bv-loading-icon">
                                <i class="material-icons">error_outline</i>
                            </div>
                            <span class="bv-loading-text">Business not found or invalid ID provided</span>
                        </div>
                    </div>

                    <!-- Two-panel layout (hidden until data loaded) -->
                    <div class="row d-none" id="bvMain">

                        <!-- ── LEFT PANEL ──────────────────────────────── -->
                        <div class="col-md-4 col-lg-3 mb-3">

                            <div class="card bv-profile-card mb-3">
                                <div class="card-body pt-4 pb-3">
                                    <div class="bv-business-icon">
                                        <i class="material-icons">storefront</i>
                                    </div>
                                    <div class="bv-business-name mb-1" id="bvBusinessName">—</div>
                                    <div class="text-center mb-3">
                                        <span class="bv-entity-chip">
                                            <i class="material-icons">tag</i>
                                            <span id="bvEntityNo">—</span>
                                        </span>
                                    </div>
                                    <ul class="list-group list-group-unbordered bv-info-list">
                                        <li class="list-group-item">
                                            <b>Owner</b>
                                            <span class="float-right" id="bvOwner">—</span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Capitalization</b>
                                            <span class="float-right" id="bvCapitalization">—</span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Contact No.</b>
                                            <span class="float-right" id="bvContactNo">—</span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Email</b>
                                            <span class="float-right" id="bvEmail">—</span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Business Status</b>
                                            <span class="float-right" id="bvBusStatus">—</span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Application Status</b>
                                            <span class="float-right" id="bvAppStatus">—</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card bv-address-card">
                                <div class="card-body">
                                    <p class="bv-address-label">
                                        <i class="material-icons" style="font-size:13px;vertical-align:middle;color:#adb5bd;">storefront</i>
                                        Business Address
                                    </p>
                                    <p class="bv-address-value" id="bvBusAddressShort">—</p>
                                    <hr class="bv-section-divider">
                                    <p class="bv-address-label">
                                        <i class="material-icons" style="font-size:13px;vertical-align:middle;color:#adb5bd;">home</i>
                                        Owner Address
                                    </p>
                                    <p class="bv-address-value" id="bvOwnerAddressShort">—</p>
                                </div>
                            </div>

                        </div>
                        <!-- ── END LEFT PANEL ─────────────────────────── -->

                        <!-- ── RIGHT PANEL ────────────────────────────── -->
                        <div class="col-md-8 col-lg-9 mb-3">

                            <div class="card bv-menu-card" id="bvMenuList">
                                <div class="list-group list-group-flush" style="border-radius:8px;">

                                    <div class="bv-menu-item list-group-item list-group-item-action"
                                         data-target="bvSectionClassification" data-title="Classification">
                                        <div class="d-flex align-items-center">
                                            <span class="bv-menu-icon"><i class="material-icons">category</i></span>
                                            <span>Classification</span>
                                        </div>
                                        <span class="bv-chevron"><i class="material-icons">chevron_right</i></span>
                                    </div>

                                    <div class="bv-menu-item list-group-item list-group-item-action"
                                         data-target="bvSectionOwner" data-title="Owner Details">
                                        <div class="d-flex align-items-center">
                                            <span class="bv-menu-icon"><i class="material-icons">person</i></span>
                                            <span>Owner Details</span>
                                        </div>
                                        <span class="bv-chevron"><i class="material-icons">chevron_right</i></span>
                                    </div>

                                    <div class="bv-menu-item list-group-item list-group-item-action"
                                         data-target="bvSectionAddress" data-title="Business Address">
                                        <div class="d-flex align-items-center">
                                            <span class="bv-menu-icon"><i class="material-icons">location_on</i></span>
                                            <span>Business Address</span>
                                        </div>
                                        <span class="bv-chevron"><i class="material-icons">chevron_right</i></span>
                                    </div>

                                </div>
                            </div>

                            <!-- Content card -->
                            <div class="card bv-content-card d-none" id="bvContentCard">
                                <div class="bv-content-header">
                                    <button class="bv-back-btn" id="bvContentBack">
                                        <i class="material-icons" style="font-size:20px;vertical-align:middle;">chevron_left</i>
                                    </button>
                                    <h4 class="bv-content-title" id="bvContentTitle">Section</h4>
                                </div>

                                <!-- Classification -->
                                <div id="bvSectionClassification" class="bv-section-body bv-content-section d-none">
                                    <div class="bv-section-header">
                                        <i class="material-icons">category</i>
                                        <h6>Business Classification Information</h6>
                                    </div>
                                    <div class="p-4">
                                        <div class="bv-form-grid">
                                            <div>
                                                <label class="bv-field-label">Enterprise Class *</label>
                                                <select class="form-control" id="bvClassificationInput" required>
                                                    <option value="">Select Enterprise Classification</option>
                                                    <option value="micro">Micro Enterprise</option>
                                                    <option value="small">Small Enterprise</option>
                                                    <option value="medium">Medium Enterprise</option>
                                                    <option value="large">Large Enterprise</option>
                                                </select>
                                                <div class="invalid-feedback">Please select an enterprise class.</div>
                                            </div>
                                            <div>
                                                <label class="bv-field-label">Sector / Product Line *</label>
                                                <input type="text" class="form-control" id="bvSectorInput" 
                                                       placeholder="e.g., Manufacturing, Services, Trading" required>
                                                <div class="invalid-feedback">Please enter the sector or product line.</div>
                                            </div>
                                            <div class="bv-form-grid-full">
                                                <label class="bv-field-label">Special Sector</label>
                                                <input type="text" class="form-control" id="bvSpecialSectorInput" 
                                                       placeholder="e.g., Women-owned, Youth-owned, PWD-owned (Optional)">
                                                <small class="form-text text-muted">Special categories for targeted programs and incentives</small>
                                            </div>
                                        </div>
                                        <div class="bv-floating-save">
                                            <button type="button" class="btn btn-enhanced-save" onclick="saveSection('classification')">
                                                <i class="material-icons mr-2" style="font-size:18px;">save</i>Save Classification
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Owner Details -->
                                <div id="bvSectionOwner" class="bv-section-body bv-content-section d-none">
                                    <div class="bv-section-header">
                                        <i class="material-icons">person</i>
                                        <h6>Owner & Personal Information</h6>
                                    </div>
                                    <div class="p-4">
                                        <div class="mb-4">
                                            <span class="bv-owner-chip">
                                                <i class="material-icons">tag</i>
                                                Owner Entity No: <strong id="bvOwnerEntityNo">—</strong>
                                            </span>
                                        </div>
                                        
                                        <!-- Personal Information -->
                                        <div class="mb-4">
                                            <h6 class="text-muted mb-3">
                                                <i class="material-icons" style="font-size:16px;vertical-align:middle;">person</i>
                                                Personal Information
                                            </h6>
                                            <div class="bv-form-grid">
                                                <div>
                                                    <label class="bv-field-label">Full Name *</label>
                                                    <input type="text" class="form-control" id="bvOwnerNameInput" 
                                                           placeholder="Enter owner's complete legal name" required>
                                                    <div class="invalid-feedback">Please enter the owner's full name.</div>
                                                </div>
                                                <div>
                                                    <label class="bv-field-label">Special Category</label>
                                                    <select class="form-control" id="bvSpecialCategoryInput">
                                                        <option value="">Select Category (Optional)</option>
                                                        <option value="Women-owned">Women-owned Business</option>
                                                        <option value="Youth-owned">Youth-owned Business</option>
                                                        <option value="PWD-owned">Person with Disability-owned</option>
                                                        <option value="Senior Citizen-owned">Senior Citizen-owned</option>
                                                        <option value="Indigenous People">Indigenous People</option>
                                                        <option value="Cooperative">Cooperative</option>
                                                        <option value="OFW-owned">OFW-owned Business</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please select a special category.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Address Information -->
                                        <div class="mb-4">
                                            <h6 class="text-muted mb-3">
                                                <i class="material-icons" style="font-size:16px;vertical-align:middle;">home</i>
                                                Owner's Residence Address
                                            </h6>
                                            <div class="bv-form-grid">
                                                <div>
                                                    <label class="bv-field-label">Region *</label>
                                                    <input type="text" class="form-control" id="bvOwnerRegionInput" 
                                                           placeholder="e.g., Region VII - Central Visayas" required>
                                                </div>
                                                <div>
                                                    <label class="bv-field-label">Province *</label>
                                                    <input type="text" class="form-control" id="bvOwnerProvinceInput" 
                                                           placeholder="e.g., Negros Occidental" required>
                                                </div>
                                                <div>
                                                    <label class="bv-field-label">City / Municipality *</label>
                                                    <input type="text" class="form-control" id="bvOwnerCityInput" 
                                                           placeholder="e.g., San Carlos City" required>
                                                </div>
                                                <div>
                                                    <label class="bv-field-label">Barangay *</label>
                                                    <input type="text" class="form-control" id="bvOwnerBarangayInput" 
                                                           placeholder="e.g., Barangay 1" required>
                                                </div>
                                                <div>
                                                    <label class="bv-field-label">Street Address</label>
                                                    <input type="text" class="form-control" id="bvOwnerStreetInput" 
                                                           placeholder="House number and street name">
                                                </div>
                                                <div>
                                                    <label class="bv-field-label">Subdivision</label>
                                                    <input type="text" class="form-control" id="bvOwnerSubdivisionInput" 
                                                           placeholder="Subdivision or village name">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bv-floating-save">
                                            <button type="button" class="btn btn-enhanced-save" onclick="saveSection('owner')">
                                                <i class="material-icons mr-2" style="font-size:18px;">save</i>Save Owner Details
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Business Address -->
                                <div id="bvSectionAddress" class="bv-section-body bv-content-section d-none">
                                    <div class="bv-section-header">
                                        <i class="material-icons">location_on</i>
                                        <h6>Business Location & Address</h6>
                                    </div>
                                    <div class="p-4">
                                        <div class="alert alert-info mb-4" style="border-left: 4px solid #007bff;">
                                            <i class="material-icons" style="font-size:16px;vertical-align:middle;">info</i>
                                            <strong>Important:</strong> Ensure this address matches your business permit and official documents.
                                        </div>
                                        
                                        <div class="bv-form-grid">
                                            <div>
                                                <label class="bv-field-label">Region *</label>
                                                <input type="text" class="form-control" id="bvBusRegionInput" 
                                                       placeholder="e.g., Region VII - Central Visayas" required>
                                                <div class="invalid-feedback">Please enter the business region.</div>
                                            </div>
                                            <div>
                                                <label class="bv-field-label">Province *</label>
                                                <input type="text" class="form-control" id="bvBusProvinceInput" 
                                                       placeholder="e.g., Negros Occidental" required>
                                                <div class="invalid-feedback">Please enter the business province.</div>
                                            </div>
                                            <div>
                                                <label class="bv-field-label">City / Municipality *</label>
                                                <input type="text" class="form-control" id="bvBusCityInput" 
                                                       placeholder="e.g., San Carlos City" required>
                                                <div class="invalid-feedback">Please enter the business city or municipality.</div>
                                            </div>
                                            <div>
                                                <label class="bv-field-label">Barangay *</label>
                                                <input type="text" class="form-control" id="bvBusBarangayInput" 
                                                       placeholder="e.g., Barangay 1" required>
                                                <div class="invalid-feedback">Please enter the business barangay.</div>
                                            </div>
                                            <div>
                                                <label class="bv-field-label">Street Address</label>
                                                <input type="text" class="form-control" id="bvBusStreetInput" 
                                                       placeholder="Building number, street name">
                                            </div>
                                            <div>
                                                <label class="bv-field-label">Subdivision / Building</label>
                                                <input type="text" class="form-control" id="bvBusSubdivisionInput" 
                                                       placeholder="Subdivision, mall, or building name">
                                            </div>
                                            <div class="bv-form-grid-full">
                                                <label class="bv-field-label">UPBLB No.</label>
                                                <input type="text" class="form-control" id="bvBusUpblbInput" 
                                                       placeholder="Urban Planning and Building License Bureau Number">
                                                <small class="form-text text-muted">Required for certain business types and locations</small>
                                            </div>
                                        </div>

                                        <div class="bv-floating-save">
                                            <button type="button" class="btn btn-enhanced-save" onclick="saveSection('address')">
                                                <i class="material-icons mr-2" style="font-size:18px;">save</i>Save Business Address
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /.bv-content-card -->

                        </div>
                        <!-- ── END RIGHT PANEL ────────────────────────── -->

                    </div>
                    <!-- /#bvMain -->

                </div>
            </div>
        </div>

        <!-- Overlay for loading states -->
        <div class="overlay" id="myOverlay">
            <div class="overlay-content">
                <img src="../../dist/img/load.gif" class="imageSpinner" alt="" srcset="">
            </div>
        </div>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>

        <!-- Main Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                All rights reserved
            </div>
            <strong>Copyright &copy; 2024 ITCSO. <a href="http://lguscc.gov.ph/">Local Government of San Carlos City</a></strong>.
        </footer>
    </div>

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- BS-Stepper -->
    <script src="../../plugins/bs-stepper/js/bs-stepper.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
    <!-- Additional plugins -->
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
    <!-- new -->
    <script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>
    <script src="../../plugins/select2/js/select2.min.js"></script>
    <!-- Business view page specific scripts -->
    <script src="../../scripts/common/address.js"></script>
    <script src="../../scripts/common/currency.js"></script>
    <script src="../../scripts/msme/business-view.js"></script>

    <script>
        // Logout function
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '../../index.php';
            }
        }
    </script>

</body>

</html>