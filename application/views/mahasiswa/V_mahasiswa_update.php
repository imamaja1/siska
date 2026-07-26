<div class="box box-primary flat">
    <div class="box-body"><br>
        <?php if($this->session->flashdata('info')) : ?>
        <?= $this->session->flashdata('info') ?>
        <?php endif; ?>
        <b>I. IDENTITAS CALON MAHASISWA</b>    <font style="color: red;">Perhatian ! form pengisian yang mengandung tanda bintang (*) harus diisi.</font><hr>
        <form id="myform" class="form-horizontal" action="<?= site_url('mahasiswa/profil/simpan_update') ?>" method="POST"  enctype="multipart/form-data">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

            <div class="form-group">
                <label class="control-label col-sm-3">Nomor Induk Mahasiswa <label style="color: red">*</label> :</label>
                <div class="col-sm-2">
                    <input readonly type="text" class="form-control" value="<?= set_value('nim', $data_mahasiswa->nim) ?>" name="nim">
                    <small class='text-danger'><?= form_error('nim'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Nomor Pokok Mahasiswa <label style="color: red;">*</label> :</label>
                <div class="col-sm-3">
                    <input readonly type="text" class="form-control" value="<?= set_value('npm', $data_mahasiswa->npm) ?>" name="npm">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Nomor Induk Kependudukan(NIK) <label style="color: red;">*</label> :</label>
                <div class="col-sm-3">
                    <input minlength="16" maxlength="16" type="text" class="form-control" value="<?= set_value('nik', $data_mahasiswa->nik) ?>" name="nik">
                  <?php if(form_error('nik')) : ?>
                    <smal class="text-danger"><?= form_error('nik') ?></smal>
                  <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Nomor Pendaftaran <label style="color: red;">*</label> :</label>
                <div class="col-sm-2">
                    <input readonly type="text" class="form-control" value="<?= set_value('no_pendaftaran', $data_mahasiswa->nomor_pendaftaran) ?>" name="no_pendaftaran">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Nomor Pendaftaran Ulang <label style="color: red;">*</label> :</label>
                <div class="col-sm-2">
                    <input readonly type="text" class="form-control" value="<?= set_value('no_pendaftaran_ulang', $data_mahasiswa->nomor_pendaftaran_ulang) ?>" name="no_pendaftaran_ulang">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Nama Mahasiswa <label style="color: red;">*</label> :</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?= set_value('nama_mahasiswa', $data_mahasiswa->nama_mahasiswa) ?>" name="nama_mahasiswa" readonly>
                    <small style="color: red"><?= form_error('nama_mahasiswa'); ?></small>
                </div>

            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Tempat Lahir dan Tanggal Lahir <label style="color: red;">*</label> :</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" value="<?= set_value('tempat_lahir', $data_mahasiswa->tempat_lahir) ?>" name="tempat_lahir">
                    <small style="color: red"><?= form_error('tempat_lahir'); ?></small>
                </div>
                <div class="col-sm-2">
                    <input type="text" class="form-control flat datepicker" value="<?= set_value('tanggal_lahir', $data_mahasiswa->tanggal_lahir) ?>" name="tanggal_lahir" id="tanggal">
                    <small style="color: red"><?= form_error('tanggal_lahir'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Alamat Lengkap <label style="color: red;" >*</label> :</label>
                <div class="col-sm-5">
                    <textarea name="alamat_lengkap" class="form-control" cols="20" rows="5"><?= set_value('alamat_lengkap', $data_mahasiswa->alamat) ?></textarea>
                    <small style="color: red"><?= form_error('alamat_lengkap'); ?></small>
                </div>

            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Kota dan Propinsi <label style="color: red;">*</label> :</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" value="<?= set_value('kota', $data_mahasiswa->kota) ?>" name="kota">
                    <small style="color: red"><?= form_error('kota'); ?></small>
                </div>
                <div class="col-sm-3">
                    <select class="form-control"name="propinsi">
                        <option value="" disabled selected>Pilih Propinsi</option>
                        <?php foreach ($provinsi as $row) { ?>
                            <option <?= set_select('propinsi', $row->nama) ?> <?= $data_mahasiswa->propinsi == $row->nama ? "selected" : "" ?> value="<?= e($row->nama) ?>"><?= e($row->nama) ?></option>
                        <?php } ?>
                    </select>
                    <small style="color: red"><?= form_error('propinsi'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">No. Telepon <label style="color: red;">*</label> :</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" value="<?= set_value('telepon', $data_mahasiswa->telepon) ?>" name="telepon">
                    <small style="color: red"><?= form_error('telepon'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Jenis Kelamin <label style="color: red;">*</label> :</label>
                <div class="col-sm-2">
                    <input type="radio" value="L" <?= set_radio('jenis_kelamin', 'L') ?>  <?= $data_mahasiswa->jenis_kelamin == "L" ? "checked" : "" ?>  name="jenis_kelamin"> Laki-Laki
                </div>
                <div class="col-sm-2">
                    <input type="radio" value="P" <?= set_radio('jenis_kelamin', 'P') ?> <?= $data_mahasiswa->jenis_kelamin == "P" ? "checked" : "" ?> name="jenis_kelamin"> Perempuan
                </div>
                <div class="col-sm-3">
                    <small style="color: red"><?= form_error('jenis_kelamin'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Agama <label style="color: red;">*</label> :</label>
                <div class="col-sm-2">
                    <select class="form-control" name="agama">
                        <option value="" selected disabled>Pilih Agama</option>
                        <?php
                        $agama = $data_mahasiswa->agama;
                        if ($agama == "Islam") {
                            echo '<option ' . set_select('agama', 'Islam') . ' value="Islam" selected>Islam</option>';
                        } else {
                            echo '<option ' . set_select('agama', 'Islam') . ' value="Islam">Islam</option>';
                        }
                        if ($agama == "Hindu") {
                            echo '<option ' . set_select('agama', 'Hindu') . ' value="Hindu" selected>Hindu</option>';
                        } else {
                            echo '<option ' . set_select('agama', 'Hindu') . ' value="Hindu">Hindu</option>';
                        }
                        if ($agama == "Kristen") {
                            echo '<option ' . set_select('agama', 'Kristen') . ' value="Kristen" selected>Kristen</option>';
                        } else {
                            echo '<option ' . set_select('agama', 'Kristen') . ' value="Kristen">Kristen</option>';
                        }
                        if ($agama == "Katolik") {
                            echo '<option ' . set_select('agama', 'Katolik') . ' value="Katolik" selected>Katolik</option>';
                        } else {
                            echo '<option ' . set_select('agama', 'Katolik') . ' value="Katolik">Katolik</option>';
                        }
                        if ($agama == "Budha") {
                            echo '<option ' . set_select('agama', 'Budha') . ' value="Budha" selected>Budha</option>';
                        } else {
                            echo '<option ' . set_select('agama', 'Budha') . ' value="Budha">Budha</option>';
                        }
                        if ($agama == "Konghucu") {
                            echo '<option ' . set_select('agama', 'Konghucu') . ' value="Konghucu" selected>Konghucu</option>';
                        } else {
                            echo '<option ' . set_select('agama', 'Konghucu') . ' value="Konghucu">Konghucu</option>';
                        }
                        ?> 
                    </select>
                    <small style="color: red"><?= form_error('agama'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Golongan Darah <label style="color: red;">*</label> :</label>
                <div class="col-sm-1">
                    <input value="A" type="radio" <?= set_radio('golongan_darah', 'A') ?> <?= $data_mahasiswa->golongan_darah == "A" ? "checked" : "" ?> id="gd" name="golongan_darah"> A
                </div>
                <div class="col-sm-1">
                    <input value="B" type="radio" <?= set_radio('golongan_darah', 'B') ?>  <?= $data_mahasiswa->golongan_darah == "B" ? "checked" : "" ?> id="gd" name="golongan_darah"> B
                </div>
                <div class="col-sm-1">
                    <input value="AB" type="radio" <?= set_radio('golongan_darah', 'AB') ?> <?= $data_mahasiswa->golongan_darah == "AB" ? "checked" : "" ?> id="gd" name="golongan_darah"> AB
                </div>
                <div class="col-sm-1">
                    <input  value="O" type="radio" <?= set_radio('golongan_darah', 'O') ?> <?= $data_mahasiswa->golongan_darah == "O" ? "checked" : "" ?> id="gd" name="golongan_darah"> O
                </div>
                <div class="col-sm-2">
                    <input value="-" type="radio" <?= set_radio('golongan_darah', '-') ?> <?= $data_mahasiswa->golongan_darah == "-" ? "checked" : "" ?> id="gd" name="golongan_darah"> Tidak Diketahui
                </div>
                <div class="col-sm-3">
                    <small style="color: red"><?= form_error('golongan_darah'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Kewarganegaraan <label style="color: red;">*</label> :</label>
                <div class="col-sm-3">
                    <input value="WNI" type="radio" <?= set_radio('kewarganegaraan', 'WNI') ?> <?= $data_mahasiswa->kewarganegaraan == "WNI" ? "checked" : "" ?> id="warga_negara" name="kewarganegaraan"> WNI (Warga Negara Indonesia)
                </div>
                <div class="col-sm-3">
                    <input value="WNA" type="radio" <?= set_radio('kewarganegaraan', 'WNA') ?> <?= $data_mahasiswa->kewarganegaraan == "WNA" ? "checked" : "" ?> id="warga_negara" name="kewarganegaraan"> WNA (Warga Negara Asing)
                </div>
                <div class="col-sm-3">
                    <small style="color: red"><?= form_error('kewarganegaraan'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Nama Instansi/Tempat Kerja  :</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?= set_value('nama_instansi', $data_mahasiswa->nama_instansi) ?>" name="nama_instansi">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Email Kampus :</label>
                <div class="col-sm-4">
                    <input type="email" class="form-control" value="<?= set_value('email', $data_mahasiswa->email) ?>" name="email">
                </div>
            </div>
            <b>II. DATA ORANG TUA</b><hr>
            <div class="form-group">
                <label class="control-label col-sm-3">Nama Ayah <label style="color: red;">*</label> :</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" value="<?= set_value('nama_ayah', $data_mahasiswa->nama_ayah) ?>" name="nama_ayah">
                    <small style="color: red"><?= form_error('nama_ayah'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Agama Ayah <label style="color: red;">*</label> :</label>
                <div class="col-sm-2">
                    <select  name="agama_ayah" class="form-control">
                        <option selected disabled>Pilih Agama</option>
                        <?php
                        $agama_ayah = $data_mahasiswa->agama_ayah;
                        if ($agama_ayah == "Islam") {
                            echo '<option ' . set_select('agama_ayah', 'Islam') . ' value="Islam" selected>Islam</option>';
                        } else {
                            echo '<option ' . set_select('agama_ayah', 'Islam') . ' value="Islam">Islam</option>';
                        }
                        if ($agama_ayah == "Hindu") {
                            echo '<option ' . set_select('agama_ayah', 'Hindu') . ' value="Hindu" selected>Hindu</option>';
                        } else {
                            echo '<option ' . set_select('agama_ayah', 'Hindu') . ' value="Hindu">Hindu</option>';
                        }
                        if ($agama_ayah == "Kristen") {
                            echo '<option ' . set_select('agama_ayah', 'Kristen') . ' value="Kristen" selected>Kristen</option>';
                        } else {
                            echo '<option ' . set_select('agama_ayah', 'Kristen') . ' value="Kristen">Kristen</option>';
                        }
                        if ($agama_ayah == "Katolik") {
                            echo '<option ' . set_select('agama_ayah', 'Katolik') . ' value="Katolik" selected>Katolik</option>';
                        } else {
                            echo '<option ' . set_select('agama_ayah', 'Katolik') . ' value="Katolik">Katolik</option>';
                        }
                        if ($agama_ayah == "Budha") {
                            echo '<option ' . set_select('agama_ayah', 'Budha') . ' value="Budha" selected>Budha</option>';
                        } else {
                            echo '<option ' . set_select('agama_ayah', 'Budha') . ' value="Budha">Budha</option>';
                        }
                        if ($agama_ayah == "Konghucu") {
                            echo '<option ' . set_select('agama_ayah', 'Konghucu') . ' value="Konghucu" selected>Konghucu</option>';
                        } else {
                            echo '<option ' . set_select('agama_ayah', 'Konghucu') . ' value="Konghucu">Konghucu</option>';
                        }
                        ?>
                    </select>
                    <small style="color: red"><?= form_error('agama_ayah'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Pekerjaan Ayah <label style="color: red;">*</label> :</label>
                <div class="col-sm-3">
                    <select name="pekerjaan_ayah" class="form-control">
                        <option value="" selected disabled>Pilih Pekerjaan</option>
                        <?php
                        $pekerjaan_ayah = $data_mahasiswa->pekerjaan_ayah;
                        if ($pekerjaan_ayah == "Pegawai Negeri Sipil") {
                            echo '<option ' . set_select('pekerjaan_ayah', 'Pegawai Negeri Sipil') . '  value="Pegawai Negeri Sipil" selected>Pegawai Negeri Sipil (PNS)</option>';
                        } else {
                            echo '<option ' . set_select('pekerjaan_ayah', 'Pegawai Negeri Sipil') . ' value="Pegawai Negeri Sipil">Pegawai Negeri Sipil (PNS)</option>';
                        }
                        if ($pekerjaan_ayah == "Pegawai Swasta") {
                            echo '<option ' . set_select('pekerjaan_ayah', 'Pegawai Swasta') . ' value="Pegawai Swasta" selected>Pegawai Swasta</option>';
                        } else {
                            echo '<option ' . set_select('pekerjaan_ayah', 'Pegawai Swasta') . ' value="Pegawai Swasta">Hindu</option>';
                        }
                        if ($pekerjaan_ayah == "Wiraswasta") {
                            echo '<option ' . set_select('pekerjaan_ayah', 'Wiraswasta') . ' value="Wiraswasta" selected>Wiraswasta</option>';
                        } else {
                            echo '<option ' . set_select('pekerjaan_ayah', 'Wiraswasta') . ' value="Wiraswasta">Wiraswasta</option>';
                        }
                        if ($pekerjaan_ayah == "TNI/Polri") {
                            echo '<option ' . set_select('pekerjaan_ayah', 'TNI/Polri') . ' value="TNI/Polri" selected>TNI/Polri</option>';
                        } else {
                            echo '<option ' . set_select('pekerjaan_ayah', 'TNI/Polri') . ' value="TNI/Polri">TNI/Polri</option>';
                        }
                        if ($pekerjaan_ayah == "Dosen") {
                            echo '<option ' . set_select('pekerjaan_ayah', 'Dosen') . ' value="Dosen" selected>Dosen</option>';
                        } else {
                            echo '<option ' . set_select('pekerjaan_ayah', 'Dosen') . ' value="Dosen">Dosen</option>';
                        }
                        if ($pekerjaan_ayah == "Guru") {
                            echo '<option ' . set_select('pekerjaan_ayah', 'Guru') . ' value="Guru" selected>Guru</option>';
                        } else {
                            echo '<option ' . set_select('pekerjaan_ayah', 'Guru') . ' value="Guru">Guru</option>';
                        }
                        if ($pekerjaan_ayah == "Petani") {
                            echo '<option ' . set_select('pekerjaan_ayah', 'Petani') . ' value="Petani" selected>Petani</option>';
                        } else {
                            echo '<option ' . set_select('pekerjaan_ayah', 'Petani') . ' value="Petani">Petani</option>';
                        }
                        if ($pekerjaan_ayah == "Rumah Tangga") {
                            echo '<option ' . set_select('pekerjaan_ayah', 'Rumah Tangga') . ' value="Rumah Tangga" selected>Rumah Tangga</option>';
                        } else {
                            echo '<option ' . set_select('pekerjaan_ayah', 'Rumah Tangga') . ' value="Rumah Tangga">Rumah Tangga</option>';
                        }
                        if ($pekerjaan_ayah == "Lainnya") {
                            echo '<option ' . set_select('pekerjaan_ayah', 'Lainnya') . ' value="Lainnya" selected>Lainnya</option>';
                        } else {
                            echo '<option ' . set_select('pekerjaan_ayah', 'Lainnya') . ' value="Lainnya">Lainnya</option>';
                        }
                        ?> 
                    </select>
                    <small style="color: red"><?= form_error('pekerjaan_ayah'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Nama Ibu <label style="color: red;">*</label> :</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" value="<?= set_value('nama_ibu', $data_mahasiswa->nama_ibu) ?>" name="nama_ibu">
                    <small style="color: red"><?= form_error('nama_ibu'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Agama Ibu <label style="color: red;">*</label> :</label>
                <div class="col-sm-2">
                    <select name="agama_ibu" class="form-control">
                        <option value="" selected disabled>Pilih Agama</option>
                        <?php
                        $agama_ibu = $data_mahasiswa->agama_ibu;
                        if ($agama_ibu == "Islam") {
                            echo '<option ' . set_select('agama_ibu', 'Islam') . ' value="Islam" selected>Islam</option>';
                        } else {
                            echo '<option ' . set_select('agama_ibu', 'Islam') . ' value="Islam">Islam</option>';
                        }
                        if ($agama_ibu == "Hindu") {
                            echo '<option ' . set_select('agama_ibu', 'Hindu') . ' value="Hindu" selected>Hindu</option>';
                        } else {
                            echo '<option ' . set_select('agama_ibu', 'Hindu') . ' value="Hindu">Hindu</option>';
                        }
                        if ($agama_ibu == "Kristen") {
                            echo '<option ' . set_select('agama_ibu', 'Kristen') . ' value="Kristen" selected>Kristen</option>';
                        } else {
                            echo '<option ' . set_select('agama_ibu', 'Kristen') . ' value="Kristen">Kristen</option>';
                        }
                        if ($agama_ibu == "Katolik") {
                            echo '<option ' . set_select('agama_ibu', 'Katolik') . ' value="Katolik" selected>Katolik</option>';
                        } else {
                            echo '<option ' . set_select('agama_ibu', 'Katolik') . ' value="Katolik">Katolik</option>';
                        }
                        if ($agama_ibu == "Budha") {
                            echo '<option ' . set_select('agama_ibu', 'Budha') . ' value="Budha" selected>Budha</option>';
                        } else {
                            echo '<option ' . set_select('agama_ibu', 'Budha') . ' value="Budha">Budha</option>';
                        }
                        if ($agama_ibu == "Konghucu") {
                            echo '<option ' . set_select('agama_ibu', 'Konghucu') . ' value="Konghucu" selected>Konghucu</option>';
                        } else {
                            echo '<option ' . set_select('agama_ibu', 'Konghucu') . ' value="Konghucu">Konghucu</option>';
                        }
                        ?>
                    </select>
                    <small style="color: red"><?= form_error('agama_ibu'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Pekerjaan Ibu <label style="color: red;">*</label> :</label>
                <div class="col-sm-3">
                    <select id="pekerjaan_ibu" name="pekerjaan_ibu" class="form-control">
                        <option value="" selected disabled>Pilih Pekerjaan</option>
                        <?php
                        $pekerjaan_ibu = $data_mahasiswa->pekerjaan_ibu;
                        if ($pekerjaan_ibu == "Pegawai Negeri Sipil") {
                            echo '<option ' . set_select('pekerjaan_ibu', 'Pegawai Negeri Sipil') . '  value="Pegawai Negeri Sipil" selected>Pegawai Negeri Sipil (PNS)</option>';
                        } else {
                            echo '<option ' . set_select('pekerjaan_ibu', 'Pegawai Negeri Sipil') . ' value="Pegawai Negeri Sipil">Pegawai Negeri Sipil (PNS)</option>';
                        }
                        if ($pekerjaan_ibu == "Pegawai Swasta") {
                            echo '<option ' . set_select('pekerjaan_ibu', 'Pegawai Swasta') . ' value="Pegawai Swasta" selected>Pegawai Swasta</option>';
                        } else {
                            echo '<option ' . set_select('pekerjaan_ibu', 'Pegawai Swasta') . ' value="Pegawai Swasta">Hindu</option>';
                        }
                        if ($pekerjaan_ibu == "Wiraswasta") {
                            echo '<option ' . set_select('pekerjaan_ibu', 'Wiraswasta') . ' value="Wiraswasta" selected>Wiraswasta</option>';
                        } else {
                            echo '<option ' . set_select('pekerjaan_ibu', 'Wiraswasta') . ' value="Wiraswasta">Wiraswasta</option>';
                        }
                        if ($pekerjaan_ibu == "TNI/Polri") {
                            echo '<option ' . set_select('pekerjaan_ibu', 'TNI/Polri') . ' value="TNI/Polri" selected>TNI/Polri</option>';
                        } else {
                            echo '<option ' . set_select('pekerjaan_ibu', 'TNI/Polri') . ' value="TNI/Polri">TNI/Polri</option>';
                        }
                        if ($pekerjaan_ibu == "Dosen") {
                            echo '<option ' . set_select('pekerjaan_ibu', 'Dosen') . ' value="Dosen" selected>Dosen</option>';
                        } else {
                            echo '<option ' . set_select('pekerjaan_ibu', 'Dosen') . ' value="Dosen">Dosen</option>';
                        }
                        if ($pekerjaan_ibu == "Guru") {
                            echo '<option ' . set_select('pekerjaan_ibu', 'Guru') . ' value="Guru" selected>Guru</option>';
                        } else {
                            echo '<option ' . set_select('pekerjaan_ibu', 'Guru') . ' value="Guru">Guru</option>';
                        }
                        if ($pekerjaan_ibu == "Petani") {
                            echo '<option ' . set_select('pekerjaan_ibu', 'Petani') . ' value="Petani" selected>Petani</option>';
                        } else {
                            echo '<option ' . set_select('pekerjaan_ibu', 'Petani') . ' value="Petani">Petani</option>';
                        }
                        if ($pekerjaan_ibu == "Rumah Tangga") {
                            echo '<option ' . set_select('pekerjaan_ibu', 'Rumah Tangga') . ' value="Rumah Tangga" selected>Rumah Tangga</option>';
                        } else {
                            echo '<option ' . set_select('pekerjaan_ibu', 'Rumah Tangga') . ' value="Rumah Tangga">Rumah Tangga</option>';
                        }
                        if ($pekerjaan_ibu == "Lainnya") {
                            echo '<option ' . set_select('pekerjaan_ibu', 'Lainnya') . ' value="Lainnya" selected>Lainnya</option>';
                        } else {
                            echo '<option ' . set_select('pekerjaan_ibu', 'Lainnya') . ' value="Lainnya">Lainnya</option>';
                        }
                        ?> 
                    </select>
                    <small style="color: red"><?= form_error('pekerjaan_ibu'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Alamat Orang Tua <label style="color: red;">*</label> :</label>
                <div class="col-sm-4">
                    <textarea name="alamat_orangtua" class="form-control" cols="20" rows="5"><?= set_value('alamat_orangtua', $data_mahasiswa->alamat_orangtua) ?></textarea>
                    <small style="color: red"><?= form_error('alamat_orangtua'); ?></small>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Kota dan Propinsi <label style="color: red;">*</label> :</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" value="<?= set_value('kota_orangtua', $data_mahasiswa->kota_orangtua) ?>" name="kota_orangtua">
                    <small style="color: red"><?= form_error('kota_orangtua'); ?></small>
                </div>
                <div class="col-sm-3">
                    <select class="form-control " name="propinsi_orangtua">
                        <option value="" disabled selected>Pilih Propinsi</option>
                        <?php foreach ($provinsi as $row) { ?>
                            <option <?= set_select('propinsi_orangtua', $row->nama) ?> <?= $data_mahasiswa->propinsi_orangtua == $row->nama ? "selected" : "" ?> value="<?= e($row->nama) ?>"><?= e($row->nama) ?></option>
                        <?php } ?>
                    </select>
                    <small style="color: red"><?= form_error('propinsi_orangtua'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Nomor Telepon Orang Tua :</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" value="<?= set_value('telepon_orangtua', $data_mahasiswa->telepon_orangtua) ?>" placeholder="No. Telpon Orang Tua" name="telepon_orangtua">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-3">
                    <a href="<?= site_url('mahasiswa/profil'); ?>" class="btn btn-default flat"><i class="fa fa-remove"></i> Batal</a>
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-check-circle"></i> Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
