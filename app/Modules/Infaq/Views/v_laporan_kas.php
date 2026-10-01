<?= $this->extend('\\Modules\\Infaq\\Views\\layout\\v_wrapper') ?>
<?= $this->section('content') ?>
<h1 class="page-title no-print"><?= esc($title) ?></h1>

<form action="" method="get" class="card no-print">
    <div class="card-body toolbar">
        <div class="toolbar-actions">
            <div class="form-group"><label for="tgl_awal">Dari tanggal</label><input type="date" id="tgl_awal" name="tgl_awal" class="form-control" value="<?= esc($tgl_awal, 'attr') ?>"></div>
            <div class="form-group"><label for="tgl_akhir">Sampai tanggal</label><input type="date" id="tgl_akhir" name="tgl_akhir" class="form-control" value="<?= esc($tgl_akhir, 'attr') ?>"></div>
        </div>
        <div class="toolbar-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan</button>
            <a href="<?= base_url('admin/laporan/cetak_pdf?' . http_build_query(['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir])) ?>" target="_blank" rel="noopener" class="btn"><i class="fas fa-file-pdf"></i> Unduh PDF</a>
        </div>
    </div>
</form>

<section class="card">
    <div class="card-body">
        <div class="report-title">
            <h2>Buku Kas Umum Sedekah Subuh</h2>
            <h2>MIN 6 Jember</h2>
            <p>Periode: <?= date('d/m/Y', strtotime($tgl_awal)) ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)) ?></p>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-wide">
                <thead class="thead-accent">
                    <tr>
                        <th class="text-center" style="width:5%">No</th>
                        <th class="text-center" style="width:13%">Tanggal</th>
                        <th>Uraian</th>
                        <th class="text-center" style="width:14%">Pemasukan</th>
                        <th class="text-center" style="width:14%">Pengeluaran</th>
                        <th class="text-center" style="width:14%">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="row-total">
                        <td colspan="3" class="text-center">Saldo awal periode</td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                        <td class="text-right nowrap">Rp <?= number_format($saldo_awal, 0, ',', '.') ?></td>
                    </tr>
                    <?php
                    $no = 1;
                    $saldo = $saldo_awal;
                    $total_masuk = 0;
                    $total_keluar = 0;
                    foreach ($list as $row) :
                        $saldo += ($row['masuk'] - $row['keluar']);
                        $total_masuk += $row['masuk'];
                        $total_keluar += $row['keluar'];
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-center nowrap"><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                            <td><?php if ($row['masuk'] > 0) : ?><span class="badge badge-ok">Masuk</span><?php else : ?><span class="badge badge-bad">Keluar</span><?php endif; ?> <?= esc($row['uraian']) ?></td>
                            <td class="text-right nowrap <?= $row['masuk'] > 0 ? 'num-in' : '' ?>"><?= $row['masuk'] > 0 ? 'Rp ' . number_format($row['masuk'], 0, ',', '.') : '-' ?></td>
                            <td class="text-right nowrap <?= $row['keluar'] > 0 ? 'num-out' : '' ?>"><?= $row['keluar'] > 0 ? 'Rp ' . number_format($row['keluar'], 0, ',', '.') : '-' ?></td>
                            <td class="text-right nowrap fw-b">Rp <?= number_format($saldo, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="row-total">
                        <td colspan="3" class="text-center">Total mutasi &amp; saldo akhir</td>
                        <td class="text-right nowrap">Rp <?= number_format($total_masuk, 0, ',', '.') ?></td>
                        <td class="text-right nowrap">Rp <?= number_format($total_keluar, 0, ',', '.') ?></td>
                        <td class="text-right nowrap">Rp <?= number_format($saldo, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="sign avoid-break">
            <div>Ketua Komite,<div class="space"></div><b><?= esc($config['nm_kakomite'] ?? '.........................') ?></b></div>
            <div>Bendahara Komite,<div class="space"></div><b><?= esc($config['nm_bdrkomite'] ?? '.........................') ?></b></div>
            <div>Jember, <?= isset($config['tgl_lap']) ? date('d-m-Y', strtotime($config['tgl_lap'])) : date('d-m-Y') ?><br>Kepala Madrasah,<div class="space"></div><b><?= esc($config['kpl_sek'] ?? '.........................') ?></b></div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
