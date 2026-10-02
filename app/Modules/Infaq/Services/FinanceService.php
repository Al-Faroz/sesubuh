<?php

namespace Modules\Infaq\Services;

/**
 * Satu-satunya tempat perhitungan kas: saldo = SUM(masuk) - SUM(keluar).
 */
class FinanceService
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function totals(): array
    {
        $masuk  = (float) ($this->db->table('sedekah_masuk')->selectSum('nominal')->get()->getRow()->nominal ?? 0);
        $keluar = (float) ($this->db->table('sedekah_keluar')->selectSum('nominal')->get()->getRow()->nominal ?? 0);

        return ['masuk' => $masuk, 'keluar' => $keluar, 'saldo' => $masuk - $keluar];
    }

    public function getGlobalBalance(): float
    {
        return $this->totals()['saldo'];
    }

    /** Saldo seluruh transaksi sebelum tanggal tertentu (saldo awal buku kas). */
    public function saldoSebelum(string $tanggal): float
    {
        $masuk  = (float) ($this->db->table('sedekah_masuk')->selectSum('nominal')->where('tanggal <', $tanggal)->get()->getRow()->nominal ?? 0);
        $keluar = (float) ($this->db->table('sedekah_keluar')->selectSum('nominal')->where('tanggal <', $tanggal)->get()->getRow()->nominal ?? 0);

        return $masuk - $keluar;
    }

    /** Jumlah kelas yang bersetor (nominal > 0) pada tanggal tertentu. */
    public function partisipasi(string $tanggal): int
    {
        return $this->db->table('sedekah_masuk')->where('tanggal', $tanggal)->where('nominal >', 0)->countAllResults();
    }

    /** Total pemasukan per hari untuk N hari terakhir yang ada datanya (urut naik). */
    public function trenHarian(int $limit = 10): array
    {
        $rows = $this->db->query(
            'SELECT tanggal, SUM(nominal) AS total, SUM(nominal > 0) AS kelas FROM sedekah_masuk GROUP BY tanggal ORDER BY tanggal DESC LIMIT ' . (int) $limit
        )->getResultArray();
        $rows = array_reverse($rows);

        return [
            'labels' => array_map(static fn($r) => date('d/m', strtotime($r['tanggal'])), $rows),
            'values' => array_map(static fn($r) => (float) $r['total'], $rows),
            'kelas'  => array_map(static fn($r) => (int) $r['kelas'], $rows),
        ];
    }

    public function getStatsSummary(): array
    {
        $t = $this->totals();

        return [
            'total_masuk'  => $t['masuk'],
            'total_keluar' => $t['keluar'],
            'saldo'        => $t['saldo'],
            'jumlah_kelas' => $this->db->table('kelas')->where('status', 'aktif')->countAllResults(),
        ];
    }
}
