<?php
$nama_mhs = $mahasiswa->nama_mahasiswa ?? $this->session->userdata('nama_mahasiswa') ?? 'Mahasiswa';
$nim_mhs = $mahasiswa->nim ?? $this->session->userdata('nim') ?? '';
$nama_parts = array_values(array_filter(explode(' ', trim($nama_mhs))));
$initials = '';
if (count($nama_parts) >= 2) {
    $initials = strtoupper(substr($nama_parts[0], 0, 1) . substr($nama_parts[1], 0, 1));
} elseif (count($nama_parts) === 1) {
    $initials = strtoupper(substr($nama_parts[0], 0, min(2, strlen($nama_parts[0]))));
} else {
    $initials = 'M';
}

$sem_ta_label = isset($tahun_akademik->semester) ? (($tahun_akademik->semester % 2 == 0) ? 'Genap' : 'Ganjil') : '';
$ta_label = isset($tahun_akademik->ta) ? e($tahun_akademik->ta) : '';
$angkatan_label = !empty($nim_mhs) ? '20' . substr($nim_mhs, 0, 2) : '';

$sks_final = 0;
$sksn_final = 0;
$data_list = (isset($data) && is_iterable($data)) ? $data : [];
?>

<div class="petikan-wrapper">

    <!-- 1. MASTER PAGE HEADER CARD -->
    <div class="petikan-header-card">
        <div class="petikan-header-left">
            <div class="petikan-header-icon">
                <i class="fa fa-file-text-o"></i>
            </div>
            <div>
                <h2 class="petikan-header-title">Petikan Nilai</h2>
                <p class="petikan-header-sub">
                    Semester <?= $sem_ta_label ?> TA. <?= $ta_label ?><?= !empty($angkatan_label) ? ', Angkatan ' . $angkatan_label : '' ?><?= !empty($prodi->nama_program_studi) ? ', ' . e($prodi->nama_program_studi) : '' ?>
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="petikan-actions">
            <a href="<?= site_url('mahasiswa/Petikan_nilai/cetak') ?>" class="petikan-btn-outline">
                <i class="fa fa-download"></i> Versi Sebelumnya
            </a>
            <a href="<?= site_url('mahasiswa/Petikan_nilai/cetak_now') ?>" class="petikan-btn-navy">
                <i class="fa fa-download"></i> Versi Terbaru &rarr;
            </a>
        </div>
    </div>

    <!-- 2. STUDENT CONTEXT STRIP -->
    <div class="petikan-meta-strip">
        <div class="petikan-student-info">
            <div class="petikan-avatar-init"><?= $initials ?></div>
            <div class="petikan-student-details">
                <h3 class="petikan-student-name"><?= e($nama_mhs) ?></h3>
                <p class="petikan-student-sub">
                    NIM: <b><?= e($nim_mhs) ?></b><?= !empty($prodi->nama_program_studi) ? ' | ' . e($prodi->nama_program_studi) : '' ?><?= !empty($prodi->nama_fakultas) ? ', ' . e($prodi->nama_fakultas) : '' ?>
                </p>
            </div>
        </div>

        <div class="petikan-stat-box">
            <div class="petikan-stat-unit">
                <span class="petikan-stat-label">Program Studi</span>
                <span class="petikan-stat-value"><?= e($prodi->nama_program_studi ?? '-') ?></span>
            </div>
            <div class="petikan-stat-divider"></div>
            <div class="petikan-stat-unit">
                <span class="petikan-stat-label">Fakultas</span>
                <span class="petikan-stat-value"><?= e($prodi->nama_fakultas ?? '-') ?></span>
            </div>
        </div>
    </div>

    <!-- 3. TABLE PER SEMESTER -->
    <?php if (!empty($data_list)) : ?>
        <?php foreach ($data_list as $key) : ?>
            <?php
            $sem_data_nilai = $key['data_nilai'] ?? [];
            $sem_sks = 0;
            if (!empty($sem_data_nilai)) {
                foreach ($sem_data_nilai as $row_item) {
                    if (isset($row_item['nilai_akhir'])) {
                        $sem_sks += (int)($row_item['sks'] ?? 0);
                    }
                }
            }
            ?>
            <div class="petikan-table-card">
                <div class="petikan-table-head-bar">
                    <h4 class="petikan-table-title">
                        SEMESTER <?= e($key['semester']) ?>
                    </h4>
                    <span class="petikan-table-sub">
                        <?= count($sem_data_nilai) ?> Mata Kuliah (<?= $sem_sks ?> SKS Selesai)
                    </span>
                </div>

                <div class="table-responsive" style="margin: 0; border: none;">
                    <table class="petikan-table-grid">
                        <thead>
                            <tr>
                                <th style="width: 50px; text-align: center;">NO.</th>
                                <th style="width: 130px; text-align: center;">KODE MK</th>
                                <th>MATAKULIAH</th>
                                <th style="width: 80px; text-align: center;">SKS</th>
                                <th style="width: 160px; text-align: center;">NA</th>
                                <th style="width: 85px; text-align: center;">GRADE</th>
                                <th style="width: 85px; text-align: center;">SKSN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($sem_data_nilai)) :
                                $sks = 0;
                                $sksn = 0;
                                $j = 1;
                                foreach ($sem_data_nilai as $row) :
                                    ?>
                                    <tr>
                                        <td style="text-align: center; color: #64748B; font-weight: 600;"><?= $j++ ?>.</td>
                                        <td style="text-align: center;" class="petikan-td-code"><?= e($row['kode_matakuliah']) ?></td>
                                        <td class="petikan-td-name"><?= e($row['nama_matakuliah']) ?></td>
                                        <td style="text-align: center; font-weight: 600; color: #0F172A;">
                                            <?= isset($row['nilai_akhir']) ? $row['sks'] : '-' ?>
                                        </td>
                                        <td class="petikan-na-cell">
                                            <?php if (isset($row['attempts']) && count($row['attempts']) > 0): ?>
                                                <?php foreach ($row['attempts'] as $att): ?>
                                                    <span>Smt <?= $att['semester'] ?>: <b><?= e(format_dua_desimal_ganjil($att['nilai_akhir'])) ?></b> (<?= $att['grade'] ?>)</span><br>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <?php if (isset($row['nilai_akhir'])): ?>
                                                    <span>Smt <?= $row['semester'] ?? '-' ?>: <b><?= e(format_dua_desimal_ganjil($row['nilai_akhir'])) ?></b> (<?= $row['grade'] ?? '-' ?>)</span>
                                                <?php else: ?>
                                                    <span style="color: #94A3B8;">-</span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="petikan-grade-cell"><?= isset($row['grade']) ? e($row['grade']) : '-' ?></td>
                                        <td style="text-align: center; font-weight: 700; color: #0F172A;">
                                            <?= isset($row['nilai_akhir']) ? $row['sksn'] : '-' ?>
                                        </td>
                                    </tr>
                                    <?php
                                    if (!isset($row['nilai_akhir'])) {
                                    } elseif ($row['grade'] == "E") {
                                        if ($row['jumlah_data'] == 0) {
                                            $sks = ($sks + $row['sks']) - ($row['sks_teori'] + $row['sks_praktek'] + $row['sks_praktikum']);
                                            $sksn = $sksn + $row['sksn'];
                                        }
                                    } else {
                                        $sks = $sks + $row['sks'];
                                        $sksn = $sksn + $row['sksn'];
                                    }
                                    if (isset($row['semester']) && isset($row['nilai_akhir'])) {
                                        $sksn_final = $sksn_final + $row['sksn'];
                                        $sks_final = $sks_final + $row['sks'];
                                    }
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 20px; color: #64748B;">
                                        <em>Tidak ada data nilai untuk semester ini.</em>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="petikan-table-card" style="padding: 36px 24px; text-align: center;">
            <div style="font-size: 34px; color: #CBD5E1; margin-bottom: 8px;"><i class="fa fa-folder-open-o"></i></div>
            <h4 style="font-size: 15px; font-weight: 800; color: #0F172A; margin: 0 0 4px;">Data Petikan Nilai Tidak Ditemukan</h4>
            <p style="font-size: 12px; color: #64748B; margin: 0;">Data petikan nilai belum tersedia atau kurikulum belum ditentukan.</p>
        </div>
    <?php endif; ?>

    <!-- 4. CUMULATIVE SUMMARY BENTO GRID -->
    <div class="petikan-summary-grid">
        <div class="petikan-summary-card">
            <span class="petikan-summary-label">Jumlah SKS yang Diambil</span>
            <span class="petikan-summary-val is-navy"><?= $sks_final ?> <span style="font-size: 14px; font-weight: 600; color: #64748B;">SKS</span></span>
            <p class="petikan-summary-sub">Total akumulasi kredit yang telah ditempuh</p>
        </div>

        <div class="petikan-summary-card">
            <span class="petikan-summary-label">Total Bobot (SKSN)</span>
            <span class="petikan-summary-val"><?= $sksn_final ?></span>
            <p class="petikan-summary-sub">Jumlah nilai mutu seluruh matakuliah</p>
        </div>

        <div class="petikan-summary-card">
            <span class="petikan-summary-label">Indeks Prestasi Kumulatif (IPK)</span>
            <span class="petikan-summary-val" style="color: #15803D;">
                <?= $sks_final == 0 ? '0.00' : format_ipk($sksn_final / $sks_final) ?>
            </span>
            <p class="petikan-summary-sub">Rasio akumulasi SKSN terhadap SKS kelulusan</p>
        </div>
    </div>

</div>
