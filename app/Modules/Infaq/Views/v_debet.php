<?= $this->extend('\Modules\Infaq\Views\layout\v_wrapper') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-edit mr-2"></i><?= $title ?></h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/debet/save') ?>" method="post">
            <?= csrf_field() ?>
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label>Pilih Tanggal Transaksi :</label>
                                <input type="date" name="tanggal" class="form-control form-control-lg" value="<?= $tanggal_hari_ini ?>" required>
                                <small class="text-muted">Jika tanggal sama diinput ulang, data lama akan otomatis diperbarui.</small>
                            </div>
                        </div>
                        <div class="col-md-8 text-right">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save mr-2"></i> SIMPAN SEMUA DATA
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-head-fixed text-nowrap">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 50px" class="text-center">#</th>
                                <th>Nama Kelas / Kelompok</th>
                                <th style="width: 350px">Nominal Infaq (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($kelas as $k) : ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><strong><?= $k['nama_kelas'] ?></strong></td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><b>Rp</b></span>
                                            </div>
                                            <input type="number" name="nominal[<?= $k['id_kelas'] ?>]"
                                                class="form-control form-control-lg"
                                                placeholder="0" min="0"
                                                value="<?= $values[$k['id_kelas']] ?? '' ?>">
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle mr-2"></i>
                        Pastikan semua nominal sudah benar sebelum menekan tombol simpan.
                        Untuk melihat histori, silakan buka menu <b>Laporan</b>.
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<script>
    // Script agar saat tanggal diganti, halaman otomatis reload ke tanggal tersebut
    document.querySelector('input[name="tanggal"]').addEventListener('change', function() {
        window.location.href = "<?= base_url('admin/debet') ?>?tanggal=" + this.value;
    });
</script>
<?= $this->endSection() ?>