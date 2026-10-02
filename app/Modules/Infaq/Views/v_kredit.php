<?= $this->extend('\\Modules\\Infaq\\Views\\layout\\v_wrapper') ?>

<?= $this->section('content') ?>
<h1 class="page-title"><?= esc($title) ?></h1>
<p class="page-sub">Catat, ubah, dan hapus pengeluaran dana sedekah. Setiap perubahan tercatat di riwayat.</p>

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
        <div class="card-header"><h2 class="card-title">Histori pengeluaran</h2></div>
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
                                <td data-label="Keterangan"><?= esc($l['keterangan']) ?><?php if (! empty($l['updated_at'])) : ?> <span class="badge badge-info">Diubah</span><?php endif; ?></td>
                                <td class="text-right num-out nowrap" data-label="Nominal">Rp <?= number_format($l['nominal'], 0, ',', '.') ?></td>
                                <td class="text-center" data-label="Aksi">
                                    <div class="row-actions">
                                        <button type="button" class="btn btn-sm" aria-label="Edit"
                                            data-dialog="dlg-edit"
                                            data-action="<?= base_url('admin/kredit/update/' . (int) $l['id_kredit']) ?>"
                                            data-fill="<?= esc(json_encode(['tanggal' => $l['tanggal'], 'nominal' => (int) round((float) $l['nominal']), 'keterangan' => $l['keterangan']]), 'attr') ?>"><i class="fas fa-pen"></i></button>
                                        <form action="<?= base_url('admin/kredit/delete/' . (int) $l['id_kredit']) ?>" method="post" data-confirm="Hapus pengeluaran ini? Data lama tetap tercatat di riwayat.">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-danger" aria-label="Hapus"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (! $list) : ?><tr><td colspan="4" class="text-center text-muted">Belum ada pengeluaran.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?= view('Modules\Infaq\Views\layout\v_pager', ['pager' => $pager]) ?>
        </div>
    </div>
</div>

<dialog id="dlg-edit" class="neo-dialog" aria-labelledby="dlg-edit-judul">
    <form action="" method="post">
        <?= csrf_field() ?>
        <h3 id="dlg-edit-judul">Ubah pengeluaran</h3>
        <div class="form-group"><label for="e-tgl">Tanggal</label><input type="date" id="e-tgl" name="tanggal" class="form-control" required></div>
        <div class="form-group"><label for="e-nom">Nominal (Rp)</label><div class="money"><b>Rp</b><input type="number" id="e-nom" name="nominal" inputmode="numeric" min="1" required></div></div>
        <div class="form-group"><label for="e-ket">Keterangan</label><textarea id="e-ket" name="keterangan" class="form-control" rows="3" maxlength="500" required></textarea></div>
        <div class="actions">
            <button type="button" class="btn" data-close-dialog>Batal</button>
            <button type="submit" class="btn btn-primary">Simpan perubahan</button>
        </div>
    </form>
</dialog>
<?= $this->endSection() ?>
