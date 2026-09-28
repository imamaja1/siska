<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SISKA STMIK BG</title>
    <link rel="icon" href="<?= app_favicon() ?>"/>
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
    <link rel="stylesheet" href="<?= base_url('assets/siska/css/demo_table.css') ?>">

    <script src="<?= base_url('assets/plugins/jQuery/jQuery-2.2.0.min.js'); ?>"></script>
    <script src="<?= base_url('assets/plugins/chartjs/Chart.min.js'); ?>"></script>
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
    </style>

</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">

    <!-- Full Width Column -->
    <div class="content-wrapper">
        <div class="container">
            <!-- Main content -->
            <section class="content">

                <?php $this->load->view($content); ?>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.container -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="container">
            <div class="pull-right hidden-xs">
                Page rendered in <strong>{elapsed_time}</strong> seconds.
            </div>

            <strong>Copyright &copy; <?php echo Date('Y'); ?> Pusat Teknologi Informasi & Komunikasi (PusTIK) <a href="<?php echo base_url() ?>">STMIK BUMIGORA MATARAM</a>.</strong>
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
<script src="<?= base_url('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'); ?>"></script>
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
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
    var selec2init = function (context) {
        if (!$.fn.select2) return;
        var $scope = context ? $(context) : $(document);
        var $targets = $scope.is('select') ? $scope : $scope.find('select');
        $targets.each(function () {
            var $this = $(this);
            if ($this.hasClass('select2-hidden-accessible') ||
                $this.closest('.dataTables_length').length ||
                $this.is('[name$="_length"]') ||
                $this.closest('.daterangepicker').length ||
                $this.hasClass('swal2-select') ||
                $this.hasClass('no-select2')) {
                return;
            }
            var width = '100%';
            if ($this.closest('.form-inline').length) {
                width = ($this.attr('style') && $this.attr('style').indexOf('width') !== -1) ? 'resolve' : 'auto';
            }
            var opts = { width: width };
            var $modal = $this.closest('.modal');
            if ($modal.length) {
                opts.dropdownParent = $modal;
            }
            $this.addClass('select2').select2(opts);
        });
    };
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $.widget.bridge('uibutton', $.ui.button);
        selec2init();
    });

    $(document).on('shown.bs.modal', function (e) {
        selec2init(e.target);
    });

    $(document).ajaxComplete(function () {
        selec2init();
    });
</script>
<script src="<?= base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/slimScroll/jquery.slimscroll.min.js'); ?>"></script>
<script src="<?= base_url('assets/dist/js/app.min.js'); ?>"></script>
<script src="<?= base_url('assets/dist/js/demo.js'); ?>"></script>

<?php $this->load->view('csrf_js'); ?>
</body>
</html>
