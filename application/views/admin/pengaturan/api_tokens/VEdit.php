<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/pengaturan/api_tokens') ?>" class="btn btn-xs btn-success flat">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="box box-primary flat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-pencil"></i> Edit Token API</h3>
    </div>
    <div class="box-body">
        <form action="<?= site_url('admin/pengaturan/api_tokens/update') ?>" method="POST" class="form-horizontal">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <input type="hidden" name="id" value="<?= $token->id ?>">

            <div class="form-group">
                <label class="control-label col-sm-3">Nama Aplikasi <label style="color: red">*</label> :</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="nama_aplikasi" value="<?= set_value('nama_aplikasi', $token->nama_aplikasi) ?>" required>
                    <small style="color: red"><?= form_error('nama_aplikasi') ?></small>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">URL Endpoint <label style="color: red">*</label> :</label>
                <div class="col-sm-7">
                    <input type="url" class="form-control" name="api_url" value="<?= set_value('api_url', $token->api_url) ?>" required>
                    <small style="color: red"><?= form_error('api_url') ?></small>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Bearer Token :</label>
                <div class="col-sm-7">
                    <textarea class="form-control" name="bearer_token" rows="3" placeholder="Kosongkan jika tidak diubah"><?= set_value('bearer_token') ?></textarea>
                    <small class="text-muted">Biarkan kosong untuk mempertahankan token yang tersimpan.</small>
                    <br><small class="text-muted">Token saat ini: <code><?= substr($token->bearer_token, 0, 12) ?>...<?= substr($token->bearer_token, -4) ?></code></small>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Status :</label>
                <div class="col-sm-5">
                    <label>
                        <input type="checkbox" name="is_active" value="1" <?= $token->is_active ? 'checked' : '' ?>> Aktif
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Dibuat :</label>
                <div class="col-sm-5">
                    <p class="form-control-static"><?= date('d-m-Y H:i', strtotime($token->created_at)) ?></p>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Terakhir Diubah :</label>
                <div class="col-sm-5">
                    <p class="form-control-static"><?= date('d-m-Y H:i', strtotime($token->updated_at)) ?></p>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-5">
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-check-circle"></i> Simpan</button>
                    <a href="<?= site_url('admin/pengaturan/api_tokens') ?>" class="btn btn-default flat"><i class="fa fa-times"></i> Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
