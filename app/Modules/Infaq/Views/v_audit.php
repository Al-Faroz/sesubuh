<?= $this->extend('\\Modules\\Infaq\\Views\\layout\\v_wrapper') ?>
<?= $this->section('content') ?>
<?php
$label = ['sedekah_masuk' => 'Setoran', 'sedekah_keluar' => 'Pengeluaran', 'kelas' => 'Kelas', 'users' => 'Akun', 'pengaturan' => 'Pengaturan', 'database' => 'Database'];
$badge = ['tambah' => 'badge-ok', 'ubah' => 'badge-info', 'hapus' => 'badge-bad', 'backup' => 'badge-violet'];
$ringkas = static function (?string $json): string {
    $a = $json ? json_decode($json, true) : null;
    if (! is_array($a)) {
        return '-';
    }
    $o = [];
    foreach ($a as $k => $v) {
        $o[] = $k . ': ' . $v;
    }
    return implode(', ', $o);
};
?>
<h1 class="page-title"><?= esc($title) ?></h1>
<p class="page-sub">Catatan siapa mengubah apa dan kapan. Hanya terlihat oleh admin.</p>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-wide">
                <thead>
                    <tr><th>Waktu</th><th>Pengguna</th><th>Aksi</th><th>Objek</th><th>Sebelum</th><th>Sesudah</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $r) : ?>
                        <tr>
                            <td class="nowrap"><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                            <td><?= esc($r['username'] ?? '-') ?></td>
                            <td><span class="badge <?= $badge[$r['aksi']] ?? 'badge-muted' ?>"><?= esc(ucfirst($r['aksi'])) ?></span></td>
                            <td><?= esc($label[$r['tabel']] ?? $r['tabel']) ?><?= $r['id_data'] ? ' #' . (int) $r['id_data'] : '' ?></td>
                            <td class="text-muted"><?= esc($ringkas($r['data_lama'])) ?></td>
                            <td><?= esc($ringkas($r['data_baru'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (! $list) : ?><tr><td colspan="6" class="text-center text-muted">Belum ada riwayat. Pastikan migrasi database fase 6 sudah diimpor.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?= view('Modules\Infaq\Views\layout\v_pager', ['pager' => $pager]) ?>
    </div>
</div>
<?= $this->endSection() ?>
