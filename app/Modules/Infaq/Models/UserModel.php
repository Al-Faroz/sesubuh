<?php

namespace Modules\Infaq\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    // Field yang diizinkan untuk diisi sesuai struktur database terbaru
    protected $allowedFields    = ['nama_user', 'username', 'password', 'role'];

    // Fitur timestamp otomatis jika Anda ingin mencatat waktu pembuatan
    protected $useTimestamps = false;
}
