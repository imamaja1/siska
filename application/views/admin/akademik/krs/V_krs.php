<div class="box box-solid flat">
    <div class="box-body">
        <!--<a href="#" class="btn btn-success flat"><i class="fa fa-arrow-left"></i> Kembali</a>-->    
        <a href="<?= site_url('admin/akademik/krs/rekapitulasi_mahasiswa_per_matakuliah'); ?>" class="btn btn-xs btn-primary btn-sm flat"><i class="fa fa-file"></i> &nbsp;Rekap Mahasiswa Per Matakuliah Per Jurusan untuk Tahun Akademik Aktif</a>
        <a href="<?= site_url('admin/akademik/krs/pencarian_mahasiswa'); ?>" class="btn btn-sm btn-info btn-xs flat"><i class="fa fa-search"></i> &nbsp;Pencarian Mahasiswa untuk pencetakan KRS</a>
        <a href="<?= site_url('admin/akademik/krs/quick_search'); ?>" class="btn btn-sm btn-danger btn-xs flat"><i class="fa fa-search"></i> &nbsp;Current KRS</a>
      	<a href="<?= site_url('admin/akademik/krs/status_krs'); ?>" class="btn btn-sm btn-success btn-xs flat"><i class="fa fa-search"></i> &nbsp;Status KRS</a>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-header">
        <h5><b>KRS (Kartu Rencana Studi)</b></h5><hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="<?= site_url('admin/akademik/krs/get_krs_process'); ?>" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-3">Angkatan <label class="text-danger">*</label> :</label>
                <div class="col-sm-3">
                    <select class="form-control" id="angkatan" name="angkatan">
                        <option value="" selected disabled>Pilih Angkatan</option>
                        <?php foreach ($tahun_akademik as $row) { ?>
                            <option <?= set_select('angkatan', substr($row->tahun_akademik, 2, 2)) ?> value="<?= substr($row->tahun_akademik, 2, 2) ?>"><?= substr($row->tahun_akademik, 0, 4) ?></option>
                        <?php } ?>
                    </select>
                    <small class="text-danger"><?= form_error('angkatan'); ?></small>
                </div>

            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Jurusan <label class="text-danger">*</label> :</label>
                <div class="col-sm-3">
                    <select class="form-control" name="jurusan" id="jurusan">
                        <option value="" disabled selected>Pilih Program Studi</option>
                        <?php foreach ($program_studi as $row) { ?>
                            <option <?= set_select('jurusan', $row->kode_program_studi) ?> value="<?= e($row->kode_program_studi) ?>"><?= e($row->nama_program_studi) ?></option>
                        <?php } ?>
                    </select>
                    <small class="text-danger"><?= form_error('jurusan'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Semester <label class="text-danger">*</label> :</label>
                <div class="col-sm-3">
                    <select class="form-control" name="semester" id="semester">
                        <option value="" selected disabled>Pilih Semester</option>
                        <option <?= set_select('semester', 1) ?> value="1">1</option>
                        <option <?= set_select('semester', 2) ?> value="2">2</option>
                        <option <?= set_select('semester', 3) ?> value="3">3</option>
                        <option <?= set_select('semester', 4) ?> value="4">4</option>
                        <option <?= set_select('semester', 5) ?> value="5">5</option>
                        <option <?= set_select('semester', 6) ?> value="6">6</option>
                        <option <?= set_select('semester', 7) ?> value="7">7</option>
                        <option <?= set_select('semester', 8) ?> value="8">8</option>
                        <option <?= set_select('semester', 9) ?> value="9">9</option>
                        <option <?= set_select('semester', 10) ?> value="10">10</option>
                        <option <?= set_select('semester', 11) ?> value="11">11</option>
                        <option <?= set_select('semester', 12) ?> value="12">12</option>
                        <option <?= set_select('semester', 13) ?> value="13">13</option>
                        <option <?= set_select('semester', 14) ?> value="14">14</option>
                    </select>
                    <small class="text-danger"><?= form_error('semester'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-gear"></i> Proses</button>
                </div>
            </div>
        </form>
    </div>
</div>