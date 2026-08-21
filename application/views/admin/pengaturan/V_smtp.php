<div class="box box-solid flat">
    <div class="box-body"><br>
        <form action="<?= site_url('admin/pengaturan/pengaturan/simpan'); ?>" method="POST" class="form-horizontal">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-3">SMTP Host :</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" name="smtp_host" placeholder="ssl://smtp.gmail.com" value="<?= set_value('smtp_host', e($smtp['smtp_host'])) ?>">
                    <small style="color: red"><?= form_error('smtp_host') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">SMTP Port :</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" name="smtp_port" placeholder="465" value="<?= set_value('smtp_port', e($smtp['smtp_port'])) ?>">
                    <small style="color: red"><?= form_error('smtp_port') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">SMTP User (Email) :</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" name="smtp_user" placeholder="email@domain.com" value="<?= set_value('smtp_user', e($smtp['smtp_user'])) ?>">
                    <small style="color: red"><?= form_error('smtp_user') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">SMTP Password :</label>
                <div class="col-sm-5">
                    <input class="form-control" type="password" name="smtp_pass" placeholder="Kosongkan jika tidak diubah" autocomplete="new-password">
                    <small class="text-muted">Biarkan kosong untuk mempertahankan password yang tersimpan.</small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">SMTP Timeout :</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" name="smtp_timeout" placeholder="5" value="<?= set_value('smtp_timeout', e($smtp['smtp_timeout'])) ?>">
                    <small style="color: red"><?= form_error('smtp_timeout') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-5">
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-check-circle"></i> Simpan</button>
                    <button type="reset" class="btn btn-default flat"><i class="fa fa-refresh"></i> Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="box box-solid flat">
    <div class="box-header with-border">
        <h3 class="box-title">Test Pengiriman Email</h3>
    </div>
    <div class="box-body">
        <form action="<?= site_url('admin/pengaturan/pengaturan/test_email'); ?>" method="POST" class="form-horizontal">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-3">Email Tujuan :</label>
                <div class="col-sm-5">
                    <input class="form-control" type="email" name="test_email_to" placeholder="Masukkan alamat email tujuan" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-5">
                    <button type="submit" class="btn btn-warning flat"><i class="fa fa-paper-plane"></i> Kirim Test Email</button>
                </div>
            </div>
        </form>
    </div>
</div>
