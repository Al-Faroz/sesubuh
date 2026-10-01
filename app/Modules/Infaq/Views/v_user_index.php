<?= $this->extend('\Modules\Infaq\Views\layout\v_wrapper') ?>
<?= $this->section('content') ?>

<div class="card card-outline card-primary shadow">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">Daftar Admin Sistem</h3>
        <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalTambah">
            <i class="fas fa-plus"></i> Tambah Admin
        </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-bordered mb-0">
            <thead class="bg-light text-center">
                <tr>
                    <th style="width: 50px">No</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th style="width: 100px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($users as $u) : ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= esc($u['nama_user']) ?></td>
                        <td class="text-center font-weight-bold"><?= esc($u['username']) ?> <small class="text-muted">(<?= esc($u['role']) ?>)</small></td>
                        <td class="text-center">
                            <form action="<?= base_url('admin/user/hapus/' . $u['id_user']) ?>" method="post" class="d-inline"
                                onsubmit="return confirm('Hapus akun ini?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Admin Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="<?= base_url('admin/user/simpan') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_user" class="form-control" required placeholder="Contoh: Ibu Tiaz">
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="operator">Operator (input data & laporan)</option>
                            <option value="admin">Admin (termasuk kelola akun)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required minlength="5">
                    </div>
                </div>
                <div class="modal-footer text-right">
                    <button type="submit" class="btn btn-primary px-4">Simpan Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>