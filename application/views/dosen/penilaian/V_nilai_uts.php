<div class="row">
    <div class="col-md-12">
        <div class="box box-solid flat">
            <div class="box-body">
                <div class="pull-right">
                    <a href="<?= site_url('dosen/penilaian/penilaian_uts') ?>" class="btn btn-success btn-xs flat"><i
                            class="fa fa-arrow-circle-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box box-info flat">
            <div class="box-header with-border">
                <h3 class="box-title"><b> KELAS - <?= $data_kelas->nama_kelas ?> </b>
                    (<?= $data_kelas->kode_matakuliah ?> - <?= $data_kelas->nama_matakuliah ?>)</h3>
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
                                    <th style="text-align: center">NILAI UTS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($data as $row) :
                                    ?>
                                    <tr>
                                        <td style="text-align: center; width: 3%"><?= $no++ ?>.</td>
                                        <td style="text-align: center"><?= $row->nim ?></td>
                                        <td><?= $row->nama_mahasiswa ?></td>
                                        <td><?= $row->dummy_uts ?></td>
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
                                    <th style="text-align: center">Prodi</th>
                                    <th style="text-align: center">Dekan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="text-align: center">
                                    <td width="50%">Status Validasi</td>
                                    <td width="50%">Status Validasi</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center">
                                        <?php if ($data_kelas->validasi_nilai_uts == 'T') : ?>
                                            <span class="label label-success">Nilai Tervalidasi</span>
                                        <?php else: ?>
                                            <span class="label label-warning">Nilai Belum Tervalidasi</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td style="text-align: center">
                                        <?php if ($data_kelas->validasi_dekan_uts == 'T') : ?>
                                            <span class="label label-success">Nilai Tervalidasi</span>
                                        <?php else: ?>
                                            <span class="label label-warning">Nilai Belum Tervalidasi</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
