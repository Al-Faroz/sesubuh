<?php

namespace Modules\Infaq\Controllers;

use App\Controllers\BaseController;
use Modules\Infaq\Models\KelasModel;

class Laporan extends BaseController
{
    public function matrik()
    {
        $db = \Config\Database::connect();
        $kelasModel = new \Modules\Infaq\Models\KelasModel();

        // Ambil data pejabat & tanggal laporan dari tabel pengaturan
        $config = $db->table('pengaturan')->where('id', 1)->get()->getRowArray();

        $tgl_awal  = $this->request->getGet('tgl_awal') ?? date('Y-m-d', strtotime('monday this week'));
        $tgl_akhir = $this->request->getGet('tgl_akhir') ?? date('Y-m-d', strtotime('saturday this week'));

        $transaksi = $db->table('sedekah_masuk')
            ->where('tanggal >=', $tgl_awal)
            ->where('tanggal <=', $tgl_akhir)
            ->get()->getResultArray();

        $data = [
            'title'     => 'Matrik Sedekah Per Kelas',
            'tgl_awal'  => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
            'kelas'     => $kelasModel->getAktif(),
            'transaksi' => $transaksi,
            'config'    => $config // Dikirim ke view
        ];

        return view('Modules\Infaq\Views\v_laporan_matrik', $data);
    }

    public function kas()
    {
        $db = \Config\Database::connect();
        $config = $db->table('pengaturan')->where('id', 1)->get()->getRowArray();

        $tgl_awal  = $this->request->getGet('tgl_awal') ?? date('Y-m-01');
        $tgl_akhir = $this->request->getGet('tgl_akhir') ?? date('Y-m-d');


        // Query UNION yang dibungkus Subquery agar tidak DOBEL
        $sql = "SELECT tanggal, uraian, SUM(masuk) as masuk, SUM(keluar) as keluar FROM (
                SELECT tanggal, 'Pemasukan Sedekah Subuh Kelas 1-6' as uraian, nominal as masuk, 0 as keluar FROM sedekah_masuk
                UNION ALL
                SELECT tanggal, keterangan as uraian, 0 as masuk, nominal as keluar FROM sedekah_keluar
            ) AS gabungan 
            WHERE tanggal BETWEEN ? AND ?
            GROUP BY tanggal, uraian
            ORDER BY tanggal ASC";

