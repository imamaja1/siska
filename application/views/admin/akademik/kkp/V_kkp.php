<div class="box box-solid flat">
    <div class="box-body">
        <!--<a href="#" class="btn btn-success flat"><i class="fa fa-arrow-left"></i> Kembali</a>-->    
        <a href="<?= site_url('admin/akademik/kkp/search'); ?>" class="btn-success btn-sm flat"><i class="fa fa-search"></i> &nbsp;Pencarian/Update Nilai KKP Mahasiswa</a>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-header">
        <b style="font-size: 15px;">Pencarian Mahasiswa untuk Nilai KKP</b><hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="<?= site_url('admin/akademik/kkp/get_mahasiswa_process'); ?>" method="POST">
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
                    <select class="form-control" name="kode_program_studi" id="kode_program_studi">
                        <option value="" disabled selected>Pilih Program Studi</option>
                        <?php foreach ($program_studi as $row) { ?>
                            <option <?= set_select('kode_program_studi', $row->kode_program_studi) ?> value="<?= $row->kode_program_studi ?>"><?= $row->nama_program_studi?></option>
                        <?php } ?>
                    </select>
                    <small class="text-danger"><?= form_error('kode_program_studi'); ?></small>
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
