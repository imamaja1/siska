<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/pengguna/pengguna'); ?>" class="btn btn-xs btn-success flat"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>
<div class="box box-solid flat">
    <div class="box-body"><br>
        <form action="<?= site_url('admin/pengguna/pengguna/simpan'); ?>" method="POST" class="form-horizontal">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-3">Nama Pengguna :</label>
                <div class="col-sm-4">
                    <input class="form-control" type="text" name="nama_pengguna" placeholder="Nama Pengguna" value="<?= set_value('nama_pengguna') ?>" >
                    <small style="color: red"><?= form_error('nama_pengguna') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Nama Login :</label>
                <div class="col-sm-4">
                    <input class="form-control" type="text" name="nama_login" placeholder="Nama Login" value="<?= set_value('nama_login') ?>" >
                    <small style="color: red"><?= form_error('nama_login') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Sandi Pengguna :</label>
                <div class="col-sm-4">
                    <input class="form-control" type="password" name="sandi_pengguna" value="<?= set_value('sandi_pengguna') ?>" >
                    <small style="color: red"><?= form_error('sandi_pengguna') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Ulangi Sandi Pengguna :</label>
                <div class="col-sm-4">
                    <input class="form-control" type="password" name="ulangi_sandi_pengguna" value="<?= set_value('ulangi_sandi_pengguna') ?>" >
                    <small style="color: red"><?= form_error('ulangi_sandi_pengguna') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Role :</label>
                <div class="col-sm-4">
                    <select class="form-control" name="id_role">
                        <option selected disabled>Pilih Hak Akses Pengguna</option>
                        <?php foreach ($roles as $role) : ?>
                        <option value="<?= e($role->id_role) ?>"><?= e($role->nama_role) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color: red"><?= form_error('id_role') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-4">
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-check-circle"></i> Simpan</button>
                    <button type="reset" class="btn btn-default flat"><i class="fa fa-refresh"></i> Reset</button>
                </div>
            </div>
        </form>     
    </div>
</div>
