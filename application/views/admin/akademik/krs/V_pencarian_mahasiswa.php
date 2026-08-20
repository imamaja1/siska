<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/krs') ?>" class="btn btn-xs btn-success flat"><i class="fa fa-arrow-left"></i> Kembali</a>
        <?= isset($jumlah_data) ? $jumlah_data : ''; ?>
        <div class="pull-right">
            <?= isset($pagination) ? $pagination : ''; ?>
        </div>
    </div>
</div>

<?php
echo isset($table) ? $table : '';
$flashmessage = $this->session->flashdata('message');
echo isset($flashmessage) ? $flashmessage : '';
?>

<div class="box box-primary flat">
    <div class="box-header">
        <h5><b>Pencarian Data KRS Mahasiswa Berdasarkan Kata Kunci</b></h5>
        <hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" method="post" action="<?= site_url('admin/akademik/krs/search_process'); ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-3">Semester <label class="text-danger">*</label> :</label>
                <div class="col-sm-3">
                    <select class="form-control" name="semester" id="semester">
                        <option value="" selected disabled>Pilih Semester</option>
                        <option <?= set_select('semester', 1) ?> value="1">1</option>
                        <option <?= set_select('semester', 2) ?> value="2">2</option>
                        <option <?= set_select('semester', 3) ?> value="3">3</option>
                        <option <?= set_select('semester', 4) ?> value="4">4</option>
                        <option <?= set_select('semester', 5) ?> value="5">5</option>
                        <option <?= set_select('semester', 6) ?> value="6">6</option>
                        <option <?= set_select('semester', 7) ?> value="7">7</option>
                        <option <?= set_select('semester', 8) ?> value="8">8</option>
                        <option <?= set_select('semester', 9) ?> value="9">9</option>
                        <option <?= set_select('semester', 10) ?> value="10">10</option>
                        <option <?= set_select('semester', 11) ?> value="11">11</option>
                        <option <?= set_select('semester', 12) ?> value="12">12</option>
                        <option <?= set_select('semester', 13) ?> value="13">13</option>
                        <option <?= set_select('semester', 14) ?> value="14">14</option>
                    </select>
                    <small class="text-danger"><?= form_error('semester'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Pencarian Berdasarkan <label class="text-danger">*</label>
                    :</label>
                <div class="col-sm-5">
                    <label><input type="radio" name="berdasarkan" value="nim" <?= set_radio('berdasarkan', 'nim') ?>>
                        NIM (Nomor Induk Mahasiswa)</label>
                    &nbsp;&nbsp;
                    <label><input type="radio" name="berdasarkan" value="nama" <?= set_radio('berdasarkan', 'nama') ?>>
                        Nama Mahasiswa </label>
                    <br>
                    <small class="text-danger"><?= form_error('berdasarkan'); ?></small>
                </div>

            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Masukkan Kata Kunci <label class="text-danger">*</label> :</label>
                <div class="col-sm-4">
                    <input value="<?= set_value('kata_kunci') ?>" type="text" class="form-control"
                           placeholder="Ketik Kata Kunci disini" name="kata_kunci" id="kata_kunci">
                    <small class="text-danger"><?= form_error('kata_kunci') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-cog"></i> Proses</button>
                </div>
            </div>
        </form>
    </div>
</div>

