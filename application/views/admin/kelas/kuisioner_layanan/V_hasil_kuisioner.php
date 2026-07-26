<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-header">
            <div class="box-tools">
                <a href="#" onclick="cetak()"
                   class="btn btn-success btn-sm flat"><i class="fa fa-file-excel-o"></i> Cetak Excel</a>
            </div>
        </div>
        <div class="box-body">
            <p align="center"><strong>HASIL PENGISIAN KUISONER PELAYANAN TA. <?= $tahun_akademik->tahun_akademik ?>
                    - <?= $tahun_akademik->semester == 0 ? 'GENAP' : 'GANJIL' ?></strong></p>
                <dl class="dl-horizontal">
                    <dt>Program Studi :</dt>
                    <dd><?= $prodi->nama_program_studi ?></dd>
                </dl>
            <hr>
            <?php if (count($data) > 0) : ?>
            <div class="table-responsive">
                <h4><i class="fa fa-arrow-circle-o-right"></i> Hasil kuisioner pelayanan</h4>
                <table class="table demo-table">
                    <thead>
                    <tr>
                        <th id="th" rowspan="2">NO.</th>
                        <?php $batas = 0;
                        foreach ($header as $key) : ?>
                            <th id="th" colspan="<?= $key->colspan ?>"><?= $key->nama_bagian ?></th>
                            <?php $batas = $batas + $key->colspan; ?>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <?php foreach ($header as $row) : ?>
                            <?php $count = $row->colspan + 1;
                            for ($n = 1; $n < $count; $n++) : ?>
                                <th id="th"><?= $n ?></th>
                            <?php endfor; ?>
                        <?php endforeach; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1;
                    foreach ($data as $item) : ?>
                        <tr>
                            <td align="center" width="8%"><strong><?= $no++ ?>.</strong></td>
                            <?php $index = 0;
                            foreach ($item as $val) : ?>
                                <?php
                                if (($batas) == $index) {
                                    break;
                                }
                                ?>
                                <td align="center"><?= $val->hasil ?></td>
                                <?php
                                if (isset($total)) {
                                    $jml[$index] = $total[$index] + $val->hasil;
                                } else {
                                    $jml[$index] = $val->hasil;
                                }
                                $index++;
                            endforeach;
                            $total = $jml; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php else: ?>
            <div class="callout callout-info flat">
                <h4><i class="fa fa-info-circle"></i> Informasi!</h4>
                <p>Belum ada satupun hasil kuisioner untuk matakuliah ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
