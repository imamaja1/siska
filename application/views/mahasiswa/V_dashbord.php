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

$foto_session = $this->session->userdata('foto');
$foto_file = FCPATH . 'assets/foto/' . $foto_session;
$has_foto = !empty($foto_session) && $foto_session !== 'P.png' && $foto_session !== 'L.png' && is_file($foto_file);
$foto_url = $has_foto ? base_url('assets/foto/' . $foto_session) : '';

$nama_parts = array_values(array_filter(explode(' ', trim($nama ?? ''))));
$initials = '';
if (count($nama_parts) >= 2) {
    $initials = strtoupper(substr($nama_parts[0], 0, 1) . substr($nama_parts[1], 0, 1));
} elseif (count($nama_parts) === 1) {
    $initials = strtoupper(substr($nama_parts[0], 0, 2));
} else {
    $initials = 'M';
}

$ipk_display = !empty($ipk_kumulatif) ? $ipk_kumulatif : '0.00';
$sks_display = !empty($total_sks_lulus) ? $total_sks_lulus : '0';
$dpa_display = !empty($dosen_wali) ? $dosen_wali : '-';
$sks_krs_display = !empty($sks_krs) ? $sks_krs : '24';
?>

<div class="mhs-dash">

    <!-- BEGIN: Hero / Identity Card -->
    <section class="dash-hero-card">
        <div class="dash-hero-left">
            <div class="dash-avatar-box">
                <?php if ($has_foto): ?>
                    <img src="<?= $foto_url ?>" alt="<?= e($nama) ?>" class="dash-avatar-img">
                <?php else: ?>
                    <span class="dash-avatar-initials"><?= e($initials) ?></span>
                <?php endif; ?>
            </div>
            <div class="dash-hero-info">
                <span class="dash-portal-lead">PORTAL MAHASISWA</span>
                <h1 class="dash-hero-name"><?= e($nama) ?></h1>
                <div class="dash-hero-chips">
                    <span class="dash-chip dash-chip-nim">NIM <?= e($nim) ?></span>
                    <?php if ($ta_label !== ''): ?>
                        <span class="dash-chip">Semester <?= e($semester_label) ?> <?= e($ta_label) ?></span>
                    <?php endif; ?>
                    <span class="dash-chip dash-chip-active">AKTIF</span>
                </div>
            </div>
        </div>

        <div class="dash-hero-right">
            <div class="dash-academic-summary">
                <div class="dash-acad-item is-dpa">
                    <span class="dash-acad-label">Dosen Pembimbing Akademik</span>
                    <strong class="dash-acad-dpa"><?= e($dpa_display) ?></strong>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Hero / Identity Card -->

    <!-- BEGIN: Status Administrasi & Registrasi Keuangan -->
    <section class="dash-panel-card dash-pay-card">
        <div class="dash-panel-header">
            <div>
                <h2 class="dash-card-title">Status Administrasi &amp; Registrasi Keuangan</h2>
                <span class="dash-card-sub">Informasi kewajiban pembayaran Semester <?= e($semester_label) ?> <?= e($ta_label) ?></span>
            </div>
            <span class="dash-reg-badge">Status: Terdaftar</span>
        </div>

        <div class="dash-pay-grid">
            <?php if ($pakai_ukt): ?>
                <div class="dash-pay-item <?= $spp_ok ? 'is-paid' : 'is-unpaid' ?>">
                    <div class="dash-pay-item-top">
                        <span class="dash-pay-type">UKT KULIAH</span>
                        <?php if ($spp_ok): ?>
                            <span class="dash-badge-lunas">LUNAS</span>
                        <?php else: ?>
                            <span class="dash-badge-unpaid">BELUM LUNAS</span>
                        <?php endif; ?>
                    </div>
                    <div class="dash-pay-info">
                        <?php if ($spp_ok): ?>
                            <span class="dash-pay-status-desc"><i class="fa fa-check-circle"></i> Terverifikasi Bagian Keuangan</span>
                        <?php else: ?>
                            <span class="dash-pay-status-desc is-pending"><i class="fa fa-clock-o"></i> Menunggu Pembayaran / Validasi</span>
                            <a href="<?= site_url('mahasiswa/krs') ?>" class="dash-btn-pay-now">Cek Tagihan / KRS &rarr;</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="dash-pay-item <?= $spp_ok ? 'is-paid' : 'is-unpaid' ?>">
                    <div class="dash-pay-item-top">
                        <span class="dash-pay-type">SPP KULIAH TETAP</span>
                        <?php if ($spp_ok): ?>
                            <span class="dash-badge-lunas">LUNAS</span>
                        <?php else: ?>
                            <span class="dash-badge-unpaid">BELUM LUNAS</span>
                        <?php endif; ?>
                    </div>
                    <div class="dash-pay-info">
                        <?php if ($spp_ok): ?>
                            <span class="dash-pay-status-desc"><i class="fa fa-check-circle"></i> Terverifikasi Bagian Keuangan</span>
                        <?php else: ?>
                            <span class="dash-pay-status-desc is-pending"><i class="fa fa-clock-o"></i> Menunggu Pembayaran / Validasi</span>
                            <a href="<?= site_url('mahasiswa/krs') ?>" class="dash-btn-pay-now">Cek Tagihan / KRS &rarr;</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="dash-pay-item <?= $sks_ok ? 'is-paid' : 'is-unpaid' ?>">
                    <div class="dash-pay-item-top">
                        <span class="dash-pay-type">BIAYA SKS VARIABEL</span>
                        <?php if ($sks_ok): ?>
                            <span class="dash-badge-lunas">LUNAS</span>
                        <?php else: ?>
                            <span class="dash-badge-unpaid">BELUM LUNAS</span>
                        <?php endif; ?>
                    </div>
                    <div class="dash-pay-info">
                        <?php if ($sks_ok): ?>
                            <span class="dash-pay-status-desc"><i class="fa fa-check-circle"></i> <?= e($sks_krs_display) ?> SKS Disetujui, Terverifikasi</span>
                        <?php else: ?>
                            <span class="dash-pay-status-desc is-pending"><i class="fa fa-clock-o"></i> Belum Diverifikasi (<?= e($sks_krs_display) ?> SKS)</span>
                            <a href="<?= site_url('mahasiswa/krs') ?>" class="dash-btn-pay-now">Cek Tagihan / KRS &rarr;</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <!-- END: Status Administrasi & Registrasi Keuangan -->

    <!-- BEGIN: Akses Cepat Layanan -->
    <section class="dash-quick-section">
        <div class="dash-section-head">
            <h2 class="dash-section-title">Akses Cepat Layanan</h2>
            <a href="<?= site_url('mahasiswa/kurikulum') ?>" class="dash-section-link">Semua Layanan &rarr;</a>
        </div>

        <div class="dash-services-grid">
            <!-- Card KRS -->
            <div class="dash-service-card">
                <div class="dash-service-top">
                    <h3 class="dash-service-title">Kartu Rencana Studi (KRS)</h3>
                    <span class="dash-service-badge"><?= e($sks_krs_display) ?> SKS</span>
                </div>
                <p class="dash-service-desc">
                    Susun rencana studi, konsultasikan dengan DPA, dan cetak KRS yang telah disetujui.
                </p>
                <div class="dash-service-action">
                    <a href="<?= site_url('mahasiswa/krs') ?>" class="dash-btn-navy-solid">
                        Susun KRS Online &rarr;
                    </a>
                </div>
            </div>

            <!-- Card KHS -->
            <div class="dash-service-card">
                <div class="dash-service-top">
                    <h3 class="dash-service-title">Kartu Hasil Studi (KHS)</h3>
                </div>
                <p class="dash-service-desc">
                    Evaluasi hasil studi semester, riwayat indeks prestasi, dan rincian nilai matakuliah.
                </p>
                <div class="dash-service-action">
                    <a href="<?= site_url('mahasiswa/khs') ?>" class="dash-btn-navy-outline">
                        Lihat KHS Semester &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- Secondary Services Links -->
        <div class="dash-secondary-links">
            <a href="<?= site_url('mahasiswa/petikan_nilai') ?>" class="dash-sublink-item">
                <i class="fa fa-file-text-o"></i>
                <div class="dash-sublink-text">
                    <strong>Petikan Nilai</strong>
                    <span>Transkrip nilai sementara seluruh semester</span>
                </div>
                <i class="fa fa-angle-right dash-sublink-arrow"></i>
            </a>
            <a href="<?= site_url('mahasiswa/kuisioner') ?>" class="dash-sublink-item">
                <i class="fa fa-comments-o"></i>
                <div class="dash-sublink-text">
                    <strong>Kuisioner Evaluasi</strong>
                    <span>Evaluasi proses perkuliahan dan pelayanan akademik</span>
                </div>
                <i class="fa fa-angle-right dash-sublink-arrow"></i>
            </a>
        </div>
    </section>
    <!-- END: Akses Cepat Layanan -->

</div>
