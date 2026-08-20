<?= $this->session->flashdata('info')  ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a href="#" class="btn btn-primary btn-sm flat" onclick="tambah1()"><i class="fa fa-plus"></i> Tambah / Ubah KRS KPAT</a>
    </div>
</div>
<div class="box box-solid flat" id="filter">
    <div class="box-header">
        <h4><i class="fa fa-search" style="color: #1b6d85"></i><strong> Pencarian data KRS KPAT</strong></h4>
        <hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="<?= site_url('admin/akademik/kpat/krs/filter')  ?>" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-2">Tahun Akademik</label>
                <div class="col-sm-3">
                    <select required class="form-control" name="tahun_akademik">
                        <option value="" selected disabled>Pilih</option>
                        <?php foreach ($tahun_akademik as $row) { ?>
                            <option value="<?= e($row->kode_tahun_akademik) ?>"><?php echo $row->tahun_akademik; if($row->semester == 0){echo "- Genap";}else{echo "- Ganjil";}?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Angkatan</label>
                <div class="col-sm-3">
                    <select required class="form-control" name="angkatan">
                        <option value="" selected disabled>Pilih</option>
                        <option value="" >Semua</option>
                        <?php foreach ($tahun_angkatan as $row) { ?>
                            <option value="<?= e(substr($row->tahun_akademik,2, 2)) ?>"><?= e(substr($row->tahun_akademik,0, 4)) ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Jurusan</label>
                <div class="col-sm-3">
                    <select required class="form-control" name="prodi">
                        <option value="" selected disabled>Pilih</option>
                        <?php foreach ($nama_jurusan as $row) { ?>
                            <option value="<?= e($row->kode_program_studi) ?>"><?= e($row->nama_program_studi) ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2"></div>
                <div class="col-sm-3">
                    <button class="btn btn-primary btn-sm flat" type="sumbit" name="sumbit"><i class="fa fa-gear"></i> Proses</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="box box-solid flat" style="display: none;" id="filter-nim">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/kpat/krs') ?>" class="pull-right text-danger"><i class="fa fa-times-circle-o"></i></a>

        <div class="col-sm-4">
            <div class="input-group input-group">
                <input type="text" id="search-box" name="nim" placeholder="Masukkan NIM" class="form-control">
                <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat" onclick="kirim()"><i class="fa fa-search"></i></button>
                    </span>
            </div>
            <div id="suggesstion-box"></div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function tambah1() {
        // body...
        $('#filter').hide();
        $('#find-box').hide();
        $('#filter-nim').show();
        ;
    }

    function kirim () {
        var nim = $('#search-box').val();

        $.ajax({
            url : "<?= site_url('admin/akademik/kpat/krs/tambah')  ?>/"+nim,
            type : "GET",
            data : "nim="+nim,
            success : function () {
                console.log = "terkirim";
                window.location.href = "<?= site_url('admin/akademik/kpat/krs/tambah')  ?>/"+nim;
            },
            error : function () {
                console.log = "gagal";
            }


        });
    }

</script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#search-box").keyup(function(){
            $.ajax({
                type: "POST",
                url: "<?= site_url('admin/akademik/kpat/krs/autocomplate')  ?>",
                data:'keyword='+$(this).val(),
                beforeSend: function(){
                    $("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function(data){
                    $("#suggesstion-box").show();
                    $("#suggesstion-box").html(data);
                    $("#search-box").css("background","#FFF");
                }
            });
        });
    });
    //To select country name
    function selectNim(val) {
        $("#search-box").val(val);
        $("#suggesstion-box").hide();
    }

    $('form select').on('change invalid', function() {
        var textfield = $(this).get(0);

        // hapus dulu pesan yang sudah ada
        textfield.setCustomValidity('');

        if (!textfield.validity.valid) {
            textfield.setCustomValidity('Tidak boleh kosong!');
        }
    });
</script>