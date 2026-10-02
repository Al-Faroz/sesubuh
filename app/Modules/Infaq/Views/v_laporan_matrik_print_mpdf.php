<?php
// Helper Tanggal Indo
function tanggal_indo($tanggal)
{
    $bulan = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

// Logic Tanggal Tanda Tangan
$tgl_tanda_tangan = isset($config['tgl_lap']) && !empty($config['tgl_lap'])
    ? $config['tgl_lap']
    : date('Y-m-d');

// --- PRE-PROCESSING DATA (PIVOT) ---
// Kita olah dulu datanya di atas sebelum masuk HTML agar rapi
$matrix = [];
$total_per_kelas = []; // Untuk Footer Bawah
$total_per_hari = [];  // Untuk Kolom Paling Kanan

// Inisialisasi total per kelas jadi 0
foreach ($kelas as $k) {
    $total_per_kelas[$k['id_kelas']] = 0;
}

// Mapping data transaksi ke format Matrix[Tanggal][Id_Kelas]
foreach ($transaksi as $t) {
    $matrix[$t['tanggal']][$t['id_kelas']] = $t['nominal'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Matrik Infaq</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 9pt;
        }

        /* Font agak kecil agar muat */

        /* Tabel Utama */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: middle;
        }

        /* Header & Footer Style */
        .table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        .footer-total td {
            background-color: #ddd;
            font-weight: bold;
        }

        /* Helper */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        /* Agar Header Tabel berulang di halaman baru */
        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-row-group;
        }

        tr {
            page-break-inside: avoid;
        }

        /* Tanda Tangan */
        .signature-table {
            width: 100%;
            margin-top: 30px;
            border: none;
            page-break-inside: avoid;
        }

        .signature-table td {
            border: none;
            text-align: center;
            vertical-align: top;
            width: 50%;
        }
    </style>
</head>

<body>

    <table style="width: 100%; border: 0; border-bottom: 2px solid #000; margin-bottom: 8px;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width: 75px; border: 0; padding: 0 0 6px 0; vertical-align: middle;">
                <?php if ($logo = logo_pdf_uri()) : ?><img src="<?= $logo ?>" width="68" height="68"><?php endif; ?>
            </td>
            <td style="border: 0; padding: 0 0 6px 0; text-align: center; vertical-align: middle;">
                <h3 style="margin: 0;">REKAPITULASI SEDEKAH SUBUH PER KELAS</h3>
                <h4 style="margin: 5px 0;">MIN 6 JEMBER</h4>
                <p style="margin: 0;">Periode: <?= date('d/m/Y', strtotime($tgl_awal)) ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)) ?></p>
            </td>
            <td style="width: 75px; border: 0;"></td>
        </tr>
    </table>
    <br>

    <table class="table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 80px;">TANGGAL</th> <?php foreach ($kelas as $k) : ?>
                    <th><?= esc($k['nama_kelas']) ?></th>
                <?php endforeach; ?>
                <th rowspan="2" style="width: 80px;">TOTAL</th>
            </tr>
            <tr>
                <?php foreach ($kelas as $k) : ?>
                    <th style="font-size: 7pt; font-weight: normal;">
                        <?= $k['jml_anak'] ?? '0' ?> Siswa
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>

        <tbody>
            <?php
            $grand_total_semua = 0;

            if (!empty($matrix)) :
                // Loop berdasarkan Tanggal
                foreach ($matrix as $tgl => $data_kelas) :
                    $subtotal_hari_ini = 0;
            ?>
                    <tr>
                        <td class="text-center"><?= date('d/m/Y', strtotime($tgl)) ?></td>

                        <?php foreach ($kelas as $k) :
                            $nominal = $data_kelas[$k['id_kelas']] ?? 0;

                            // Hitung Total Vertical & Horizontal
                            $subtotal_hari_ini += $nominal;
                            $total_per_kelas[$k['id_kelas']] += $nominal;
                        ?>
                            <td class="text-right">
                                <?= $nominal > 0 ? number_format($nominal, 0, ',', '.') : '-' ?>
                            </td>
                        <?php endforeach; ?>

                        <td class="text-right font-bold" style="background-color: #f9f9f9;">
                            <?= number_format($subtotal_hari_ini, 0, ',', '.') ?>
                        </td>
                    </tr>
                <?php
                    $grand_total_semua += $subtotal_hari_ini;
                endforeach;
            else:
                ?>
                <tr>
                    <td colspan="<?= count($kelas) + 2 ?>" class="text-center">Belum ada data transaksi pada periode ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>

        <tfoot>
            <tr class="footer-total">
                <td class="text-center">TOTAL SETORAN</td>

                <?php foreach ($kelas as $k) : ?>
                    <td class="text-right">
                        <?= number_format($total_per_kelas[$k['id_kelas']] ?? 0, 0, ',', '.') ?>
                    </td>
                <?php endforeach; ?>

                <td class="text-right" style="background-color: #ccc;">
                    <?= number_format($grand_total_semua, 0, ',', '.') ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                <p>Ketua Komite,</p>
                <br><br><br><br>
                <p class="font-bold" style="text-decoration: underline;"><?= esc($config['nm_kakomite'] ?? '.........................') ?></p>
            </td>
            <td>
                <p>Jember, <?= tanggal_indo($tgl_tanda_tangan) ?></p>
                <p>Bendahara Komite,</p>
                <br><br><br><br>
                <p class="font-bold" style="text-decoration: underline;"><?= $config['nm_bdrkomite'] ?? '.........................' ?></p>
            </td>
        </tr>
    </table>

</body>

</html>