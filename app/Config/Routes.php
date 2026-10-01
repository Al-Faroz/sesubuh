<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->setDefaultNamespace('Modules\Infaq\Controllers');

$routes->get('/', 'Home::index');
$routes->get('login', 'Auth::index');
$routes->post('login/auth', 'Auth::loginAction');
$routes->get('logout', 'Auth::logout');

$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Dashboard::index');

    // --- PERBAIKAN MANAJEMEN ADMIN (USER) ---
    // Pastikan rute simpan ada di sini agar tidak 404
    $routes->get('manajemen-admin', 'User::index');
    $routes->get('user', 'User::index');
    $routes->post('user/simpan', 'User::simpan'); // Ini untuk memproses tambah admin
    $routes->get('user/hapus/(:num)', 'User::hapus/$1');

    // --- MODUL LAPORAN & PENGATURAN ---
    $routes->group('laporan', function ($routes) {
        $routes->get('matrik', 'Laporan::matrik');
        $routes->get('kas', 'Laporan::kas');

        // Rute Pengaturan Pejabat (GET untuk tampil, POST untuk update)
        $routes->get('pengaturan', 'Laporan::pengaturan');
        $routes->post('pengaturan', 'Laporan::pengaturan');
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
        $routes->get('delete/(:num)', 'Kredit::delete/$1');
    });

    // --> TAMBAHKAN INI UNTUK PDF <--
    $routes->get('laporan/cetak_pdf', '\Modules\Infaq\Controllers\Laporan::cetak_pdf');

    $routes->get('laporan/pengaturan', '\Modules\Infaq\Controllers\Laporan::pengaturan');
    $routes->post('laporan/pengaturan', '\Modules\Infaq\Controllers\Laporan::pengaturan');
    $routes->get('laporan/cetak_matrik_pdf', '\Modules\Infaq\Controllers\Laporan::cetak_matrik_pdf');
});
