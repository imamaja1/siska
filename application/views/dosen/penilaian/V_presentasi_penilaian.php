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
                            <option <?= ($get_kode_ta == $row->kode_tahun_akademik ? "selected" : "") ?> value="<?= $row->kode_tahun_akademik ?>"><?= $row->tahun_akademik ?> - <?= $row->semester == 0 ? "GENAP" : "GANJIL" ?></option>
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
                <div class="callout callout-info">
                    <ul>
                        <li>Data Presentasi tidak dapat diubah jika nilai sudah disimpan.</li>
                        <li>Klik tombol Isi default akan otomatis mengisi dengan presentasi Harian: 20%, UTS: 30% & UAS: 50%</li>
                        <li>Total presentasi nilai tidak boleh lebih dan tidak boleh kurang dari sama dengan 100%</li>
                    </ul>
                </div>
                <div class="table-responsive" id="landing-presensi-nilai">
                    
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

<script>
    $(document).ready(function () {
        autoload2();
        $("#nilai-akademik").change(function (e) {
            var url = "<?= site_url('dosen/penilaian/choose_presentasi_nilai') ?>";
            var data = $(this).serialize();
            $.ajax({
                url: url,
                data: data,
                type: "post",
                beforeSend: function () {
                    var html = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
                    $("#landing-presensi-nilai").html(html);
                },
                success: function (res) {
                    $("#landing-presensi-nilai").html(res);
                }
            })
        })
    })

    function autoload2() {
        var url = "<?= site_url('dosen/penilaian/choose_presentasi_nilai') ?>";
        $.ajax({
            url: url,
            beforeSend: function () {
                var html = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
                $("#landing-presensi-nilai").html(html);
            },
            success: function (res) {
                $("#landing-presensi-nilai").html(res);
            }
        })
    }
    <?php
        if ($this->session->flashdata('info')) {
            echo $this->session->flashdata('info');
        }
    ?>

</script>