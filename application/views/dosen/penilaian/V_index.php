<?php
$warna = array('bg-yellow', 'bg-green', 'bg-red', 'bg-aqua');
$k = array_rand($warna);
$v = $warna[$k];
?>
<div class="row">
    <div class="col-md-8 col-xs-12">
        <h3 style="margin-top: 0px">NILAI HARIAN, UTS & UAS</h3>
    </div>
    <div class="col-md-4">
        <form action="">
            <div class="form-group">
                <select id="nilai-akademik" name="kode_nilai_akademik" class="form-control select2">
                    <option value="" selected disabled>Tahun Akademik</option>
                    <?php foreach ($tahun_akademik as $row) : ?>
                        <option value="<?= $row->kode_tahun_akademik ?>"><?= $row->tahun_akademik ?>
                            - <?= $row->semester == 0 ? "GENAP" : "GANJIL" ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <?php if ($setting->aktif_kuisioner == 'A') : ?>
        <?php if (!empty($chat_id['chatid'])): ?>
            
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="table-responsive" id="landing-nilai">

                            </div>
                        </div>
                    </div>
                </div>
          
        <?php else: ?>
            <div class="col-md-12 col-xs-12">
                <div class="callout callout-warning">
                    <h4><i class="fa fa-ban"></i> Peringatan</h4>
                    <p>Penginputan nilai mahasiswa belum bisa dilakukan. Karena anda belum menyambungkan aplikasi siska dengan aplikasi telegram, silahkan ikuti langkah yang terdapat pada menu dashboard.</p>
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="col-md-12 col-xs-12">
            <div class="callout callout-danger">
                <h4><i class="fa fa-ban"></i> Peringatan</h4>

                <p>Penginputan nilai mahasiswa belum bisa dilakukan. Bagian akademik akan mengaktifkan penginputan nilai
                    mahasiswa pada waktu yang telah di tentukan.</p>
            </div>
        </div>
    <?php endif; ?>
</div>
<hr style="border: solid 1px #009abf">
<div class="row">
    <div class="col-md-8 col-xs-12">
        <h3 style="margin-top: 0px">Hasil Kuisioner PBM (Proses Belajar Mengajar)</h3>
    </div>
    <div class="col-md-4">
        <form action="">
            <div class="form-group">
                <select id="tahun-akademik" name="kode_tahun_akademik" class="form-control select2">
                    <option value="" selected disabled>Tahun Akademik</option>
                    <?php foreach ($tahun_akademik as $row) : ?>
                        <option value="<?= $row->kode_tahun_akademik ?>"><?= $row->tahun_akademik ?>
                            - <?= $row->semester == 0 ? "GENAP" : "GANJIL" ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</div>
<div class="row" id="landing-kuisioner">

</div>
<div class="modal fade" id="modal-saran" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><i class="fa fa-comments-o"></i> Kommentar / Catatan</h4>
            </div>
            <div class="modal-body" id="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        autoload1();
        $("#tahun-akademik").change(function (e) {
            var url = "<?= site_url('dosen/penilaian/kuisioner') ?>";
            var data = $(this).serialize();
            $.ajax({
                url: url,
                data: data,
                type: "post",
                beforeSend: function () {
                    var html = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
                    $("#landing-kuisioner").html(html);
                },
                success: function (res) {
                    $("#landing-kuisioner").html(res);
                }
            })
        })
    })

    function autoload1() {
        var url = "<?= site_url('dosen/penilaian/kuisioner') ?>";
        $.ajax({
            url: url,
            beforeSend: function () {
                var html = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
                $("#landing-kuisioner").html(html);
            },
            success: function (res) {
                $("#landing-kuisioner").html(res);
            }
        })
    }

    $(document).ready(function () {
        autoload2();
        $("#nilai-akademik").change(function (e) {
            var url = "<?= site_url('dosen/penilaian/choose_nilai') ?>";
            var data = $(this).serialize();
            $.ajax({
                url: url,
                data: data,
                type: "post",
                beforeSend: function () {
                    var html = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
                    $("#landing-nilai").html(html);
                },
                success: function (res) {
                    $("#landing-nilai").html(res);
                }
            })
        })
    })

    function autoload2() {
        var url = "<?= site_url('dosen/penilaian/choose_nilai') ?>";
        $.ajax({
            url: url,
            beforeSend: function () {
                var html = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
                $("#landing-nilai").html(html);
            },
            success: function (res) {
                $("#landing-nilai").html(res);
            }
        })
    }




    function show_commen(kelas_id, jenis) {
        var url = "<?= site_url('dosen/penilaian/show_comment') ?>/" + kelas_id + "/" + jenis;
        $("#modal-saran").modal("show");
        $.ajax({
            url: url,
            beforeSend: function () {
                var html = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
                $("#modal-body").html(html);
            },
            success: function (res) {
                $("#modal-body").html(res);
            }
        })
    }

    function show_history_comment(kelas_id) {
        var url = "<?= site_url('dosen/penilaian/show_history_comment') ?>/" + kelas_id;
        $("#modal-saran").modal("show");
        $.ajax({
            url: url,
            beforeSend: function () {
                var html = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
                $("#modal-body").html(html);
            },
            success: function (res) {
                $("#modal-body").html(res);
            }
        })
    }

</script>