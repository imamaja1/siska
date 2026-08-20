<?= $this->session->flashdata('info') ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a href="#" class="btn btn-primary btn-sm flat" onclick="$('#tambah-tahun-akademik').modal('toggle')"><i class="fa fa-plus-circle"></i> Tambah</a>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-body table-responsive" id="tabel-container">
        <?php $this->load->view('admin/jurusan/V_tabel_tahun_akademik', array('data' => $data)); ?>
    </div>
</div>

<!--modal tambah tahun akademik-->
<div class="modal fade" id="tambah-tahun-akademik" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Tambah Tahun Akademik</b></h4>
            </div>
<form class="form-horizontal" id="form-tambah" method="POST" action="<?= site_url('admin/jurusan/tahun_akademik/simpan') ?>">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Tahun Akademik</label>
                        <div class="col-sm-5">
                            <input type="hidden" name="param" id="param">
                            <input type="text" required name="tahun_akademik" id="edit-tahun-akademik" placeholder="Tahun Akademik" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Semester</label>
                        <div class="col-sm-5">
                            <select required class="form-control" id="edit-semester" name="semester">
                                <option value="">Pilih</option>
                                <option value="1">Ganjil</option>
                                <option value="0">Genap</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Tanggal Mulai</label>
                        <div class="col-sm-5">
                            <div class="input-group date">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" name="tanggal_mulai" id="edit-tanggal-mulai" placeholder="Tahun Akademik" class="form-control datepicker">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Tanggal Berakhir</label>
                        <div class="col-sm-5">
                            <div class="input-group date">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" name="tanggal_berakhir" id="edit-tanggal-berakhir" placeholder="Tahun Akademik" class="form-control datepicker">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                    <button type="submit" id="submit" name="submit" class="btn btn-success flat"><i class="fa fa-check-circle"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--edit tahun akademik-->
<div class="modal fade" id="modal-edit" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="content-edit">
        </div>
    </div>
</div>
<script>
    $(function () {
        $(".datepicker").datepicker({
            format: "yyyy/mm/dd",
        });
    });

    function reloadTabel() {
        $.ajax({
            url: "<?= site_url('admin/jurusan/tahun_akademik/render_tabel') ?>",
            success: function(res) {
                $("#tabel-container").html(res);
            }
        });
    }

    function hapus(id) {
        swal({
            title: '',
            text: "Anda yakin ingin menghapus data ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            $.ajax({
                url: "<?= site_url('admin/jurusan/tahun_akademik/hapus/') ?>" + id,
                type: "GET",
                dataType: "json",
                success: function (res) {
                    if (res.status) {
                        swal('Success!', res.msg, 'success');
                        reloadTabel();
                    } else {
                        swal('Gagal!', res.msg, 'error');
                    }
                }
            });
        });
    }

    function editTahunakademik(id) {
        var url = "<?= site_url('admin/jurusan/tahun_akademik/edit') ?>/"+id;
        $.ajax({
            url : url,
            success : function (res) {
                $("#content-edit").html(res);
                $("#modal-edit").modal('show');
                $(".datepicker").datepicker({
                    format: "yyyy/mm/dd",
                });
                $("#form-edit").submit(function(e) {
                    e.preventDefault();
                    var formUrl = $(this).attr('action');
                    var formData = $(this).serialize();
                    $.ajax({
                        url: formUrl,
                        type: "POST",
                        data: formData,
                        dataType: "json",
                        success: function(res) {
                            if (res.status) {
                                swal('Success!', res.msg, 'success');
                                $("#modal-edit").modal('hide');
                                reloadTabel();
                            } else {
                                swal('Gagal!', res.msg, 'error');
                            }
                        }
                    });
                });
            },
            error : function () {
                console.log('gagal load data');
            }
        });
    }

    $(document).ready(function() {
        $("#form-tambah").submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: formData,
                dataType: "json",
                success: function(res) {
                    if (res.status) {
                        swal('Success!', res.msg, 'success');
                        $("#tambah-tahun-akademik").modal('hide');
                        $("#form-tambah")[0].reset();
                        reloadTabel();
                    } else {
                        swal('Gagal!', res.msg, 'error');
                    }
                }
            });
        });
    });

    function aktif(id) {
        $.ajax({
            url: "<?= site_url('admin/jurusan/tahun_akademik/ubah_status/') ?>" + id + "/A",
            type: "GET",
            dataType: "json",
            success: function (res) {
                if (res.status) {
                    swal('Success!', res.msg, 'success');
                    reloadTabel();
                } else {
                    swal('Gagal!', res.msg, 'error');
                }
            }
        });
    }

    function nonaktif(id) {
        $.ajax({
            url: "<?= site_url('admin/jurusan/tahun_akademik/ubah_status/') ?>" + id + "/N",
            type: "GET",
            dataType: "json",
            success: function (res) {
                if (res.status) {
                    swal('Success!', res.msg, 'success');
                    reloadTabel();
                } else {
                    swal('Gagal!', res.msg, 'error');
                }
            }
        });
    }
</script>