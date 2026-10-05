<div class="box box-solid flat">
    <div class="box-header">
        <h4><i style="color: #017ebc;" class="fa fa-graduation-cap"></i> <strong>Laporan Mahasiswa Kelulusan</strong></h4>
        <hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" method="post" action="<?= site_url('admin/laporan/kelulusan/filter') ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-2">Periode Kelulusan</label>
                <div class="col-sm-4">
                    <select name="tahun_akademik" class="form-control select2">
                        <option value="all">Semua Periode</option>
                        <?php foreach ($tahun_akademik as $row) : ?>
                            <option value="<?= e($row->kode_tahun_akademik) ?>">
                                <?= e($row->tahun_akademik) ?> - <?= $row->semester == 0 ? 'Genap' : 'Ganjil' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Program Studi</label>
                <div class="col-sm-4">
                    <select name="prodi" class="form-control select2">
                        <option value="all">Semua Program Studi</option>
                        <?php foreach ($nama_jurusan as $row) : ?>
                            <option value="<?= e($row->kode_program_studi) ?>">
                                <?= e($row->singkatan_program_studi) ?> - <?= e($row->nama_program_studi) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Angkatan Masuk</label>
                <div class="col-sm-4">
                    <select name="angkatan" class="form-control select2">
                        <option value="all">Semua Angkatan</option>
                        <?php foreach ($tahun_angkatan as $row) : ?>
                            <option value="<?= e(substr($row->tahun_akademik, 2, 2)) ?>">
                                <?= e(substr($row->tahun_akademik, 0, 4)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3 col-sm-offset-2">
                    <button type="submit" name="submit" class="btn btn-primary btn-sm flat">
                        <i class="fa fa-filter"></i> Tampilkan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
