<div class="row">
    <!--    <div class="form-group">-->
    <!--        <div class="input-group">-->
    <!--            <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Search...">-->
    <!--            <span class="input-group-btn"><button onclick="cari()" class="btn btn-danger" title="Cari"><i class="fa fa-search"></i></button></span>-->
    <!--        </div>-->
    <!--    </div>-->
    <div class="col-md-12 col-xs-12" id="landing-kelas">

    </div>
    <!--    <div class="col-md-8 col-xs-12" id="landing-nilai">-->
    <!--        <div style="height: 200px; background-color: #00a7d0; border-radius: 20px; padding: 20px">-->
    <!--            <p style="text-align: center; color: white"><i>"Landing page"</i></p>-->
    <!--        </div>-->
    <!--    </div>-->
</div>

<div class="modal fade" id="modal-lihat" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content" id="landing-lihat">

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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
    var super_kelas_id = '';
    var loader = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";

    function lihat(kelas_id) {
        super_kelas_id = kelas_id;
        var url = "<?= site_url('dosen/kaprodi/validasinilai/data_mahasiswa') ?>/" + kelas_id;
        $.ajax({
            url: url,
            // beforeSend: function () {
            //     $("#landing-nilai").html(loader);
            // },
            success: function (res) {
                // $("#landing-nilai").html(res);
                $("#landing-lihat").html(res);
                $("#modal-lihat").modal('show');
            }
        })
    }

    function validasi() {

        var url = "<?php //= site_url('dosen/kaprodi/validasinilai/validasi')    ?>///" + super_kelas_id;
        var url = "<?= site_url('dosen/kaprodi/validasinilai/validasi_prodi') ?>/" + super_kelas_id;
        swal({
            title: '',
            html: "<strong>Validasi Nilai</strong> berarti anda sudah selesai mengoreksi nilai mahasiswa yang di inputkan dosen." +
                    "Nilai yang sudah di <storng>Validasi</storng> tidak bisa dirubah kembali. Tekan <strong>YA</strong> untuk melanjutkan dan " +
                    "<strong>Tidak</strong> untuk membatalkan.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            $.ajax({
                url: url,
                // beforeSend: function () {
                //     $("#landing-nilai").html(loader);
                // },
                success: function (res) {
                    // console.log(res);
                    // lihat(super_kelas_id)
                    $("#modal-lihat").modal('hide');
                    sukses();
                    kelas();
                }
            })
        });
    }

    function sukses() {
        swal({
            title: "Sukses",
            text: "Nilai telah di validasi.!",
            icon: "success",
        });
    }
    ;

    // tidak dipakai karna ganti pakai form di modal
    function revisi() {
        var url = "<?= site_url('dosen/kaprodi/validasinilai/revisi_nilai') ?>/" + super_kelas_id;
        swal({
            title: '',
            html: "Menekan tombol <strong>Revisi</strong> berarti meberikan akses kepada dosen untuk melakukan <strong>Revisi/Perbahan</strong>" +
                    " pada nilai mahasiswa. Tekan <strong>YA</strong> untuk melanjutkan dan <strong>Tidak</strong> untuk membatalkan.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            $.ajax({
                url: url,
                beforeSend: function () {
                    $("#landing-nilai").html(loader);
                },
                success: function (res) {
                    lihat(super_kelas_id)
                }
            })
        });

    }

    function kelas() {
        var url = "<?= site_url('dosen/kaprodi/validasinilai/kelas') ?>";
        $.ajax({
            url: url,
            beforeSend: function () {
                $("#landing-kelas").html(loader);
            },
            success: function (res) {
                $("#landing-kelas").html(res);
            }
        })
    }

    function cari() {
        var url = "<?= site_url('dosen/kaprodi/validasinilai/cari_kelas') ?>";
        var data = "keyword=" + $("#keyword").val();
        $.ajax({
            url: url,
            data: data,
            type: 'post',
            beforeSend: function () {
                $("#landing-kelas").html(loader);
            },
            success: function (res) {
                $("#landing-kelas").html(res);
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

    $(document).ready(function () {
        kelas();
    })
</script>