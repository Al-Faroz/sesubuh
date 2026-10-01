<?= $this->extend('\Modules\Infaq\Views\layout\v_wrapper') ?>
<?= $this->section('content') ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= $title ?></h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>Rp <?= number_format($total_pemasukan, 0, ',', '.') ?></h3>
                        <p>Total Pemasukan</p>
                    </div>
                    <div class="icon"><i class="fas fa-arrow-down"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></h3>
                        <p>Total Pengeluaran</p>
                    </div>
                    <div class="icon"><i class="fas fa-arrow-up"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></h3>
                        <p>Saldo Kas Saat Ini</p>
                    </div>
                    <div class="icon"><i class="fas fa-wallet"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $partisipasi_today ?></h3>
                        <p>Partisipasi Hari Ini</p>
                    </div>
                    <div class="icon"><i class="fas fa-users"></i></div>
                </div>
            </div>
        </div>

        <div class="alert alert-success alert-dismissible">
            <h5><i class="icon fas fa-check"></i> Berhasil Masuk!</h5>
            Selamat Datang, <b><?= esc(session()->get('username') ?? 'admin') ?></b>. Sistem Sedekah Subuh siap dikelola.
        </div>

    </div>
</section>

<?= $this->endSection() ?>