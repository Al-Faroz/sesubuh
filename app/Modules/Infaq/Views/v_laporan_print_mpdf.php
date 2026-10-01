<?php
// HELPER: Tanggal Indonesia (ditaruh langsung di view agar praktis)
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
    $split = explode('-', $tanggal); // Format: YYYY-MM-DD
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

// Logic Tanggal Tanda Tangan
$tgl_tanda_tangan = isset($config['tgl_lap']) && !empty($config['tgl_lap'])
    ? $config['tgl_lap']
    : date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Kas Sedekah Subuh</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11pt;
            color: #000;
        }

        /* --- LOGIC UTAMA TABEL --- */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        /* Header Tabel: Ulangi di setiap halaman baru */
        thead {
            display: table-header-group;
        }

        /* Baris Header */
        .table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        /* Footer Tabel: Hanya muncul di akhir data (bukan tiap halaman) */
        tfoot {
            display: table-row-group;
        }

        /* Baris Data: Jangan dipotong di tengah baris jika halaman habis */
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        /* --- HELPER TEXT --- */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .font-bold {
            font-weight: bold;
        }

        /* Footer Total Style */
        .footer-total td {
            background-color: #ddd;
            font-weight: bold;
            border-top: 2px solid #000;
        }

        /* --- TANDA TANGAN --- */
        .signature-table {
            width: 100%;
            margin-top: 40px;
            border: none;
            page-break-inside: avoid;
            /* Tanda tangan jangan terpotong */
        }

        .signature-table td {
            border: none;
            text-align: center;
            vertical-align: top;
            width: 50%;
            /* Bagi 2 kolom rata */
        }
    </style>
</head>

<body>

    <div class="text-center">
        <h2 style="margin: 0;">BUKU KAS UMUM SEDEKAH SUBUH</h2>
        <h3 style="margin: 5px 0;">MIN 6 JEMBER</h3>
        <p style="margin: 0;">Periode: <?= date('d/m/Y', strtotime($tgl_awal)) ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)) ?></p>
    </div>
    <br>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 5%;">NO</th>
                <th style="width: 13%;">TANGGAL</th>
                <th style="width: 37%;">URAIAN</th>
                <th style="width: 15%;">MASUK</th>
                <th style="width: 15%;">KELUAR</th>
                <th style="width: 15%;">SALDO</th>
            </tr>
        </thead>
        <tbody>
            <tr style="background-color: #f9f9f9;">
                <td colspan="3" class="text-center font-bold">SALDO AWAL PERIODE</td>
                <td class="text-right">-</td>
                <td class="text-right">-</td>
                <td class="text-right font-bold"><?= number_format($saldo_awal, 0, ',', '.') ?></td>
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
                    <td class="text-left"><?= $row['uraian'] ?></td>
                    <td class="text-right"><?= $row['masuk'] > 0 ? number_format($row['masuk'], 0, ',', '.') : '-' ?></td>
                    <td class="text-right"><?= $row['keluar'] > 0 ? number_format($row['keluar'], 0, ',', '.') : '-' ?></td>
                    <td class="text-right font-bold"><?= number_format($saldo, 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>

        <tfoot>
            <tr class="footer-total">
                <td colspan="3" class="text-center">TOTAL MUTASI & SALDO AKHIR</td>
                <td class="text-right"><?= number_format($total_masuk, 0, ',', '.') ?></td>
                <td class="text-right"><?= number_format($total_keluar, 0, ',', '.') ?></td>
                <td class="text-right" style="background-color: #ccc;"><?= number_format($saldo, 0, ',', '.') ?></td>
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