<div class="row">
    <!-- <div class="col-md-12">
        <div class="box box-solid flat">
            <div class="box-body">
                <div class="pull-right">
                    <a href="<?= site_url('dosen/kaprodi/validasinilai/validasi_nilai_revisi') ?>" class="btn btn-success btn-xs flat"><i
                            class="fa fa-arrow-circle-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div> -->
    <div class="col-md-12">
        <div class="box box-info flat">
            <div class="box-header with-border">
                <h3 class="box-title"><b> KELAS - <?= e($data_kelas->nama_kelas) ?> </b>
                    (<?= e($data_kelas->kode_matakuliah) ?> - <?= e($data_kelas->nama_matakuliah) ?>)</h3>
            </div>
            <div class="box-body">
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
                                    <th style="text-align: center">STATUS MAHASISWA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($data as $key => $row) :
                                    ?>
                                    <tr>
                                        <td style="text-align: center; width: 3%"><?= $no++ ?>.</td>
                                        <td style="text-align: center"><?= e($row->nim) ?></td>
                                        <td><?= e($row->nama_mahasiswa) ?></td>
                                        <td>
                                            <div class="form-group"
                                                 style="margin: 0px">
                                                <input type="text" id="<?= e($key) ?>"
                                                       value="<?= e($row->harian ? $row->harian:$row->nilai_harian) ?>"
                                                       class="form-control harian-<?= e($row->kode_khs_detail) ?>"
                                                       placeholder="Enter ..." <?= e($data_kelas->status_dosen == 'T' ? 'disabled':'') ?>>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group "
                                                 style="margin: 0px">
                                                <input type="text" id="<?= e($key) ?>"
                                                       value="<?= e($row->uts ? $row->uts:$row->nilai_uts) ?>"
                                                       class="form-control uts-<?= e($row->kode_khs_detail) ?> "
                                                       placeholder="Enter ..."  <?= e($data_kelas->status_dosen == 'T' ? 'disabled':'') ?>>
                                            </div>    
                                        <td>
                                            <div class="form-group"
                                                 style="margin: 0px">
                                                <input type="text" id="<?= e($key) ?>"
                                                       value="<?= e($row->uas ? $row->uas:$row->nilai_uas) ?>"
                                                       class="form-control uas-<?= e($row->kode_khs_detail) ?>"
                                                       placeholder="Enter ..."  <?= e($data_kelas->status_dosen == 'T' ? 'disabled':'') ?>>
                                            </div>
                                        <td><p id="na<?= e($key) ?>"><?= round($row->na ? $row->na:$row->nilai_akhir) ?></p></td>
                                        <td><p id="grade<?= e($key) ?>"> <?= e($row->grade) ?> </p></td>
                                        <td style="text-align:center"><?= e($row->block_id ? "Block":"Aktif") ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
</div>