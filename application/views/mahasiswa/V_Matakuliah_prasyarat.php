<?= $this->session->flashdata('info') ?>

<?php
$count_prasyarat = !empty($data_prasyarat) ? count($data_prasyarat) : 0;
$prodi_name = $prodi->nama_program_studi ?? $this->session->userdata('program_studi') ?? '';
$kur_name = $kurikulum->nama_kurikulum ?? '';
?>

<div class="prasyarat-wrapper">

    <!-- 1. UNIFIED PAGE HEADER CARD -->
    <div class="prasyarat-header-card">
        <div>
            <h2 class="prasyarat-title">Daftar Matakuliah Prasyarat</h2>
            <p class="prasyarat-sub">Ketentuan Syarat Kelulusan Matakuliah Sebelum Pengambilan Rencana Studi (KRS)</p>
        </div>
    </div>

    <!-- 2. NOTICE ALERT BANNER -->
    <div class="prasyarat-notice-banner">
        <div class="prasyarat-notice-icon">
            <i class="fa fa-info-circle"></i>
        </div>
        <div>
            <h4 class="prasyarat-notice-title">Aturan Pengambilan Matakuliah Berprasyarat</h4>
            <p class="prasyarat-notice-text">
                Mahasiswa wajib telah menyelesaikan dan lulus matakuliah prasyarat dengan nilai minimal C (Bobot 2.00) sebelum memprogram matakuliah lanjutan pada saat pengisian Kartu Rencana Studi (KRS).
            </p>
        </div>
    </div>

    <!-- 3. STANDARDIZED PREREQUISITE TABLE CARD -->
    <div class="prasyarat-table-card">
        <div class="prasyarat-table-head-bar">
            <h4 class="prasyarat-table-title">
                Ketentuan Prasyarat Matakuliah
                <?= !empty($prodi_name) ? ' - ' . e($prodi_name) : '' ?>
                <?= !empty($kur_name) ? ' (' . e($kur_name) . ')' : '' ?>
            </h4>
            <span class="prasyarat-table-badge">
                <?= $count_prasyarat ?> Ketentuan Matakuliah
            </span>
        </div>

        <div class="table-responsive" style="margin: 0; border: none;">
            <table class="prasyarat-table-grid">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">NO</th>
                        <th style="width: 140px; text-align: center;">KODE MK</th>
                        <th>MATA KULIAH LANJUTAN</th>
                        <th style="width: 140px; text-align: center;">KODE SYARAT</th>
                        <th>MATA KULIAH PRASYARAT</th>
                        <th style="width: 130px; text-align: center;">SYARAT MIN.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($count_prasyarat > 0) : ?>
                        <?php $i = 1; foreach ($data_prasyarat as $data) : ?>
                            <tr>
                                <td style="text-align: center; color: #64748B; font-weight: 600;"><?= $i++ ?>.</td>
                                <td style="text-align: center;" class="prasyarat-td-code"><?= e($data['matakuliah_yg_diambil']) ?></td>
                                <td class="prasyarat-td-name"><?= e($data['nama_matakuliah_yg_diambil']) ?></td>
                                <td style="text-align: center;" class="prasyarat-td-syarat-code"><?= e($data['matakuliah_prasyarat']) ?></td>
                                <td class="prasyarat-td-syarat-name"><?= e($data['nama_matakuliah_prasyarat']) ?></td>
                                <td style="text-align: center;">
                                    <span class="prasyarat-badge-min">Minimal C</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 32px; color: #64748B;">
                                <em>Tidak ada data matakuliah prasyarat untuk kurikulum ini.</em>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="prasyarat-table-footer-bar">
            <div>
                Total: <b><?= $count_prasyarat ?> Ketentuan Matakuliah Prasyarat</b>
            </div>
            <div>
                Sistem Informasi Akademik &mdash; Universitas Bumigora
            </div>
        </div>
    </div>

</div>
