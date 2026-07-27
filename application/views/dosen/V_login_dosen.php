<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Dosen | Log in</title>
        <link rel="icon" href="<?php echo base_url('assets/siska/img/logo_kampus.png') ?>" />
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
                <b style="font-size: 20px;"><img width="30px;" heigt="30px;" src="<?= base_url('assets/gambar/dosen.png'); ?>">&nbsp;&nbsp;LOGIN DOSEN</b>
            </div>
            <div class="login-box-body " style="padding-top: 0px;">
                <p class="login-box-msg" style="font-size: 18px"> <br>Sistem Informasi Akademik <b>(SISKA)</b></p>

                <form action="<?= site_url('dosen/login_dosen/login'); ?>" method="post">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group has-feedback">
                        <input value="<?= set_value('email') ?>" type="text" class="form-control" placeholder="Alamat Email" name="email">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        <small class="text-danger"><?= form_error('email') ?></small>
                    </div>
                    <div class="form-group has-feedback">
                        <input value="<?= set_value('sandi') ?>" type="password" class="form-control" placeholder="Sandi" name="sandi">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        <small class="text-danger"><?= form_error('sandi') ?></small>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 pull-right">
                            <button type="submit" class="btn btn-primary btn-block btn-flat"><i class=""></i> Login</button>
                        </div>
                    </div>

                </form>
            </div>
            <?php
            $pesan = $this->session->flashdata('pesan');
            if (!empty($pesan)):
                ?>
                <div class="alert alert-dismissible flat animated fadeInDown" style="background-color: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <p><?= e($pesan) ?></p>
                </div>
            <?php else:endif; ?>
        </div>

        <script src="<?= site_url('assets/plugins/jQuery/jQuery-2.2.0.min.js') ?>"></script>
        <script src="<?= site_url('assets/bootstrap/js/bootstrap.js') ?>"></script>
        <script src="<?= site_url('assets/plugins/iCheck/icheck.js') ?>"></script>
    <?php $this->load->view('csrf_js'); ?>
    </body>
</html>
