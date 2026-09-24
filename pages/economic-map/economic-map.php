<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>San Carlos City | Economic Map</title>

    <!-- Google Font: Roboto (Material) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Select2 -->
    <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <!-- Shared custom styles -->
    <link rel="stylesheet" href="../../dist/css/user_defined.css?v=5">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin="">

    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">

    <style>
        /* ── Map containers ── */
        #mapHotspot,
        #mapDistribution,
        #mapRisk,
        #mapOpportunity { width: 100%; height: 580px; z-index: 1; }

        /* ── Map card header ── */
        .map-card-header {
            padding: .75rem 1.1rem;
            font-size: .78rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .06em; color: #fff;
            background: linear-gradient(90deg, #1a3a6b 0%, #1e4db7 100%);
            display: flex; align-items: center; justify-content: space-between;
        }

        /* ── Tabs ── */
        .emap-tabs { border-bottom: 2px solid #e2e8f0; }
        .emap-tabs .nav-link {
            font-size: .83rem; font-weight: 600; color: #64748b;
            border-radius: 8px 8px 0 0; padding: .65rem 1.1rem;
            border: none; border-bottom: 2px solid transparent; margin-bottom: -2px;
            transition: color .15s, border-color .15s;
        }
        .emap-tabs .nav-link:hover { color: #2563eb; }
        .emap-tabs .nav-link.active {
            color: #2563eb; background: #fff;
            border-bottom-color: #2563eb;
        }
        .emap-tabs .nav-link i { font-size: 17px; vertical-align: middle; margin-right: 5px; }

        /* ── Map cards ── */
        .map-card {
            border: none; border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,.10);
            overflow: hidden;
        }

        /* ── Map legend ── */
        .map-legend {
            background: rgba(255,255,255,.95); border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,.18); padding: 10px 13px;
            line-height: 2; font-size: .77rem;
        }
        .map-legend h6 { font-size: .7rem; font-weight: 700; text-transform: uppercase;
                          letter-spacing: .05em; color: #64748b; margin-bottom: 4px; }
        .legend-dot { display: inline-block; width: 11px; height: 11px; border-radius: 50%;
                      margin-right: 6px; vertical-align: middle; }
        .legend-circle { display: inline-block; border-radius: 50%; margin-right: 6px;
                         vertical-align: middle; background: rgba(220,53,69,.45);
                         border: 1px solid #dc3545; }

        /* ── Stat pills ── */
        .stat-pill {
            border: none; border-left: 4px solid #2563eb !important;
            border-radius: 10px !important; box-shadow: 0 2px 8px rgba(0,0,0,.07);
        }
        .stat-pill .value { font-size: 1.65rem; font-weight: 700; line-height: 1; color: #1e293b; }
        .stat-pill .label { font-size: .95rem; font-weight: 600; color: #64748b; line-height: 1.3; }

        /* ── Sector filter (simple organized list) ── */
        .sector-search {
            display: flex; align-items: center; gap: 6px;
            border: 1px solid #e2e8f0; border-radius: 8px;
            padding: .35rem .6rem; background: #f8fafc;
        }
        .sector-search i { font-size: 16px; color: #9ca3af; flex-shrink: 0; }
        .sector-search input {
            border: none; outline: none; background: transparent;
            font-size: .8rem; width: 100%; color: #1e293b;
        }
        .sector-all-btn {
            display: flex; align-items: center; gap: 7px; width: 100%;
            font-size: .78rem; font-weight: 700; color: #343a40;
            background: #f1f3f5; border: 1px solid #e2e8f0; border-radius: 8px;
            padding: .45rem .65rem; margin-bottom: 6px; cursor: pointer;
            transition: background .12s, border-color .12s;
        }
        .sector-all-btn i { font-size: 16px; color: #64748b; }
        .sector-all-btn:hover { background: #e9ecef; }
        .sector-all-btn.active { background: #1e4db7; border-color: #1e4db7; color: #fff; }
        .sector-all-btn.active i { color: #fff; }
        .sector-all-btn.dimmed { opacity: .55; }
        .sector-list {
            max-height: 300px; overflow-y: auto;
            border: 1px solid #e2e8f0; border-radius: 8px; background: #fff;
        }
        .sector-row {
            display: flex; align-items: center; gap: 8px; width: 100%;
            padding: .45rem .65rem; cursor: pointer; text-align: left;
            background: #fff; border: none; border-bottom: 1px solid #f1f5f9;
            font-size: .75rem; transition: background .12s;
        }
        .sector-row:last-child { border-bottom: none; }
        .sector-row:hover { background: #eff6ff; }
        .sector-row.active { background: #eff6ff; box-shadow: inset 3px 0 0 #1e4db7; }
        .sector-row .sector-name { flex: 1; font-weight: 600; color: #1e293b; line-height: 1.35; }
        .sector-row.active .sector-name { color: #1e4db7; }
        .sector-row .sector-count {
            font-size: .68rem; font-weight: 700; color: #64748b;
            background: #f1f5f9; border-radius: 20px; padding: 1px 8px;
            white-space: nowrap; flex-shrink: 0;
        }
        .sector-row.active .sector-count { background: #1e4db7; color: #fff; }
        .sector-empty { padding: .7rem; font-size: .78rem; color: #9ca3af; text-align: center; }

        /* ── Pie chart / legend spacing ── */
        @media (min-width: 768px) {
            .dist-pie-legend-col {
                padding-top: 6px;
                padding-bottom: 6px;
            }
            /* Visible line between the two sector columns */
            #distPieLegend > .row { position: relative; }
            #distPieLegend > .row::before {
                content: ''; position: absolute; top: 2px; bottom: 2px; left: 50%;
                border-left: 1px solid #dee2e6;
            }
            /* Breathing space between the two sector columns */
            #distPieLegend > .row > [class*="col-"] {
                padding-left: 26px;
                padding-right: 26px;
            }
        }

        /* ── Breakdown table ── */
        .brgy-breakdown { font-size: .82rem; width: 100%; }
        .brgy-breakdown td { padding: 3px 6px; }
        .breakdown-bar { height: 7px; border-radius: 4px; display: block; min-width: 2px; }

        /* ── Misc cards ── */
        .pressure-card {
            border: 1px solid #e2e8f0; border-left: 4px solid #64748b;
            border-radius: 8px; padding: .55rem .8rem; margin-bottom: .55rem;
            background: #fff; transition: box-shadow .15s;
        }
        .pressure-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .pressure-card .pc-name { font-size: .79rem; font-weight: 600; color: #1e293b; }
        .pressure-card .pc-agency { font-size: .66rem; color: #64748b; }
        .pressure-card .pc-metric { font-size: .73rem; color: #495057; }

        .highlight-item {
            display: flex; align-items: center; gap: 9px;
            padding: .4rem .6rem; border-radius: 8px; margin-bottom: .35rem;
            background: #f0f9ff; font-size: .77rem; border: 1px solid #bae6fd;
        }
        .highlight-item i { color: #16a34a; font-size: 16px; }
        .highlight-item .hi-label { color: #64748b; }
        .highlight-item .hi-barangay { font-weight: 700; color: #1e293b; }

        /* ── Hotspot ranking ── */
        .hotspot-rank-item {
            display: flex; align-items: center; gap: 8px;
            padding: .42rem .6rem; border-radius: 8px; margin-bottom: .3rem;
            background: #f8fafc; border: 1px solid #e2e8f0;
            cursor: pointer; transition: background .12s, box-shadow .12s;
            font-size: .78rem;
        }
        .hotspot-rank-item:hover { background: #eff6ff; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .hotspot-rank-item .rank-num {
            font-weight: 700; color: #fff; background: #64748b;
            border-radius: 50%; min-width: 22px; height: 22px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: .7rem; flex-shrink: 0;
        }
        .hotspot-rank-item.rank-1 .rank-num { background: #7b1fa2; }
        .hotspot-rank-item.rank-2 .rank-num { background: #dc3545; }
        .hotspot-rank-item.rank-3 .rank-num { background: #fd7e14; }
        .hotspot-rank-item .rank-name { flex: 1; font-weight: 600; color: #1e293b; }
        .hotspot-rank-item .rank-count { font-weight: 700; color: #1e293b; white-space: nowrap; }

        /* ── Ranking dropdown (modern custom dropdown) ── */
        .modern-dd { position: relative; margin-bottom: 4px; }
        .modern-dd-btn {
            display: flex; align-items: center; gap: 10px; width: 100%;
            border: 1.5px solid #e2e8f0; border-radius: 12px;
            background: linear-gradient(135deg, #ffffff 0%, #f1f5ff 100%);
            padding: .62rem .85rem; cursor: pointer; text-align: left;
            box-shadow: 0 1px 3px rgba(15,23,42,.06);
            transition: border-color .15s, box-shadow .15s, transform .1s;
        }
        .modern-dd-btn:hover { border-color: #93c5fd; box-shadow: 0 2px 8px rgba(30,77,183,.12); }
        .modern-dd-btn:active { transform: scale(.99); }
        .modern-dd.open .modern-dd-btn {
            border-color: #1e4db7;
            box-shadow: 0 0 0 3px rgba(30,77,183,.14);
        }
        .modern-dd-btn > i:first-child {
            font-size: 20px; color: #fff; flex-shrink: 0;
            background: linear-gradient(135deg, #dc3545 0%, #7b1fa2 100%);
            border-radius: 8px; padding: 3px;
        }
        .modern-dd-btn span {
            flex: 1; min-width: 0; font-size: .8rem; font-weight: 600; color: #1e293b;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.5;
        }
        .modern-dd-chevron {
            font-size: 20px !important; color: #94a3b8; flex-shrink: 0;
            transition: transform .2s ease; background: none !important; padding: 0 !important;
        }
        .modern-dd.open .modern-dd-chevron { transform: rotate(180deg); color: #1e4db7; }
        .modern-dd-panel {
            position: absolute; top: calc(100% + 6px); left: 0; right: 0; z-index: 1050;
            background: #fff; border: 1px solid #e2e8f0; border-radius: 12px;
            box-shadow: 0 12px 32px rgba(15,23,42,.16); overflow: hidden;
            animation: ddPop .16s ease;
        }
        @keyframes ddPop {
            from { opacity: 0; transform: translateY(-5px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .modern-dd-search {
            display: flex; align-items: center; gap: 6px;
            padding: .5rem .65rem; border-bottom: 1px solid #f1f5f9; background: #f8fafc;
        }
        .modern-dd-search i { font-size: 16px; color: #9ca3af; flex-shrink: 0; }
        .modern-dd-search input {
            border: none; outline: none; background: transparent;
            font-size: .78rem; width: 100%; color: #1e293b;
        }
        .modern-dd-list { max-height: 240px; overflow-y: auto; padding: 6px; }
        .modern-dd-opt {
            display: flex; align-items: center; gap: 8px; width: 100%;
            padding: .48rem .55rem; border: none; border-radius: 8px;
            background: transparent; cursor: pointer; text-align: left;
            font-size: .76rem; transition: background .12s;
        }
        .modern-dd-opt:hover { background: #eff6ff; }
        .modern-dd-opt.selected { background: #eff6ff; box-shadow: inset 3px 0 0 #1e4db7; }
        .modern-dd-opt .rank-num {
            font-weight: 700; color: #fff; background: #64748b;
            border-radius: 50%; min-width: 22px; height: 22px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: .68rem; flex-shrink: 0;
        }
        .modern-dd-opt.opt-1 .rank-num { background: linear-gradient(135deg, #7b1fa2, #4a148c); }
        .modern-dd-opt.opt-2 .rank-num { background: linear-gradient(135deg, #dc3545, #9f1239); }
        .modern-dd-opt.opt-3 .rank-num { background: linear-gradient(135deg, #fd7e14, #c2410c); }
        .modern-dd-opt .opt-name { flex: 1; min-width: 0; font-weight: 600; color: #1e293b; line-height: 1.35; }
        .modern-dd-opt.selected .opt-name { color: #1e4db7; }
        .modern-dd-opt .opt-check {
            font-size: 16px !important; color: #1e4db7; flex-shrink: 0;
            visibility: hidden; background: none !important; padding: 0 !important;
        }
        .modern-dd-opt.selected .opt-check { visibility: visible; }
        .modern-dd-opt .opt-count {
            font-size: .66rem; font-weight: 700; color: #64748b;
            background: #f1f5f9; border-radius: 20px; padding: 1px 7px;
            white-space: nowrap; flex-shrink: 0;
        }
        .modern-dd-opt.selected .opt-count { background: #1e4db7; color: #fff; }
        .modern-dd-empty { padding: .7rem; font-size: .76rem; color: #9ca3af; text-align: center; }
        .rank-detail {
            display: flex; align-items: center; gap: 10px;
            margin-top: 10px; padding: .6rem .75rem;
            background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px;
            font-size: .75rem;
        }
        .rank-detail .rank-num {
            font-weight: 700; color: #fff; background: #1e4db7;
            border-radius: 50%; min-width: 24px; height: 24px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: .72rem; flex-shrink: 0;
        }
        .rank-detail .rank-info { flex: 1; line-height: 1.4; color: #1e293b; }
        .rank-detail .rank-info b { font-size: .82rem; }

        /* ── Area search ── */
        .area-search { position: relative; max-width: 680px; }
        .area-search .input-group {
            border-radius: 12px; overflow: hidden;
            box-shadow: 0 4px 14px rgba(0,0,0,.10);
        }
        .area-search .input-group-text {
            background: #fff; border: 1px solid #e2e8f0; border-right: 0;
            padding-left: 14px; padding-right: 10px;
        }
        .area-search .form-control {
            border: 1px solid #e2e8f0; border-left: 0; border-right: 0;
            font-size: .9rem; height: 46px; transition: box-shadow .2s;
        }
        .area-search .form-control:focus { box-shadow: none; border-color: #2563eb; }
        .area-search .form-control:focus ~ .input-group-text,
        .area-search .input-group:focus-within .input-group-text,
        .area-search .input-group:focus-within .btn { border-color: #2563eb; }
        .area-search .btn {
            border: 1px solid #e2e8f0; border-left: 0;
            background: #fff; color: #64748b; border-radius: 0 12px 12px 0;
        }
        .area-search .btn:hover { background: #f8fafc; color: #dc2626; }

        /* ── Search autocomplete dropdown ── */
        .area-matches {
            position: absolute; top: calc(100% + 4px); left: 0; right: 0; z-index: 1060;
            background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,.13); max-height: 340px; overflow-y: auto;
        }
        .area-match-item {
            display: flex; align-items: center; gap: 10px;
            padding: .55rem .85rem; cursor: pointer; font-size: .83rem;
            border-bottom: 1px solid #f1f5f9; transition: background .12s;
        }
        .area-match-item:last-child { border-bottom: none; }
        .area-match-item:hover { background: #eff6ff; }
        .area-match-item .ami-icon { color: #2563eb; font-size: 17px; flex-shrink: 0; }
        .area-match-item .ami-label { flex: 1; font-weight: 500; color: #1e293b; }
        .area-match-item .ami-sub { font-size: .72rem; color: #64748b; display: block; }
        .area-match-item .ami-type {
            font-size: .58rem; text-transform: uppercase; font-weight: 700;
            letter-spacing: .06em; color: #fff; margin-left: auto; flex-shrink: 0;
            background: #2563eb; border-radius: 20px; padding: 2px 7px;
        }
        .area-match-item[data-type="barangay"] .ami-type { background: #0891b2; }

        /* ── Area summary card ── */
        #areaSummaryWrap .card { border: none; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,.08); }
        #areaSummaryWrap .card-body { padding: 1.25rem; }
        .as-stat { text-align: center; }
        .as-stat .as-value { font-size: 1.5rem; font-weight: 700; color: #1e293b; line-height: 1; }
        .as-stat .as-label { font-size: .63rem; text-transform: uppercase; letter-spacing: .05em; color: #64748b; margin-top: 2px; }

        /* ── No-data placeholder ── */
        .no-data-msg {
            display: flex; align-items: center; justify-content: center;
            min-height: 160px; color: #94a3b8; font-size: .875rem; gap: 7px;
        }

        /* ── Info note ── */
        .emap-note {
            background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px;
            padding: .5rem .85rem; font-size: .78rem; color: #64748b;
            display: flex; align-items: center; gap: 7px;
        }
        .emap-note i { color: #2563eb; font-size: 16px; flex-shrink: 0; }

        /* ── Geocoding loading indicator ── */
        #searchLocating {
            display: none; position: absolute; right: 50px; top: 50%;
            transform: translateY(-50%); z-index: 10;
        }
        .search-spinner {
            width: 18px; height: 18px; border: 2px solid #e2e8f0;
            border-top-color: #2563eb; border-radius: 50%;
            animation: spin .7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>

<body class="sidebar-mini layout-fixed" style="height: auto;">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img src="../../dist/img/itcsologo.webp" alt="Loading" height="60" width="60">
    </div>

    <!-- ── Navbar ──────────────────────────────────────────────────────── -->
    <nav class="main-header navbar sticky-top navbar-expand navbar-dark navbar-dark">
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
                    <a class="nav-link text-sm" data-widget="fullscreen" href="#" role="button">
                        <i class="material-icons text-white" style="font-size:20px;vertical-align:middle;">fullscreen</i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link text-sm pt-0 pb-0" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false" role="button">
                        <div class="image pt-0 pb-0">
                            <img src="../../dist/img/default.jfif"
                                 class="img-circle portrait-sidebar elevation-2" alt="User Image">
                        </div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink"
                         style="background-color: #495057 !important">
                        <div class="user-panel d-flex">
                            <div class="image">
                                <img src="../../dist/img/default.jfif"
                                     class="img-circle elevation-2" alt="User Image">
                            </div>
                            <div class="info">
                                <a href="#" class="d-block text-white text-sm">BEN GANAGANAG</a>
                            </div>
                        </div>
                        <hr class="mt-1 mb-1">
                        <a class="nav-link text-sm sidebar-franchise-user-panel" style="padding-left: 13px;" role="button">
                            <i class="material-icons" style="font-size:18px;vertical-align:middle;background-color:rgba(16,16,16,0.42);border-radius:22px;padding:6px;">manage_accounts</i>
                            &nbsp;Edit Profile
                        </a>
                        <a class="nav-link text-sm" style="padding-left: 13px;" onclick="logout()" role="button">
                            <i class="material-icons" style="font-size:18px;vertical-align:middle;background-color:rgba(16,16,16,0.42);border-radius:22px;padding:6px;">logout</i>
                            &nbsp;Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Sidebar -->
    <?php include '../../pages/sidebar/sidebar.php'; ?>

    <!-- ── Content wrapper ────────────────────────────────────────────── -->
    <div id="body_wrapper" class="content-wrapper">
        <div class="content pt-4 pb-2">
            <div class="container-fluid">

                <!-- ── Page header ── -->
                <div class="card card-raised mb-3">
                    <div class="card-body d-flex align-items-center justify-content-between px-4 py-3">
                        <div>
                            <h5 class="mb-0 font-weight-bold">
                                <i class="material-icons align-middle mr-1"
                                   style="font-size:22px;color:#007bff;vertical-align:middle;">map</i>
                                San Carlos City Economic Map
                            </h5>
                            <small class="text-muted">MSME-based economic activity &mdash; San Carlos City Negosyo Center</small>
                        </div>
                    </div>
                </div>

                <!-- ── Area search ──────────────────────────────────────── -->
                <div class="area-search mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white" style="border-right:0;">
                                <i class="material-icons" style="font-size:18px;color:#9ca3af;">search</i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="areaSearch"
                               placeholder="Search Barangay or Street &mdash; e.g. Barangay II, Rizal, S. Carmona"
                               autocomplete="off"
                               style="border-left:0;">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button"
                                    id="areaSearchClear" title="Clear">
                                <i class="material-icons" style="font-size:17px;">close</i>
                            </button>
                        </div>
                    </div>
                    <div id="areaMatches" class="area-matches d-none"></div>
                    <div id="searchLocating"><div class="search-spinner"></div></div>
                </div>

                <!-- ── Area summary results ─────────────────────────────── -->
                <div id="areaSummaryWrap" class="d-none mb-3">
                    <div class="card card-raised no-hover">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-0 font-weight-bold" id="asTitle">—</h6>
                                    <small class="text-muted" id="asSubtitle"></small>
                                </div>
                                <span id="asRiskBadge"></span>
                            </div>

                            <div class="row text-center mb-3">
                                <div class="col as-stat mb-2">
                                    <div class="as-value" id="asTotal">—</div>
                                    <div class="as-label">Total MSMEs</div>
                                </div>
                                <div class="col as-stat mb-2">
                                    <div class="as-value" style="color:#28a745;" id="asMicro">—</div>
                                    <div class="as-label">Micro</div>
                                </div>
                                <div class="col as-stat mb-2">
                                    <div class="as-value" style="color:#fd7e14;" id="asSmall">—</div>
                                    <div class="as-label">Small</div>
                                </div>
                                <div class="col as-stat mb-2">
                                    <div class="as-value" style="color:#6f42c1;" id="asMedium">—</div>
                                    <div class="as-label">Medium</div>
                                </div>
                                <div class="col as-stat mb-2">
                                    <div class="as-value" style="color:#dc3545;" id="asLarge">—</div>
                                    <div class="as-label">Large</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="mb-2" style="font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                        Top industry
                                    </div>
                                    <p class="mb-0 small font-weight-bold" id="asTopIndustry">—</p>
                                    <div class="mt-3 mb-2" style="font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                        Economic activity
                                    </div>
                                    <p class="small text-muted mb-1">New registrations: <b id="asNew">—</b></p>
                                    <ul class="small mb-0 pl-3" id="asIndustries" style="line-height:1.7;"></ul>
                                </div>
                                <div class="col-md-7">
                                    <div class="mb-2" style="font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                        Sector mix
                                    </div>
                                    <table class="brgy-breakdown mb-0" id="asSectors">
                                        <tr><td colspan="3" class="text-muted small">—</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Tabs ─────────────────────────────────────────────── -->
                <ul class="nav nav-tabs emap-tabs mb-3" id="emapTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-hotspot" data-toggle="tab" href="#pane-hotspot"
                           role="tab" aria-controls="pane-hotspot" aria-selected="true">
                            <i class="material-icons">local_fire_department</i>Economic Hotspot Map
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-distribution" data-toggle="tab" href="#pane-distribution"
                           role="tab" aria-controls="pane-distribution" aria-selected="false">
                            <i class="material-icons">pie_chart</i>MSME Distribution Map
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-risk" data-toggle="tab" href="#pane-risk"
                           role="tab" aria-controls="pane-risk" aria-selected="false">
                            <i class="material-icons">shield</i>Economic Risk Map
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-opportunity" data-toggle="tab" href="#pane-opportunity"
                           role="tab" aria-controls="pane-opportunity" aria-selected="false">
                            <i class="material-icons">lightbulb</i>Economic Opportunity Map
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="emapTabContent">

                    <!-- ══ TAB: ECONOMIC HOTSPOT MAP ══════════════════════ -->
                    <div class="tab-pane fade show active" id="pane-hotspot" role="tabpanel"
                         aria-labelledby="tab-hotspot">
                        <div class="row">
                            <!-- Stats sidebar -->
                            <div class="col-md-3 mb-3">
                                <div class="card card-raised no-hover stat-pill mb-3">
                                    <div class="card-body py-3">
                                        <div class="label">Registered MSMEs</div>
                                        <div class="value" id="hotspotTotal">—</div>
                                    </div>
                                </div>
                                <div class="card card-raised no-hover stat-pill mb-3" style="border-left-color:#dc3545!important;">
                                    <div class="card-body py-3">
                                        <div class="label">Top Barangay</div>
                                        <div class="value" id="hotspotTopBrgy">—</div>
                                    </div>
                                </div>
                                <div class="card card-raised no-hover stat-pill mb-3" style="border-left-color:#28a745!important;">
                                    <div class="card-body py-3">
                                        <div class="label">Barangays with Businesses</div>
                                        <div class="value" id="hotspotWithBusiness">—</div>
                                    </div>
                                </div>
                                <div class="card card-raised no-hover mb-3">
                                    <div class="card-body d-flex align-items-center justify-content-between px-3 py-3 border-bottom">
                                        <span class="font-weight-bold" style="font-size:.82rem;">Barangay Ranking</span>
                                        <span class="badge badge-pill msme-badge-unknown" id="hotspotRankBadge">Top to Lowest</span>
                                    </div>
                                    <div class="card-body py-3">
                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            Top Barangay to Lowest
                                        </div>
                                        <div class="modern-dd" id="hotspotRankDD">
                                            <button type="button" class="modern-dd-btn" id="hotspotRankBtn">
                                                <i class="material-icons">emoji_events</i>
                                                <span id="hotspotRankBtnText">Loading ranking…</span>
                                                <i class="material-icons modern-dd-chevron">expand_more</i>
                                            </button>
                                            <div class="modern-dd-panel d-none" id="hotspotRankPanel">
                                                <div class="modern-dd-search">
                                                    <i class="material-icons">Search</i>
                                                    <input type="text" id="hotspotRankSearch"
                                                           placeholder="Search Barangay…"
                                                           autocomplete="off">
                                                </div>
                                                <div class="modern-dd-list" id="hotspotRanking"></div>
                                            </div>
                                        </div>
                                        <div id="hotspotRankDetail" class="rank-detail d-none"></div>
                                        <div class="small text-muted mt-2" style="line-height:1.5;">
                                            Select a Barangay to Fly to it on the Map.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Map -->
                            <div class="col-md-9 mb-3">
                                <div class="card map-card">
                                    <div class="map-card-header">
                                        <span>
                                            <i class="material-icons align-middle mr-1"
                                               style="font-size:17px;color:#ef4444;">local_fire_department</i>
                                            Economic Hotspot Map &mdash; business concentration per barangay
                                        </span>
                                        <span class="badge badge-pill msme-badge-unknown" id="hotspotBadge">loading…</span>
                                    </div>
                                    <div id="mapHotspot"></div>
                                </div>
                            </div>
                        </div>
                        <div class="emap-note mb-3">
                            <i class="material-icons">Info</i>
                            Hotspots reflect the number of registered MSMEs per barangay from the
                            <b>SCIMS Registry</b> (vamosmobile.app). Larger, Darker Circles indicate Higher Business Concentration.
                        </div>
                    </div>

                    <!-- ══ TAB: MSME DISTRIBUTION MAP ═════════════════════ -->
                    <div class="tab-pane fade" id="pane-distribution" role="tabpanel"
                         aria-labelledby="tab-distribution">
                        <div class="row">
                            <!-- Filters / legend / breakdown -->
                            <div class="col-md-3 mb-3">
                                <div class="card card-raised no-hover">
                                    <div class="card-body d-flex align-items-center justify-content-between px-3 py-3 border-bottom">
                                        <span class="font-weight-bold" style="font-size:.82rem;">Distribution by Sector</span>
                                        <span class="badge badge-pill msme-badge-unknown" id="distTotal">0 MSMEs</span>
                                    </div>
                                    <div class="card-body py-3">
                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            Filter by Sector
                                        </div>
                                        <div class="sector-search mb-2">
                                            <i class="material-icons">Search</i>
                                            <input type="text" id="distSectorSearch"
                                                   placeholder="Search Sector…"
                                                   autocomplete="off">
                                        </div>
                                        <button class="sector-all-btn active" data-cat="all">
                                            <i class="material-icons">apps</i>
                                            <span>All Sectors</span>
                                        </button>
                                        <div id="distChips" class="sector-list mb-3">
                                            <!-- sector rows injected by JS -->
                                        </div>

                                        <hr class="my-2">

                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            Legend
                                        </div>
                                        <div id="distLegend" class="small"></div>

                                        <hr class="my-2">

                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            Sector totals
                                        </div>
                                        <div id="distCategoryTotals" class="small"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Map + pie chart -->
                            <div class="col-md-9 mb-3 d-flex flex-column">
                                <div class="card map-card">
                                    <div class="map-card-header">
                                        <span>
                                            <i class="material-icons align-middle mr-1"
                                               style="font-size:17px;color:#60a5fa;">pie_chart</i>
                                            MSME Distribution Map &mdash; Dominant Sector per Barangay
                                        </span>
                                        <span class="badge badge-pill msme-badge-unknown" id="distBadge">loading…</span>
                                    </div>
                                    <div id="mapDistribution"></div>
                                </div>

                                <!-- ── Sector pie chart (exactly below the map, stretches to align with sector totals) ── -->
                                <div class="card card-raised no-hover mt-3 mb-0 flex-grow-1 d-flex flex-column">
                                    <div class="card-body d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
                                        <div>
                                            <span class="font-weight-bold">Sector Share</span>
                                            <small class="text-muted ml-2">All Barangays Combined</small>
                                        </div>
                                        <span class="badge badge-pill msme-badge-unknown" id="distPieBadge">loading…</span>
                                    </div>
                                    <div class="card-body px-4 py-3 flex-grow-1 d-flex flex-column justify-content-center">
                                        <div class="row align-items-center flex-grow-1">
                                            <!-- Doughnut -->
                                            <div class="col-md-5 d-flex justify-content-center mb-4 mb-md-0 pr-md-1">
                                                <div style="position:relative;width:340px;max-width:100%;height:500px;">
                                                    <canvas id="distPieChart"></canvas>
                                                </div>
                                            </div>
                                            <!-- Legend list -->
                                            <div class="col-md-7 pl-md-1 dist-pie-legend-col">
                                                <div id="distPieLegend"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="emap-note mb-3">
                            <i class="material-icons">Info</i>
                            Each circle is coloured by the dominant MSME sector in the barangay; click a circle to see
                            the full sector breakdown. Use the sector filter to focus on a specific industry.
                            Business counts come from the <b>SCIMS Registry</b>.
                        </div>
                    </div>

                    <!-- ══ TAB: ECONOMIC RISK MAP ════════════════════════ -->
                    <div class="tab-pane fade" id="pane-risk" role="tabpanel"
                         aria-labelledby="tab-risk">
                        <div class="row">
                            <!-- Summary / formula sidebar -->
                            <div class="col-md-3 mb-3">
                                <div class="card card-raised no-hover stat-pill mb-3" style="border-left-color:#dc3545!important;">
                                    <div class="card-body py-3">
                                        <div class="value" id="riskCriticalCount">—</div>
                                        <div class="label">Critical Risk Areas</div>
                                    </div>
                                </div>
                                <div class="card card-raised no-hover stat-pill mb-3" style="border-left-color:#fd7e14!important;">
                                    <div class="card-body py-3">
                                        <div class="value" id="riskHighCount">—</div>
                                        <div class="label">High Risk Areas</div>
                                    </div>
                                </div>
                                <div class="card card-raised no-hover stat-pill mb-3" style="border-left-color:#6c757d!important;">
                                    <div class="card-body py-3">
                                        <div class="value" id="riskTotalAreas">—</div>
                                        <div class="label">Barangays Assessed</div>
                                    </div>
                                </div>

                                <div class="card card-raised no-hover mb-3">
                                    <div class="card-body py-3">
                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            Calamity event
                                        </div>
                                        <select class="form-control form-control-sm" id="riskCalamity">
                                            <option value="" disabled selected>Select Calamity</option>
                                        </select>
                                        <div class="small text-muted mt-1">
                                            Risk is recomputed using the historical damage of the selected event (from Calamity Monitoring).
                                        </div>
                                    </div>
                                </div>

                                <div class="card card-raised no-hover">
                                    <div class="card-body py-3">
                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            How it&rsquo;s calculated
                                        </div>
                                        <p class="small mb-2" style="line-height:1.5;">
                                            <b>Economic Risk = Business Exposure &times; Hazard Level &times; Historical Damage</b>
                                        </p>
                                        <ul class="small text-muted mb-0 pl-3" style="line-height:1.6;">
                                            <li><b>Exposure</b> &mdash; Number of MSMEs in the Area (SCIMS Registry)</li>
                                            <li><b>Hazard</b> &mdash; LGU-Assessed Flood/Hazard Rating</li>
                                            <li><b>Damage</b> &mdash; Prior Calamity Losses Recorded in Calamity Monitoring</li>
                                        </ul>
                                        <hr class="my-2">
                                        <div class="small text-muted">
                                            Areas with high MSME concentration in flood-prone zones with a history of
                                            damage score highest &mdash; pinpointing where a disaster would cause the
                                            greatest economic disruption.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Map -->
                            <div class="col-md-9 mb-3">
                                <div class="card map-card">
                                    <div class="map-card-header">
                                        <span>
                                            <i class="material-icons align-middle mr-1"
                                               style="font-size:17px;color:#f87171;">shield</i>
                                            Economic Risk Map &mdash; linked to Calamity Monitoring
                                        </span>
                                        <span class="badge badge-pill msme-badge-unknown" id="riskBadge">loading…</span>
                                    </div>
                                    <div id="mapRisk"></div>
                                </div>
                            </div>
                        </div>
                        <div class="emap-note mb-3">
                            <i class="material-icons">info</i>
                            Risk levels:
                            <span class="legend-dot" style="background:#dc3545;"></span>Critical
                            <span class="legend-dot" style="background:#fd7e14;"></span>High
                            <span class="legend-dot" style="background:#ffc107;"></span>Moderate
                            <span class="legend-dot" style="background:#28a745;"></span>Low
                            &mdash; Click a Circle for the Full Risk Breakdown.
                        </div>
                    </div>

                    <!-- ══ TAB: ECONOMIC OPPORTUNITY MAP ═════════════════ -->
                    <div class="tab-pane fade" id="pane-opportunity" role="tabpanel"
                         aria-labelledby="tab-opportunity">
                        <div class="row">
                            <!-- Investment guidance sidebar -->
                            <div class="col-md-3 mb-3">
                                <div class="card card-raised no-hover stat-pill mb-3" style="border-left-color:#198754!important;">
                                    <div class="card-body py-3">
                                        <div class="value" id="oppHighCount">—</div>
                                        <div class="label">High / Very High Areas</div>
                                    </div>
                                </div>
                                <div class="card card-raised no-hover stat-pill mb-3" style="border-left-color:#007bff!important;">
                                    <div class="card-body py-3">
                                        <div class="value" id="oppTotalAreas">—</div>
                                        <div class="label">Barangays Assessed</div>
                                    </div>
                                </div>

                                <div class="card card-raised no-hover">
                                    <div class="card-body d-flex align-items-center justify-content-between px-3 py-3 border-bottom">
                                        <span class="font-weight-bold" style="font-size:.82rem;">Where to Invest &amp; Support</span>
                                    </div>
                                    <div class="card-body py-3">
                                        <div id="oppHighlights"></div>

                                        <hr class="my-2">

                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            How it&rsquo;s calculated
                                        </div>
                                        <ul class="small text-muted mb-0 pl-3" style="line-height:1.6;">
                                            <li><b>Commercial Potential</b> &mdash; MSME Concentration (SCIMS registry)</li>
                                            <li><b>Growth Momentum</b> &mdash; New Registrations</li>
                                            <li><b>Tourism Potential</b> &mdash; Coastal / Island Assets</li>
                                            <li><b>Agriculture Potential</b> &mdash; Land &amp; Production Capacity</li>
                                            <li><b>Livelihood Gap</b> &mdash; High Population, Few Businesses</li>
                                            <li><b>Infrastructure Gap</b> &mdash; Businesses, Limited Infrastructure</li>
                                            <li><b>Sector Diversity Gap</b> &mdash; Underrepresented Industries</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Map -->
                            <div class="col-md-9 mb-3">
                                <div class="card map-card">
                                    <div class="map-card-header">
                                        <span>
                                            <i class="material-icons align-middle mr-1"
                                               style="font-size:17px;color:#4ade80;">lightbulb</i>
                                            Economic Opportunity Map &mdash; investment &amp; development guidance
                                        </span>
                                        <span class="badge badge-pill msme-badge-unknown" id="oppBadge">loading…</span>
                                    </div>
                                    <div id="mapOpportunity"></div>
                                </div>
                            </div>
                        </div>
                        <div class="emap-note mb-3">
                            <i class="material-icons">info</i>
                            Opportunity Levels:
                            <span class="legend-dot" style="background:#198754;"></span>Very High
                            <span class="legend-dot" style="background:#28a745;"></span>High
                            <span class="legend-dot" style="background:#ffc107;"></span>Moderate
                            <span class="legend-dot" style="background:#6c757d;"></span>Low
                            &mdash; Click a Circle to See which Opportunity Drivers Apply.
                        </div>
                    </div>

                </div><!-- /tab-content -->

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

<!-- ── Scripts ──────────────────────────────────────────────────────── -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="../../plugins/select2/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>
<!-- Leaflet -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<!-- Economic map logic -->
<script src="../../scripts/economic-map/economic-map.js"></script>

</body>
</html>
