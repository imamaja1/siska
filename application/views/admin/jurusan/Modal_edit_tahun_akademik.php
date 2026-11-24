<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"><b>Edit Tahun Akademik</b></h4>
</div>
<form class="form-horizontal" id="form-edit" method="POST" action="<?= site_url('admin/jurusan/tahun_akademik/ubah/'.$id) ?>">
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
        <hr style="margin: 10px 0;">
        <div class="form-group">
            <label class="control-label col-sm-3">Tanggal Awal KRS</label>
            <div class="col-sm-5">
                <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" name="tgl_awal_krs" value="<?= (!empty($data->tgl_awal_krs) && $data->tgl_awal_krs !== '0000-00-00') ? e($data->tgl_awal_krs) : '' ?>" placeholder="YYYY-MM-DD (Opsional)" class="form-control datepicker" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3">Batas Akhir KRS</label>
            <div class="col-sm-5">
                <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" name="tgl_batas_krs" value="<?= (!empty($data->tgl_batas_krs) && $data->tgl_batas_krs !== '0000-00-00') ? e($data->tgl_batas_krs) : '' ?>" placeholder="YYYY-MM-DD (Opsional)" class="form-control datepicker" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3">Status KRS</label>
            <div class="col-sm-5">
                <?php $status_krs_val = isset($data->status_krs) ? $data->status_krs : 'N'; ?>
                <select class="form-control" name="status_krs">
                    <option value="N" <?= $status_krs_val === 'N' ? 'selected' : '' ?>>Nonaktif</option>
                    <option value="A" <?= $status_krs_val === 'A' ? 'selected' : '' ?>>Aktif</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
        <button type="submit" id="submit" name="submit" class="btn btn-success flat"><i class="fa fa-check-circle"></i> Simpan</button>
    </div>
</form>
