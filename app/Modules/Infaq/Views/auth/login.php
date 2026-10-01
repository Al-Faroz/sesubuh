<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Sedekah Subuh</title>
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <h1 class="h1"><b>Sedekah</b>SUBUH</h1>
                <small>MIN 6 JEMBER</small>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Khusus Admin & Operator</p>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger text-sm"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('login/auth') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-user"></span></div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Masuk Ke Sistem</button>
                </form>
                <div class="text-center mt-3">
                    <a href="<?= base_url('/') ?>" class="text-sm"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>