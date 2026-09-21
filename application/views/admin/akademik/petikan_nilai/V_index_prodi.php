<div class="row">
    <div class="col-md-3" style="position: sticky;
  position: -webkit-sticky;
  top: 0; /* required */">
        <div class="box box-primary flat">
            <div class="box-body">
                <h4><i class="fa fa-search" style="color: #017ebc"></i><strong> Filter Prodi</strong></h4>
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
                        <label class="control-label">Tahun Akademik : </label>
                            <select name="tahun_akademik" class="form-control">
                                <?php foreach ($tahun_akademik as $row) { ?>
                                    <option value="<?= e($row->kode_tahun_akademik) ?>" <?= ($row->kode_tahun_akademik == $kode_tahun_akademik_aktif) ? 'selected' : '' ?>><?= e($row->tahun_akademik) ?> - <?= ($row->semester % 2 == 0 ? 'Genap' : 'Ganjil') ?></option>
                                <?php } ?>
                            </select>
                    </div>
                    <div class="form-group">
                        <div class="pull-right">
                            <button type="submit" name="submit" class="btn btn-primary btn-sm flat"><i class="fa fa-gear"></i> Filter</button>
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
     var loadingHtml = '<div class="text-center" style="padding:60px 0;">' +
         '<i class="fa fa-spinner fa-spin fa-3x" style="color:#017ebc"></i>' +
         '<p style="margin-top:12px;color:#666;"><strong>Memuat data...</strong></p></div>';

     function showLoading(target) {
         $(target).html(loadingHtml);
     }

     $(document).ready(function () {
          $("#form-filter").submit(function (e) {
              e.preventDefault();
              var url = $(this).attr('action');
              var data = $(this).serialize();
              showLoading('#landing');
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
        showLoading('#landing');
        $.ajax({
            url : url,
            success : function (res) {
                $('#landing').html(res);
            },
            error : function () {
                $('#landing').html('<div class="callout callout-danger"><h4><i class="fa fa-warning"></i> Error!</h4><p>Gagal memuat data. Silakan coba lagi.</p></div>');
            }
        })
    })

    function view(id) {
        var url = "<?= site_url('admin/akademik/Petikan_nilai/detail') ?>/"+id;
        showLoading('#landing-modal');
        $("#modal-view").modal('show');
        $.ajax({
            url : url,
            success : function (res) {
                $('#landing-modal').html(res);
            },
            error : function () {
                $('#landing-modal').html('<div class="callout callout-danger"><h4><i class="fa fa-warning"></i> Error!</h4><p>Gagal memuat data. Silakan coba lagi.</p></div>');
            }
        })
    }
 </script>
