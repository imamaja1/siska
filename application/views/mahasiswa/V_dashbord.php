<?php
$nim = $this->session->userdata('nim');
$nama = $this->session->userdata('nama_mahasiswa');

$prodi_nama = isset($prodi->nama_program_studi) ? $prodi->nama_program_studi : '';

$semester_label = (isset($tahun_akademik->semester) && $tahun_akademik->semester % 2 == 0) ? 'Genap' : 'Ganjil';
$ta_label = isset($tahun_akademik->tahun_akademik) ? $tahun_akademik->tahun_akademik : '';

$spp_ok = !empty($pembayaran) && $pembayaran->pembayaran_spp == 1;
$sks_ok = !empty($pembayaran) && $pembayaran->pembayaran_sks == 1;
$lab_ok = !empty($pembayaran) && $pembayaran->pembayaran_lab == 1;

$angkatan_num = (int) (isset($angkatan) ? $angkatan : 0);
$pakai_ukt = $angkatan_num >= 2025;
?>

<div class="mhs-dash">

    <!-- BEGIN: Hero -->
    <section class="dash-hero">
        <div class="dash-hero-main">

            <div class="dash-hero-text">
                <span class="dash-greeting-lead">Selamat datang,</span>
                <h1 class="dash-greeting-name"><?= e($nama) ?></h1>
                <div class="dash-meta">
                    <span class="dash-meta-item"><i class="fa fa-id-card-o"></i> <?= e($nim) ?></span>
                    <?php if ($prodi_nama !== ''): ?>
                        <span class="dash-meta-item"><i class="fa fa-graduation-cap"></i> <?= e($prodi_nama) ?></span>
                    <?php endif; ?>
                    <?php if ($ta_label !== ''): ?>
                        <span class="dash-meta-item"><i class="fa fa-calendar-check-o"></i> Semester <?= e($semester_label) ?> &ndash; TA <?= e($ta_label) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </section>
    <!-- END: Hero -->

    <!-- BEGIN: Status Mahasiswa -->
    <section class="dash-panel dash-pay">
        <div class="dash-panel-head">
            <h2 class="dash-panel-title">Status Mahasiswa</h2>
            <?php if ($ta_label !== ''): ?>
                <span class="dash-panel-hint">TA <?= e($ta_label) ?> <?= e($semester_label) ?></span>
            <?php endif; ?>
        </div>
        <div class="dash-pay-body">
            <div class="dash-pay-grid">
                <?php if ($pakai_ukt): ?>
                    <div class="dash-stat">
                        <span class="dash-stat-label">UKT</span>
                        <span class="dash-stat-value <?= $spp_ok ? 'is-ok' : 'is-warn' ?>"><?= $spp_ok ? 'Lunas' : 'Belum' ?></span>
                    </div>
                <?php else: ?>
                    <div class="dash-stat">
                        <span class="dash-stat-label">SPP</span>
                        <span class="dash-stat-value <?= $spp_ok ? 'is-ok' : 'is-warn' ?>"><?= $spp_ok ? 'Lunas' : 'Belum' ?></span>
                    </div>
                    <div class="dash-stat">
                        <span class="dash-stat-label">SKS</span>
                        <span class="dash-stat-value <?= $sks_ok ? 'is-ok' : 'is-warn' ?>"><?= $sks_ok ? 'Lunas' : 'Belum' ?></span>
                    </div>
                    <div class="dash-stat">
                        <span class="dash-stat-label">Praktikum</span>
                        <span class="dash-stat-value <?= $lab_ok ? 'is-ok' : 'is-warn' ?>"><?= $lab_ok ? 'Lunas' : 'Belum' ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="dash-hero-actions">
                <a href="<?= site_url('mahasiswa/krs') ?>" class="dash-btn dash-btn-solid">
                    <i class="fa fa-pencil-square-o"></i>
                    <span>Susun KRS</span>
                </a>
                <a href="<?= site_url('mahasiswa/khs') ?>" class="dash-btn dash-btn-line">
                    <i class="fa fa-bar-chart"></i>
                    <span>Lihat KHS</span>
                </a>
            </div>
        </div>
    </section>
    <!-- END: Status Mahasiswa -->

    <div class="dash-grid">

        <!-- BEGIN: Quick Access -->
        <section class="dash-panel">
            <div class="dash-panel-head">
                <h2 class="dash-panel-title">Akses cepat</h2>
                <span class="dash-panel-hint">Menu akademik <i class="fa fa-angle-right"></i></span>
            </div>

            <ul>
                <li class="dash-list-item">
                    <a href="<?= site_url('mahasiswa/krs') ?>">
                        <span class="dash-list-icon"><i class="fa fa-pencil-square-o"></i></span>
                        <span class="dash-list-text">
                            <span class="dash-list-label">Kartu Rencana Studi</span>
                            <span class="dash-list-desc">Susun dan ajukan matakuliah semester berjalan.</span>
                        </span>
                        <i class="fa fa-angle-right dash-list-arrow"></i>
                    </a>
                </li>
                <li class="dash-list-item">
                    <a href="<?= site_url('mahasiswa/khs') ?>">
                        <span class="dash-list-icon"><i class="fa fa-bar-chart"></i></span>
                        <span class="dash-list-text">
                            <span class="dash-list-label">Kartu Hasil Studi</span>
                            <span class="dash-list-desc">Lihat nilai final tiap semester yang dipublikasikan.</span>
                        </span>
                        <i class="fa fa-angle-right dash-list-arrow"></i>
                    </a>
                </li>
                <li class="dash-list-item">
                    <a href="<?= site_url('mahasiswa/petikan_nilai') ?>">
                        <span class="dash-list-icon"><i class="fa fa-file-text-o"></i></span>
                        <span class="dash-list-text">
                            <span class="dash-list-label">Petikan Nilai</span>
                            <span class="dash-list-desc">Transkrip nilai sementara seluruh semester.</span>
                        </span>
                        <i class="fa fa-angle-right dash-list-arrow"></i>
                    </a>
                </li>
                <li class="dash-list-item">
                    <a href="<?= site_url('mahasiswa/kuisioner') ?>">
                        <span class="dash-list-icon"><i class="fa fa-comments-o"></i></span>
                        <span class="dash-list-text">
                            <span class="dash-list-label">Kuisioner Evaluasi</span>
                            <span class="dash-list-desc">Beri penilaian proses kuliah dan dosen pengampu.</span>
                        </span>
                        <i class="fa fa-angle-right dash-list-arrow"></i>
                    </a>
                </li>
            </ul>
        </section>
        <!-- END: Quick Access -->

    </div>

</div>
