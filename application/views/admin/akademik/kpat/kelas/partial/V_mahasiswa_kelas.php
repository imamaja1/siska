<style>
    .idup{
        background-color: #00a7d0;
        color: white;
        font-size: 25pt;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        text-align: center;
    }
    .nama-kelas{
        font-size: 25pt;
        justify-content: center;
        /*background-color: white;*/
        /*color: black;*/
        align-items: center;
        font-weight: bold;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        text-align: center;
    }
</style>
<?php foreach ($nama_kelas as $row) : ?>
<button class="nama-kelas <?= $kelas_id == $row->kelas_id ? 'idup' : '' ?>" onclick="pilih(this, '<?= $row->kelas_id ?>')"><?= $row->nama_kelas ?></button>
<?php endforeach; ?>
<div class="modal-content" style="border-radius: 20px">
    <div id="dosen-pengampu"
         style="background-color: white; padding: 20px; padding-bottom:0px;border-top-left-radius: 20px;border-top-right-radius: 20px; ">
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <form id="form-tambah-pengampu" action="" method="post">
                    <div class="form-group">
                        <select name="kode_dosen[]" required multiple data-placeholder="Dosen Pengampu"
                                class="form-control select2" style="width: 100%">
                          <?php foreach ($dosen as $row) : ?>
                              <option value="<?= $row->kode_dosen ?>"><?= $row->nama_dosen ?></option>
                          <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-info"><i class="fa fa-check-square-o"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-6 col-xs-12" id="landing-pengampu">
            </div>
        </div>
        <hr style="margin-bottom: 0px">
    </div>
    <div class="modal-body" style="padding: 20px; border-radius: 10px">
      <?php if (count($data) > 0) : ?>
          <div class="box no-border">
              <div class="box-header">
                <form id="form-tambah-mahasiswa" action="<?= site_url('admin/akademik/kpat/kelas/tambah_mahasiswa') ?>"
                    method="post">
                    <div class="col-sm-4" style="padding-left: 0%">
                        <div>
                            <input autocomplete="off" required type="text" name="nim" id="search-box"
                                    placeholder="Masukan NIM atau Nama"
                                    class="form-control">
                            <input type="hidden" name="kode_krs_detail" id="kode-krs-detail">
                        </div>
                        <div id="suggesstion-box"></div>
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" name="submit" class="btn btn-info flat"><i class="fa fa-plus"></i>
                            Tambah
                        </button>
                    </div>
                </form>
                  <div class="pull-right">
                       <a href="#" class="btn btn-default flat" onclick="$('#dosen-pengampu').fadeToggle();"
                         title="Dosen Pengampu"><i class="fa fa-graduation-cap"></i></a>
                      <a href="<?= site_url('admin/kuisioner/kelas/cetak_kelas/' . $kelas_id) ?>" title="Cetak"
                         class="btn btn-danger flat"><i
                                  class="fa fa-print"></i></a>
                  </div>
              </div>
              <div class="box-body">
                  <div class="table-responsive">
                      <form id="form-pindah-kelas" method="post"
                            action="<?= site_url('admin/akademik/kpat/kelas/pindah_kelas') ?>">
                          <table class="table table-striped table-bordered">
                              <thead>
                              <tr>
                                  <th id="th" width="3%">NO.</th>
                                  <th id="th">NIM</th>
                                  <th id="th" width="5%">HAPUS</th>
                                  <th id="th">CEK</th>
                                  <th id="th">NAMA MAHASISWA</th>
                              </tr>
                              </thead>
                              <tbody>
                              <?php $i = 1;
                              foreach ($data as $row) : ?>
                                  <tr>
                                      <td align="center"><?= $i++ ?>.</td>
                                      <td align="center"><?= $row->nim ?></td>
                                      <td align="center">
                                          <a href="#"
                                             onclick="hapus('<?= site_url('admin/akademik/kpat/kelas/hapus/' . $row->kelas_mahasiswa_id) ?>')"><i
                                                      class="fa fa-trash" style="color: red;"></i></a>
                                      </td>
                                      <td align="center">
                                          <input type="checkbox" name="kelas_mahasiswa_id[]"
                                                 value="<?= $row->kelas_mahasiswa_id ?>">
                                      </td>
                                      <td><?= $row->nama_mahasiswa ?></td>
                                  </tr>
                              <?php endforeach; ?>
                              </tbody>
                          </table>
                          <hr>
                          <div class="form-group">
                              <div class="col-sm-4 col-xs-12" style="padding: 0px">
                                  <label> Pindah ke : </label>
                                  <div class="input-group">
                                      <select required name="kelas_id" class="form-control">
                                          <option value="" selected disabled>Pilih</option>
                                        <?php foreach ($nama_kelas as $row) : ?>
                                            <option value="<?= $row->kelas_id ?>">Kelas
                                                - <?= $row->nama_kelas ?></option>
                                        <?php endforeach; ?>
                                      </select>
                                      <span class="input-group-btn">
                      <button type="submit" name="submit" class="btn btn-danger btn-flat"><i
                                  class="fa fa-arrow-circle-right"></i></button>
                    </span>
                                  </div>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      <?php else: ?>
          <div class="box no-border">
              <div class="box-header">
                  <form id="form-tambah-mahasiswa" action="<?= site_url('admin/akademik/kpat/kelas/tambah_mahasiswa') ?>"
                        method="post">
                      <div class="col-sm-4" style="padding-left: 0%">
                          <div>
                              <input autocomplete="off" required type="text" name="nim" id="search-box"
                                     placeholder="Masukan NIM atau Nama"
                                     class="form-control">
                              <input type="hidden" name="kode_krs_detail" id="kode-krs-detail">
                          </div>
                          <div id="suggesstion-box"></div>
                      </div>
                      <div class="col-sm-2">
                          <button type="submit" name="submit" class="btn btn-info flat"><i class="fa fa-plus"></i>
                              Tambah
                          </button>
                      </div>
                  </form>
              </div>
              <div class="box-body">
                  <div class="callout callout-info">
                      <h4><i class="fa fa-info"></i> Information!</h4>

                      <p>Data yang anda cari tidak ditemukan.</p>
                  </div>
              </div>
          </div>
      <?php endif; ?>
    </div>
