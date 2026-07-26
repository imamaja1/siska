<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel"><b>Edit Ketua Jurusan</b></h4>
</div>
<form method="POST" action="<?= site_url('admin/jurusan/program_studi/ketua_jurusan/ubah'); ?>">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
    <div class="modal-body">
        <div class="form-group">
            <label>Nama Program Studi</label>
            <input type="hidden" name="param" id="param" value="<?= e($data->kode_kaprodi) ?>">
            <select class="form-control" name="kode_nama_jurusan">
                <option value="">Pilih Program Studi</option>
                <?php foreach ($nama_jurusan as $row) { ?>
                    <option <?= $row->kode_program_studi == $data->kode_program_studi ? 'selected' : '' ?> value="<?= e($row->kode_program_studi) ?>"><?= e($row->nama_program_studi) ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Nama Dosen</label>
            <select class="form-control select2" style="width: 100%;" name="kode_dosen">
                <option value="">Pilih Nama Dosen</option>
                <?php foreach ($dosen as $row) { ?>
                    <option <?= $row->kode_dosen == $data->kode_dosen ? 'selected' : '' ?> value="<?= e($row->kode_dosen) ?>"><?= e($row->nama_dosen) ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
        <button type="submit" class="btn btn-primary flat "><i class="fa fa-check-circle"></i> Simpan</button>
    </div>
</form>