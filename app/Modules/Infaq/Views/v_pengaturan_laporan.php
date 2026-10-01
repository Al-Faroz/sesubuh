<?= $this->extend('\Modules\Infaq\Views\layout\v_wrapper') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-8">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Update Pejabat Laporan</h3>
            </div>

            <form action="<?= site_url('admin/laporan/pengaturan') ?>" method="post">
                <?= csrf_field() ?>

                <div class="card-body">
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Ketua Komite</label>
                        <input type="text" name="nm_kakomite" class="form-control" value="<?= $config['nm_kakomite'] ?? '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Kepala Madrasah</label>
                        <input type="text" name="kpl_sek" class="form-control" value="<?= $config['kpl_sek'] ?? '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Bendahara Komite</label>
                        <input type="text" name="nm_bdrkomite" class="form-control" value="<?= $config['nm_bdrkomite'] ?? '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Laporan</label>
                        <input type="date" name="tgl_lap" class="form-control" value="<?= $config['tgl_lap'] ?? '' ?>" required>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">SIMPAN PERUBAHAN</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>