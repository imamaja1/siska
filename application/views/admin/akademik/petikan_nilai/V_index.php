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
                                    <option value="<?= $row->kode_program_studi?>"><?= $row->nama_program_studi?></option>
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
                <form id="form-cari" action="<?= site_url('admin/akademik/petikan_nilai/cari') ?>">
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
                     success : function (res) {
                         $("#landing").html(res);
                     },
                 })
             }
         })

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
                     success : function (res) {
                         $("#landing").html(res);
                     },
                 })
             }
         })
     })

 </script>