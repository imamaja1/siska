<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php if (isset($judul) || isset($sub_judul)): ?>
            <title>SISKA STMIK BG | <?php echo $judul . " - " . $sub_judul; ?></title>
        <?php else: ?>
            <title>SISKA STMIK BG</title>
        <?php endif; ?>

        <link rel="icon" href="<?php echo base_url('assets/siska/img/logo_kampus.png') ?>" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/plugins/daterangepicker/daterangepicker-bs3.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/plugins/select2/select2.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/dist/css/AdminLTE.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/dist/css/skins/_all-skins.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/font-awesome/css/font-awesome.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/sweetalert/dist/sweetalert2.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/animate.css/animate.min.css') ?>">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" href="<?= base_url('assets/siska/css/demo_table.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/siska/admin.css') ?>">

    </head>
    <body class="hold-transition skin-blue layout-top-nav" style="background-color: #ecf0f5; padding-top: 100px;">
    <center>
        <div>
            <img width="200px" height="200px" src="<?= site_url('assets/gambar/setting.png'); ?>"><br>
            <h3>Mohon maaf, halaman ini masih dalam proses pengembangan<br> oleh tim<b> developers STMIK Bumigora Mataram</b></h3>
            <button onclick="window.history.back();" class="btn btn-default flat"><i class="fa fa-arrow-left"></i> Kembali</button>

        </div>
    </center>

</body>
</html>