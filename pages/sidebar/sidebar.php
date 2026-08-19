<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <a class="brand-link user-panel pb-3 mb-3 d-flex">

    <img src="../../dist/img/nclogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light text-lg">Negosyo Center</span>

  </a>
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->

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

        <li id="statistics_sidebar" class="nav-item ">
          <a href="../calamity/calamity.php" class="nav-link sidebar-statistics cursor-e">
            <i class="nav-icon fa-brands fa-watchman-monitoring"></i>
            <p class="pt-2">
              CALAMITY MONITORING
            </p>
          </a>
        </li>

      </ul>
    </nav>
  </div>

  <!-- /.sidebar -->



</aside>