<div class="box box-warning">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-list"></i> KELAS - <?= e($data_kelas->nama_kelas) ?>
            / <?= e($data_kelas->kode_matakuliah . " - " . $data_kelas->nama_matakuliah) ?></h3>
    </div>
    <div class="box-body">
        <?php if (count($data) > 0) : ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped demo-table">
                    <thead>
                    <tr>
                        <th rowspan="2" style="text-align: center">NO.</th>
                        <th rowspan="2" style="text-align: center">NIM</th>
                        <th rowspan="2" style="text-align: center">NAMA</th>
                        <th colspan="4" style="text-align: center">NILAI</th>
                        <th rowspan="2" style="text-align: center">GRADE</th>

                    </tr>
                    <tr>
                        <th style="text-align: center">HARIAN</th>
                        <th style="text-align: center">UTS</th>
                        <th style="text-align: center">UAS</th>
                        <th style="text-align: center">NA</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1;
                    foreach ($data as $row) : ?>
                        <tr>
                            <td><?= $no++ ?>.</td>
                            <td><?= e($row->nim) ?></td>
                        	<td><?= e($row->nama_mahasiswa) ?></td>
                                <td><?= e($row->dummy_harian) ?></td>
                                <td><?= e($row->dummy_uts) ?></td>
                                <td><?= e($row->dummy_uas) ?></td>
                                <td><?= round($row->dummy_na) ?></td>
                               <?php if (($row->grade == E) || ($row->grade == D)): ?>
                                    <td style="text-align: center; font-weight: bold; background: red; color: white;"><?= e($row->grade) ?></td>
                                <?php else: ?>
                                    <td style="text-align: center; font-weight: bold;"><?= e($row->grade) ?></td>

                                <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="callout callout-danger">
                <h4><i class="fa fa-ban"></i> Peringatan</h4>
                <p>Dosen bersangkutan <b>belum/belum selesai</b> melakukan penginputan nilai.</p>
            </div>
        <?php endif; ?>
    </div>
    <div class="box-footer">
        <?php if ($data_kelas->status_nilai == 'T') : ?>
            <?php if ($data_kelas->validasi_dekan != 'T') : ?>
                <form action="<?= site_url('dosen/dekan/validasinilai/revisi_uas') ?>" method="post">
                    <div class="form-group">
                        <input type="hidden" value="<?= e($data_kelas->kelas_id) ?>" name="kelas_id" required readonly>
                        <textarea class="form-control" name="catatan_prodi" id="catatan_prodi" required
                               placeholder="Catatan untuk dosen wajib diisi jika nilai di revisi"></textarea>
                    </div>
                    <button class="btn btn-warning pull-right btn-flat"><i class="fa fa-recycle"></i> Revisi Nilai
                    </button>
                    <a onclick="validasi()" href="#" class="btn btn-primary btn-flat pull-left"
                       title="Tekan untuk memvalidasi"><i class="fa fa-check"></i> Validasi Nilai
                    </a>
                </form>
            <?php else: ?>
                <div class="label label-success"><i class="fa fa-check-square-o"></i> Nilai sudah tervalidasi</div>
            <?php endif; ?>
        <?php else: ?>
            <div class="label label-danger"><i class="fa fa-times"></i> Nilai belum selesai diinputkan.</div>
        <?php endif; ?>
    </div>
</div>