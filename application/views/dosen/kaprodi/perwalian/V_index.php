<div class="row">
    <div class="col-md-3" style="position: sticky;
  position: -webkit-sticky;
  top: 0; /* required */">
        <div class="box box-success flat">
            <div class="box-body">
                <h4><i class="fa fa-search" style="color: #017ebc"></i><strong> Filter</strong></h4>
            </div>
            <div class="box-body">
                <form id="form-filter" method="POST" action="<?= site_url('dosen/kaprodi/konsultasi_perwalian/filter') ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group">
						<label class="control-label ">Cari per Angkatan : </label>
                        <select required name="angkatan" class="form-control">
                            <option selected disabled value="">Pilih</option>
                            <?php foreach ($tahun_angkatan as $row) { ?>
                                <option value="<?= e(substr($row->tahun_akademik,2, 2))?>"><?= e(substr($row->tahun_akademik,0, 4))?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="pull-right">
                            <button type="submit" name="submit" class="btn btn-primary btn-sm flat"><i class="fa fa-search"></i> Filter</button>
                        </div>
                    </div>
                </form>
                <br>
                <hr>
                <form id="form-cari" action="<?= site_url('dosen/kaprodi/konsultasi_perwalian/cari') ?>">
                    <div class="form-group" style="margin: 0px">
                        <label for="inputEmail3" class=" control-label">Cari NIM/Nama Mahasiswa</label>

                            <div class="input-group input-group-sm">
                                <input type="text" name="keyword" class="form-control" placeholder="NIM / Nama Mahasiswa">
                                <span class="input-group-btn">
                            <button class="btn btn-danger"><i class="fa fa-search"></i></button>
                        </span>
                            </div>

                    </div>
                </form>
              
              
                <br>
                <hr>
                <form id="form-cari-dosen" action="#" method="post">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <label for="inputEmail3" class=" control-label">Cari Nama Dosen</label>
                    <div class="form-group">
                        <input type="hidden" name="kode_dosen_cari" id="kode-dosen-cari">
                        <input type="text" required autocomplete="off" placeholder="Masukan Nama Dosen" name="kode_dosen" id="search-box-dosen" class="form-control">
                        <div id="suggesstion-box-dosen"></div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-sm btn-flat pull-right"><i class="fa fa-search"></i> Cari</button>
                    </div>
                </form>
                <br>
                <hr>
                <a href="<?=site_url('dosen/kaprodi/Konsultasi_perwalian/rekap_dosen_wali')?>" class="pull-right btn-sm btn btn-flat btn-success"><i class="fa fa-file-excel-o"></i> Export Perwalian</a>

              
            </div>
        </div>
    </div>
    <div class="col-md-9" id="landing">
        <div style="width: 100%; height: 500px; background-color: lightblue; padding: 15px; border-radius:15px ">
            <p style="text-align: center"><i>"Result for your search"</i></p>
        </div>
    </div>
</div>

 <script>
     $(document).ready(function () {
         $("#form-filter").submit(function (e) {
             e.preventDefault();
             if (!$(this).validate())
             {
                 swal("Required",'Form tidak boleh kosong','error');
             }else{
                 var url = $(this).attr('action');
                 var data = $(this).serialize();
                 $.ajax({
                     url : url,
                     data : data,
                     type : 'post',
                     beforeSend : function () {
                         var html = "<img style='width: 100%; height: 100%' src='https://assets.website-files.com/5c7fdbdd4e3feeee8dd96dd2/5ce46f8ffd710a2c22c15e48_cust_ami.gif'/>";
                         $("#landing").html(html);
                     },
                     success : function (res) {
                         $("#landing").html(res);
                     },
                 })
             }
         })
         
         // cari mahasiswa

         $("#form-cari").submit(function (e) {
             e.preventDefault();
             if (!$(this).validate())
             {
                 swal("Required",'Form tidak boleh kosong','error');
             }else{
                 var url = $(this).attr('action');
                 var data = $(this).serialize();
                 $.ajax({
                     url : url,
                     data : data,
                     type : 'post',
                     beforeSend : function () {
                         var html = "<img style='width: 100%; height: 100%' src='https://assets.website-files.com/5c7fdbdd4e3feeee8dd96dd2/5ce46f8ffd710a2c22c15e48_cust_ami.gif'/>";
                         $("#landing").html(html);
                     },
                     success : function (res) {
                         $("#landing").html(res);
                     },
                 })
             }
         })
         
         // cari dosen
         $("#form-cari-dosen").submit(function (e) {
             e.preventDefault();
             perdosen();
         });

         $("#search-box-dosen").keyup(function () {
             $.ajax({
                 type: "POST",
                 url: "<?= site_url('dosen/kaprodi/Konsultasi_perwalian/autocomplatedosen') ?>",
                 data: 'keyword=' + $(this).val(),
                 beforeSend: function () {
                     $("#search-box-dosen").css("background", "#FFF");
                 },
                 success: function (data) {
                     $("#suggesstion-box-dosen").show();
                     $("#suggesstion-box-dosen").html(data);
                     $("#search-box-dosen").css("background", "#FFF");
                 }
             });
         });
     })
     
     var super_kode_dosen;
     function perdosen(){
         var url = "<?= site_url('dosen/kaprodi/Konsultasi_perwalian/filter_perdosen') ?>/"+super_kode_dosen;
         $.ajax({
             url : url,
             beforeSend : function () {
                 $("#landing").html("<div class='text-center'><img src='https://assets.website-files.com/5c7fdbdd4e3feeee8dd96dd2/5ce46f8ffd710a2c22c15e48_cust_ami.gif'/></div>");
             },
             success : function (res) {
                 $("#landing").html(res);
             }
         })
     }
     function selectDosen(val, nama) {
         super_kode_dosen = val;
         $("#search-box-dosen").val(nama);
         $("#kode-dosen-cari").val(val);
         $("#suggesstion-box-dosen").hide();
     }

     $('form select').on('change invalid', function () {
         var textfield = $(this).get(0);

         // hapus dulu pesan yang sudah ada
         textfield.setCustomValidity('');

         if (!textfield.validity.valid) {
             textfield.setCustomValidity('Tidak boleh kosong!');
         }
     });

 </script>