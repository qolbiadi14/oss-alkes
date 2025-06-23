<?= $this->include('layout/layout_header') ?>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="../../index2.html" class="h1"><b>Virtual Alkes</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Silakan Login untuk masuk ke aplikasi</p>

                <form action="<?php echo base_url('/login') ?>" method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mb-1">
                    <a href="forgot-password.html">Lupa password</a>
                </p>
                <p class="mb-0">
                    <a href="<?= base_url('/register') ?>" class="text-center">Registrasi pelanggan baru</a>
                </p>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <?= $this->include('layout/layout_footer') ?>
    <?php if (session()->getFlashdata('success')): ?>
        <script>
            $(function() {
                if ($('#toastsContainerTopRight').length === 0) {
                    $(document.body).append('<div id="toastsContainerTopRight" class="toasts-top-right fixed"></div>');
                }
                $(
                    '<div class="toast bg-success fade show" role="alert" aria-live="assertive" aria-atomic="true">' +
                    '<div class="toast-header">' +
                    '<strong class="mr-auto">Sukses</strong>' +
                    '<small>Info</small>' +
                    '<button data-dismiss="toast" type="button" class="ml-2 mb-1 close" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                    '</div>' +
                    '<div class="toast-body">' +
                    '<?= session('success') ?>' +
                    '</div>' +
                    '</div>'
                ).appendTo('#toastsContainerTopRight');
                setTimeout(function() {
                    $('.toast').toast('hide');
                }, 3500);
            });
        </script>
    <?php endif; ?>