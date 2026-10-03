<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Price Monitor | Negosyo Center</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" type="image/png" sizes="40x16" href="../../../dist/img/splogo.png">

    <style>
        :root {
            --accent:      #028090;
            --accent-mid:  #05a8bc;
            --accent-soft: #e6f4f5;
            --ink:         #1a1a1a;
            --muted:       #6b7280;
            --line:        #e8e8e8;
            --bg:          #f8fafc;
        }
        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #fff; color: var(--ink); margin: 0;
        }

        /* ── Header ─────────────────────────────────────────── */
        .site-header {
            background: linear-gradient(135deg, #013a40 0%, #028090 60%, #05c0d8 100%);
            padding: 3rem 1rem 2.5rem; text-align: center; position: relative; overflow: hidden;
        }
        .site-header::before {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .site-header .seal {
            width: 60px; height: 60px; object-fit: contain;
            margin-bottom: 1rem; filter: drop-shadow(0 2px 8px rgba(0,0,0,.25)); position: relative;
        }
        .site-header .kicker {
            font-size: .72rem; font-weight: 600; letter-spacing: .2em;
            text-transform: uppercase; color: rgba(255,255,255,.65);
            margin-bottom: .5rem; position: relative;
        }
        .site-header h1 {
            font-size: clamp(1.9rem,4vw,2.8rem); font-weight: 700;
            letter-spacing: -.03em; color: #fff; margin-bottom: .5rem; position: relative;
        }
        .site-header .subtitle {
            color: rgba(255,255,255,.75); max-width: 34rem;
            margin: 0 auto; font-size: 1rem; position: relative;
        }
        .header-pill {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,.15); backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,.2); border-radius: 999px;
            padding: 6px 16px; font-size: .78rem; font-weight: 600;
            color: #fff; margin-top: 1.25rem; position: relative;
        }

        /* ── Category grid ───────────────────────────────────── */
        .category-section { padding: 2.5rem 0 1rem; }
        .category-card {
            background: #fff; border: 1px solid var(--line); border-radius: 18px;
            padding: 1.75rem 1rem; text-align: center; cursor: pointer; height: 100%;
            transition: border-color .2s, box-shadow .2s, transform .2s;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
        }
        .category-card:hover {
            border-color: var(--accent);
            box-shadow: 0 12px 32px rgba(2,128,144,.12); transform: translateY(-4px);
        }
        .category-card:focus { outline: 2px solid var(--accent); outline-offset: 3px; }
        .category-icon {
            width: 58px; height: 58px; border-radius: 50%;
            background: var(--accent-soft); color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.45rem; margin: 0 auto 1rem;
            transition: background .2s, color .2s, transform .2s;
        }
        .category-card:hover .category-icon { background: var(--accent); color: #fff; transform: scale(1.1); }
        .category-name  { font-weight: 700; font-size: 1rem; margin-bottom: .25rem; }
        .category-agency{ font-size: .78rem; color: var(--muted); }
        .category-count { margin-top: .75rem; }
        .category-count .badge {
            background: var(--accent-soft); color: var(--accent);
            font-weight: 600; font-size: .72rem; padding: .35em .85em; border-radius: 999px;
        }

        /* ── Modals ──────────────────────────────────────────── */
        .modal-content { border: none; border-radius: 22px; overflow: hidden; box-shadow: 0 24px 64px rgba(0,0,0,.18); }
        .modal-header  { border-bottom: 1px solid var(--line); padding: 1.25rem 1.5rem; background: #fff; }
        .modal-title   { font-weight: 700; font-size: 1.05rem; }
        .modal-footer  { border-top: 1px solid var(--line); padding: 1rem 1.5rem; background: #fff; }
        .btn-ghost {
            background: transparent; border: 1.5px solid var(--line);
            color: var(--muted); border-radius: 999px; padding: .4rem 1.4rem;
            font-size: .88rem; font-weight: 500; cursor: pointer;
            transition: border-color .2s, color .2s, background .2s;
        }
        .btn-ghost:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-soft); }

        /* ── Commodity list ──────────────────────────────────── */
        .commodity-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: .85rem 1rem; border-bottom: 1px solid #f0f0f0;
            border-radius: 10px; cursor: pointer; transition: background .15s, transform .1s;
        }
        .commodity-row:hover { background: var(--accent-soft); transform: translateX(3px); }
        .commodity-row:last-child { border-bottom: none; }
        .commodity-name { font-weight: 600; }
        .commodity-meta { font-size: .8rem; color: var(--muted); margin-top: .12rem; }
        .commodity-arrow{ color: var(--muted); font-size: .8rem; margin-left: 1rem; white-space: nowrap; }
        .commodity-empty{ text-align: center; color: var(--muted); padding: 2.5rem 1rem; }

        /* ── Search ──────────────────────────────────────────── */
        .commodity-search { position: relative; margin: .5rem 0; }
        .commodity-search i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: .9rem; }
        .commodity-search input {
            width: 100%; border: 1.5px solid var(--line); border-radius: 14px;
            padding: .65rem 1rem .65rem 2.5rem; font-size: .95rem; background: #fff;
            transition: border-color .2s, box-shadow .2s;
        }
        .commodity-search input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(2,128,144,.12); }

        /* ── Modal inner tabs ────────────────────────────────── */
        .modal-tabs {
            display: flex;
            border-bottom: 2px solid var(--line);
            background: #fff;
            padding: 0 1.5rem;
        }
        .modal-tab-btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: .75rem 1rem;
            font-size: .82rem; font-weight: 600;
            color: var(--muted); background: none; border: none;
            border-bottom: 2.5px solid transparent;
            margin-bottom: -2px; cursor: pointer;
            transition: color .15s, border-color .15s;
            white-space: nowrap; font-family: inherit; outline: none;
        }
        .modal-tab-btn:hover { color: var(--accent); }
        .modal-tab-btn.active {
            color: var(--accent);
            border-bottom-color: var(--accent);
        }
        .modal-tab-btn i { font-size: .9rem; }

        /* ── Tab panes ───────────────────────────────────────── */
        .modal-tab-pane { display: none; }
        .modal-tab-pane.active { display: block; }

        /* scrollable pane body */
        .pane-scroll {
            max-height: 68vh; overflow-y: auto; padding: 0;
        }
        .pane-scroll::-webkit-scrollbar { width: 5px; }
        .pane-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }

        /* ── Establishment table ─────────────────────────────── */
        .establishment-table th {
            font-size: .76rem; text-transform: uppercase; letter-spacing: .06em;
            color: var(--muted); background: var(--bg); border-top: none; font-weight: 600;
        }
        .establishment-table td { vertical-align: middle; font-size: .9rem; }
        .est-row-lowest   td { background: #d1fae5 !important; border-color: #a7f3d0 !important; }
        .est-row-highest  td { background: #fee2e2 !important; border-color: #fecaca !important; }
        .est-row-above-srp td { background: #fef9c3 !important; border-color: #fde68a !important; }
        .est-rank {
            display: inline-flex; align-items: center; justify-content: center;
            width: 26px; height: 26px; border-radius: 50%;
            font-size: .72rem; font-weight: 700; background: #e5e7eb; color: #6b7280;
        }
        .est-rank-1    { background: #d1fae5; color: #065f46; }
        .est-rank-last { background: #fee2e2; color: #991b1b; }
        .est-vs-badge  { display: inline-block; font-size: .72rem; font-weight: 600; padding: 2px 9px; border-radius: 999px; white-space: nowrap; }
        .est-vs-below  { background: #d1fae5; color: #065f46; }
        .est-vs-at     { background: #e5e7eb; color: #374151; }
        .est-vs-above  { background: #fee2e2; color: #991b1b; }
        .srp-reference-bar {
            display: flex; align-items: center; gap: 8px;
            background: linear-gradient(90deg,#eff6ff,#f0f9ff);
            border-bottom: 1px solid #bfdbfe; padding: 10px 24px; font-size: .875rem; color: #1d4ed8;
        }
        .est-legend {
            display: flex; align-items: center; gap: 16px; padding: 8px 24px;
            background: var(--bg); border-bottom: 1px solid var(--line);
            font-size: .75rem; color: var(--muted); flex-wrap: wrap;
        }
        .est-legend-item { display: flex; align-items: center; gap: 5px; }
        .est-legend-dot  { width: 12px; height: 12px; border-radius: 3px; flex-shrink: 0; }
        .est-count-badge {
            font-size: .72rem; font-weight: 600; padding: 2px 10px;
            border-radius: 999px; background: var(--accent-soft); color: var(--accent);
        }
        .est-summary { display: flex; align-items: center; gap: 6px; font-size: .82rem; flex-wrap: wrap; }
        .est-summary-item { display: flex; flex-direction: column; line-height: 1.2; }
        .est-summary-label { font-size: .65rem; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); }
        .est-summary-value { font-size: .88rem; color: var(--ink); font-weight: 600; }
        .est-summary-sep   { color: #d1d5db; font-size: 1rem; }

        /* ── Price History tab pane ──────────────────────────── */
        .ph-pane-inner { padding: 0; }

        /* Range pills */
        .ph-range-bar {
            display: flex; align-items: center; gap: 8px;
            padding: 1rem 1.5rem .75rem;
            border-bottom: 1px solid var(--line);
        }
        .ph-range-label {
            font-size: .7rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: .07em; color: var(--muted); margin-right: 2px; white-space: nowrap;
        }
        .ph-pill {
            padding: 4px 14px; border-radius: 999px; font-size: .75rem; font-weight: 600;
            border: 1.5px solid var(--line); background: #fff; color: var(--muted);
            cursor: pointer; transition: all .15s ease; font-family: inherit;
        }
        .ph-pill:hover  { border-color: var(--accent); color: var(--accent); background: var(--accent-soft); }
        .ph-pill.active { border-color: var(--accent); background: var(--accent); color: #fff; }

        /* Stat row */
        .ph-stats { display: flex; gap: 8px; padding: 1rem 1.5rem .75rem; flex-wrap: wrap; }
        .ph-stat {
            flex: 1; min-width: 80px; background: #fff;
            border-radius: 10px; padding: 9px 12px;
            border: 1px solid var(--line);
            border-left: 3px solid var(--line);
        }
        .ph-stat:nth-child(1) { border-left-color: var(--accent); }
        .ph-stat:nth-child(2) { border-left-color: #16a34a; }
        .ph-stat:nth-child(3) { border-left-color: #dc2626; }
        .ph-stat:nth-child(4) { border-left-color: #7c3aed; }
        .ph-stat:nth-child(5) { border-left-color: #d97706; }
        .ph-stat-label { font-size: .6rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); margin-bottom: 2px; }
        .ph-stat-value { font-size: 1rem; font-weight: 700; color: var(--ink); line-height: 1.2; }
        .ph-stat-value.ph-up   { color: #dc2626; }
        .ph-stat-value.ph-down { color: #16a34a; }

        /* Chart area — fixed height so it never jumps */
        .ph-chart-area {
            padding: 0 1.5rem .5rem;
            position: relative;
            height: 220px;
        }
        .ph-chart-area canvas {
            position: absolute;
            top: 0; left: 1.5rem;
            width: calc(100% - 3rem) !important;
            height: 220px !important;
        }
        /* Loading overlay sits on top of canvas */
        .ph-chart-overlay {
            position: absolute; inset: 0;
            display: flex; align-items: center; justify-content: center;
            background: rgba(255,255,255,.92); z-index: 3;
            gap: 8px; font-size: .85rem; color: var(--muted);
            border-radius: 8px; pointer-events: none;
        }

        /* Chart legend row */
        .ph-chart-legend {
            display: flex; align-items: center; gap: 18px;
            padding: 0 1.5rem .75rem; font-size: .75rem; color: var(--muted);
        }
        .ph-leg-item { display: flex; align-items: center; gap: 6px; }
        .ph-leg-swatch {
            display: inline-block; width: 22px; height: 3px; border-radius: 2px; flex-shrink: 0;
        }
        .ph-leg-dashed {
            background: transparent !important;
            border-top: 2.5px dashed #3b82f6; height: 0;
        }

        /* Empty / building states */
        .ph-empty-state {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            height: 220px; color: var(--muted); gap: 8px; font-size: .85rem;
        }
        .ph-empty-state i { font-size: 2rem; color: #d1d5db; }
        .ph-building-notice {
            display: flex; align-items: flex-start; gap: 10px;
            background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px;
            padding: 12px 16px; margin: 0 1.5rem 1rem; font-size: .82rem; color: #92400e;
        }
        .ph-building-notice i { font-size: 1rem; flex-shrink: 0; color: #d97706; margin-top: 1px; }

        /* History table */
        .ph-table-wrap {
            padding: 0 1.5rem 1.25rem; max-height: 200px; overflow-y: auto;
        }
        .ph-table-wrap::-webkit-scrollbar { width: 4px; }
        .ph-table-wrap::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        .ph-table { width: 100%; border-collapse: collapse; font-size: .82rem; }
        .ph-table thead th {
            position: sticky; top: 0; background: #fff; z-index: 1;
            font-size: .67rem; text-transform: uppercase; letter-spacing: .06em;
            color: var(--muted); padding: 6px 10px; font-weight: 600;
            border-bottom: 1px solid var(--line);
        }
        .ph-table tbody td { padding: 7px 10px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
        .ph-table tbody tr:last-child td { border-bottom: none; }
        .ph-table tbody tr:hover td { background: var(--bg); }
        .ph-chg-up   { color: #dc2626; font-weight: 600; }
        .ph-chg-down { color: #16a34a; font-weight: 600; }
        .ph-chg-flat { color: var(--muted); }
        .ph-badge-active   { background: #d1fae5; color: #065f46; font-size: .68rem; font-weight: 700; padding: 2px 8px; border-radius: 999px; }
        .ph-badge-inactive { background: #f3f4f6; color: #6b7280;  font-size: .68rem; font-weight: 700; padding: 2px 8px; border-radius: 999px; }

        /* ── Footer ──────────────────────────────────────────── */
        .site-footer {
            border-top: 1px solid var(--line); background: #fff;
            padding: 1.5rem 1rem; text-align: center;
            color: var(--muted); font-size: .85rem; margin-top: 2rem;
        }
        .site-footer a { color: var(--accent); text-decoration: none; }
        .site-footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <header class="site-header">
        <div class="container">
            <img src="../../../dist/img/splogo.png" alt="San Carlos City seal" class="seal">
            <div class="kicker">San Carlos City &middot; Negosyo Center</div>
            <h1>Price Monitor</h1>
            <p class="subtitle">Browse retail prices by category and track how prices change over time.</p>
            <div class="header-pill">
                <i class="fas fa-circle" style="font-size:8px;color:#4ade80;"></i>
                Live Monitoring
            </div>
        </div>
    </header>

    <main class="container category-section mb-4">
        <div class="row" id="categoryGrid">
            <div class="col-12 text-center text-muted py-5">
                <i class="fas fa-spinner fa-spin fa-2x mb-3" style="color:var(--accent);display:block;"></i>
                Loading categories…
            </div>
        </div>
    </main>

    <!-- ── Category commodities modal ─────────────────────────────── -->
    <div class="modal fade" id="categoryCommoditiesModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header align-items-center">
                    <h5 class="modal-title" id="modalCategoryTitle">
                        <i class="fas fa-box-open mr-2" style="color:var(--accent);"></i>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body py-2 px-3">
                    <div class="commodity-search">
                        <i class="fas fa-search"></i>
                        <input type="text" id="commoditySearch" placeholder="Search commodity…" autocomplete="off">
                    </div>
                    <div id="commodityList"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-ghost" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Establishment detail + Price History modal ─────────────── -->
    <div class="modal fade" id="establishmentDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">

                <!-- Modal header (shared) -->
                <div class="modal-header align-items-start">
                    <div>
                        <h5 class="modal-title mb-1" id="modalCommodityTitle">Commodity Prices</h5>
                        <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                            <small class="text-muted" id="modalCommoditySubtitle"></small>
                            <span class="est-count-badge" id="estCountBadge" style="display:none;"></span>
                        </div>
                    </div>
                    <button type="button" class="close ml-auto" data-dismiss="modal">&times;</button>
                </div>

                <!-- Tab navigation -->
                <div class="modal-tabs" role="tablist">
                    <button class="modal-tab-btn active" data-modal-tab="est" role="tab" aria-selected="true">
                        <i class="fas fa-store"></i> Establishment Prices
                    </button>
                    <button class="modal-tab-btn" data-modal-tab="hist" role="tab" aria-selected="false">
                        <i class="fas fa-chart-line"></i> Price History
                    </button>
                </div>

                <!-- ══ Pane 1: Establishment Prices ══ -->
                <div class="modal-tab-pane active" id="pane-est">
                    <div class="pane-scroll">

                        <!-- SRP bar -->
                        <div class="srp-reference-bar" id="srpReferenceBar" style="display:none;">
                            <i class="fas fa-tag" style="font-size:13px;"></i>
                            <span style="color:#3b82f6;font-weight:500;">Suggested Retail Price (SRP):</span>
                            <span style="font-weight:700;" id="srpReferenceValue"></span>
                        </div>

                        <!-- Legend -->
                        <div class="est-legend" id="estLegend" style="display:none;">
                            <span class="est-legend-item"><span class="est-legend-dot" style="background:#d1fae5;border:1.5px solid #10b981;"></span>Lowest price</span>
                            <span class="est-legend-item"><span class="est-legend-dot" style="background:#fee2e2;border:1.5px solid #ef4444;"></span>Highest price</span>
                            <span class="est-legend-item"><span class="est-legend-dot" style="background:#fef9c3;border:1.5px solid #f59e0b;"></span>Above SRP</span>
                        </div>

                        <!-- Establishment comparison table -->
                        <div class="table-responsive">
                            <table class="table establishment-table mb-0" id="estCompareTable">
                                <thead>
                                    <tr>
                                        <th class="pl-4" style="width:40px;">#</th>
                                        <th>Establishment / Store</th>
                                        <th class="text-right">SRP (&#8369;)</th>
                                        <th class="text-right pr-4">Prevailing Price (&#8369;)</th>
                                        <th class="text-center pr-4">vs SRP</th>
                                    </tr>
                                </thead>
                                <tbody id="establishmentListBody"></tbody>
                            </table>
                        </div>

                    </div><!-- /pane-scroll -->

                    <!-- Pane 1 footer -->
                    <div class="modal-footer justify-content-between">
                        <div class="est-summary" id="estSummary" style="display:none;">
                            <span class="est-summary-item">
                                <span class="est-summary-label">Avg Price</span>
                                <span class="est-summary-value" id="estAvgPrice">—</span>
                            </span>
                            <span class="est-summary-sep">·</span>
                            <span class="est-summary-item">
                                <span class="est-summary-label">Lowest</span>
                                <span class="est-summary-value" style="color:#16a34a;" id="estLowestPrice">—</span>
                            </span>
                            <span class="est-summary-sep">·</span>
                            <span class="est-summary-item">
                                <span class="est-summary-label">Highest</span>
                                <span class="est-summary-value" style="color:#dc2626;" id="estHighestPrice">—</span>
                            </span>
                        </div>
                        <div class="ml-auto d-flex" style="gap:8px;">
                            <button type="button" class="btn-ghost" id="btnBackToCommodities">
                                <i class="fas fa-arrow-left mr-1"></i>Back
                            </button>
                            <button type="button" class="btn-ghost" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div><!-- /pane-est -->

                <!-- ══ Pane 2: Price History ══ -->
                <div class="modal-tab-pane" id="pane-hist">
                    <div class="pane-scroll">
                        <div class="ph-pane-inner">

                            <!-- Range pills -->
                            <div class="ph-range-bar">
                                <span class="ph-range-label">Range:</span>
                                <button class="ph-pill" data-range="7d">7D</button>
                                <button class="ph-pill active" data-range="30d">30D</button>
                                <button class="ph-pill" data-range="90d">90D</button>
                                <button class="ph-pill" data-range="all">All Time</button>
                            </div>

                            <!-- Stat cards -->
                            <div class="ph-stats" id="phStats">
                                <div class="ph-stat">
                                    <div class="ph-stat-label">Current</div>
                                    <div class="ph-stat-value" id="phStatCurrent">—</div>
                                </div>
                                <div class="ph-stat">
                                    <div class="ph-stat-label">Period Low</div>
                                    <div class="ph-stat-value ph-down" id="phStatLow">—</div>
                                </div>
                                <div class="ph-stat">
                                    <div class="ph-stat-label">Period High</div>
                                    <div class="ph-stat-value ph-up" id="phStatHigh">—</div>
                                </div>
                                <div class="ph-stat">
                                    <div class="ph-stat-label">Change</div>
                                    <div class="ph-stat-value" id="phStatChange">—</div>
                                </div>
                                <div class="ph-stat">
                                    <div class="ph-stat-label">Records</div>
                                    <div class="ph-stat-value" id="phStatCount">—</div>
                                </div>
                            </div>

                            <!-- Chart — always visible, overlay covers it while loading -->
                            <div class="ph-chart-area" id="phChartArea">
                                <div class="ph-chart-overlay" id="phLoading">
                                    <i class="fas fa-spinner fa-spin" style="color:var(--accent);"></i>
                                    <span>Loading chart…</span>
                                </div>
                                <canvas id="phChart"></canvas>
                            </div>

                            <!-- Chart legend -->
                            <div class="ph-chart-legend">
                                <span class="ph-leg-item">
                                    <span class="ph-leg-swatch" style="background:#028090;"></span>
                                    Prevailing Price
                                </span>
                                <span class="ph-leg-item">
                                    <span class="ph-leg-swatch ph-leg-dashed"></span>
                                    SRP
                                </span>
                            </div>

                            <!-- Empty state (no data) -->
                            <div class="ph-empty-state" id="phEmptyState" style="display:none;">
                                <i class="fas fa-chart-line"></i>
                                <span id="phEmptyMsg">No price records in this period.</span>
                            </div>

                            <!-- Building notice (< 3 data points) -->
                            <div class="ph-building-notice" id="phBuildingNotice" style="display:none;">
                                <i class="fas fa-info-circle"></i>
                                <span>Price history is still building. Records will appear here as prices are monitored over time.</span>
                            </div>

                            <!-- History table -->
                            <div class="ph-table-wrap" id="phTableWrap" style="display:none;">
                                <table class="ph-table">
                                    <thead>
                                        <tr>
                                            <th>Date &amp; Time</th>
                                            <th class="text-right">Prevailing (&#8369;)</th>
                                            <th class="text-right">SRP (&#8369;)</th>
                                            <th class="text-center">Change</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="phTableBody"></tbody>
                                </table>
                            </div>

                        </div>
                    </div><!-- /pane-scroll -->

                    <!-- Pane 2 footer -->
                    <div class="modal-footer justify-content-between">
                        <small class="text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Mock data shown — backend integration coming soon.
                        </small>
                        <div class="d-flex" style="gap:8px;">
                            <button type="button" class="btn-ghost" id="btnBackToCommoditiesHist">
                                <i class="fas fa-arrow-left mr-1"></i>Back
                            </button>
                            <button type="button" class="btn-ghost" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div><!-- /pane-hist -->

            </div><!-- /modal-content -->
        </div>
    </div>

    <footer class="site-footer">
        <div class="container">
            &copy; <span id="year"></span> San Carlos City &middot; Negosyo Center &mdash;
            <a href="http://lguscc.gov.ph/" target="_blank" rel="noopener">Local Government of San Carlos City</a>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script src="../../../scripts/price-monitoring/price-view.js?v=5"></script>
    <script>document.getElementById('year').textContent = new Date().getFullYear();</script>
</body>
</html>
