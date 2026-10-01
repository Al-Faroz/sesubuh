<?php

namespace Modules\Infaq\Models; // Wajib tepat seperti ini

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table            = 'kelas'; // Nama tabel di database
    protected $primaryKey       = 'id_kelas';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_kelas', 'status']; // Kolom yang boleh dimanipulasi

    /**
     * Mengambil daftar kelas yang berstatus aktif untuk form input harian
     * Sesuai dengan DAK-03-UIX (Automasi Input)
     */
    public function getAktif()
    {
        return $this->where('status', 'aktif')
            ->orderBy('nama_kelas', 'ASC')
            ->findAll();
    }
}
