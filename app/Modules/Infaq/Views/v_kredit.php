<?= $this->extend('\Modules\Infaq\Views\layout\v_wrapper') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0"><?= $title ?></h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-danger">
                    <div class="card-header">
                        <h3 class="card-title">Tambah Pengeluaran</h3>
                    </div>
                    <form action="<?= base_url('admin/kredit/save') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" value="<?= $tanggal_hari_ini ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Nominal (Rp)</label>
                                <input type="number" name="nominal" class="form-control" placeholder="0" required>
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-danger btn-block">Simpan Pengeluaran</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Histori Pengeluaran</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th class="text-right">Nominal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($list as $l) : ?>
                                    <tr>
                                        <td><?= date('d-m-Y', strtotime($l['tanggal'])) ?></td>
                                        <td><?= esc($l['keterangan']) ?></td>
                                        <td class="text-right text-danger">Rp <?= number_format($l['nominal'], 0, ',', '.') ?></td>
                                        <td class="text-center">
                                            <form action="<?= base_url('admin/kredit/delete/' . $l['id_kredit']) ?>" method="post" class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>