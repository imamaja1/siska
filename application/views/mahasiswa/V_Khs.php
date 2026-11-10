<?php
$has_nilai = isset($data['data_nilai']) && !empty($data['data_nilai']);
$sksn = 0;
$sks = 0;
if ($has_nilai) {
    foreach ($data['data_nilai'] as $row) {
        $sksn += $row['sksn'];
        $sks += $row['sks'];
    }
}
$ipk = $sks > 0 ? $sksn / $sks : 0;

if ($ipk >= 3.5) {
    $jumlah_maksimum_sks = 24;
} elseif ($ipk >= 3.25) {
    $jumlah_maksimum_sks = 23;
} elseif ($ipk >= 3) {
    $jumlah_maksimum_sks = 22;
} elseif ($ipk >= 2.75) {
    $jumlah_maksimum_sks = 21;
} elseif ($ipk >= 2.5) {
    $jumlah_maksimum_sks = 20;
} elseif ($ipk >= 2.25) {
    $jumlah_maksimum_sks = 19;
} elseif ($ipk >= 2) {
    $jumlah_maksimum_sks = 18;
} elseif ($ipk >= 1.75) {
    $jumlah_maksimum_sks = 16;
} elseif ($ipk >= 1.5) {
    $jumlah_maksimum_sks = 14;
} else {
    $jumlah_maksimum_sks = 12;
}

$nama_mhs = $data['nama_mahasiswa'] ?? $this->session->userdata('nama_mahasiswa') ?? 'Mahasiswa';
$nim_mhs = $data['nim'] ?? $this->session->userdata('nim') ?? '';
$nama_parts = array_values(array_filter(explode(' ', trim($nama_mhs))));
$initials = '';
if (count($nama_parts) >= 2) {
    $initials = strtoupper(substr($nama_parts[0], 0, 1) . substr($nama_parts[1], 0, 1));
} elseif (count($nama_parts) === 1) {
    $initials = strtoupper(substr($nama_parts[0], 0, min(2, strlen($nama_parts[0]))));
} else {
    $initials = 'M';
}

$sem_label = isset($data['semester']) ? (($data['semester'] == 'K') ? 'Konversi' : 'Semester ' . $data['semester']) : 'Semester -';
$sem_sub = isset($data['semester']) ? (($data['semester'] == 'K') ? 'Konversi' : (($data['semester'] % 2 == 0) ? 'Genap' : 'Ganjil')) : '';
$ta_label = isset($data['tahun_akademik']) ? e($data['tahun_akademik']) : '';
$kurikulum_label = isset($data['kurikulum']) ? e($data['kurikulum']) : '';
?>

