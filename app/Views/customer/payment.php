<?= $this->include('layout/layout_header'); ?>
<div class="wrapper">
    <?= $this->include('layout/layout_navbar'); ?>
    <?= $this->include('layout/layout_sidebar'); ?>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Pembayaran</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Pembayaran</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="invoice p-3 mb-3">
                            <div class="row">
                                <div class="col-12">
                                    <h4>
                                        <i class="fas fa-globe"></i> OSS ALKES
                                        <small class="float-right">Tanggal: <?= date('d/m/Y', strtotime($order['created_at'] ?? date('Y-m-d'))) ?></small>
                                    </h4>
                                </div>
                            </div>
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    Dari
                                    <address>
                                        <strong><?= esc($store['name'] ?? '-') ?></strong><br>
                                        <?= esc($store['address'] ?? '-') ?><br>
                                        Kota: <?= esc($store_city_name ?? '-') ?><br>
                                        Telepon: <?= esc($store['phone'] ?? '-') ?><br>
                                    </address>
                                </div>
                                <div class="col-sm-4 invoice-col">
                                    Kepada
                                    <address>
                                        <strong><?= esc($user['fullname'] ?? '-') ?></strong><br>
                                        <?= esc($user['address'] ?? '-') ?><br>
                                        Kota: <?= esc($user_city_name ?? '-') ?><br>
                                        Telepon: <?= esc($user['phone'] ?? '-') ?><br>
                                    </address>
                                </div>
                                <div class="col-sm-4 invoice-col">
                                    <br>
                                    <b>Order Number:</b> <?= esc($order['order_number']) ?><br>
                                    <b>Status:</b> <?= esc($order['status']) ?><br>
                                    <b>Status Pembayaran:</b> <?= esc($order['payment_status']) ?><br>
                                </div>
                            </div>
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $grandTotal = 0;
                                            if (!empty($orderItems)):
                                                $no = 1;
                                                foreach ($orderItems as $item):
                                                    $subtotal = $item['quantity'] * $item['price'];
                                                    $grandTotal += $subtotal;
                                            ?>
                                                    <tr>
                                                        <td><?= $no++ ?></td>
                                                        <td><?= esc($item['product_name']) ?></td>
                                                        <td><?= $item['quantity'] ?></td>
                                                        <td>Rp. <?= number_format($item['price'], 0, ',', '.') ?></td>
                                                        <td>Rp. <?= number_format($subtotal, 0, ',', '.') ?></td>
                                                    </tr>
                                                <?php endforeach;
                                            else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">Order kosong.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <p class="lead">Payment Methods:</p>
                                    <img src="<?= base_url('adminlte/dist/img/credit/visa.png') ?>" alt="Visa">
                                    <img src="<?= base_url('adminlte/dist/img/credit/mastercard.png') ?>" alt="Mastercard">
                                </div>
                                <div class="col-6">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th style="width:50%">Total:</th>
                                                    <td>Rp. <?= number_format($grandTotal, 0, ',', '.') ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row no-print">
                                <div class="col-12">
                                    <?php if (($order['payment_status'] ?? '') === 'pending'): ?>
                                        <a href="<?= base_url('customer/payment/payPrepaid/' . $order['id']) ?>" id="btn-prepaid" class="btn btn-success float-right ml-2">Prepaid</a>
                                        <a href="<?= base_url('customer/payment/payPostpaid/' . $order['id']) ?>" id="btn-postpaid" class="btn btn-primary float-right ml-2">Post Paid</a>
                                    <?php endif; ?>
                                </div>
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
    <aside class="control-sidebar control-sidebar-dark">
    </aside>
</div>
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
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= getenv('MIDTRANS_CLIENT_KEY') ?>"></script>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    const snapToken = urlParams.get('snapToken');
    if (snapToken) {
        window.snap.pay(snapToken, {
            onSuccess: function(result) {
                window.location.href = "<?= base_url('customer/payment/success/' . $order['id']) ?>?payment_gateway=" + encodeURIComponent(result.payment_type) + "&paid_at=" + encodeURIComponent(new Date().toISOString());
            },
            onPending: function(result) {
                alert('Menunggu pembayaran!');
                location.reload();
            },
            onError: function(result) {
                alert('Pembayaran gagal!');
                window.location.href = "<?= base_url('customer/payment/failed/' . $order['id']) ?>";
            }
        });
    }
</script>