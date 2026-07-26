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
    <title>Form Reset</title>
    <style>p {color: red;}body {background: #563c55 url('<?= base_url('assets/gambar/blurred5.jpg'); ?>') no-repeat center top;-webkit-background-size: cover;-moz-background-size: cover;background-size: cover;}.login-logo {color: white;text-shadow: 0.075em 0.08em 0.1em rgba(0, 0, 0, 1);}</style>
</head>

<body>

<body class="hold-transition ">
<div class="row">
    <div class="col-xs-12 col-md-8 col-lg-4">
        <div class="login-box ">
            <div class="login-logo text-green">
                <b>SISKA</b><br/>
                <small>UNIVERSITAS BUMIGORA</small>
            </div><!-- /.login-logo -->
            <div class="login-box-body">
                <h5 class="login-box-msg">Form Password Baru</h5>
                <p style="color: green">Reset Password atas nama : <br> <b><?php echo $nama; ?></b></p>
                <?php if (!$this->session->flashdata('pesan')): ?>
                    <?php echo form_open('lupa_password/reset_password/token/' . $token); ?>
                    <div class="form-group has-feedback <?= (form_error('password')) ? 'has-error' : ''; ?>">
                        <input type="password" id="password"  name="password" autocomplete="off" class="form-control" placeholder="Masukkan Password baru">
                        <small class="text-red"><?php echo form_error('password'); ?></small>
                    </div>
                    <div class="form-group has-feedback <?= (form_error('passconf')) ? 'has-error' : ''; ?>">
                        <input type="password" id="passconf" name="passconf" autocomplete="off" class="form-control" placeholder="Masukkan Konfirmasi Password">
                        <small class="text-red"><?php echo form_error('passconf'); ?></small>
                    </div>
                    <input type="submit" class="btn btn-primary btn-block btn-flat" value="Simpan Password" name="submit">
                    <?= form_close(); ?>
                <?php
                else:
                    echo $this->session->flashdata('pesan');
                endif;
                ?>
                <hr>
                <a href="<?= site_url('login') ?>" class="btn-link">Kembali ke halaman login</a> <br>
                
            </div>
        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
</div>
<script src="<?= base_url('assets/plugins/jQuery/jQuery-2.2.0.min.js') ?>"></script>
<script src="<?= base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>
<script>$(".alert").fadeTo(3000, 500).slideUp(1000, function () {$(".alert").alert('close');});</script>
<?php $this->load->view('csrf_js'); ?>
</body>

</html>