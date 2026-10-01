<?php

namespace Modules\Infaq\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $hari_ini = date('Y-m-d');

        // 1. Hitung Ringkasan Saldo (Widget Box)
        $pemasukan = $db->table('sedekah_masuk')->selectSum('nominal')->get()->getRow()->nominal ?? 0;
        $pengeluaran = $db->table('sedekah_keluar')->selectSum('nominal')->get()->getRow()->nominal ?? 0;
        $partisipasi = $db->table('sedekah_masuk')->where('tanggal', $hari_ini)->countAllResults(false);

        $data = [
            'title'             => 'Dashboard Utama',
            'total_pemasukan'   => $pemasukan,
            'total_pengeluaran' => $pengeluaran,
            'saldo_akhir'       => $pemasukan - $pengeluaran,
            'partisipasi_today' => $partisipasi . ' Kelas',
        ];

        return view('Modules\Infaq\Views\v_dashboard', $data);
    }
}
