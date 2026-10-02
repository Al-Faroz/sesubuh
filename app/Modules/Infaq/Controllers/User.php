<?php

namespace Modules\Infaq\Controllers;

use App\Controllers\BaseController;
use Modules\Infaq\Models\UserModel;
use Modules\Infaq\Services\AuditService;

class User extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return view('\Modules\Infaq\Views\v_user_index', [
            'title' => 'Manajemen Admin',
            'users' => $this->userModel->findAll(),
        ]);
    }

    public function simpan()
    {
        $rules = [
            'nama_user' => 'required|max_length[100]',
            'username'  => 'required|alpha_numeric|min_length[3]|max_length[50]|is_unique[users.username]',
            'password'  => 'required|min_length[5]|max_length[72]',
            'role'      => 'required|in_list[admin,operator]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(base_url('admin/user'))->with('error', implode(' ', $this->validator->getErrors()));
        }

        $id = $this->userModel->insert([
            'nama_user' => $this->request->getPost('nama_user'),
            'username'  => $this->request->getPost('username'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'      => $this->request->getPost('role'),
        ]);
        (new AuditService())->log('tambah', 'users', (int) $id, null, [
            'username' => $this->request->getPost('username'),
            'role'     => $this->request->getPost('role'),
        ]);

        return redirect()->to(base_url('admin/user'))->with('success', 'Akun baru berhasil ditambahkan');
    }

    public function hapus($id)
    {
        $id   = (int) $id;
        $user = $this->userModel->find($id);

        if (! $user) {
            return redirect()->to(base_url('admin/user'))->with('error', 'Akun tidak ditemukan.');
        }
        if ((int) session()->get('id_user') === $id) {
            return redirect()->to(base_url('admin/user'))->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }
        if ($user['role'] === 'admin' && $this->userModel->where('role', 'admin')->countAllResults() <= 1) {
            return redirect()->to(base_url('admin/user'))->with('error', 'Admin terakhir tidak boleh dihapus.');
        }

        $this->userModel->delete($id);
        (new AuditService())->log('hapus', 'users', $id, ['username' => $user['username'], 'role' => $user['role']], null);

        return redirect()->to(base_url('admin/user'))->with('success', 'Akun berhasil dihapus');
    }
}
