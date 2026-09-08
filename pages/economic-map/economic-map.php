<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>San Carlos City | Economic Map</title>

    <!-- Google Font: Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <!-- Shared custom styles -->
    <link rel="stylesheet" href="../../dist/css/user_defined.css?v=5">
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin="">

    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">

    <style>
        body { font-family: 'Roboto', sans-serif; }

        /* ── Page heading ── */
        .emap-heading    { font-weight: 700; font-size: 1.15rem; color: #1a1a2e; margin-bottom: 2px; }
        .emap-subheading { color: #9ca3af; font-size: .8rem; }

        /* ── Tabs ── */
        .emap-tabs .nav-link {
            font-size: .85rem; font-weight: 600; color: #495057;
            border-radius: 6px 6px 0 0; padding: .65rem 1.15rem;
            border: 1px solid transparent;
        }
        .emap-tabs .nav-link.active {
            color: #007bff; background: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }
        .emap-tabs .nav-link i { font-size: 17px; vertical-align: middle; margin-right: 4px; }

        /* ── Map panels ── */
        .map-card { border: none; border-radius: 8px;
                    box-shadow: 0 2px 8px rgba(0,0,0,.10), 0 1px 3px rgba(0,0,0,.07); }
        .map-card-header {
            border-bottom: 1px solid #e9ecef; padding: .7rem 1.1rem;
            font-size: .78rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: .05em; color: #495057; border-radius: 8px 8px 0 0;
            display: flex; align-items: center; justify-content: space-between;
        }
        #mapHotspot,
        #mapDistribution,
        #mapRisk,
        #mapPressure,
        #mapOpportunity { width: 100%; height: 560px; border-radius: 0 0 8px 8px; z-index: 1; }
        .map-legend {
            background: rgba(255,255,255,.92); border-radius: 6px;
            box-shadow: 0 1px 4px rgba(0,0,0,.25); padding: 10px 12px;
            line-height: 1.9; font-size: .78rem;
        }
        .map-legend h6 { font-size: .72rem; font-weight: 700; text-transform: uppercase;
                          letter-spacing: .04em; color: #495057; }
        .legend-dot { display: inline-block; width: 12px; height: 12px; border-radius: 50%;
                      margin-right: 6px; vertical-align: middle; }
        .legend-circle { display: inline-block; border-radius: 50%; margin-right: 6px;
                         vertical-align: middle; background: rgba(220,53,69,.45);
                         border: 1px solid #dc3545; }

        /* ── Area search ── */
        .area-search { position: relative; max-width: 640px; }
        .area-matches {
            position: absolute; top: 100%; left: 0; right: 0; z-index: 1050;
            background: #fff; border: 1px solid #dee2e6; border-radius: 6px;
            box-shadow: 0 4px 14px rgba(0,0,0,.14); max-height: 320px; overflow-y: auto;
        }
        .area-match-item {
            display: flex; align-items: center; gap: 8px;
            padding: .5rem .75rem; cursor: pointer; font-size: .82rem;
            border-bottom: 1px solid #f1f3f5;
        }
        .area-match-item:hover { background: #f8f9fa; }
        .area-match-item .ami-icon { color: #9ca3af; font-size: 15px; }
        .area-match-item .ami-type {
            font-size: .6rem; text-transform: uppercase; font-weight: 700;
            letter-spacing: .05em; color: #9ca3af; margin-left: auto;
        }
        .as-stat { text-align: center; }
        .as-stat .as-value { font-size: 1.3rem; font-weight: 700; color: #1a1a2e; }
        .as-stat .as-label {
            font-size: .66rem; text-transform: uppercase; letter-spacing: .05em; color: #9ca3af;
        }

        /* ── Summary / breakdown ── */
        .cat-chip {
            font-size: .74rem; font-weight: 600; border-radius: 20px;
            padding: .28rem .7rem; margin: 0 4px 6px 0; cursor: pointer;
            border: 1px solid transparent; transition: opacity .15s ease;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .cat-chip.active { box-shadow: 0 0 0 2px #fff, 0 0 0 4px currentColor; }
        .cat-chip.dimmed { opacity: .35; }

        .brgy-breakdown { font-size: .82rem; width: 100%; }
        .brgy-breakdown td { padding: 2px 6px; }
        .breakdown-bar { height: 7px; border-radius: 4px; display: block; min-width: 2px; }

        .stat-pill { border-left: 4px solid #007bff !important; }
        .stat-pill .value { font-size: 1.55rem; font-weight: 700; line-height: 1; color: #1a1a2e; }
        .stat-pill .label { font-size: .7rem; font-weight: 600; text-transform: uppercase;
                            letter-spacing: .05em; color: #9ca3af; }

        .no-data-msg {
            display: flex; align-items: center; justify-content: center;
            min-height: 160px; color: #adb5bd; font-size: .875rem; gap: 6px;
        }

        .pressure-card {
            border: 1px solid #e9ecef; border-left: 4px solid #6c757d;
            border-radius: 6px; padding: .55rem .7rem; margin-bottom: .55rem;
            background: #fff;
        }
        .pressure-card .pc-name { font-size: .78rem; font-weight: 600; color: #343a40; }
        .pressure-card .pc-agency { font-size: .66rem; color: #9ca3af; }
        .pressure-card .pc-metric { font-size: .72rem; color: #495057; }

        .highlight-item {
            display: flex; align-items: center; gap: 8px;
            padding: .38rem .5rem; border-radius: 6px; margin-bottom: .3rem;
            background: #f8f9fa; font-size: .76rem;
        }
        .highlight-item i { color: #198754; font-size: 15px; }
        .highlight-item .hi-label { color: #6c757d; }
        .highlight-item .hi-barangay { font-weight: 700; color: #1a1a2e; }
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
                    <p class="emap-heading mb-0">
                        <i class="material-icons align-middle mr-1"
                           style="font-size:22px;color:#007bff;vertical-align:middle;">map</i>
                        San Carlos City Economic Map
                    </p>
                    <small class="emap-subheading">
                        MSME-based economic activity &mdash; San Carlos City Negosyo Center
                    </small>
                </div>

                <!-- ── Area search ─────────────────────────────────────── -->
                <div class="area-search mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white" style="border-right:0;">
                                <i class="material-icons" style="font-size:18px;color:#9ca3af;">search</i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="areaSearch"
                               placeholder="Search barangay or street &mdash; e.g. Barangay II, Rizal, S. Carmona"
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
                </div>

                <!-- ── Area summary results ────────────────────────────── -->
                <div id="areaSummaryWrap" class="d-none">
                    <div class="card map-card mb-3">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-0" style="font-weight:700;color:#1a1a2e;"
                                        id="asTitle">—</h6>
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
                                    <div class="mb-2" style="font-size:.68rem;font-weight:600;
                                         text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                        Top industry
                                    </div>
                                    <p class="mb-0 small" style="font-weight:600;" id="asTopIndustry">—</p>

                                    <div class="mt-3 mb-2" style="font-size:.68rem;font-weight:600;
                                         text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                        Economic activity
                                    </div>
                                    <p class="small text-muted mb-1">
                                        New registrations: <b id="asNew">—</b>
                                    </p>
                                    <ul class="small mb-0 pl-3" id="asIndustries" style="line-height:1.7;"></ul>
                                </div>
                                <div class="col-md-7">
                                    <div class="mb-2" style="font-size:.68rem;font-weight:600;
                                         text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
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
                        <a class="nav-link" id="tab-pressure" data-toggle="tab" href="#pane-pressure"
                           role="tab" aria-controls="pane-pressure" aria-selected="false">
                            <i class="material-icons">price_change</i>Price / Economic Pressure Map
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
                            <!-- Stats -->
                            <div class="col-md-3 mb-3">
                                <div class="card stat-pill mb-3">
                                    <div class="card-body py-3">
                                        <div class="value" id="hotspotTotal">—</div>
                                        <div class="label">Registered MSMEs</div>
                                    </div>
                                </div>
                                <div class="card stat-pill mb-3" style="border-left-color:#dc3545!important;">
                                    <div class="card-body py-3">
                                        <div class="value" id="hotspotTopBrgy">—</div>
                                        <div class="label">Top Barangay</div>
                                    </div>
                                </div>
                                <div class="card stat-pill mb-3" style="border-left-color:#28a745!important;">
                                    <div class="card-body py-3">
                                        <div class="value" id="hotspotWithBusiness">—</div>
                                        <div class="label">Barangays with businesses</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Map -->
                            <div class="col-md-9 mb-3">
                                <div class="card map-card">
                                    <div class="map-card-header">
                                        <span>
                                            <i class="material-icons align-middle mr-1"
                                               style="font-size:17px;color:#dc3545;">local_fire_department</i>
                                            Economic Hotspot Map &mdash; business concentration per barangay
                                        </span>
                                        <span class="badge badge-pill msme-badge-unknown" id="hotspotBadge">loading…</span>
                                    </div>
                                    <div id="mapHotspot"></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-muted small">
                            <i class="material-icons align-middle" style="font-size:15px;">info_outline</i>
                            Hotspots reflect the number of registered MSMEs per barangay from the
                            <b>SCIMS registry</b> (vamosmobile.app). Larger, darker circles
                            indicate higher business concentration.
                        </div>
                    </div>

                    <!-- ══ TAB: MSME DISTRIBUTION MAP ═════════════════════ -->
                    <div class="tab-pane fade" id="pane-distribution" role="tabpanel"
                         aria-labelledby="tab-distribution">
                        <div class="row">
                            <!-- Filters / legend / breakdown -->
                            <div class="col-md-3 mb-3">
                                <div class="card map-card">
                                    <div class="map-card-header">
                                        <span>Distribution by Sector</span>
                                        <span class="badge badge-pill msme-badge-unknown" id="distTotal">0 MSMEs</span>
                                    </div>
                                    <div class="card-body py-3">
                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;
                                             text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            Filter by sector
                                        </div>
                                        <div id="distChips" class="mb-3">
                                            <button class="cat-chip active" data-cat="all"
                                                    style="background:#f1f3f5;color:#343a40;">
                                                All sectors
                                            </button>
                                            <!-- chips injected by JS -->
                                        </div>

                                        <hr class="my-2">

                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;
                                             text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            Sector totals
                                        </div>
                                        <div id="distCategoryTotals" class="small">
                                            <!-- injected by JS -->
                                        </div>

                                        <hr class="my-2">

                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;
                                             text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            Legend
                                        </div>
                                        <div id="distLegend" class="small">
                                            <!-- injected by JS -->
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
                                               style="font-size:17px;color:#007bff;">pie_chart</i>
                                            MSME Distribution Map &mdash; dominant sector per barangay
                                        </span>
                                        <span class="badge badge-pill msme-badge-unknown" id="distBadge">loading…</span>
                                    </div>
                                    <div id="mapDistribution"></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-muted small">
                            <i class="material-icons align-middle" style="font-size:15px;">info_outline</i>
                            Each circle is coloured by the dominant MSME sector in the barangay; click a circle to see
                            the full sector breakdown. Use the sector filter to focus on a specific industry.
                            Business counts come from the <b>SCIMS registry</b>.
                        </div>
                    </div>

                    <!-- ══ TAB: ECONOMIC RISK MAP ════════════════════════ -->
                    <div class="tab-pane fade" id="pane-risk" role="tabpanel"
                         aria-labelledby="tab-risk">
                        <div class="row">
                            <!-- Summary / formula -->
                            <div class="col-md-3 mb-3">
                                <div class="card stat-pill mb-3" style="border-left-color:#dc3545!important;">
                                    <div class="card-body py-3">
                                        <div class="value" id="riskCriticalCount">—</div>
                                        <div class="label">Critical Risk Areas</div>
                                    </div>
                                </div>
                                <div class="card stat-pill mb-3" style="border-left-color:#fd7e14!important;">
                                    <div class="card-body py-3">
                                        <div class="value" id="riskHighCount">—</div>
                                        <div class="label">High Risk Areas</div>
                                    </div>
                                </div>
                                <div class="card stat-pill mb-3" style="border-left-color:#6c757d!important;">
                                    <div class="card-body py-3">
                                        <div class="value" id="riskTotalAreas">—</div>
                                        <div class="label">Barangays assessed</div>
                                    </div>
                                </div>

                                <div class="card map-card mb-3">
                                    <div class="card-body py-3">
                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;
                                             text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            Calamity event
                                        </div>
                                        <select class="form-control form-control-sm" id="riskCalamity">
                                            <option value="0">All calamities</option>
                                        </select>
                                        <div class="small text-muted mt-1">
                                            Risk is recomputed using the historical damage of the selected
                                            event (from Calamity Monitoring).
                                        </div>
                                    </div>
                                </div>

                                <div class="card map-card">
                                    <div class="card-body py-3">
                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;
                                             text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            How it&rsquo;s calculated
                                        </div>
                                        <p class="small mb-2" style="line-height:1.5;">
                                            <b>Economic Risk = Business Exposure &times; Hazard Level &times; Historical Damage</b>
                                        </p>
                                        <ul class="small text-muted mb-0 pl-3" style="line-height:1.6;">
                                            <li><b>Exposure</b> &mdash; number of MSMEs in the area (SCIMS registry)</li>
                                            <li><b>Hazard</b> &mdash; LGU-assessed flood/hazard rating</li>
                                            <li><b>Damage</b> &mdash; prior calamity losses recorded in Calamity Monitoring</li>
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
                                               style="font-size:17px;color:#dc3545;">shield</i>
                                            Economic Risk Map &mdash; linked to Calamity Monitoring
                                        </span>
                                        <span class="badge badge-pill msme-badge-unknown" id="riskBadge">loading…</span>
                                    </div>
                                    <div id="mapRisk"></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-muted small">
                            <i class="material-icons align-middle" style="font-size:15px;">info_outline</i>
                            Risk levels: <span class="legend-dot" style="background:#dc3545;"></span>Critical
                            <span class="legend-dot" style="background:#fd7e14;"></span>High
                            <span class="legend-dot" style="background:#ffc107;"></span>Moderate
                            <span class="legend-dot" style="background:#28a745;"></span>Low
                            &mdash; click a circle for the full risk breakdown.
                        </div>
                    </div>

                    <!-- ══ TAB: PRICE / ECONOMIC PRESSURE MAP ══════════════ -->
                    <div class="tab-pane fade" id="pane-pressure" role="tabpanel"
                         aria-labelledby="tab-pressure">
                        <div class="row">
                            <!-- Commodity price pressure panel -->
                            <div class="col-md-3 mb-3">
                                <div class="card map-card">
                                    <div class="map-card-header">
                                        <span>Price Pressure by Commodity</span>
                                        <span class="badge badge-pill msme-badge-unknown" id="pressureAsOf">—</span>
                                    </div>
                                    <div class="card-body py-3">
                                        <div id="pressureCards">
                                            <!-- injected by JS -->
                                        </div>

                                        <hr class="my-2">

                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;
                                             text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            How it&rsquo;s calculated
                                        </div>
                                        <p class="small mb-2" style="line-height:1.5;">
                                            <b>Economic Pressure = Price Pressure &times; Sector MSME Exposure</b>
                                        </p>
                                        <ul class="small text-muted mb-0 pl-3" style="line-height:1.6;">
                                            <li><b>Price pressure</b> &mdash; latest monitored price vs. SRP per commodity group (DA / DTI / DOE)</li>
                                            <li><b>Exposure</b> &mdash; MSMEs (SCIMS registry) in the sectors that depend on that commodity (e.g. fuel &times; transportation)</li>
                                        </ul>
                                        <hr class="my-2">
                                        <div class="small text-muted">
                                            Example: high fuel prices combined with many transportation MSMEs
                                            produce high economic pressure. Electricity/energy indicators are
                                            tracked under DOE fuel monitoring.
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
                                               style="font-size:17px;color:#fd7e14;">price_change</i>
                                            Price / Economic Pressure Map &mdash; linked to Price Monitoring
                                        </span>
                                        <span class="badge badge-pill msme-badge-unknown" id="pressureBadge">loading…</span>
                                    </div>
                                    <div id="mapPressure"></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-muted small">
                            <i class="material-icons align-middle" style="font-size:15px;">info_outline</i>
                            Pressure levels: <span class="legend-dot" style="background:#dc3545;"></span>Critical
                            <span class="legend-dot" style="background:#fd7e14;"></span>High
                            <span class="legend-dot" style="background:#ffc107;"></span>Moderate
                            <span class="legend-dot" style="background:#28a745;"></span>Low
                            <span class="legend-dot" style="background:#6c757d;"></span>No data
                            &mdash; click a circle to see which commodity groups drive the pressure.
                        </div>
                    </div>

                    <!-- ══ TAB: ECONOMIC OPPORTUNITY MAP ═════════════════ -->
                    <div class="tab-pane fade" id="pane-opportunity" role="tabpanel"
                         aria-labelledby="tab-opportunity">
                        <div class="row">
                            <!-- Investment guidance panel -->
                            <div class="col-md-3 mb-3">
                                <div class="card stat-pill mb-3" style="border-left-color:#198754!important;">
                                    <div class="card-body py-3">
                                        <div class="value" id="oppHighCount">—</div>
                                        <div class="label">High / Very High areas</div>
                                    </div>
                                </div>
                                <div class="card stat-pill mb-3" style="border-left-color:#007bff!important;">
                                    <div class="card-body py-3">
                                        <div class="value" id="oppTotalAreas">—</div>
                                        <div class="label">Barangays assessed</div>
                                    </div>
                                </div>

                                <div class="card map-card">
                                    <div class="map-card-header">
                                        <span>Where to invest &amp; support</span>
                                    </div>
                                    <div class="card-body py-3">
                                        <div id="oppHighlights">
                                            <!-- injected by JS -->
                                        </div>

                                        <hr class="my-2">

                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;
                                             text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            How it&rsquo;s calculated
                                        </div>
                                        <ul class="small text-muted mb-0 pl-3" style="line-height:1.6;">
                                            <li><b>Commercial potential</b> &mdash; MSME concentration (SCIMS registry)</li>
                                            <li><b>Growth momentum</b> &mdash; new registrations</li>
                                            <li><b>Tourism potential</b> &mdash; coastal / island assets</li>
                                            <li><b>Agriculture potential</b> &mdash; land &amp; production capacity</li>
                                            <li><b>Livelihood gap</b> &mdash; high population, few businesses</li>
                                            <li><b>Infrastructure gap</b> &mdash; businesses, limited infrastructure</li>
                                            <li><b>Sector diversity gap</b> &mdash; underrepresented industries</li>
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
                                               style="font-size:17px;color:#198754;">lightbulb</i>
                                            Economic Opportunity Map &mdash; investment &amp; development guidance
                                        </span>
                                        <span class="badge badge-pill msme-badge-unknown" id="oppBadge">loading…</span>
                                    </div>
                                    <div id="mapOpportunity"></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-muted small">
                            <i class="material-icons align-middle" style="font-size:15px;">info_outline</i>
                            Opportunity levels: <span class="legend-dot" style="background:#198754;"></span>Very High
                            <span class="legend-dot" style="background:#28a745;"></span>High
                            <span class="legend-dot" style="background:#ffc107;"></span>Moderate
                            <span class="legend-dot" style="background:#6c757d;"></span>Low
                            &mdash; click a circle to see which opportunity drivers apply.
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

<!-- ── Scripts ─────────────────────────────────────────────────────── -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<!-- Leaflet -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
<!-- Economic map logic -->
<script src="../../scripts/economic-map/economic-map.js"></script>

</body>
</html>