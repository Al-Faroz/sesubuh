<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    // Pemakaian di route: ['filter' => 'role:admin']
    public function before(RequestInterface $request, $arguments = null)
    {
        $role = session()->get('role');

        if (! $role || ($arguments && ! in_array($role, $arguments, true))) {
            return redirect()->to(base_url('admin'))->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
