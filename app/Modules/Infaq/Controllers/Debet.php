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

        // Hanya kelas aktif yang boleh diisi
        $kelasValid = array_column($this->kelasModel->getAktif(), 'id_kelas');

        foreach ($nominal as $id_kelas => $value) {
            if (! in_array((int) $id_kelas, array_map('intval', $kelasValid), true)) {
                continue;
            }
            if ($value === '' || ! is_numeric($value) || $value < 0) {
                continue;
            }
            $this->infaqModel->upsertInfaq([
                'id_kelas'   => (int) $id_kelas,
                'nominal'    => (int) $value,
                'tanggal'    => $tanggal,
                'created_by' => $userId,
            ]);
        }

        return redirect()->to(base_url('admin/debet?tanggal=' . $tanggal))->with('success', 'Data berhasil diperbarui.');
    }
}
