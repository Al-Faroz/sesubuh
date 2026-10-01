<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Sedekah Subuh</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="<?= base_url('public/assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/neo.css') ?>">
</head>

<body class="neo">
    <main class="login-wrap">
        <div class="login-card">
            <div class="login-logo"><i class="fas fa-hand-holding-heart"></i></div>
            <h1>Sedekah Subuh</h1>
            <p class="sub">MIN 6 Jember &middot; Khusus Admin &amp; Operator</p>

            <?php if ($msg = session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger" role="alert"><?= esc($msg) ?></div>
            <?php endif; ?>
            <?php if ($msg = session()->getFlashdata('success')) : ?>
                <div class="alert alert-success" role="status"><?= esc($msg) ?></div>
            <?php endif; ?>

            <form action="<?= base_url('login/auth') ?>" method="post">
                <?= csrf_field() ?>
                <div class="field">
                    <i class="fas fa-user" aria-hidden="true"></i>
                    <input type="text" name="username" placeholder="Username" aria-label="Username" autocomplete="username" required autofocus>
                </div>
                <div class="field">
                    <i class="fas fa-lock" aria-hidden="true"></i>
                    <input type="password" name="password" placeholder="Password" aria-label="Password" autocomplete="current-password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Masuk ke Sistem</button>
            </form>
            <a href="<?= base_url('/') ?>" class="login-back"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
        </div>
    </main>
</body>

</html>
