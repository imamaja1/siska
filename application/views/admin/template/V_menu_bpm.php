<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">MENU PILIHAN</li>

            <!-- Dashboard -->
            <li class="<?= ($judul == 'Home') ? 'active' : ''; ?>">
                <a href="<?= site_url('home/admin'); ?>">
                    <i class="glyphicon glyphicon-home"></i> <span>Dashboard</span>
                </a>
            </li>

            <!-- KRS -->
            <li>
                <a href="<?= site_url('admin/akademik/krs'); ?>">
                    <i class="fa fa-file-text-o"></i> <span>KRS</span>
                </a>
            </li>

            <li class="header">DUKUNGAN</li>
            <li><a href="<?= site_url('maintance'); ?>"><i class="fa fa-book text-yellow"></i> <span>Panduan</span></a></li>
            <li><a href="<?= site_url('maintance'); ?>"><i class="fa fa-envelope text-red"></i> <span>Laporkan Masalah</span></a></li>

            <li class="header">AKUN</li>
            <li><a href="<?= site_url('admin/pengguna/ganti_sandi'); ?>"><i class="fa fa-key"></i> <span>Ganti Sandi</span></a></li>
            <li><a href="#" onclick="konfirmasiKeluar('<?= site_url('admin/login_admin/logout') ?>')"><i class="fa fa-sign-out"></i> <span>Logout</span></a></li>
        </ul>
    </section>
</aside>
