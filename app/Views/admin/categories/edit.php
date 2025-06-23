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
                        <h1 class="m-0">Edit Data Kategori</h1>
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
                        <div class="card card-primary">
                            <div class="card-header">
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form action="<?= base_url('admin/categories/update/' . $category['id']) ?>" method="post">
                                <div class="card-body">
                                    <?php if (session()->getFlashdata('errors')): ?>
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                <?php foreach (session('errors') as $error): ?>
                                                    <li><?= esc($error) ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <label for="name">Nama Kategori</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama kategori" value="<?= old('name', $category['name']) ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Deskripsi</label>
                                        <input type="text" class="form-control" id="description" name="description" placeholder="Masukkan deskripsi kategori" value="<?= old('description', $category['description']) ?>">
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
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