<?php if (count($data) > 0): ?>
    <?php foreach ($data as $row): ?>
        <div class="box box-primary flat">
            <div class="box-body">
                <a class="btn btn-primary btn-xs flat pull-right" data-toggle="modal" onclick="isi_data_kurikulum('<?= e($row['semester']) ?>')"><i class="fa fa-plus-circle"></i> Tambah Data Kurikulum</a>
                <p><strong>SEMESTER <?= e($row['semester']) ?></strong></p>
                <br>
                <?php if (count($row['data']) > 0) : ?>
                    <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="text-align: center" width="20">NO.</th>
                            <th style="text-align: center" width="70">ID MK.</th>
                            <th style="text-align: center" width="200">KODE MATAKULIAH</th>
                            <th style="text-align: center">NAMA MATAKULIAH</th>
                            <th style="text-align: center">SKS TEORI</th>
                            <th style="text-align: center">SKS PRAKTEK</th>
                            <th style="text-align: center">SKS PRAKTIKUM</th>
                            <th style="text-align: center" width="150">AKSI</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; $j_teori = 0; $j_pr = 0; $j_pm = 0;
                        foreach ($row['data'] as $d) {
                           $j_teori += $d->sks_teori;
                           $j_pr += $d->sks_praktek;
                           $j_pm += $d->sks_praktikum;
                            ?>
                            <tr <?= in_array($d->id_matakuliah, $mk_pilihan) ? "style='font-style: italic'" : "" ?> <?=($d->jenis == '1')? 'style="font-style: italic"':"style='font-weight: bold'";?>>
                                <td><?= $i++ ?>.</td>
                                <td><?= e($d->id_matakuliah) ?></td>
                                <td id="kode-matakuliah-<?= e($d->kode_kurikulum) ?>"><?= e($d->kode_matakuliah) ?></td>
                                <td>
                                  <?= e($d->nama_matakuliah) ?>
                                  <?= (!empty($nama_pilihan[$d->id_matakuliah])) ? ' - (Kompetensi : ' . e($nama_pilihan[$d->id_matakuliah]) . ')' : '' ?>
                                  <?= ($d->jenis == 1) ? '- (Matakuliah Pilihan)' : '' ?>
                                </td>
                                <td style="text-align: center"><?= e($d->sks_teori) ?></td>
                                <td style="text-align: center"><?= e($d->sks_praktek) ?></td>
                                <td style="text-align: center"><?= e($d->sks_praktikum) ?></td>
                                <td style="text-align: center">
                                    <a href="#" onclick="editKurikulum('<?= e($d->kode_kurikulum) ?>')" class="btn btn-xs btn-info flat"> <i class="fa fa-edit"></i> Edit</a>
                                    <a href="#" onclick="hapus('<?= e($d->kode_kurikulum) ?>', '<?= e($d->kode_nama_kurikulum) ?>')" class="btn btn-xs btn-danger flat"> <i class="fa fa-trash"></i> Hapus</a>
                                </td>
                            </tr>
                        <?php } ?>
                          <tr style="text-align: center; font-style: italic; font-weight: bold;">
                                <td colspan="4">Jumlah</td>
                                <td><?= e($j_teori) ?></td>
                                <td><?= e($j_pr) ?></td>
                                <td><?= e($j_pm) ?></td>
                                <td><?= e($j_teori + $j_pr + $j_pm) ?></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                <?php else : ?>
                    <div class="alert alert-info alert-dismissible flat">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-info"></i> Info!</h4>
                        Data tidak ada...
                    </div>
                <?php endif; ?>
                <hr>
            </div>
        </div>
    <?php endforeach; ?>
<?php else : ?>
    <div class="alert alert-info alert-dismissible flat">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info"></i> Info!</h4>
        Data tidak ada...
    </div>
<?php endif; ?>
