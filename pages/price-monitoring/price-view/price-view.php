<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Price Monitor | Negosyo Center</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" type="image/png" sizes="40x16" href="../../../dist/img/splogo.png">

    <style>
        /* ═══════════════════════════════════════════════════════
           DESIGN TOKENS — dark minimalist + soft glassmorphism
        ═══════════════════════════════════════════════════════ */
        :root {
            --bg:           #0d0f14;
            --bg2:          #13161e;
            --surface:      rgba(255,255,255,.045);
            --surface-high: rgba(255,255,255,.07);
            --border:       rgba(255,255,255,.08);
            --border-soft:  rgba(255,255,255,.05);
            --accent:       #00d4e8;
            --accent-dim:   rgba(0,212,232,.18);
            --accent-glow:  rgba(0,212,232,.08);
            --green:        #10b981;
            --green-dim:    rgba(16,185,129,.18);
            --red:          #f87171;
            --red-dim:      rgba(248,113,113,.18);
            --amber:        #fbbf24;
            --amber-dim:    rgba(251,191,36,.14);
            --ink:          #f0f2f6;
            --ink-soft:     rgba(240,242,246,.7);
            --muted:        rgba(240,242,246,.38);
            --muted2:       rgba(240,242,246,.22);
            --blur:         blur(18px);
        }

        * { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--ink);
            margin: 0;
            min-height: 100vh;
        }

        /* subtle animated gradient mesh behind everything */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background:
                radial-gradient(ellipse 80vw 60vh at 10% 0%, rgba(0,212,232,.06) 0%, transparent 60%),
                radial-gradient(ellipse 60vw 50vh at 90% 100%, rgba(139,92,246,.05) 0%, transparent 60%);
        }

        /* ── Scrollbar ───────────────────────────────────────── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,.22); }

        /* ── Header ─────────────────────────────────────────── */
        .site-header {
            position: relative; z-index: 1;
            padding: 4rem 1rem 3.5rem;
            text-align: center;
            background: linear-gradient(180deg, rgba(0,212,232,.04) 0%, transparent 100%);
            border-bottom: 1px solid var(--border-soft);
            overflow: hidden;
        }
        /* decorative rings */
        .site-header::before, .site-header::after {
            content: '';
            position: absolute; border-radius: 50%; pointer-events: none;
        }
        .site-header::before {
            width: 520px; height: 520px;
            top: -260px; left: 50%; transform: translateX(-50%);
            border: 1px solid rgba(0,212,232,.06);
        }
        .site-header::after {
            width: 320px; height: 320px;
            top: -160px; left: 50%; transform: translateX(-50%);
            border: 1px solid rgba(0,212,232,.1);
        }

        .header-seal-wrap {
            position: relative; display: inline-block; margin-bottom: 1.25rem;
        }
        .header-seal-wrap::before {
            content: '';
            position: absolute; inset: -10px; border-radius: 50%;
            background: radial-gradient(circle, var(--accent-dim) 0%, transparent 70%);
        }
        .site-header .seal {
            width: 64px; height: 64px; object-fit: contain;
            position: relative; filter: drop-shadow(0 0 16px rgba(0,212,232,.3));
        }
        .site-header .kicker {
            font-size: .68rem; font-weight: 600; letter-spacing: .22em;
            text-transform: uppercase; color: var(--accent); margin-bottom: .6rem;
            position: relative;
        }
        .site-header h1 {
            font-size: clamp(2rem, 5vw, 3rem); font-weight: 700;
            letter-spacing: -.04em; color: var(--ink); margin-bottom: .6rem;
            position: relative;
        }
        .site-header h1 span { color: var(--accent); }
        .site-header .subtitle {
            color: var(--muted); max-width: 32rem; margin: 0 auto; font-size: .95rem;
            font-weight: 400; line-height: 1.6; position: relative;
        }
        .header-pill {
            display: inline-flex; align-items: center; gap: 7px;
            margin-top: 1.5rem; position: relative;
            background: var(--surface); backdrop-filter: var(--blur);
            border: 1px solid var(--border); border-radius: 999px;
            padding: 6px 16px; font-size: .75rem; font-weight: 600;
            color: var(--ink-soft); letter-spacing: .02em;
        }
        .header-pill-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--green);
            box-shadow: 0 0 8px var(--green);
            animation: pulse-dot 2s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: .4; }
        }

        /* ── Category grid ───────────────────────────────────── */
        .category-section { padding: 3rem 0 1.5rem; position: relative; z-index: 1; }

        .category-card {
            background: var(--surface);
            backdrop-filter: var(--blur);
            -webkit-backdrop-filter: var(--blur);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem 1.25rem 1.5rem;
            text-align: center; cursor: pointer; height: 100%;
            transition: border-color .25s, box-shadow .25s, transform .25s, background .25s;
            position: relative; overflow: hidden;
        }
        .category-card::before {
            content: '';
            position: absolute; inset: 0; border-radius: 20px;
            background: radial-gradient(ellipse at 50% 0%, var(--accent-glow), transparent 70%);
            opacity: 0; transition: opacity .25s;
        }
        .category-card:hover {
            border-color: rgba(0,212,232,.35);
            box-shadow: 0 0 0 1px rgba(0,212,232,.15), 0 16px 48px rgba(0,0,0,.4);
            transform: translateY(-5px);
            background: var(--surface-high);
        }
        .category-card:hover::before { opacity: 1; }
        .category-card:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-dim); }

        .category-icon {
            width: 58px; height: 58px; border-radius: 16px;
            background: var(--accent-dim);
            border: 1px solid rgba(0,212,232,.2);
            color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; margin: 0 auto 1rem;
            transition: background .25s, transform .25s, box-shadow .25s;
            position: relative;
        }
        .category-card:hover .category-icon {
            background: var(--accent);
            color: #0d0f14;
            box-shadow: 0 0 20px rgba(0,212,232,.4);
            transform: scale(1.08);
        }
        .category-name  { font-weight: 600; font-size: .95rem; color: var(--ink); margin-bottom: .3rem; }
        .category-agency { font-size: .75rem; color: var(--muted); }
        .category-count { margin-top: .8rem; }
        .category-count .badge {
            background: var(--accent-dim); color: var(--accent);
            font-weight: 600; font-size: .7rem; padding: .3em .85em; border-radius: 999px;
            border: 1px solid rgba(0,212,232,.2);
        }

        /* ── Modals (glassmorphism) ───────────────────────────── */
        .modal-content {
            background: rgba(19,22,30,.88);
            backdrop-filter: var(--blur);
            -webkit-backdrop-filter: var(--blur);
            border: 1px solid var(--border);
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,.6), 0 0 0 1px rgba(255,255,255,.04);
            color: var(--ink);
        }
        .modal-header {
            border-bottom: 1px solid var(--border);
            padding: 1.25rem 1.5rem;
            background: transparent;
        }
        .modal-title { font-weight: 700; font-size: 1.05rem; color: var(--ink); }
        .modal-footer {
            border-top: 1px solid var(--border);
            padding: 1rem 1.5rem;
            background: transparent;
        }
        .modal-backdrop { background: rgba(0,0,0,.7); }

        /* close button */
        .close { color: var(--muted); opacity: 1; text-shadow: none; font-size: 1.4rem; }
        .close:hover { color: var(--ink); }

        /* ghost button */
        .btn-ghost {
            background: var(--surface); border: 1px solid var(--border);
            color: var(--ink-soft); border-radius: 999px; padding: .4rem 1.3rem;
            font-size: .85rem; font-weight: 500; cursor: pointer; font-family: inherit;
            transition: border-color .18s, color .18s, background .18s;
        }
        .btn-ghost:hover {
            border-color: rgba(0,212,232,.4); color: var(--accent);
            background: var(--accent-glow);
        }

        /* ── Commodity list modal ────────────────────────────── */
        .commodity-search { position: relative; margin: .5rem 0 .75rem; }
        .commodity-search i {
            position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
            color: var(--muted); font-size: .88rem;
        }
        .commodity-search input {
            width: 100%;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: .65rem 1rem .65rem 2.5rem;
            font-size: .92rem; color: var(--ink);
            font-family: inherit;
            transition: border-color .18s, box-shadow .18s;
        }
        .commodity-search input::placeholder { color: var(--muted); }
        .commodity-search input:focus {
            outline: none; border-color: rgba(0,212,232,.45);
            box-shadow: 0 0 0 3px var(--accent-dim);
            background: var(--surface-high);
        }

        .commodity-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: .8rem 1rem; border-bottom: 1px solid var(--border-soft);
            border-radius: 10px; cursor: pointer;
            transition: background .15s, transform .12s;
        }
        .commodity-row:hover { background: var(--surface-high); transform: translateX(4px); }
        .commodity-row:last-child { border-bottom: none; }
        .commodity-name { font-weight: 600; font-size: .92rem; color: var(--ink); }
        .commodity-meta { font-size: .76rem; color: var(--muted); margin-top: .1rem; }
        .commodity-arrow { color: var(--muted); font-size: .78rem; margin-left: 1rem; white-space: nowrap; }
        .commodity-empty { text-align: center; color: var(--muted); padding: 2.5rem 1rem; font-size: .88rem; }

        /* ── Modal inner tabs ────────────────────────────────── */
        .modal-tabs {
            display: flex;
            border-bottom: 1px solid var(--border);
            background: transparent;
            padding: 0 1.5rem;
            gap: 4px;
        }
        .modal-tab-btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: .8rem 1rem;
            font-size: .8rem; font-weight: 600;
            color: var(--muted); background: none; border: none;
            border-bottom: 2px solid transparent;
            margin-bottom: -1px; cursor: pointer;
            transition: color .15s, border-color .15s;
            white-space: nowrap; font-family: inherit; outline: none;
            letter-spacing: .01em;
        }
        .modal-tab-btn:hover { color: var(--ink-soft); }
        .modal-tab-btn.active { color: var(--accent); border-bottom-color: var(--accent); }
        .modal-tab-btn i { font-size: .85rem; }

        /* ── Tab panes ───────────────────────────────────────── */
        .modal-tab-pane { display: none; }
        .modal-tab-pane.active { display: block; }
        .pane-scroll { max-height: 68vh; overflow-y: auto; }

        /* ── Establishment prices table ──────────────────────── */
        .establishment-table th {
            font-size: .7rem; text-transform: uppercase; letter-spacing: .07em;
            color: var(--muted); background: rgba(255,255,255,.02); border-top: none; font-weight: 600;
            border-color: var(--border) !important;
        }
        .establishment-table td { vertical-align: middle; font-size: .88rem; color: var(--ink-soft); border-color: var(--border-soft) !important; }
        .establishment-table tbody tr:hover td { background: var(--surface-high) !important; }

        /* row highlights in dark */
        .est-row-lowest   td { background: rgba(16,185,129,.1)  !important; border-color: rgba(16,185,129,.2) !important; }
        .est-row-highest  td { background: rgba(248,113,113,.1) !important; border-color: rgba(248,113,113,.2) !important; }
        .est-row-above-srp td{ background: rgba(251,191,36,.08) !important; border-color: rgba(251,191,36,.2) !important; }

        .est-rank {
            display: inline-flex; align-items: center; justify-content: center;
            width: 26px; height: 26px; border-radius: 50%;
            font-size: .7rem; font-weight: 700;
            background: rgba(255,255,255,.08); color: var(--muted);
        }
        .est-rank-1    { background: rgba(16,185,129,.25); color: var(--green); }
        .est-rank-last { background: rgba(248,113,113,.25); color: var(--red); }

        .est-vs-badge { display: inline-block; font-size: .7rem; font-weight: 600; padding: 2px 9px; border-radius: 999px; white-space: nowrap; }
        .est-vs-below { background: var(--green-dim); color: var(--green); }
        .est-vs-at    { background: rgba(255,255,255,.08); color: var(--muted); }
        .est-vs-above { background: var(--red-dim); color: var(--red); }

        .srp-reference-bar {
            display: flex; align-items: center; gap: 8px;
            background: rgba(59,130,246,.08);
            border-bottom: 1px solid rgba(59,130,246,.15);
            padding: 10px 24px; font-size: .85rem; color: #93c5fd;
        }
        .est-legend {
            display: flex; align-items: center; gap: 16px; padding: 8px 24px;
            background: rgba(255,255,255,.02); border-bottom: 1px solid var(--border-soft);
            font-size: .73rem; color: var(--muted); flex-wrap: wrap;
        }
        .est-legend-item { display: flex; align-items: center; gap: 5px; }
        .est-legend-dot  { width: 11px; height: 11px; border-radius: 3px; flex-shrink: 0; }

        .est-count-badge {
            font-size: .7rem; font-weight: 600; padding: 2px 10px;
            border-radius: 999px; background: var(--accent-dim); color: var(--accent);
            border: 1px solid rgba(0,212,232,.2);
        }
        .est-summary { display: flex; align-items: center; gap: 6px; font-size: .8rem; flex-wrap: wrap; }
        .est-summary-item { display: flex; flex-direction: column; line-height: 1.2; }
        .est-summary-label { font-size: .63rem; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); }
        .est-summary-value { font-size: .87rem; color: var(--ink); font-weight: 600; }
        .est-summary-sep   { color: var(--border); font-size: 1rem; }

        /* ── Price History tab ───────────────────────────────── */
        .ph-range-bar {
            display: flex; align-items: center; gap: 8px;
            padding: 1rem 1.5rem .75rem;
            border-bottom: 1px solid var(--border-soft);
        }
        .ph-range-label {
            font-size: .68rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: .08em; color: var(--muted); margin-right: 2px; white-space: nowrap;
        }
        .ph-pill {
            padding: 4px 14px; border-radius: 999px; font-size: .73rem; font-weight: 600;
            border: 1px solid var(--border); background: var(--surface); color: var(--muted);
            cursor: pointer; transition: all .15s ease; font-family: inherit;
        }
        .ph-pill:hover  { border-color: rgba(0,212,232,.35); color: var(--accent); background: var(--accent-glow); }
        .ph-pill.active { border-color: rgba(0,212,232,.5); background: var(--accent-dim); color: var(--accent); }

        /* Stat cards */
        .ph-stats { display: flex; gap: 8px; padding: 1rem 1.5rem .75rem; flex-wrap: wrap; }
        .ph-stat {
            flex: 1; min-width: 78px;
            background: var(--surface);
            backdrop-filter: var(--blur);
            border: 1px solid var(--border);
            border-left: 2px solid var(--border);
            border-radius: 10px; padding: 9px 12px;
        }
        .ph-stat:nth-child(1) { border-left-color: var(--accent); }
        .ph-stat:nth-child(2) { border-left-color: var(--green); }
        .ph-stat:nth-child(3) { border-left-color: var(--red); }
        .ph-stat:nth-child(4) { border-left-color: #a78bfa; }
        .ph-stat:nth-child(5) { border-left-color: var(--amber); }
        .ph-stat-label { font-size: .59rem; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: var(--muted); margin-bottom: 3px; }
        .ph-stat-value { font-size: .97rem; font-weight: 700; color: var(--ink); line-height: 1.2; }
        .ph-stat-value.ph-up   { color: var(--red); }
        .ph-stat-value.ph-down { color: var(--green); }

        /* Chart container */
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
        .ph-chart-overlay {
            position: absolute; inset: 0;
            display: flex; align-items: center; justify-content: center;
            background: rgba(13,15,20,.85); z-index: 3;
            gap: 8px; font-size: .83rem; color: var(--muted);
            border-radius: 8px; pointer-events: none;
        }

        /* Chart legend */
        .ph-chart-legend {
            display: flex; align-items: center; gap: 18px;
            padding: 0 1.5rem .75rem; font-size: .73rem; color: var(--muted);
        }
        .ph-leg-item { display: flex; align-items: center; gap: 7px; }
        .ph-leg-swatch { display: inline-block; width: 20px; height: 2.5px; border-radius: 2px; flex-shrink: 0; }
        .ph-leg-dashed { background: transparent !important; border-top: 2px dashed #3b82f6; height: 0; }

        /* Empty / building */
        .ph-empty-state {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            height: 220px; color: var(--muted); gap: 8px; font-size: .85rem;
        }
        .ph-empty-state i { font-size: 2rem; color: rgba(255,255,255,.1); }
        .ph-building-notice {
            display: flex; align-items: flex-start; gap: 10px;
            background: var(--amber-dim); border: 1px solid rgba(251,191,36,.2);
            border-radius: 10px; padding: 12px 16px; margin: 0 1.5rem 1rem;
            font-size: .8rem; color: var(--amber);
        }
        .ph-building-notice i { font-size: 1rem; flex-shrink: 0; margin-top: 1px; }

        /* History table */
        .ph-table-wrap {
            padding: 0 1.5rem 1.25rem; max-height: 200px; overflow-y: auto;
        }
        .ph-table { width: 100%; border-collapse: collapse; font-size: .8rem; }
        .ph-table thead th {
            position: sticky; top: 0; background: rgba(13,15,20,.95); z-index: 1;
            font-size: .65rem; text-transform: uppercase; letter-spacing: .07em;
            color: var(--muted); padding: 6px 10px; font-weight: 600;
            border-bottom: 1px solid var(--border);
        }
        .ph-table tbody td { padding: 7px 10px; border-bottom: 1px solid var(--border-soft); vertical-align: middle; color: var(--ink-soft); }
        .ph-table tbody tr:last-child td { border-bottom: none; }
        .ph-table tbody tr:hover td { background: var(--surface); }
        .ph-chg-up   { color: var(--red); font-weight: 600; }
        .ph-chg-down { color: var(--green); font-weight: 600; }
        .ph-chg-flat { color: var(--muted); }
        .ph-badge-active   { background: var(--green-dim); color: var(--green); font-size: .67rem; font-weight: 700; padding: 2px 8px; border-radius: 999px; }
        .ph-badge-inactive { background: rgba(255,255,255,.06); color: var(--muted); font-size: .67rem; font-weight: 700; padding: 2px 8px; border-radius: 999px; }

        /* ── Footer ──────────────────────────────────────────── */
        .site-footer {
            position: relative; z-index: 1;
            border-top: 1px solid var(--border-soft);
            background: transparent;
            padding: 2rem 1rem;
            text-align: center;
            color: var(--muted); font-size: .82rem; margin-top: 2rem;
        }
        .site-footer a { color: var(--accent); text-decoration: none; opacity: .8; }
        .site-footer a:hover { opacity: 1; }

        /* ── Utility ─────────────────────────────────────────── */
        .text-accent { color: var(--accent); }
    </style>
</head>
<body>

    <header class="site-header">
        <div class="container">
            <div class="header-seal-wrap">
                <img src="../../../dist/img/splogo.png" alt="San Carlos City seal" class="seal">
            </div>
            <div class="kicker">San Carlos City &middot; Negosyo Center</div>
            <h1>Price <span>Monitor</span></h1>
            <p class="subtitle">Browse retail prices by category and track how prices change over time.</p>
            <div class="header-pill">
                <span class="header-pill-dot"></span>
                Live Monitoring
            </div>
        </div>
    </header>

    <main class="container category-section mb-4">
        <div class="row" id="categoryGrid">
            <div class="col-12 text-center py-5" style="color:var(--muted);">
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
                        <i class="fas fa-box-open mr-2 text-accent"></i>
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
                            <small style="color:var(--muted);" id="modalCommoditySubtitle"></small>
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
                            <span style="color:#93c5fd;font-weight:500;">Suggested Retail Price (SRP):</span>
                            <span style="font-weight:700;" id="srpReferenceValue"></span>
                        </div>

                        <!-- Legend -->
                        <div class="est-legend" id="estLegend" style="display:none;">
                            <span class="est-legend-item"><span class="est-legend-dot" style="background:rgba(16,185,129,.4);border:1px solid rgba(16,185,129,.5);"></span>Lowest</span>
                            <span class="est-legend-item"><span class="est-legend-dot" style="background:rgba(248,113,113,.4);border:1px solid rgba(248,113,113,.5);"></span>Highest</span>
                            <span class="est-legend-item"><span class="est-legend-dot" style="background:rgba(251,191,36,.3);border:1px solid rgba(251,191,36,.4);"></span>Above SRP</span>
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
                                <span class="est-summary-value" style="color:var(--green);" id="estLowestPrice">—</span>
                            </span>
                            <span class="est-summary-sep">·</span>
                            <span class="est-summary-item">
                                <span class="est-summary-label">Highest</span>
                                <span class="est-summary-value" style="color:var(--red);" id="estHighestPrice">—</span>
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

                            <!-- Chart — always visible, overlay covers while loading -->
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
                                    <span class="ph-leg-swatch" style="background:var(--accent);"></span>
                                    Prevailing Price
                                </span>
                                <span class="ph-leg-item">
                                    <span class="ph-leg-swatch ph-leg-dashed"></span>
                                    SRP
                                </span>
                            </div>

                            <!-- Empty state -->
                            <div class="ph-empty-state" id="phEmptyState" style="display:none;">
                                <i class="fas fa-chart-line"></i>
                                <span id="phEmptyMsg">No price records in this period.</span>
                            </div>

                            <!-- Building notice -->
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
                        <small style="color:var(--muted);">
                            <i class="fas fa-info-circle mr-1"></i>
                            Mock data — backend integration coming soon.
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
    <script src="../../../scripts/price-monitoring/price-view.js?v=6"></script>
    <script>document.getElementById('year').textContent = new Date().getFullYear();</script>
</body>
</html>
