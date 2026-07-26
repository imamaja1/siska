<?= $this->session->flashdata('info')  ?>
<div class="box box-solid flat">
    <div class="box-body">
        <!-- <a href="#" class="btn btn-primary btn-sm flat" onclick="tambah1()"><i class="fa fa-plus"></i> Tambah </a> -->
        <div class="col-md-4">
            <select class ="form-control" onchange="kelas_kpat()" id="tahun_akademik">
                <?php foreach ($tahun_akademik as $value) { 
                    if ($value->semester == 0) {
                    ?>
                    <option value="<?= $value->kode_tahun_akademik ?>">
                        <?= $value->tahun_akademik ?> - GENAP
                    </option>
                <?php }} ?>
            </select>
        </div>
        <div class="col-md-4" >
            <select class ="form-control" onchange="kelas_kpat()" id="program_studi">
                <option value="">
                    -- Pilih Program Studi --
                </option>
                <?php foreach ($jurusan as $value) { 
                    ?>
                    <option value="<?= $value->kode_program_studi ?>">
                        <?= $value->nama_program_studi ?>
                    </option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>

<div class="row" >
    <div class="col-md-7" id="kelas"></div>
    <div class="col-md-5" id="landing-kelas"></div>
</div>
<script type="text/javascript">
    var kode_program_studi;
    var kode_tahun_akademik_mega;
    var loader = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
    function kelas_kpat() {
        var ta = $('#tahun_akademik').val();
        var prodi = $('#program_studi').val();
        kode_program_studi = $('#program_studi').val();
        kode_tahun_akademik_mega  = $('#tahun_akademik').val();
        loader = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
        $.ajax({
            url: '<?= site_url('admin/akademik/kpat/kelas/kelas') ?>/' + ta + '/' + prodi,
            type: 'get',
            beforeSend: function () {
                $('#kelas').html(loader);
            },
            success: function (data) {
                $('#kelas').html(data);
            },
            error: function () {
                console.log('gagal');
            }
        })
    }    
</script>
