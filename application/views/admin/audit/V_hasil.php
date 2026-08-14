<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-header">
            <h4><i class="fa fa-table"></i> Hasil Audit Nilai Dosen vs KHS</h4>
            <small>Program Studi: <?= e($prodi->nama_program_studi) ?> | Tahun Akademik: <?= e($tahun_akademik->ta) ?> - <?= $tahun_akademik->semester == 1 ? 'Ganjil' : 'Genap' ?></small>
        </div>
        <div class="box-body">
            <?php if (count($data) > 0) : ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Matakuliah</th>
                            <th>Kelas</th>
                            <th>Level</th>
                            <th>NA Dosen</th>
                            <th>NA KHS</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($data as $row) : ?>
                        <tr class="<?= $row->is_mbkm ? 'success' : ($row->status == 'Tidak Sinkron' ? 'danger' : '') ?>">
                            <td align="center"><?= $no++ ?></td>
                            <td><?= e($row->nim) ?></td>
                            <td><?= e($row->nama_mahasiswa) ?></td>
                            <td><?= e($row->kode_matakuliah) ?> - <?= e($row->nama_matakuliah) ?></td>
                            <td><?= e($row->nama_kelas) ?></td>
                            <td align="center"><?= e($row->level) ?></td>
                            <td align="center"><?= e($row->na) ?></td>
                            <td align="center"><?= e($row->nilai_akhir) ?></td>
                            <td align="center">
                                <?php if ($row->is_mbkm) : ?>
                                    <span class="label label-success"><?= e($row->status) ?></span>
                                <?php elseif ($row->status == 'Tidak Sinkron') : ?>
                                    <span class="label label-danger">Tidak Sinkron</span>
                                <?php else : ?>
                                    <span class="label label-success">Sinkron</span>
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
                <p>Tidak ada data audit untuk filter yang dipilih.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>