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
        // Batasi percobaan login: maks 5x per menit per IP
        $throttler = service('throttler');
        if ($throttler->check('login_' . md5($this->request->getIPAddress()), 5, MINUTE) === false) {
            return redirect()->back()->with('error', 'Terlalu banyak percobaan. Coba lagi sebentar lagi.');
        }

        $db       = \Config\Database::connect();
        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $user = $db->table('users')->where('username', $username)->get()->getRowArray();

        if ($user && password_verify($password, $user['password'])) {
            session()->regenerate(true); // cegah session fixation
            session()->set([
                'id_user'   => $user['id_user'],
                'username'  => $user['username'],
                'role'      => $user['role'],
                'logged_in' => true,
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
