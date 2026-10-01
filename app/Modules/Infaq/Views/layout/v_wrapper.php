<?php
// Layout induk: header + sidebar + konten + footer
echo view('Modules\Infaq\Views\layout\v_header', ['title' => $title ?? 'Dashboard']);
echo view('Modules\Infaq\Views\layout\v_sidebar');
?>

<main class="neo-main">
    <div class="neo-top">
        <button type="button" class="neo-burger" data-neo-toggle aria-controls="neo-side" aria-expanded="false" aria-label="Buka menu"><i class="fas fa-bars"></i></button>
        <span>SEDEKAH SUBUH</span>
    </div>

    <?php foreach (['success' => 'alert-success', 'error' => 'alert-danger'] as $key => $cls) : ?>
        <?php if ($msg = session()->getFlashdata($key)) : ?>
            <div class="alert <?= $cls ?> alert-dismissible fade show" role="alert">
                <?= esc($msg) ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">&times;</button>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?= $this->renderSection('content') ?>

    <footer class="neo-foot">&copy; 2026 MIN 6 Jember</footer>
</main>

<?php echo view('Modules\Infaq\Views\layout\v_footer'); ?>
