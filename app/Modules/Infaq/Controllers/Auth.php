<?php

namespace Modules\Infaq\Controllers;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('admin'); // Langsung merujuk ke grup rute admin
        }
        // Tambahkan backslash di awal agar namespace absolut terbaca
        return view('\Modules\Infaq\Views\v_auth');
    }

    // app/Modules/Infaq/Controllers/Auth.php

    public function loginAction()
    {
        $db = \Config\Database::connect();
        $username = trim($this->request->getPost('username'));
        $password = $this->request->getPost('password');

        $user = $db->table('users')->where('username', $username)->get()->getRowArray();

        if ($user && password_verify($password, $user['password'])) {
            // Set session tanpa mempedulikan role
            session()->set([
                'id_user'   => $user['id_user'],
                'username'  => $user['username'],
                'logged_in' => true
            ]);
            return redirect()->to(base_url('admin'));
        }

        return redirect()->back()->with('error', 'Login Gagal! Periksa kembali akun Anda.');
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'Berhasil Logout.');
    }
}
