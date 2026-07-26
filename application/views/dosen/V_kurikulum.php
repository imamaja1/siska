<div class="box box-solid flat">
    <div class="box-body"><br>
        <form name="kurikulum_form" class="form-horizontal"  action="<?= $form_action; ?>" method="POST">
            <div class="form-group">
                <label class="control-label col-sm-3">Jurusan <label class="text-danger">*</label> :</label>
                <div class="col-sm-3">
                    <?php
                    $js = 'class="form-control select2" onChange="this.form.submit();"';
                    echo form_dropdown('kode_nama_kurikulum', $options_nama_kurikulum, isset($default['kode_nama_kurikulum']) ? $default['kode_nama_kurikulum'] : '', $js);
                    ?>
                </div>
                <small class="text-danger"><?= form_error('kode_nama_kurikulum'); ?></small>
            </div>
        </form>
    </div>
</div>

<?php if ($status_kurikulum) { ?>
    <div id="khs">
        <!-- Start Header Petikan Nilai -->
        <div align="center">
            <h4>
                <b><?= strtoupper($nama_prodi) . ' (Kurikulum : ' . $nama_kurikulum->nama_kurikulum . ')'; ?></b>
            </h4>
        </div>
        <br/>
        <!-- Start Content Petikan Nilai -->
        <?php
        $sm = 1;
        foreach ($data_kurikulum as $item) :
            ?>
            <?php if (!empty($item['data'])) : ?>
                <div class="box box-primary flat">
                    <div class="box-header">
                        <?= "<b>SEMESTER " . $item['semester']; ?>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table demo-table">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;" width="5%">NO</th>
                                        <th style="text-align:center;" width="18%">KODE MK</th>
                                        <th style="text-align:center;" width="54%">MATAKULIAH</th>
                                        <th style="text-align:center;" width="8%">SKS TEORI</th>
                                        <th style="text-align:center;" width="8%">SKS PRAKTEK</th>
                                        <th style="text-align:center;" width="8%">SKS PRAKTIKUM</th>
                                    </tr>
                                </thead>
                                <?php
                                $i = 1;
                                foreach ($item['data'] as $row) :
                                    ?>
                                    <tr <?= in_array($row->id_matakuliah, $mk_pilihan) ? "style='font-style: italic'" : '' ?> <?= ($row->jenis == 1) ? "style='font-style: italic'" : ''; ?>>
                                        <td align="center"><?= $i . '.'; ?></td>
                                        <td align="center"><?= $row->kode_matakuliah; ?></td>
                                        <td><?= $row->nama_matakuliah; ?> <?= ($nama_pilihan[$row->id_matakuliah] == true) ? ' - (Kompetensi : ' . $nama_pilihan[$row->id_matakuliah] . ')' : '' ?></td>
                                        <td align="center"><?= $row->sks_teori; ?></td>
                                        <td align="center"><?= $row->sks_praktek; ?></td>
                                        <td align="center"><?= $row->sks_praktikum; ?></td>
                                    </tr>
                                    <?php
                                    $i++;
                                endforeach;
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php
    }
    if (!empty($link)) {
        echo '<p id="bottom_link">';
        foreach ($link as $links) {
            echo $links . ' ';
        }
        echo '</p>';
    }
    ?>

