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
        <li id="module_price_monitoring" class="nav-item">

          <a href="#" class="nav-link sidebar-nav-link">
            <span class="sidebar-nav-icon"><i class="fas fa-tags sidebar-icon"></i></span>
            

            <p class="sidebar-nav-text">
              PRICE MONITORING
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>

          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="../price-monitoring/price-monitoring.php" class="nav-link">
                
                <p>Price Monitoring</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="../price-monitoring/category.php" class="nav-link">
                <i class=""></i>
                <p>Categories</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="../price-monitoring/commodity.php" class="nav-link">
                <i class=""></i>
                <p>Commodities</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="../price-monitoring/Agency.php" class="nav-link">
                <i class=""></i>
                <p>Agencies</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="../price-monitoring/price-view/price-view.php" class="nav-link">
                <i class=""></i>
                <p>Price View</p>
              </a>
            </li>

          </ul>

        </li>

        <!-- Economic Map -->
                <li id="module_economic_map" class="nav-item has-treeview <?= (basename($_SERVER['PHP_SELF']) === 'economic-map.php') ? 'menu-open' : '' ?>">

                    <a href="#" class="nav-link sidebar-nav-link <?= (basename($_SERVER['PHP_SELF']) === 'economic-map.php') ? 'active' : '' ?>">
                        <span class="sidebar-nav-icon"><i class="fas fa-map-marked-alt sidebar-icon"></i></span>
                    

                        <p class="sidebar-nav-text">
                            ECONOMIC MAP
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="../economic-map/economic-map.php#hotspot" class="nav-link">
                                <i class="fas fa-fire nav-icon"></i>
                                <p>Economic Hotspot Map</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../economic-map/economic-map.php#distribution" class="nav-link">
                                <i class="fas fa-chart-pie nav-icon"></i>
                                <p>MSME Distribution Map</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../economic-map/economic-map.php#risk" class="nav-link">
                                <i class="fas fa-shield-alt nav-icon"></i>
                                <p>Economic Risk Map</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../economic-map/economic-map.php#pressure" class="nav-link">
                                <i class="fas fa-chart-line nav-icon"></i>
                                <p>Price / Economic Pressure Map</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../economic-map/economic-map.php#opportunity" class="nav-link">
                                <i class="fas fa-lightbulb nav-icon"></i>
                                <p>Economic Opportunity Map</p>
                            </a>
                        </li>

                    </ul>
                </li>

      </ul>

    </nav>

  </div>
  <!-- /.sidebar -->

</aside>
