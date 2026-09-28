<!-- end.box -->
<?php if (count($data) > 0): ?>
    <div class="box box-primary flat">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-list-alt"></i> Kurikulum Matakuliah</h3>
        </div>
        <div class="box-body">
            <?php foreach ($data as $row): ?>
                <?php
                if (count($row['data']) <= 0) {
                    break;
                }
                ?>
                <div class="mhs-semester-label">Semester <?= $row['semester'] ?></div>
                <div class="table-responsive mhs-table-wrap">
                    <table class="table mhs-table">
                        <thead>
                            <tr>
                                <th class="mhs-c-no">No.</th>
                                <th class="mhs-c-kode">Kode Matakuliah</th>
                                <th>Nama Matakuliah</th>
                                <th class="mhs-c-num">SKS Teori</th>
                                <th class="mhs-c-num">SKS Praktek</th>
                                <th class="mhs-c-num">SKS Praktikum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($row['data'] as $d) {
                                $style_tr = in_array($d->id_matakuliah, $mk_pilihan) ? "font-style: italic" : "font-weight: bold";
                                if ($d->jenis == '1') { $style_tr = "font-style: italic"; }
                                ?>
                                <tr style="<?= $style_tr ?>">
                                    <td class="mhs-num"><?= $i++ ?>.</td>
                                    <td class="mhs-kode"><?= e($d->kode_matakuliah) ?></td>
                                    <td>
                                        <?= e($d->nama_matakuliah) ?>
                                        <?= (!empty($nama_pilihan[$d->id_matakuliah])) ? ' - (Kompetensi : ' . e($nama_pilihan[$d->id_matakuliah]) . ')' : '' ?>
                                        <?= ($d->jenis == 1) ? '- (Matakuliah Pilihan Umum)' : '' ?>
                                    </td>
                                    <td class="mhs-num"><?= $d->sks_teori ?></td>
                                    <td class="mhs-num"><?= $d->sks_praktek ?></td>
                                    <td class="mhs-num"><?= $d->sks_praktikum ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

<?php else : ?>
    <div class="callout callout-danger">
        <h4>Perhatian!</h4>
        <p>Data tidak ditemukan...</p>
    </div>
<?php endif; ?>