</div>

<script>
  
  $('#matakuliah').val("<?= $matakuliah->id_matakuliah ?>");
    $('#nama-kelas-id').val(super_kelas_id);
    $('#kode_tahun_akademik').val(kode_tahun_akademik_mega);
  
    $('.select2').select2()

    $("#search-box").keyup(function () {
        var keyword = $(this).val();
        var id_matakuliah = "<?= $matakuliah->id_matakuliah ?>";
        $.ajax({
            url: '<?= site_url("admin/akademik/kpat/kelas/autocomplate") ?>',
            type: 'post',
            data: 'keyword=' + keyword + '&id_matakuliah=' + id_matakuliah + '&kode_tahun_akademik=' +kode_tahun_akademik_mega,
            beforeSend: function () {
                $("#search-box").css("background", "#FFF url(../../LoaderIcon.gif) no-repeat 165px");
            },
            success: function (data) {
                console.log('kamu berhasil');
                $("#suggesstion-box").show();
                $("#suggesstion-box").html(data);
                $("#search-box").css("background", "#FFF");
            },
            error: function () {
                console.log('kamu gagal');
            }
        });
    });

    function selectNim(val, kode_krs_detail) {
        $("#search-box").val(val);
        $("#kode-krs-detail").val(kode_krs_detail);
        $("#suggesstion-box").hide();
    }

    function hapus(url) {
        swal({
            title: '',
            text: "Anda yaikin menghapus data ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            // window.location.href = url;
            $.ajax({
                url: url,
                success: function (res) {
                    var obj = JSON.parse(res);
                    if (!obj.status) {
                        swal("Error", 'Data gagal di hapus', 'error');
                    }
                    lihat(super_kelas_id);
                }
            })
        });
    }

    $("#form-tambah-mahasiswa").submit(function (e) {
        e.preventDefault();
        var url = $(this).prop('action');
        var data = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: data,
            success: function (res) {
                var obj = JSON.parse(res);
                if (!obj.status) {
                    swal("Info", obj.message, 'info');
                }
                lihat(super_kelas_id);
            }
        })
    })

    $("#form-pindah-kelas").submit(function (e) {
        e.preventDefault();
        var url = $(this).prop('action');
        var data = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: data,
            success: function () {
                swal("Success", "Data berhasil diubah", "success");
                lihat(super_kelas_id);
            }
        })
    })

    function load_pegampu() {
        var url = "<?= site_url('admin/akademik/kpat/kelas/pengampu') ?>/" + super_kelas_id;
        $.ajax({
            url: url,
            success: function (res) {
                $("#landing-pengampu").html(res);
            }
        })
    }

    function hapus_pengampu(id) {
        var url = "<?= site_url('admin/akademik/kpat/kelas/hapus_pengampu') ?>/" + id;
        swal({
            title: '',
            text: "Anda yaikin menghapus data ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            $.ajax({
                url: url,
                success: function () {
                    load_pegampu();
                }
            })
        });

    }

    function pilih(contex, kelas_id) {
        super_kelas_id = kelas_id;
        $(".nama-kelas").removeClass('idup');
        $(contex).addClass('idup');
        lihat(kelas_id);
        load_pegampu();
    }
</script>
<script>
    $(document).ready(function () {
        var prov = $('[data-toggle="popover"]').popover({
            placement: "bottom",
            trigger: "focus",
            title: "Dosen Pengajar"
        });

        load_pegampu();

        $("#form-tambah-pengampu").submit(function (e) {
            e.preventDefault();
            var url = "<?= site_url('admin/akademik/kpat/kelas/simpan_pengampu') ?>/" + super_kelas_id;
            var data = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: data,
                success: function () {
                    swal('Success!', 'Dosen Pengampu berhasil di simpan', 'success');
                    load_pegampu();
                }
            })
        })
    });
</script>
