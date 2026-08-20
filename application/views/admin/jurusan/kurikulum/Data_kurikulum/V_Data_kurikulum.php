<div class="box box-solid flat">
    <div class="box-body">
        <strong><?= "Kurikulum " . e($nama_kurikulum->nama_kurikulum) ?> <i class="fa fa-angle-double-right"></i> <?= e($nama_kurikulum->singkatan_program_studi) ?> </strong>
        <div class="pull-right">
            <a href="<?= site_url('admin/jurusan/kurikulum/data_kurikulum/excel/'.$kode_nama_kurikulum) ?>" class="btn btn-success btn-xs flat" title="Export Excel"><i class="fa fa-file-excel-o"></i> Excel</a>
            <a href="<?= site_url('admin/jurusan/kurikulum/data_kurikulum') ?>" class="btn btn-danger btn-xs flat"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
</div>

<div id="data-container">
    <?php $this->load->view('admin/jurusan/kurikulum/Data_kurikulum/V_render_data', array(
        'data' => $data,
        'kode_nama_kurikulum' => $kode_nama_kurikulum,
        'data_matakuliah' => $data_matakuliah,
        'mk_pilihan' => $mk_pilihan,
        'nama_pilihan' => $nama_pilihan
    )); ?>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <form id="form-tambah-data" method="POST" action="<?= site_url('admin/jurusan/kurikulum/data_kurikulum/simpan_data_kurikulum') ?>">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-body" style="height:400px; overflow-y:scroll;">
                    <div class="form-group">
                        <input type="hidden" name="semester" id="semester">
                        <input type="hidden" name="kode_nama_kurikulum" value="<?= e($kode_nama_kurikulum) ?>">
                        <?php foreach ($data_matakuliah as $row) : ?>
                            <div class="col-sm-12" style="border-top: 1px solid #61b5ed; border-collapse: collapse;">
                                <label>
                                    <input type="checkbox" name="id_matakuliah[]" value="<?= e($row->id_matakuliah) ?>" class="flat-red">
                                    <?= e($row->kode_matakuliah) ?> - <?= e($row->nama_matakuliah) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
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

<div class="modal fade" id="edit-kurikulum" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Ubah Data Kurikulum</b></h4>
            </div>
            <form id="form-edit-data" method="POST" action="<?= site_url('admin/jurusan/kurikulum/data_kurikulum/ubah') ?>">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Matakuliah</label>
                        <input type="hidden" name="param" id="edit-kode-kurikulum">
                        <input type="hidden" name="kode_nama_kurikulum" id="edit-kode-nama" value="<?= e($kode_nama_kurikulum) ?>">
                        <select name="id_matakuliah" id="edit-kode-matakuliah" class="form-control select2" style="width: 100%" required>
                            <?php foreach ($data_matakuliah as $row) : ?>
                                <option value="<?= e($row->id_matakuliah) ?>"><?= e($row->kode_matakuliah) ?> - <?= e($row->nama_matakuliah) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
                    <button type="submit" name="submit" class="btn btn-success flat"><i class="fa fa-check-circle"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    var kode_nama_kurikulum = "<?= e($kode_nama_kurikulum) ?>";

    function reloadData() {
        $.ajax({
            url: "<?= site_url('admin/jurusan/kurikulum/data_kurikulum/render_data') ?>/" + kode_nama_kurikulum,
            success: function(res) {
                $('#data-container').html(res);
            }
        });
    }

    function editKurikulum(id) {
        $("#edit-kode-kurikulum").val(id);
        $("#edit-kurikulum").modal("show");
    }

    function isi_data_kurikulum(semester) {
        $("#semester").val(semester);
        $("#myModal").modal('toggle');
        $("#myModalLabel").html("<b> Semester " + semester + "</b>");
    }

    function hapus(id, kode_nama_kurikulum) {
        swal({
            title: '',
            text: "Anda yakin ingin menghapus data ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya'
        }).then(function () {
            $.ajax({
                url: "<?= site_url('admin/jurusan/kurikulum/data_kurikulum/hapus') ?>/" + id + "/" + kode_nama_kurikulum,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res.status) {
                        swal('Success!', res.msg, 'success');
                        reloadData();
                    } else {
                        swal('Gagal!', res.msg, 'error');
                    }
                }
            });
        });
    }

    $(document).ready(function() {
        $("#form-tambah-data").submit(function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var data = $(this).serialize();
            $.ajax({
                url: url,
                data: data,
                type: 'POST',
                dataType: 'json',
                success: function(res) {
                    if (res.status) {
                        swal('Success!', res.msg, 'success');
                        $('#myModal').modal('hide');
                        reloadData();
                    } else {
                        swal('Gagal!', res.msg, 'error');
                    }
                }
            });
        });

        $("#form-edit-data").submit(function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var data = $(this).serialize();
            $.ajax({
                url: url,
                data: data,
                type: 'POST',
                dataType: 'json',
                success: function(res) {
                    if (res.status) {
                        swal('Success!', res.msg, 'success');
                        $('#edit-kurikulum').modal('hide');
                        reloadData();
                    } else {
                        swal('Gagal!', res.msg, 'error');
                    }
                }
            });
        });
    });
</script>