<div class="box box-primary flat">
    <div class="box-header">
        <h5><b>Pencarian data nilai KKP Mahasiswa</b></h5><hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" method="post" action="<?= site_url('admin/akademik/kkp/search_process'); ?>">
            <div class="form-group">
                <label class="control-label col-sm-3">Pencarian Berdasarkan <label class="text-danger">*</label> :</label>
                <div class="col-sm-5">
                    <label><input type="radio" name="berdasarkan" value="nim" <?= set_radio('berdasarkan', 'nim') ?>> NIM (Nomor Induk Mahasiswa)</label>
                    &nbsp;&nbsp;
                    <label><input type="radio" name="berdasarkan" value="nama" <?= set_radio('berdasarkan', 'nama') ?>> Nama Mahasiswa </label>
                    <br>
                    <small class="text-danger"><?= form_error('berdasarkan'); ?></small>
                </div>

            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Masukan Kata Kunci <label class="text-danger">*</label> :</label>
                <div class="col-sm-4">
                    <input value="<?= set_value('kata_kunci') ?>" type="text" class="form-control" placeholder="Ketik Kata Kunci disini" name="kata_kunci" id="kata_kunci">
                    <small class="text-danger"><?= form_error('kata_kunci') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-cog"></i>  Proses</button>
                </div>
            </div>
        </form>
    </div>
</div>