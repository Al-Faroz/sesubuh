<?php

namespace Modules\Infaq\Models;

use CodeIgniter\Model;

class InfaqModel extends Model
{
    protected $table            = 'sedekah_masuk';
    protected $primaryKey       = 'id_debet';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_kelas', 'nominal', 'tanggal', 'created_by', 'updated_by', 'updated_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function upsertInfaq(array $data)
    {
        $sql = "INSERT INTO sedekah_masuk (id_kelas, nominal, tanggal, created_by) 
                VALUES (?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE 
                nominal = VALUES(nominal), 
                created_by = VALUES(created_by)";

        return $this->db->query($sql, [
            $data['id_kelas'],
            $data['nominal'],
            $data['tanggal'],
            $data['created_by']
        ]);
    }

    // Fungsi READ: Mengambil semua data transaksi masuk
    public function getData()
    {
        return $this->select('sedekah_masuk.*, kelas.nama_kelas, users.username')
            ->join('kelas', 'kelas.id_kelas = sedekah_masuk.id_kelas')
            ->join('users', 'users.id_user = sedekah_masuk.created_by', 'left')
            ->orderBy('tanggal', 'DESC')
            ->findAll();
    }

    public function getWeeklyMatrix($startDate, $endDate)
    {
        return $this->select('sedekah_masuk.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.id_kelas = sedekah_masuk.id_kelas')
            ->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate)
            ->findAll();
    }
}
