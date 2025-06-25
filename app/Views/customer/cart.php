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
                        <h1 class="m-0">Cart</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Cart</li>
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
                    <div class="col-12">
                        <!-- Main content -->
                        <div class="invoice p-3 mb-3">
                            <!-- Table row -->
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Nama Produk</th>
                                                <th>Jumlah</th>
                                                <th>Harga Satuan</th>
                                                <th>Subtotal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $grandTotal = 0;
                                            if (!empty($cart)):
                                                $no = 1;
                                                foreach ($cart as $item):
                                                    $subtotal = $item['qty'] * $item['price'];
                                                    $grandTotal += $subtotal;
                                            ?>
                                                    <tr>
                                                        <td><?= $no++ ?></td>
                                                        <td><?= esc($item['name']) ?></td>
                                                        <td>
                                                            <form action="<?= base_url('customer/cart/updateQuantity/' . $item['id']) ?>" method="post" class="form-inline">
                                                                <input type="number" name="qty" value="<?= $item['qty'] ?>" min="1" class="form-control form-control-sm mr-2" style="width:70px;">
                                                                <button type="submit" class="btn btn-primary btn-sm">Ubah</button>
                                                            </form>
                                                        </td>
                                                        <td>Rp. <?= number_format($item['price'], 0, ',', '.') ?></td>
                                                        <td>Rp. <?= number_format($subtotal, 0, ',', '.') ?></td>
                                                        <td>
                                                            <a href="<?= base_url('customer/cart/remove/' . $item['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus item ini dari cart?')"><i class="fas fa-trash"></i> Hapus</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach;
                                            else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">Cart kosong.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <div class="row">
                                <div class="col-6">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th style="width:50%">Grand Total:</th>
                                                    <td>Rp. <?= number_format($grandTotal, 0, ',', '.') ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <?php
                            // Ambil city_id customer dan city_id toko dari cart
                            $canPostpaid = false;
                            if (!empty($cart)) {
                                $userCityId = session()->get('city_id');
                                $firstProduct = reset($cart);
                                $storeId = null;
                                // Coba dapatkan store_id dari produk
                                if (isset($firstProduct['id'])) {
                                    $productsModel = new \App\Models\ProductsModel();
                                    $product = $productsModel->find($firstProduct['id']);
                                    if ($product && isset($product['store_id'])) {
                                        $storeId = $product['store_id'];
                                    }
                                }
                                if ($storeId) {
                                    $storeModel = new \App\Models\StoreModel();
                                    $store = $storeModel->find($storeId);
                                    if ($store && isset($store['city_id']) && $userCityId && $store['city_id'] == $userCityId) {
                                        $canPostpaid = true;
                                    }
                                }
                            }
                            ?>

                            <!-- this row will not appear when printing -->
                            <div class="row no-print">
                                <div class="col-12">
                                    <?php if (!empty($cart)): ?>
                                        <form action="<?= base_url('customer/payment/process') ?>" method="post" class="d-inline">
                                            <button type="submit" class="btn btn-success float-right ml-2">Proses</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <!-- /.invoice -->
                    </div><!-- /.col -->
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
<?php if (session()->getFlashdata('success')): ?>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            toastr.success('<?= esc(session()->getFlashdata('success'), 'js') ?>');
        });
    </script>
<?php endif; ?>