<?php

namespace Modules\Infaq\Models;

use CodeIgniter\Model;

class KreditModel extends Model
{
    protected $table            = 'sedekah_keluar';
    protected $primaryKey       = 'id_kredit';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['tanggal', 'nominal', 'keterangan', 'created_by', 'updated_by', 'updated_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Dimatikan agar tidak error #1054
}
