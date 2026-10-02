<?php

namespace Modules\Infaq\Controllers;

use App\Controllers\BaseController;
use Modules\Infaq\Models\KreditModel;
use Modules\Infaq\Services\AuditService;

class Kredit extends BaseController
{
    protected $kreditModel;

    private array $rules = [
        'tanggal'    => 'required|valid_date[Y-m-d]',
        'nominal'    => 'required|decimal|greater_than[0]',
        'keterangan' => 'required|max_length[500]',
    ];

    public function __construct()
    {
        $this->kreditModel = new KreditModel();
    }

    public function index()
    {
        $list = $this->kreditModel->orderBy('tanggal', 'DESC')->orderBy('id_kredit', 'DESC')->paginate(10);

        return view('Modules\Infaq\Views\v_kredit', [
            'title'            => 'Manajemen Pengeluaran (Kredit)',
            'list'             => $list,
            'pager'            => $this->kreditModel->pager,
            'tanggal_hari_ini' => date('Y-m-d'),
        ]);
    }

    private function dataForm(): array
    {
        return [
            'tanggal'    => $this->request->getPost('tanggal'),
            'nominal'    => (float) $this->request->getPost('nominal'),
            'keterangan' => trim((string) $this->request->getPost('keterangan')),
        ];
    }

    public function save()
    {
        if (! $this->validate($this->rules)) {
            return redirect()->to(base_url('admin/kredit'))->with('error', implode(' ', $this->validator->getErrors()));
        }

        $data = $this->dataForm();
        $id   = $this->kreditModel->insert($data + ['created_by' => session()->get('id_user')]);
        (new AuditService())->log('tambah', 'sedekah_keluar', (int) $id, null, $data);

        return redirect()->to(base_url('admin/kredit'))->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function update($id)
    {
        $id  = (int) $id;
        $old = $this->kreditModel->find($id);
        if (! $old) {
            return redirect()->to(base_url('admin/kredit'))->with('error', 'Data tidak ditemukan.');
        }
        if (! $this->validate($this->rules)) {
            return redirect()->to(base_url('admin/kredit'))->with('error', implode(' ', $this->validator->getErrors()));
        }

        $baru = $this->dataForm();
        $lama = ['tanggal' => $old['tanggal'], 'nominal' => (float) $old['nominal'], 'keterangan' => $old['keterangan']];
        if ($lama == $baru) {
            return redirect()->to(base_url('admin/kredit'))->with('success', 'Tidak ada perubahan data.');
        }

        $this->kreditModel->update($id, $baru + ['updated_by' => session()->get('id_user'), 'updated_at' => date('Y-m-d H:i:s')]);
        (new AuditService())->log('ubah', 'sedekah_keluar', $id, $lama, $baru);

        return redirect()->to(base_url('admin/kredit'))->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function delete($id)
    {
        $id  = (int) $id;
        $old = $this->kreditModel->find($id);
        if (! $old) {
            return redirect()->to(base_url('admin/kredit'))->with('error', 'Data tidak ditemukan.');
        }

        $this->kreditModel->delete($id);
        (new AuditService())->log('hapus', 'sedekah_keluar', $id, ['tanggal' => $old['tanggal'], 'nominal' => (float) $old['nominal'], 'keterangan' => $old['keterangan']], null);

        return redirect()->to(base_url('admin/kredit'))->with('success', 'Data pengeluaran dihapus (tercatat di riwayat).');
    }
}
