<div class="col-md-6">
    <div class="box box-primary flat">
        <div class="box-body">
            <br>
            <form method="Post" action="<?= site_url('auth/create_group') ?>" class="form-horizontal">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="form-group">
                    <label class="control-label col-sm-3">Nama Group</label>
                    <div class="col-sm-7">
                        <input type="text" name="group_name" id="group_name" class="form-control" placeholder="Nama Group">
                        <small class="text-danger"><?= form_error('group_name'); ?></small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Deskripsi Group</label>
                    <div class="col-sm-7">
                        <textarea cols="20" rows="5" class="form-control" id="description" name="description" placeholder="Deskripsi Group"></textarea>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <a href="<?= site_url('auth/pengguna'); ?>" class="btn btn-default flat"><i class="fa fa-remove"></i> Batal</a>
                        <button type="submit" class="btn btn-success flat"><i class="fa fa-check-circle"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>