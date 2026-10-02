<?php

namespace Modules\Infaq\Controllers;

use App\Controllers\BaseController;
use Modules\Infaq\Models\KelasModel;
use Modules\Infaq\Services\FinanceService;

class Home extends BaseController
{
    public function index()
    {
        $db    = \Config\Database::connect();
        $kelas = (new KelasModel())->getAktif();

        $valid = static function ($d) {
            $x = \DateTime::createFromFormat('Y-m-d', (string) $d);
            return $x && $x->format('Y-m-d') === $d;
        };

        // Default periode: 10 hari terakhir yang ada datanya
        $tgl_awal  = $this->request->getGet('tgl_awal');
        $tgl_akhir = $this->request->getGet('tgl_akhir');
        if (! $valid($tgl_awal) || ! $valid($tgl_akhir)) {
            $hariData  = $db->table('sedekah_masuk')->select('tanggal')->distinct()->orderBy('tanggal', 'DESC')->limit(10)->get()->getResultArray();
            $tgl_akhir = $hariData[0]['tanggal'] ?? date('Y-m-d');
            $tgl_awal  = $hariData ? end($hariData)['tanggal'] : $tgl_akhir;
        }
        if ($tgl_awal > $tgl_akhir) {
            [$tgl_awal, $tgl_akhir] = [$tgl_akhir, $tgl_awal];
        }

        $total = (new FinanceService())->totals();

        // Transaksi pada periode terpilih (terbaru di atas)
        $transaksi = $db->table('sedekah_masuk')
            ->where('tanggal >=', $tgl_awal)
            ->where('tanggal <=', $tgl_akhir)
            ->orderBy('tanggal', 'DESC')
            ->get()->getResultArray();

        // Ringkasan periode
        $harian = $kelasPerHari = $perKelas = $hariSetor = [];
        foreach ($transaksi as $t) {
            $nom = (int) $t['nominal'];
            $harian[$t['tanggal']]    = ($harian[$t['tanggal']] ?? 0) + $nom;
            $perKelas[$t['id_kelas']] = ($perKelas[$t['id_kelas']] ?? 0) + $nom;
            if ($nom > 0) {
                $kelasPerHari[$t['tanggal']] = ($kelasPerHari[$t['tanggal']] ?? 0) + 1;
                $hariSetor[$t['id_kelas']]   = ($hariSetor[$t['id_kelas']] ?? 0) + 1;
            }
        }
        ksort($harian);

        $peringkat = [];
        foreach ($kelas as $k) {
            $peringkat[] = [
                'nama'  => $k['nama_kelas'],
                'total' => $perKelas[$k['id_kelas']] ?? 0,
                'hari'  => $hariSetor[$k['id_kelas']] ?? 0,
            ];
        }
        usort($peringkat, static fn($a, $b) => [$b['total'], $b['hari']] <=> [$a['total'], $a['hari']]);

        $tertinggi = null;
        if ($harian) {
            $tgl       = array_search(max($harian), $harian, true);
            $tertinggi = ['tanggal' => $tgl, 'total' => $harian[$tgl]];
        }

        return view('Modules\Infaq\Views\v_home', [
            'title'             => 'Portal Sedekah Subuh - MIN 6 JEMBER',
            'total_pemasukan'   => $total['masuk'],
            'total_pengeluaran' => $total['keluar'],
            'saldo_akhir'       => $total['saldo'],
            'tgl_awal'          => $tgl_awal,
            'tgl_akhir'         => $tgl_akhir,
            'kelas'             => $kelas,
            'transaksi'         => $transaksi,
            'harian'            => $harian,
            'kelas_per_hari'    => $kelasPerHari,
            'peringkat'         => $peringkat,
            'total_periode'     => array_sum($harian),
            'rata_harian'       => $harian ? (int) round(array_sum($harian) / count($harian)) : 0,
            'tertinggi'         => $tertinggi,
            'hari_total'        => count($harian),
        ]);
    }
}
