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
        $tanggal = $this->request->getPost('tanggal');
        $nominal = $this->request->getPost('nominal');
        $userId  = session()->get('id_user') ?? 1;

        foreach ($nominal as $id_kelas => $value) {
            if ($value !== '' && $value >= 0) {
                $insertData = [
                    'id_kelas'   => $id_kelas,
                    'nominal'    => (int)$value,
                    'tanggal'    => $tanggal,
                    'created_by' => $userId
                ];
                $this->infaqModel->upsertInfaq($insertData);
            }
        }

        // Redirect kembali ke tanggal yang sama agar data yang baru diinput langsung terlihat
        return redirect()->to(base_url('admin/debet?tanggal=' . $tanggal))->with('success', 'Data berhasil diperbarui.');
    }
}
