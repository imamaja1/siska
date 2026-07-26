<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Dosen Perwalian</h4>
</div>
<form onsubmit="simpan(this, event)" action="<?= site_url('admin/jurusan/perwalian/simpan_ubah_perwalian/'.$kode_perwalian."/".$filter) ?>" method="POST">
    <div class="modal-body">
        <div class="form-group">
            <label for="" >Nama Dosen Wali</label>
            <select name="kode_dosen" class="form-control select2" style="width: 100%;">
                <option value="" selected>Pilih</option>
                <?php foreach ($dosen as $row) : ?>
                    <option <?= $data->kode_dosen == $row->kode_dosen ? 'selected' : '' ?>  value="<?= $row->kode_dosen ?>"><?= $row->nama_dosen ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="" >Nama Dosen Perwakilan</label>
            <select name="kode_dosen_perwakilan" class="form-control select2" style="width: 100%;">
                <option value="" selected>Pilih</option>
                <?php foreach ($dosen as $row) : ?>
                    <option <?= $data->kode_dosen_perwakilan == $row->kode_dosen ? 'selected' : '' ?>  value="<?= $row->kode_dosen ?>"><?= $row->nama_dosen ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger flat" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
        <button type="submit" class="btn btn-primary flat"><i class="fa fa-check-square-o"></i> Simpan</button>
    </div>
</form>
<script>
    $(".select2").select2();
</script>