<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/keuangan/status_perkuliahan/rekap') ?>" class="btn btn-success btn-sm flat"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
        <a href="<?= site_url('admin/keuangan/status_perkuliahan/cetak') ?>" class="btn btn-danger btn-sm flat pull-right"><i class="fa fa-file-excel-o"></i> Export</a>
    </div>
</div>

<!--<div class="col-md-12 col-xs-12 col-sm-12">-->
        <div class="box box-primary flat">
            <div class="box-header">
                <h4><span class="text-success"><i class="fa fa-calendar"></i></span><strong> TAHUN AKADEMIK - <?= e($tahun_akademik->tahun_akademik) ?>  (<span class="text-success"><?= $tahun_akademik->semester == 0 ? "GENAP" : "GANJIL" ?></span>)</strong></h4>
            </div>
            <div class="box-body">
                <?php if (count($data) > 0): ?>
                    <table class="table demo-table">
                        <thead>
                        <tr>
                            <th id="color" style="text-align: center">TAHUN ANGKATAN</th>
                            <th id="color" style="text-align: center">AKTIF</th>
                            <th id="color" style="text-align: center">PINDAH</th>
                            <th id="color" style="text-align: center">TANPA KETERANGAN</th>
                            <th id="color" style="text-align: center">CUTI</th>
                            <th id="color" style="text-align: center">TUGAS AKHIR</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $row) : ?>
                            <tr>
                                <td style="text-align: center"><?= $row['angkatan']  ?></td>
                                <td style="text-align: center"><?= $row['A']  ?></td>
                                <td style="text-align: center"><?= $row['P']  ?></td>
                                <td style="text-align: center"><?= $row['T']  ?></td>
                                <td style="text-align: center"><?= $row['C']  ?></td>
                                <td style="text-align: center"><?= $row['mhs_tugas_akhir']  ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p class="alert alert-warning">Data tidak ditemukan</p>
                <?php endif ?>
            </div>
        </div>
<!--    </div>-->

