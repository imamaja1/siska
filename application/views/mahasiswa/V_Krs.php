<?= $this->session->flashdata('info') ?>

<?php
// Pre-calculate SKS breakdown and total SKS for student meta strip
$t = 0; $pk = 0; $pt = 0;
if (!empty($data)) {
    foreach ($data as $row) {
        $t  += (int) $row->sks_teori;
        $pk += (int) $row->sks_praktek;
        $pt += (int) $row->sks_praktikum;
    }
}
$total_sks = $t + $pk + $pt;

// Monogram initial
$nama_words = explode(' ', trim($nama_mahasiswa ?? 'M'));
if (count($nama_words) >= 2) {
    $initials = strtoupper(substr($nama_words[0], 0, 1) . substr($nama_words[1], 0, 1));
} else {
    $initials = strtoupper(substr($nama_words[0], 0, min(2, strlen($nama_words[0]))));
}

$angkatan = substr($data_mahasiswa->nim ?? $nim, 0, 2);
$is_approved = !empty($aktif_dosen);
$maks_sks = isset($beban_sks['beban_sks']) ? $beban_sks['beban_sks'] : 24;
?>

<div class="krs-wrapper">

    <!-- 1. MASTER PAGE HEADER CARD -->
    <div class="krs-header-card">
        <div class="krs-header-left">
            <div>
                <h2 class="krs-header-title">KRS | <?= ($semester == 'K') ? 'Konversi' : 'Semester ' . e($semester) ?></h2>
                <p class="krs-header-sub">
                    Semester <?= ($semester == 'K') ? 'Konversi' : e($semester) ?> TA. <?= isset($tahun_akademik->tahun_akademik) ? e($tahun_akademik->tahun_akademik) : '' ?><?php if (!empty($prodi->nama_program_studi)) : ?> | <?= e($prodi->nama_program_studi) ?><?php endif; ?>
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="krs-actions">
            <?php if ($is_approved) : ?>
                <span class="krs-status-approved">
                    <i class="fa fa-check-circle"></i> Status: Disetujui DPA ✓
                </span>
            <?php else : ?>
                <span class="krs-status-pending">
                    <i class="fa fa-clock-o"></i> Status: Belum Disetujui DPA
                </span>
            <?php endif; ?>

            <?php if ($ta == $tahun_akademik->kode_tahun_akademik) : ?>
                <?php if (empty($aktif_dosen) && empty($bayar_sks)) : ?>
                    <a href="<?= site_url('mahasiswa/krs/edit_krs') ?>" class="krs-btn-outline">
                        <i class="fa fa-edit"></i> Ubah KRS
                    </a>
                <?php else : ?>
                    <?php if (empty($aktif_dosen) && $angkatan > 24) : ?>
                        <a href="<?= site_url('mahasiswa/krs/edit_krs') ?>" class="krs-btn-outline">
                            <i class="fa fa-edit"></i> Ubah KRS
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($aktif_dosen) && $angkatan > 24) : ?>
                        <a href="<?= site_url('mahasiswa/krs/print_view') ?>" target="_blank" class="krs-btn-outline">
                            <i class="fa fa-print"></i> Cetak KRS
                        </a>
                        <a href="<?= site_url('mahasiswa/krs/cetak') ?>" class="krs-btn-navy">
                            <i class="fa fa-download"></i> Unduh PDF &rarr;
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (!empty($aktif_dosen) && !empty($bayar_sks) && $angkatan < 25) : ?>
                    <a href="<?= site_url('mahasiswa/krs/print_view') ?>" target="_blank" class="krs-btn-outline">
                        <i class="fa fa-print"></i> Cetak KRS
                    </a>
                    <a href="<?= site_url('mahasiswa/krs/cetak') ?>" class="krs-btn-navy">
                        <i class="fa fa-download"></i> Unduh PDF &rarr;
                    </a>
                <?php endif; ?>
            <?php elseif ($semester != 'K') : ?>
                <a href="<?= site_url('mahasiswa/krs/print_view_lalu/'.$tahun_akademik->kode_tahun_akademik.'/'.$semester) ?>" target="_blank" class="krs-btn-outline">
                    <i class="fa fa-print"></i> Cetak KRS
                </a>
                <a href="<?= site_url('mahasiswa/krs/cetak_lalu/'.$tahun_akademik->kode_tahun_akademik.'/'.$semester) ?>" class="krs-btn-navy">
                    <i class="fa fa-download"></i> Unduh PDF &rarr;
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- 2. STUDENT CONTEXT STRIP -->
    <div class="krs-meta-strip">
        <div class="krs-student-info">
            <div class="krs-student-details">
                <h3 class="krs-student-name"><?= e($nama_mahasiswa) ?></h3>
                <p class="krs-student-sub">
                    NIM: <b><?= e($nim) ?></b><?php if (!empty($prodi->nama_program_studi)) : ?> | <?= e($prodi->nama_program_studi) ?><?php endif; ?><?php if (!empty($prodi->nama_fakultas)) : ?>, <?= e($prodi->nama_fakultas) ?><?php endif; ?>
                </p>
            </div>
        </div>

        <div class="krs-stat-box">
            <div class="krs-stat-unit">
                <span class="krs-stat-label">Dosen Wali / PA</span>
                <span class="krs-stat-value"><?= e($dosen_wali) ?></span>
                <?php if (!empty($dosen_perwakilan)) : ?>
                    <span style="font-size:11px; color:#15803D;">
                        <i class="fa fa-phone"></i> <?= e($dosen_perwakilan['nama_dosen']) ?> (<?= e($dosen_perwakilan['no_telp']) ?>)
                    </span>
                <?php endif; ?>
            </div>
            <div class="krs-stat-divider"></div>
            <div class="krs-stat-unit">
                <span class="krs-stat-label">Total SKS Diambil</span>
                <span class="krs-stat-value is-navy"><?= $total_sks ?> SKS / <?= $maks_sks ?> SKS Maks</span>
            </div>
            <div class="krs-stat-divider"></div>
            <div class="krs-stat-unit">
                <span class="krs-stat-label">Status Registrasi</span>
                <span class="krs-chip-active">AKTIF KULIAH</span>
            </div>
        </div>
    </div>

    <!-- 3. SEMESTER SELECTOR TABS -->
    <div class="krs-tabs-row">
        <div class="krs-tabs-list">
            <?php if (!empty($krs_mhs)) : ?>
                <?php foreach ($krs_mhs as $row) : ?>
                    <?php $isActiveTab = (isset($ta_selected) && $ta_selected == $row->kode_tahun_akademik && $semester == $row->semester); ?>
                    <a href="<?= site_url('mahasiswa/krs/old/'.$row->semester) ?>"
                       class="krs-tab-item <?= $isActiveTab ? 'is-active' : '' ?>">
                        <i class="fa fa-calendar-check-o"></i> <?= ($row->semester == 'K') ? 'Konversi' : 'Semester '.$row->semester ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php $isCurrentActive = ($ta == $tahun_akademik->kode_tahun_akademik); ?>
            <a href="<?= site_url('mahasiswa/krs/index/') ?>"
               class="krs-tab-item <?= $isCurrentActive ? 'is-active' : '' ?>">
                <i class="fa fa-calendar-check-o"></i> Semester <?= isset($semester_aktif) ? $semester_aktif : $semester ?> (Aktif Berjalan)
            </a>
        </div>
        <div class="krs-note-hint">
            Sistem Informasi Akademik &mdash; Universitas Bumigora
        </div>
    </div>

    <!-- 4. STANDARDIZED DATA TABLE CARD -->
    <div class="krs-table-card">
        <div class="krs-table-head-bar">
            <h4 class="krs-table-title">
                Daftar Mata Kuliah Semester <?= ($semester == 'K') ? 'Konversi' : e($semester) ?>
            </h4>
            <span class="krs-table-badge">
                <?= !empty($data) ? count($data) : 0 ?> Mata Kuliah (<?= $total_sks ?> SKS Total)
            </span>
        </div>

        <div class="table-responsive" style="margin: 0; border: none;">
            <table class="krs-table-grid">
                <thead>
                    <tr>
                        <th style="width: 45px; text-align: center;">NO</th>
                        <th style="width: 130px; text-align: center;">KODE MK</th>
                        <th>NAMA MATA KULIAH</th>
                        <th style="width: 85px; text-align: center;">TEORI</th>
                        <th style="width: 85px; text-align: center;">PRAKTEK</th>
                        <th style="width: 85px; text-align: center;">PRAKTIKUM</th>
                        <th style="width: 95px; text-align: center;">TOTAL SKS</th>
                        <th style="width: 100px; text-align: center;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data)) : ?>
                        <?php $i = 1; foreach ($data as $row) : ?>
                            <?php
                            $sub_total = (int)$row->sks_teori + (int)$row->sks_praktek + (int)$row->sks_praktikum;
                            $mk_name = $row->nama_matakuliah;
                            if (substr($this->session->userdata('nim'), 0, 2) == '16' && $row->kode_matakuliah == 'TSKB351435') {
                                $mk_name = 'Animasi 2 Dimensi';
                            }
                            ?>
                            <tr>
                                <td style="text-align: center; color: #64748B; font-weight: 600;"><?= $i++ ?></td>
                                <td style="text-align: center;" class="krs-td-code"><?= e($row->kode_matakuliah) ?></td>
                                <td class="krs-td-name"><?= e($mk_name) ?></td>
                                <td style="text-align: center; color: #334155;">
                                    <?= ((int)$row->sks_teori > 0) ? (int)$row->sks_teori . ' SKS' : '-' ?>
                                </td>
                                <td style="text-align: center; color: #334155;">
                                    <?= ((int)$row->sks_praktek > 0) ? (int)$row->sks_praktek . ' SKS' : '-' ?>
                                </td>
                                <td style="text-align: center; color: #334155;">
                                    <?= ((int)$row->sks_praktikum > 0) ? (int)$row->sks_praktikum . ' SKS' : '-' ?>
                                </td>
                                <td style="text-align: center; font-weight: 800; color: #0F172A;">
                                    <?= $sub_total ?> SKS
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($row->status == 'B') : ?>
                                        <span class="krs-badge-b"><i class="fa fa-check"></i> Baru</span>
                                    <?php else : ?>
                                        <span class="krs-badge-u"><i class="fa fa-refresh"></i> Ulang</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 24px; color: #64748B;">
                                <em>Tidak ada data mata kuliah untuk semester ini.</em>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="krs-table-footer-bar">
            <div>
                Total Beban Studi Semester Ini: <b><?= !empty($data) ? count($data) : 0 ?> Mata Kuliah</b>
            </div>
            <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                <span>SKS Teori: <b><?= $t ?></b>, Praktek: <b><?= $pk ?></b>, Praktikum: <b><?= $pt ?></b></span>
                <span class="krs-total-pill">Total: <?= $total_sks ?> SKS</span>
            </div>
        </div>
    </div>

    <!-- 5. BOTTOM GRID: RINGKASAN & LEGENDA -->
    <div class="krs-bottom-grid">
        <!-- Ringkasan Beban SKS -->
        <div class="krs-info-box">
            <h5 class="krs-info-box-title"><i class="fa fa-bar-chart"></i> Ringkasan Beban SKS</h5>
            <table class="table table-bordered table-striped" style="margin: 0; font-size: 12.5px;">
                <tbody>
                    <tr>
                        <th style="width: 55%; color: #475569;">IP Semester Lalu</th>
                        <td style="font-weight: 700; color: #0F172A;">
                            <?= ($semester != "1" && $semester != "K" && isset($beban_sks['ip_semester_lalu'])) ? number_format($beban_sks['ip_semester_lalu'], 2) : "-" ?>
                        </td>
                    </tr>
                    <?php if (substr($nim, 4, 1) != 3) : ?>
                        <tr>
                            <th style="color: #475569;">Beban SKS Semester Sekarang</th>
                            <td style="font-weight: 700; color: #0F3D6E;">
                                <?= ($semester == 1 || $semester == 'K') ? $total_sks : ($beban_sks['beban_sks'] ?? '-') ?> SKS
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <th style="color: #475569;">Jumlah Kredit yang Diambil</th>
                        <td style="font-weight: 700; color: #0F172A;"><?= $total_sks ?> SKS</td>
                    </tr>
                    <tr>
                        <th style="color: #475569;">Status Pengesahan Dosen Wali</th>
                        <td>
                            <?php if ($is_approved) : ?>
                                <span style="color: #15803D; font-weight: 700;"><i class="fa fa-check-circle"></i> Sudah Disetujui</span>
                            <?php else : ?>
                                <span style="color: #DC2626; font-weight: 700;"><i class="fa fa-clock-o"></i> Menunggu Persetujuan</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Panduan & Keterangan -->
        <div class="krs-info-box">
            <h5 class="krs-info-box-title"><i class="fa fa-info-circle"></i> Keterangan &amp; Panduan</h5>
            <ul class="krs-info-list">
                <li><span class="krs-badge-b" style="display:inline-block; margin-right:4px;">B</span> <b>Baru</b>: Mata kuliah baru pertama kali diambil pada semester ini.</li>
                <li><span class="krs-badge-u" style="display:inline-block; margin-right:4px;">U</span> <b>Ulang</b>: Mata kuliah perbaikan atau mengulang dari semester sebelumnya.</li>
                <li><b>T / PK / PT</b>: Rincian jenis SKS Teori (T), Praktek (PK), dan Praktikum (PT).</li>
                <li>Apabila terdapat ketidaksesuaian mata kuliah atau batas maksimum SKS, silakan konsultasi langsung dengan <b>Dosen Pembimbing Akademik (DPA)</b> sebelum batas waktu KRS berakhir.</li>
            </ul>
        </div>
    </div>

</div>

