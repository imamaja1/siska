<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
                <form action="">
                    <div class="form-group col-md-6 col-sm-12">
                        <label for="">Tahun Akademik <span class="text-danger">*</span></label>
                        <select name="kode_tahun_akademik" class="form-control select2" id="kode-tahun-akademik">
                            <?php foreach ($tahun_akademik as $row) : ?>
                                <option value="<?= $row->kode_tahun_akademik ?>"><?= $row->tahun_akademik ?> <?= $row->semester == '1' ? 'GANJIL' : 'GENAP' ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="landing">

</div>
<!--ja-->
<script>
    var super_kode_tahun_akademik;
    var loading = "<p style='text-align: center'><img src='<?= base_url('assets/siska/img/logo-ubg.gif') ?>' alt=''></p>"
    $(document).ready(function () {
        super_kode_tahun_akademik = "<?= tahun_akademik()->kode_tahun_akademik ?>"
        get_data();
        
        $("#kode-tahun-akademik").change(function () {
            super_kode_tahun_akademik = $(this).val();
            get_data();
        })
    })

    function get_data() {
        var url = "<?= site_url('admin/keuangan/mahasiswa_aktif/get_data') ?>/"+super_kode_tahun_akademik;
        $.ajax({
            url : url,
            beforeSend : function(){
                $("#landing").html(loading);
            },
            success : function (res) {
                $("#landing").html(res);
            }
        })
    }
</script>