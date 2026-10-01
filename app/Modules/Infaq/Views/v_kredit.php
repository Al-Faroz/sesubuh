<?= $this->extend('\\Modules\\Infaq\\Views\\layout\\v_wrapper') ?>

<?= $this->section('content') ?>
<h1 class="page-title"><?= esc($title) ?></h1>
<p class="page-sub">Catat dan kelola pengeluaran dana sedekah.</p>

<div class="split">
    <form action="<?= base_url('admin/kredit/save') ?>" method="post" class="card">
        <?= csrf_field() ?>
        <div class="card-header"><h2 class="card-title">Tambah pengeluaran</h2></div>
        <div class="card-body">
            <div class="form-group">
                <label for="k-tgl">Tanggal</label>
                <input type="date" id="k-tgl" name="tanggal" class="form-control" value="<?= esc($tanggal_hari_ini, 'attr') ?>" required>
            </div>
            <div class="form-group">
                <label for="k-nom">Nominal (Rp)</label>
                <div class="money"><b>Rp</b><input type="number" id="k-nom" name="nominal" inputmode="numeric" min="1" placeholder="0" required></div>
            </div>
            <div class="form-group mb-0">
                <label for="k-ket">Keterangan</label>
                <textarea id="k-ket" name="keterangan" class="form-control" rows="3" maxlength="500" required></textarea>
            </div>
        </div>
        <div class="card-footer"><button type="submit" class="btn btn-primary btn-block">Simpan pengeluaran</button></div>
    </form>

    <div class="card">
        <div class="card-header"><h2 class="card-title">Histori pengeluaran</h2><span class="hint">10 transaksi terakhir</span></div>
        <div class="card-body">
            <div class="table-responsive stack-wrap">
                <table class="table table-stack">
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
                                <td class="nowrap" data-label="Tanggal"><?= date('d-m-Y', strtotime($l['tanggal'])) ?></td>
                                <td data-label="Keterangan"><?= esc($l['keterangan']) ?></td>
                                <td class="text-right num-out nowrap" data-label="Nominal">Rp <?= number_format($l['nominal'], 0, ',', '.') ?></td>
                                <td class="text-center" data-label="Aksi">
                                    <form action="<?= base_url('admin/kredit/delete/' . (int) $l['id_kredit']) ?>" method="post" data-confirm="Hapus data pengeluaran ini?">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-danger" aria-label="Hapus"><i class="fas fa-trash"></i></button>
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
<?= $this->endSection() ?>
