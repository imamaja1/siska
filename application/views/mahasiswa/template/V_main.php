<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= isset($judul) ? e($judul) . ' - ' : '' ?>SISKA Universitas Bumigora</title>
    <link rel="icon" href="<?= app_favicon() ?>">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!--        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"  integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
    <!--        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.11/css/AdminLTE.min.css" />-->
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/AdminLTE.min.css'); ?>">
    <!-- Google Fonts: Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400;1,500;1,700&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,600;1,6..72,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/daterangepicker/daterangepicker-bs3.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/select2/select2.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/lobibox/dist/css/lobibox.min.css'); ?>">

    <link rel="stylesheet" href="<?= base_url('assets/dist/css/skins/_all-skins.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/sweetalert/dist/sweetalert2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/animate.css/animate.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/siska/css/demo_table.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/siska/mahasiswa.css?v=' . filemtime(FCPATH . 'assets/siska/mahasiswa.css')) ?>">

    <script src="<?= base_url('assets/plugins/jQuery/jQuery-2.2.0.min.js'); ?>"></script>
    <script src="<?= base_url('assets/plugins/chartjs/Chart.min.js'); ?>"></script>
    <script src="<?= base_url('assets/sweetalert/dist/sweetalert2.min.js') ?>"></script>
    <script src="<?= base_url('assets/tableedit/jquery.tabledit.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/lobibox/dist/js/lobibox.min.js') ?>"></script>
</head>
<body class="hold-transition skin-blue layout-top-nav">
<?php if ($this->session->userdata('is_impersonating')): ?>
<div style="background-color: #f39c12; color: white; text-align: center; padding: 5px; font-size: 13px;">
    Mode Impersonasi Mahasiswa | <a href="<?= site_url('admin/impersonasi_dosen/kembali') ?>" style="color: white; text-decoration: underline;" onclick="return confirm('Kembali ke akun admin?')">Kembali ke Admin</a>
</div>
<?php endif; ?>

<div class="wrapper">
    <header class="main-header">
        <nav class="navbar navbar-static-top">
            <div class="container siska-navbar-container">
                
                <!-- Left: Campus & App Identity -->
                <div class="siska-brand-wrapper">
                    <a href="<?= site_url('mahasiswa/home') ?>" class="siska-brand">
                        <img src="<?= app_logo() ?>" alt="Logo UBG" class="siska-brand-logo">
                        <div class="siska-brand-meta">
                            <span class="siska-brand-app">SISKA MAHASISWA</span>
                            <span class="siska-brand-institution">Universitas Bumigora</span>
                        </div>
                    </a>
                </div>
                
                <!-- Right: User Menu (pure CSS dropdown, desktop only) & Menu Toggle -->
                <div class="siska-header-actions">
                    <div class="siska-user-menu">
                        <button type="button" class="siska-user-trigger" aria-haspopup="true" aria-label="Menu Akun">
                            <i class="fa fa-user"></i>
                            <i class="fa fa-angle-down siska-user-caret"></i>
                        </button>
                        <div class="siska-user-panel" role="menu">
                            <a class="siska-user-item" role="menuitem" href="<?= site_url('mahasiswa/ganti_sandi') ?>">
                                <i class="fa fa-key"></i> Ubah Sandi
                            </a>
                            <a class="siska-user-item is-danger" role="menuitem" href="#!" onclick="konfirmasiKeluar('<?= site_url('Login/logout') ?>')">
                                <i class="fa fa-sign-out"></i> Logout
                            </a>
                        </div>
                    </div>

                    <button type="button" class="siska-mobile-toggle" id="btn-nav-toggle" aria-label="Buka Menu" aria-controls="navbar-collapse" aria-expanded="false">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>

                <!-- Navigation Menu (horizontal on desktop, right slide-in drawer on mobile) -->
                <?php $this->load->view('mahasiswa/template/V_menu') ?>

            </div>
        </nav>
    </header>
    <div class="content-wrapper">
        <div class="container">
            <?php if (empty($hide_page_header)): ?>
            <section class="content-header">
                <div style="font-size: 16px;">
                    <b><?= isset($judul) ? e($judul) : "" ?></b>
                </div>
            </section>
            <?php endif; ?>
            <section class="content">

                <?php $this->load->view($conten); ?>
            </section>
        </div>
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>SISKA v2.0</b> Terintegrasi
        </div>
        <strong>&copy; <?= date('Y') ?> Universitas Bumigora</strong>
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


<script>
    $('#myButton').on('click', function () {
        var $btn = $(this).button('loading')
        // business logic...
        $btn.button('reset')
    });

    $(document).ready(function() {
        if (typeof selec2init === 'function') {
            selec2init();
        }

        // Mobile right slide-in drawer
        var $drawer = $('#navbar-collapse');
        var $backdrop = $('#nav-backdrop');
        var $toggle = $('#btn-nav-toggle');

        function openDrawer() {
            $drawer.addClass('is-open');
            $backdrop.addClass('is-open');
            $toggle.addClass('is-active').attr('aria-expanded', 'true');
            $('body').addClass('nav-drawer-open');
        }
        function closeDrawer() {
            $drawer.removeClass('is-open');
            $backdrop.removeClass('is-open');
            $toggle.removeClass('is-active').attr('aria-expanded', 'false');
            $('body').removeClass('nav-drawer-open');
        }

        $toggle.on('click', function (e) {
            e.stopPropagation();
            if ($drawer.hasClass('is-open')) {
                closeDrawer();
            } else {
                openDrawer();
            }
        });
        $backdrop.on('click', closeDrawer);
        $(document).on('keyup', function (e) {
            if (e.keyCode === 27) closeDrawer();
        });
        $drawer.find('a').not('.dropdown-toggle').on('click', closeDrawer);
    });
</script>
<?php $this->load->view('csrf_js'); ?>
</body>
</html>