<div class="khs-wrapper">

    <!-- 1. MASTER PAGE HEADER CARD -->
    <div class="khs-header-card">
        <div class="khs-header-left">
            <div class="khs-header-icon">
                <i class="fa fa-bar-chart"></i>
            </div>
            <div>
                <h2 class="khs-header-title">KHS | <?= e($sem_label) ?></h2>
                <p class="khs-header-sub">
                    Semester <?= $sem_sub ?> TA. <?= $ta_label ?><?php if (!empty($prodi->nama_program_studi)) : ?> | Kurikulum <?= e($prodi->nama_program_studi) ?> <?= !empty($kurikulum_label) ? '('.$kurikulum_label.')' : '' ?><?php endif; ?>
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <?php if ($has_nilai) : ?>
            <div class="khs-actions">
                <a href="<?= site_url('mahasiswa/khs/print_view/'.$data['krs'].'/'.$data['nim']) ?>" target="_blank" class="khs-btn-outline">
                    <i class="fa fa-print"></i> Cetak KHS
                </a>
                <a href="<?= site_url('mahasiswa/khs/cetak/'.$data['krs'].'/'.$data['nim']) ?>" class="khs-btn-navy">
                    <i class="fa fa-download"></i> Unduh KHS &rarr;
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- 2. STUDENT CONTEXT STRIP -->
    <div class="khs-meta-strip">
        <div class="khs-student-info">
            <div class="khs-avatar-init"><?= $initials ?></div>
            <div class="khs-student-details">
                <h3 class="khs-student-name"><?= e($nama_mhs) ?></h3>
                <p class="khs-student-sub">
                    NIM: <b><?= e($nim_mhs) ?></b><?php if (!empty($prodi->nama_program_studi)) : ?> | <?= e($prodi->nama_program_studi) ?><?php endif; ?><?php if (!empty($prodi->nama_fakultas)) : ?>, <?= e($prodi->nama_fakultas) ?><?php endif; ?>
                </p>
            </div>
        </div>

        <div class="khs-stat-box">
            <div class="khs-stat-unit">
                <span class="khs-stat-label">Dosen Wali / PA</span>
                <span class="khs-stat-value"><?= e($dosen_wali ?? '-') ?></span>
            </div>
            <div class="khs-stat-divider"></div>
            <div class="khs-stat-unit">
                <span class="khs-stat-label">Semester KHS</span>
                <span class="khs-stat-value is-navy"><?= e($sem_label) ?> <?= !empty($sem_sub) ? '('.$sem_sub.')' : '' ?></span>
            </div>
        </div>
    </div>

    <!-- 3. SEMESTER SELECTOR TABS -->
    <div class="khs-tabs-bar">
        <div class="khs-tabs-list">
            <?php if (!empty($kode_krs)) : ?>
                <?php foreach ($kode_krs as $row) : ?>
                    <?php $isActive = (isset($data['krs']) && $data['krs'] == $row->kode_krs); ?>
                    <a href="<?= site_url('mahasiswa/khs/index/'.$row->semester) ?>"
                       class="khs-tab-item <?= $isActive ? 'is-active' : '' ?>">
                        <i class="fa fa-calendar-check-o"></i> <?= ($row->semester == 'K') ? 'Konversi' : 'Semester '.$row->semester ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="khs-tabs-meta">
            <?php if ($has_nilai) : ?>
                Total: <b><?= count($data['data_nilai']) ?> Mata Kuliah</b> (<b><?= $sks ?> SKS</b>)
            <?php else : ?>
                Sistem Informasi Akademik &mdash; Universitas Bumigora
            <?php endif; ?>
        </div>
    </div>

    <!-- 4. KHS DATA TABLE -->
    <?php if ($has_nilai) : ?>
        <div class="khs-table-card">
            <div class="table-responsive" style="margin: 0; border: none;">
                <table class="khs-table-grid">
                    <thead>
                        <tr>
                            <th style="width: 55px; text-align: center;">NO.</th>
                            <th style="width: 140px; text-align: center;">KODE</th>
                            <th>MATAKULIAH</th>
                            <th style="width: 90px; text-align: center;">SKS</th>
                            <th style="width: 90px; text-align: center;">GRADE</th>
                            <th style="width: 90px; text-align: center;">SKSN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($data['data_nilai'] as $row) : ?>
                            <tr>
                                <td style="text-align: center; color: #64748B; font-weight: 600;"><?= $i++ ?>.</td>
                                <td style="text-align: center;" class="khs-td-code"><?= e($row['kode_matakuliah']) ?></td>
                                <td class="khs-td-name"><?= e($row['nama_matakuliah']) ?></td>
                                <td style="text-align: center; font-weight: 600; color: #0F172A;"><?= $row['sks'] ?></td>
                                <td class="khs-grade-cell"><?= e($row['grade']) ?></td>
                                <td style="text-align: center; font-weight: 700; color: #0F172A;"><?= $row['sksn'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="khs-table-footer-bar">
                <div>
                    <b>Jumlah Selesai:</b> <?= count($data['data_nilai']) ?> Mata Kuliah
                </div>
                <div style="display: flex; align-items: center; gap: 20px;">
                    <span>Total SKS: <span class="khs-footer-stat"><?= $sks ?></span></span>
                    <span>Total SKSN: <span class="khs-footer-stat"><?= $sksn ?></span></span>
                </div>
            </div>
        </div>

        <!-- 5. BOTTOM SUMMARY CARDS -->
        <div class="khs-bottom-grid">
            <div class="khs-summary-card">
                <span class="khs-summary-label">Jumlah SKS Ditempuh</span>
                <span class="khs-summary-val is-navy"><?= $sks ?> <span style="font-size: 14px; font-weight: 600; color: #64748B;">SKS</span></span>
                <p class="khs-summary-sub">Beban studi yang diambil semester ini</p>
            </div>

            <div class="khs-summary-card">
                <span class="khs-summary-label">Indeks Prestasi Semester (IPS)</span>
                <span class="khs-summary-val"><?= format_ipk($ipk) ?></span>
                <p class="khs-summary-sub">Dihitung dari rasio bobot SKSN / SKS</p>
            </div>

            <?php if (substr($nim_mhs, 4, 1) != 3) : ?>
                <div class="khs-summary-card">
                    <span class="khs-summary-label">Maksimum SKS Semester Depan</span>
                    <span class="khs-summary-val" style="color: #15803D;"><?= $jumlah_maksimum_sks ?> <span style="font-size: 14px; font-weight: 600; color: #64748B;">SKS</span></span>
                    <p class="khs-summary-sub">Batas hak pengambilan beban studi berikutnya</p>
                </div>
            <?php endif; ?>
        </div>

    <?php else : ?>
        <div class="khs-table-card" style="padding: 36px 24px; text-align: center;">
            <div style="font-size: 34px; color: #CBD5E1; margin-bottom: 8px;"><i class="fa fa-folder-open-o"></i></div>
            <h4 style="font-size: 15px; font-weight: 800; color: #0F172A; margin: 0 0 4px;">Data KHS Tidak Ditemukan</h4>
            <p style="font-size: 12px; color: #64748B; margin: 0;">Belum ada data nilai atau kartu hasil studi yang tercatat untuk semester ini.</p>
        </div>
    <?php endif; ?>

</div>
