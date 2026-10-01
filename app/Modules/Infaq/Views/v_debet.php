<?= $this->extend('\\Modules\\Infaq\\Views\\layout\\v_wrapper') ?>

<?= $this->section('content') ?>
<h1 class="page-title"><?= esc($title) ?></h1>
<p class="page-sub">Isi nominal setiap kelas, lalu simpan sekaligus.</p>

<form action="<?= base_url('admin/debet/save') ?>" method="post" class="card">
    <?= csrf_field() ?>
    <div class="card-body">
        <div class="toolbar">
            <div class="form-group">
                <label for="tanggal">Tanggal transaksi</label>
                <input type="date" id="tanggal" name="tanggal" class="form-control form-control-lg" value="<?= esc($tanggal_hari_ini, 'attr') ?>" required>
                <div class="hint">Jika tanggal yang sama diinput ulang, data lama diperbarui.</div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> Simpan semua data</button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center" style="width:56px">#</th>
                        <th>Kelas</th>
                        <th style="width:min(340px,50%)">Nominal infaq</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($kelas as $k) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="fw-b"><?= esc($k['nama_kelas']) ?></td>
                            <td>
                                <div class="money">
                                    <b>Rp</b>
                                    <input type="number" name="nominal[<?= (int) $k['id_kelas'] ?>]" inputmode="numeric" placeholder="0" min="0"
                                        aria-label="Nominal kelas <?= esc($k['nama_kelas'], 'attr') ?>"
                                        value="<?= esc($values[$k['id_kelas']] ?? '', 'attr') ?>">
                                </div>
                            </td>
                            <td class="text-center nowrap">
                                <?php if (isset($values[$k['id_kelas']])) : ?><span class="badge badge-ok">Tersimpan</span><?php else : ?><span class="badge badge-muted">Belum diisi</span><?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <div class="alert alert-success mb-0">Pastikan semua nominal sudah benar sebelum menyimpan. Riwayat dapat dilihat di menu Laporan.</div>
    </div>
</form>

<script>
    // Saat tanggal diganti, muat ulang halaman ke tanggal tersebut
    document.getElementById('tanggal').addEventListener('change', function() {
        window.location.href = "<?= base_url('admin/debet') ?>?tanggal=" + encodeURIComponent(this.value);
    });
</script>
<?= $this->endSection() ?>
