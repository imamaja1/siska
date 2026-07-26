<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"><b>Tambah Tahun Akademik</b></h4>
</div>
<form class="form-horizontal" id="form" method="POST" action="<?= site_url('admin/jurusan/tahun_akademik/ubah/'.$id) ?>">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label col-sm-3">Tahun Akademik</label>
            <div class="col-sm-5">
                <input type="text" required name="tahun_akademik" value="<?= e($data->tahun_akademik) ?>" placeholder="Tahun Akademik" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3">Semester</label>
            <div class="col-sm-5">
                <select required class="form-control" name="semester">
                    <option value="">Pilih</option>
                    <option <?= $data->semester == '1' ? 'selected' : ''?> value="1">Ganjil</option>
                    <option <?= $data->semester == '0' ? 'selected' : ''?> value="0">Genap</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3">Tanggal Mulai</label>
            <div class="col-sm-5">
                <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" name="tanggal_mulai" value="<?= e($data->tanggal_mulai) ?>" placeholder="Tahun Akademik" class="form-control datepicker">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3">Tanggal Berakhir</label>
            <div class="col-sm-5">
                <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" name="tanggal_berakhir" value="<?= e($data->tanggal_berakhir) ?>" placeholder="Tahun Akademik" class="form-control datepicker">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
        <button type="submit" id="submit" name="submit" class="btn btn-success flat"><i class="fa fa-check-circle"></i> Simpan</button>
    </div>
</form>
