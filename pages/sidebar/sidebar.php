<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$currentDir  = basename(dirname($_SERVER['PHP_SELF']));
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Brand -->
    <a href="../../pages/dashboard/dashboard.php" class="brand-link">
        <img src="../../dist/img/nclogo.png"
             alt="Negosyo Center"
             class="brand-image img-circle elevation-3"
             style="opacity:.9;">
        <span class="brand-text font-weight-bold">Negosyo Center</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview" role="menu" data-accordion="false">

            

                <!-- MSME Database & Statistics -->
                <li class="nav-item <?= ($currentDir === 'msme') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= ($currentDir === 'msme') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            MSME Database &amp; Statistics
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/msme/msme.php"
                               class="nav-link <?= ($currentPage === 'msme.php' && $currentDir === 'msme') ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-list"></i>
                                <p>MSME Master List</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Calamity Monitoring -->
                <li class="nav-item <?= ($currentDir === 'calamity') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= ($currentDir === 'calamity') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-exclamation-triangle"></i>
                        <p>
                            Calamity Monitoring
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    
                </li>

            

            </ul>
        </nav>
    </div>

</aside>
