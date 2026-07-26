<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title"><b>Tambah Fakultas</b></h4>
</div>
<form action="<?= site_url('admin/jurusan/universitas/fakultas/add') ?>" method="post">
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
                    <option value="<?= $item->kode_dosen ?>"><?= $item->nama_dosen ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
        <button type="submit" class="btn btn-success flat"><i class="fa fa-check-circle"></i> Simpan</button>
    </div>
</form>
