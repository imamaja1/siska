<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>SISKA UBG</title>
        <link rel="icon" type="image/png" sizes="96x96" href="<?= base_url('assets/gambar') ?>/favicon-96x96.png">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/plugins/daterangepicker/daterangepicker-bs3.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/plugins/select2/select2.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/plugins/lobibox/dist/css/LobiBox.min.css'); ?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.11/css/AdminLTE.min.css" />
        <link rel="stylesheet" href="<?= base_url('assets/dist/css/skins/_all-skins.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/font-awesome/css/font-awesome.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/sweetalert/dist/sweetalert2.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/animate.css/animate.min.css') ?>">

        <link rel="stylesheet" href="<?= base_url('assets/siska/css/demo_table.css') ?>">

        <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js"></script>
        <script src="<?= base_url('assets/sweetalert/dist/sweetalert2.min.js') ?>"></script>
        <script src="<?= base_url('assets/tableedit/jquery.tabledit.min.js') ?>"></script>
        <script src="<?= base_url('assets/plugins/lobibox/dist/js/lobibox.min.js') ?>"></script>
        <script src="<?= base_url('assets/plugins/lobibox/dist/js/lobibox.js') ?>"></script>

        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <style>
            .th-color{
                background-color:#b0c4de;
            }
        </style>
        <style type="text/css">
            #kotak-pesan {
                position:fixed !important;
                /* position:absolute;right:10px; */
                left:75%;bottom:10%;margin:0 auto;width:400px;
                background:#fff;padding:16px; padding-right: 100px;
                border:2px solid #3c8dbc;color:#3c8dbc;
                font:normal 1em Cambria,Georgia,Serif;
                box-shadow:0px 1px 3px rgba(0,0,0,0.4);}

            #kotak-pesan a.close:hover {background:red;color:#fff;}
            #kotak-pesan a.close {
                position:absolute;
                top:5px;right:5px;background:#fff;
                font:bold 13px Arial,Sans-Serif;
                line-height:15px;width:15px;text-align:center;
                color:red;border:2px solid #fff;
                box-shadow:0px 1px 2px rgba(0,0,0,0.4);
                border-radius:15px;cursor:pointer;}

            #th{
                text-align: center;
            }
        </style>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <nav class="navbar navbar-static-top">
                    <div class="container">
                        <div class="navbar-header">
                            <a href="#" class="navbar-brand"><b>SISKA DOSEN</b></a>
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                                <i class="fa fa-bars"></i>
                            </button>
                        </div>

                        <?php $this->load->view('dosen/template/V_menu') ?>

                        <div class="navbar-custom-menu">
                            <ul class="nav navbar-nav">

                                <li class="dropdown user user-menu">
                                    Menu Toggle Button 
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <span><img src="<?= base_url('assets/gambar/dosen.png'); ?>" class="user-image" alt="User Image">&nbsp;<?= substr($this->session->userdata('nama_dosen'), 0, 10) ?>... <i class="fa fa-angle-down"></i></span>
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
                    </div>
                </nav>
            </header>
            <div class="content-wrapper">
                <div class="container">
                    Content Header (Page header) 
                    <section class="content-header">
                        <div style="font-size: 18px;">
                            <b><?= $judul ?></b>
                        </div>
                        <ol class="breadcrumb">
                            <?= isset($title_h1) ? $title_h1 : ""; ?>
                            <?= isset($title_h2) ? $title_h2 : ""; ?>
                            <?= isset($title_h3) ? $title_h3 : ""; ?>
                        </ol>
                    </section>
                    <section class="content">
                        <?php $this->load->view($content); ?>
                    </section>
                </div>
            </div>
            <footer class="main-footer">
                <div class="container">
                    <div class="pull-right hidden-xs">
                        Page rendered in <strong>{elapsed_time}</strong> seconds. 
                    </div>

                    <strong>Copyright &copy; <?php echo Date('Y'); ?> Pusat Teknologi Informasi & Komunikasi (PusTIK) <a href="<?php echo base_url() ?>">UNIVERSITAS BUMIGORA MATARAM</a>.</strong>
                </div>
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
        <!--<script src="<?= base_url('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'); ?>"></script>-->
        <script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
        <script>
                                                    $(function () {
                                                        // Daterange Picker
                                                        $('#tanggal').daterangepicker({
                                                            singleDatePicker: true,
                                                            showDropdowns: true,
                                                            format: 'DD/MM/YYYY'
                                                        });
                                                        $('#tanggal1').daterangepicker({
                                                            singleDatePicker: true,
                                                            showDropdowns: true,
                                                            format: 'DD/MM/YYYY'
                                                        });
                                                        // Data Table
                                                        $("#data").dataTable({
                                                            scrollX: true
                                                        });
                                                    });
        </script>

        <script>
            $(function () {
                $("#example1").DataTable();
                $(".data-table").DataTable({
                    columnDefs: [
                        {targets: 'no-sort', orderable: false}
                    ],
                    "pageLength": 50
                });
                $('#example2').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": false,
                    "info": true,
                    "autoWidth": false
                });
            });
            var selec2init = function () {
                $(".select2").select2();
            }
        </script>

        <script type="text/javascript">
            $(document).ready(function () {
                $.widget.bridge('uibutton', $.ui.button);
                selec2init();

            });
        </script>
        <script src="<?= base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
        <script src="<?= base_url('assets/plugins/slimScroll/jquery.slimscroll.min.js'); ?>"></script>
        <script src="<?= base_url('assets/dist/js/app.min.js'); ?>"></script>
        <script src="<?= base_url('assets/dist/js/demo.js'); ?>"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
        <script src="<?= base_url('assets/siska/js/detail_mhs.js'); ?>"></script>
        <script>
            function konfirmasiKeluar(url) {
                swal({
                    title: "",
                    text: "Anda yakin ingin keluar dari sistem ini?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: 'red',
                    cancelButtonText: 'Tidak',
                    confirmButtonText: 'Ya',
                    closeOnConfirm: false
                }).then(function () {
                    window.location.href = url;
                })
            }
        </script>
    <?php $this->load->view('csrf_js'); ?>
    </body>
</html>