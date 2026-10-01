<?= $this->extend('\Modules\Infaq\Views\layout\v_wrapper') ?>
<?= $this->section('content') ?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Filter Laporan Kas</h3>
    </div>
    <form action="" method="get" class="form-inline">
        <div class="form-group mr-2">
            <label class="mr-2">Dari :</label>
            <input type="date" name="tgl_awal" class="form-control" value="<?= $tgl_awal ?>">
        </div>
        <div class="form-group mr-2">
            <label class="mr-2">Sampai :</label>
            <input type="date" name="tgl_akhir" class="form-control" value="<?= $tgl_akhir ?>">
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan</button>

        <a href="<?= base_url('admin/laporan/cetak_pdf?tgl_awal=' . $tgl_awal . '&tgl_akhir=' . $tgl_akhir) ?>" target="_blank" class="btn btn-danger ml-2">
            <i class="fas fa-file-pdf"></i> Download PDF
        </a>
    </form>
</div>

<div class="card shadow-none border-0" id="print-area">
    <div class="card-body">

        <div class="text-center mb-4">
            <h2 class="font-weight-bold">BUKU KAS UMUM SEDEKAH SUBUH</h2>
            <h3 class="font-weight-bold">MIN 6 JEMBER</h3>
            <p>Periode: <?= date('d/m/Y', strtotime($tgl_awal)) ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)) ?></p>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm custom-table">
                <thead>
                    <tr class="text-center bg-light">
                        <th style="width: 5%;">NO</th>
                        <th style="width: 12%;">TANGGAL</th>
                        <th style="width: 44%;">URAIAN</th>
                        <th style="width: 13%;">PEMASUKAN</th>
                        <th style="width: 13%;">PENGELUARAN</th>
                        <th style="width: 13%;">SALDO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="font-weight-bold bg-light">
                        <td colspan="3" class="text-center">SALDO AWAL PERIODE</td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                        <td class="text-right">Rp <?= number_format($saldo_awal, 0, ',', '.') ?></td>
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
                            <td class="text-center"><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                            <td class="text-wrap"><?= $row['uraian'] ?></td>
                            <td class="text-right"><?= $row['masuk'] > 0 ? 'Rp ' . number_format($row['masuk'], 0, ',', '.') : '-' ?></td>
                            <td class="text-right"><?= $row['keluar'] > 0 ? 'Rp ' . number_format($row['keluar'], 0, ',', '.') : '-' ?></td>
                            <td class="text-right font-weight-bold">Rp <?= number_format($saldo, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

                <tfoot>
                    <tr class="bg-gray font-weight-bold" style="background-color: #e9ecef !important;">
                        <td colspan="3" class="text-center text-uppercase">Total Mutasi & Saldo Akhir</td>
                        <td class="text-right">Rp <?= number_format($total_masuk, 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format($total_keluar, 0, ',', '.') ?></td>
                        <td class="text-right" style="background-color: #ddd !important;">Rp <?= number_format($saldo, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="row mt-5 avoid-break">
            <div class="col-4 text-center">
                <p>Ketua Komite,</p>
                <br><br><br>
                <p class="font-weight-bold underline"><?= $config['nm_kakomite'] ?? '.........................' ?></p>
            </div>
            <div class="col-4 text-center">
                <p>Bendahara Komite,</p>
                <br><br><br>
                <p class="font-weight-bold underline"><?= $config['nm_bdrkomite'] ?? '.........................' ?></p>
            </div>
            <div class="col-4 text-center">
                <p>Jember, <?= isset($config['tgl_lap']) ? date('d-m-Y', strtotime($config['tgl_lap'])) : date('d-m-Y') ?></p>
                <p>Kepala Madrasah,</p>
                <br><br><br>
                <p class="font-weight-bold underline"><?= $config['kpl_sek'] ?? '.........................' ?></p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling Normal (Layar) */
    .text-wrap {
        white-space: normal !important;
        word-wrap: break-word;
    }

    /* Styling Saat Print */
    @media print {

        /* Sembunyikan elemen non-cetak */
        .btn,
        .card-header,
        form,
        .main-footer,
        .navbar,
        .main-sidebar {
            display: none !important;
        }

        .content-wrapper,
        .card {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
        }

        /* Paksa Background Color keluar saat print (untuk header & footer tabel) */
        .bg-light,
        .bg-gray {
            background-color: #f4f6f9 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Ukuran Font Tabel */
        .table {
            font-size: 11pt !important;
            width: 100% !important;
        }

        .table td,
        .table th {
            padding: 4px !important;
            vertical-align: middle !important;
        }

        /* Lebar Kolom Spesifik (Override Bootstrap) */
        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 12% !important;
        }

        /* Tanggal */
        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 44% !important;
        }

        /* Uraian */

        /* Hindari pemotongan baris tanda tangan */
        .avoid-break {
            page-break-inside: avoid;
        }
    }
</style>
<?= $this->endSection() ?>