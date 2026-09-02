<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/pengaturan/api_tokens') ?>" class="btn btn-xs btn-success flat">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="box box-primary flat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-plus-circle"></i> Tambah Token API</h3>
    </div>
    <div class="box-body">
        <form action="<?= site_url('admin/pengaturan/api_tokens/simpan') ?>" method="POST" class="form-horizontal">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

            <div class="form-group">
                <label class="control-label col-sm-3">Nama Aplikasi <label style="color: red">*</label> :</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="nama_aplikasi" placeholder="PMB v2" value="<?= set_value('nama_aplikasi') ?>" required>
                    <small style="color: red"><?= form_error('nama_aplikasi') ?></small>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">URL Endpoint <label style="color: red">*</label> :</label>
                <div class="col-sm-7">
                    <input type="url" class="form-control" name="api_url" placeholder="https://pmbv2.ubg.ac.id/api/mahasiswa" value="<?= set_value('api_url') ?>" required>
                    <small style="color: red"><?= form_error('api_url') ?></small>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Bearer Token <label style="color: red">*</label> :</label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <input type="text" class="form-control" name="bearer_token" id="bearer_token" placeholder="Masukkan token atau generate otomatis" value="<?= set_value('bearer_token', $generated_token) ?>" required>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default flat" onclick="generateToken()" title="Generate Token">
                                <i class="fa fa-refresh"></i> Generate
                            </button>
                        </span>
                    </div>
                    <small style="color: red"><?= form_error('bearer_token') ?></small>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Status :</label>
                <div class="col-sm-5">
                    <label>
                        <input type="checkbox" name="is_active" value="1" checked> Aktif
                    </label>
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

<script>
function generateToken() {
    $.ajax({
        url: '<?= site_url("admin/pengaturan/api_tokens/generate_token") ?>',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res.status) {
                $('#bearer_token').val(res.token);
            }
        }
    });
}
</script>
