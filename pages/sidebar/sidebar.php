<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a class="brand-link user-panel pb-3 mb-3 d-flex">
        <img src="../../dist/img/nclogo.png"
             alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3"
             style="opacity:.8">

        <span class="brand-text font-weight-light text-lg">
            Negosyo Center
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <nav class="mt-2">

            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">

                <!-- MSME -->
                <li id="module_msme" class="nav-item">
                    <a href="../msme/msme.php" class="nav-link">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>MSME</p>
                    </a>
                </li>

                <!-- Calamity -->
                <li id="module_calamity" class="nav-item">
                    <a href="../calamity/calamity.php" class="nav-link">
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
                            <a href="../price-monitoring/dti.php" class="nav-link">
                                <i class="fas fa-store nav-icon"></i>
                                <p>DTI - Basic Necessities</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../price-monitoring/da.php" class="nav-link">
                                <i class="fas fa-seedling nav-icon"></i>
                                <p>DA - Agriculture</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../price-monitoring/doe.php" class="nav-link">
                                <i class="fas fa-gas-pump nav-icon"></i>
                                <p>DOE - Petroleum</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../price-monitoring/other-agency.php" class="nav-link">
                                <i class="fas fa-building nav-icon"></i>
                                <p>Other Agencies</p>
                            </a>
                        </li>

                    </ul>

                </li>

            </ul>

        </nav>

    </div>
    <!-- /.sidebar -->

</aside>