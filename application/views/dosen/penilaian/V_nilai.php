<div class="row">
    <div class="col-md-12">
        <div class="box box-solid flat">
            <div class="box-body">
                <div class="pull-right">
                    <a href="<?= site_url('dosen/penilaian') ?>" class="btn btn-success btn-xs flat"><i
                            class="fa fa-arrow-circle-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box box-info flat">
            <div class="box-header with-border">
                <h3 class="box-title"><b> KELAS - <?= e($data_kelas->nama_kelas) ?> </b>
                    (<?= e($data_kelas->kode_matakuliah) ?> - <?= e($data_kelas->nama_matakuliah) ?>)</h3>
            </div>



            <div class="box-body">
                <?php if (count($data) > 0) : ?>
                    <div class="table-responsive">
                        <table class="table demo-table">
                            <thead>
                                <tr style="background-color: #00c0ef">
                                    <th style="text-align: center">NO.</th>
                                    <th style="text-align: center">NIM</th>
                                    <th style="text-align: center">NAMA</th>
                                    <th style="text-align: center">NILAI HARIAN</th>
                                    <th style="text-align: center">NILAI UTS</th>
                                    <th style="text-align: center">NILAI UAS</th>
                                    <th style="text-align: center">NILAI AKHIR</th>
                                    <th style="text-align: center">GRADE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($data as $row) :
                                    ?>
                                    <tr>
                                        <td style="text-align: center; width: 3%"><?= $no++ ?>.</td>
                                        <td style="text-align: center"><?= e($row->nim) ?></td>
                                        <td><?= e($row->nama_mahasiswa) ?></td>
                                        <td><?= e($row->dummy_harian) ?></td>
                                        <td><?= e($row->dummy_uts) ?></td>
                                        <td><?= e($row->dummy_uas) ?></td>
                                        <td><?= e(round($row->dummy_na)) ?></td>
                                        <td><?= e($row->grade) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table demo-table">
                            <thead>
                                <tr>
                                    <th colspan="2" style="text-align: center">Prodi</th>
                                    <th colspan="2" style="text-align: center">Dekan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="text-align: center">
                                    <td width="15%">Status Validasi</td>
                                    <td>Catatan</td>
                                    <td width="15%">Status Validasi</td>
                                    <td>Catatan</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center">
                                        <?php if ($data_kelas->validasi_nilai == 'T') : ?>
                                            <span class="label label-success">Nilai Tervalidasi</span>
                                        <?php else: ?>
                                            <span class="label label-warning">Nilai Belum Tervalidasi</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= e($data_kelas->catatan_prodi) ?></td>
                                    <td style="text-align: center">
                                        <?php if ($data_kelas->validasi_dekan == 'T') : ?>
                                            <span class="label label-success">Nilai Tervalidasi</span>
                                        <?php else: ?>
                                            <span class="label label-warning">Nilai Belum Tervalidasi</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= e($data_kelas->catatan_dekan) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>



        </div>
    </div>
</div>
