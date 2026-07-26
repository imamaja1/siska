<?php
$warna = array('bg-yellow', 'bg-green', 'bg-red', 'bg-aqua');
$k = array_rand($warna);
$v = $warna[$k];
?>
<div class="box box-solid flat">
    <div class="box-body">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Pilih Tahun Akademik:</label>
            <div class="col-sm-4">
                <form action="">
                    <select id="nilai-akademik" name="kode_nilai_akademik" class="form-control select2">
                        <option value="" selected disabled>Tahun Akademik</option>
                        <?php foreach ($tahun_akademik as $row) : ?>
                            <option <?= ($get_kode_ta == $row->kode_tahun_akademik ? "selected" : "") ?> value="<?= e($row->kode_tahun_akademik) ?>"><?= e($row->tahun_akademik) ?> - <?= e($row->semester == 0 ? "GENAP" : "GANJIL") ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="table-responsive" id="landing-nilai">

                </div>
            </div>
        </div>
    </div>
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


<div class="modal fade" id="modal-pesan" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content" id="landing-pesan">

        </div>
    </div>
</div>

<script>
    function pesan_uas_dekan(kelas_id) {
        super_kelas_id = kelas_id;
        var url = "<?= site_url('dosen/penilaian/pesan_uas') ?>/" + kelas_id +"/dosen/dekan";
        $.ajax({
            url: url,
            success: function (res) {
                $("#landing-pesan").html(res);
                $("#modal-pesan").modal('show');
                $("#pesan_dekan_" + kelas_id).addClass('btn-primary').removeClass('btn-danger').removeClass('badge-notif');
                console.log('ada');
            }
        })
    }
    function pesan_uas_prodi(kelas_id) {
        super_kelas_id = kelas_id;
        var url = "<?= site_url('dosen/penilaian/pesan_uas') ?>/" + kelas_id +"/dosen/prodi";
        $.ajax({
            url: url,
            success: function (res) {
                $("#landing-pesan").html(res);
                $("#modal-pesan").modal('show');
                $("#pesan_prodi_" + kelas_id).addClass('btn-primary').removeClass('btn-danger').removeClass('badge-notif');
            }
        })
    }
    
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
            var url = "<?= site_url('dosen/penilaian/choose_nilai_uas') ?>";
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
        var url = "<?= site_url('dosen/penilaian/choose_nilai_uas') ?>";
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