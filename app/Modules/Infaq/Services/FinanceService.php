<?php

namespace Modules\Infaq\Services;

class FinanceService
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Menghitung Saldo Global secara Real-time
     * Sesuai DAK-02-DATA: SUM(Masuk) - SUM(Keluar)
     */
    public function getGlobalBalance(): float
    {
        $masuk  = $this->db->table('sedekah_masuk')->selectSum('nominal')->get()->getRow()->nominal ?? 0;
        $keluar = $this->db->table('sedekah_keluar')->selectSum('nominal')->get()->getRow()->nominal ?? 0;

        return (float) ($masuk - $keluar);
    }

    /**
     * Mengambil Statistik Ringkasan untuk Dashboard (v_dashboard)
     */
    public function getStatsSummary(): array
    {
        return [
            'total_masuk'  => $this->db->table('sedekah_masuk')->selectSum('nominal')->get()->getRow()->nominal ?? 0,
            'total_keluar' => $this->db->table('sedekah_keluar')->selectSum('nominal')->get()->getRow()->nominal ?? 0,
            'saldo'        => $this->getGlobalBalance(),
            'jumlah_kelas' => $this->db->table('kelas')->where('status', 'aktif')->countAllResults()
        ];
    }
}
