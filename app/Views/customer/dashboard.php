<?= $this->include('layout/layout_header'); ?>
<div class="wrapper">

    <?= $this->include('layout/layout_navbar'); ?>

    <?= $this->include('layout/layout_sidebar'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard v1</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-10">
                        <div class="row">
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $product): ?>
                                    <div class="col-12 col-sm-6 col-md-4 mb-3 px-1 d-flex align-items-stretch">
                                        <div class="card h-100 w-100" style="min-width:260px; max-width:380px; width:100%; min-height:420px;">
                                            <?php if (!empty($product['image'])): ?>
                                                <img src="<?= base_url('writable/uploads/' . $product['image']) ?>" class="card-img-top" alt="<?= esc($product['name']) ?>" style="height:180px;object-fit:cover;">
                                            <?php else: ?>
                                                <img src="<?= base_url('public/adminlte/plugins/no-image.png') ?>" class="card-img-top" alt="No Image" style="height:180px;object-fit:cover;">
                                            <?php endif; ?>
                                            <div class="card-body d-flex flex-column p-3">
                                                <h1 class="card-title mb-1" style="font-size:1.15rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                    <?= esc($product['name']) ?>
                                                </h1>
                                                <p class="card-text mb-1" style="font-size:0.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                    <span class="badge badge-info">
                                                        <?= esc($categoriesMap[$product['category_id']] ?? '-') ?>
                                                    </span>
                                                <p class="card-text mb-1" style="font-size:1rem;">Toko: <?= esc($storesMap[$product['store_id']] ?? '-') ?></p>
                                                <h4>
                                                    <span class="text-muted" style="font-size:0.9rem;">Stok <?= number_format($product['stock']) ?></span>
                                                </h4>
                                                <h3>
                                                    <span class="text-success">Rp. <?= number_format($product['price'], 0, ',', '.') ?></span>
                                                </h3>
                                                <div class="mt-auto">
                                                    <a href="<?= base_url('customer/cart/add/' . $product['id']) ?>" class="btn btn-success btn-sm mr-2 mb-1"><i class="fas fa-cart-plus"></i> Cart</a>
                                                    <a href="<?= base_url('customer/products/detail/' . $product['id']) ?>" class="btn btn-primary btn-sm mb-1"><i class="fas fa-info-circle"></i> Detail</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="alert alert-info">Belum ada produk tersedia.</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-success h-100" style="min-width:180px; max-width:250px; width:100%; min-height:320px; max-height:420px; overflow-y:auto;">
                            <div class="card-header">
                                <h3 class="card-title">Kategori Produk</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            <div class="card-body" style="display: block;">
                                <ul class="list-unstyled mb-0">
                                    <li><a href="<?= base_url('customer/dashboard') ?>" class="font-weight-bold">Semua Produk</a></li>
                                    <?php foreach ($categories as $cat): ?>
                                        <li><a href="<?= base_url('customer/dashboard?category=' . $cat['id']) ?>"><?= esc($cat['name']) ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 3.2.0
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<?= $this->include('layout/layout_footer'); ?>
<?php if (session()->getFlashdata('error')): ?>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            toastr.error('<?= esc(session()->getFlashdata('error'), 'js') ?>');
        });
    </script>
<?php endif; ?>