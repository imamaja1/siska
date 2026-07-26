<!-- end.box -->
<?php if (count($data) > 0): ?>
    <div class="box box-primary flat">
        <div class="box-body">
            <?php foreach ($data as $row): ?>
                <?php
                if (count($row['data']) <= 0) {
                    break;
                }
                ?>
                <p><strong>SEMESTER <?= $row['semester'] ?></strong></p>
        		<?php if (count($row['data']) > 0) : ?>
                    <div class="table-responsive">
                        <table class="table demo-table">
                            <thead>
                                <tr>
                                    <th class="th-color" width="20">
                            <center>No.</center>
                            </th>
                            <th class="th-color" width="200">
                            <center>Kode Matakuliah</center>
                            </th>
                            <th class="th-color">
                            <center>Nama Matakuliah</center>
                            </th>
                            <th class="th-color">
                            <center>SKS Teori</center>
                            </th>
                            <th class="th-color">
                            <center>SKS Praktek</center>
                            </th>
                            <th class="th-color">
                            <center>SKS Pratikum</center>
                            </th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($row['data'] as $d) {
                                    ?>
                                     <tr  <?= in_array($d->id_matakuliah, $mk_pilihan) ? "style='font-style: italic'" : 'style="font-weight: bold"' ?> <?=($d->jenis == '1')? 'style="font-style: italic"':'';?>>
                                        <td><center><?= $i++ ?>.</center></td>
                                		<td id="kode-matakuliah-<?= $d->kode_kurikulum ?>">
                                			<center><?= $d->kode_matakuliah ?></center>
                                		</td>
                                		<td>
                                          	<?= $d->nama_matakuliah ?>
                                          	<?= (!empty($nama_pilihan[$d->id_matakuliah])) ? ' - (Kompetensi : ' . $nama_pilihan[$d->id_matakuliah] . ')' : '' ?>
                                        	<?= ($d->jenis == 1) ? '- (Matakuliah Pilihan Umum)' : '' ?>
                                       </td>
                                		<td width="100"><center><?= $d->sks_teori ?></center></td>
                                		<td width="100"><center><?= $d->sks_praktek ?></center></td>
                                		<td width="100"><center><?= $d->sks_praktikum ?></center></td>
                                </tr>
            				<?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <p class="alert alert-warning flat"><strong>Data belum ada, silahkan lakukan pengisian data</strong>
                    </p>
                <?php endif; ?>
                <hr>
    <?php endforeach; ?>
        </div>
    </div>

<?php else : ?>
    <p class="alert alert-danger flat"><strong>Data tidak ditemukan...</strong></p>
<?php endif; ?>