<div class="box box-solid flat">
    <form class="form-horizontal" method="post" action="<?= site_url('admin/jurusan/student_body/filter') ?>">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
        <div class="box-body">
            <div class="form-group">
                <label for="jurusan" class="col-sm-2 control-label text-right">Jurusan<span class="text-danger">*</span></label>
                <div class="col-sm-5">
                    <select name="kode_program_studi" required class="form-control" id="jurusan">
                        <option value="">Pilih</option>
                        <?php foreach ($prodi as $row) : ?>
                            <option value="<?= e($row->kode_program_studi) ?>"><?= e($row->nama_program_studi) ?> (<?= e($row->singkatan_program_studi) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="angkatan" class="col-sm-2 control-label text-right">Angkatan<span class="text-danger">*</span></label>
                <div class="col-sm-5">
                    <select name="angkatan" class="form-control" required id="angkatan">
                        <option value="">Pilih</option>
                        <?php foreach ($angkatan as $row) : ?>
                            <option value="<?= substr($row->tahun_akademik,2,2) ?>"><?= substr($row->tahun_akademik,0,4) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3 col-sm-offset-2">
                    <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>
        </div>
    </form>
</div>