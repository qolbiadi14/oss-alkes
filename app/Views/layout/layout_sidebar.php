<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?= base_url('adminlte/dist/img/AdminLTELogo.png') ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= base_url('adminlte/dist/img/user2-160x160.jpg') ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <?php if (session()->get('username')): ?>
                    <a href="<?= base_url('/logout') ?>" class="d-block" title="Logout">
                        <?= session()->get('username'); ?> <span style="font-size:smaller;">(Logout)</span>
                    </a>
                <?php else: ?>
                    <span class="d-block">Guest</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php if (session()->get('role') === 'admin'): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('/admin/dashboard') ?>" class="nav-link active">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Dashboard Admin</p>
                                </a>
                            </li>
                        <?php elseif (session()->get('role') === 'vendor'): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('/vendor/dashboard') ?>" class="nav-link active">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Dashboard Vendor</p>
                                </a>
                            </li>
                        <?php elseif (session()->get('role') === 'customer'): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('/customer/dashboard') ?>" class="nav-link active">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Dashboard Customer</p>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <!-- Master Data (Admin only) -->
                <?php if (session()->get('role') === 'admin'): ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Master Data<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('/admin/categories') ?>" class="nav-link">
                                    <i class="fas fa-archive nav-icon"></i>
                                    <p>Data Kategori</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/admin/users') ?>" class="nav-link">
                                    <i class="fas fa-users nav-icon"></i>
                                    <p>Data User</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/admin/accstores') ?>" class="nav-link">
                                    <i class="fas fa-store nav-icon"></i>
                                    <p>Data Toko</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <!-- Manajemen Toko (Vendor only) -->
                <?php if (session()->get('role') === 'vendor'): ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>Manajemen Toko<i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('/vendor/products') ?>" class="nav-link">
                                    <i class="fas fa-shopping-basket nav-icon"></i>
                                    <p>Data Produk</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/vendor/storeidentity') ?>" class="nav-link">
                                    <i class="fas fa-warehouse nav-icon"></i>
                                    <p>Identitas Toko</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <!-- Transaksi (semua role, sub-menu sesuai role) -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Transaksi<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php if (session()->get('role') === 'customer'): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('/customer/cart') ?>" class="nav-link">
                                    <i class="fas fa-shopping-bag nav-icon"></i>
                                    <p>Keranjang Belanja</p>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (session()->get('role') === 'vendor'): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('/vendor/receiveorders') ?>" class="nav-link">
                                    <i class="fas fa-people-carry nav-icon"></i>
                                    <p>Terima Order</p>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (session()->get('role') === 'admin'): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('/admin/send') ?>" class="nav-link">
                                    <i class="fas fa-shipping-fast nav-icon"></i>
                                    <p>Pengiriman Order</p>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a href="<?= base_url('/reports') ?>" class="nav-link">
                                <i class="fas fa-clipboard-list nav-icon"></i>
                                <p>Laporan Transaksi</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>