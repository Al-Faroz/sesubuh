<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <script>
        (function() {
            try {
                var t = localStorage.getItem('neo-theme') || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', t);
            } catch (e) {}
        })();
    </script>
    <title>Login | Sedekah Subuh</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="<?= base_url('public/assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/neo.css') ?>">
</head>

<body class="neo">
    <main class="login-wrap">
        <button type="button" class="theme-toggle theme-float" data-theme-toggle aria-label="Ganti mode tampilan"><i class="fas fa-moon"></i></button>
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
                    <input type="password" id="password" name="password" placeholder="Password" aria-label="Password" autocomplete="current-password" required>
                    <button type="button" class="toggle-pass" id="toggle-pass" aria-label="Tampilkan password" aria-pressed="false"><i class="fas fa-eye"></i></button>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Masuk ke Sistem</button>
            </form>
            <a href="<?= base_url('/') ?>" class="login-back"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
        </div>
    </main>
    <script src="<?= base_url('public/assets/js/neo.js') ?>"></script>
    <script>
        document.getElementById('toggle-pass').addEventListener('click', function() {
            var input = document.getElementById('password');
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            this.setAttribute('aria-pressed', show ? 'true' : 'false');
            this.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
            this.firstElementChild.className = show ? 'fas fa-eye-slash' : 'fas fa-eye';
        });
    </script>
</body>

</html>
