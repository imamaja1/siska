<?php if ($this->session->userdata('info')) : ?>
    <?= e($this->session->userdata('info')) ?>
<?php endif;?>
<div class="box box-solid">
    <div class="box-body">
        <a href="#" onclick="tambah()" class="btn btn-primary flat btn-sm"><i class="fa fa-plus"></i> Tambah Pengajar</a>
    </div>
</div>
<div class="box box-solid">
    <div class="box-header">
        <h4><i class="fa fa-search"></i> Pencarian Kelas</h4>
    </div>
    <div class="box-body">
        <form id="filter-form" class="form-horizontal" method="post" action="<?= site_url('admin/kuisioner/mengajar/filter') ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label for="kode_program_studi" class="control-label col-sm-2"> Matakulaih</label>
                <div class="col-sm-4">
                    <select required name="id_matakuliah" class="form-control select2" id="matakuliah" >
                        <option value="" disabled selected>Pilih</option>
                        <?php foreach ($matakuliah as $row) : ?>
                            <option value="<?= e($row->id_matakuliah) ?>"><?= e($row->kode_matakuliah) ?> - <?= e($row->nama_matakuliah) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="semester" class="control-label col-sm-2">Kelas</label>
                <div class="col-sm-4">
                    <select required name="kelas_id" class="form-control" id="nama-kelas">
                        <option value="" disabled selected>Pilih</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                    <button type="submit" name="sumbit" class="btn btn-primary btn-sm flat"><i class="fa fa-gear"></i> Prosses</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php if (isset($data)) : ?>
    <?= print_r($data) ?>
<?php endif; ?>
<!--modal tambah pengajar-->
<div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Tambah Pengajar</h4>
            </div>
            <form action="<?= site_url('admin/kuisioner/mengajar/simpan_mengajar') ?>" method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="modal-body">
                <div class="form-group">
                    <label for="kode_matakuliah"> Kode Matakuliah</label>
                    <select required name="id_matakuliah" class="select2 form-control" id="kode-matakuliah" style="width: 100%;">
                        <option value="" selected disabled>Pilih</option>
                        <?php foreach ($matakuliah as $row) : ?>
                            <option value="<?= e($row->id_matakuliah) ?>"><?= e($row->kode_matakuliah) ?> - <?= e($row->nama_matakuliah) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="kelas_id"> Nama Kelas</label>
                    <select required name="kelas_id" class="form-control" id="kelas-id">
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-success btn-sm flat pull-right" onclick="tambah_form(); return false;"><i class="fa fa-plus"></i> Tambah Dosen</button>
                    <br>
                    <label for="kode_dosen">Nama Dosen</label>
                        <input id="idf" value="1" type="hidden" />
                        <input id="kode-dosen" name="kode_dosen[]" type="hidden">
                        <input required type="text" id="search-box" class="form-control" placeholder="Masukkan Nama Dosen">
                    <div id="suggesstion-box"></div>
                </div>
                <div id="dinamic-form">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger flat" data-dismiss="modal">Close</button>
                <button type="submit" name="submit" class="btn btn-primary flat"><i class="fa fa-check-square-o"></i> Simpan</button>
            </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div id="disini"></div>
<script>
    function tambah() {
        $('#modal-tambah').modal('toggle');
    }

    $('#kode-matakuliah').change(function () {
        var id_matakuliah = $(this).val();
        $.ajax({
            url : "<?= site_url('admin/kuisioner/mengajar/get_kelas') ?>/"+id_matakuliah,
            type : "get",
            success : function (data) {
                console.log('kamu berhasil');
                $('#kelas-id').html(data);
            },
            error : function () {
                console.log('gagal mengambil data');
            }
        });

    });

    $('#matakuliah').change(function () {
        var id_matakuliah = $(this).val();
        $.ajax({
            url : "<?= site_url('admin/kuisioner/mengajar/get_kelas') ?>/"+id_matakuliah,
            type : "get",
            success : function (data) {
                console.log('kamu berhasil');
                $('#nama-kelas').html(data);
            },
            error : function () {
                console.log('gagal mengambil data');
            }
        });

    });

    function tambah_form() {
        var idf = $('#idf').val();
        var str;
        str = "<div class='form-group' id='srow" + idf + "'>" +
            "<div class='col-sm-10' style=\"padding-left: 0;\">" +
            "<input id='kode-dosen-"+idf+"' name='kode_dosen[]' type='hidden'>"+
            "<input required type='text' onfocus='cari(" + idf + ");' id='search-box-" +idf+ "' class='form-control' placeholder='Masukkan Nama Dosen' >" +
            "</div><a href='#' class='btn btn-danger flat' onclick='hapusElemen(\"#srow" + idf + "\"); return false;'><i class='fa fa-times'></i> Hapus</a>" +
            "<div id='suggesstion-box-" +idf+ "'></div></div>";
        $('#dinamic-form').append(str);
        idf = (idf-1) + 2;
        $('#idf').val(idf);
    }

    function hapusElemen(idf) {
        $(idf).remove();
    }

    var id;
    function cari(id) {
        this.id = id;
        $("#search-box-"+id).keyup(function () {
            $.ajax({
                type: "post",
                url: "<?= site_url('admin/kuisioner/mengajar/autocomplate_dinamic') ?>",
                data: 'keyword=' + $(this).val(),
                beforeSend: function () {
                    $("#search-box-"+id).css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function (data) {
                    $("#suggesstion-box-"+id).show();
                    $("#suggesstion-box-"+id).html(data);
                    $("#search-box-"+id).css("background", "#FFF");
                }
            });
        });
    }

    function pilihNim(val,nama_dosen) {
        console.log(nama_dosen);
        $("#search-box-"+id).val(nama_dosen);
        $("#kode-dosen-"+id).val(val);
        $("#suggesstion-box-"+id).hide();
    }

</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#search-box").keyup(function () {
            $.ajax({
                type: "post",
                url: "<?= site_url('admin/kuisioner/mengajar/autocomplate') ?>",
                data: 'keyword=' + $(this).val(),
                beforeSend: function () {
                    $(".search-box").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function (data) {
                    $("#suggesstion-box").show();
                    $("#suggesstion-box").html(data);
                    $("#search-box").css("background", "#FFF");
                }
            });
        });

    });
    //To select country name
    function selectNim(val,nama_dosen) {
        console.log(nama_dosen);
        $("#search-box").val(nama_dosen);
        $("#kode-dosen").val(val);
        $("#suggesstion-box").hide();
    }
</script>
<script>
    $('#filter-form').submit(function () {
       $.ajax({
           url  : $(this).attr('action'),
           type : 'post',
           data : $(this).serialize(),
           success : function (data) {
               $('#disini').load("<?= site_url('admin/kuisioner/mengajar/data_pengajar') ?>");
           },
           error : function () {
               alert('kamu gagal');
           }
       });

       return false;
    });
</script>