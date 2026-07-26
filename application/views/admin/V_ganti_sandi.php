<?= $this->session->flashdata('pesan') ?>
<div class="box box-success flat">
    <div class="box-body"><br>
        <form class="form-horizontal" method="POST" action="<?= site_url('admin/pengguna/ganti_sandi/ganti_sandi_proses'); ?>">
            <div class="form-group">
                <label class="control-label col-sm-2">Sandi Pengguna <small class="text-danger">*</small> :</label>
                <div class="col-sm-4">
                    <input type="password" class="form-control" name="sandi_pengguna" value="<?= set_value('sandi_pengguna') ?>">
                    <small class="text-danger"><?= form_error('sandi_pengguna'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Ulangi Sandi <small class="text-danger">*</small> :</label>
                <div class="col-sm-4">
                    <input type="password" class="form-control" name="ulangi_sandi_pengguna" value="<?= set_value('ulangi_sandi_pengguna') ?>">
                    <small class="text-danger"><?= form_error('ulangi_sandi_pengguna'); ?></small>
                </div>

            </div>
            <div class="form-group">
                <label class="control-label col-sm-2"></label>
                <div class="col-sm-4">
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-check-circle"></i> Simpan</button>
                    <button type="reset" class="btn btn-info flat"><i class="fa fa-refresh"></i> Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>
