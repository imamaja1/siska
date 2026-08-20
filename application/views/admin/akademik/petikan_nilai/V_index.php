<div class="row">
    <div class="col-md-3" style="position: sticky;
  position: -webkit-sticky;
  top: 0; /* required */">
        <div class="box box-primary flat">
            <div class="box-body">
                <h4><i class="fa fa-search" style="color: #017ebc"></i><strong> Filter</strong></h4>
            </div>
            <div class="box-body">
                <form id="form-filter" method="POST" action="<?= site_url('admin/akademik/petikan_nilai/filter') ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group">
                        <label class="control-label ">Angkatan : </label>
                        <select required name="angkatan" class="form-control">
                            <option selected disabled value="">Pilih</option>
                            <?php foreach ($tahun_angkatan as $row) { ?>
                                <option value="<?= substr($row->tahun_akademik,2, 2)?>"><?= substr($row->tahun_akademik,0, 4)?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Jurusan : </label>
                            <select required class="form-control" name="prodi">
                                <option selected disabled value="" >Pilih</option>
                                <?php foreach ($nama_jurusan as $row) { ?>
                                    <option value="<?= e($row->kode_program_studi)?>"><?= e($row->nama_program_studi)?></option>
                                <?php } ?>
                            </select>
                    </div>
                    <div class="form-group">
                        <div class="pull-right">
                            <button type="submit" name="submit" class="btn btn-primary btn-sm flat"><i class="fa fa-gear"></i> Filter</button>
                        </div>
                    </div>
                </form>
                <br>
                <hr>
                <form id="form-cari" method="POST" action="<?= site_url('admin/akademik/petikan_nilai/cari') ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group" style="margin: 0px">
                        <label for="inputEmail3" class=" control-label">NIM/Nama</label>

                            <div class="input-group input-group-sm">
                                <input type="text" name="keyword" class="form-control" placeholder="Keyword">
                                <span class="input-group-btn">
                            <button class="btn btn-danger"><i class="fa fa-search"></i></button>
                        </span>
                            </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-9" id="landing">
        <div style="width: 100%; height: 500px; background-color: lightblue; padding: 15px; border-radius:15px ">
            <p style="text-align: center"><i>"Result for your search"</i></p>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-view" style="display: none;">
    <div class="modal-dialog" style="max-width: 80%; width: 100%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Petikan Nilai</h4>
            </div>
            <div class="modal-body" id="landing-modal">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

 <script>
     $(document).ready(function () {
          $("#form-filter").submit(function (e) {
              e.preventDefault();
              var url = $(this).attr('action');
              var data = $(this).serialize();
              $.ajax({
                  url : url,
                  data : data,
                  type : 'post',
                  success : function (res) {
                      $("#landing").html(res);
                  },
                  error : function (xhr, status, error) {
                      console.log('AJAX Error:', error);
                      console.log('Response:', xhr.responseText);
                      $("#landing").html('<div class="callout callout-danger"><h4><i class="fa fa-warning"></i> Error!</h4><p>Gagal memuat data. Silakan coba lagi.</p></div>');
                  }
              })
          })

          $("#form-cari").submit(function (e) {
              e.preventDefault();
              var url = $(this).attr('action');
              var data = $(this).serialize();
              $.ajax({
                  url : url,
                  data : data,
                  type : 'post',
                  success : function (res) {
                      $("#landing").html(res);
                  },
                  error : function (xhr, status, error) {
                      console.log('AJAX Error:', error);
                      console.log('Response:', xhr.responseText);
                      $("#landing").html('<div class="callout callout-danger"><h4><i class="fa fa-warning"></i> Error!</h4><p>Gagal memuat data. Silakan coba lagi.</p></div>');
                  }
              })
          })
     })

    $(document).on('click', '#halaman a', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url : url,
            success : function (res) {
                $('#landing').html(res);
            },
            error : function () {
                console.log('gagal load');
            }
        })
    })

    function view(id) {
        var url = "<?= site_url('admin/akademik/Petikan_nilai/detail') ?>/"+id;
        $.ajax({
            url : url,
            success : function (res) {
                $('#landing-modal').html(res);
                $("#modal-view").modal('show');
            },
            error : function () {
                console.log('gagal load');
            }
        })
    }
    function view_ganjil(id) {
        var url = "<?= site_url('admin/akademik/Petikan_nilai/detail_ganjil') ?>/"+id;
        $.ajax({
            url : url,
            success : function (res) {
                $('#landing-modal').html(res);
                $("#modal-view").modal('show');
            },
            error : function () {
                console.log('gagal load');
            }
        })
    }
    function view_genap(id) {
        var url = "<?= site_url('admin/akademik/Petikan_nilai/detail_genap') ?>/"+id;
        $.ajax({
            url : url,
            success : function (res) {
                $('#landing-modal').html(res);
                $("#modal-view").modal('show');
            },
            error : function () {
                console.log('gagal load');
            }
        })
    }
 </script>