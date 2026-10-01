<?= $this->extend('\\Modules\\Infaq\\Views\\layout\\v_wrapper') ?>
<?= $this->section('content') ?>
<h1 class="page-title"><?= esc($title) ?></h1>
<p class="page-sub">Nama pejabat dan tanggal ini dipakai pada tanda tangan laporan.</p>

<form action="<?= site_url('admin/laporan/pengaturan') ?>" method="post" class="card" style="max-width:640px">
    <?= csrf_field() ?>
    <div class="card-body">
        <div class="form-group">
            <label for="nm_kakomite">Ketua Komite</label>
            <input type="text" id="nm_kakomite" name="nm_kakomite" class="form-control" value="<?= esc($config['nm_kakomite'] ?? '', 'attr') ?>" required>
        </div>
        <div class="form-group">
            <label for="kpl_sek">Kepala Madrasah</label>
            <input type="text" id="kpl_sek" name="kpl_sek" class="form-control" value="<?= esc($config['kpl_sek'] ?? '', 'attr') ?>" required>
        </div>
        <div class="form-group">
            <label for="nm_bdrkomite">Bendahara Komite</label>
            <input type="text" id="nm_bdrkomite" name="nm_bdrkomite" class="form-control" value="<?= esc($config['nm_bdrkomite'] ?? '', 'attr') ?>" required>
        </div>
        <div class="form-group mb-0">
            <label for="tgl_lap">Tanggal laporan</label>
            <input type="date" id="tgl_lap" name="tgl_lap" class="form-control" value="<?= esc($config['tgl_lap'] ?? '', 'attr') ?>" required>
        </div>
    </div>
    <div class="card-footer text-right"><button type="submit" class="btn btn-primary">Simpan perubahan</button></div>
</form>
<?= $this->endSection() ?>
