<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="<?= base_url('adminlte/dist/img/AdminLTELogo.png') ?>" alt="AdminLTELogo" height="60" width="60">
</div>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <?php
        $cart = session()->get('cart') ?? [];
        $totalCartQty = 0;
        foreach ($cart as $item) {
            $totalCartQty += $item['qty'];
        }
        ?>
        <?php if (session()->get('role') === 'customer'): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('customer/cart') ?>" role="button">
                    <i class="fas fa-shopping-bag"></i>
                    <?php if ($totalCartQty > 0): ?>
                        <span class="badge badge-danger navbar-badge"><?= $totalCartQty ?></span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->