<?= $this->session->flashdata('info') ?>
<div class="box box-solid flat">
    <div class="box-body">
        <div class="col-xs-3">
            <select id="kode-nama-kurikulum" class="form-control select2">
                <option selected disabled value="">Pilih Kurikulum</option>
                <?php foreach ($nama_kurikulum as $row) : ?>
                    <option value="<?= $row->kode_nama_kurikulum ?>"> <?= $row->nama_kurikulum . " - " . $row->singkatan_program_studi ?> </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-xs-3">
            <a href="#" id="tambah" class="btn btn-primary flat" data-toggle="modal" onclick="$('#myModal').modal('toggle');"> <i class="fa fa-plus"></i> Tambah Matakuliah Prasyarat</a>
        </div>
    </div>
</div>

<div id="isi">

</div>
<!-- modal tambah prasyarat-->
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Tambah Matakuliah Prasyarat</b></h4>
            </div>
            <form id="form-tambah" method="POST" action="<?= site_url('admin/jurusan/kurikulum/matakuliah_prasyarat/tambah_prasyarat') ?>">
                <div class="modal-body">
                  <!--  <div class="form-group">
                        <label>Nama Kurikulum</label>
                        <select name="nama_kurikulum" class="form-control select2" id="nama-kurikulum" style="width: 100%">
                            <option selected disabled value="">Nama Kurikulum</option>
                            <?php foreach ($nama_kurikulum as $row) : ?>
                                <option value="<?= $row->kode_nama_kurikulum ?>"> <?= $row->nama_kurikulum . " - " . $row->singkatan_program_studi ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>-->
                    <div class="form-group">
                        <label>Matakuliah yg diambil</label>
                        <select name="id_matakuliah_ambil" class="form-control select2" style="width: 100%;" id="yg_diambil">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Matakuliah prasyarat</label>
                        <select name="id_matakuliah_syarat" class="form-control select2" style="width: 100%;" id="syarat">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jenis Matakuliah Prasyarat</label>
                        <select name="jenis_prasyarat" class="form-control" style="width: 100%;">
                            <option value="" selected disabled>Pilih</option>
                            <option value="LA">LANSUNG</option>
                            <option value="LU">LULUS</option>
                            <option value="AM">AMBIL</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                    <button type="submit" name="submit" class="btn btn-success flat"><i class="fa fa-check-circle"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- model edit-prasyarat -->
<!--<div class="modal fade" id="edit-prasyarat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">-->
<div class="modal fade" id="edit-prasyarat" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="isi-edit">

        </div>
    </div>
</div>

<!-- script -->
<script type="text/javascript">
    var kode_nama_kurikulum_super = '';
    $(function () {
        $('.dataTables-example').dataTable();
        $(".select-modal").select2({
            dropdownParent: $("#myModal")
        });
    });

    function editPrasyarat(id,kode_nama_kurikulum) {
//        console.log(kode_nama_kurikulum);
        var url = "<?= site_url('admin/jurusan/kurikulum/matakuliah_prasyarat/edit_prasyarat') ?>/"+id+"/"+kode_nama_kurikulum;
        $.ajax({
            url : url,
            success : function (res) {
                $("#isi-edit").html(res);
                $("#edit-prasyarat").modal("show");
            }
        });

    }

    //$("#nama-kurikulum").change(function () {
    //
    //    var nama_kurikulum = $("#nama-kurikulum").val();
    //    // kode_nama_kurikulum = $("#nama-kurikulum").val();
    //    $.ajax({
    //        url: "<?//= site_url('admin/jurusan/kurikulum/Matakuliah_prasyarat/get_matakuliah') ?>//",
    //        type: "POST",
    //        data: "nama_kurikulum=" + nama_kurikulum,
    //        success: function (data) {
    //            $("#yg_diambil").html(data);
    //            $("#syarat").html(data);
    //        }
    //    });
    //});

    function matakuliah(){
        $.ajax({
            url: "<?= site_url('admin/jurusan/kurikulum/Matakuliah_prasyarat/get_matakuliah') ?>",
            type: "POST",
            data: "nama_kurikulum=" + kode_nama_kurikulum_super,
            success: function (data) {
                $("#yg_diambil").html(data);
                $("#syarat").html(data);
            }
        });
    }

    $("#kode-nama-kurikulum").change(function () {

        var kode_nama_kurikulum = $("#kode-nama-kurikulum").val();
        kode_nama_kurikulum_super = $("#kode-nama-kurikulum").val();
        $.ajax({
            url: "<?= site_url('admin/jurusan/kurikulum/Matakuliah_prasyarat/filter') ?>",
            type: "POST",
            data: "kode_nama_kurikulum=" + kode_nama_kurikulum,
            success: function (data) {
                $('#isi').html(data);
                matakuliah();
            },
        });
    });

    function ambil() {
        $.ajax({
            url: "<?= site_url('admin/jurusan/kurikulum/Matakuliah_prasyarat/filter') ?>",
            type: "POST",
            data: "kode_nama_kurikulum=" + kode_nama_kurikulum_super,
            success: function (data) {
                $('#isi').html(data);
            },
        });
    }

    function hapus(id)
    {
        var url = "<?= site_url('admin/jurusan/kurikulum/matakuliah_prasyarat/hapus') ?>/" + id;
        swal({
            title: '',
            text: "Anda yaikin menghapus data ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(function () {
            // window.location.href = url;
            $.ajax({
                url: url,
                type: "get",
                success: function (data) {
                    ambil();
                },
                error : function (xhr) {
                    console.log('gagal hapus')
                }
            });
            // filter();
        });
    }

    $("#form-tambah").submit(function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            url : url,
            data : data +"&nama_kurikulum=" + kode_nama_kurikulum_super,
            type : 'post',
            success : function () {
                // alert('data berhasil disimpan');
                ambil();
                $("#myModal").modal('toggle');
                // $(".modal-backdrop").remove;
            },
            error : function (res) {
                alert('data gagal disimpan');
                ambil();
            }
        })
    })



</script>