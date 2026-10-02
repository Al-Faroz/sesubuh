<?php

namespace Modules\Infaq\Controllers;

use App\Controllers\BaseController;
use Modules\Infaq\Models\KelasModel;
use Modules\Infaq\Services\AuditService;

class Kelas extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new KelasModel();
    }

    public function index()
    {
        return view('Modules\Infaq\Views\v_kelas', [
            'title' => 'Data Kelas',
            'kelas' => $this->model->orderBy('status', 'ASC')->orderBy('nama_kelas', 'ASC')->findAll(),
        ]);
    }

    public function simpan()
    {
        $rules = [
            'nama_kelas' => 'required|max_length[20]|is_unique[kelas.nama_kelas]',
            'jml_anak'   => 'required|is_natural|less_than_equal_to[100]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->to(base_url('admin/kelas'))->with('error', implode(' ', $this->validator->getErrors()));
        }

        $data = ['nama_kelas' => trim((string) $this->request->getPost('nama_kelas')), 'jml_anak' => (int) $this->request->getPost('jml_anak')];
        $id   = $this->model->insert($data + ['status' => 'aktif']);
        (new AuditService())->log('tambah', 'kelas', (int) $id, null, $data);

        return redirect()->to(base_url('admin/kelas'))->with('success', 'Kelas ditambahkan.');
    }

    public function update($id)
    {
        $id  = (int) $id;
        $old = $this->model->find($id);
        if (! $old) {
            return redirect()->to(base_url('admin/kelas'))->with('error', 'Kelas tidak ditemukan.');
        }

        $rules = [
            'nama_kelas' => "required|max_length[20]|is_unique[kelas.nama_kelas,id_kelas,{$id}]",
            'jml_anak'   => 'required|is_natural|less_than_equal_to[100]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->to(base_url('admin/kelas'))->with('error', implode(' ', $this->validator->getErrors()));
        }

        $baru = ['nama_kelas' => trim((string) $this->request->getPost('nama_kelas')), 'jml_anak' => (int) $this->request->getPost('jml_anak')];
        $lama = ['nama_kelas' => $old['nama_kelas'], 'jml_anak' => (int) $old['jml_anak']];
        if ($lama != $baru) {
            $this->model->update($id, $baru);
            (new AuditService())->log('ubah', 'kelas', $id, $lama, $baru);
        }

        return redirect()->to(base_url('admin/kelas'))->with('success', 'Data kelas disimpan.');
    }

    /** Kelas tidak dihapus (riwayat setoran tetap utuh), hanya diaktifkan/dinonaktifkan. */
    public function status($id)
    {
        $id  = (int) $id;
        $old = $this->model->find($id);
        if (! $old) {
            return redirect()->to(base_url('admin/kelas'))->with('error', 'Kelas tidak ditemukan.');
        }

        $baru = $old['status'] === 'aktif' ? 'nonaktif' : 'aktif';
        $this->model->update($id, ['status' => $baru]);
        (new AuditService())->log('ubah', 'kelas', $id, ['nama_kelas' => $old['nama_kelas'], 'status' => $old['status']], ['nama_kelas' => $old['nama_kelas'], 'status' => $baru]);

        return redirect()->to(base_url('admin/kelas'))->with('success', "Kelas {$old['nama_kelas']} sekarang {$baru}.");
    }
}
