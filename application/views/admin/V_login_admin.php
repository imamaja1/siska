<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin | Log in</title>
        <link rel="icon" type="image/png" sizes="96x96" href="<?= base_url('assets/gambar') ?>/favicon-96x96.png">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="<?= site_url('assets/bootstrap/css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?= site_url('assets/font-awesome/css/font-awesome.min.css') ?>">
        <link rel="stylesheet" href="<?= site_url('assets/dist/css/AdminLTE.css') ?>">
        <link rel="stylesheet" href="<?= site_url('assets/plugins/iCheck/square/blue.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/animate.css/animate.min.css') ?>">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition login-page ">
        <div class="login-box">
            <div class="login-logo"><br></div>
            <div style="background-color: #3c8dbc; height: 50px; padding: 10px; color: #fff; text-align: center;">
                <b style="font-size: 20px;"><img width="40px;" heigt="40px;" src="<?= base_url('assets/gambar/admin.png'); ?>">&nbsp;&nbsp;LOGIN ADMIN</b>
            </div>
            <div class="login-box-body " style="padding-top: 0px;">
                <p class="login-box-msg" style="font-size: 18px"><br>Sistem Informasi Akademik <b>(SISKA)</b></p>

                <form action="<?= site_url('admin/login_admin/login'); ?>" method="post">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group has-feedback">
                        <input value="<?= set_value('nama_login') ?>" type="text" class="form-control" placeholder="Nama Login" name="nama_login">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        <small class="text-danger"><?= form_error('nama_login') ?></small>
                    </div>
                    <div class="form-group has-feedback">
                        <input value="<?= set_value('sandi') ?>" type="password" class="form-control" placeholder="Sandi" name="sandi">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        <small class="text-danger"><?= form_error('sandi') ?></small>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 pull-right">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
                        </div>
                    </div>

                </form>
            </div>
            <?php
            $pesan = $this->session->flashdata('pesan');
            if (!empty($pesan)):
                ?>
                <div style="color:white;background-color: #3c8dbc;" class="alert alert-dismissible flat animated fadeInDown">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <p><?= $pesan; ?></p>
                </div>
            <?php else:endif; ?>

        </div>

        <script src="<?= site_url('assets/plugins/jQuery/jQuery-2.2.0.min.js') ?>"></script>
        <script src="<?= site_url('assets/bootstrap/js/bootstrap.js') ?>"></script>
        <script src="<?= site_url('assets/plugins/js/iCheck/icheck.js') ?>"></script>
    <?php $this->load->view('csrf_js'); ?>
    </body>
</html>
