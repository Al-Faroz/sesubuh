<?= $this->extend('\\Modules\\Infaq\\Views\\layout\\v_wrapper') ?>
<?= $this->section('content') ?>
<h1 class="page-title no-print"><?= esc($title) ?></h1>

<form method="get" action="" class="card no-print">
    <div class="card-body toolbar">
        <div class="toolbar-actions">
            <div class="form-group"><label for="tgl_awal">Dari tanggal</label><input type="date" id="tgl_awal" name="tgl_awal" class="form-control" value="<?= esc($tgl_awal, 'attr') ?>"></div>
            <div class="form-group"><label for="tgl_akhir">Sampai tanggal</label><input type="date" id="tgl_akhir" name="tgl_akhir" class="form-control" value="<?= esc($tgl_akhir, 'attr') ?>"></div>
        </div>
        <div class="toolbar-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan</button>
            <a href="<?= base_url('admin/laporan/cetak_matrik_pdf?' . http_build_query(['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir])) ?>" target="_blank" rel="noopener" class="btn"><i class="fas fa-file-pdf"></i> Unduh PDF</a>
        </div>
    </div>
</form>

<section class="card">
    <div class="card-body">
        <div class="report-title">
            <h2>Matrik Sedekah Subuh Per Kelas</h2>
            <h2>MIN 6 Jember</h2>
            <p>Periode: <?= date('d/m/Y', strtotime($tgl_awal)) ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)) ?></p>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-matrik table-wide text-center">
                <thead class="thead-accent">
                    <tr>
                        <th class="text-center">Tanggal</th>
                        <?php foreach ($kelas as $k) : ?>
                            <th class="text-center"><?= esc($k['nama_kelas']) ?><br><small><?= (int) ($k['jml_anak'] ?? 0) ?> siswa</small></th>
                        <?php endforeach; ?>
                        <th class="text-center">Total</th>
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
                    foreach ($matrix as $tgl => $v) :
                        $total_harian = 0; ?>
                        <tr>
                            <td class="fw-b nowrap"><?= date('d/m/Y', strtotime($tgl)) ?></td>
                            <?php foreach ($kelas as $k) :
                                $nom = $v[$k['id_kelas']] ?? 0;
                                $total_harian += $nom;
                                $total_per_kelas[$k['id_kelas']] = ($total_per_kelas[$k['id_kelas']] ?? 0) + $nom; ?>
                                <td class="text-center"><?= $nom > 0 ? number_format($nom, 0, ',', '.') : '-' ?></td>
                            <?php endforeach; ?>
                            <td class="fw-b text-right nowrap">Rp <?= number_format($total_harian, 0, ',', '.') ?></td>
                        </tr>
                        <?php $grand_total += $total_harian; ?>
                    <?php endforeach; ?>
                    <tr class="row-total">
                        <td>Total</td>
                        <?php foreach ($kelas as $k) : ?>
                            <td class="text-center"><?= number_format($total_per_kelas[$k['id_kelas']] ?? 0, 0, ',', '.') ?></td>
                        <?php endforeach; ?>
                        <td class="text-right nowrap">Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="sign avoid-break">
            <div>Ketua Komite,<div class="space"></div><b><?= esc(strtoupper($config['nm_kakomite'] ?? '')) ?></b></div>
            <div>Kepala Madrasah,<div class="space"></div><b><?= esc(strtoupper($config['kpl_sek'] ?? '')) ?></b></div>
            <div>Jember, <?= isset($config['tgl_lap']) ? date('d-m-Y', strtotime($config['tgl_lap'])) : date('d-m-Y') ?><br>Bendahara Komite,<div class="space"></div><b><?= esc(strtoupper($config['nm_bdrkomite'] ?? '')) ?></b></div>
        </div>
    </div>
</section>

<style>
    @media print {
        @page { size: landscape; margin: .5cm; }
        .neo .table { font-size: 8pt; }
    }
</style>
<?= $this->endSection() ?>
