<?= $this->include('layout/layout_header'); ?>
<div class="wrapper">
    <?= $this->include('layout/layout_navbar'); ?>
    <?= $this->include('layout/layout_sidebar'); ?>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Ulasan</h1>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Beri Umpan Balik untuk Order #<?= $order['order_number'] ?></h3>
                            </div>
                            <div class="card-body">
                                <?php if (session()->getFlashdata('error')): ?>
                                    <div class="alert alert-danger">
                                        <?= session()->getFlashdata('error') ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (session()->getFlashdata('success')): ?>
                                    <div class="alert alert-success">
                                        <?= session()->getFlashdata('success') ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($allReviewed): ?>
                                    <div class="alert alert-success">Semua produk pada order ini sudah diulas. Terima kasih!</div>
                                <?php else: ?>
                                    <form action="<?= base_url('customer/feedback/store') ?>" method="post">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="user_id" value="<?= session()->get('user_id') ?>">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

                                        <div class="form-group">
                                            <label for="product">Produk</label>
                                            <select class="form-control" name="product_id" required>
                                                <option value="">Pilih Produk</option>
                                                <?php foreach ($orderItems as $item): ?>
                                                    <?php foreach ($products as $product): ?>
                                                        <?php if ($item['product_id'] == $product['id']): ?>
                                                            <option value="<?= $product['id'] ?>"><?= $product['name'] ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Penilaian</label>
                                            <div class="rating">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="rating1" value="1" required>
                                                    <label class="form-check-label" for="rating1">1 ⭐</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="rating2" value="2">
                                                    <label class="form-check-label" for="rating2">2 ⭐</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="rating3" value="3">
                                                    <label class="form-check-label" for="rating3">3 ⭐</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="rating4" value="4">
                                                    <label class="form-check-label" for="rating4">4 ⭐</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="rating5" value="5">
                                                    <label class="form-check-label" for="rating5">5 ⭐</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="message">Pesan Umpan Balik</label>
                                            <textarea class="form-control" name="message" rows="5" placeholder="Masukkan umpan balik Anda..." required></textarea>
                                        </div>

                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary">Kirim Umpan Balik</button>
                                            <a href="<?= base_url('customer/reports') ?>" class="btn btn-default">Batal</a>
                                        </div>
                                    </form>
                                <?php endif; ?>
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
<?= $this->include('layout/layout_footer'); ?>