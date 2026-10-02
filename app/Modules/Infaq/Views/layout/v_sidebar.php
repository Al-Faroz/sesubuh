<?php
$path  = trim(uri_string(), '/');
$cls   = static fn(bool $on): string => $on ? ' class="active" aria-current="page"' : '';
$admin = session()->get('role') === 'admin';
?>
<aside class="neo-side" id="neo-side">
    <a href="<?= base_url('admin') ?>" class="neo-brand">
        <img class="logo-plate " src="<?= base_url('public/assets/img/logo.png') ?>" alt="Logo MIN 6 Jember" width="44" height="44">
        <span>SEDEKAH SUBUH<small>MIN 6 Jember</small></span>
    </a>
    <nav aria-label="Menu utama">
        <ul class="neo-nav">
            <li><a href="<?= base_url('admin') ?>" <?= $cls($path === 'admin') ?>><i class="fas fa-tachometer-alt ic-ok"></i> Dashboard</a></li>
            <li><a href="<?= base_url('admin/debet') ?>" <?= $cls(url_is('*admin/debet*')) ?>><i class="fas fa-hand-holding-usd ic-ok"></i> Infaq Masuk</a></li>
            <li><a href="<?= base_url('admin/kredit') ?>" <?= $cls(url_is('*admin/kredit*')) ?>><i class="fas fa-file-invoice-dollar ic-bad"></i> Pengeluaran</a></li>
            <li class="nav-label">Laporan</li>
            <li><a href="<?= base_url('admin/laporan/matrik') ?>" <?= $cls(url_is('*admin/laporan/matrik*')) ?>><i class="fas fa-table ic-info"></i> Matrik Per Kelas</a></li>
            <li><a href="<?= base_url('admin/laporan/kas') ?>" <?= $cls(url_is('*admin/laporan/kas*')) ?>><i class="fas fa-book ic-violet"></i> Buku Kas Umum</a></li>
            <?php if ($admin) : ?>
                <li class="nav-label">Pengelolaan</li>
                <li><a href="<?= base_url('admin/kelas') ?>" <?= $cls(url_is('*admin/kelas*')) ?>><i class="fas fa-school ic-info"></i> Data Kelas</a></li>
                <li><a href="<?= base_url('admin/laporan/pengaturan') ?>" <?= $cls(url_is('*admin/laporan/pengaturan*')) ?>><i class="fas fa-user-cog ic-warn"></i> Setting Pejabat</a></li>
                <li><a href="<?= base_url('admin/manajemen-admin') ?>" <?= $cls(url_is('*admin/manajemen-admin*') || url_is('*admin/user*')) ?>><i class="fas fa-users-cog ic-warn"></i> Kelola Akun</a></li>
                <li><a href="<?= base_url('admin/audit') ?>" <?= $cls(url_is('*admin/audit*')) ?>><i class="fas fa-history ic-violet"></i> Riwayat Perubahan</a></li>
                <li><a href="<?= base_url('admin/backup') ?>" <?= $cls(url_is('*admin/backup*')) ?>><i class="fas fa-database ic-ok"></i> Backup Database</a></li>
            <?php endif; ?>
            <li class="nav-label">Tampilan</li>
            <li><button type="button" class="nav-btn" data-theme-toggle aria-label="Ganti mode tampilan"><i class="fas fa-moon ic-warn"></i> <span>Mode gelap / terang</span></button></li>
            <li>
                <form action="<?= base_url('logout') ?>" method="post" class="nav-form">
                    <?= csrf_field() ?>
                    <button type="submit" class="nav-btn out"><i class="fas fa-sign-out-alt"></i> <span>Keluar</span></button>
                </form>
            </li>
        </ul>
    </nav>
</aside>
<div class="neo-scrim"></div>
