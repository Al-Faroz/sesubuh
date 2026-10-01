<?= $this->extend('\Modules\Infaq\Views\layout\v_wrapper') ?>
<?= $this->section('content') ?>

<div class="card card-default no-print mt-3 shadow-sm">
    <div class="card-body">
        <form method="get" action="">
            <div class="row align-items-end font-weight-bold">
                <div class="col-md-3"><label>Dari Tanggal</label><input type="date" name="tgl_awal" class="form-control" value="<?= $tgl_awal ?>"></div>
                <div class="col-md-3"><label>Sampai Tanggal</label><input type="date" name="tgl_akhir" class="form-control" value="<?= $tgl_akhir ?>"></div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="<?= base_url('admin/laporan/cetak_matrik_pdf?tgl_awal=' . $tgl_awal . '&tgl_akhir=' . $tgl_akhir) ?>" target="_blank" class="btn btn-danger ml-2">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="print-area mt-4">
    <div class="text-center py-3">
        <h4 class="font-weight-bold text-uppercase">MATRIK SEDEKAH SUBUH PER KELAS</h4>
        <h5>MIN 6 JEMBER</h5>
        <p>Periode: <?= date('d/m/Y', strtotime($tgl_awal)) ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)) ?></p>
    </div>

    <table class="table table-bordered text-center border-dark">
        <thead class="bg-success text-white">
            <tr>
                <th rowspan="2" style="vertical-align: middle;">Tanggal</th>
                <?php foreach ($kelas as $k) : ?>
                    <th style="font-size: 0.8rem;"><?= $k['nama_kelas'] ?></th>
                <?php endforeach; ?>
                <th rowspan="2" style="vertical-align: middle;" class="bg-warning text-dark">TOTAL</th>
            </tr>
            <tr>
                <?php foreach ($kelas as $k) : ?>
                    <th style="font-size: 0.65rem; font-weight: normal;"><?= $k['jml_anak'] ?? '0' ?> Siswa</th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $matrix = [];
            $total_per_kelas = [];
            foreach ($transaksi as $t) {
                $matrix[$t['tanggal']][$t['id_kelas']] = $t['nominal'];
            }

            $grand_total = 0;
            if (!empty($matrix)) :
                foreach ($matrix as $tgl => $v) :
                    $total_harian = 0; ?>
                    <tr>
                        <td class="font-weight-bold"><?= date('d/m/Y', strtotime($tgl)) ?></td>
                        <?php foreach ($kelas as $k) :
                            $nom = $v[$k['id_kelas']] ?? 0;
                            $total_harian += $nom;
                            $total_per_kelas[$k['id_kelas']] = ($total_per_kelas[$k['id_kelas']] ?? 0) + $nom; ?>
                            <td><?= $nom > 0 ? number_format($nom, 0, ',', '.') : '-' ?></td>
                        <?php endforeach; ?>
                        <td class="font-weight-bold text-right">Rp <?= number_format($total_harian, 0, ',', '.') ?></td>
                    </tr>
                    <?php $grand_total += $total_harian; ?>
            <?php endforeach;
            endif; ?>

            <tr class="font-weight-bold bg-light" style="font-size: 0.75rem;">
                <td>TOTAL SETORAN</td>
                <?php foreach ($kelas as $k) : ?>
                    <td><?= number_format($total_per_kelas[$k['id_kelas']] ?? 0, 0, ',', '.') ?></td>
                <?php endforeach; ?>
                <td class="bg-warning text-dark text-right">Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <div class="row mt-5 text-center sign-section">
        <div class="col-4">Ketua Komite,<br><br><br><br><b class="text-uppercase"><?= $config['nm_kakomite'] ?></b></div>
        <div class="col-4">Kepala Madrasah,<br><br><br><br><b class="text-uppercase"><?= $config['kpl_sek'] ?></b></div>
        <div class="col-4">Jember, <?= date('d-m-Y', strtotime($config['tgl_lap'])) ?><br>Bendahara Komite,<br><br><br><br><b class="text-uppercase"><?= $config['nm_bdrkomite'] ?></b></div>
    </div>
</div>

<style>
    @media print {
        @page {
            size: landscape;
            margin: 0.5cm;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .no-print,
        .main-footer,
        .main-sidebar,
        .content-header,
        .main-header {
            display: none !important;
        }

        .content-wrapper {
            margin-left: 0 !important;
            background-color: white !important;
        }

        table {
            width: 100% !important;
            border-collapse: collapse;
            font-size: 8pt;
        }

        th,
        td {
            border: 1px solid #333 !important;
            padding: 3px !important;
            color: black !important;
        }

        .bg-success {
            background-color: #28a745 !important;
            color: white !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
            color: black !important;
        }
    }
</style>
<?= $this->endSection() ?>