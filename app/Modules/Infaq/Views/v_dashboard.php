<?= $this->extend('\Modules\Infaq\Views\layout\v_wrapper') ?>

<?= $this->section('content') ?>
<h1 class="page-title"><?= esc($title) ?></h1>
<p class="page-sub">Selamat datang, <b><?= esc(session()->get('username') ?? 'admin') ?></b>.</p>

<section class="stats" aria-label="Ringkasan kas">
    <div class="stat">
        <div class="stat-ico"><i class="fas fa-arrow-down"></i></div>
        <div>
            <div class="stat-val in"><span class="cur">Rp</span><?= number_format($total_pemasukan, 0, ',', '.') ?></div>
            <div class="stat-lbl">Total Pemasukan</div>
        </div>
    </div>
    <div class="stat">
        <div class="stat-ico out"><i class="fas fa-arrow-up"></i></div>
        <div>
            <div class="stat-val out"><span class="cur">Rp</span><?= number_format($total_pengeluaran, 0, ',', '.') ?></div>
            <div class="stat-lbl">Total Pengeluaran</div>
        </div>
    </div>
    <div class="stat">
        <div class="stat-ico info"><i class="fas fa-wallet"></i></div>
        <div>
            <div class="stat-val"><span class="cur">Rp</span><?= number_format($saldo_akhir, 0, ',', '.') ?></div>
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

<div class="dash-grid">
    <section class="card">
        <div class="card-header"><h2 class="card-title"><i class="fas fa-chart-line ic-ok"></i> Tren pemasukan harian</h2><span class="hint">10 hari terakhir yang ada datanya</span></div>
        <div class="card-body">
            <?php if ($tren['labels']) : ?>
                <div class="chart-box"><canvas id="chart-harian" role="img" aria-label="Grafik garis pemasukan per hari"></canvas></div>
            <?php else : ?>
                <p class="text-muted mb-0">Belum ada data setoran.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="card">
        <div class="card-header"><h2 class="card-title"><i class="fas fa-stream ic-violet"></i> Aktivitas terbaru</h2></div>
        <div class="card-body">
            <?php
            $obj = ['sedekah_masuk' => 'setoran', 'sedekah_keluar' => 'pengeluaran', 'kelas' => 'kelas', 'users' => 'akun', 'pengaturan' => 'pengaturan', 'database' => 'database'];
            $bdg = ['tambah' => 'badge-ok', 'ubah' => 'badge-info', 'hapus' => 'badge-bad', 'backup' => 'badge-violet'];
            ?>
            <ul class="feed">
                <?php foreach ($aktivitas as $a) : ?>
                    <li>
                        <span class="badge <?= $bdg[$a['aksi']] ?? 'badge-muted' ?>"><?= esc(ucfirst($a['aksi'])) ?></span>
                        <span class="feed-txt"><b><?= esc($a['username'] ?? '-') ?></b> &middot; <?= esc($obj[$a['tabel']] ?? $a['tabel']) ?></span>
                        <time class="hint"><?= date('d/m H:i', strtotime($a['created_at'])) ?></time>
                    </li>
                <?php endforeach; ?>
                <?php if (! $aktivitas) : ?><li class="text-muted">Belum ada aktivitas tercatat.</li><?php endif; ?>
            </ul>
        </div>
    </section>
</div>

<script type="application/json" id="chart-data"><?= json_encode(['labels' => $tren['labels'], 'values' => $tren['values'], 'kelas' => $tren['kelas'], 'total' => $jml_kelas], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
<script src="<?= neo_asset('plugins/chart.js/Chart.bundle.min.js') ?>"></script>
<script src="<?= neo_asset('js/neo-chart.js') ?>"></script>
<?= $this->endSection() ?>
