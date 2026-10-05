<?php
$seg2 = strtolower($this->uri->segment(2) ?? '');
$is_akademik_sub = in_array($seg2, array('kurikulum', 'matakuliah_prasyarat', 'kompetensi', 'kuisioner'));
?>
<div class="siska-nav-backdrop" id="nav-backdrop"></div>
<div class="navbar-collapse siska-navbar-nav-wrapper" id="navbar-collapse">
    <div class="siska-drawer-brand">
        <img src="<?= app_logo() ?>" alt="Logo Universitas Bumigora">
        <div class="siska-drawer-brand-meta">
            <span class="siska-drawer-brand-title">SISKA</span>
            <span class="siska-drawer-brand-sub">Universitas Bumigora</span>
        </div>
    </div>
    <ul class="nav navbar-nav siska-nav">
        <li class="<?= ($seg2 == 'home' || empty($seg2)) ? 'active' : '' ?>">
            <a href="<?= site_url('mahasiswa/home') ?>">
                <span>Dashboard</span>
            </a>
        </li>
        <li class="<?= ($seg2 == 'profil') ? 'active' : '' ?>">
            <a href="<?= site_url('mahasiswa/profil') ?>">
                <span>Profil</span>
            </a>
        </li>
        <li class="<?= ($seg2 == 'krs') ? 'active' : '' ?>">
            <a href="<?= site_url('mahasiswa/krs') ?>">
                <span>KRS</span>
            </a>
        </li>
        <li class="<?= ($seg2 == 'khs') ? 'active' : '' ?>">
            <a href="<?= site_url('mahasiswa/khs') ?>">
                <span>KHS</span>
            </a>
        </li>
        <li class="<?= ($seg2 == 'petikan_nilai') ? 'active' : '' ?>">
            <a href="<?= site_url('mahasiswa/petikan_nilai') ?>">
                <span>Petikan Nilai</span>
            </a>
        </li>
        <li class="dropdown <?= $is_akademik_sub ? 'active' : '' ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <span>Akademik</span> <span class="caret"></span>
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
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <span>Tautan</span> <span class="caret"></span>
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

    <!-- Drawer bottom actions -->
    <div class="siska-nav-actions">
        <a href="<?= site_url('mahasiswa/ganti_sandi') ?>" class="btn btn-default btn-block">
            <i class="fa fa-key"></i> Ganti Sandi
        </a>
        <a href="#!" onclick="konfirmasiKeluar('<?= site_url('Login/logout') ?>')" class="btn btn-danger btn-block">
            <i class="fa fa-sign-out"></i> Logout
        </a>
    </div>
</div>
