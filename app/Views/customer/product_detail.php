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
        <section class="content">
            <div class="card card-solid">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="col-12 mb-3">
                                <img src="<?= !empty($product['image']) ? base_url('writable/uploads/' . $product['image']) : base_url('public/adminlte/plugins/no-image.png') ?>" class="product-image img-fluid" alt="<?= esc($product['name']) ?>">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <h3 class="my-3"><?= esc($product['name']) ?></h3>
                            <p><?= esc($product['description']) ?></p>
                            <hr>
                            <p><b>Kategori:</b> <span class="badge badge-info"><?= esc($category['name'] ?? '-') ?></span></p>
                            <p><b>Toko:</b> <?= esc($store['name'] ?? '-') ?></p>
                            <p><b>Stok:</b> <?= number_format($product['stock']) ?></p>
                            <h3 class="text-success">Rp. <?= number_format($product['price'], 0, ',', '.') ?></h3>
                            <div class="mt-4">
                                <a href="<?= base_url('customer/cart/add/' . $product['id']) ?>" class="btn btn-success btn-lg btn-flat"><i class="fas fa-cart-plus fa-lg mr-2"></i>Tambah ke Cart</a>
                            </div>
                            <div class="mt-3">
                                <b>Rating Rata-rata:</b> <span class="badge badge-warning"><?= number_format($avgRating, 1) ?> / 5</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <nav class="w-100">
                            <div class="nav nav-tabs" id="product-tab" role="tablist">
                                <a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">Deskripsi</a>
                                <a class="nav-item nav-link" id="product-comments-tab" data-toggle="tab" href="#product-comments" role="tab" aria-controls="product-comments" aria-selected="false">Komentar</a>
                                <a class="nav-item nav-link" id="product-rating-tab" data-toggle="tab" href="#product-rating" role="tab" aria-controls="product-rating" aria-selected="false">Rating</a>
                            </div>
                        </nav>
                        <div class="tab-content p-3" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                                <?= esc($product['description']) ?>
                            </div>
                            <div class="tab-pane fade" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab">
                                <?php if (!empty($feedbacks)): ?>
                                    <?php foreach ($feedbacks as $fb): ?>
                                        <div class="border-bottom mb-2 pb-2">
                                            <b><?= esc($fb['user']) ?></b> <span class="badge badge-warning">Rating: <?= $fb['rating'] ?>/5</span><br>
                                            <span><?= esc($fb['comment']) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Belum ada komentar.</p>
                                <?php endif; ?>
                            </div>
                            <div class="tab-pane fade" id="product-rating" role="tabpanel" aria-labelledby="product-rating-tab">
                                <b>Rating Rata-rata:</b> <span class="badge badge-warning"><?= number_format($avgRating, 1) ?> / 5</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
<?php if (session()->getFlashdata('error')): ?>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            toastr.error('<?= esc(session()->getFlashdata('error'), 'js') ?>');
        });
    </script>
<?php endif; ?>
<?= $this->include('layout/layout_footer'); ?>