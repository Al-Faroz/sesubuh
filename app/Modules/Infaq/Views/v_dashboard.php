<?= $this->extend('\Modules\Infaq\Views\layout\v_wrapper') ?>

<?= $this->section('content') ?>
<h1 class="page-title"><?= esc($title) ?></h1>
<p class="page-sub">Selamat datang, <b><?= esc(session()->get('username') ?? 'admin') ?></b>.</p>

<section class="stats" aria-label="Ringkasan kas">
    <div class="stat">
        <div class="stat-ico"><i class="fas fa-arrow-down"></i></div>
        <div>
            <div class="stat-val">Rp <?= number_format($total_pemasukan, 0, ',', '.') ?></div>
            <div class="stat-lbl">Total Pemasukan</div>
        </div>
    </div>
    <div class="stat">
        <div class="stat-ico out"><i class="fas fa-arrow-up"></i></div>
        <div>
            <div class="stat-val">Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></div>
            <div class="stat-lbl">Total Pengeluaran</div>
        </div>
    </div>
    <div class="stat">
        <div class="stat-ico"><i class="fas fa-wallet"></i></div>
        <div>
            <div class="stat-val">Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></div>
            <div class="stat-lbl">Saldo Kas Saat Ini</div>
        </div>
    </div>
    <div class="stat">
        <div class="stat-ico warn"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-val"><?= esc($partisipasi_today) ?></div>
            <div class="stat-lbl">Partisipasi Hari Ini</div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
