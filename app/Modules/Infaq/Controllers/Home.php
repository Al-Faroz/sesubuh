<?php

namespace Modules\Infaq\Controllers;

use App\Controllers\BaseController;
use Modules\Infaq\Models\KelasModel;

class Home extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $kelasModel = new \Modules\Infaq\Models\KelasModel();

        // Default: 7 hari terakhir s/d hari ini
        $tgl_awal  = $this->request->getGet('tgl_awal') ?? date('Y-m-d', strtotime('-6 days'));
        $tgl_akhir = $this->request->getGet('tgl_akhir') ?? date('Y-m-d');

        // 1. Widget Box (Tetap Akumulasi Global)
        $pemasukan = $db->table('sedekah_masuk')->selectSum('nominal')->get()->getRow()->nominal ?? 0;
        $pengeluaran = $db->table('sedekah_keluar')->selectSum('nominal')->get()->getRow()->nominal ?? 0;

        // 2. Data Matrik dalam rentang waktu terpilih
        $transaksi = $db->table('sedekah_masuk')
            ->where('tanggal >=', $tgl_awal)
            ->where('tanggal <=', $tgl_akhir)
            ->orderBy('tanggal', 'DESC') // Terbaru di atas
            ->get()->getResultArray();

        $data = [
            'title'             => 'Portal Sedekah Subuh - MIN 6 JEMBER',
            'total_pemasukan'   => $pemasukan,
            'total_pengeluaran' => $pengeluaran,
            'saldo_akhir'       => $pemasukan - $pengeluaran,
            'tgl_awal'          => $tgl_awal,
            'tgl_akhir'         => $tgl_akhir,
            'kelas'             => $kelasModel->getAktif(),
            'transaksi'         => $transaksi
        ];

        return view('Modules\Infaq\Views\v_home', $data);
    }
}
