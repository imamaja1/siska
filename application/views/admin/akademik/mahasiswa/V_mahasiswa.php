<?= $this->session->flashdata('info') ?>

<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/mahasiswa/tambah'); ?>" class=" btn btn-xs btn-primary flat"><i class="fa fa-plus-circle"></i> Tambah Data Mahasiswa</a>
        <a href="<?= site_url('admin/akademik/mahasiswa/search'); ?>" class="btn btn-info btn-xs flat"><i class="fa fa-search"></i> Pencarian Mahasiswa</a>
      	<a a href="<?= site_url('admin/akademik/mahasiswa/Mahasiswa_Validasi_KRS'); ?>" class="btn btn-xs btn-primary flat"><i class="fa fa-file"></i> Validasi(Dosen) KRS Mahasiswa</a>
        <a a href="<?= site_url('admin/akademik/mahasiswa/reset_sandi'); ?>" class="btn btn-xs btn-danger flat"><i class="fa fa-refresh"></i> Reset Sandi Per Mahasiswa</a>
    </div>
</div>

<div class="box box-primary flat">
    <div class="box-header">
        <h5><b>Pencarian Data Mahasiswa Berdasarkan Angkatan dan Jurusan</b></h5><hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" method="POST" action="<?= site_url('admin/akademik/mahasiswa/get_mahasiswa_process'); ?>">
            <div class="form-group">
                <label class="control-label col-sm-3">Angkatan :</label>
                <div class="col-sm-3">
                    <select class="form-control" name="angkatan" id="angkatan">
                        <option value="" disabled selected>Pilih Angkatan</option>
                        <?php foreach ($tahun_akademik as $row) { ?>
                            <option <?php echo set_select('angkatan', substr($row->tahun_akademik, 2, 2)) ?> value="<?= substr($row->tahun_akademik, 2, 2) ?>"><?= substr($row->tahun_akademik, 0, 4) ?></option>
                        <?php } ?>
                    </select>
                    <div class="small text-danger"><?php echo form_error('angkatan'); ?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Jurusan :</label>
                <div class="col-sm-3">
                    <select class="form-control" name="kode_program_studi" id="jurusan">
                        <option value="" disabled selected>Pilih Jurusan</option>
                        <?php foreach ($program_studi as $row) { ?>
                            <option <?php echo set_select('kode_program_studi', $row->kode_program_studi) ?>  value="<?= $row->kode_program_studi?>"><?= $row->singkatan_program_studi ?> - (<?= $row->nama_program_studi ?>)</option>
                        <?php } ?>
                        <option value="Ekstensi">S1 Ekstensi</option>
                    </select>
                    <div class="small text-danger"><?php echo form_error('kode_program_studi'); ?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-3">
                    <button class="btn btn-success flat"><i class="fa fa-cog"></i> Proses</button>
                </div>
            </div>
        </form>
    </div>
</div>

