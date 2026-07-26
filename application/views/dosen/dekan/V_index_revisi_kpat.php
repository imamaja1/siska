<div class="box box-solid flat">
    <div class="box-body">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Pilih Tahun Akademik:</label>
            <div class="col-sm-4">
                <form action="">
                    <select id="nilai-akademik" name="kode_nilai_akademik" class="form-control select2">
                        <option value="" selected disabled>Tahun Akademik</option>
                        <?php foreach ($tahun_akademik as $row) : ?>
                            <option <?= ($select == $row->kode_tahun_akademik ? "selected" : "") ?> value="<?= $row->kode_tahun_akademik ?>"><?= $row->tahun_akademik ?> - <?= $row->semester == 0 ? "GENAP" : "GANJIL" ?></option>
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

<div class="modal fade bd-example-modal-lg" id="ModalNilai" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Nilai Mahasiswa</h3>
      </div>
      <div class="modal-body">
        <div id='nilai_mahasiswa'></div>
        <!-- <p>Modal body text goes here.</p> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ModalNilaiValidasi" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Nilai Mahasiswa Tervalidasi Oleh Dekan</h3>
      </div>
      <div class="modal-body">
        <div id='nilai_mahasiswa_tervalidasi'></div>
        <!-- <p>Modal body text goes here.</p> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
function show_nilai(param1,param2) {
    $.ajax({
        url: '<?= site_url('dosen/dekan/validasinilai_kpat/revisi_nilai_validasi')?>',
        type: 'post',
        data: {
            "kelas": param1,
            "level": param2,
            "kode_ta" : $("#nilai-akademik").val(),
        },
        success: function (res) {
            $("#nilai_mahasiswa").html(res);
        },
        error: function () {
            console.log('gagal');
        }
    })
}
function show_mhs(param1,param2) {
    $.ajax({
        url: '<?= site_url('dosen/dekan/validasinilai_kpat/revisi_nilai_validasi_mhs')?>',
        type: 'post',
        data: {
            "kelas": param1,
            "level": param2,
        },
        success: function (res) {
            $("#nilai_mahasiswa").html(res);
        },
        error: function () {
            console.log('gagal');
        }
    })
}
function show_nilai_validasi(param1) {
    $.ajax({
        url: '<?= site_url('dosen/dekan/validasinilai_kpat/revisi_nilai_divalidasi')?>',
        type: 'post',
        data: {
            "kelas": param1,
            "kode_ta" : $("#nilai-akademik").val(),
        },
        success: function (res) {
            $("#nilai_mahasiswa_tervalidasi").html(res);
        },
        error: function () {
            console.log('gagal');
        }
    })
}
function selesai(param1,param2) {
    swal({
            title: '',
            html: "Menekan tombol <strong>Validasi Nilai</strong> berarti data nilai mahasiswa sudah selesai di inputkan dan siap untuk di  <strong>SIMPAN DI KHS</strong>." +
                    "Tekan <strong>YA</strong> untuk melanjutkan dan <strong>Tidak</strong> untuk membatalkan.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            $.ajax({
                url: '<?= site_url('dosen/dekan/validasinilai_kpat/revisi_nilai_selesai')?>',
                type: 'post',
                data: {
                    "kelas": param1,
                    "level": param2,
                },
                success: function (res) {
                    if (res) {
                        refres();
                        $('#ModalNilai').modal('hide');
                        // location.reload();
                    }
                },
                error: function () {
                    console.log('gagal');
                }
            })
        });
}
function revisi(param1,param2) {
    const pesan = $('#pesantext').val();
    swal({
        title: '',
        html: "Menekan tombol <strong>Revisi Nilai</strong> berarti data nilai mahasiswa sudah selesai di periksa dan siap untuk di kembalikan ke <strong>Dosen</strong>." +
                "Tekan <strong>YA</strong> untuk melanjutkan dan <strong>Tidak</strong> untuk membatalkan.",
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Tidak',
        confirmButtonText: 'Ya'
    }).then(function () {
        if( pesan == '') {
            swal({
                title: '',
                html: "Pesan Tidak Boleh <strong>KOSONG</strong> Jika Menekan tombol Revisi.",
                type: 'warning',
                showCancelButton: false,
                confirmButtonText: 'Ya'
            })
        }else{
            $.ajax({
                url: '<?= site_url('dosen/dekan/validasinilai_kpat/revisi_nilai_revisi')?>',
                type: 'post',
                data: {
                    "kelas": param1,
                    "level": param2,
                    "pesan": pesan,
                },
                success: function (res) {
                    if (res) {
                        refres();
                        $('#ModalNilai').modal('hide');
                    }
                },
                error: function () {
                    console.log('gagal');
                }
            })
        }
    });
}

function autoload2() {
    var url = "<?= site_url('dosen/dekan/validasinilai_kpat/choose_nilai_revisi') ?>";
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

$(document).ready(function () {
    autoload2();
    $("#nilai-akademik").change(function (e) {
        var url = "<?= site_url('dosen/dekan/validasinilai_kpat/choose_nilai_revisi') ?>";
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

function refres() {
    const url = "<?= site_url('dosen/dekan/validasinilai_kpat/choose_nilai_revisi') ?>";
    $.ajax({
        url: url,
        data: {
            "kode_nilai_akademik" : $("#nilai-akademik").val(),
        },
        type: "post",
        beforeSend: function () {
            var html = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
            $("#landing-nilai").html(html);
        },
        success: function (res) {
            $("#landing-nilai").html(res);
        }
    })
}
</script>