<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file-text-o"></i> Petikan Nilai</h3>
        <div class="box-tools">
            <a href="<?= site_url('mahasiswa/Petikan_nilai/cetak') ?>" class="btn btn-default btn-sm">
                <i class="fa fa-download"></i> Versi Sebelumnya
            </a>
            <a href="<?= site_url('mahasiswa/Petikan_nilai/cetak_now') ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-download"></i> Versi Terbaru
            </a>
        </div>
    </div>
    <div class="box-body">
        <p class="mhs-subtitle">
            SEMESTER <?= $tahun_akademik->semester % 2 == (0) ? "GENAP" : "GANJIL"; ?> TA. <?= e($tahun_akademik->ta) ?>
            &nbsp;&bull;&nbsp; Angkatan 20<?= e(substr($mahasiswa->nim, 0, 2)) ?>
        </p>

        <div class="row mhs-info">
            <div class="col-sm-6">
                <div class="mhs-info-card">
                    <div class="mhs-info-row"><span class="mhs-info-label">Nama Mahasiswa</span><span class="mhs-info-value"><?= e($mahasiswa->nama_mahasiswa) ?></span></div>
                    <div class="mhs-info-row"><span class="mhs-info-label">NIM</span><span class="mhs-info-value"><?= e($mahasiswa->nim) ?></span></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mhs-info-card">
                    <div class="mhs-info-row"><span class="mhs-info-label">Program Studi</span><span class="mhs-info-value"><?= e($prodi->nama_program_studi) ?></span></div>
                    <div class="mhs-info-row"><span class="mhs-info-label">Fakultas</span><span class="mhs-info-value"><?= e($prodi->nama_fakultas) ?></span></div>
                </div>
            </div>
        </div>

        <?php
        $sks_final = 0;
        $sksn_final = 0;
        $data = (isset($data) && is_iterable($data)) ? $data : [];
        if (!empty($data)) :
            foreach ($data as $key) :
            ?>
            <div class="mhs-semester-label">Semester <?= $key['semester'] ?></div>
            <div class="table-responsive mhs-table-wrap">
                <table class="table mhs-table">
                    <thead>
                        <tr>
                            <th class="mhs-c-no">No.</th>
                            <th class="mhs-c-kode">Kode MK</th>
                            <th class="mhs-c-mk">Matakuliah</th>
                            <th class="mhs-c-num">SKS</th>
                            <th class="mhs-c-na">NA</th>
                            <th class="mhs-c-num">Grade</th>
                            <th class="mhs-c-num">SKSN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($key['data_nilai'])) :
                            $sks = 0;
                            $sksn = 0;
                            $j = 1;
                            foreach ($key['data_nilai'] as $row) :
                                ?>
                                <tr>
                                    <td class="mhs-num"><?= $j++ ?></td>
                                    <td class="mhs-kode"><?= e($row['kode_matakuliah']) ?></td>
                                    <td><?= e($row['nama_matakuliah']) ?></td>
                                    <td class="mhs-num"><?= isset($row['nilai_akhir']) ? $row['sks'] : '-' ?></td>
                                    <td style="font-size: 11px;">
                                        <?php if (isset($row['attempts']) && count($row['attempts']) > 0): ?>
                                            <?php foreach ($row['attempts'] as $att): ?>
                                                Smt <?= $att['semester'] ?>: <?= number_format($att['nilai_akhir'], 2) ?> (<?= $att['grade'] ?>)<br>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <?php if (isset($row['nilai_akhir'])): ?>
                                                Smt <?= $row['semester'] ?? '-' ?>: <?= number_format($row['nilai_akhir'], 2) ?> (<?= $row['grade'] ?? '-' ?>)
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="mhs-num"><?= isset($row['grade']) ? $row['grade'] : '-' ?></td>
                                    <td class="mhs-num"><?= isset($row['nilai_akhir']) ? $row['sksn'] : '-' ?></td>
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
                            <?php endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
        <?php else : ?>
            <div class="alert alert-info text-center" style="margin: 20px 0;">
                <i class="fa fa-info-circle"></i> Data petikan nilai belum tersedia atau kurikulum belum ditentukan.
            </div>
        <?php endif; ?>

        <div class="row mhs-bottom">
            <div class="col-md-6 col-md-offset-6 col-sm-12">
                <div class="mhs-summary">
                    <div class="mhs-summary-row"><span>Jumlah SKS yang diambil</span><strong><?= $sks_final ?></strong></div>
                    <div class="mhs-summary-row"><span>Jumlah SKSN</span><strong><?= $sksn_final ?></strong></div>
                    <div class="mhs-summary-row"><span>IPK</span><strong><?= $sks_final == 0 ? '0' : number_format($sksn_final / $sks_final, 2) ?></strong></div>
                </div>
            </div>
        </div>
    </div>
</div>
