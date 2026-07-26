<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/nilai'); ?>" class="btn-sm btn-success flat"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="box box-primary flat">
    <div class="box-body"><br>
        <form class="form-horizontal" method="POST" action="<?= site_url('admin/akademik/nilai/get_update_nilai_process'); ?>">

            <div class="form-group">
                <label class="control-label col-sm-3"> Jurusan <label style="color: red;">*</label> :</label>
                <div class="col-sm-4">
                  <input type="hidden" name="kode-tahun-akademik" id="kode-tahun-akademik"
                           value="<?= tahun_akademik()->kode_tahun_akademik ?>">
                  
                    <select required class="form-control" name="jurusan" id="jurusan">
                        <option value="" selected disabled>Pilih Jurusan</option>
                        <?php foreach ($program_studi as $data) { ?>
                            <option value="<?= $data->kode_program_studi ?>"><?= $data->nama_program_studi ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"> Matakuliah <label style="color: red;">*</label> :</label>
                <div class="col-sm-4">
                    <select required class="form-control select2" name="matakuliah" id="matakuliah">
                        <option value="" selected disabled>Pilih Matakuliah</option>
                    </select>
                </div>
            </div>
          <div class="form-group">
                <label for="kelas" class="control-label col-sm-3">Kelas <small><i>(optional)</i></small> :</label>
                <div class="col-sm-4">
                    <select required name="kelas_id" id="nama-kelas-id" class="form-control">
                        <option value="" selected disabled>Pilih</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-3">
                    <button class="btn btn-primary flat" type="submit"><i class="fa fa-cog"></i> Proses</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#jurusan').change(function () {
            $.post("<?= site_url(); ?>admin/akademik/nilai/get_matakuliah/" + $('#jurusan').val(), {}, function (obj) {
                $('#matakuliah').html(obj);
            });
        });
      
      $('#matakuliah').change(function () {
            var id_matakuliah = $(this).val();
            var kode_tahun_akademik = $('#kode-tahun-akademik').val();
            $.ajax({
                url: '<?= site_url("admin/kuisioner/kuisioner/get_kelas") ?>/' + id_matakuliah + '/' + kode_tahun_akademik,
                success: function (data) {
                    $('#nama-kelas-id').html(data);
                    console.log(data);
                },
                error: function () {
                    console.log('kamu gagal load');
                }
            })
        })
      
    });
</script>  