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

            <!-- Keuangan -->
            <li class="<?= ($judul == 'Keuangan') ? 'active' : ''; ?> treeview">
                <a href="#">
                    <i class="fa fa-money"></i>
                    <span>Keuangan</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= site_url('admin/keuangan/status_perkuliahan'); ?>"><i class="fa fa-circle-o"></i> Status Perkuliahan</a></li>
                    <li><a href="<?= site_url('admin/keuangan/mahasiswa_aktif'); ?>"><i class="fa fa-circle-o"></i> Mahasiswa Aktif</a></li>
                    <li><a href="<?= site_url('admin/keuangan/block'); ?>"><i class="fa fa-circle-o"></i> Block Mahasiswa</a></li>
                    <li><a href="<?= site_url('admin/keuangan/pembayaran'); ?>"><i class="fa fa-circle-o"></i> Pembayaran</a></li>
                    <li><a href="<?= site_url('admin/akademik/mahasiswa'); ?>"><i class="fa fa-circle-o"></i> Mahasiswa</a></li>
                    <li><a href="<?= site_url('admin/akademik/krs'); ?>"><i class="fa fa-circle-o"></i> KRS</a></li>
                    <li><a href="<?= site_url('admin/akademik/khs'); ?>"><i class="fa fa-circle-o"></i> KHS</a></li>
                </ul>
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
