<div class="box box-solid flat">
    <div class="box-body">
        <button class="btn btn-primary btn-sm btn-flat" onclick="$('#modal-tambah').modal('toggle')"><i
                    class="fa fa-plus-circle"></i> Tambah
        </button>
    </div>
</div>
<div id="landing">

</div>
<!--modal tambah-->
<!-- modal tambah prasyarat-->
<div class="modal fade" id="modal-tambah" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><b><span class="text-success"><i class="fa fa-plus-circle"></i></span> Form Kurikulum Angkatan</b></h4>
            </div>
            <form id="form-add" class="form-horizontal" method="POST" action="<?= site_url('admin/jurusan/kurikulum/kurikulum_angkatan/add') ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="angkatan" class="col-sm-3 control-label">Angkatan<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" required class="form-control" name="angkatan" placeholder="Ex: 2019, 2017">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="angkatan" class="col-sm-3 control-label">Nama Kurikulum<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select name="kode_nama_kurikulum" class="form-control select2" required style="width: 100%">
                                <option value="" selected disabled>Pilih</option>
                                <?php foreach ($nama_kurikulum as $row) : ?>
                                    <option value="<?= $row->kode_nama_kurikulum ?>"><?= $row->nama_kurikulum ?> - <?= $row->singkatan_program_studi?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="angkatan" class="col-sm-3 control-label">Extensi<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="ekstensi" id="optionsRadios1" value="N" checked >
                                    Tidak
                                </label>&nbsp;
                                <label>
                                    <input type="radio" name="ekstensi" id="optionsRadios1" value="Y" >
                                    Ya
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="angkatan" class="col-sm-3 control-label">Paket<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="paket" id="optionsRadios1" value="N" checked >
                                    Tidak
                                </label>&nbsp;
                                <label>
                                    <input type="radio" name="paket" id="optionsRadios1" value="Y" >
                                    Ya
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa  fa-remove"></i> Batal
                    </button>
                    <button type="submit" name="submit" class="btn btn-success flat"><i class="fa fa-check-circle"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--modal edit-->
<div class="modal fade" id="modal-edit" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="content-edit">

        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        all();
        $("#form-add").submit(function (e) {
            e.preventDefault();
            var url = $(this).attr('action');
            if (!$(this)[0].checkValidity())
            {
                swal('Warning','Semua form required harus di isi','error');
            }else{
                $.ajax({
                    url : url,
                    data : $(this).serialize(),
                    type : 'post',
                    success : function (res) {
                        var obj = JSON.parse(res);
                        if (obj.status)
                        {
                            all();
                            $('#modal-tambah').modal('hide');
                            swal('Success', obj.msg, 'success');
                            $(this).each(function () {
                                this.reset();
                            })
                        }else{
                            all();
                            $('#modal-tambah').modal('hide');
                            swal('Gagal', obj.msg, 'error');
                        }
                    }
                })
            }

        })
    });

    function all() {
        var url = "<?= site_url('admin/jurusan/kurikulum/kurikulum_angkatan/all') ?>";
        $.ajax({
            url : url,
            success : function (res) {
                $("#landing").html(res);
            }
        })
    }

    function edit(id) {
        var url = "<?= site_url('admin/jurusan/kurikulum/kurikulum_angkatan/edit') ?>/"+id;
        $.ajax({
            url : url,
            success : function (res) {
               $("#content-edit").html(res);
               $("#modal-edit").modal('show');
            }
        })
    }

    function update(contex, e) {
        e.preventDefault();
        var url = $(contex).attr('action');
        if (!$(contex)[0].checkValidity())
        {
            swal('Warning','Semua form required harus di isi','error');
        }else{
            $.ajax({
                url : url,
                data : $(contex).serialize(),
                type : 'post',
                success : function (res) {
                    var obj = JSON.parse(res);
                    if (obj.status)
                    {
                        all();
                        $('#modal-edit').modal('hide');
                        swal('Success', obj.msg, 'success');
                    }else{
                        all();
                        $('#modal-edit').modal('hide');
                        swal('Success', obj.msg, 'success');
                    }
                }
            })
        }

    }

    function hapus(id)
    {
        var url = "<?= site_url('admin/jurusan/kurikulum/kurikulum_angkatan/hapus') ?>/"+id;
        swal({
            title: '',
            text: "Anda yaikin menghapus data ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            $.ajax({
                url : url,
                success : function (res) {
                    var obj = JSON.parse(res);
                    if (obj.status)
                    {
                        all();
                        swal('Success!',obj.msg, 'success');
                    }else{
                        swal('Gagal!',obj.msg, 'error');
                    }
                }
            })
        });
    }
</script>