<?php

namespace Modules\Infaq\Controllers;

use App\Controllers\BaseController;
use Modules\Infaq\Models\InfaqModel;
use Modules\Infaq\Models\KelasModel;

class Debet extends BaseController
{
    protected $infaqModel;
    protected $kelasModel;

    public function __construct()
    {
        $this->infaqModel = new InfaqModel();
        $this->kelasModel = new KelasModel();
    }

    public function index()
    {
        // Ambil tanggal dari filter URL (GET), jika tidak ada gunakan hari ini
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

        // Ambil data transaksi yang sudah ada di tanggal tersebut
        $existingData = $this->infaqModel->where('tanggal', $tanggal)->findAll();

        // Mapping nominal agar otomatis terisi di form
        $nominalValue = [];
        foreach ($existingData as $row) {
            $nominalValue[$row['id_kelas']] = $row['nominal'];
        }

        $data = [
            'title'            => 'Input Infaq Sedekah Subuh',
            'kelas'            => $this->kelasModel->getAktif(),
            'tanggal_hari_ini' => $tanggal, // Variabel ini yang dicari View
            'values'           => $nominalValue
        ];

        return view('Modules\Infaq\Views\v_debet', $data);
    }

    public function save()
    {
        $tanggal = (string) $this->request->getPost('tanggal');
        $nominal = $this->request->getPost('nominal');
        $userId  = session()->get('id_user');

        $dt = \DateTime::createFromFormat('Y-m-d', $tanggal);
        if (! $dt || $dt->format('Y-m-d') !== $tanggal || ! is_array($nominal)) {
            return redirect()->to(base_url('admin/debet'))->with('error', 'Tanggal atau data tidak valid.');
        }

        $namaKelas = array_column($this->kelasModel->getAktif(), 'nama_kelas', 'id_kelas');

        $existing = [];
        foreach ($this->infaqModel->where('tanggal', $tanggal)->findAll() as $r) {
            $existing[(int) $r['id_kelas']] = $r;
        }

        $audit = new \Modules\Infaq\Services\AuditService();
        $db    = \Config\Database::connect();
        $tambah = $ubah = 0;

        $db->transStart();
        foreach ($nominal as $idKelas => $value) {
            $id = (int) $idKelas;
            if (! isset($namaKelas[$id]) || $value === '' || ! is_numeric($value) || $value < 0) {
                continue;
            }
            $nom = (int) $value;

            if (! isset($existing[$id])) {
                $newId = $this->infaqModel->insert(['id_kelas' => $id, 'nominal' => $nom, 'tanggal' => $tanggal, 'created_by' => $userId]);
                $audit->log('tambah', 'sedekah_masuk', (int) $newId, null, ['kelas' => $namaKelas[$id], 'tanggal' => $tanggal, 'nominal' => $nom]);
                $tambah++;
            } elseif ((int) round((float) $existing[$id]['nominal']) !== $nom) {
                $lama = (int) round((float) $existing[$id]['nominal']);
                $this->infaqModel->update($existing[$id]['id_debet'], ['nominal' => $nom, 'updated_by' => $userId, 'updated_at' => date('Y-m-d H:i:s')]);
                $audit->log('ubah', 'sedekah_masuk', (int) $existing[$id]['id_debet'], ['kelas' => $namaKelas[$id], 'tanggal' => $tanggal, 'nominal' => $lama], ['kelas' => $namaKelas[$id], 'tanggal' => $tanggal, 'nominal' => $nom]);
                $ubah++;
            }
        }
        $db->transComplete();

        $pesan = ($tambah + $ubah) === 0 ? 'Tidak ada perubahan data.' : "Tersimpan: {$tambah} baru, {$ubah} diubah.";

        return redirect()->to(base_url('admin/debet?tanggal=' . $tanggal))->with('success', $pesan);
    }
}
