<?= $this->include('layout/layout_header'); ?>
<div class="wrapper">
    <?= $this->include('layout/layout_navbar'); ?>
    <?= $this->include('layout/layout_sidebar'); ?>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Pengiriman Order</h1>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <button class="btn btn-secondary" onclick="location.reload()"><i class="fas fa-sync-alt"></i> Refresh</button>
                                </div>
                                <div class="ml-auto">
                                    <?php if (isset($pager) && $pager->getTotal('orders') > 10): ?>
                                        <?= $pager->links('orders', 'default_full') ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Aksi</th>
                                            <th>Invoice</th>
                                            <th>Alamat Toko</th>
                                            <th>Kota Toko</th>
                                            <th>No. Telepon Toko</th>
                                            <th>Nama Customer</th>
                                            <th>Alamat Customer</th>
                                            <th>Kota Customer</th>
                                            <th>No. Telepon Customer</th>
                                            <th>Jenis Pembayaran</th>
                                            <th>Payment Gateway</th>
                                            <th>Grand Total</th>
                                            <th>Status</th>
                                            <th>Status Pembayaran</th>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($orders)): $no = 1;
                                            foreach ($orders as $order): ?>
                                                <tr data-widget="expandable-table" aria-expanded="false">
                                                    <td><?= $no++ ?></td>
                                                    <td>
                                                        <?php if ($order['status'] === 'ready'): ?>
                                                            <form action="<?= base_url('admin/send/updateStatus/' . $order['id']) ?>" method="post" style="display:inline;">
                                                                <button type="submit" class="btn btn-primary btn-sm w-100 mb-1" style="min-width:90px;" onclick="return confirm('Kirim pesanan ini?')">Kirim</button>
                                                            </form>
                                                        <?php elseif ($order['status'] === 'shipped'): ?>
                                                            <form action="<?= base_url('admin/send/updateStatus/' . $order['id']) ?>" method="post" style="display:inline;">
                                                                <button type="submit" class="btn btn-success btn-sm w-100" style="min-width:90px;" onclick="return confirm('Tandai pesanan sudah sampai?')">Sampai</button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= esc($order['order_number']) ?></td>
                                                    <td><?= esc($order['store_address']) ?></td>
                                                    <td><?= esc($order['store_city']) ?></td>
                                                    <td><?= esc($order['store_phone']) ?></td>
                                                    <td><?= esc($order['customer_name']) ?></td>
                                                    <td><?= esc($order['customer_address']) ?></td>
                                                    <td><?= esc($order['customer_city']) ?></td>
                                                    <td><?= esc($order['customer_phone']) ?></td>
                                                    <td><?= esc(ucfirst($order['payment_method'] ?? '-')) ?></td>
                                                    <td><?= esc(ucfirst($order['payment_gateway'] ?? '-')) ?></td>
                                                    <td>Rp. <?= number_format($order['total_amount'], 0, ',', '.') ?></td>
                                                    <td><?= esc(ucfirst($order['status'])) ?></td>
                                                    <td><?= esc(ucfirst($order['payment_status'])) ?></td>
                                                </tr>
                                                <tr class="expandable-body d-none">
                                                    <td colspan="15">
                                                        <table class="table mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th>Nama Barang</th>
                                                                    <th>Jumlah</th>
                                                                    <th>Harga Satuan</th>
                                                                    <th>Subtotal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $itemNo = 1;
                                                                foreach ($order['items'] as $item): ?>
                                                                    <tr>
                                                                        <td><?= $itemNo++ ?></td>
                                                                        <td><?= esc($item['product_name']) ?></td>
                                                                        <td><?= $item['quantity'] ?></td>
                                                                        <td>Rp. <?= number_format($item['price'], 0, ',', '.') ?></td>
                                                                        <td>Rp. <?= number_format($item['quantity'] * $item['price'], 0, ',', '.') ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            <?php endforeach;
                                        else: ?>
                                            <tr>
                                                <td colspan="15" class="text-center">Tidak ada data order.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 3.2.0
        </div>
    </footer>
    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>
<?php if (session()->getFlashdata('success')): ?>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            toastr.success('<?= session('success') ?>');
        });
    </script>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            toastr.error('<?= session('error') ?>');
        });
    </script>
<?php endif; ?>
<?= $this->include('layout/layout_footer'); ?>