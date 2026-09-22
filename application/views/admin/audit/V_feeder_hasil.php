<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-header">
            <h4><i class="fa fa-table"></i> <?= e($judul_hasil) ?></h4>
            <small><?= e($sub_hasil) ?> | ID Semester Feeder: <?= e($id_semester) ?></small>
        </div>
        <div class="box-body">
            <?php $summary = $hasil['summary']; ?>
            <div class="row" style="margin-bottom: 10px;">
                <div class="col-sm-3 col-xs-6">
                    <span class="label label-success">Sesuai: <?= (int) $summary['sesuai'] ?></span>
                </div>
                <div class="col-sm-3 col-xs-6">
                    <span class="label label-danger">Berbeda: <?= (int) $summary['berbeda'] ?></span>
                </div>
                <div class="col-sm-3 col-xs-6">
                    <span class="label label-warning">Tidak ada di Feeder: <?= (int) $summary['tidak_ada'] ?></span>
                </div>
                <div class="col-sm-3 col-xs-6">
                    <span class="label label-default">Nilai SISKA kosong: <?= (int) $summary['kosong'] ?></span>
                </div>
            </div>

            <?php if (!empty($hasil['feeder_error'])) : ?>
                <div class="callout callout-warning flat">
                    <p><i class="fa fa-exclamation-triangle"></i> Catatan Feeder: <?= e($hasil['feeder_error']) ?></p>
                </div>
            <?php elseif (empty($hasil['feeder_total'])) : ?>
                <div class="callout callout-info flat">
                    <p><i class="fa fa-info-circle"></i> Tidak ada data nilai pada Feeder untuk semester ini. Pastikan ID semester (<?= e($id_semester) ?>) tersedia di Feeder.</p>
                </div>
            <?php endif; ?>

            <?php if (empty($hasil['feeder_keys']) && !empty($hasil['feeder_error'])) : ?>
                <div class="callout callout-info flat">
                    <p>Field respons Feeder tidak dapat dikenali. Jalankan Test Koneksi di Konfig Feeder lalu periksa format respons.</p>
                </div>
            <?php endif; ?>

            <?php if (count($hasil['rows']) > 0) : ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Matakuliah</th>
                            <th>Kelas</th>
                            <th>Nilai SISKA (Angka)</th>
                            <th>Nilai SISKA (Huruf)</th>
                            <th>Nilai Feeder (Angka)</th>
                            <th>Nilai Feeder (Huruf)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($hasil['rows'] as $row) : ?>
                        <tr class="<?= $row->status == 'Berbeda' ? 'danger' : '' ?>">
                            <td align="center"><?= $no++ ?></td>
                            <td><?= e($row->nim) ?></td>
                            <td><?= e($row->nama_mahasiswa) ?></td>
                            <td><?= e($row->kode_matakuliah) ?> - <?= e($row->nama_matakuliah) ?></td>
                            <td><?= !empty($row->nama_kelas) ? e($row->nama_kelas) : '-' ?></td>
                            <td align="center"><?= $row->nilai_siska !== NULL && $row->nilai_siska !== '' ? e($row->nilai_siska) : '-' ?></td>
                            <td align="center"><?= !empty($row->nilai_huruf_siska) ? e($row->nilai_huruf_siska) : '-' ?></td>
                            <td align="center"><?= $row->nilai_angka !== NULL && $row->nilai_angka !== '' ? e($row->nilai_angka) : '-' ?></td>
                            <td align="center"><?= $row->nilai_huruf !== NULL && $row->nilai_huruf !== '' ? e($row->nilai_huruf) : '-' ?></td>
                            <td align="center">
                                <?php if ($row->status == 'Sesuai') : ?>
                                    <span class="label label-success">Sesuai</span>
                                <?php elseif ($row->status == 'Berbeda') : ?>
                                    <span class="label label-danger">Berbeda</span>
                                <?php elseif ($row->status == 'Tidak ada di Feeder') : ?>
                                    <span class="label label-warning">Tidak ada di Feeder</span>
                                <?php else : ?>
                                    <span class="label label-default"><?= e($row->status) ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else : ?>
            <div class="callout callout-info flat">
                <h4><i class="fa fa-info-circle"></i> Informasi!</h4>
                <p>Tidak ada data nilai untuk filter yang dipilih.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
