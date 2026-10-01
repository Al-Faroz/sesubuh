<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">SEDEKAH SUBUH</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="<?= base_url('admin') ?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('admin/debet') ?>" class="nav-link">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p>Infaq Masuk (Debet)</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('admin/kredit') ?>" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>Pengeluaran (Kredit)</p>
                    </a>
                </li>
                <li class="nav-header text-uppercase" style="font-size: 0.75rem; opacity: 0.8;">Laporan & Rekapitulasi</li>

                <li class="nav-item">
                    <a href="<?= base_url('admin/laporan/matrik') ?>" class="nav-link <?= (url_is('*admin/laporan/matrik*')) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-table text-primary"></i>
                        <p>
                            Matrik Per Kelas
                            <span class="right badge badge-primary">Mingguan</span>
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('admin/laporan/kas') ?>" class="nav-link <?= (url_is('*admin/laporan/kas*')) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-book text-success"></i>
                        <p>
                            Buku Kas Umum
                            <span class="right badge badge-success">Bulanan</span>
                        </p>
                    </a>
                </li>

                <li class="nav-header text-uppercase" style="font-size: 0.75rem; opacity: 0.8;">Konfigurasi Sistem</li>

                <li class="nav-item">
                    <a href="<?= base_url('admin/laporan/pengaturan') ?>" class="nav-link <?= (url_is('*admin/laporan/pengaturan*')) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-cog text-warning"></i>
                        <p>Setting Pejabat Lap.</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('admin/manajemen-admin') ?>" class="nav-link <?= (url_is('*admin/manajemen-admin*')) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>Manajemen Admin</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>