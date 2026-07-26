<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>SISKA UNIVERSITAS BUMIGORA</title>
        <link rel="icon" type="image/png" sizes="96x96" href="<?= base_url('assets/gambar') ?>/favicon-96x96.png">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <!--        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"  integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
        <!--        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.11/css/AdminLTE.min.css" />-->
        <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/dist/css/AdminLTE.min.css'); ?>">
        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/plugins/select2/select2.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/plugins/lobibox/dist/css/lobibox.min.css'); ?>">

        <link rel="stylesheet" href="<?= base_url('assets/dist/css/skins/_all-skins.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/font-awesome/css/font-awesome.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/sweetalert/dist/sweetalert2.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/animate.css/animate.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/siska/css/demo_table.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/siska/mahasiswa.css') ?>">

        <script src="<?= base_url('assets/plugins/jQuery/jQuery-2.2.0.min.js'); ?>"></script>
        <script src="<?= base_url('assets/tableedit/jquery.tabledit.min.js') ?>"></script>
    </head>
    <body class="hold-transition skin-blue layout-top-nav">

        <div class="wrapper">
            <!--            <header class="main-header">
                            <nav class="navbar navbar-static-top">
                                <div class="container">
                                    <div class="navbar-header">
                                        <a href="#" class="navbar-brand"><b>UNIVERSITAS BUMIGORA</b></a>
                                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                                data-target="#navbar-collapse">
                                            <i class="fa fa-bars"></i>
                                        </button>
                                    </div>
            
            
                                </div>
                            </nav>
                        </header>-->
            <div class="content-wrapper">
                <div class="container">
                    <section class="content-header">
                        <div style="font-size: 18px;">
                            <b><img src="<?= base_url('assets/gambar/logo_nilai.png') ?>" width="150px;"></b>
                        </div>
                    </section>
                    <section class="content">

                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title"><b>Verifikasi Nilai</b></h3>
                            </div>
                            <div class="box-body" style="font-size: 16px;   ">
                                <center>
                                    <img src="<?= base_url('assets/gambar/verifikasi.png') ?>" width="100px;">
                                </center>
                                <br>
                                <table>
                                    <tr>
                                        <td>Status Dokumen <br><b style="color: #2c96ff;">AKTIF</b></td>
                                    </tr>

                                </table>
                                <br>
                                <table>
                                    <tr>
                                        <td><b>Info Matakuliah</b> <br><?= e($query1->mtkm) ?> - <?= e($query1->nama_matakuliah) ?></td>
                                    </tr>

                                </table>
                                <br>
                                <table>
                                    <tr>
                                        <td><b>Info Pendantangan</b> <br>
                                            <ol>
                                                <li>Dekan <?= e($query1->nama_fakultas) ?> : <?= e($query3->dosen_fakultas) ?></li>
                                                <li>Ketua Program Studi <?= e($query1->nama_program_studi) ?> : <?= e($query4->dosen_program_studi) ?></li>
                                               <li>Dosen Pengampu : <?= e($nama_dosen->nama_dosen) ?> </li>
                                            </ol>
                                        </td>
                                    </tr>

                                </table>
                                <br>
                                <table>
                                    <tr>
                                        <td><b>Ditanda tangani di</b> <br>
                                            Universitas Bumigora - Jl. Ismail Marzuki No.22, Cilinaya, Kec. Cakranegara, Kota Mataram, Nusa Tenggara Bar. 83127.
                                        </td>
                                    </tr>

                                </table>
                                <br>
                                <table>
                                    <tr>
                                        <td><b>Informasi tanggal</b> <br>
                                            Tgl. Penandatangan: <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar"></i>&nbsp;<?= date('d/m/Y', strtotime($query4->tanggal_tandantangan)) ?>
                                            &nbsp;&nbsp;<i class="fa fa-clock-o"></i> <?= date('h:i:s', strtotime($query4->tanggal_tandantangan)) ?> <br>

                                            Tgl. Pindai: <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar"></i>&nbsp;<span id="tanggal"></span> 
                                            &nbsp;&nbsp;<i class="fa fa-clock-o"></i> <span id="waktu"></span>
                                        </td>
                                    </tr>

                                </table>
                                <br>

<!--                                <p>Date/Time: </p>-->

                                <script>
                                    var dt = new Date();
                                    document.getElementById("tanggal").innerHTML = dt.toLocaleDateString("id-ID",{ day: '2-digit', month: '2-digit', year: 'numeric'});

                                    var dt = new Date();
                                    document.getElementById("waktu").innerHTML = dt.toLocaleTimeString();
                                </script>

                            </div>

                        </div>
                    </section>
                </div>
            </div>
            <footer class="main-footer">
                <div class="container">
                    <div class="pull-right hidden-xs">
                        <b>Version</b> 1.0
                    </div>
                    <strong>Copyright &copy; <?= date('Y') ?> Universitas Bumigora Mataram</a>.</strong>
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


        <script src="<?= base_url('assets/siska/mahasiswa.js'); ?>"></script>

        <script src="<?= base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
        <script src="<?= base_url('assets/plugins/slimScroll/jquery.slimscroll.min.js'); ?>"></script>
        <script src="<?= base_url('assets/dist/js/app.min.js'); ?>"></script>
        <script src="<?= base_url('assets/dist/js/demo.js'); ?>"></script>



    <?php $this->load->view('csrf_js'); ?>
    </body>
</html>
