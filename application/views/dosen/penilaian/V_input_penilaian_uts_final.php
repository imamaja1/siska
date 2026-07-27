<div class="row">
    <div class="col-md-12">
        <div class="box box-solid flat">
            <div class="box-body">
                <div class="pull-right">
                    <a href="<?= site_url('dosen/penilaian/penilaian_final') ?>" class="btn btn-success btn-xs flat"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box box-info flat">
            <div class="box-header with-border">
                <h3 class="box-title"><b> KELAS - <?= e($data_kelas->nama_kelas) ?> </b>
                    (<?= e($data_kelas->kode_matakuliah) ?> - <?= e($data_kelas->nama_matakuliah) ?>)</h3>
                <small class="pull-right text-muted">Perbandingan Nilai Penilaian vs KHS</small>
            </div>
            <div class="box-body">
                <div class="alert alert-info flat">
                    <i class="fa fa-info-circle"></i> <strong>Status Sinkron:</strong> Jika nilai <span class="badge bg-red">Belum Sinkron</span>, kemungkinan ada 3 kasus:<br>
                    1. Nilai telah berubah dengan menggunakan <b>form perubahan</b><br>
                    2. Nilai telah berubah karena mahasiswa mengikuti <b>MBKM</b><br>
                    3. Nilai telah berubah melalui <b>internal memo</b>
                </div>
                <div class="table-responsive">
                    <table class="table demo-table data-table">
                        <thead>
                            <tr style="background-color: #00c0ef">
                                <th style="text-align: center">NO.</th>
                                <th style="text-align: center">NIM</th>
                                <th style="text-align: center">NAMA</th>
                                <th style="text-align: center">NILAI PENILAIAN</th>
                                <th style="text-align: center">NILAI KHS</th>
                                <th style="text-align: center">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($data as $row): ?>
                                <tr>
                                    <td style="text-align: center; width: 3%"><?= $no++ ?>.</td>
                                    <td style="text-align: center"><?= e($row->nim) ?></td>
                                    <td><?= e($row->nama_mahasiswa) ?></td>
                                    <td style="text-align:center"><b><?= e($row->dummy_uts) ?></b></td>
                                    <td style="text-align:center"><?= e($row->final_khs) ?></td>
                                    <td style="text-align:center">
                                        <?php $sync = ($row->dummy_uts == $row->final_khs); ?>
                                        <span class="badge <?= $sync ? 'bg-green' : 'bg-red' ?>"><?= $sync ? 'Sinkron' : 'Belum Sinkron' ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
