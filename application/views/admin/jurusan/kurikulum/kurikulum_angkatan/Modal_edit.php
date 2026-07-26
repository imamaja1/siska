<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title" id="myModalLabel"><b><span class="text-success"><i class="fa fa-plus-circle"></i></span> Form Kurikulum Angkatan</b></h4>
</div>
<form class="form-horizontal" method="POST" onsubmit="update(this, event)" action="<?= site_url('admin/jurusan/kurikulum/kurikulum_angkatan/update/'.$id) ?>">
    <div class="modal-body">
        <div class="form-group">
            <label for="angkatan" class="col-sm-3 control-label">Angkatan<span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="text" required class="form-control" value="<?= $data->angkatan ?>" name="angkatan" placeholder="Ex: 2019, 2017">
            </div>
        </div>
        <div class="form-group">
            <label for="angkatan" class="col-sm-3 control-label">Nama Kurikulum<span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <select name="kode_nama_kurikulum" class="form-control select2" required style="width: 100%">
                    <option value="" selected disabled>Pilih</option>
                    <?php foreach ($nama_kurikulum as $row) : ?>
                        <option <?= $data->kode_nama_kurikulum == $row->kode_nama_kurikulum ? 'selected' : '' ?> value="<?= $row->kode_nama_kurikulum ?>"><?= $row->nama_kurikulum ?> - <?= $row->singkatan_program_studi?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="angkatan" class="col-sm-3 control-label">Extensi<span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <div class="radio">
                    <label>
                        <input type="radio" <?= $data->ekstensi == 'N' ? 'checked' : '' ?>  name="ekstensi" id="optionsRadios1" value="N" >
                        Tidak
                    </label>&nbsp;
                    <label>
                        <input type="radio" <?= $data->ekstensi == 'Y' ? 'checked' : '' ?> name="ekstensi" id="optionsRadios1" value="Y" >
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
                        <input type="radio" <?= $data->paket == 'N' ? 'checked' : '' ?> name="paket" id="optionsRadios1" value="N" checked >
                        Tidak
                    </label>&nbsp;
                    <label>
                        <input type="radio" <?= $data->paket == 'Y' ? 'checked' : '' ?> name="paket" id="optionsRadios1" value="Y" >
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
<script>
    $(".select2").select2();
</script>