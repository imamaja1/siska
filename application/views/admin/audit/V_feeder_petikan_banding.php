<?php
/**
 * Partial tabel Perbandingan SISKA vs Feeder yang dikelompokkan per
 * tahun akademik + semester Feeder. Setiap nomor menampilkan 2 baris:
 * baris 1 = Feeder, baris 2 = SISKA.
 *
 * Variabel:
 *   $grup : ['tahun_akademik' => string, 'rows' => array]
 */
?>
<div class="table-responsive" style="margin-top: 10px;">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th colspan="10" class="bg-primary">
                    Tahun Akademik <?= e($grup['tahun_akademik']) ?>
                    <span class="label label-default"><?= count($grup['rows']) ?> MK</span>
                </th>
            </tr>
            <tr>
                <th width="40" class="text-center">No</th>
                <th width="80" class="text-center">Sumber</th>
                <th width="120" class="text-center">Kode MK</th>
                <th>Matakuliah</th>
                <th width="60" class="text-center">SKS</th>
                <th width="80" class="text-center">Nilai Angka</th>
                <th width="80" class="text-center">Nilai Huruf</th>
                <th width="80" class="text-center">Kelas</th>
                <th width="150" class="text-center">Semester</th>
                <th width="130" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($grup['rows'])) : ?>
            <tr><td colspan="10" class="text-center">Tidak ada data.</td></tr>
            <?php else : ?>
            <?php $no = 1; foreach ($grup['rows'] as $b) : ?>
            <?php
                $row_class = $b['status'] === 'Berbeda' ? 'danger' : ($b['status'] === 'Sesuai' ? '' : 'warning');
                $feeder_sks = ($b['feeder_sks'] !== NULL && $b['feeder_sks'] !== '') ? number_format((float) $b['feeder_sks'], 2) : '-';
                $siska_sks  = ($b['siska_sks'] !== NULL && $b['siska_sks'] !== '') ? number_format((float) $b['siska_sks'], 2) : '-';
            ?>
            <tr class="<?= $row_class ?>">
                <td class="text-center" rowspan="2"><?= $no++ ?></td>
                <td class="text-center"><span class="label label-info">Feeder</span></td>
                <td class="text-center"><?= $b['feeder_kode'] !== '' ? e($b['feeder_kode']) : '-' ?></td>
                <td>
                    <?php if ($b['feeder_nama'] !== '') : ?>
                        <?= e($b['feeder_nama']) ?>
                        <?php if (!empty($b['feeder_transfer'])) : ?>
                            <span class="label label-info">Transfer</span>
                        <?php endif; ?>
                        <?php if (!empty($b['matched_by']) && $b['matched_by'] === 'nama') : ?>
                            <span class="label label-default">Cocok nama</span>
                        <?php endif; ?>
                    <?php else : ?>
                        -
                    <?php endif; ?>
                </td>
                <td class="text-center"><?= e($feeder_sks) ?></td>
                <td class="text-center"><?= e($b['feeder_angka']) ?></td>
                <td class="text-center"><?= e($b['feeder_huruf']) ?></td>
                <td class="text-center"><?= $b['feeder_kelas'] !== '' ? e($b['feeder_kelas']) : '-' ?></td>
                <td class="text-center"><?= $b['feeder_semester'] !== '' ? e($b['feeder_semester']) : '-' ?></td>
                <td class="text-center" rowspan="2">
                    <?php if ($b['status'] === 'Sesuai') : ?>
                        <span class="label label-success">Sesuai</span>
                    <?php elseif ($b['status'] === 'Berbeda') : ?>
                        <span class="label label-danger">Berbeda</span>
                    <?php elseif ($b['status'] === 'SISKA belum ada nilai') : ?>
                        <span class="label label-warning">SISKA belum ada nilai</span>
                    <?php elseif ($b['status'] === 'Feeder belum ada nilai') : ?>
                        <span class="label label-info">Feeder belum ada nilai</span>
                    <?php elseif ($b['status'] === 'Tidak ada di SISKA') : ?>
                        <span class="label label-warning">Tidak ada di SISKA</span>
                    <?php elseif ($b['status'] === 'Tidak ada di Feeder') : ?>
                        <span class="label label-info">Tidak ada di Feeder</span>
                    <?php else : ?>
                        <span class="label label-info"><?= e($b['status']) ?></span>
                    <?php endif; ?>
                    <?php if ($b['status'] === 'SISKA belum ada nilai' && $b['siska_kode'] !== '' && $b['siska_kode_tahun_akademik'] !== '') : ?>
                        <br>
                        <button type="button" class="btn btn-xs btn-info flat btn-cek-dummy"
                            data-nim="<?= e(isset($nim) ? $nim : '') ?>"
                            data-ta="<?= e($b['siska_kode_tahun_akademik']) ?>"
                            data-kode="<?= e($b['siska_kode']) ?>">
                            <i class="fa fa-search"></i> Cari Dummy
                        </button>
                    <?php endif; ?>
                </td>
            </tr>
            <tr class="<?= $row_class ?>">
                <td class="text-center"><span class="label label-warning">SISKA</span></td>
                <td class="text-center"><?= $b['siska_kode'] !== '' ? e($b['siska_kode']) : '-' ?></td>
                <td>
                    <?php if ($b['siska_nama'] !== '') : ?>
                        <?= e($b['siska_nama']) ?>
                        <?php if (!empty($b['siska_konversi'])) : ?>
                            <span class="label label-primary">Konversi</span>
                        <?php endif; ?>
                    <?php else : ?>
                        -
                    <?php endif; ?>
                </td>
                <td class="text-center"><?= e($siska_sks) ?></td>
                <td class="text-center"><?= e($b['siska_angka']) ?></td>
                <td class="text-center"><?= e($b['siska_huruf']) ?></td>
                <td class="text-center"><?= $b['siska_kelas'] !== '' ? e($b['siska_kelas']) : '-' ?></td>
                <td class="text-center"><?= $b['siska_semester'] !== '' ? e($b['siska_semester']) : '-' ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
