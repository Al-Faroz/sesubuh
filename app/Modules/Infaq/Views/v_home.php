<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .portal-header {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            color: white;
            padding: 50px 0 80px 0;
        }

        .content-overlap {
            margin-top: -60px;
        }

        .table-matrik {
            font-size: 0.85rem;
        }
    </style>
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-navy sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="<?= base_url() ?>"><i class="fas fa-hand-holding-heart mr-2 text-warning"></i> SEDEKAH SUBUH MIN 6</a>
            <a href="<?= base_url('login') ?>" class="btn btn-outline-light btn-sm ml-auto"><i class="fas fa-lock mr-1"></i> LOGIN ADMIN</a>
        </div>
    </nav>

    <header class="portal-header text-center">
        <div class="container">
            <h2 class="font-weight-bold">Sedekah Subuh MIN 6 Jember</h2>
            <p class="lead">Laporan Partisipasi Kelas Real-Time</p>
        </div>
    </header>

    <main class="container content-overlap">
        <div class="row">
            <div class="col-lg-4 col-12">
                <div class="small-box bg-white shadow border-bottom border-success">
                    <div class="inner">
                        <h4 class="text-success font-weight-bold">Rp <?= number_format($total_pemasukan, 0, ',', '.') ?></h4>
                        <p class="text-muted">Total Pemasukan</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="small-box bg-white shadow border-bottom border-danger">
                    <div class="inner">
                        <h4 class="text-danger font-weight-bold">Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></h4>
                        <p class="text-muted">Total Pengeluaran</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="small-box bg-white shadow border-bottom border-primary">
                    <div class="inner">
                        <h4 class="text-primary font-weight-bold">Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></h4>
                        <p class="text-muted">Saldo Kas Saat Ini</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow border-0" style="border-radius: 12px;">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0 font-weight-bold"><i class="fas fa-calendar-alt mr-2 text-primary"></i> Matrik Seminggu Terakhir</h5>
                    </div>
                    <div class="col-md-6 text-right">
                        <form action="" method="get" class="form-inline justify-content-end">
                            <input type="date" name="tgl_awal" class="form-control form-control-sm mr-1" value="<?= $tgl_awal ?>">
                            <input type="date" name="tgl_akhir" class="form-control form-control-sm mr-1" value="<?= $tgl_akhir ?>">
                            <button type="submit" class="btn btn-primary btn-sm px-3">FILTER</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center mb-0 table-matrik">
                        <thead class="bg-navy text-white">
                            <tr>
                                <th>Tgl</th>
                                <?php foreach ($kelas as $k) : ?><th><?= esc($k['nama_kelas']) ?></th><?php endforeach; ?>
                                <th class="bg-warning text-dark">JUMLAH</th>
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
                                    <td class="font-weight-bold"><?= date('d/m', strtotime($tgl)) ?></td>
                                    <?php foreach ($kelas as $k) :
                                        $nom = $v[$k['id_kelas']] ?? 0;
                                        $tgl_total += $nom; ?>
                                        <td><?= $nom > 0 ? number_format($nom, 0, ',', '.') : '-' ?></td>
                                    <?php endforeach; ?>
                                    <td class="bg-light font-weight-bold"><?= number_format($tgl_total, 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center py-5 text-muted small">
        by: LemahTeles &copy; 2026 MIN 6 JEMBER - Sistem Transparansi Sedekah Subuh
    </footer>
</body>

</html>