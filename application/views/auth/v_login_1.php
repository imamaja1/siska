<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="icon" type="image/png" sizes="96x96" href="<?= base_url('assets/gambar') ?>/favicon-96x96.png">
        <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/dist/css/AdminLTE.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/animate.css/animate.min.css') ?>">
        <title>Log In</title>
        <style>p {
                color: red;
            }

            body {
                background: #563c55 url('<?= base_url('assets/gambar/blurred5.jpg'); ?>') no-repeat center top;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                background-size: cover;
            }

            .login-logo {
                color: white;
                text-shadow: 0.075em 0.08em 0.1em rgba(0, 0, 0, 1);
            }</style>
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
                        <h5 class="login-box-msg">Silahkan Masukkan Data Anda</h5>
                        <?php if ($this->session->flashdata('pesan')): ?>
                            <div class="alert animated fadeInUp alert-<?= ($this->session->flashdata('tipe') == 'danger') ? 'danger' : 'success' ?>">
                                <h6><?= $this->session->flashdata('pesan'); ?></h6>
                            </div>
                            <?php
                        endif;
                        ?>
                        <?= form_open('login'); ?>
                        <div class="form-group has-feedback <?= (form_error('username')) ? 'has-error' : ''; ?>">
                            <input type="text" pattern=".{6,}" required title="Minimum 6 Characters" id="username" required
                                   name="username" autocomplete="off" value="<?= set_value('username') ?>" class="form-control"
                                   placeholder="Isi dengan nim atau email anda">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                            <?php
                            if (!form_error('username')) :
                                ?>
                                <small class="text-green">Mahasiswa Menggunakan NIM, Dosen Menggunakan Email</small>
                                <?php
                            else :
                                ?>
                                <small class="text-red"><?php echo form_error('username'); ?></small>
                            <?php
                            endif;
                            ?>
                        </div>
                        <div class="form-group has-feedback <?= (form_error('password')) ? 'has-error' : ''; ?>">
                            <input type="password" pattern=".{6,}" title="Minimum 6 Characters" name="password"
                                   value="<?= set_value('password') ?>" class="form-control" placeholder="Password" required>
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            <small class="text-red"><?php echo form_error('password'); ?></small>
                        </div>
                        <div class="form-group has-feedback <?= (form_error('status')) ? 'has-error' : ''; ?>">
                            <select required name="status" class="form-control">
                                <option value="">Login Sebagai..</option>
                                <?php if (set_value('status') != '') { ?>
                                    <option value="<?= set_value('status'); ?>"
                                            selected><?= ucfirst(set_value('status')) ?></option>
                                        <?php } ?>
                                <option value="mahasiswa">Mahasiswa</option>
                                <!--<option value="dosen">Dosen</option>-->
                            </select>
                            <small class="text-red"><?php echo form_error('status'); ?></small>
                        </div>
                        <input type="submit" name="submit" class="btn btn-primary btn-block btn-flat" value="Log In">
                        <br>
                        <a href="#" class="btn btn-danger btn-block" onclick="$('#modal-cek-validasi').modal('toggle');">Cek Validasi Pembayaran!</a>
                        <?= form_close(); ?>
                        <hr/>
                        <a href="<?= site_url('lupa_password') ?>" class="text-right">Lupa Password ?</a><br>
                      <!--<a href="<?= base_url('assets/prosedur_krs_genap_2021.pdf') ?>" target="_blank" class="text-right">Alur Pengisian KRS Mahsiswa</a> -->
                        <br>
                        <a href="https://drive.google.com/file/d/13jGfW4dsBPG7BMrVQzDfc8KePpU0oAN6/view?usp=sharing" target="_blank" class="text-right">Alur Pengisian KRS MABA 2022</a><br>
                        <a href="https://docs.google.com/spreadsheets/d/13gV3oTu-6qkkww473xZ3ZCWnjkuQweXY/edit?usp=sharing&ouid=108500172438656022126&rtpof=true&sd=true" target="_blank" class="text-right">NIM MABA 2022</a><br>

                    </div>
                </div><!-- /.login-box-body -->
            </div><!-- /.login-box -->
        </div>
        <!--modal cek validasi pembayaran-->
        <div class="modal fade" id="modal-cek-validasi" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Cek Validasi Pembayaran</h4>
                    </div>
                    <div class="modal-body">
                        <form id="form-cek-pembayaran" action="<?= site_url('CekPembayaran/search') ?>" method="post">
                            <div class="form-group">
                                <div class="input-group input-group-lg">
                                    <input type="number" pattern="[0-9]+" name="nim" required class="form-control" placeholder="Masukan NIM" minlength="10" maxlength="10">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-danger btn-flat">Cari</button>
                                    </span>
                                </div>
                            </div>
                        </form>
                        <div id="landing-result">

                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <script src="<?= base_url('assets/plugins/jQuery/jQuery-2.2.0.min.js') ?>"></script>
        <script src="<?= base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>
        <script>
                            $(".alert").fadeTo(9000, 500).slideUp(1000, function () {
                                $(".alert").alert('close');
                            });
        </script>
        <script>
            $(document).ready(function () {
                $('#form-cek-pembayaran').submit(function (e) {
                    e.preventDefault();
                    var url = $(this).prop('action');
                    var data = $(this).serialize();
                    $.ajax({
                        url: url,
                        data: data,
                        type: 'post',
                        success: function (res) {
                            console.log(res);
                            $('#landing-result').html(res);
                        }
                    })
                })
            })
        </script>
    <?php $this->load->view('csrf_js'); ?>
    </body>
</html>