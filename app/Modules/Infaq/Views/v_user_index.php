<?= $this->extend('\\Modules\\Infaq\\Views\\layout\\v_wrapper') ?>
<?= $this->section('content') ?>
<h1 class="page-title"><?= esc($title) ?></h1>
<p class="page-sub">Admin dapat mengelola akun; operator hanya input data dan laporan.</p>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Daftar akun</h2>
        <button type="button" class="btn btn-primary btn-sm" data-dialog="dlg-tambah"><i class="fas fa-plus"></i> Tambah akun</button>
    </div>
    <div class="card-body">
        <div class="table-responsive stack-wrap">
            <table class="table table-stack">
                <thead>
                    <tr>
                        <th class="text-center" style="width:56px">No</th>
                        <th>Nama lengkap</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th class="text-center" style="width:90px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($users as $u) : ?>
                        <tr>
                            <td class="text-center" data-label="No"><?= $no++ ?></td>
                            <td data-label="Nama"><?= esc($u['nama_user']) ?></td>
                            <td class="fw-b" data-label="Username"><?= esc($u['username']) ?></td>
                            <td data-label="Role"><span class="badge <?= $u['role'] === 'admin' ? 'badge-accent' : 'badge-info' ?>"><?= esc(ucfirst($u['role'])) ?></span></td>
                            <td class="text-center" data-label="Aksi">
                                <form action="<?= base_url('admin/user/hapus/' . (int) $u['id_user']) ?>" method="post" data-confirm="Hapus akun ini?">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger" aria-label="Hapus akun"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<dialog id="dlg-tambah" class="neo-dialog" aria-labelledby="dlg-judul">
    <form action="<?= base_url('admin/user/simpan') ?>" method="post">
        <?= csrf_field() ?>
        <h3 id="dlg-judul">Tambah akun baru</h3>
        <div class="form-group">
            <label for="u-nama">Nama lengkap</label>
            <input type="text" id="u-nama" name="nama_user" class="form-control" required placeholder="Contoh: Ibu Tiaz">
        </div>
        <div class="form-group">
            <label for="u-user">Username</label>
            <input type="text" id="u-user" name="username" class="form-control" required autocomplete="off">
        </div>
        <div class="form-group">
            <label for="u-role">Role</label>
            <select id="u-role" name="role" class="form-control" required>
                <option value="operator">Operator (input data &amp; laporan)</option>
                <option value="admin">Admin (termasuk kelola akun)</option>
            </select>
        </div>
        <div class="form-group">
            <label for="u-pass">Password</label>
            <input type="password" id="u-pass" name="password" class="form-control" required minlength="5" autocomplete="new-password">
        </div>
        <div class="actions">
            <button type="button" class="btn" data-close-dialog>Batal</button>
            <button type="submit" class="btn btn-primary">Simpan akun</button>
        </div>
    </form>
</dialog>
<?= $this->endSection() ?>
