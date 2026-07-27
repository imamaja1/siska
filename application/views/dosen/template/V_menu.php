<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header" style="color: white;">MENU DOSEN</li>
            <li class="<?php echo ($judul == 'Home') ? 'active' : ''; ?> treeview">
                <a href="<?php echo site_url('home/dosen'); ?>">
                    <i class="glyphicon glyphicon-home"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="<?= isset($a_bidang_ilmu) ? $a_bidang_ilmu : '' ?>"><a href="<?= site_url('dosen/bidang_ilmu') ?>"><i class="glyphicon glyphicon-question-sign"></i> <span>Bidang Ilmu Dosen</span></a></li>


            <li hidden class="<?= isset($a_absensi_kehadiran) ? $a_absensi_kehadiran : '' ?>"><a href="<?= site_url('dosen/absensi_kehadiran') ?>"><i class="fa fa-check-square-o"></i> <span>Absensi Kehadiran</span></a></li>


            <li class="<?= isset($a_botdosen) ? $a_botdosen : '' ?>"><a href="<?= site_url('dosen/botdosen') ?>"><i class="fa fa-envelope"></i> <span>Notifikasi Telegram</span></a></li>

            <li class="<?= isset($a_kurikulum) ? $a_kurikulum : ''; ?> treeview">
                <a href="<?php echo site_url('dosen/kurikulum'); ?>">
                    <i class="glyphicon glyphicon-th-list"></i> <span>Kurikulum</span>
                </a>
            </li>

            <li class="<?= isset($a_matakuliah_prasyarat) ? $a_matakuliah_prasyarat : ''; ?> treeview">
                <a href="<?php echo site_url('dosen/matakuliah_prasyarat'); ?>">
                    <i class="glyphicon glyphicon-check"></i> <span>Matakuliah Prasyarat</span>
                </a>
            </li>
            <li class="<?= isset($a_konsultasi_perwalian) ? $a_konsultasi_perwalian : ''; ?> treeview">
                <a href="#">
                    <i class="fa fa-money"></i>
                    <span>Konsultasi Perwalian</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo site_url('dosen/konsultasi_perwalian'); ?>"><i class="fa fa-circle-o"></i> Belum Diaktifkan</a></li>
                    <li><a href="<?php echo site_url('dosen/konsultasi_perwalian/konsultasi_perwalian_aktif'); ?>"><i class="fa fa-circle-o"></i> Sudah Diaktifkan</a></li>
          			<li><a href="<?php echo site_url('dosen/konsultasi_perwalian/perkuliahan_perwalian_tidak_aktif'); ?>"><i class="fa fa-circle-o"></i> Perkuliahan Tidak Aktif</a></li>
                </ul>
            </li>


            <li class="<?= isset($a_penilaian) ? $a_penilaian : ''; ?> treeview">
                <a href="#">
                    <i class="glyphicon glyphicon-sort-by-order"></i>
                    <span>Penilaian</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= isset($a_presentasi_penilaian) ? $a_presentasi_penilaian : ''; ?>"><a href="<?php echo site_url('dosen/penilaian/presentasi_penilaian'); ?>"><i class="fa fa-circle-o"></i> Persentase Penilaian</a></li>
                    <!-- <li class="<?= isset($a_penilaian_uts) ? $a_penilaian_uts : ''; ?>"><a href="<?php echo site_url('dosen/penilaian/penilaian_uts'); ?>"><i class="fa fa-circle-o"></i> UTS</a></li>
                    <li><a href="<?php echo site_url('dosen/penilaian/penilaian_harian_uas'); ?>"><i class="fa fa-circle-o"></i> Harian & UAS</a></li> -->
                    <li><a href="<?php echo site_url('dosen/penilaian/penilaian_revisi'); ?>"><i class="fa fa-circle-o"></i> Penilaian Mahasiswa </a></li>
                    <li><a href="<?php echo site_url('dosen/penilaian/penilaian_final'); ?>"><i class="fa fa-circle-o"></i> Penilaian Final</a></li>
                    <li><a href="<?php echo site_url('dosen/penilaian/penilaian_kuisioner'); ?>"><i class="fa fa-circle-o"></i> Hasil Kuisioner Dosen</a></li>
                </ul>
            </li>
          <!-- <li class="<?= isset($a_penilaian_kpat) ? $a_penilaian_kpat : ''; ?> treeview">
                <a href="#">
                    <i class="glyphicon glyphicon-sort-by-order"></i>
                    <span>Penilaian KPAT</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= isset($a_presentasi_penilaian_kpat) ? $a_presentasi_penilaian_kpat : ''; ?>"><a href="<?php echo site_url('dosen/penilaian_kpat/presentasi_penilaian'); ?>"><i class="fa fa-circle-o"></i> Presentasi Penilaian</a></li>
                    <li><a href="<?php echo site_url('dosen/penilaian_kpat/penilaian_revisi'); ?>"><i class="fa fa-circle-o"></i> Penilaian Mahasiswa </a></li>
                </ul>
            </li> -->

            <!-- <li class="<?= isset($a_update_penilaian) ? $a_update_penilaian : ''; ?> treeview">
                <a href="#">
                    <i class="glyphicon glyphicon-sort-by-order"></i>
                    <span>Update Penilaian</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= isset($a_update_penilaian_uts) ? $a_update_penilaian_uts : ''; ?>"><a href="<?php echo site_url('dosen/update_nilai/update_uts'); ?>"><i class="fa fa-circle-o"></i> UTS</a></li>
                    <li lass="<?= isset($a_update_penilaian_uas) ? $a_update_penilaian_uas : ''; ?>"><a href="<?php echo site_url('dosen/update_nilai/update_harian_uas'); ?>"><i class="fa fa-circle-o"></i> Harian & UAS</a></li>
                </ul>
            </li> -->


            <?php if (isKaprodi($this->session->userdata('kode_dosen'))) : ?>
                <li class="header" style="color: white;">MENU KAPRODI</li>
                
                <li hidden class="<?= isset($a_mahasiswa_prodi) ? $a_mahasiswa_prodi: ''; ?> treeview">
                    <a href="#">
                        <i class="fa fa-users"></i>
                        <span>Data Mahasiswa</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?= isset($a_data_semua_mahasiswa) ? $a_data_semua_mahasiswa: ''; ?>"><a href="<?php echo site_url('dosen/kaprodi/mahasiswa'); ?>"><i class="fa fa-circle-o"></i> Semua</a></li>
                        <li><a href="<?php echo site_url('dosen/konsultasi_perwalian'); ?>"><i class="fa fa-circle-o"></i> Aktif</a></li>
                        <li><a href="<?php echo site_url('dosen/konsultasi_perwalian'); ?>"><i class="fa fa-circle-o"></i> Tidak Aktif</a></li>
                        <li><a href="<?php echo site_url('dosen/konsultasi_perwalian'); ?>"><i class="fa fa-circle-o"></i> Lulus</a></li>
                        <li><a href="<?php echo site_url('dosen/konsultasi_perwalian'); ?>"><i class="fa fa-circle-o"></i> Belum Lulus</a></li>
                        <li><a href="<?php echo site_url('dosen/konsultasi_perwalian'); ?>"><i class="fa fa-circle-o"></i> Cuti</a></li>
                    </ul>
                </li>
                
                <li class="<?= isset($a_bidang_ilmu_kaprodi) ? $a_bidang_ilmu_kaprodi : '' ?>"><a href="<?= site_url('dosen/bidang_ilmu/info_bidang_ilmu_for_kaprodi') ?>"><i class="fa fa-users"></i> <span>Data Dosen</span></a></li>
                <li><a href="<?= site_url('dosen/kaprodi/konsultasi_perwalian') ?>"><i class="fa fa-user"></i> Perwalian Mahasiswa</a></li>
                <li><a href="<?= site_url('dosen/kaprodi/ipk') ?>"><i class="glyphicon glyphicon-th-list"></i>IPK</a></li>
                <li><a href="<?= site_url('dosen/kaprodi/Kpat') ?>"><i class="glyphicon glyphicon-th-list"></i>KPAT</a></li>
                <li class="<?= isset($a_bidang_ilmu_kaprodi) ? $a_bidang_ilmu_kaprodi : '' ?>"><a href="<?= site_url('dosen/bidang_ilmu/info_bidang_ilmu_for_kaprodi') ?>"><i class="fa fa-user"></i> <span>Data Bidang Ilmu Dosen</span></a></li>

                <li hidden><a href="#"><i class="fa fa-sort-numeric-desc"></i> <span>Hasil Pengajaran Dosen</span></a></li>
                <li><a href="<?= site_url('dosen/kaprodi/MBKM') ?>"><i class="glyphicon glyphicon-th-list"></i>MBKM</a></li>
          		<li><a href="<?= site_url('dosen/kaprodi/KRSAN') ?>"><i class="glyphicon glyphicon-th-list"></i>Mahasiswa KRS</a></li>
                <li><a href="<?= site_url('dosen/kaprodi/Kelas') ?>"><i class="glyphicon glyphicon-th-list"></i>Kelas Mahasiswa</a></li>
                <li class=" treeview" hidden>
                    <a href="#">
                        <i class="fa fa-question"></i>
                        <span>Status Perkuliahan</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo site_url('dosen/konsultasi_perwalian'); ?>"><i class="fa fa-circle-o"></i> Aktif</a></li>
                        <li><a href="<?php echo site_url('dosen/konsultasi_perwalian'); ?>"><i class="fa fa-circle-o"></i> Tidak Aktif</a></li>
                    </ul>
                </li>
                <li hidden><a href="#"><i class="fa fa-balance-scale"></i> <span>Rasio Dosen & MHS</span></a></li>

                <li class="<?= isset($a_validasi_nilai_kaprodi) ? $a_validasi_nilai_kaprodi : ''; ?> treeview">
                    <a href="#">
                        <i class="fa fa-sort-numeric-asc"></i>
                        <span>Validasi Nilai</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <!-- <li class="<?= isset($a_validasi_nilai_uts_prodi) ? $a_validasi_nilai_uts_prodi : ''; ?>"><a href="<?php echo site_url('dosen/kaprodi/validasinilai/validasi_nilai_uts'); ?>"><i class="fa fa-circle-o"></i> UTS</a></li>
                        <li class="<?= isset($a_validasi_nilai_uts_prodi) ? $a_validasi_nilai_uas_prodi : ''; ?>"><a href="<?php echo site_url('dosen/kaprodi/validasinilai/validasi_nilai_uas'); ?>"><i class="fa fa-circle-o"></i> Harian & UAS</a></li> -->
                        <li class="<?= isset($a_validasi_nilai_uts_prodi) ? $a_validasi_nilai_uas_prodi : ''; ?>"><a href="<?php echo site_url('dosen/kaprodi/validasinilai/validasi_nilai_revisi'); ?>"><i class="fa fa-circle-o"></i>Penilaian Mahasiswa</a></li>
                    </ul>
                </li>
          <li class="<?= isset($a_validasi_nilai_kaprodi_kpat) ? $a_validasi_nilai_kaprodi_kpat : ''; ?> treeview">
                    <a href="#">
                        <i class="fa fa-sort-numeric-asc"></i>
                        <span>Validasi Nilai KPAT</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?= isset($a_validasi_nilai_prodi_kpat) ? $a_validasi_nilai_prodi_kpat : ''; ?>"><a href="<?php echo site_url('dosen/kaprodi/validasinilai_kpat/validasi_nilai_revisi'); ?>"><i class="fa fa-circle-o"></i>Penilaian Mahasiswa</a></li>
                    </ul>
                </li>
                <!-- <li class="<?= isset($a_update_nilai_kaprodi) ? $a_update_nilai_kaprodi : ''; ?> treeview">
                    <a href="#">
                        <i class="fa fa-sort-numeric-asc"></i>
                        <span>Update Nilai</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?= isset($a_update_nilai_uts_prodi) ? $a_update_nilai_uts_prodi : ''; ?>"><a href="<?php echo site_url('dosen/kaprodi/update_penilaian/validasi_nilai_uts'); ?>"><i class="fa fa-circle-o"></i> UTS</a></li>
                        <li class="<?= isset($a_update_nilai_uas_prodi) ? $a_update_nilai_uas_prodi : ''; ?>"><a href="<?php echo site_url('dosen/kaprodi/update_penilaian/validasi_nilai_uas'); ?>"><i class="fa fa-circle-o"></i> Harian & UAS</a></li>
                    </ul>
                </li> -->

                <li class=" treeview" hidden>
                    <a href="#">
                        <i class="fa fa-trophy"></i>
                        <span>Indeks Prestasi</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo site_url('dosen/konsultasi_perwalian'); ?>"><i class="fa fa-circle-o"></i> IP</a></li>
                        <li><a href="<?php echo site_url('dosen/konsultasi_perwalian'); ?>"><i class="fa fa-circle-o"></i> IPK</a></li>
                    </ul>
                </li>

                <li class=" treeview" hidden>
                    <a href="#">
                        <i class="fa fa-graduation-cap"></i>
                        <span>Kelulusan</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo site_url('dosen/konsultasi_perwalian'); ?>"><i class="fa fa-circle-o"></i> Tepat Waktu</a></li>
                        <li><a href="<?php echo site_url('dosen/konsultasi_perwalian'); ?>"><i class="fa fa-circle-o"></i> Tidak Tepat Waktu</a></li>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (isDekan($this->session->userdata('kode_dosen'))) : ?>
                <li class="header" style="color: white;">MENU DEKAN</li>

                <li class="<?= isset($a_bidang_ilmu_dekan) ? $a_bidang_ilmu_dekan : '' ?>"><a href="<?= site_url('dosen/bidang_ilmu/info_bidang_ilmu_for_dekan') ?>"><i class="fa fa-user"></i> <span>Data Bidang Ilmu Dosen</span></a></li>


                <li hidden><a href="#"><i class="fa fa-sort-numeric-desc"></i> <span>Hasil Pengajaran Dosen</span></a></li>
                <li class=" treeview" hidden>
                    <a href="#">
                        <i class="fa fa-question"></i>
                        <span>Status Perkuliahan</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo site_url('dosen/konsultasi_perwalian'); ?>"><i class="fa fa-circle-o"></i> Aktif</a></li>
                        <li><a href="<?php echo site_url('dosen/konsultasi_perwalian'); ?>"><i class="fa fa-circle-o"></i> Tidak Aktif</a></li>
                    </ul>
                </li>
                <li hidden><a href="#"><i class="fa fa-balance-scale"></i> <span>Rasio Dosen & MHS</span></a></li>



                <li class="<?= isset($a_validasi_nilai_dekan) ? $a_validasi_nilai_dekan : ''; ?> treeview">
                    <a href="#">
                        <i class="fa fa-sort-numeric-asc"></i>
                        <span>Validasi Nilai</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <!-- <li class="<?= isset($a_validasi_nilai_uts_dekan) ? $a_validasi_nilai_uts_dekan : ''; ?>"><a href="<?php echo site_url('dosen/dekan/validasinilai/validasi_nilai_uts'); ?>"><i class="fa fa-circle-o"></i> UTS</a></li>
                        <li class="<?= isset($a_validasi_nilai_uas_dekan) ? $a_validasi_nilai_uas_dekan : ''; ?>"><a href="<?php echo site_url('dosen/dekan/validasinilai/validasi_nilai_uas'); ?>"><i class="fa fa-circle-o"></i> Harian & UAS</a></li> -->
                        <li class="<?= isset($a_validasi_nilai_uas_dekan) ? $a_validasi_nilai_uas_dekan : ''; ?>"><a href="<?php echo site_url('dosen/dekan/validasinilai/validasi_nilai_revisi'); ?>"><i class="fa fa-circle-o"></i> Penilaian Mahasiswa</a></li>
                    </ul>
                </li>
                <!-- <li class="<?= isset($a_update_nilai_dekan) ? $a_update_nilai_dekan : ''; ?> treeview">
                    <a href="#">
                        <i class="fa fa-sort-numeric-asc"></i>
                        <span>Update Nilai</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?= isset($a_update_nilai_uts_dekan) ? $a_update_nilai_uts_dekan : ''; ?>"><a href="<?php echo site_url('dosen/dekan/update_penilaian/validasi_nilai_uts'); ?>"><i class="fa fa-circle-o"></i> UTS</a></li>
                        <li class="<?= isset($a_update_nilai_uas_dekan) ? $a_update_nilai_uas_dekan : ''; ?>"><a href="<?php echo site_url('dosen/dekan/update_penilaian/validasi_nilai_uas'); ?>"><i class="fa fa-circle-o"></i> Harian & UAS</a></li>
                    </ul>
                </li> -->

                <li class=" treeview" hidden>
                    <a href="#">
                        <i class="fa fa-trophy"></i>
                        <span>Indeks Prestasi</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo site_url('dosen/konsultasi_perwalian'); ?>"><i class="fa fa-circle-o"></i> IP</a></li>
                        <li><a href="<?php echo site_url('dosen/konsultasi_perwalian'); ?>"><i class="fa fa-circle-o"></i> IPK</a></li>
                    </ul>
                </li>

                <li class=" treeview" hidden>
                    <a href="#">
                        <i class="fa fa-graduation-cap"></i>
                        <span>Kelulusan</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo site_url('dosen/konsultasi_perwalian'); ?>"><i class="fa fa-circle-o"></i> Tepat Waktu</a></li>
                        <li><a href="<?php echo site_url('dosen/konsultasi_perwalian'); ?>"><i class="fa fa-circle-o"></i> Tidak Tepat Waktu</a></li>
                    </ul>
                </li>
            <?php endif; ?>

            <li class="header">DUKUNGAN</li>
            <li><a href="<?= site_url('maintance'); ?>"><i class="fa fa-book text-yellow"></i> <span>Panduan</span></a></li>
            <li><a href="<?= site_url('maintance'); ?>"><i class="fa fa-envelope text-red"></i> <span>Laporkan Masalah</span> <i class="fa fa-external-link pull-right"></i></a></li>
        </ul>

    </section>
</aside>
