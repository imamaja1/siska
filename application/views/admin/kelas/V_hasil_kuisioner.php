<div class="box box-solid">
    <div class="box-body">
        <a href="<?= site_url('admin/kuisioner/kuisioner/cetak_kuisioner') ?>" class="btn btn-success btn-sm flat pull-right"><i class="fa fa-file-excel-o"></i> Cetak Excel</a>
        <a href="<?= site_url('admin/kuisioner/kuisioner') ?>" class="btn btn-danger btn-sm flat pull-right"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
    </div>
</div>
<div class="box box-primary">
    <div class="box-header">
        <p align="center"><strong>HASIL PENGISIAN KUISONER</strong></p>
        <div class="col-sm-6">
            <dl class="dl-horizontal">
                <dt>Nama Dosen : </dt>
                <?php $no=1; foreach ($top['dosen'] as $row) :?>
                    <dd><?= $no++; ?>. <?= $row->nama_dosen ?></dd>
                <?php endforeach;?>
            </dl>
        </div>
        <div class="col-sm-6">
            <dl class="dl-horizontal">
                <dt>Kelas : </dt>
                <dd><?= $nama_kelas ?></dd>
                <dt>Matakuliah : </dt>
                <dd><?= $top['nama_matakuliah']->nama_matakuliah ?></dd>
            </dl>

        </div>
    </div>
    <div class="box-body">
    <?php if (isset($data['hasil'])) : ?>
        <div class="table-responsive">
            <?php if (isset($data['hasil']['P'])) : ?>
                <h4><i class="fa fa-arrow-circle-o-right"></i> Hasil kuisioner teori</h4>
                <table class="table demo-table">
                    <thead>
                    <tr>
                        <th id="th" rowspan="2">NO.</th>
                        <?php $batas=0; foreach ($data['soal_kuisioner']['T'] as $key) : ?>
                            <th id="th" colspan="<?= $key->colspan ?>"><?= $key->kategori ?></th>
                            <?php $batas = $batas + $key->colspan; ?>
                        <?php endforeach;?>
                    </tr>
                    <tr>
                        <?php foreach ($data['soal_kuisioner']['T'] as $row) : ?>
                            <?php $count = $row->colspan+1;  for ($n=1; $n < $count; $n++) : ?>
                                <th id="th"><?=$n ?></th>
                            <?php endfor; ?>
                        <?php endforeach;?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=1; foreach ($data['hasil']['T'] as $item) :?>
                        <tr>
                            <td align="center" width="8%"><strong><?= $no++ ?>.</strong></td>
                            <?php $index = 0; foreach ($item as $val) :?>
                                <?php
                                if (($batas) == $index)
                                {
                                    break;
                                }
                                ?>
                                <td align="center"><?= $val->hasil ?></td>
                                <?php
                                if (isset($total))
                                {
                                    $jml[$index] = $total[$index] +  $val->hasil;
                                }else{
                                    $jml[$index] = $val->hasil;
                                }
                                $index++;
                            endforeach;
                            $total = $jml;?>
                        </tr>
                    <?php endforeach;?>
                    <tr>
                        <td align="center" style="background-color: #aed4e6;" ><strong>Jumlah</strong> </td>
                        <?php foreach ($total as $row => $value) : ?>
                            <td align="center" style="background-color: #aed4e6;"><strong><?= $value ?></strong></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td align="center" style="background-color: #e6ddae;"> <strong>Rata-rata</strong></td>
                        <?php $jml_mah = count($data['hasil']['T']); foreach ($total as $key => $val) : ?>
                            <td align="center" style="background-color: #e6ddae;"><strong><?= number_format($val/$jml_mah, 1)?></strong></td>
                        <?php endforeach; ?>
                    </tr>
                    </tbody>
                </table>
