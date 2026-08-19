<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>San Carlos City | MSME Dashboard</title>

    <!-- Google Font: Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <!-- Shared custom styles (badges, buttons, cards) -->
    <link rel="stylesheet" href="../../dist/css/user_defined.css?v=5">

    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">

    <style>
        body { font-family: 'Roboto', sans-serif; }

        /* ── Page heading ── */
        .dash-heading       { font-weight: 700; font-size: 1.15rem; color: #1a1a2e; margin-bottom: 2px; }
        .dash-subheading    { color: #9ca3af; font-size: .8rem; }

        /* ── Filter card ── */
        .dash-filter-label  {
            font-size: .72rem; font-weight: 500;
            text-transform: uppercase; letter-spacing: .05em;
            color: #9ca3af; margin-bottom: 4px;
        }
        .dash-filter-select {
            font-size: .875rem; border-radius: 5px; border-color: #ced4da;
        }
        .dash-filter-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,.18);
        }

        /* ── Stat cards ── */
        .stat-card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,.10), 0 1px 3px rgba(0,0,0,.07);
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0,0,0,.13);
        }
        .stat-card-icon {
            width: 52px; height: 52px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; color: #fff; flex-shrink: 0;
        }
        .stat-card-value {
            font-size: 2rem; font-weight: 700; line-height: 1; color: #1a1a2e;
        }
        .stat-card-label {
            font-size: .72rem; font-weight: 500; text-transform: uppercase;
            letter-spacing: .06em; color: #9ca3af; margin-top: 3px;
        }

        /* Left-border accent per classification — mirrors msme-badge colours */
        .stat-accent-total  { border-left: 4px solid #007bff !important; }
        .stat-accent-micro  { border-left: 4px solid #084298 !important; }
        .stat-accent-small  { border-left: 4px solid #0a3622 !important; }
        .stat-accent-medlg  { border-left: 4px solid #7d3a00 !important; }

        .icon-bg-total  { background: #007bff; }
        .icon-bg-micro  { background: #084298; }
        .icon-bg-small  { background: #198754; }
        .icon-bg-medlg  { background: #e67e22; }

        /* ── Chart panels ── */
        .chart-panel {
            border: none; border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,.10), 0 1px 3px rgba(0,0,0,.07);
        }
        .chart-panel-header {
            border-bottom: 1px solid #e9ecef;
            padding: .7rem 1.1rem;
            font-size: .78rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: .05em; color: #495057;
            border-radius: 8px 8px 0 0;
            display: flex; align-items: center; justify-content: space-between;
        }
        .chart-panel-body   { padding: 1rem 1.1rem 1.2rem; }

        /* Loading pulse */
        .loading-pulse { animation: ldpulse 1.1s ease-in-out infinite; }
        @keyframes ldpulse { 0%,100%{opacity:1} 50%{opacity:.3} }

        /* No-data placeholder */
        .no-data-msg {
            display: flex; align-items: center; justify-content: center;
            min-height: 180px; color: #adb5bd; font-size: .875rem; gap: 6px;
        }
    </style>
</head>

<body class="sidebar-mini layout-fixed" style="height:auto;">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img src="../../dist/img/itcsologo.webp" alt="Loading" height="60" width="60">
    </div>

    <!-- ── Navbar ─────────────────────────────────────────────────────── -->
    <nav class="main-header navbar sticky-top navbar-expand navbar-dark">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="material-icons" style="font-size:20px;vertical-align:middle;">menu</i>
                </a>
            </li>
        </ul>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="material-icons text-white" style="font-size:20px;vertical-align:middle;">fullscreen</i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link pt-0 pb-0" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false" role="button">
                        <img src="../../dist/img/default.jfif"
                             class="img-circle portrait-sidebar elevation-2" alt="User">
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
                        <a class="nav-link text-sm" style="padding-left:13px;"
                           onclick="logout()" role="button">
                            <i class="material-icons"
                               style="font-size:18px;vertical-align:middle;
                                      background:rgba(16,16,16,.42);border-radius:22px;padding:6px;">
                                logout
                            </i>&nbsp;Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Sidebar -->
    <?php include '../../pages/sidebar/sidebar.php'; ?>

    <!-- ── Content wrapper ───────────────────────────────────────────── -->
    <div class="content-wrapper">
        <div class="content pt-4 pb-4">
            <div class="container-fluid">

                <!-- Page heading -->
                <div class="mb-3">
                    <p class="dash-heading mb-0">
                        <i class="material-icons align-middle mr-1"
                           style="font-size:22px;color:#007bff;vertical-align:middle;">dashboard</i>
                        MSME Dashboard
                    </p>
                    <small class="dash-subheading">
                        Reports and statistics overview &mdash; San Carlos City Negosyo Center
                    </small>
                </div>

                <!-- ── Filter bar ─────────────────────────────────────────── -->
                <div class="card card-raised mb-4">
                    <div class="card-body px-4 py-3">
                        <div class="row align-items-end">

                            <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                                <div class="dash-filter-label">Classification</div>
                                <select class="form-control dash-filter-select" id="filterClassification">
                                    <option value="">All classifications</option>
                                    <option value="Micro">Micro</option>
                                    <option value="Small">Small</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Large">Large</option>
                                </select>
                            </div>

                            <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                                <div class="dash-filter-label">Product / Sector</div>
                                <select class="form-control dash-filter-select" id="filterSector">
                                    <option value="">All sectors</option>
                                    <!-- populated on load from server -->
                                </select>
                            </div>

                            <div class="col-md-2 col-sm-6 mb-2 mb-md-0">
                                <div class="dash-filter-label">Status</div>
                                <select class="form-control dash-filter-select" id="filterStatus">
                                    <option value="">All statuses</option>
                                    <option value="ACTIVE">Active</option>
                                    <option value="INACTIVE">Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
                                <div class="dash-filter-label">Actions</div>
                                <div class="d-flex" style="gap:8px;">
                                    <button class="btn btn-raised-primary btn-sm d-flex align-items-center"
                                            id="btnApply">
                                        <i class="material-icons mr-1" style="font-size:16px;">search</i>Apply
                                    </button>
                                    <button class="btn btn-raised-secondary btn-sm d-flex align-items-center"
                                            id="btnReset" title="Reset filters">
                                        <i class="material-icons" style="font-size:16px;">refresh</i>
                                    </button>
                                    <div class="dropdown ml-1">
                                        <button class="btn btn-raised-success btn-sm d-flex align-items-center
                                                        dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false" id="btnExport">
                                            <i class="material-icons mr-1" style="font-size:16px;">download</i>
                                            Report
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#" id="exportCSV">
                                                <i class="material-icons align-middle mr-2"
                                                   style="font-size:16px;color:#198754;">table_view</i>
                                                Export as Excel / CSV
                                            </a>
                                            <a class="dropdown-item" href="#" id="exportPDF">
                                                <i class="material-icons align-middle mr-2"
                                                   style="font-size:16px;color:#dc3545;">picture_as_pdf</i>
                                                Export as PDF
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ── Stat cards ─────────────────────────────────────────── -->
                <div class="row mb-4">

                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card stat-card stat-accent-total h-100">
                            <div class="card-body d-flex align-items-center" style="gap:16px;">
                                <div class="stat-card-icon icon-bg-total">
                                    <i class="material-icons">store</i>
                                </div>
                                <div>
                                    <div class="stat-card-value loading-pulse" id="statTotal">—</div>
                                    <div class="stat-card-label">Total MSMEs</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card stat-card stat-accent-micro h-100">
                            <div class="card-body d-flex align-items-center" style="gap:16px;">
                                <div class="stat-card-icon icon-bg-micro">
                                    <i class="material-icons">storefront</i>
                                </div>
                                <div>
                                    <div class="stat-card-value loading-pulse" id="statMicro">—</div>
                                    <div class="stat-card-label">Micro</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card stat-card stat-accent-small h-100">
                            <div class="card-body d-flex align-items-center" style="gap:16px;">
                                <div class="stat-card-icon icon-bg-small">
                                    <i class="material-icons">business</i>
                                </div>
                                <div>
                                    <div class="stat-card-value loading-pulse" id="statSmall">—</div>
                                    <div class="stat-card-label">Small</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card stat-card stat-accent-medlg h-100">
                            <div class="card-body d-flex align-items-center" style="gap:16px;">
                                <div class="stat-card-icon icon-bg-medlg">
                                    <i class="material-icons">domain</i>
                                </div>
                                <div>
                                    <div class="stat-card-value loading-pulse" id="statMedLg">—</div>
                                    <div class="stat-card-label">Medium + Large</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ── Charts ─────────────────────────────────────────────── -->
                <div class="row">

                    <!-- Businesses by Classification -->
                    <div class="col-lg-5 mb-4">
                        <div class="card chart-panel h-100">
                            <div class="chart-panel-header">
                                <span>
                                    <i class="material-icons align-middle mr-1"
                                       style="font-size:17px;color:#007bff;">bar_chart</i>
                                    By Classification
                                </span>
                                <span class="badge badge-pill msme-badge-unknown"
                                      id="badgeClassTotal">0 records</span>
                            </div>
                            <div class="chart-panel-body" id="wrapChartClass">
                                <canvas id="chartClassification" height="240"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Top Product / Sectors -->
                    <div class="col-lg-7 mb-4">
                        <div class="card chart-panel h-100">
                            <div class="chart-panel-header">
                                <span>
                                    <i class="material-icons align-middle mr-1"
                                       style="font-size:17px;color:#007bff;">insights</i>
                                    Top Product / Sectors
                                </span>
                                <span class="badge badge-pill msme-badge-unknown"
                                      id="badgeSectorTotal">0 sectors</span>
                            </div>
                            <div class="chart-panel-body" id="wrapChartSector">
                                <canvas id="chartSectors" height="240"></canvas>
                            </div>
                        </div>
                    </div>

                </div>

            </div><!-- /container-fluid -->
        </div><!-- /content -->
    </div><!-- /content-wrapper -->

    <!-- Control sidebar -->
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

<!-- ── Scripts ─────────────────────────────────────────────────────── -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<!-- Chart.js (CDN — same version already used in msme.php) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<!-- Dashboard logic -->
<script src="../../scripts/msme/msme-dashboard.js"></script>

</body>
</html>
