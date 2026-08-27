<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Brand -->
    <a href="/NegosyoCenter/pages/msme/msme.php" class="brand-link sidebar-brand-link d-flex align-items-center">
        <img src="/NegosyoCenter/dist/img/nclogo.png"
             alt="Negosyo Center Logo"
             class="brand-image img-circle elevation-2 sidebar-brand-img">
        <div class="sidebar-brand-text-wrap ml-2">
            <span class="sidebar-brand-name">Negosyo Center</span>
            <span class="sidebar-brand-sub">San Carlos City</span>
        </div>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-1 pb-3">
            <ul class="nav nav-pills nav-sidebar flex-column sidebar-nav-list"
                data-widget="treeview" role="menu" data-accordion="false">

                <!-- ── MAIN ──────────────────────────────────── -->
                <li class="sidebar-section-label">Main</li>

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="/NegosyoCenter/pages/msme/dashboard.php"
                       class="nav-link sidebar-nav-link cursor-e <?= (basename($_SERVER['PHP_SELF']) === 'dashboard.php') ? 'active' : '' ?>">
                        <span class="sidebar-nav-icon">
                            <i class="fas fa-tachometer-alt sidebar-icon"></i>
                        </span>
                        <p class="sidebar-nav-text">Dashboard</p>
                    </a>
                </li>

                <!-- ── MODULES ───────────────────────────────── -->
                <li class="sidebar-section-label">Modules</li>

                <!-- MSME -->
                <li id="module_msme" class="nav-item">
                    <a href="/NegosyoCenter/pages/msme/msme.php"
                       class="nav-link sidebar-nav-link cursor-e <?= in_array(basename($_SERVER['PHP_SELF']), ['msme.php', 'page-view.php']) ? 'active' : '' ?>">
                        <span class="sidebar-nav-icon">
                            <i class="fas fa-store sidebar-icon"></i>
                        </span>
                        <p class="sidebar-nav-text">MSME</p>
                    </a>
                </li>

                <!-- Calamity Monitoring -->
                <li id="module_calamity" class="nav-item">
                    <a href="/NegosyoCenter/pages/calamity/calamity.php"
                       class="nav-link sidebar-nav-link <?= (basename($_SERVER['PHP_SELF']) === 'calamity.php') ? 'active' : '' ?>">
                        <span class="sidebar-nav-icon">
                            <i class="fas fa-exclamation-triangle sidebar-icon"></i>
                        </span>
                        <p class="sidebar-nav-text">Calamity Monitoring</p>
                    </a>
                </li>

                <!-- Price Monitoring -->
                <li id="module_price_monitoring" class="nav-item has-treeview <?= in_array(basename($_SERVER['PHP_SELF']), ['price-monitoring.php','category.php','commodity.php','price-view.php']) ? 'menu-open' : '' ?>">

                    <a href="#" class="nav-link sidebar-nav-link <?= in_array(basename($_SERVER['PHP_SELF']), ['price-monitoring.php','category.php','commodity.php','price-view.php']) ? 'active' : '' ?>">
                        <span class="sidebar-nav-icon">
                            <i class="fas fa-tags sidebar-icon"></i>
                        </span>
                        <p class="sidebar-nav-text">
                            Price Monitoring
                            <i class="fas fa-angle-left sidebar-arrow"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview sidebar-sub-nav">

                        <li class="nav-item">
                            <a href="/NegosyoCenter/pages/price-monitoring/price-monitoring.php"
                               class="nav-link sidebar-nav-link sidebar-sub-link <?= (basename($_SERVER['PHP_SELF']) === 'price-monitoring.php') ? 'active' : '' ?>">
                                <span class="sidebar-sub-icon">
                                    <i class="fas fa-chart-line sidebar-icon"></i>
                                </span>
                                <p class="sidebar-nav-text">Price Monitoring</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/NegosyoCenter/pages/price-monitoring/category.php"
                               class="nav-link sidebar-nav-link sidebar-sub-link <?= (basename($_SERVER['PHP_SELF']) === 'category.php') ? 'active' : '' ?>">
                                <span class="sidebar-sub-icon">
                                    <i class="fas fa-th-large sidebar-icon"></i>
                                </span>
                                <p class="sidebar-nav-text">Categories</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/NegosyoCenter/pages/price-monitoring/commodity.php"
                               class="nav-link sidebar-nav-link sidebar-sub-link <?= (basename($_SERVER['PHP_SELF']) === 'commodity.php') ? 'active' : '' ?>">
                                <span class="sidebar-sub-icon">
                                    <i class="fas fa-box sidebar-icon"></i>
                                </span>
                                <p class="sidebar-nav-text">Commodities</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/NegosyoCenter/pages/price-monitoring/price-view/price-view.php"
                               class="nav-link sidebar-nav-link sidebar-sub-link <?= (basename($_SERVER['PHP_SELF']) === 'price-view.php') ? 'active' : '' ?>">
                                <span class="sidebar-sub-icon">
                                    <i class="fas fa-chart-bar sidebar-icon"></i>
                                </span>
                                <p class="sidebar-nav-text">Price View</p>
                            </a>
                        </li>

                    </ul>
                </li>

            </ul>
        </nav>
    </div>
    <!-- /.sidebar -->

</aside>
