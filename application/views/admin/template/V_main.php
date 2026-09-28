<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php $judul = isset($judul) ? $judul : ''; $sub_judul = isset($sub_judul) ? $sub_judul : ''; ?>
    <?php if ($judul !== '' || $sub_judul !== ''): ?>
        <title>SISKA UBG | <?= e($judul) ?> - <?= e($sub_judul) ?></title>
    <?php else: ?>
        <title>SISKA UBG</title>
    <?php endif; ?>

    <link rel="icon" href="<?= app_favicon() ?>">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/daterangepicker/daterangepicker-bs3.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/select2/select2.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/lobibox/dist/css/lobibox.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/AdminLTE.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/skins/_all-skins.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/sweetalert/dist/sweetalert2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/animate.css/animate.min.css') ?>">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/siska/css/demo_table.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/siska/admin.css?v=' . filemtime(FCPATH . 'assets/siska/admin.css')) ?>">

    <script src="<?= base_url('assets/plugins/jQuery/jQuery-2.2.0.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/chartjs/Chart.min.js'); ?>"></script>
<script src="<?= base_url('assets/sweetalert/dist/sweetalert2.min.js') ?>"></script>
<script>window.addEventListener('unhandledrejection',function(e){if(e.reason&&(e.reason==='overlay'||e.reason==='cancel'||e.reason==='close'))e.preventDefault()});</script>
<script src="<?= base_url('assets/plugins/lobibox/dist/js/lobibox.min.js') ?>"></script>
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
            <span class="logo-mini"><i class="fa fa-graduation-cap"></i></span>
            <span class="logo-lg" style="font-size: 16px; font-weight: 700; letter-spacing: 0.5px;">
                <i class="fa fa-graduation-cap" style="margin-right: 6px;"></i>SISKA ADMIN
            </span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <?php if ($this->session->userdata('sekarang')): ?>
                <span class="navbar-academic-badge hidden-xs"><i class="fa fa-calendar-check-o"></i> <?= e($this->session->userdata('sekarang')) ?></span>
            <?php endif; ?>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-user admin-user-icon" aria-hidden="true"></i>
                            <span class="hidden-xs"><?= e($this->session->userdata('nama_pengguna')) ?> <i class="fa fa-angle-down" style="font-size: 11px; margin-left: 4px;"></i></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <i class="fa fa-user admin-user-header-icon" aria-hidden="true"></i>
                                <p>
                                    <?= e($this->session->userdata('nama_pengguna')) ?>
                                    <small>Administrator</small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="<?= site_url('admin/pengguna/ganti_sandi'); ?>" class="btn btn-default btn-sm"><i class="fa fa-key"></i> Ganti Sandi</a>
                                </div>
                                <div class="pull-right">
                                    <a href="#!" onclick="konfirmasiKeluar('<?= site_url('admin/login_admin/logout') ?>')" class="btn btn-danger btn-sm"><i class="fa fa-sign-out"></i> Keluar</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <?php $this->load->view('admin/template/V_menu'); ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div style="font-size: 16px;">
                <b><?= !empty($sub_judul) ? e($sub_judul) : 'Dashboard' ?></b>
            </div>
            <ol class="breadcrumb">
                <?php if (isset($title_h1) || isset($title_h2) || isset($title_h3)): ?>
                    <?= isset($title_h1) ? $title_h1 : "" ?>
                    <?= isset($title_h2) ? $title_h2 : "" ?>
                    <?= isset($title_h3) ? $title_h3 : "" ?>
                <?php else: ?>
                    <li><a href="<?php echo site_url('home/admin'); ?>"><i class="fa fa-home"></i> <?= !empty($judul) ? e($judul) : 'Dashboard' ?></a></li>
                <?php endif; ?>
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
<script src="<?= base_url('assets/siska/admin.js?v=' . filemtime(FCPATH . 'assets/siska/admin.js')); ?>"></script>
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

    $(document).ready(function() {
        if (typeof selec2init === 'function') {
            selec2init();
        }
    });
</script>

<?php $this->load->view('csrf_js'); ?>
</body>
</html>