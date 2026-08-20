<?= $this->session->flashdata('info'); ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a href="#" class="btn-primary btn-sm flat" onclick="$('#modal-add').modal('toggle')"><i class="fa fa-plus-circle"></i> Tambah</a>
    </div>
</div>
<div class="box box-info flat">
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table">
                <thead>
                    <tr>
                        <th style="width:3%; text-align:center">No</th>
                        <th style="text-align:center">Kode Fakultas</th>
                        <th style="text-align:center">Nama Fakultas</th>
                        <th style="text-align:center">Dekan</th>
                        <th style="text-align:center; width:150px">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($fakultas as $row) : ?>
                        <tr>
                            <td align="center"><?= $no++ ?></td>
                            <td align="center"><?= e($row->kode_fakultas) ?></td>
                            <td><?= e($row->nama_fakultas) ?></td>
                            <td><?= e($row->dekan) ?></td>
                            <td align="center">
                                <a href="#!" class="btn-xs btn-info flat" onclick="editFakultas('<?= e($row->kode_fakultas) ?>', '<?= e($row->nama_fakultas) ?>', '<?= e($row->dekan_kode) ?>')"><i class="fa fa-edit"></i> Ubah</a>&nbsp;
                                <a href="#!" class="btn-xs btn-danger flat" onclick="hapus('<?= site_url('admin/jurusan/universitas/fakultas/delete/' . $row->kode_fakultas) ?>')"><i class="fa fa-trash"></i> Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><b>Tambah Fakultas</b></h4>
            </div>
            <form action="<?= site_url('admin/jurusan/universitas/fakultas/add') ?>" method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Fakultas:</label>
                        <input type="text" name="kode_fakultas" class="form-control" placeholder="Kode Fakultas" autofocus required>
                    </div>
                    <div class="form-group">
                        <label>Nama Fakultas:</label>
                        <input type="text" name="nama_fakultas" class="form-control" placeholder="Nama Fakultas" required>
                    </div>
                    <div class="form-group">
                        <label>Dekan:</label>
                        <select name="dekan" class="form-control">
                            <?php foreach ($dosen as $item) : ?>
                                <option value="<?= e($item->kode_dosen) ?>"><?= e($item->nama_dosen) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
                    <button type="submit" class="btn btn-success flat"><i class="fa fa-check-circle"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><b>Edit Fakultas</b></h4>
            </div>
            <form id="form-edit-fakultas" method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Fakultas:</label>
                        <input type="text" id="kode-fakultas-edit" name="kode_fakultas" class="form-control" readonly required>
                    </div>
                    <div class="form-group">
                        <label>Nama Fakultas:</label>
                        <input type="text" id="nama-fakultas-edit" name="nama_fakultas" class="form-control" placeholder="Nama Fakultas" required>
                    </div>
                    <div class="form-group">
                        <label>Dekan:</label>
                        <select name="dekan" id="dekan-edit" class="form-control">
                            <?php foreach ($dosen as $item) : ?>
                                <option value="<?= e($item->kode_dosen) ?>"><?= e($item->nama_dosen) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
                    <button type="submit" class="btn btn-success flat"><i class="fa fa-check-circle"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editFakultas(kode, nama, dekan) {
        $("#kode-fakultas-edit").val(kode);
        $("#nama-fakultas-edit").val(nama);
        $("#dekan-edit").val(dekan);
        $("#form-edit-fakultas").attr("action", "<?= site_url('admin/jurusan/universitas/fakultas/update') ?>/" + kode);
        $("#modal-edit").modal('show');
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
