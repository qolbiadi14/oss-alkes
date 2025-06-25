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
                        <h1 class="m-0">Data Store</h1>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- /.row -->
                <!-- Main row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="<?= base_url('admin/accstores') ?>" class="btn btn-secondary"><i class="fas fa-sync"></i> Refresh</a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table w-100">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">No. </th>
                                                <th style="width: 120px">Aksi</th>
                                                <th>Nama Toko</th>
                                                <th>Alamat</th>
                                                <th>Kota</th>
                                                <th>Telepon</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($stores)): $no = 1;
                                                foreach ($stores as $store): ?>
                                                    <tr>
                                                        <td><?= $no++ ?>.</td>
                                                        <td>
                                                            <div class="d-flex flex-wrap">
                                                                <?php if ($store['status'] === 'pending'): ?>
                                                                    <form action="<?= base_url('admin/accstores/approve/' . $store['id']) ?>" method="post" class="d-inline-block me-1" style="width:110px;">
                                                                        <button type="submit" class="btn btn-success btn-sm w-100 rounded" onclick="return confirm('Approve store ini?')">
                                                                            <i class="fas fa-check"></i> Approve
                                                                        </button>
                                                                    </form>
                                                                    <form action="<?= base_url('admin/accstores/reject/' . $store['id']) ?>" method="post" class="d-inline-block me-1" style="width:110px;">
                                                                        <button type="submit" class="btn btn-danger btn-sm w-100 rounded" onclick="return confirm('Reject store ini?')">
                                                                            <i class="fas fa-times"></i> Reject
                                                                        </button>
                                                                    </form>
                                                                <?php elseif ($store['status'] === 'approved'): ?>
                                                                    <form action="<?= base_url('admin/accstores/suspend/' . $store['id']) ?>" method="post" class="d-inline-block me-1" style="width:110px;">
                                                                        <button type="submit" class="btn btn-warning btn-sm w-100 rounded" onclick="return confirm('Suspend store ini?')">
                                                                            <i class="fas fa-ban"></i> Suspend
                                                                        </button>
                                                                    </form>
                                                                <?php elseif ($store['status'] === 'suspended'): ?>
                                                                    <form action="<?= base_url('admin/accstores/unsuspend/' . $store['id']) ?>" method="post" class="d-inline-block me-1" style="width:110px;">
                                                                        <button type="submit" class="btn btn-warning btn-sm w-100 rounded" onclick="return confirm('Unsuspend store ini?')">
                                                                            <i class="fas fa-undo"></i> Unsuspend
                                                                        </button>
                                                                    </form>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                        <td><?= esc($store['name']) ?></td>
                                                        <td><?= esc($store['address']) ?></td>
                                                        <td><?= isset($cities[$store['city_id']]) ? esc($cities[$store['city_id']]) : '-' ?></td>
                                                        <td><?= esc($store['phone']) ?></td>
                                                        <td><?= esc($store['status']) ?></td>
                                                    </tr>
                                                <?php endforeach;
                                            else: ?>
                                                <tr>
                                                    <td colspan="7" class="text-center">Belum ada data</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row (main row) -->
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
<?php if (session()->getFlashdata('success')): ?>
    <script>
        $(function() {
            if ($('#toastsContainerTopRight').length === 0) {
                $(document.body).append('<div id="toastsContainerTopRight" class="toasts-top-right fixed"></div>');
            }
            var toastHtml =
                '<div class="toast bg-success fade" role="alert" aria-live="assertive" aria-atomic="true">' +
                '<div class="toast-header">' +
                '<strong class="mr-auto">Sukses</strong>' +
                '<small>Info</small>' +
                '<button data-dismiss="toast" type="button" class="ml-2 mb-1 close" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '</div>' +
                '<div class="toast-body">' +
                '<?= session('success') ?>' +
                '</div>' +
                '</div>';
            var $toast = $(toastHtml).appendTo('#toastsContainerTopRight');
            $toast.toast({
                delay: 3500
            });
            $toast.toast('show');
        });
    </script>
<?php endif; ?>