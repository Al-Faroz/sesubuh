<?php

namespace Modules\Infaq\Controllers;

use App\Controllers\BaseController;
use Modules\Infaq\Services\AuditService;

class Backup extends BaseController
{
    public function index()
    {
        return view('Modules\Infaq\Views\v_backup', ['title' => 'Backup Database']);
    }

    public function unduh()
    {
        $db     = \Config\Database::connect();
        $tabel  = ['kelas', 'pengaturan', 'users', 'sedekah_masuk', 'sedekah_keluar', 'audit_log'];
        $out    = '-- Backup Sedekah Subuh ' . date('Y-m-d H:i:s') . "\nSET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tabel as $t) {
            if (! $db->tableExists($t)) {
                continue;
            }
            $create = $db->query('SHOW CREATE TABLE `' . $t . '`')->getRowArray();
            $out   .= "DROP TABLE IF EXISTS `{$t}`;\n" . array_values($create)[1] . ";\n\n";

            $rows = $db->table($t)->get()->getResultArray();
            foreach (array_chunk($rows, 100) as $chunk) {
                $cols = '`' . implode('`,`', array_keys($chunk[0])) . '`';
                $vals = [];
                foreach ($chunk as $r) {
                    $vals[] = '(' . implode(',', array_map(static fn($v) => $db->escape($v), array_values($r))) . ')';
                }
                $out .= "INSERT INTO `{$t}` ({$cols}) VALUES\n" . implode(",\n", $vals) . ";\n";
            }
            $out .= "\n";
        }
        $out .= "SET FOREIGN_KEY_CHECKS=1;\n";

        (new AuditService())->log('backup', 'database');

        return $this->response->download('backup_sedekah_subuh_' . date('Ymd_His') . '.sql', $out);
    }
}
