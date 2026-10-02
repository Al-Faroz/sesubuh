<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->setDefaultNamespace('Modules\Infaq\Controllers');

$routes->get('/', 'Home::index');
$routes->get('login', 'Auth::index');
$routes->post('login/auth', 'Auth::loginAction');
$routes->post('logout', 'Auth::logout');

$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Dashboard::index');

    // --- PERBAIKAN MANAJEMEN ADMIN (USER) ---
    // Pastikan rute simpan ada di sini agar tidak 404
    // Manajemen akun: hanya role admin
    $routes->group('', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('manajemen-admin', 'User::index');
        $routes->get('user', 'User::index');
        $routes->post('user/simpan', 'User::simpan');
        $routes->post('user/hapus/(:num)', 'User::hapus/$1');

        // Pengaturan pejabat, data kelas, backup, riwayat: admin saja
        $routes->get('laporan/pengaturan', 'Laporan::pengaturan');
        $routes->post('laporan/pengaturan', 'Laporan::pengaturan');
        $routes->get('kelas', 'Kelas::index');
        $routes->post('kelas/simpan', 'Kelas::simpan');
        $routes->post('kelas/update/(:num)', 'Kelas::update/$1');
        $routes->post('kelas/status/(:num)', 'Kelas::status/$1');
        $routes->get('backup', 'Backup::index');
        $routes->post('backup/unduh', 'Backup::unduh');
        $routes->get('audit', 'Audit::index');
    });

    // --- MODUL LAPORAN & PENGATURAN ---
    $routes->group('laporan', function ($routes) {
        $routes->get('matrik', 'Laporan::matrik');
        $routes->get('kas', 'Laporan::kas');

        $routes->get('cetak_pdf', 'Laporan::cetak_pdf');
        $routes->get('cetak_matrik_pdf', 'Laporan::cetak_matrik_pdf');
    });

    // Modul Infaq Masuk (Debet)
    $routes->group('debet', function ($routes) {
        $routes->get('/', 'Debet::index');
        $routes->post('save', 'Debet::save');
    });

    // Modul Pengeluaran (Kredit)
    $routes->group('kredit', function ($routes) {
        $routes->get('/', 'Kredit::index');
        $routes->post('save', 'Kredit::save');
        $routes->post('update/(:num)', 'Kredit::update/$1');
        $routes->post('delete/(:num)', 'Kredit::delete/$1');
    });

    // --> TAMBAHKAN INI UNTUK PDF <--
});
