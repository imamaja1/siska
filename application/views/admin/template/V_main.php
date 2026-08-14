<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if (isset($judul) || isset($sub_judul)): ?>
        <title>SISKA UBG | <?= e($judul) ?> - <?= e($sub_judul) ?></title>
    <?php else: ?>
        <title>SISKA UBG</title>
    <?php endif; ?>

    <link rel="icon" type="image/png" sizes="96x96" href="<?= base_url('assets/gambar') ?>/favicon-96x96.png">
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
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="stylesheet" href="<?= base_url('assets/siska/css/demo_table.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/siska/admin.css') ?>">

    <script src="<?= base_url('assets/plugins/jQuery/jQuery-2.2.0.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/chartjs/Chart.min.js'); ?>"></script>
<script src="<?= base_url('assets/sweetalert/dist/sweetalert2.min.js') ?>"></script>
<script>window.addEventListener('unhandledrejection',function(e){if(e.reason&&(e.reason==='overlay'||e.reason==='cancel'||e.reason==='close'))e.preventDefault()});</script>
<script src="<?= base_url('assets/tableedit/jquery.tabledit.min.js') ?>?v=2"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">

<?php if ($this->session->userdata('is_impersonating')): ?>
<div style="background-color: #f39c12; color: white; text-align: center; padding: 5px; font-size: 13px;">
    Mode Impersonasi Pengguna | <a href="<?= site_url('admin/pengguna/pengguna/kembali') ?>" style="color: white; text-decoration: underline;" onclick="return confirm('Kembali ke akun admin?')">Kembali ke Admin</a>
</div>
<?php endif; ?>

<div class="wrapper">
    <header class="main-header">
        <a href="<?php echo site_url('home/admin'); ?>" class="logo">
            <span class="logo-mini"><b>S</b>IS</span>
            <span class="logo-lg" style="font-size: 20px; font-family: calibri;"><b>SISKA ADMIN</b></span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <a class="navbar-brand" style="font-size: 16px;"><b> <?= e($this->session->userdata('sekarang')) ?></b></a>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?= base_url() ?>assets/gambar/admin.png"
                                     class="user-image"><?= e($this->session->userdata('nama_pengguna')) ?>&nbsp;

                                <span class="hidden-xs"> <i class="fa fa-angle-down"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header" style="height: 100px; color: #fff;">

                                    <p>
                                        Login sebagai: <br><?= e($this->session->userdata('nama_pengguna')) ?>
                                    </p>
                                </li>
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="<?= site_url('admin/pengguna/ganti_sandi'); ?>"
                                           class="btn btn-default btn-flat"><i class="fa fa-key"></i> Ganti Sandi</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="#!"
                                           onclick="konfirmasiKeluar('<?= site_url('admin/login_admin/logout') ?>')"
                                           class="btn btn-default btn-flat"><i class="fa fa-sign-out"></i> Keluar</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li style="padding-right: 20px;">
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <?php $this->load->view('admin/template/V_menu'); ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div style="font-size: 18px;">
                <b><?= e($sub_judul) ?></b>
            </div>
            <ol class="breadcrumb">
                <?= isset($title_h1) ? $title_h1 : "" ?>
                <?= isset($title_h2) ? $title_h2 : "" ?>
                <?= isset($title_h3) ? $title_h3 : "" ?>
            </ol>
        </section>
        <section class="content">
            <?php $this->load->view($content); ?>
        </section>
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 1.0 &mdash; Page rendered in <strong>{elapsed_time}</strong> seconds.
        </div>
        <strong>Copyright &copy; <?= date('Y') ?> PusTIK Universitas Bumigora</strong>
    </footer>
</div>
<script src="<?= base_url('assets/plugins/input-mask/jquery.inputmask.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/input-mask/jquery.inputmask.date.extensions.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/select2/select2.full.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/fastclick/fastclick.min.js'); ?>"></script>
<script src="<?= base_url('assets/bootstrap/js/tooltip.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/jQueryUI/jquery-ui.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/daterangepicker/moment.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/daterangepicker/daterangepicker.js'); ?>"></script>
<!--<script src="--><?//= base_url('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'); ?><!--"></script>-->
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
<script src="<?= base_url('assets/siska/admin.js'); ?>"></script>
<script src="<?= base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/slimScroll/jquery.slimscroll.min.js'); ?>"></script>
<script src="<?= base_url('assets/dist/js/app.min.js'); ?>"></script>
<script src="<?= base_url('assets/dist/js/demo.js'); ?>"></script>
<script src="<?= base_url('assets/siska/js/detail_mhs.js'); ?>"></script>

<script>
    $(".data-table").dataTable({
        "ordering": false,
        "info": false,
        "pageLength": 50,
        columnDefs: [{
            orderable: false,
            targets: "no-sort"
        }]
    });
</script>
  <script>
        $(".data-nilai").dataTable({
            "ordering": true,
            "pageLength": 50,
            columnDefs: [{
                orderable: false,
                targets: "no-sort"
            }],
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua Data"] ]
        });
    </script>
    <script>
        $(".data-nilai2").dataTable({
            "ordering": true,
            "pageLength": 50,
            columnDefs: [{
                orderable: false,
                targets: "no-sort"
            }],
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua Data"] ]
        });
      
      </script>

<script>
    function konfirmasiKeluar(url) {
        if (confirm('Apakah Anda yakin ingin keluar?')) {
            window.location.href = url;
        }
    }
</script>

<?php $this->load->view('csrf_js'); ?>
</body>
</html>