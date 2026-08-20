<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <a class="brand-link user-panel pb-3 mb-3 d-flex">
    <img src="../../dist/img/nclogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
      style="opacity:.8">

    <span class="brand-text font-weight-light text-lg">
      Negosyo Center
    </span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <!-- Dashboard -->
        <li class="nav-item">
          <a href="../msme/dashboard.php"
            class="nav-link cursor-e <?= (basename($_SERVER['PHP_SELF']) === 'dashboard.php') ? 'active' : '' ?>">
            <i class="nav-icon material-icons" style="font-size:19px;vertical-align:middle;">dashboard</i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- MSME Master List -->
        <li id="module_msme" class="nav-item">
          <a href="../msme/msme.php"
            class="nav-link sidebar-statistics cursor-e <?= (basename($_SERVER['PHP_SELF']) === 'msme.php') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-xlg fa-chart-line"></i>
            <p class="pt-2">MSME</p>
          </a>
        </li>

        <!-- Calamity -->
        <li id="module_calamity" class="nav-item">
          <a href="../calamity/calamity.php"
            class="nav-link <?= (basename($_SERVER['PHP_SELF']) === 'calamity.php') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>CALAMITY MONITORING</p>
          </a>
        </li>

        <!-- Price Monitoring -->
        <li id="module_price_monitoring" class="nav-item has-treeview">

          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tags"></i>

            <p>
              PRICE MONITORING
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>

          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="../price-monitoring/price-monitoring.php" class="nav-link">
                <i class="fas fa-tags nav-icon"></i>
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
              <a href="../price-monitoring/price-view/price-view.php" class="nav-link">
                <i class=""></i>
                <p>Price View</p>
              </a>
            </li>

          </ul>

        </li>

        <!-- Economic Map -->
                <li id="module_economic_map" class="nav-item has-treeview <?= (basename($_SERVER['PHP_SELF']) === 'economic-map.php') ? 'menu-open' : '' ?>">

                    <a href="#" class="nav-link <?= (basename($_SERVER['PHP_SELF']) === 'economic-map.php') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-map-marked-alt"></i>

                        <p>
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