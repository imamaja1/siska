<?php
$grand_total_sks = 0;
if (!empty($data)) {
    foreach ($data as $sem_row) {
        if (!empty($sem_row['data'])) {
            foreach ($sem_row['data'] as $sub) {
                $grand_total_sks += (int)$sub->sks_teori + (int)$sub->sks_praktek + (int)$sub->sks_praktikum;
            }
        }
    }
}
$prodi_name = $prodi->nama_program_studi ?? $this->session->userdata('program_studi') ?? 'Program Studi';
$kur_name = $kurikulum->nama_kurikulum ?? 'Berjalan';
?>

<div class="kurikulum-wrapper">

    <!-- 1. UNIFIED PAGE HEADER CARD -->
    <div class="kurikulum-header-card">
        <div>
            <h2 class="kurikulum-title">Kurikulum Matakuliah - <?= e($prodi_name) ?> (<?= e($kur_name) ?>)</h2>
            <p class="kurikulum-sub">Daftar Sebaran Matakuliah &amp; Beban Studi SKS Berdasarkan Kurikulum Berjalan</p>
        </div>
    </div>

    <!-- 2. PROGRAM STUDI INFO STRIP -->
    <div class="kurikulum-meta-strip">
        <div class="kurikulum-brand-group">
            <div class="kurikulum-brand-badge">UBG</div>
            <div class="kurikulum-program-info">
                <h3 class="kurikulum-program-title"><?= e($prodi_name) ?></h3>
                <p class="kurikulum-program-sub">
                    <?= !empty($prodi->nama_fakultas) ? e($prodi->nama_fakultas) . ', ' : '' ?>Universitas Bumigora<?= !empty($prodi->gelar) ? ' (Gelar: ' . e($prodi->gelar) . ')' : '' ?>
                </p>
            </div>
        </div>

        <div class="kurikulum-stat-box">
            <div class="kurikulum-stat-unit">
                <span class="kurikulum-stat-label">Total Beban Kelulusan</span>
                <span class="kurikulum-stat-val is-navy"><?= $grand_total_sks > 0 ? $grand_total_sks : 144 ?> SKS</span>
            </div>
            <div class="kurikulum-stat-divider"></div>
            <div class="kurikulum-stat-unit">
                <span class="kurikulum-stat-label">Masa Studi Normal</span>
                <span class="kurikulum-stat-val">8 Semester (4 Tahun)</span>
            </div>
            <div class="kurikulum-stat-divider"></div>
            <div class="kurikulum-stat-unit">
                <span class="kurikulum-stat-label">Status Kurikulum</span>
                <span class="kurikulum-chip-active">BERLAKU AKTIF</span>
            </div>
        </div>
    </div>

    <!-- 3. CURRICULUM TABLES PER SEMESTER -->
    <?php if (!empty($data) && count($data) > 0) : ?>
        <?php foreach ($data as $row) : ?>
            <?php
            $items = $row['data'] ?? [];
            if (empty($items)) {
                continue;
            }
            $sem_t = 0; $sem_pk = 0; $sem_pt = 0;
            foreach ($items as $item_val) {
                $sem_t  += (int)$item_val->sks_teori;
                $sem_pk += (int)$item_val->sks_praktek;
                $sem_pt += (int)$item_val->sks_praktikum;
            }
            $sem_total_sks = $sem_t + $sem_pk + $sem_pt;
            ?>
            <div class="kurikulum-table-card">
                <div class="kurikulum-table-head-bar">
                    <h4 class="kurikulum-table-title">Daftar Struktur Matakuliah Semester <?= e($row['semester']) ?></h4>
                    <span class="kurikulum-table-sub">
                        <?= count($items) ?> Matakuliah (<?= $sem_total_sks ?> SKS)
                    </span>
                </div>

                <div class="table-responsive" style="margin: 0; border: none;">
                    <table class="kurikulum-table-grid">
                        <thead>
                            <tr>
                                <th style="width: 50px; text-align: center;">NO</th>
                                <th style="width: 140px; text-align: center;">KODE MK</th>
                                <th>NAMA MATA KULIAH</th>
                                <th style="width: 90px; text-align: center;">TEORI</th>
                                <th style="width: 90px; text-align: center;">PRAKTEK</th>
                                <th style="width: 90px; text-align: center;">PRAKTIKUM</th>
                                <th style="width: 95px; text-align: center;">TOTAL SKS</th>
                                <th style="width: 130px; text-align: center;">SIFAT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($items as $d) : ?>
                                <?php
                                $sub_total = (int)$d->sks_teori + (int)$d->sks_praktek + (int)$d->sks_praktikum;
                                $is_pilihan_kompetensi = in_array($d->id_matakuliah, $mk_pilihan);
                                $is_pilihan_umum = ($d->jenis == 1);
                                ?>
                                <tr>
                                    <td style="text-align: center; color: #64748B; font-weight: 600;"><?= $i++ ?>.</td>
                                    <td style="text-align: center;" class="kurikulum-td-code"><?= e($d->kode_matakuliah) ?></td>
                                    <td class="kurikulum-td-name">
                                        <?= e($d->nama_matakuliah) ?>
                                        <?php if (!empty($nama_pilihan[$d->id_matakuliah])) : ?>
                                            <span style="font-size: 11px; color: #B45309; font-weight: 600;">(<?= e($nama_pilihan[$d->id_matakuliah]) ?>)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center; color: #334155;">
                                        <?= (int)$d->sks_teori > 0 ? (int)$d->sks_teori . ' SKS' : '0' ?>
                                    </td>
                                    <td style="text-align: center; color: #334155;">
                                        <?= (int)$d->sks_praktek > 0 ? (int)$d->sks_praktek . ' SKS' : '0' ?>
                                    </td>
                                    <td style="text-align: center; color: #334155;">
                                        <?= (int)$d->sks_praktikum > 0 ? (int)$d->sks_praktikum . ' SKS' : '0' ?>
                                    </td>
                                    <td style="text-align: center; font-weight: 800; color: #0F172A;">
                                        <?= $sub_total ?> SKS
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if ($is_pilihan_umum) : ?>
                                            <span class="kurikulum-badge-pilihan">Pilihan</span>
                                        <?php elseif ($is_pilihan_kompetensi) : ?>
                                            <span class="kurikulum-badge-pilihan">Pilihan</span>
                                        <?php else : ?>
                                            <span class="kurikulum-badge-wajib">Wajib</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="kurikulum-table-footer-bar">
                    <div>
                        Total Beban Semester <?= e($row['semester']) ?>: <b><?= count($items) ?> Matakuliah</b>
                    </div>
                    <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                        <span>Teori: <b><?= $sem_t ?> SKS</b>, Praktek: <b><?= $sem_pk ?> SKS</b>, Lab: <b><?= $sem_pt ?> SKS</b></span>
                        <span class="kurikulum-total-pill">Total: <?= $sem_total_sks ?> SKS</span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="kurikulum-table-card" style="padding: 36px 24px; text-align: center;">
            <div style="font-size: 34px; color: #CBD5E1; margin-bottom: 8px;"><i class="fa fa-folder-open-o"></i></div>
            <h4 style="font-size: 15px; font-weight: 800; color: #0F172A; margin: 0 0 4px;">Data Kurikulum Tidak Ditemukan</h4>
            <p style="font-size: 12px; color: #64748B; margin: 0;">Kurikulum untuk program studi ini belum tersedia atau belum diaktifkan.</p>
        </div>
    <?php endif; ?>

</div>
