<div class="box box-solid flat">
    <div class="box-body">
        <a href="#" id="tambah" class=" btn-sm btn btn-primary flat" data-toggle="modal" onclick="$('#myModal').modal('toggle')"> <i class="fa fa-plus-circle"></i> Tambah</a>
    </div>
</div>

<div class="box box-primary">
    <div class="box-body" id="tabel-container">
        <?php $this->load->view('admin/jurusan/kurikulum/V_tabel_nama_kurikulum', array('data' => $data)); ?>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Tambah Nama Kurikulum</b></h4>
            </div>
            <form id="form-tambah-nama" method="POST" action="<?= site_url('admin/jurusan/kurikulum/nama_kurikulum/simpan') ?>">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kurikulum</label>
                        <input type="text" name="nama_kurikulum" class="form-control" placeholder="Nama Kurikulum" required>
                    </div>
                    <div>
                        <label>Nama Jurusan</label>
                        <select name="jurusan" class="form-control" required>
                            <option value="">Pilih</option>
                            <?php foreach ($prodi as $j) { ?>
                                <option value="<?= e($j->kode_program_studi) ?>"><?= e($j->singkatan_program_studi) ?> - <?= e($j->nama_program_studi) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
                    <button type="submit" name="submit" class="btn btn-success flat"><i class="fa fa-check-circle"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-nama-kurikulum" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Ubah Nama Kurikulum</b></h4>
            </div>
            <form id="form-edit-nama" method="POST" action="<?= site_url('admin/jurusan/kurikulum/nama_kurikulum/ubah') ?>">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kurikulum</label>
                        <input type="hidden" name="param" id="param">
                        <input type="text" name="nama_kurikulum" class="form-control" placeholder="Nama Kurikulum" id="edit-kurikulum" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Jurusan</label>
                        <select name="jurusan" class="form-control" id="edit-jurusan" required>
                            <option value="">Pilih</option>
                            <?php foreach ($prodi as $j) { ?>
                                <option value="<?= e($j->kode_program_studi) ?>"><?= e($j->singkatan_program_studi) ?> - <?= e($j->nama_program_studi) ?></option>
                            <?php } ?>
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
    function reloadTabel() {
        $.ajax({
            url: "<?= site_url('admin/jurusan/kurikulum/nama_kurikulum/render_tabel') ?>",
            success: function(res) {
                $('#tabel-container').html(res);
            }
        });
    }

    function editNamakurikulum(id) {
        var nama_kurikulum = $("#nama-kurikulum-" + id).html().trim();
        var nama_jurusan = $("#kode-jurusan-" + id).html().trim();
        $("#param").val(id);
        $("#edit-kurikulum").val(nama_kurikulum);
        $("#edit-jurusan").val(nama_jurusan);
        $("#edit-nama-kurikulum").modal("show");
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
            confirmButtonText: 'Ya',
        }).then(function () {
            $.ajax({
                url: "<?= site_url('admin/jurusan/kurikulum/nama_kurikulum/hapus') ?>/" + id,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
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

    $(document).ready(function() {
        $("#form-tambah-nama").submit(function(e) {
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
                        reloadTabel();
                        $("#form-tambah-nama")[0].reset();
                    } else {
                        swal('Gagal!', res.msg, 'error');
                    }
                }
            });
        });

        $("#form-edit-nama").submit(function(e) {
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
                        $('#edit-nama-kurikulum').modal('hide');
                        reloadTabel();
                    } else {
                        swal('Gagal!', res.msg, 'error');
                    }
                }
            });
        });
    });
</script>