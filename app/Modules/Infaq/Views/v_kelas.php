<?= $this->extend('\\Modules\\Infaq\\Views\\layout\\v_wrapper') ?>
<?= $this->section('content') ?>
<h1 class="page-title"><?= esc($title) ?></h1>
<p class="page-sub">Kelas tidak dihapus agar riwayat setoran tetap utuh; cukup dinonaktifkan.</p>

<?php $aktif = array_filter($kelas, static fn($k) => $k['status'] === 'aktif'); ?>
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Daftar kelas</h2>
        <div class="toolbar-actions">
            <span class="badge badge-ok"><?= count($aktif) ?> kelas aktif</span>
            <span class="badge badge-info"><?= array_sum(array_column($aktif, 'jml_anak')) ?> siswa</span>
            <button type="button" class="btn btn-primary btn-sm" data-dialog="dlg-tambah"><i class="fas fa-plus"></i> Tambah kelas</button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive stack-wrap">
            <table class="table table-stack">
                <thead>
                    <tr>
                        <th>Kelas</th>
                        <th class="text-right">Jumlah siswa</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kelas as $k) : ?>
                        <tr>
                            <td class="fw-b" data-label="Kelas"><?= esc($k['nama_kelas']) ?></td>
                            <td class="text-right" data-label="Siswa"><?= (int) $k['jml_anak'] ?></td>
                            <td class="text-center" data-label="Status"><span class="badge <?= $k['status'] === 'aktif' ? 'badge-ok' : 'badge-muted' ?>"><?= $k['status'] === 'aktif' ? 'Aktif' : 'Nonaktif' ?></span></td>
                            <td class="text-center" data-label="Aksi">
                                <div class="row-actions">
                                    <button type="button" class="btn btn-sm" aria-label="Edit kelas <?= esc($k['nama_kelas'], 'attr') ?>"
                                        data-dialog="dlg-edit"
                                        data-action="<?= base_url('admin/kelas/update/' . (int) $k['id_kelas']) ?>"
                                        data-fill="<?= esc(json_encode(['nama_kelas' => $k['nama_kelas'], 'jml_anak' => (int) $k['jml_anak']]), 'attr') ?>"><i class="fas fa-pen"></i></button>
                                    <form action="<?= base_url('admin/kelas/status/' . (int) $k['id_kelas']) ?>" method="post" data-confirm="<?= $k['status'] === 'aktif' ? 'Nonaktifkan kelas ini? Kelas tidak akan muncul di input harian dan laporan.' : 'Aktifkan kembali kelas ini?' ?>">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm"><?= $k['status'] === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<dialog id="dlg-tambah" class="neo-dialog" aria-labelledby="dlg-tambah-judul">
    <form action="<?= base_url('admin/kelas/simpan') ?>" method="post">
        <?= csrf_field() ?>
        <h3 id="dlg-tambah-judul">Tambah kelas</h3>
        <div class="form-group"><label for="t-nama">Nama kelas</label><input type="text" id="t-nama" name="nama_kelas" class="form-control" maxlength="20" required placeholder="Contoh: 1A"></div>
        <div class="form-group"><label for="t-jml">Jumlah siswa</label><input type="number" id="t-jml" name="jml_anak" class="form-control" min="0" max="100" value="0" required></div>
        <div class="actions"><button type="button" class="btn" data-close-dialog>Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</dialog>

<dialog id="dlg-edit" class="neo-dialog" aria-labelledby="dlg-edit-judul">
    <form action="" method="post">
        <?= csrf_field() ?>
        <h3 id="dlg-edit-judul">Ubah kelas</h3>
        <div class="form-group"><label for="e-nama">Nama kelas</label><input type="text" id="e-nama" name="nama_kelas" class="form-control" maxlength="20" required></div>
        <div class="form-group"><label for="e-jml">Jumlah siswa</label><input type="number" id="e-jml" name="jml_anak" class="form-control" min="0" max="100" required></div>
        <div class="actions"><button type="button" class="btn" data-close-dialog>Batal</button><button type="submit" class="btn btn-primary">Simpan perubahan</button></div>
    </form>
</dialog>
<?= $this->endSection() ?>
