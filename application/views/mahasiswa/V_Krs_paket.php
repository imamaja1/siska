<?php
$nim = $this->session->userdata('nim');
$nama = $this->session->userdata('nama_mahasiswa');

$prodi_nama = isset($prodi->nama_program_studi) ? $prodi->nama_program_studi : '';
$jenjang_nama = isset($jenjang) ? $jenjang : '';

$prodi_display = $prodi_nama;
if (!empty($jenjang_nama) && !empty($prodi_nama)) {
    $prodi_display = (stripos($prodi_nama, $jenjang_nama) === 0) ? $prodi_nama : trim($jenjang_nama . ' ' . $prodi_nama);
} elseif (!empty($jenjang_nama)) {
    $prodi_display = $jenjang_nama;
}

$semester_label = (isset($tahun_akademik->semester) && $tahun_akademik->semester % 2 == 0) ? 'Genap' : 'Ganjil';
$ta_label = isset($tahun_akademik->tahun_akademik) ? $tahun_akademik->tahun_akademik : '';

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

$ips_lalu = isset($jumlah_maksimum_sks['ip_semester_lalu']) ? number_format((float)$jumlah_maksimum_sks['ip_semester_lalu'], 2) : '0.00';
$max_sks = isset($jumlah_maksimum_sks['beban_sks']) ? (int)$jumlah_maksimum_sks['beban_sks'] : 24;
?>

