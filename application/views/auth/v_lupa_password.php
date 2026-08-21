<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" sizes="96x96" href="<?=base_url('assets/gambar')?>/favicon-96x96.png">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/AdminLTE.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/animate.css/animate.min.css') ?>">
    <title>Lupa Password</title>
    <style>p {color: red;}body {background: #563c55 url('<?= base_url('assets/gambar/blurred5.jpg'); ?>') no-repeat center top;-webkit-background-size: cover;-moz-background-size: cover;background-size: cover;}.login-logo {color: white;text-shadow: 0.075em 0.08em 0.1em rgba(0, 0, 0, 1);}</style>
</head>
<body class="hold-transition ">
<div class="row">
    <div class="col-xs-12 col-md-8 col-lg-4">
        <div class="login-box ">
            <div class="login-logo text-green">
                <b>SISKA</b><br/>
                <small>UNIVERSITAS BUMIGORA</small>
            </div><!-- /.login-logo -->
            <div class="login-box-body">
                <h5 class="login-box-msg">Reset Password</h5>
                <?= $this->session->flashdata('pesan'); ?>
                <?= $this->session->flashdata('email_salah'); ?>
                <?= form_open('lupa_password'); ?>
                <div class="form-group has-feedback <?= (form_error('email')) ? 'has-error' : ''; ?>">
                    <input type="email" id="email" name="email" autocomplete="off" class="form-control"
                           placeholder="Masukkan email terdaftar" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <small class="text-red"><?php echo form_error('email'); ?></small>
                </div>
                <div class="form-group has-feedback <?= (form_error('captcha')) ? 'has-error' : ''; ?>">
                    <label class="control-label">Verifikasi: hitung hasilnya</label>
                    <div>
                        <img src="<?= site_url('Lupa_password/captcha_image'); ?>" style="margin-bottom:5px; border:1px solid #ddd; border-radius:4px;" alt="Captcha">
                    </div>
                    <input type="number" id="captcha" name="captcha" autocomplete="off" class="form-control"
                           placeholder="Jawaban" required>
                </div>
                <input type="submit" class="btn btn-primary btn-block btn-flat" name="submit" value="Kirim Link">
                <?= form_close(); ?>
                <hr>
                <a href="<?= site_url('login') ?>" class="btn-link">Kembali ke halaman login</a> <br>
          
            </div>
        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
</div>
<script src="<?= base_url('assets/plugins/jQuery/jQuery-2.2.0.min.js') ?>"></script>
<script src="<?= base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>
<?php $this->load->view('csrf_js'); ?>
</body>
</html>