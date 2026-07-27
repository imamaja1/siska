<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('dosen/perwalian/search'); ?>" class="btn-sm btn-info flat"><i class="fa fa-search"></i> Pencarian Data</a>
        <a href="<?= site_url('dosen/perwalian/perwakilan'); ?>" class="btn-sm btn-warning flat"><i class="fa fa-user"></i> Perwakilan Dosen</a>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-body"><br>
        <form class="form-horizontal" action="<?= site_url('dosen/perwalian/get_perwalian_prosess'); ?>" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-3">Angkatan <label class="text-danger">*</label> :</label>
                <div class="col-sm-3">
                    <select class="form-control" id="angkatan" name="angkatan">
                        <option value="" selected disabled>Pilih Angkatan</option>
                        <?php foreach ($tahun_akademik as $row) { ?>
                            <option <?= set_select('angkatan', substr($row->tahun_akademik, 2, 2)) ?> value="<?= e(substr($row->tahun_akademik, 2, 2)) ?>"><?= e(substr($row->tahun_akademik, 0, 4)) ?></option>
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
                            <option <?= set_select('jurusan', $row->kode_jurusan . $row->kode_jenjang) ?> value="<?= e($row->kode_jurusan) ?><?= e($row->kode_jenjang) ?>"><?= e($row->singkatan_program_studi) ?></option>
                        <?php } ?>
                    </select>
                    <small class="text-danger"><?= form_error('jurusan'); ?></small>
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
<?php
$this->session->set_userdata('auth_kode_dosen', $this->session->userdata('kode_dosen'));
?>