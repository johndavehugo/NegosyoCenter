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

    <style>
        :root {
            --accent: #028090;
            --accent-soft: #e6f4f5;
            --ink: #1a1a1a;
            --muted: #6b7280;
            --line: #e8e8e8;
            --bg: #fafafa;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--ink);
        }

        /* ── Header ─────────────────────────────── */
        .site-header {
            background: #ffffff;
            border-bottom: 1px solid var(--line);
            padding: 2.75rem 1rem 2.25rem;
            text-align: center;
        }
        .site-header .seal {
            width: 56px;
            height: 56px;
            object-fit: contain;
            margin-bottom: 0.75rem;
        }
        .site-header .kicker {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 0.5rem;
        }
        .site-header h1 {
            font-size: clamp(1.8rem, 4vw, 2.6rem);
            font-weight: 600;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }
        .site-header .subtitle {
            color: var(--muted);
            max-width: 34rem;
            margin: 0 auto;
            font-size: 1rem;
        }

        /* ── Category grid ──────────────────────── */
        .category-section {
            padding: 2.5rem 0 1rem;
        }
        .category-card {
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 1.75rem 1rem;
            text-align: center;
            cursor: pointer;
            height: 100%;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }
        .category-card:hover {
            border-color: var(--accent);
            box-shadow: 0 10px 30px rgba(2, 128, 144, 0.10);
            transform: translateY(-3px);
        }
        .category-card:focus {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }
        .category-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--accent-soft);
            color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin: 0 auto 1rem auto;
            transition: background 0.2s ease, color 0.2s ease;
        }
        .category-card:hover .category-icon {
            background: var(--accent);
            color: #ffffff;
        }
        .category-name {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }
        .category-agency {
            font-size: 0.8rem;
            color: var(--muted);
        }
        .category-count {
            margin-top: 0.75rem;
        }
        .category-count .badge {
            background: var(--accent-soft);
            color: var(--accent);
            font-weight: 600;
            font-size: 0.72rem;
            padding: 0.35em 0.75em;
            border-radius: 999px;
        }

        /* ── Modal ──────────────────────────────── */
        .modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
        }
        .modal-header {
            border-bottom: 1px solid var(--line);
            padding: 1.25rem 1.5rem;
        }
        .modal-title {
            font-weight: 600;
        }
        .modal-title i {
            color: var(--accent);
        }
        .modal-body {
            padding: 0.5rem 1.5rem;
        }
        .modal-footer {
            border-top: 1px solid var(--line);
            padding: 1rem 1.5rem;
        }
        .btn-ghost {
            background: transparent;
            border: 1px solid var(--line);
            color: var(--muted);
            border-radius: 999px;
            padding: 0.4rem 1.4rem;
            font-size: 0.9rem;
            transition: border-color 0.2s ease, color 0.2s ease;
        }
        .btn-ghost:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        /* ── Commodity list ─────────────────────── */
        .commodity-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.9rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .commodity-row:last-child {
            border-bottom: none;
        }
        .commodity-name {
            font-weight: 500;
        }
        .commodity-meta {
            font-size: 0.82rem;
            color: var(--muted);
            margin-top: 0.15rem;
        }
        .commodity-price {
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--accent);
            white-space: nowrap;
            margin-left: 1rem;
        }
        .commodity-empty {
            text-align: center;
            color: var(--muted);
            padding: 2.5rem 1rem;
        }

        /* ── Commodity search ───────────────────── */
        .commodity-search {
            position: relative;
            margin-bottom: 0.5rem;
        }
        .commodity-search i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 0.9rem;
        }
        .commodity-search input {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 0.65rem 1rem 0.65rem 2.5rem;
            font-size: 0.95rem;
            background: #ffffff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .commodity-search input::placeholder {
            color: var(--muted);
        }
        .commodity-search input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(2, 128, 144, 0.12);
        }

        /* ── Footer ─────────────────────────────── */
        .site-footer {
            border-top: 1px solid var(--line);
            background: #ffffff;
            padding: 1.5rem 1rem;
            text-align: center;
            color: var(--muted);
            font-size: 0.85rem;
            margin-top: 2rem;
        }
        .site-footer a {
            color: var(--accent);
            text-decoration: none;
        }
    </style>
</head>
<body>

    <header class="site-header">
        <div class="container">
            <img src="../../../dist/img/splogo.png" alt="San Carlos City seal" class="seal">
            <div class="kicker">San Carlos City &middot; Negosyo Center</div>
            <h1>Price Monitor</h1>
            <p class="subtitle">Browse retail prices by category. Select a category to view available commodities.</p>
        </div>
    </header>

    <main class="container category-section mb-4">
        <div class="row" id="categoryGrid">
            <div class="col-12 text-center text-muted">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
                <p class="mt-2">Loading categories...</p>
            </div>
        </div>
    </main>

    <!-- Category Modal -->
    <div class="modal fade" id="categoryCommoditiesModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header align-items-center">
                    <h5 class="modal-title" id="modalCategoryTitle">
                        <i class="fas fa-box-open mr-2"></i>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="commodity-search">
                        <i class="fas fa-search"></i>
                        <input type="text" id="commoditySearch" placeholder="Search commodity..." autocomplete="off">
                    </div>
                    <div id="commodityList"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-ghost" data-dismiss="modal">Close</button>
                </div>

            </div>
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
    <script src="../../../scripts/price-monitoring/price-view.js"></script>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>

</body>
</html>