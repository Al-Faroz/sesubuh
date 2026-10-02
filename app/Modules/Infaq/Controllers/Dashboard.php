<?php

namespace Modules\Infaq\Controllers;

use App\Controllers\BaseController;
use Modules\Infaq\Services\AuditService;
use Modules\Infaq\Services\FinanceService;

class Dashboard extends BaseController
{
    public function index()
    {
        $fin       = new FinanceService();
        $t         = $fin->totals();
        $jmlKelas  = \Config\Database::connect()->table('kelas')->where('status', 'aktif')->countAllResults();

        return view('Modules\Infaq\Views\v_dashboard', [
            'title'             => 'Dashboard Utama',
            'total_pemasukan'   => $t['masuk'],
            'total_pengeluaran' => $t['keluar'],
            'saldo_akhir'       => $t['saldo'],
            'partisipasi_today' => $fin->partisipasi(date('Y-m-d')) . ' dari ' . $jmlKelas . ' kelas',
            'tren'              => $fin->trenHarian(10),
            'jml_kelas'         => $jmlKelas,
            'aktivitas'         => (new AuditService())->terbaru(8),
        ]);
    }
}
