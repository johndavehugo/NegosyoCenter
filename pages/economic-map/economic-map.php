<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>San Carlos City Economic Hotspot Map</title>

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

    <link rel="stylesheet" href="../../dist/css/economic-map/shared/shared.css">
    <link rel="stylesheet" href="../../dist/css/economic-map/hotspot/hotspot.css">
    <link rel="stylesheet" href="../../dist/css/economic-map/distribution/distribution.css">
    <link rel="stylesheet" href="../../dist/css/economic-map/risk/risk.css">
    <link rel="stylesheet" href="../../dist/css/economic-map/opportunity/opportunity.css">
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
    <?php include __DIR__ . '/../sidebar/sidebar.php'; ?>

    <!-- ── Content wrapper ────────────────────────────────────────────── -->
    <div id="body_wrapper" class="content-wrapper">
        <div class="content pt-4 pb-2">
            <div class="container-fluid">

                <!-- ── Page header ── -->
                <div class="card card-raised mb-3">
                    <div class="card-body d-flex align-items-center justify-content-between px-4 py-3">
                        <div>
                            <h5 class="mb-0 font-weight-bold" id="economicMapTitle">
                                <i class="material-icons align-middle mr-1"
                                   style="font-size:22px;color:#007bff;vertical-align:middle;">map</i>
                                San Carlos City Economic Hotspot Map
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
                        <a class="nav-link active" id="tab-hotspot" data-toggle="tab" data-page-title="San Carlos City Economic Hotspot Map" href="#pane-hotspot"
                           role="tab" aria-controls="pane-hotspot" aria-selected="true">
                            <i class="material-icons">local_fire_department</i>Economic Hotspot Map
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-distribution" data-toggle="tab" data-page-title="San Carlos City MSME Distribution Map" href="#pane-distribution"
                           role="tab" aria-controls="pane-distribution" aria-selected="false">
                            <i class="material-icons">pie_chart</i>MSME Distribution Map
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-risk" data-toggle="tab" data-page-title="San Carlos City Economic Risk Map" href="#pane-risk"
                           role="tab" aria-controls="pane-risk" aria-selected="false">
                            <i class="material-icons">shield</i>Economic Risk Map
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-opportunity" data-toggle="tab" data-page-title="San Carlos City Economic Opportunity Map" href="#pane-opportunity"
                           role="tab" aria-controls="pane-opportunity" aria-selected="false">
                            <i class="material-icons">lightbulb</i>Economic Opportunity Map
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="emapTabContent">

                <?php include __DIR__ . '/components/hotspot/hotspot.php'; ?>

                <?php include __DIR__ . '/components/distribution/distribution.php'; ?>

                <?php include __DIR__ . '/components/risk/risk.php'; ?>

                <?php include __DIR__ . '/components/opportunity/opportunity.php'; ?>

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
<script src="../../scripts/economic-map/shared/shared.js"></script>
<script src="../../scripts/economic-map/hotspot/hotspot.js"></script>
<script src="../../scripts/economic-map/distribution/distribution.js"></script>
<script src="../../scripts/economic-map/risk/risk.js"></script>
<script src="../../scripts/economic-map/opportunity/opportunity.js"></script>

</body>
</html>
