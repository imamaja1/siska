<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php $sub_judul = isset($sub_judul) ? $sub_judul : ''; $judul = isset($judul) ? $judul : ''; ?>
        <?php if (!empty($judul) || !empty($sub_judul)): ?>
            <title>SISKA UBG | <?php echo e($judul) . " - " . e($sub_judul); ?></title>
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
        <script src="<?= base_url('assets/tableedit/jquery.tabledit.min.js') ?>"></script>

        <style>
            .badge-notif {
                position:relative;
            }

            .badge-notif[data-badge]:after {
                    content:attr(data-badge);
                    position:absolute;
                    top:-10px;
                    right:-10px;
                    font-size:.7em;
                    background:#e53935;
                    color:white;
                    width:18px;
                    height:18px;
                    text-align:center;
                    line-height:18px;
                    border-radius: 50%;
            }
        </style>

    </head>
    <body class="hold-transition skin-blue sidebar-mini">
<?php if ($this->session->userdata('is_impersonating')): ?>
<div style="background-color: #f39c12; color: white; text-align: center; padding: 5px; font-size: 13px;">
    Mode Impersonasi Dosen | <a href="<?= site_url('admin/impersonasi_dosen/kembali') ?>" style="color: white; text-decoration: underline;" onclick="return confirm('Kembali ke akun admin?')">Kembali ke Admin</a>
</div>
<?php endif; ?>

        <div class="wrapper">
            <header class="main-header">
                <a href="<?php echo site_url('dosen/home'); ?>" class="logo">
                    <span class="logo-mini"><i class="fa fa-graduation-cap"></i></span>
                    <span class="logo-lg" style="font-size: 16px; font-weight: 700; letter-spacing: 0.5px;">
                        <i class="fa fa-graduation-cap" style="margin-right: 6px;"></i>SISKA DOSEN
                    </span>
                </a>
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
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
                                    <span class="hidden-xs"><?= e($this->session->userdata('nama_dosen')) ?> <i class="fa fa-angle-down" style="font-size: 11px; margin-left: 4px;"></i></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="user-header">
                                        <i class="fa fa-user admin-user-header-icon" aria-hidden="true"></i>
                                        <p>
                                            <?= e($this->session->userdata('nama_dosen')) ?>
                                            <small>Dosen</small>
                                        </p>
                                    </li>
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="<?= site_url('dosen/ganti_sandi'); ?>" class="btn btn-default btn-sm"><i class="fa fa-key"></i> Ganti Sandi</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="#!" onclick="konfirmasiKeluar('<?= site_url('dosen/login_dosen/logout') ?>')" class="btn btn-danger btn-sm"><i class="fa fa-sign-out"></i> Logout</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <?php $this->load->view('dosen/template/V_menu'); ?>
            <div class="content-wrapper">


                <section class="content-header">
                    <h1>
                        <?= e($judul) ?>
                        <!--<small>Control panel</small>-->
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> <?= e($judul) ?></a></li>
                        <li class="active"><?= e(isset($sub_judul) ? $sub_judul : '') ?></li>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>



        <script>
            $(".data-table").dataTable({
                "ordering": false,
                // "order": [[1, 'desc']]
                "info": false,
                "pageLength": 50
            });
            $(".validasi-table").dataTable({
                "order": [[8, 'desc']],
                "info": false,
                "pageLength": 50
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
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua Data"]]
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
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua Data"]]
            });
        </script>

        <script>
            $(".data-nilai3").dataTable({
                "ordering": true,
                "pageLength": 10,
                columnDefs: [{
                        orderable: false,
                        targets: "no-sort"
                    }],
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua Data"]]
            });
        </script>
        <script>
            $(document).ready(function() {
                if (typeof selec2init === 'function') {
                    selec2init();
                }
            });
        </script>

    <?php $this->load->view('csrf_js'); ?>
    </body>
</html>