<?php

namespace Modules\Infaq\Services;

/**
 * Mencatat perubahan data ke tabel audit_log.
 * Kegagalan mencatat tidak menggagalkan aksi utama, tapi dilaporkan ke log aplikasi.
 */
class AuditService
{
    public function log(string $aksi, string $tabel, ?int $idData = null, ?array $lama = null, ?array $baru = null): void
    {
        try {
            \Config\Database::connect()->table('audit_log')->insert([
                'id_user'   => session()->get('id_user'),
                'username'  => session()->get('username'),
                'aksi'      => $aksi,
                'tabel'     => $tabel,
                'id_data'   => $idData,
                'data_lama' => $lama === null ? null : json_encode($lama, JSON_UNESCAPED_UNICODE),
                'data_baru' => $baru === null ? null : json_encode($baru, JSON_UNESCAPED_UNICODE),
                'ip'        => service('request')->getIPAddress(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Audit gagal dicatat: ' . $e->getMessage());
        }
    }

    public function terbaru(int $jumlah = 8): array
    {
        try {
            return \Config\Database::connect()->table('audit_log')->orderBy('id', 'DESC')->limit($jumlah)->get()->getResultArray();
        } catch (\Throwable $e) {
            return [];
        }
    }
}
