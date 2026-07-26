<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php $sub_judul = isset($sub_judul) ? $sub_judul : ''; $judul = isset($judul) ? $judul : ''; ?>
        <?php if (!empty($judul) || !empty($sub_judul)): ?>
            <title>SISKA UBG | <?php echo $judul . " - " . $sub_judul; ?></title>
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
                <a href="#" class="logo">
                    <span class="logo-mini"><b>S</b>IS</span>
                    <span class="logo-lg" style="font-size: 20px; font-family: calibri;"><b>SISKA DOSEN</b></span>
                </a>
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>

                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            
<!--                            <li class="dropdown messages-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-envelope-o"></i>
                                    <span class="label label-success">4</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">You have 4 messages</li>
                                    <li>
                                         inner menu: contains the actual data 
                                        <ul class="menu">
                                            <li> start message 
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                                    </div>
                                                    <h4>
                                                        Support Team
                                                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                                    </h4>
                                                    <p>Why not buy a new awesome theme?</p>
                                                </a>
                                            </li>
                                             end message 
                                            <li>
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
                                                    </div>
                                                    <h4>
                                                        AdminLTE Design Team
                                                        <small><i class="fa fa-clock-o"></i> 2 hours</small>
                                                    </h4>
                                                    <p>Why not buy a new awesome theme?</p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
                                                    </div>
                                                    <h4>
                                                        Developers
                                                        <small><i class="fa fa-clock-o"></i> Today</small>
                                                    </h4>
                                                    <p>Why not buy a new awesome theme?</p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
                                                    </div>
                                                    <h4>
                                                        Sales Department
                                                        <small><i class="fa fa-clock-o"></i> Yesterday</small>
                                                    </h4>
                                                    <p>Why not buy a new awesome theme?</p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
                                                    </div>
                                                    <h4>
                                                        Reviewers
                                                        <small><i class="fa fa-clock-o"></i> 2 days</small>
                                                    </h4>
                                                    <p>Why not buy a new awesome theme?</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="footer"><a href="#">See All Messages</a></li>
                                </ul>
                            </li>
                        
                            <li class="dropdown notifications-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-bell-o"></i>
                                    <span class="label label-warning">10</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">You have 10 notifications</li>
                                    <li>
                                         inner menu: contains the actual data 
                                        <ul class="menu">
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                                                    page and may cause design problems
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-users text-red"></i> 5 new members joined
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-user text-red"></i> You changed your username
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="footer"><a href="#">View all</a></li>
                                </ul>
                            </li>-->

                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="<?= base_url('assets/gambar/dosen.png'); ?>" class="user-image" alt="User Image">
                                    <span class="hidden-xs">&nbsp;<?= substr($this->session->userdata('nama_dosen'), 0, 10) ?>... <i class="fa fa-angle-down"></i></span>

                                </a>
                                <ul class="dropdown-menu">

                                    <li class="user-header" style="height: 100px;">

                                        <p style="color: white;">
                                            Login Sebagai:<br>
                                            <?= $this->session->userdata('nama_dosen') ?>
                                        </p>
                                    </li>

                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="<?= site_url('dosen/ganti_sandi'); ?>" class="btn-default flat btn"><i class="fa fa-key"></i> Ganti Sandi</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="#!" onclick="konfirmasiKeluar('<?= site_url('dosen/login_dosen/logout') ?>')" class="btn btn-default flat"><i class="glyphicon glyphicon-off"></i> Logout</a>
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
                        <?= $judul ?>
                        <!--<small>Control panel</small>-->
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> <?= $judul ?></a></li>
                        <li class="active"><?= isset($sub_judul) ? $sub_judul : '' ?></li>
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

    <?php $this->load->view('csrf_js'); ?>
    </body>
</html>