

<table class="table table-bordered demo-table">
    <thead>
        <tr>
            <th style="text-align: center;">Kode</th>
            <th>Matakuliah</th>

            <th>HARIAN</th>
            <th>UTS</th>
            <th>UAS</th>
            <th>Aksi</th>
        </tr>
        <tr>

        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($data as $key => $row):
            ?>
            <tr>

                <td align="center"><?= $row->kelas_id; ?></td>
                <td><?= $row->kode_matakuliah ?> - <?= $row->nama_matakuliah ?> Kelas - <?= $row->nama_kelas ?></td>

                <td><?= $row->nilai_harian ?></td>
                <td><?= $row->nilai_uts ?></td>
                <td><?= $row->nilai_uas ?></td>
                <td style="white-space: nowrap;width: 1px;">
                    <?php if (($row->nilai_harian == "") || ($row->nilai_uts == "") || ($row->nilai_uas == "")): ?>
                        <a class="btn btn-primary btn-xs btn-flat" href="#" data-toggle="modal" data-target="#isi_presentasi<?= $row->kelas_id ?>">
                            <i class="fa fa-arrow-circle-right"></i>
                            Isi Presentasi
                        </a>

                        <div class="modal fade" id="isi_presentasi<?= $row->kelas_id ?>" style="display: none;">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">

                                    <form action="<?= site_url('dosen/penilaian/store_persentasi_penilaian') ?>" method="post">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                            <h4 class="modal-title"><i class="fa fa-info-circle"></i> Input Persentasi Nilai</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div id="flash-info">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Nilai Harian <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="hidden" name="kelas_id" value="<?= $row->kelas_id ?>">
                                                    <input type="number" class="form-control " maxlength="3" min="0" max="100" required
                                                           name="nilai_harian" id="nilai_harian" placeholder="_%">
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Nilai UTS <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control " maxlength="3" min="0" max="100" required
                                                           name="nilai_uts" id="nilai_uts" placeholder="_%">
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Nilai UAS <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" maxlength="3" min="0" max="100" required
                                                           name="nilai_uas" id="nilai_uas" placeholder="_%">
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </div>
                                            <div class="text-danger" id="pesan_kesalahan"></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>
                                                Tutup
                                            </button>
                                            <button type="submit" id="simpan_presentasi_nilai_dosen" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <a class="btn btn-info btn-xs btn-flat" href="<?= site_url('dosen/penilaian/isi_default/' . $row->kelas_id) ?>">
                            <i class="fa fa-arrow-circle-right"></i>
                            Isi Default
                        </a>
                    <?php else: ?>
                        <a class="btn btn-warning btn-xs btn-flat" href="#" data-toggle="modal" data-target="#update_presentasi<?= $row->kelas_id ?>">
                            <i class="fa fa-arrow-circle-right"></i>
                            Update Presentasi
                        </a>
                        <div class="modal fade" id="update_presentasi<?= $row->kelas_id ?>" style="display: none;">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">

                                    <form action="<?= site_url('dosen/penilaian/udpate_persentasi_penilaian/'.$row->id) ?>" method="post">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                            <h4 class="modal-title"><i class="fa fa-info-circle"></i> Input Persentasi Nilai</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div id="flash-info">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Nilai Harian <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="hidden" name="kelas_id" value="<?= $row->kelas_id ?>">
                                                    <input type="number" class="form-control " maxlength="3" min="0" max="100" required
                                                           name="nilai_harian" id="nilai_harian" placeholder="%" value="<?= $row->nilai_harian ?>" >
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Nilai UTS <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control " maxlength="3" min="0" max="100" required
                                                           name="nilai_uts" id="nilai_uts" placeholder="_%" value="<?= $row->nilai_uts ?>">
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Nilai UAS <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" maxlength="3" min="0" max="100" required
                                                           name="nilai_uas" id="nilai_uas" placeholder="_%" value="<?= $row->nilai_uas ?>">
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </div>
                                            <div class="text-danger" id="pesan_kesalahan"></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>
                                                Tutup
                                            </button>
                                            <button type="submit" id="simpan_presentasi_nilai_dosen" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>