<div class="mhs-krs-page">

    <?= $this->session->flashdata('info') ? $this->session->flashdata('info') : '' ?>

    <!-- 1. Master Page Header Card -->
    <div class="krs-header-card">
        <div class="krs-header-left">
            <div class="krs-header-icon">
                <i class="fa fa-calendar-check-o"></i>
            </div>
            <div>
                <h2 class="krs-header-title">Pengisian KRS Paket | Semester <?= e($semester_label) ?></h2>
                <p class="krs-header-sub">
                    Semester <?= e($semester_label) ?> <?= $ta_label !== '' ? 'TA. ' . e($ta_label) : '' ?><?= !empty($prodi_display) ? ' | ' . e($prodi_display) : '' ?>
                </p>
            </div>
        </div>
        <div class="krs-period-badge">
            PERIODE PENGISIAN DIBUKA
        </div>
    </div>

    <!-- 2. Student Context Strip with SKS Quota Indicator -->
    <div class="krs-meta-strip">
        <div class="krs-student-info">
            <div class="krs-avatar-init"><?= $initials ?></div>
            <div class="krs-student-details">
                <h3 class="krs-student-name"><?= e($nama) ?></h3>
                <p class="krs-student-sub">
                    NIM: <b><?= e($nim) ?></b><?php if (!empty($prodi_display)) : ?> | <?= e($prodi_display) ?><?php endif; ?><?php if (!empty($prodi->nama_fakultas)) : ?>, <?= e($prodi->nama_fakultas) ?><?php endif; ?>
                </p>
            </div>
        </div>

        <!-- SKS Quota Indicator -->
        <div class="krs-stat-box">
            <div class="krs-stat-unit">
                <span class="krs-stat-label">Sistem</span>
                <span class="krs-stat-value">Paket SKS</span>
            </div>
            <div class="krs-stat-divider"></div>
            <div class="krs-stat-unit">
                <span class="krs-stat-label">Batas Maksimal</span>
                <span class="krs-stat-value is-navy"><?= e($max_sks) ?> SKS</span>
            </div>
        </div>
    </div>

    <!-- 3. Semester Selector Tabs -->
    <div class="krs-tabs-row">
        <div class="krs-tabs-list">
            <?php if (!empty($krs_mhs) && is_array($krs_mhs)): ?>
                <?php foreach ($krs_mhs as $row): ?>
                    <a href="<?= site_url('mahasiswa/krs/old/' . $row->semester) ?>" class="krs-tab-item">
                        <i class="fa fa-calendar"></i>
                        <span><?= ($row->semester == 'K') ? 'Konversi' : 'Semester ' . e($row->semester) ?></span>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
            <span class="krs-tab-item is-active">
                <i class="fa fa-calendar"></i>
                <span>Semester <?= e($semester) ?> (Aktif)</span>
            </span>
        </div>
    </div>

    <!-- 4. Form Pengisian KRS & Table Section -->
    <form id="form-krs-mahasiswa" action="<?= site_url('mahasiswa/Krs/simpan_krs') ?>" method="post" name="krs_form">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

        <?php if (!empty($data_matakuliah) && is_array($data_matakuliah)): ?>
            <?php foreach ($data_matakuliah as $row): ?>
                <?php if (count($row['data']) > 0): ?>
                    <div class="krs-table-card">
                        <div class="krs-table-head-bar">
                            <span class="krs-table-title">SEMESTER <?= e($row['semester']) ?></span>
                        </div>
                        <div class="table-responsive">
                            <table class="table krs-table-grid">
                                <thead>
                                    <tr>
                                        <th style="width: 50px; text-align: center;">NO.</th>
                                        <th style="width: 140px;">KODE MK</th>
                                        <th>MATAKULIAH</th>
                                        <th style="width: 60px; text-align: center;">T</th>
                                        <th style="width: 60px; text-align: center;">PK</th>
                                        <th style="width: 60px; text-align: center;">PT</th>
                                        <th style="width: 70px; text-align: center;">B</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($row['data'] as $d):
                                    ?>
                                        <tr>
                                            <td style="text-align: center; color: #64748B;"><?= $i++ ?>.</td>
                                            <td id="kode-matakuliah-<?= e($d['kode_nama_kurikulum']) ?>"><strong><?= e($d['kode_matakuliah']) ?></strong></td>
                                            <td><?= e($d['nama_matakuliah']) ?></td>
                                            <td style="text-align: center;"><?= $d['sks_teori'] ?></td>
                                            <td style="text-align: center; color: <?= $d['sks_praktek'] > 0 ? '#0F172A' : '#64748B' ?>;"><?= $d['sks_praktek'] ?></td>
                                            <td style="text-align: center; color: <?= $d['sks_praktikum'] > 0 ? '#0F172A' : '#64748B' ?>;"><?= $d['sks_praktikum'] ?></td>
                                            <td style="text-align: center;">
                                                <input type="checkbox" id="cek-<?= $d['id_matakuliah'] ?>" class="check-kpat" checked onclick="return false" name="baru[]" value="<?= $d['id_matakuliah'] ?>" title="Paket Baru">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php if (isset($row['pilihan'])): ?>
                                        <tr class="krs-row-pilihan">
                                            <td colspan="7">Matakuliah Pilihan</td>
                                        </tr>
                                        <?php foreach ($row['pilihan'] as $pilih): ?>
                                            <tr>
                                                <td style="text-align: center; color: #64748B;"><?= $i++ ?>.</td>
                                                <td id="kode-matakuliah-<?= e($pilih['kode_nama_kurikulum']) ?>"><strong><?= e($pilih['kode_matakuliah']) ?></strong></td>
                                                <td><?= e($pilih['nama_matakuliah']) ?></td>
                                                <td style="text-align: center;"><?= $pilih['sks_teori'] ?></td>
                                                <td style="text-align: center; color: <?= $pilih['sks_praktek'] > 0 ? '#0F172A' : '#64748B' ?>;"><?= $pilih['sks_praktek'] ?></td>
                                                <td style="text-align: center; color: <?= $pilih['sks_praktikum'] > 0 ? '#0F172A' : '#64748B' ?>;"><?= $pilih['sks_praktikum'] ?></td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" id="cek-<?= $pilih['id_matakuliah'] ?>" class="check-kpat" checked onclick="return false" name="baru[]" value="<?= $pilih['id_matakuliah'] ?>" title="Paket Baru">
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- 5. Submission Bar Card -->
        <div class="krs-submit-card">
            <div id="kotak-pesan" class="krs-live-status is-ok">
                <i class="fa fa-check-circle"></i>
                <span class="krs-status-text">Matakuliah paket semester telah disiapkan secara otomatis. Klik tombol di kanan untuk mengajukan.</span>
            </div>

            <div class="krs-submit-actions">
                <div id="loading" class="krs-loading-wrap">
                    <button type="submit" name="submit" id="submit" class="krs-btn-submit">
                        <i class="fa fa-send"></i> Ajukan KRS ke Dosen Wali &rarr;
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- 6. Legend / Keterangan Card -->
    <div class="krs-legend-card">
        <div class="krs-legend-group">
            <span class="krs-legend-title">Status Pengambilan:</span>
            <span class="krs-legend-pill"><strong>B</strong> Paket Baru</span>
        </div>
        <div class="krs-legend-divider"></div>
        <div class="krs-legend-group">
            <span class="krs-legend-title">Jenis SKS:</span>
            <span class="krs-legend-pill"><strong>T</strong> Teori</span>
            <span class="krs-legend-pill"><strong>PK</strong> Praktek</span>
            <span class="krs-legend-pill"><strong>PT</strong> Praktikum</span>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#form-krs-mahasiswa').bind('submit', function (e) {
        var button = $('#loading');
        button.html('<button class="krs-btn-submit" disabled><i class="fa fa-refresh fa-spin"></i> Permintaan sedang diproses...</button>');
    });
</script>
