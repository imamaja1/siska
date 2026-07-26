<?php
    $warna = array('bg-yellow','bg-green','bg-red','bg-aqua');
    $k = array_rand($warna);
    $v = $warna[$k];
?>
<div class="row">
    <?php if (count($data) > 0) : ?>
        <?php foreach ($data as $row) : ?>
            <div class="col-md-4 col-xs-12">
                <div class="info-box bg-aqua">
                    <span class="info-box-icon"><i class="fa fa-book"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text"><?= e($row->kode_matakuliah) ?> - <?= e($row->nama_matakuliah) ?></span>
                        <span class="info-box-number">KELAS - <?= e($row->nama_kelas) ?></span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                            <a href="<?= site_url('dosen/penilaian/nilai_mahasiswa/'.$row->kelas_id) ?>" style="color: #ffffff"><i class="fa fa-arrow-circle-right"></i> Beri Penilaian </a>
                          </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-md-12 col-xs-12">
            <div class="callout callout-warning">
                <h4><i class="fa fa-warning"></i> Peringatan</h4>

                <p>Tidak ada kelas yang di ampu</p>
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
                    <!-- <option value="" selected disabled>Tahun Akademik</option> -->
                    <?php foreach ($tahun_akademik as $row) : ?>
                        <option value="<?= e($row->kode_tahun_akademik) ?>"><?= e($row->tahun_akademik) ?> - <?= e($row->semester == 0 ? "GENAP" : "GANJIL")?></option>
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
                <h4 class="modal-title"><i class="fa fa-comments-o"></i>Commentar</h4>
            </div>
            <div class="modal-body" id="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    $(document).ready(function () {
        autoload();
        $("#tahun-akademik").change(function (e) {
            var url = "<?= site_url('dosen/penilaian/kuisioner') ?>";
            var data = $(this).serialize();
            $.ajax({
                url : url,
                data : data,
                type : "post",
                beforeSend : function () {
                    var html = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
                    $("#landing-kuisioner").html(html);
                },
                success : function (res) {
                    $("#landing-kuisioner").html(res);
                }
            })
        })
    })

    function autoload() {
        var url = "<?= site_url('dosen/penilaian/kuisioner') ?>";
        $.ajax({
            url : url,
            beforeSend : function () {
                var html = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
                $("#landing-kuisioner").html(html);
            },
            success : function (res) {
                $("#landing-kuisioner").html(res);
            }
        })
    }

    function show_commen(kelas_id) {
        var url = "<?= site_url('dosen/penilaian/show_comment') ?>/"+kelas_id;
        $("#modal-saran").modal("show");
        $.ajax({
            url : url,
            beforeSend : function () {
                var html = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
                $("#modal-body").html(html);
            },
            success : function (res) {
                $("#modal-body").html(res);
            }
        })
    }
</script>