<?php
// Layout induk: header + sidebar + konten + footer
echo view('Modules\Infaq\Views\layout\v_header', ['title' => $title ?? 'Dashboard']);
echo view('Modules\Infaq\Views\layout\v_sidebar');
?>

<main class="neo-main">
    <div class="neo-top">
        <button type="button" class="neo-burger" data-neo-toggle aria-controls="neo-side" aria-expanded="false" aria-label="Buka menu"><i class="fas fa-bars"></i></button>
        <img class="logo-plate " src="<?= neo_asset('img/logo.png') ?>" alt="Logo MIN 6 Jember" width="36" height="36">
        <span>SEDEKAH SUBUH</span>
        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Ganti mode tampilan" style="margin-left:auto"><i class="fas fa-moon"></i></button>
    </div>

    <div class="neo-content">

    <?php foreach (['success' => 'alert-success', 'error' => 'alert-danger'] as $key => $cls) : ?>
        <?php if ($msg = session()->getFlashdata($key)) : ?>
            <div class="alert <?= $cls ?> " role="alert">
                <?= esc($msg) ?>
                <button type="button" class="close" data-close aria-label="Tutup">&times;</button>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?= $this->renderSection('content') ?>

        </div>

    <footer class="neo-foot">&copy; 2026 MIN 6 Jember</footer>
</main>

<?php echo view('Modules\Infaq\Views\layout\v_footer'); ?>
