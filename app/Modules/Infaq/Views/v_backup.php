<?= $this->extend('\\Modules\\Infaq\\Views\\layout\\v_wrapper') ?>
<?= $this->section('content') ?>
<h1 class="page-title"><?= esc($title) ?></h1>
<p class="page-sub">Unduh salinan seluruh data aplikasi dalam satu file SQL.</p>

<div class="card" style="max-width:640px">
    <div class="card-body">
        <p>Isi backup: kelas, pengaturan pejabat, akun, setoran masuk, pengeluaran, dan riwayat perubahan. Untuk memulihkan, impor file ini lewat phpMyAdmin.</p>
        <div class="alert alert-warning mb-0">File ini memuat hash password akun dan data keuangan. Simpan di tempat aman dan jangan diunggah ke repo atau dibagikan.</div>
    </div>
    <div class="card-footer">
        <form action="<?= base_url('admin/backup/unduh') ?>" method="post">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-primary"><i class="fas fa-download"></i> Unduh backup (.sql)</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
