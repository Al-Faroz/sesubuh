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

        // Default: 7 hari terakhir yang ada datanya
        $valid = static function ($d) {
            $x = \DateTime::createFromFormat('Y-m-d', (string) $d);
            return $x && $x->format('Y-m-d') === $d;
        };
        $terakhir  = $db->table('sedekah_masuk')->selectMax('tanggal')->get()->getRow()->tanggal ?? date('Y-m-d');
        $tgl_akhir = $this->request->getGet('tgl_akhir');
        $tgl_awal  = $this->request->getGet('tgl_awal');
        if (! $valid($tgl_akhir)) {
            $tgl_akhir = $terakhir;
        }
        if (! $valid($tgl_awal)) {
            $tgl_awal = date('Y-m-d', strtotime($tgl_akhir . ' -6 days'));
        }
        if ($tgl_awal > $tgl_akhir) {
            [$tgl_awal, $tgl_akhir] = [$tgl_akhir, $tgl_awal];
        }

        // 1. Widget Box (Tetap Akumulasi Global)
        $pemasukan = $db->table('sedekah_masuk')->selectSum('nominal')->get()->getRow()->nominal ?? 0;
        $pengeluaran = $db->table('sedekah_keluar')->selectSum('nominal')->get()->getRow()->nominal ?? 0;

        // 2. Data Matrik dalam rentang waktu terpilih
        $transaksi = $db->table('sedekah_masuk')
            ->where('tanggal >=', $tgl_awal)
            ->where('tanggal <=', $tgl_akhir)
            ->orderBy('tanggal', 'DESC') // Terbaru di atas
            ->get()->getResultArray();

        $kelas = $kelasModel->getAktif();

        // Data grafik & peringkat
        $harian = [];
        $perKelas = [];
        foreach ($transaksi as $t) {
            $harian[$t['tanggal']] = ($harian[$t['tanggal']] ?? 0) + (int) $t['nominal'];
            $perKelas[$t['id_kelas']] = ($perKelas[$t['id_kelas']] ?? 0) + (int) $t['nominal'];
        }
        ksort($harian);
        $peringkat = [];
        foreach ($kelas as $k) {
            $peringkat[] = ['nama' => $k['nama_kelas'], 'total' => $perKelas[$k['id_kelas']] ?? 0];
        }
        usort($peringkat, static fn($a, $b) => $b['total'] <=> $a['total']);

        $data = [
            'title'             => 'Portal Sedekah Subuh - MIN 6 JEMBER',
            'total_pemasukan'   => $pemasukan,
            'total_pengeluaran' => $pengeluaran,
            'saldo_akhir'       => $pemasukan - $pengeluaran,
            'tgl_awal'          => $tgl_awal,
            'tgl_akhir'         => $tgl_akhir,
            'kelas'             => $kelas,
            'transaksi'         => $transaksi,
            'harian'            => $harian,
            'peringkat'         => $peringkat,
            'total_periode'     => array_sum($harian),
        ];

        return view('Modules\Infaq\Views\v_home', $data);
    }
}
