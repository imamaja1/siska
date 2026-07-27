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

            <!-- Jurusan -->
            <li class="<?= ($judul == 'Jurusan') ? 'active' : ''; ?> treeview">
                <a href="#">
                    <i class="fa fa-map-o"></i>
                    <span>Jurusan</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= site_url('admin/jurusan/institusi'); ?>"><i class="fa fa-circle-o"></i> Institusi</a></li>
                    <li><a href="<?= site_url('admin/jurusan/universitas/fakultas'); ?>"><i class="fa fa-circle-o"></i> Fakultas</a></li>
                    <li class="<?= (isset($judul_sub_judul) && $judul_sub_judul == 'Program Studi') ? 'active' : ''; ?>">
                        <a href="#"><i class="fa fa-circle-o"></i> Program Studi
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?= site_url('admin/jurusan/program_studi/jenjang'); ?>"><i class="fa fa-circle-o"></i> Jenjang</a></li>
                            <li><a href="<?= site_url('admin/jurusan/program_studi/kode_jurusan'); ?>"><i class="fa fa-circle-o"></i> Kode Jurusan</a></li>
                            <li><a href="<?= site_url('admin/jurusan/program_studi/nama_jurusan'); ?>"><i class="fa fa-circle-o"></i> Nama Jurusan</a></li>
                            <li><a href="<?= site_url('admin/jurusan/program_studi/kompetensi'); ?>"><i class="fa fa-circle-o"></i> Kompetensi</a></li>
                            <li><a href="<?= site_url('admin/jurusan/program_studi/ketua_jurusan'); ?>"><i class="fa fa-circle-o"></i> Ketua Jurusan</a></li>
                        </ul>
                    </li>
                    <li class="<?= (isset($judul_sub_judul) && $judul_sub_judul == 'Kurikulum') ? 'active' : ''; ?>">
                        <a href="#"><i class="fa fa-circle-o"></i> Kurikulum
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?= site_url('admin/jurusan/kurikulum/matakuliah'); ?>"><i class="fa fa-circle-o"></i> Matakuliah</a></li>
                            <li><a href="<?= site_url('admin/jurusan/kurikulum/nama_kurikulum'); ?>"><i class="fa fa-circle-o"></i> Nama Kurikulum</a></li>
                            <li><a href="<?= site_url('admin/jurusan/kurikulum/data_kurikulum'); ?>"><i class="fa fa-circle-o"></i> Data Kurikulum</a></li>
                            <li><a href="<?= site_url('admin/jurusan/kurikulum/kurikulum_angkatan'); ?>"><i class="fa fa-circle-o"></i> Kurikulum Angkatan</a></li>
                            <li><a href="<?= site_url('admin/jurusan/kurikulum/matakuliah_prasyarat'); ?>"><i class="fa fa-circle-o"></i> Matakuliah Prasyarat</a></li>
                        </ul>
                    </li>
                    <li><a href="<?= site_url('admin/jurusan/dosen') ?>"><i class="fa fa-circle-o"></i> Dosen</a></li>
                    <li><a href="<?= site_url('admin/jurusan/perwalian') ?>"><i class="fa fa-circle-o"></i> Perwalian</a></li>
                    <li><a href="<?= site_url('admin/jurusan/konsultasi_perwalian') ?>"><i class="fa fa-circle-o"></i> Konsultasi Perwalian</a></li>
                    <li><a href="<?= site_url('admin/jurusan/distribusi_matakuliah'); ?>"><i class="fa fa-circle-o"></i> Distribusi Matakuliah</a></li>
                </ul>
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
                </ul>
            </li>

            <!-- Akademik -->
            <li class="<?= ($judul == 'Akademik') ? 'active' : ''; ?> treeview">
                <a href="#">
                    <i class="fa fa-sticky-note"></i>
                    <span>Akademik</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= site_url('admin/akademik/mahasiswa'); ?>"><i class="fa fa-circle-o"></i> Mahasiswa</a></li>
                    <li><a href="<?= site_url('admin/akademik/krs'); ?>"><i class="fa fa-circle-o"></i> KRS</a></li>
                    <li><a href="<?= site_url('admin/akademik/nilai'); ?>"><i class="fa fa-circle-o"></i> Nilai</a></li>
                    <li><a href="<?= site_url('admin/akademik/khs'); ?>"><i class="fa fa-circle-o"></i> KHS</a></li>
                    <li><a href="<?= site_url('admin/akademik/kkp'); ?>"><i class="fa fa-circle-o"></i> KKP</a></li>
                    <li><a href="<?= site_url('admin/akademik/petikan_nilai'); ?>"><i class="fa fa-circle-o"></i> Petikan Nilai</a></li>
                    <li><a href="<?= site_url('admin/akademik/konversi'); ?>"><i class="fa fa-circle-o"></i> Konversi Matakuliah</a></li>
                    <li><a href="<?= site_url('admin/akademik/kompetensi'); ?>"><i class="fa fa-circle-o"></i> Kompetensi Mahasiswa</a></li>
                    <li><a href="<?= site_url('admin/akademik/pembayaran_mahasiswa'); ?>"><i class="fa fa-circle-o"></i> Pembayaran Mahasiswa</a></li>
                    <li><a href="<?= site_url('admin/akademik/status_perkuliahan'); ?>"><i class="fa fa-circle-o"></i> Status Perkuliahan</a></li>
                    <li class="<?= (isset($judul_sub_judul) && $judul_sub_judul == 'KPAT') ? 'active' : ''; ?>">
                        <a href="#"><i class="fa fa-circle-o"></i> KPAT
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?= site_url('admin/akademik/kpat/kelas'); ?>"><i class="fa fa-circle-o"></i> Kelas</a></li>
                            <li><a href="<?= site_url('admin/akademik/kpat/krs'); ?>"><i class="fa fa-circle-o"></i> KRS</a></li>
                            <li><a href="<?= site_url('admin/akademik/kpat/nilai'); ?>"><i class="fa fa-circle-o"></i> Nilai</a></li>
                            <li><a href="<?= site_url('admin/akademik/kpat/khs'); ?>"><i class="fa fa-circle-o"></i> KHS</a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <!-- MBKM -->
            <li class="<?= ($judul == 'mbkm') ? 'active' : ''; ?> treeview">
                <a href="#">
                    <i class="fa fa-globe"></i>
                    <span>MBKM</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= site_url('admin/mbkm/daftar'); ?>"><i class="fa fa-circle-o"></i> Daftar Mahasiswa</a></li>
                </ul>
            </li>

            <!-- Kuisioner -->
            <li class="<?= ($judul == 'Kuisioner') ? 'active' : ''; ?> treeview">
                <a href="#">
                    <i class="fa fa-pie-chart"></i>
                    <span>Kuisioner</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= site_url('admin/kuisioner/kelas'); ?>"><i class="fa fa-circle-o"></i> Kelas</a></li>
                    <li><a href="<?= site_url('admin/kuisioner/mengajar'); ?>"><i class="fa fa-circle-o"></i> Mengajar</a></li>
                    <li><a href="<?= site_url('admin/kuisioner/kuisioner'); ?>"><i class="fa fa-circle-o"></i> Kuisioner PBM</a></li>
                    <li><a href="<?= site_url('admin/kuisioner/kuisioner/kuisioner_layanan'); ?>"><i class="fa fa-circle-o"></i> Kuisioner Pelayanan</a></li>
                    <li><a href="<?= site_url('admin/kuisioner/kuisioner/download_pmb'); ?>"><i class="fa fa-circle-o"></i> Download PBM</a></li>
                </ul>
            </li>

            <!-- Audit Nilai -->
            <li class="<?= ($judul == 'Audit Nilai') ? 'active' : ''; ?> treeview">
                <a href="#">
                    <i class="fa fa-check-square-o"></i>
                    <span>Audit Nilai</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= site_url('admin/audit'); ?>"><i class="fa fa-circle-o"></i> Nilai Dosen dan KHS</a></li>
                </ul>
            </li>

            <!-- Laporan -->
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-print"></i>
                    <span>Laporan</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= site_url('admin/laporan/rekap_ipk'); ?>"><i class="fa fa-circle-o"></i> IPK Per Prodi Per Angkatan</a></li>
                    <li><a href="<?= site_url('admin/laporan/aktif_perkuliahan'); ?>"><i class="fa fa-circle-o"></i> Mahasiswa Aktif Perkuliahan</a></li>
                </ul>
            </li>

            <!-- User -->
            <li class="<?= ($judul == 'User') ? 'active' : ''; ?>">
                <a href="<?= site_url('user'); ?>">
                    <i class="glyphicon glyphicon-user"></i> <span>User</span>
                </a>
            </li>

            <!-- Pengguna -->
            <li class="<?= ($judul == 'Impersonasi' || $judul == 'Pengguna') ? 'active' : ''; ?> treeview">
                <a href="#">
                    <i class="glyphicon glyphicon-user"></i> <span>Pengguna</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= site_url('admin/pengguna/pengguna'); ?>"><i class="fa fa-circle-o"></i> Pengguna</a></li>
                    <li><a href="<?= site_url('admin/impersonasi_dosen'); ?>"><i class="fa fa-circle-o"></i> Dosen</a></li>
                    <li><a href="<?= site_url('admin/impersonasi_mahasiswa'); ?>"><i class="fa fa-circle-o"></i> Mahasiswa</a></li>
                </ul>
            </li>

            <?php if ($this->session->userdata('is_impersonating')): ?>
            <li>
                <a href="<?= site_url('admin/impersonasi_dosen/kembali'); ?>" style="background-color: #f39c12; color: white;" onclick="return confirm('Kembali ke akun admin?')">
                    <i class="fa fa-undo"></i> <span>Kembali ke Admin</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- RBAC -->
            <li>
                <a href="<?= site_url('admin/rbac'); ?>">
                    <i class="fa fa-lock"></i> <span>RBAC</span>
                </a>
            </li>

            <li class="header">DUKUNGAN</li>
            <li><a href="<?= site_url('maintance'); ?>"><i class="fa fa-book text-yellow"></i> <span>Panduan</span></a></li>
            <li><a href="<?= site_url('maintance'); ?>"><i class="fa fa-envelope text-red"></i> <span>Laporkan Masalah</span></a></li>

        </ul>
    </section>
</aside>
