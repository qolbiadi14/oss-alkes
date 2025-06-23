<?= $this->include('layout/layout_header') ?>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="../../index2.html" class="h1"><b>Virtual Alkes</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Registrasi pelanggan baru</p>

                <form action="<?= base_url('register') ?>" method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="fullname" class="form-control" placeholder="Nama Lengkap" required value="<?= old('fullname') ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username" required value="<?= old('username') ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required value="<?= old('email') ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="confirm_password" class="form-control" placeholder="Retype password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group date mb-3" id="reservationdate" data-target-input="nearest">
                        <input type="text" name="birth_date" class="form-control datetimepicker-input" data-target="#reservationdate" placeholder="Tanggal Lahir" required value="<?= old('birth_date') ?>">
                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin: </label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="laki" value="L" <?= old('gender', 'L') == 'L' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="laki">Laki-laki</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="perempuan" value="P" <?= old('gender') == 'P' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="perempuan">Perempuan</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea class="form-control" name="address" rows="3" placeholder="Masukkan alamat lengkap" required><?= old('address') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Kota</label>
                        <select class="form-control" name="city_id" required>
                            <option value="">-- Pilih Kota --</option>
                            <?php if (isset($cities) && is_array($cities)): ?>
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?= $city['id'] ?>" <?= old('city_id') == $city['id'] ? 'selected' : '' ?>><?= $city['name'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>No. HP (opsional)</label>
                        <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx" value="<?= old('phone') ?>">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                        <div class="col-6">
                            <button type="reset" class="btn btn-secondary btn-block">Reset</button>
                        </div>
                    </div>
                </form>
                <a href="<?= base_url('/login') ?>" class="text-center">Sudah memiliki akun? Silakan Login</a>
            </div>
            <!-- /.form-box -->
        </div>
    </div>
    <?= $this->include('layout/layout_footer') ?>
    <script>
        $(function() {
            $('#reservationdate').datetimepicker({
                format: 'L',
                icons: {
                    time: 'far fa-clock'
                }
            });
            // Tambahan: klik input juga munculkan datepicker
            $('#reservationdate .datetimepicker-input').on('click', function() {
                $('#reservationdate').datetimepicker('show');
            });
        });
    </script>
    <?php if (session()->getFlashdata('success')): ?>
        <div id="toastsContainerTopRight" class="toasts-top-right fixed"></div>
        <script>
            $(function() {
                $(document).ready(function() {
                    $(document.body).append('<div id="toastsContainerTopRight" class="toasts-top-right fixed"></div>');
                    $(document.body).toast({
                        autohide: true
                    });
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
                });
            });
        </script>
    <?php endif; ?>