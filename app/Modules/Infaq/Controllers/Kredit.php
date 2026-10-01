<?php

namespace Modules\Infaq\Controllers;

use App\Controllers\BaseController;
use Modules\Infaq\Models\KreditModel;

class Kredit extends BaseController
{
    protected $kreditModel;

    public function __construct()
    {
        $this->kreditModel = new KreditModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Pengeluaran (Kredit)',
            'list'  => $this->kreditModel->orderBy('tanggal', 'DESC')->limit(10)->findAll(),
            'tanggal_hari_ini' => date('Y-m-d')
        ];

        return view('Modules\Infaq\Views\v_kredit', $data);
    }

    public function save()
    {
        $rules = [
            'tanggal'    => 'required|valid_date[Y-m-d]',
            'nominal'    => 'required|decimal|greater_than[0]',
            'keterangan' => 'required|max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(base_url('admin/kredit'))->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->kreditModel->insert([
            'tanggal'    => $this->request->getPost('tanggal'),
            'nominal'    => $this->request->getPost('nominal'),
            'keterangan' => trim($this->request->getPost('keterangan')),
            'created_by' => session()->get('id_user'),
        ]);

        return redirect()->to(base_url('admin/kredit'))->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function delete($id)
    {
        // Pastikan ID dikonversi ke integer untuk keamanan
        if ($this->kreditModel->delete((int)$id)) {
            return redirect()->to(base_url('admin/kredit'))->with('success', 'Data pengeluaran berhasil dihapus.');
        }
        return redirect()->to(base_url('admin/kredit'))->with('error', 'Gagal menghapus data.');
    }
}
