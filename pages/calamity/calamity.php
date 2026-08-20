<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>San Carlos City | Negosyo Center</title>

    <!-- Google Font: Roboto (matches rest of app) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
    <!-- Material Icons (required by sidebar nav icons) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round">
    <!-- Source Sans Pro (original font.css kept for any legacy usage) -->
    <link rel="stylesheet" href="../../dist/css/font.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.css">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Select2 -->
    <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../dist/css/user_defined.css">
    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">

    <style>
        /* ── Variables ── */
        :root {
            --cal-blue:       #1a3a6b;
            --cal-blue-mid:   #1e4db7;
            --cal-blue-light: #2563eb;
            --cal-red:        #b91c1c;
            --cal-red-mid:    #dc2626;
            --cal-red-light:  #ef4444;
            --cal-bg:         #f0f4f8;
        }

        /* ── Layout ── */
        body { background-color: var(--cal-bg) !important; }

        /* ── Navbar ── */
        .main-header.navbar {
            background: linear-gradient(90deg, var(--cal-blue) 60%, var(--cal-red) 100%) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        /* ── Sidebar ── */
        .main-sidebar { background-color: var(--cal-blue) !important; }
        .main-sidebar .nav-sidebar .nav-item .nav-link { color: #cfd8f0 !important; }
        .main-sidebar .nav-sidebar .nav-item .nav-link.active,
        .main-sidebar .nav-sidebar .nav-item .nav-link:hover {
            background-color: var(--cal-red) !important;
            color: #fff !important;
        }
        .brand-link {
            background-color: var(--cal-blue) !important;
            border-bottom: 1px solid rgba(255,255,255,0.15) !important;
        }

        /* ── Page header banner ── */
        .page-header-card {
            background: linear-gradient(135deg, var(--cal-blue) 0%, #2a52a0 50%, var(--cal-red) 100%);
            border-radius: 12px;
            padding: 28px 28px 22px;
            margin-bottom: 20px;
            box-shadow: 0 4px 18px rgba(26,58,107,0.35);
            position: relative;
            overflow: hidden;
        }
        .page-header-card::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 220px; height: 100%;
            background: rgba(185,28,28,0.18);
            clip-path: polygon(40% 0%, 100% 0%, 100% 100%, 0% 100%);
        }
        .page-header-card h2 {
            color: #fff;
            font-weight: 700;
            margin: 0 0 4px;
            font-size: 1.8rem;
            position: relative; z-index: 1;
        }
        .page-header-card p {
            color: rgba(255,255,255,0.75);
            margin: 0;
            font-size: 0.875rem;
            position: relative; z-index: 1;
        }

        /* ── Table card ── */
        .table-card {
            background: #fff;
            border-radius: 12px;
            border-top: 4px solid var(--cal-red);
            box-shadow: 0 2px 14px rgba(0,0,0,0.09);
            padding: 20px;
        }

        /* ── Action buttons ── */
        .action-bar { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
        #btn_add_calamity {
            background-color: var(--cal-red) !important;
            border-color: var(--cal-red) !important;
            color: #fff !important;
            border-radius: 6px; font-weight: 600; padding: 6px 16px;
        }
        #btn_add_calamity:hover {
            background-color: var(--cal-red-light) !important;
            border-color: var(--cal-red-light) !important;
        }
        #btn_add_incident {
            background-color: var(--cal-blue-light) !important;
            border-color: var(--cal-blue-light) !important;
            color: #fff !important;
            border-radius: 6px; font-weight: 600; padding: 6px 16px;
        }
        #btn_add_incident:hover {
            background-color: var(--cal-blue-mid) !important;
            border-color: var(--cal-blue-mid) !important;
        }

        /* ── DataTable headers ── */
        #tblCalamityIncidents.dataTable thead th,
        #tblAffectedBusinesses.dataTable thead th {
            background: linear-gradient(90deg, var(--cal-blue) 0%, var(--cal-blue-mid) 100%) !important;
            border-color: var(--cal-blue-mid) !important;
            color: #fff !important;
            text-align: center;
            font-weight: 600;
            letter-spacing: 0.03em;
        }
        #tblCalamityIncidents.dataTable tbody td,
        #tblAffectedBusinesses.dataTable tbody td {
            text-align: center;
            vertical-align: middle !important;
        }
        #tblCalamityIncidents.dataTable tbody tr:hover td,
        #tblAffectedBusinesses.dataTable tbody tr:hover td {
            background-color: #eef2ff !important;
        }

        /* ── Footer ── */
        .main-footer {
            border-top: 3px solid var(--cal-red);
            background: #fff;
        }

        .btn-outline-info-custom { border-radius: 50rem; }
    </style>

</head>

<body class="sidebar-mini layout-fixed" style="height: auto">

    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="" src="../../dist/img/itcsologo.webp" alt="AdminLTELogo" height="60" width="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar sticky-top navbar-expand navbar-dark">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
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
                            <a class="nav-link text-sm" style="padding-left: 13px;" onclick="logout()" role="button">
                                <i class="fa-solid p-1 fa-right-from-bracket" style="background-color: rgb(16 16 16 / 42%); border-radius: 22px; padding: 9px !important;"></i> &nbsp;Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <?php include '../../pages/sidebar/sidebar.php' ?>

        <!-- Content -->
        <div id="body_wrapper" class="content-wrapper">
            <div class="content mt-3">
                <div class="container-fluid">

                    <!-- Page Header -->
                    <div class="page-header-card">
                        <h2><i class="fas fa-cloud-showers-heavy mr-2"></i>Calamity Monitoring</h2>
                        <p>Track and manage calamity incidents affecting MSMEs in San Carlos City</p>
                    </div>

                    <!-- Table Card -->
                    <div class="table-card">
                        <div class="action-bar">
                            <button type="button" class="btn btn-sm" id="btn_add_calamity" data-toggle="modal" data-target="#addCalamityModal">
                                <i class="fas fa-cloud-rain mr-1"></i>Add Calamity
                            </button>
                            <button type="button" class="btn btn-sm" id="btn_add_incident" data-toggle="modal" data-target="#addIncidentModal">
                                <i class="fas fa-plus mr-1"></i>Add Incident
                            </button>
                        </div>

                        <table id="tblCalamityIncidents" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Calamity</th>
                                    <th>Type</th>
                                    <th>Declaration Date</th>
                                    <th>MSMEs Affected</th>
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
        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline">All rights reserved</div>
        <strong>Copyright &copy; 2024 ITCSO. <a href="http://lguscc.gov.ph/">Local Government of San Carlos City</a></strong>.
    </footer>

    <?php include 'modal-add-calamity.php'; ?>
    <?php include 'modal-add.php'; ?>
    <?php include 'modal-view.php'; ?>
    <?php include 'modal-update.php'; ?>
    <?php include 'modal-edit-calamity.php'; ?>

    <!-- SCRIPTS -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/select2/js/select2.min.js"></script>
    <script src="../../dist/js/adminlte.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>
    <script src="../../scripts/common/alert.js"></script>

    <script src="../../scripts/calamity/calamity-table.js?v=<?= time() ?>"></script>
    <script src="../../scripts/calamity/calamity-add-calamity.js?v=<?= time() ?>"></script>
    <script src="../../scripts/calamity/calamity-edit-calamity.js?v=<?= time() ?>"></script>
    <script src="../../scripts/calamity/calamity-add.js?v=<?= time() ?>"></script>
    <script src="../../scripts/calamity/calamity-update.js?v=<?= time() ?>"></script>

</body>

</html>
