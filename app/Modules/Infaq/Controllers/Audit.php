<?php

namespace Modules\Infaq\Controllers;

use App\Controllers\BaseController;
use Modules\Infaq\Models\AuditLogModel;

class Audit extends BaseController
{
    public function index()
    {
        $model = new AuditLogModel();

        return view('Modules\Infaq\Views\v_audit', [
            'title' => 'Riwayat Perubahan',
            'list'  => $model->orderBy('id', 'DESC')->paginate(20),
            'pager' => $model->pager,
        ]);
    }
}
