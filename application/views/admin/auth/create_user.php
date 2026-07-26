<div class="col-md-8">
    <div class="box box-primary flat">
        <div class="box-body">
            <br>
            <form class="form-horizontal" action="<?= site_url('auth/create_user') ?>" method="post">
                <div class="form-group">
                    <label class="control-label col-sm-3">Nama Depan</label>
                    <div class="col-sm-4">
                        <input value="<?= set_value('first_name') ?>" type="text" class="form-control" name="first_name" id="first_name" placeholder="Nama Depan">
                        <small class="text-danger"><?= form_error('first_name'); ?></small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Nama Belakang</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Nama Belakang">
                         <small class="text-danger"><?= form_error('last_name'); ?></small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Nama Perusahaan</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="company" id="company" placeholder="Nama Perusahaan">
                        <small class="text-danger"><?= form_error('company'); ?></small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Email</label>
                    <div class="col-sm-5">
                        <input type="email" class="form-control" name="identity" id="identity" placeholder="Email">
                        <small class="text-danger"><?= form_error('identity'); ?></small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">No.Telpon</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="No.Telpon">
                        <small class="text-danger"><?= form_error('phone'); ?></small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Password</label>
                    <div class="col-sm-4">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                        <small class="text-danger"><?= form_error('password'); ?></small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Konfirmasi Password</label>
                    <div class="col-sm-4">
                        <input type="password" class="form-control" name="password_confirm" id="password_confirm" placeholder="Konfirmasi Password">
                        <small class="text-danger"><?= form_error('password_confirm'); ?></small>
                    </div>
                </div>
                <div class="box-header">
                    <div class="pull-right">
                        <a href="<?= site_url('auth/pengguna') ?>" class="btn btn-default flat"><i class="fa fa-remove"></i> Batal</a>
                        <button class="btn btn-primary flat"><i class="fa fa-plus-circle"></i> Simpan</button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>







