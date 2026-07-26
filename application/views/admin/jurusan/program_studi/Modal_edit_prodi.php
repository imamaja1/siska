<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel"><b>Edit Jurusan</b></h4>
</div>
<form method="POST" action="<?= site_url('admin/jurusan/program_studi/nama_jurusan/ubah'); ?>">
    <div class="modal-body">
        <div class="form-group">
            <label>Nama Fakultas</label>
            <input type="hidden" name="param" value="<?= $data->kode_program_studi ?>">
            <select class="form-control" name="kode_fakultas">
                <option value="" disabled selected>Pilih Fakultas</option>
                <?php foreach ($fakultas as $row) { ?>
                    <option <?= $row->kode_fakultas == $data->kode_fakultas ? "selected" : ""?> value="<?= $row->kode_fakultas?>"><?= $row->nama_fakultas?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Jenjang</label>
            <select class="form-control" name="jenjang" id="" required>
                <option value="" disabled selected>Jenjang Pendidikan</option>
                <option value="2" <?= $data->id_jenjang == 2 ? "selected" : ""?> >Diploma</option>
                <option value="1" <?= $data->id_jenjang == 1 ? "selected" : ""?> >Strata 1</option>
                <option value="3" <?= $data->id_jenjang == 3 ? "selected" : ""?> >Strata 2</option>
            </select>
        </div>
        <div class="form-group">
            <label>Kode Program Studi</label>
            <input  class="form-control" type="number" value="<?= $data->kode_prodi_univ ?>" name="kode_prodi_univ" maxlength="2" placeholder="Kode Program Studi">
        </div>
        <div class="form-group">
            <label>Nama Program Studi</label>
            <input required  class="form-control" type="text"  name="nama_jurusan" value="<?= $data->nama_program_studi ?>" placeholder="Nama Jurusan">
        </div>
        <div class="form-group">
            <label>Singkatan</label>
            <input required class="form-control" type="text" name="singkatan" value="<?= $data->singkatan_program_studi ?>" placeholder="Singkatan">
        </div>
        <div class="form-group">
            <label>Sistem Kompetensi</label>
            <select class="form-control" name="kompetensi" id="" required>
                <option value="" disabled selected>Pilih ya jika Prodi mempunyai MK Kompetensi</option>
                <option value="Y" <?= $data->kompetensi == 'Y' ? 'selected':''; ?> >Ya</option>
                <option value="N" <?= $data->kompetensi == 'N' ? 'selected':''; ?> >Tidak</option>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
        <button type="submit" class="btn btn-success flat "><i class="fa fa-check-circle"></i> Simpan</button>
    </div>
</form>