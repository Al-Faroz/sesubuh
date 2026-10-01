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
    <title><?= esc($title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="<?= base_url('public/assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/neo.css') ?>">
</head>

<body class="neo">
    <header class="pub-top">
        <div class="in">
            <a class="brand" href="<?= base_url() ?>"><i class="fas fa-hand-holding-heart"></i> SEDEKAH SUBUH</a>
            <button type="button" class="theme-toggle" data-theme-toggle aria-label="Ganti mode tampilan"><i class="fas fa-moon"></i></button>
            <a href="<?= base_url('login') ?>" class="btn btn-primary btn-sm"><i class="fas fa-lock"></i> Login Admin</a>
        </div>
    </header>

    <main class="pub-wrap">
        <section class="pub-hero">
            <span class="badge badge-info"><?= date('d/m/Y', strtotime($tgl_awal)) ?> &ndash; <?= date('d/m/Y', strtotime($tgl_akhir)) ?></span>
            <h1>Sedekah Subuh MIN 6 Jember</h1>
            <p>Laporan partisipasi kelas yang terbuka untuk seluruh warga madrasah.</p>
        </section>

        <section class="stats" aria-label="Ringkasan kas">
            <div class="stat">
                <div class="stat-ico"><i class="fas fa-arrow-down"></i></div>
                <div><div class="stat-val in"><span class="cur">Rp</span><?= number_format($total_pemasukan, 0, ',', '.') ?></div><div class="stat-lbl">Total Pemasukan</div></div>
            </div>
            <div class="stat">
                <div class="stat-ico out"><i class="fas fa-arrow-up"></i></div>
                <div><div class="stat-val out"><span class="cur">Rp</span><?= number_format($total_pengeluaran, 0, ',', '.') ?></div><div class="stat-lbl">Total Pengeluaran</div></div>
            </div>
            <div class="stat">
                <div class="stat-ico info"><i class="fas fa-wallet"></i></div>
                <div><div class="stat-val"><span class="cur">Rp</span><?= number_format($saldo_akhir, 0, ',', '.') ?></div><div class="stat-lbl">Saldo Kas Saat Ini</div></div>
            </div>
        </section>

        <form action="" method="get" class="card">
            <div class="card-body toolbar">
                <div class="toolbar-actions">
                    <div class="form-group"><label for="tgl_awal">Dari tanggal</label><input type="date" id="tgl_awal" name="tgl_awal" class="form-control" value="<?= esc($tgl_awal, 'attr') ?>"></div>
                    <div class="form-group"><label for="tgl_akhir">Sampai tanggal</label><input type="date" id="tgl_akhir" name="tgl_akhir" class="form-control" value="<?= esc($tgl_akhir, 'attr') ?>"></div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Tampilkan</button>
            </div>
        </form>

        <div class="grid-2">
            <section class="card">
                <div class="card-header"><h2 class="card-title"><i class="fas fa-chart-column ic-ok"></i> Tren pemasukan harian</h2><span class="badge badge-ok">Total Rp <?= number_format($total_periode, 0, ',', '.') ?></span></div>
                <div class="card-body">
                    <?php if ($harian) : ?>
                        <div class="chart-box"><canvas id="chart-harian" role="img" aria-label="Grafik batang pemasukan per hari"></canvas></div>
                    <?php else : ?>
                        <p class="text-muted mb-0">Belum ada data pada periode ini.</p>
                    <?php endif; ?>
                </div>
            </section>

            <section class="card">
                <div class="card-header"><h2 class="card-title"><i class="fas fa-trophy ic-warn"></i> Peringkat kelas</h2></div>
                <div class="card-body">
                    <?php $totals = array_column($peringkat, 'total');
                    $maks = max(1, $totals ? max($totals) : 1); ?>
                    <ol class="meter-list">
                        <?php foreach ($peringkat as $i => $p) : ?>
                            <li class="meter-row">
                                <span class="rank"><?= $i + 1 ?></span>
                                <div>
                                    <div class="top"><span><?= esc($p['nama']) ?></span><span>Rp <?= number_format($p['total'], 0, ',', '.') ?></span></div>
                                    <div class="meter" aria-hidden="true"><span style="width:<?= round($p['total'] / $maks * 100) ?>%"></span></div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </section>
        </div>

        <section class="card">
            <div class="card-header"><h2 class="card-title"><i class="fas fa-table ic-info"></i> Matrik per kelas</h2></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-matrik table-wide text-center">
                        <thead class="thead-accent">
                            <tr>
                                <th class="text-center">Tgl</th>
                                <?php foreach ($kelas as $k) : ?><th class="text-center"><?= esc($k['nama_kelas']) ?></th><?php endforeach; ?>
                                <th class="text-center">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $matrix = [];
                            foreach ($transaksi as $t) {
                                $matrix[$t['tanggal']][$t['id_kelas']] = $t['nominal'];
                            }
                            foreach ($matrix as $tgl => $v) : $tgl_total = 0; ?>
                                <tr>
                                    <td class="fw-b nowrap"><?= date('d/m', strtotime($tgl)) ?></td>
                                    <?php foreach ($kelas as $k) :
                                        $nom = $v[$k['id_kelas']] ?? 0;
                                        $tgl_total += $nom; ?>
                                        <td class="text-center"><?= $nom > 0 ? number_format($nom, 0, ',', '.') : '-' ?></td>
                                    <?php endforeach; ?>
                                    <td class="fw-b num-in"><?= number_format($tgl_total, 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <footer class="neo-foot">&copy; 2026 MIN 6 Jember</footer>
    </main>

    <script type="application/json" id="chart-data"><?= json_encode([
        'labels' => array_map(static fn($d) => date('d/m', strtotime($d)), array_keys($harian)),
        'values' => array_values($harian),
    ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
    <script src="<?= base_url('public/assets/plugins/chart.js/Chart.bundle.min.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/neo.js') ?>"></script>
    <script>
        (function() {
            var canvas = document.getElementById('chart-harian');
            if (!canvas) return;
            var data = JSON.parse(document.getElementById('chart-data').textContent);
            var chart;

            function css(name) {
                return getComputedStyle(document.documentElement).getPropertyValue(name).trim();
            }

            function rupiah(n) {
                return 'Rp ' + Number(n).toLocaleString('id-ID');
            }

            function build() {
                if (chart) chart.destroy();
                var text = css('--muted'),
                    grid = css('--line');
                chart = new Chart(canvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Pemasukan',
                            data: data.values,
                            backgroundColor: css('--accent'),
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        tooltips: {
                            callbacks: {
                                label: function(t) {
                                    return rupiah(t.yLabel);
                                }
                            }
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontColor: text,
                                    callback: function(v) {
                                        return v >= 1000 ? (v / 1000) + 'rb' : v;
                                    }
                                },
                                gridLines: {
                                    color: grid
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    fontColor: text
                                },
                                gridLines: {
                                    display: false
                                }
                            }]
                        }
                    }
                });
            }
            build();
            document.addEventListener('neo-theme', build);
        })();
    </script>
</body>

</html>
