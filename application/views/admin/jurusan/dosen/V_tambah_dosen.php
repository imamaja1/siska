<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/jurusan/dosen'); ?>" class="btn-sm btn-success flat"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-body"><br>
        <form class="form-horizontal" id="form" method="POST" action="<?= site_url('admin/jurusan/dosen/simpan') ?>">
            <div class="form-group">
                <label class="control-label col-sm-2">NIK</label>
                <div class="col-sm-3">
                    <input value="<?= set_value('nik') ?>" type="text" name="nik" placeholder="NIK" class="form-control" title="Field NIK harus diisi">
                    <small style="color: red;"><?= form_error('nik') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Nama Dosen</label>
                <div class="col-sm-4">
                    <input value="<?= set_value('nama_dosen') ?>" type="text" name="nama_dosen" class="form-control" placeholder="Nama dosen">
                    <small style="color: red;"><?= form_error('nama_dosen') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Field Studi</label>
                <div class="col-sm-4">
                    <input value="<?= set_value('field_studi') ?>" type="text" name="field_studi" class="form-control" placeholder="Field studi"> 
                    <small style="color: red;"><?= form_error('field_studi') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Alumni</label>
                <div class="col-sm-4">
                    <input value="<?= set_value('alumni') ?>" type="text" name="alumni" class="form-control" placeholder="Alumni">
                    <small style="color: red;"><?= form_error('alumni') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Status Dosen</label>
                <div class="col-sm-3">
                    <select class="form-control" name="status_dosen">
                        <option selected disabled>Pilih Status</option>
                        <option <?= set_select('status_dosen', 'T') ?> value="T">Tetap</option>
                        <option <?= set_select('status_dosen', 'L') ?> value="L">Luar</option>
                    </select>
                    <small style="color: red;"><?= form_error('status_dosen') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Homebase</label>
                <div class="col-sm-3">
                    <select class="form-control" name="homebase">
                        <option  disabled selected>Pilih Homebase</option>
                        <?php foreach ($homebase as $row) : ?>
                            <option <?= set_select('homebase', $row->kode_program_studi) ?> value="<?= $row->kode_program_studi ?>"><?= $row->singkatan_program_studi ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color: red;"><?= form_error('homebase') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">No. Telepon</label>
                <div class="col-sm-4">
                    <input value="<?= set_value('no_telp') ?>" type="text" name="no_telp"
                           class="form-control" placeholder="No. Telp">
                    <small style="color: red;"><?= form_error('no_telp') ?></small>
                </div>

            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Alamat email</label>
                <div class="col-sm-4">
                    <input value="<?= set_value('alamat_email') ?>" type="text" name="alamat_email" class="form-control" placeholder="Email">
                    <small style="color: red;"><?= form_error('alamat_email') ?></small>
                </div>

            </div>
            <div class="form-group" id="group-password">
                <label class="control-label col-sm-2">Password</label>
                <div class="col-sm-3">
                    <input value="<?= set_value('password') ?>" type="password" id="password" name="password" class="form-control" placeholder="Password">
                    <small style="color: red;"><?= form_error('password') ?></small>
                </div>

            </div>
            <div class="form-group" id="group-ulangi-password">
                <label class="control-label col-sm-2">Ulangi Password</label>
                <div class="col-sm-3">
                    <input value="<?= set_value('ulangi_password') ?>" name="ulangi_password" type="password"  class="form-control" placeholder="Ulangi  Password">
                    <small style="color: red;"><?= form_error('ulangi_password') ?></small>
                </div>

            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Status Login</label>
                <div class="col-sm-6">
                    <div class="radio">
                        <label><input <?= set_radio('status_login', 'A') ?> type="radio" name="status_login" value="A" id="login-aktif" >Aktif</label>
                        <label><input <?= set_radio('status_login', 'N') ?> type="radio" name="status_login" value="N" id="login-nonaktif">Tidak Aktif</label>
                    </div>
                    <small style="color: red;"><?= form_error('status_login') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2"></label>
                <div class="col-sm-5">
                    <button type="submit" name="submit" class="btn btn-primary flat"><i class="fa fa-check-circle"></i> Simpan</button>
                    <button type="reset" name="submit" class="btn btn-danger flat"><i class="fa fa-refresh"></i> Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>

