<?= $this->include('layout/layout_header'); ?>
<div class="wrapper">
    <?= $this->include('layout/layout_navbar'); ?>
    <?= $this->include('layout/layout_sidebar'); ?>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Pembayaran Gagal</h1>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="alert alert-danger mt-4">
                    <h4>Maaf, pembayaran gagal diproses.</h4>
                    <p>Silakan coba kembali atau hubungi admin jika masalah berlanjut.</p>
                    <a href="<?= base_url('customer/reports') ?>" class="btn btn-primary mt-2">Kembali ke Laporan Order</a>
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