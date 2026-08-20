<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <a class="brand-link user-panel pb-3 mb-3 d-flex">
    <img src="../../dist/img/nclogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
      style="opacity:.8">
  <a class="brand-link user-panel pb-3 mb-3 d-flex">
    <img src="../../dist/img/nclogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
      style="opacity:.8">

    <span class="brand-text font-weight-light text-lg">
      Negosyo Center
    </span>
  </a>
    <span class="brand-text font-weight-light text-lg">
      Negosyo Center
    </span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
  <!-- Sidebar -->
  <div class="sidebar">

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <!-- Dashboard -->
        <li class="nav-item">
          <a href="../msme/dashboard.php"
            class="nav-link cursor-e <?= (basename($_SERVER['PHP_SELF']) === 'dashboard.php') ? 'active' : '' ?>">
            class="nav-link cursor-e <?= (basename($_SERVER['PHP_SELF']) === 'dashboard.php') ? 'active' : '' ?>">
            <i class="nav-icon material-icons" style="font-size:19px;vertical-align:middle;">dashboard</i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- MSME Master List -->
        <li id="module_msme" class="nav-item">
          <a href="../msme/msme.php"
            class="nav-link sidebar-statistics cursor-e <?= (basename($_SERVER['PHP_SELF']) === 'msme.php') ? 'active' : '' ?>">
            class="nav-link sidebar-statistics cursor-e <?= (basename($_SERVER['PHP_SELF']) === 'msme.php') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-xlg fa-chart-line"></i>
            <p class="pt-2">MSME</p>
          </a>
        </li>

        <!-- Calamity -->
        <li id="module_calamity" class="nav-item">
          <a href="../calamity/calamity.php" class="nav-link">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>CALAMITY MONITORING</p>
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
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tags"></i>

            <p>
              PRICE MONITORING
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
            <p>
              PRICE MONITORING
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>

          <ul class="nav nav-treeview">
          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="../price-monitoring/price-monitoring.php" class="nav-link">
                <i class="fas fa-tags nav-icon"></i>
                <p>Price Monitoring</p>
              </a>
            </li>
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
          </ul>

        </li>
        </li>

      </ul>
      </ul>

    </nav>
    </nav>

  </div>
  <!-- /.sidebar -->
  </div>
  <!-- /.sidebar -->

</aside>