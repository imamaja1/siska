<div class="row">
    <div class="col-md-3" style="position: sticky;
  position: -webkit-sticky;
  top: 0; /* required */">
        <div class="box box-primary flat">
            <div class="box-body">
                <h4><i class="fa fa-search" style="color: #017ebc"></i><strong> Cari Mahasiswa</strong></h4>
            </div>
            <div class="box-body">
                <form id="form-cari-mahasiswa" method="POST" action="<?= site_url('admin/akademik/petikan_nilai/cari_mahasiswa') ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group">
                        <label class="control-label ">NIM/Nama : </label>
                        <input type="text" required name="keyword" class="form-control" placeholder="NIM/Nama" autofocus>
                    </div>
                    <div class="form-group">
                        <label class="control-label ">Tahun Akademik : </label>
                        <select name="tahun_akademik" class="form-control">
                            <?php foreach ($tahun_akademik as $row) { ?>
                                <option value="<?= e($row->kode_tahun_akademik) ?>" <?= ($row->kode_tahun_akademik == $kode_tahun_akademik_aktif) ? 'selected' : '' ?>><?= e($row->tahun_akademik) ?> - <?= ($row->semester % 2 == 0 ? 'Genap' : 'Ganjil') ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="pull-right">
                            <button type="submit" name="submit" class="btn btn-primary btn-sm flat"><i class="fa fa-search"></i> Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-9" id="landing">
        <div style="width: 100%; height: 500px; background-color: lightblue; padding: 15px; border-radius:15px ">
            <p style="text-align: center"><i>"Petikan nilai mahasiswa akan ditampilkan di sini"</i></p>
        </div>
    </div>
</div>

 <script>
     var loadingHtml = '<div class="text-center" style="padding:60px 0;">' +
         '<i class="fa fa-spinner fa-spin fa-3x" style="color:#017ebc"></i>' +
         '<p style="margin-top:12px;color:#666;"><strong>Memuat petikan nilai...</strong></p></div>';

     function showLoading(target) {
         $(target).html(loadingHtml);
     }

     $(document).ready(function () {
          $("#form-cari-mahasiswa").submit(function (e) {
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
 </script>
