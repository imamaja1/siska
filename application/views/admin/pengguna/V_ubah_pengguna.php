<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/pengguna/pengguna'); ?>" class="btn btn-xs btn-success flat"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="<?= isset($active_1_1) ? $active_1_1 : "" ?>"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Ubah Data</a></li>
        <li class="<?= isset($active_2_1) ? $active_2_1 : "" ?>"><a href="#tab_2" data-toggle="tab" aria-expanded="false">Ubah Password</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane <?= isset($active_1_2) ? $active_1_2 : "" ?>" id="tab_1">
            <div class="box box-solid flat">
                <div class="box-body"><br>
                    <form action="<?= site_url('admin/pengguna/pengguna/ubah_data'); ?>" method="POST" class="form-horizontal">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" name="kode_pengguna" value="<?= set_value('kode_pengguna', $data_pengguna->kode_pengguna) ?>">
                        <div class="form-group">
                            <label class="control-label col-sm-3">Nama Pengguna :</label>
                            <div class="col-sm-4">
                                <input class="form-control" type="text" name="nama_pengguna" placeholder="Nama Pengguna" value="<?= set_value('nama_pengguna', $data_pengguna->nama_pengguna) ?>" >
                                <small style="color: red"><?= form_error('nama_pengguna') ?></small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Nama Login :</label>
                            <div class="col-sm-4">
                                <input class="form-control" type="text" name="nama_login" placeholder="Nama Login" value="<?= set_value('nama_login', $data_pengguna->nama_login) ?>" >
                                <small style="color: red"><?= form_error('nama_login') ?></small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Role :</label>
                            <div class="col-sm-4">
                                <select class="form-control" name="id_role">
                                    <?php foreach ($roles as $role) : ?>
                                    <option <?= $role->id_role == $data_pengguna->id_role ? "selected" : "" ?> value="<?= e($role->id_role) ?>"><?= e($role->nama_role) ?></option>
                                    <?php endforeach;?>
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
        </div>
        <div class="tab-pane <?= isset($active_2_2) ? $active_2_2 : "" ?>" id="tab_2">
            <div class="box box-solid flat">
                <div class="box-header">
                    <h5>Ubah Password Bapak/Ibu <b><?= e($data_pengguna->nama_pengguna) ?></b></h5><hr>
                </div>
                <div class="box-body">
                    <form action="<?= site_url('admin/pengguna/pengguna/ubah_password'); ?>" method="POST" class="form-horizontal">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" name="kode_pengguna" value="<?= set_value('kode_pengguna', $data_pengguna->kode_pengguna) ?>">
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
                            <label class="control-label col-sm-3"></label>
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary flat"><i class="fa fa-check-circle"></i> Simpan</button>
                                <button type="reset" class="btn btn-default flat"><i class="fa fa-refresh"></i> Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>