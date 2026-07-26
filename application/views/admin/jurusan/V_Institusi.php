<?= $this->session->flashdata('info'); ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a href="#" class="btn-primary btn-sm flat" onclick="$('#tambah-institusi').modal('toggle')"><i class="fa fa-plus-circle"></i> Tambah</a>
    </div>
</div>
<div class="box box-info flat">
    <div class="box-body">
        <?php if (count($data_institusi) > 0): ?>
            <div class="table-responsive">
                <table class="table demo-table">
                    <thead>
                        <tr>
                            <th id="th">No</th>
                            <th id="th">Kode Institusi</th>
                            <th id="th">Nama Institusi</th>
                            <th id="th">Singkatan</th>
                            <th id="th">Tindakan</th>
                        </tr>
                    </thead>

                    <?php
                    $no = 1;
                    foreach ($data_institusi as $row) {
                        ?>
                        <tr>
                            <td align="center" width="10"><?= $no++; ?></td>
                            <td align="center"><?= e($row->kode_institusi) ?></td>
                            <td id="nama-<?= e($row->kode_institusi) ?>"><?= e($row->nama_institusi) ?></td>
                            <td id="singkatan-<?= e($row->kode_institusi) ?>"><?= e($row->singkatan) ?></td>
                            <td align="center" width="150">
                                <a href="#!" class="btn-xs btn-info flat" onclick="javascript:editInstitusi(<?= e($row->kode_institusi) ?>)"><i class="fa fa-edit"></i> Ubah</a>&nbsp;
                                <a href="#!" class="btn-xs btn-danger flat" onclick="hapus('<?= site_url('admin/jurusan/institusi/hapus/' . $row->kode_institusi) ?>')"><i class="fa fa-trash"></i> Hapus</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>

                </table>
            </div>

        <?php else: ?>
            <p class="alert">Tidak ada data institusi.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Tambah Institusi -->
<div class="modal fade" id="tambah-institusi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Tambah Institusi</b></h4>
            </div>
            <form method="POST" action="<?= site_url('admin/jurusan/institusi/tambah'); ?>">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Institusi:</label>
                        <input required type="text" class="form-control" placeholder="Kode Institusi" name="kode" id="kode">
                    </div>
                    <div class="form-group">
                        <label>Nama Institusi:</label>
                        <textarea required name="nama" id="nama" class="form-control" placeholder="Nama Institusi" cols="20" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Singkatan Institusi:</label>
                        <input required class="form-control" type="text" name="singkatan" id="singkatan" placeholder="Singkatan Institusi">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
                    <button type="submit" class="btn btn-success flat "><i class="fa fa-check-circle"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Institusi -->
<div class="modal fade" id="edit-institusi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Edit Institusi</b></h4>
            </div>
            <form method="POST" action="<?= site_url('admin/jurusan/institusi/ubah'); ?>">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-body">
                    <input required readonly type="hidden" class="form-control" placeholder="Kode Institusi" name="kode-edit" id="kode-edit">
                    <div class="form-group">
                        <label>Nama Institusi:</label>
                        <textarea required name="nama-edit" id="nama-edit" class="form-control" placeholder="Nama Institusi" cols="20" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Singkatan Institusi:</label>
                        <input required class="form-control" type="text" name="singkatan-edit" id="singkatan-edit" placeholder="Singkatan Institusi">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
                    <button type="submit" class="btn btn-success flat "><i class="fa fa-check-circle"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hapus Institusi -->
<script>
    function editInstitusi(id) {

        var nama = $("#nama-" + id).html();
        var singkatan = $("#singkatan-" + id).html();

        $("#kode-edit").val(id);
        $("#nama-edit").val(nama);
        $("#singkatan-edit").val(singkatan);

        $("#edit-institusi").modal("show");
    }

    function hapus(url) {
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
            window.location.href = url;
        });

    }
</script>