<!--                TABEL KUISIONER PRAKTIKUM-->
                <hr>
                <h4><i class="fa fa-arrow-circle-o-right"></i> Hasil kuisioner praktikum</h4>
                <table class="table demo-table">
                <thead>
                <tr>
                    <th id="th" rowspan="2">NO.</th>
                    <?php $batas2=0; foreach ($data['soal_kuisioner']['P'] as $key) : ?>
                            <th id="th" colspan="<?= $key->colspan ?>"><?= $key->kategori ?></th>
                        <?php $batas2 = $batas2 + $key->colspan; ?>
                    <?php endforeach;?>
                </tr>
                <tr>
                    <?php foreach ($data['soal_kuisioner']['P'] as $row) : ?>
                        <?php $count = $row->colspan+1;  for ($n=1; $n < $count; $n++) : ?>
                        <th id="th"><?=$n ?></th>
                        <?php endfor; ?>
                    <?php endforeach;?>
                </tr>
                </thead>
                <tbody>
                    <?php $no=1; foreach ($data['hasil']['P'] as $item) :?>
                        <tr>
                            <td align="center" width="8%"><strong><?= $no++ ?>.</strong></td>
                            <?php $key = 0; foreach ($item as $valu) :?>
                                <?php
                                if (($batas2) == $key)
                                {
                                    break;
                                }
                                ?>
                            <td align="center"><?= $valu->hasil ?></td>
                            <?php
                                if (isset($tot))
                                {
                                    $jmlh[$key] = $tot[$key] +  $valu->hasil;
                                }else{
                                    $jmlh[$key] = $valu->hasil;
                                }
                                $key++;
                            endforeach;
                            $tot = $jmlh;?>
                        </tr>
                    <?php endforeach;?>
                <tr>
                    <td align="center" style="background-color: #aed4e6;" ><strong>Jumlah</strong> </td>
                    <?php foreach ($tot as $row => $value) : ?>
                    <td align="center" style="background-color: #aed4e6;"><strong><?= $value ?></strong></td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <td align="center" style="background-color: #e6ddae;"> <strong>Rata-rata</strong></td>
                    <?php $jml_mah = count($data['hasil']['P']); foreach ($tot as $key => $val) : ?>
                        <td align="center" style="background-color: #e6ddae;"><strong><?= number_format($val/$jml_mah, 1)?></strong></td>
                    <?php endforeach; ?>
                </tr>
                </tbody>
            </table>
<!--                TABEL TEORI SAJA-->
            <?php else : ?>
                <table class="table demo-table">
                    <thead>
                    <tr>
                        <th id="th" rowspan="2">NO.</th>
                        <?php $batas3 = 0; foreach ($data['soal_kuisioner'] as $key) : ?>
                            <th id="th" colspan="<?= $key->colspan ?>"><?= $key->kategori ?></th>
                            <?php $batas3 = $batas3 + $key->colspan; ?>
                        <?php endforeach;?>
                    </tr>
                    <tr>
                        <?php foreach ($data['soal_kuisioner'] as $row) : ?>
                            <?php $count = $row->colspan+1;  for ($n=1; $n < $count; $n++) : ?>
                                <th id="th"><?=$n ?></th>
                            <?php endfor; ?>
                        <?php endforeach;?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=1; foreach ($data['hasil'] as $item) :?>
                        <tr>
                            <td align="center" width="8%"><strong><?= $no++ ?>.</strong></td>
                            <?php $index = 0; foreach ($item as $val) :?>
                                <?php
                                if (($batas3) == $index)
                                {
                                    break;
                                }
                                ?>
                                <td align="center"><?= $val->hasil ?></td>
                                <?php
                                if (isset($total))
                                {
                                    $jml[$index] = $total[$index] +  $val->hasil;
                                }else{
                                    $jml[$index] = $val->hasil;
                                }
                                $index++;
                            endforeach;
                            $total = $jml;?>
                        </tr>
                    <?php endforeach;?>
                    <tr>
                        <td align="center" style="background-color: #aed4e6;" ><strong>Jumlah</strong> </td>
                        <?php foreach ($total as $row => $value) : ?>
                            <td align="center" style="background-color: #aed4e6;"><strong><?= $value ?></strong></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td align="center" style="background-color: #e6ddae;"> <strong>Rata-rata</strong></td>
                        <?php $jml_mah = count($data['hasil']); foreach ($total as $key => $val) : ?>
                            <td align="center" style="background-color: #e6ddae;"><strong><?= number_format($val/$jml_mah, 1)?></strong></td>
                        <?php endforeach; ?>
                    </tr>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
        <div class="callout callout-info flat">
            <h4><i class="fa fa-info-circle"></i> Informasi!</h4>
            <p>Belum ada satupun hasil kuisioner untuk matakuliah ini.</p>
        </div>
    <?php endif; ?>
</div>