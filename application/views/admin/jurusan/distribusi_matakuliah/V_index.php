<div class="box box-solid flat">
    <form class="form-horizontal" method="post" action="<?= site_url('admin/jurusan/distribusi_matakuliah/filter') ?>">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
        <div class="box-header">
            <h4><i class="fa fa-search"></i> Filter</h4>
        </div>
        <div class="box-body">
            <div class="form-group">
                <label for="angkatan" class="col-sm-2 control-label text-right">Tahun Akademik<span class="text-danger">*</span></label>
                <div class="col-sm-5">
                    <select name="kode_tahun_akademik" style="width: 100%" class="form-control select2" required id="angkatan">
                        <option value="">Pilih</option>
                        <?php foreach ($tahun_akademik as $row) : ?>
                            <option value="<?= e($row->kode_tahun_akademik) ?>"><?= e($row->tahun_akademik) ?> - <?= $row->semester == 0 ? "GENAP" : "GANJIL" ?></option>
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
