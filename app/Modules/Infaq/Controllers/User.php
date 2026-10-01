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
        $password = $this->request->getPost('password');

        $this->userModel->insert([
            'nama_user' => $this->request->getPost('nama_user'),
            'username'  => $this->request->getPost('username'),
            'password'  => password_hash($password, PASSWORD_DEFAULT), // Keamanan hash
        ]);

        return redirect()->to(base_url('admin/user'))->with('success', 'Admin baru berhasil ditambahkan');
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
