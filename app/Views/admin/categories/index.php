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
                        <h1 class="m-0">Data Kategori</h1>
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
                                    <a href="<?= base_url('admin/categories/add') ?>" class="btn btn-primary mr-2"><i class="fas fa-plus"></i> Tambah</a>
                                    <a href="<?= base_url('admin/categories') ?>" class="btn btn-secondary"><i class="fas fa-sync"></i> Refresh</a>
                                </div>
                                <div class="ml-auto">
                                    <?php if (isset($pager) && $pager->getTotal('categories') > 10): ?>
                                        <?= $pager->links('categories', 'default_full') ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table w-100">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">No. </th>
                                                <th style="width: 40px">Aksi</th>
                                                <th>Nama Kategori</th>
                                                <th>Deskripsi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($categories)): $no = 1 + ($currentPage - 1) * 10;
                                                foreach ($categories as $cat): ?>
                                                    <tr>
                                                        <td><?= $no++ ?>.</td>
                                                        <td>
                                                            <div class="btn-group" role="group">
                                                                <a href="<?= base_url('admin/categories/edit/' . $cat['id']) ?>" class="btn btn-success btn-sm rounded mr-1" style="min-width:38px;"><i class="fas fa-edit"></i></a>
                                                                <form action="<?= base_url('admin/categories/delete/' . $cat['id']) ?>" method="post" style="display:inline;">
                                                                    <button type="submit" class="btn btn-danger btn-sm rounded" style="min-width:38px;" onclick="return confirm('Yakin ingin menghapus kategori ini?')"><i class="fas fa-trash"></i></button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                        <td><?= esc($cat['name']) ?></td>
                                                        <td><?= esc($cat['description']) ?></td>
                                                    </tr>
                                                <?php endforeach;
                                            else: ?>
                                                <tr>
                                                    <td colspan="4" class="text-center">Belum ada data</td>
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