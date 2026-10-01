<?php

namespace Modules\Infaq\Controllers;

use App\Controllers\BaseController;
use Modules\Infaq\Models\UserModel;

class User extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Admin',
            'users' => $this->userModel->findAll()
        ];
        // Menghapus 'user\' dari path pemanggilan
        return view('\Modules\Infaq\Views\v_user_index', $data);
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

        $this->userModel->insert([
            'nama_user' => $this->request->getPost('nama_user'),
            'username'  => $this->request->getPost('username'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'      => $this->request->getPost('role'),
        ]);

        return redirect()->to(base_url('admin/user'))->with('success', 'Akun baru berhasil ditambahkan');
    }

    public function hapus($id)
    {
        // Proteksi agar tidak menghapus akun yang sedang dipakai login
        if (session()->get('id_user') == $id) {
            return redirect()->to(base_url('admin/user'))->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $this->userModel->delete($id);
        return redirect()->to(base_url('admin/user'))->with('success', 'Admin berhasil dihapus');
    }
}