        $data = [
            'title'      => 'Buku Kas Umum Sedekah Subuh',
            'tgl_awal'   => $tgl_awal,
            'tgl_akhir'  => $tgl_akhir,
            'saldo_awal' => (new \Modules\Infaq\Services\FinanceService())->saldoSebelum($tgl_awal),
            'list'       => $db->query($sql, [$tgl_awal, $tgl_akhir])->getResultArray(),
            'config'     => $config
        ];
        return view('Modules\Infaq\Views\v_laporan_kas', $data);
    }

    // Fungsi Update Pengaturan
    public function pengaturan()
    {
        $db = \Config\Database::connect();

        // Gunakan pengecekan method yang lebih kuat
        if ($this->request->getMethod() === 'post' || $this->request->getPost('nm_kakomite')) {
            $dataUpdate = [
                'nm_kakomite'  => $this->request->getPost('nm_kakomite'),
                'nm_bdrkomite' => $this->request->getPost('nm_bdrkomite'),
                'kpl_sek'      => $this->request->getPost('kpl_sek'),
                'tgl_lap'      => $this->request->getPost('tgl_lap'),
            ];

            // Pastikan ID 1 ada di database
            $lama = $db->table('pengaturan')->where('id', 1)->get()->getRowArray();
            $db->table('pengaturan')->where('id', 1)->update($dataUpdate);
            (new \Modules\Infaq\Services\AuditService())->log('ubah', 'pengaturan', 1, $lama ? array_diff_key($lama, ['id' => 1]) : null, $dataUpdate);

            session()->setFlashdata('success', 'Data Berhasil Diupdate!');
            return redirect()->to(base_url('admin/laporan/pengaturan'));
        }

        $data = [
            'title'  => 'Pengaturan Pejabat Laporan',
            'config' => $db->table('pengaturan')->where('id', 1)->get()->getRowArray()
        ];

        return view('Modules\Infaq\Views\v_pengaturan_laporan', $data);
    }

    // ... code sebelumnya ...

    public function cetak_pdf()
    {
        $db = \Config\Database::connect();

        // 1. Ambil Data (Sama seperti sebelumnya)
        $config = $db->table('pengaturan')->where('id', 1)->get()->getRowArray();
        $tgl_awal  = $this->request->getGet('tgl_awal') ?? date('Y-m-01');
        $tgl_akhir = $this->request->getGet('tgl_akhir') ?? date('Y-m-d');


        $sql = "SELECT tanggal, uraian, SUM(masuk) as masuk, SUM(keluar) as keluar FROM (
                SELECT tanggal, 'Pemasukan Sedekah Subuh Kelas 1-6' as uraian, nominal as masuk, 0 as keluar FROM sedekah_masuk
                UNION ALL
                SELECT tanggal, keterangan as uraian, 0 as masuk, nominal as keluar FROM sedekah_keluar
            ) AS gabungan 
            WHERE tanggal BETWEEN ? AND ?
            GROUP BY tanggal, uraian
            ORDER BY tanggal ASC";

        $data = [
            'tgl_awal'   => $tgl_awal,
            'tgl_akhir'  => $tgl_akhir,
            'saldo_awal' => (new \Modules\Infaq\Services\FinanceService())->saldoSebelum($tgl_awal),
            'list'       => $db->query($sql, [$tgl_awal, $tgl_akhir])->getResultArray(),
            'config'     => $config
        ];

        // 2. Render View ke HTML
        $html = view('\Modules\Infaq\Views\v_laporan_print_mpdf', $data);

        // --- PERBAIKAN DISINI ---
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => sys_get_temp_dir(), // <--- WAJIB UNTUK HOSTING!
            'mode' => 'utf-8', 
            'format' => [215, 330], 
            'orientation' => 'P',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10
        ]);

        $mpdf->WriteHTML($html);

        // Output
        $nama_file = 'Laporan-Kas-' . date('dmY') . '.pdf';
        
        // Bersihkan buffer output sebelum kirim PDF (Pencegah file corrupt)
        if (ob_get_length()) ob_end_clean(); // Ganti ob_clean() jadi ob_end_clean() lebih ampuh

        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output($nama_file, 'D'); 
        exit();
    }

    // ... Function matrik() dan kas() sudah ada di atas ...

    public function cetak_matrik_pdf()
    {
        $db = \Config\Database::connect();
        $kelasModel = new \Modules\Infaq\Models\KelasModel();

        // 1. Ambil Data (Sama persis dengan function matrik)
        $config = $db->table('pengaturan')->where('id', 1)->get()->getRowArray();

        $tgl_awal  = $this->request->getGet('tgl_awal') ?? date('Y-m-d', strtotime('monday this week'));
        $tgl_akhir = $this->request->getGet('tgl_akhir') ?? date('Y-m-d', strtotime('saturday this week'));

        // Query Transaksi
        $transaksi = $db->table('sedekah_masuk')
            ->where('tanggal >=', $tgl_awal)
            ->where('tanggal <=', $tgl_akhir)
            ->orderBy('tanggal', 'ASC')
            ->get()->getResultArray();

        $data = [
            'tgl_awal'  => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
            'kelas'     => $kelasModel->getAktif(), // Penting: Urutan kelas harus konsisten
            'transaksi' => $transaksi,
            'config'    => $config
        ];

        // 2. Render View ke HTML
        $html = view('\Modules\Infaq\Views\v_laporan_matrik_print_mpdf', $data);

        // --- PERBAIKAN DISINI ---
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => sys_get_temp_dir(), // <--- WAJIB UNTUK HOSTING!
            'mode' => 'utf-8', 
            // Ingat trik landscape kemarin: Ukuran Portrait, Orientasi Landscape
            'format' => [215, 330], 
            'orientation' => 'L',   
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10
        ]);

        $mpdf->WriteHTML($html);

        // Output
        $nama_file = 'Laporan-Matrik-' . date('dmY') . '.pdf';
        
        if (ob_get_length()) ob_end_clean(); // Bersihkan buffer

        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output($nama_file, 'D'); 
        exit();
    }
}
