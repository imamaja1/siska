<?= $this->session->flashdata('pesan') ?>
<div class="box box-success flat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-key"></i> Ubah Sandi</h3>
    </div>
    <div class="box-body">
        <form class="form-horizontal" method="POST" action="<?= site_url('mahasiswa/ganti_sandi/ganti_sandi_proses'); ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-3 col-md-2">Sandi Lama <small class="text-danger">*</small> :</label>
                <div class="col-sm-8 col-md-5">
                    <input type="password" class="form-control" name="sandi_lama" placeholder="Masukkan sandi saat ini" value="<?= set_value('sandi_lama') ?>">
                    <small class="text-danger"><?= form_error('sandi_lama'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3 col-md-2">Sandi Baru <small class="text-danger">*</small> :</label>
                <div class="col-sm-8 col-md-5">
                    <input type="password" class="form-control" name="sandi_pengguna" placeholder="Masukkan sandi baru minimal 6 karakter" value="<?= set_value('sandi_pengguna') ?>">
                    <small class="text-danger"><?= form_error('sandi_pengguna'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3 col-md-2">Ulangi Sandi <small class="text-danger">*</small> :</label>
                <div class="col-sm-8 col-md-5">
                    <input type="password" class="form-control" name="ulangi_sandi_pengguna" placeholder="Ketik ulang sandi baru" value="<?= set_value('ulangi_sandi_pengguna') ?>">
                    <small class="text-danger"><?= form_error('ulangi_sandi_pengguna'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3 col-md-2"></label>
                <div class="col-sm-8 col-md-5">
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-check-circle"></i> Simpan Sandi</button>
                    <button type="reset" class="btn btn-default flat"><i class="fa fa-refresh"></i> Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>
