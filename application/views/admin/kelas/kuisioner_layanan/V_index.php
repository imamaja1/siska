<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h4><i class="fa fa-file-excel-o"></i> Laporan Hasil Kuisioner Pelayanan</h4>
            </div>
            <div class="box-body">
                <form id="form-filter" class="form-horizontal"
                      action="<?= site_url('admin/kuisioner/kuisioner/filter_layanan') ?>" method="post">
                    <div class="form-group">
                        <label for="tahun_akademik" class="control-label col-sm-2">Tahun Akademik <span
                                    class="text-danger">*</span></label>
                        <div class="col-sm-4 col-xs-12">
                            <select required name="kode_tahun_akademik" id="kode-tahun-akademik"
                                    class="form-control select2">
                                <option value="" selected disabled>Pilih</option>
                                <?php foreach ($tahun_akademik as $row) : ?>
                                    <option value="<?= $row->kode_tahun_akademik ?>"><?= $row->tahun_akademik ?>
                                        - <?= $row->semester == 1 ? 'Ganjil' : 'Genap' ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tahun_akademik" class="control-label col-sm-2">Program Studi <span
                                    class="text-danger">*</span></label>
                        <div class="col-sm-4 col-xs-12">
                            <select required name="kode_program_studi" id="kode-program-studi"
                                    class="form-control select2">
                                <option value="" selected disabled>Pilih</option>
                                <?php foreach ($prodi as $row) : ?>
                                    <option value="<?= $row->kode_program_studi ?>"><?= $row->nama_program_studi ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-4">
                            <button type="submit" name="sumbit" class="btn btn-primary flat"><i class="fa fa-gear"></i>
                                Prosses
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row" id="landing">

</div>
<script>
    var super_kode_tahun_akademik;
    // var super_angkatan;
    var super_kode_program_studi;
    var loading = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";

    $("#form-filter").submit(function (e) {
        e.preventDefault();
        super_kode_program_studi = $("#kode-program-studi").val();
        // super_angkatan = $("#angkatan").val();
        super_kode_tahun_akademik = $("#kode-tahun-akademik").val();
        var url = $(this).prop('action');
        var data = $(this).serialize();
        $.ajax({
            url : url,
            data : data,
            type : 'post',
            beforeSend : function(){
                $("#landing").html(loading);
            },
            success : function (res) {
                $("#landing").html(res);
            }
        })
    })

    function cetak() {
        var url = "<?= site_url('admin/kuisioner/kuisioner/cetak_kuisioner_layanan') ?>/"+super_kode_tahun_akademik+"/"+super_kode_program_studi;
        window.location.href = url;
    }

</script>
