<?php
$path  = trim(uri_string(), '/');
$cls   = static fn(bool $on): string => $on ? ' class="active" aria-current="page"' : '';
$admin = session()->get('role') === 'admin';
?>
<aside class="neo-side" id="neo-side">
    <a href="<?= base_url('admin') ?>" class="neo-brand">
        <i class="fas fa-hand-holding-heart"></i>
        <span>SEDEKAH SUBUH<small>MIN 6 Jember</small></span>
    </a>
    <nav aria-label="Menu utama">
        <ul class="neo-nav">
            <li><a href="<?= base_url('admin') ?>" <?= $cls($path === 'admin') ?>><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="<?= base_url('admin/debet') ?>" <?= $cls(url_is('*admin/debet*')) ?>><i class="fas fa-hand-holding-usd"></i> Infaq Masuk</a></li>
            <li><a href="<?= base_url('admin/kredit') ?>" <?= $cls(url_is('*admin/kredit*')) ?>><i class="fas fa-file-invoice-dollar"></i> Pengeluaran</a></li>
            <li class="nav-label">Laporan</li>
            <li><a href="<?= base_url('admin/laporan/matrik') ?>" <?= $cls(url_is('*admin/laporan/matrik*')) ?>><i class="fas fa-table"></i> Matrik Per Kelas</a></li>
            <li><a href="<?= base_url('admin/laporan/kas') ?>" <?= $cls(url_is('*admin/laporan/kas*')) ?>><i class="fas fa-book"></i> Buku Kas Umum</a></li>
            <li class="nav-label">Sistem</li>
            <li><a href="<?= base_url('admin/laporan/pengaturan') ?>" <?= $cls(url_is('*admin/laporan/pengaturan*')) ?>><i class="fas fa-user-cog"></i> Setting Pejabat</a></li>
            <?php if ($admin) : ?>
                <li><a href="<?= base_url('admin/manajemen-admin') ?>" <?= $cls(url_is('*admin/manajemen-admin*') || url_is('*admin/user*')) ?>><i class="fas fa-users-cog"></i> Kelola Akun</a></li>
            <?php endif; ?>
            <li><a href="<?= base_url('logout') ?>" class="out"><i class="fas fa-sign-out-alt"></i> Keluar</a></li>
        </ul>
    </nav>
</aside>
<div class="neo-scrim"></div>
