<?php
$seg2 = strtolower($this->uri->segment(2) ?? '');
$is_akademik_sub = in_array($seg2, array('kurikulum', 'matakuliah_prasyarat', 'kompetensi', 'kuisioner'));

$sess_nama = $this->session->userdata('nama_mahasiswa') ?: 'Mahasiswa';
$sess_nim = $this->session->userdata('nim') ?: '';
$sess_prodi = $this->session->userdata('singkatan_program_studi') ?: ($this->session->userdata('program_studi') ?: '');

$sess_parts = array_values(array_filter(explode(' ', trim($sess_nama))));
$sess_initials = '';
if (count($sess_parts) >= 2) {
    $sess_initials = strtoupper(substr($sess_parts[0], 0, 1) . substr($sess_parts[1], 0, 1));
} elseif (count($sess_parts) === 1) {
    $sess_initials = strtoupper(substr($sess_parts[0], 0, min(2, strlen($sess_parts[0]))));
} else {
    $sess_initials = 'M';
}
?>

<div class="siska-nav-backdrop" id="nav-backdrop"></div>

<div class="navbar-collapse siska-navbar-nav-wrapper" id="navbar-collapse">

    <!-- Mobile Drawer Header (Visible on Mobile only) -->
    <div class="siska-drawer-header">
        <div class="siska-drawer-user-info">
            <div class="siska-drawer-avatar"><?= e($sess_initials) ?></div>
            <div class="siska-drawer-user-meta">
                <span class="siska-drawer-user-name"><?= e($sess_nama) ?></span>
                <span class="siska-drawer-user-sub">NIM <?= e($sess_nim) ?><?= !empty($sess_prodi) ? ' | ' . e($sess_prodi) : '' ?></span>
            </div>
        </div>
        <button type="button" class="siska-drawer-close" id="btn-drawer-close" aria-label="Tutup Menu">
            <i class="fa fa-times"></i>
        </button>
    </div>

    <!-- Navigation Menu Links -->
    <div class="siska-drawer-nav-body">
        <ul class="nav navbar-nav siska-nav">
            <!-- Section 1: Menu Utama (Mobile Section Header) -->
            <li class="siska-drawer-section-title"><span>MENU UTAMA</span></li>

            <li class="<?= ($seg2 == 'home' || empty($seg2)) ? 'active' : '' ?>">
                <a href="<?= site_url('mahasiswa/home') ?>">
                    <span class="siska-nav-text">Dashboard</span>
                    <i class="fa fa-caret-right siska-mobile-arrow"></i>
                </a>
            </li>
            <li class="<?= ($seg2 == 'profil') ? 'active' : '' ?>">
                <a href="<?= site_url('mahasiswa/profil') ?>">
                    <span class="siska-nav-text">Profil</span>
                    <i class="fa fa-caret-right siska-mobile-arrow"></i>
                </a>
            </li>

            <!-- Section 2: Akademik & Perkuliahan -->
            <li class="siska-drawer-section-title"><span>AKADEMIK &amp; PERKULIAHAN</span></li>

            <li class="<?= ($seg2 == 'krs') ? 'active' : '' ?>">
                <a href="<?= site_url('mahasiswa/krs') ?>">
                    <span class="siska-nav-text">KRS</span>
                    <i class="fa fa-caret-right siska-mobile-arrow"></i>
                </a>
            </li>
            <li class="<?= ($seg2 == 'khs') ? 'active' : '' ?>">
                <a href="<?= site_url('mahasiswa/khs') ?>">
                    <span class="siska-nav-text">KHS</span>
                    <i class="fa fa-caret-right siska-mobile-arrow"></i>
                </a>
            </li>
            <li class="<?= ($seg2 == 'petikan_nilai') ? 'active' : '' ?>">
                <a href="<?= site_url('mahasiswa/petikan_nilai') ?>">
                    <span class="siska-nav-text">Petikan Nilai</span>
                    <i class="fa fa-caret-right siska-mobile-arrow"></i>
                </a>
            </li>
            <li class="dropdown <?= $is_akademik_sub ? 'active' : '' ?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="siska-nav-text">Akademik</span> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li class="<?= ($seg2 == 'kurikulum') ? 'active' : '' ?>">
                        <a href="<?= site_url('mahasiswa/kurikulum') ?>">Kurikulum Matakuliah</a>
                    </li>
                    <li class="<?= ($seg2 == 'matakuliah_prasyarat') ? 'active' : '' ?>">
                        <a href="<?= site_url('mahasiswa/matakuliah_prasyarat') ?>">Matakuliah Prasyarat</a>
                    </li>
                    <li class="<?= ($seg2 == 'kompetensi') ? 'active' : '' ?>">
                        <a href="<?= site_url('mahasiswa/kompetensi') ?>">Uji Kompetensi</a>
                    </li>
                    <li class="divider"></li>
                    <li class="<?= ($seg2 == 'kuisioner') ? 'active' : '' ?>">
                        <a href="<?= site_url('mahasiswa/kuisioner') ?>">Kuisioner Evaluasi</a>
                    </li>
                </ul>
            </li>

            <!-- Section 3: Tautan Kampus -->
            <li class="siska-drawer-section-title"><span>TAUTAN KAMPUS</span></li>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="siska-nav-text">Tautan</span> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li><a target="_blank" href="https://universitasbumigora.ac.id/">Website Universitas</a></li>
                    <li><a target="_blank" href="https://baak.universitasbumigora.ac.id/">Portal BAAK</a></li>
                    <li><a target="_blank" href="https://e-learning.universitasbumigora.ac.id/">E-Learning UBG</a></li>
                    <li><a target="_blank" href="https://perpustakaan.universitasbumigora.ac.id/">Perpustakaan</a></li>
                    <li><a target="_blank" href="https://repository.universitasbumigora.ac.id/">Repository Institusi</a></li>
                </ul>
            </li>
        </ul>
    </div>

    <!-- Mobile Drawer Footer (Visible on Mobile only) -->
    <div class="siska-drawer-footer">
        <a href="<?= site_url('mahasiswa/ganti_sandi') ?>" class="siska-drawer-btn-sandi">
            <i class="fa fa-key"></i> Ganti Sandi Akun
        </a>
        <button type="button" onclick="konfirmasiKeluar('<?= site_url('Login/logout') ?>')" class="siska-drawer-btn-logout">
            <i class="fa fa-sign-out"></i> Keluar dari Akun
        </button>
        <div class="siska-drawer-version">
            SISKA &mdash; Universitas Bumigora
        </div>
    </div>

</div>
