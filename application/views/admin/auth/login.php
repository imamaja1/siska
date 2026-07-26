
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Login SISKA</title>
        <link rel="icon" href="<?php echo base_url('assets/siska/img/logo_kampus.png') ?>" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?= base_url(); ?>assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?= base_url(); ?>assets/dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/iCheck/square/blue.css">

    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="#"><b>STMIK </b>Bumigora</a>
            </div>
            <div class="login-box-body">
                <p class="login-box-msg">LOGIN SISKA</p>
                <form action="<?= site_url('auth/login') ?>" method="post">
                    <div class="form-group has-feedback">
                        <input value="<?= set_value('identity') ?>" type="text" class="form-control" name="identity"  placeholder="Email">
                        <small class="text-danger"><?= form_error('identity') ?></small>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input value="<?= set_value('password') ?>" type="password" class="form-control" name="password" placeholder="Password">
                        <small class="text-danger"><?= form_error('password') ?></small>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-8" style="padding-left: 35px;">
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox" id="remember"> Ingat Saya
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat"><i class="fa fa-unlock"></i> Masuk</button>
                        </div>
                    </div>
                    <hr>
                    <a href="<?= site_url('auth/forgot_password'); ?>">Saya Lupas Password</a><br>
                </form>
            </div>
        </div>

        <script src="<?= base_url(); ?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="<?= base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?= base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>
    <?php $this->load->view('csrf_js'); ?>
    </body>
</html>


