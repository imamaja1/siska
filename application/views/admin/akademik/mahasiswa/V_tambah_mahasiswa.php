<?= $this->session->flashdata('info'); ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/mahasiswa') ?>" class="btn btn-xs btn-success flat"><i
                    class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="box box-solid flat">

    <div class="box-body"><br>
        <b>I. IDENTITAS CALON MAHASISWA</b> <font color="red"> - Perhatian ! form pengisian yang mengandung tanda
            bintang (*) harus diisi</font>
        <hr>
        <form class="form-horizontal" action="<?= site_url('admin/akademik/mahasiswa/simpan_data_mahasiswa') ?>"
              method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-3">Nomor Induk Mahasiswa <label style="color: red">*</label>
                    :</label>
                <div class="col-sm-3">
                    <input value="<?= set_value('nim') ?>" type="text" placeholder="NIM" class="form-control" name="nim"
                           maxlength="10">
                    <small style="color: red"><?= form_error('nim'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Nomor Induk Kependudukan <label style="color: red">*</label>
                    :</label>
                <div class="col-sm-3">
                    <input value="<?= set_value('nik') ?>" type="text" placeholder="NIK" class="form-control" name="nik"
                           maxlength="16">
                    <small style="color: red"><?= form_error('nik'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Nomor Pokok Mahasiswa :</label>
                <div class="col-sm-3">
                    <input value="<?= set_value('npm') ?>" type="text" placeholder="NPM" class="form-control"
                           name="npm">
                    <small style="color: red"><?= form_error('npm'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Nomor Pendaftaran <label style="color: red">*</label> :</label>
                <div class="col-sm-3">
                    <input value="<?= set_value('no_pendaftaran') ?>" type="text" placeholder="Nomor Pendaftaran"
                           class="form-control" name="no_pendaftaran">
                    <small style="color: red"><?= form_error('no_pendaftaran'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Nomor Pendaftaran Ulang <label style="color: red">*</label>
                    :</label>
                <div class="col-sm-4">
                    <input value="<?= set_value('no_pendaftaran_ulang') ?>" type="text"
                           placeholder="Nomor Pendaftaran Ulang" class="form-control" name="no_pendaftaran_ulang">
                    <small style="color: red"><?= form_error('no_pendaftaran_ulang'); ?></small>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Status Kuliah <label style="color: red">*</label> :</label>
                <div class="col-sm-4">
                    <select name="status" class="form-control select2">
                        <option <?= set_value('status') == 'A' ? 'selected' : '' ?> value="A" selected>Aktif</option>
                        <option <?= set_value('status') == 'N' ? 'selected' : '' ?> value="N">Tidak</option>
                    </select>
                    <small style="color: red"><?= form_error('status'); ?></small>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Status Penfataran <label style="color: red">*</label> :</label>
                <div class="col-sm-4">
                    <select name="status_pendaftaran" class="form-control select2">
                        <option <?= set_value('status_pendaftaran') == 'B' ? 'selected' : '' ?> value="B" selected>
                            Baru
                        </option>
                        <option <?= set_value('status_pendaftaran') == 'T' ? 'selected' : '' ?> value="T">Transfer
                        </option>
                        <option <?= set_value('status_pendaftaran') == 'L' ? 'selected' : '' ?> value="L">Lanjut
                        </option>
                    </select>
                    <small style="color: red"><?= form_error('status_pendaftaran'); ?></small>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Password  <label style="color: red">*</label>:</label>
                <div class="col-sm-3">
                    <input value="<?= set_value('password') ?>" type="text" placeholder="Password" class="form-control"
                           name="password" minlength="8">
                    <small style="color: red"><?= form_error('password'); ?></small>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Program Studi <label style="color: red">*</label> :</label>
                <div class="col-sm-4">
                    <select name="program_studi_kode" class="form-control select2">
                        <option value="" selected disabled>Pilih</option>
                        <?php foreach ($prodi as $row) : ?>
                            <option <?= set_value('program_studi_kode') == $row->kode_program_studi ? 'selected' : '' ?>
                                    value="<?= e($row->kode_program_studi) ?>"><?= e($row->nama_program_studi) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color: red"><?= form_error('kode_program_Studi'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Nama Mahasiswa <label style="color: red">*</label> :</label>
                <div class="col-sm-4">
                    <input value="<?= set_value('nama_mahasiswa') ?>" type="text" placeholder="Nama Mahasiswa"
                           class="form-control" name="nama_mahasiswa" id="nama_mahasiswa">
                    <small style="color: red"><?= form_error('nama_mahasiswa'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Tempat Lahir dan Tanggal Lahir <label style="color: red">*</label>
                    :</label>
                <div class="col-sm-2">
                    <input value="<?= set_value('tempat_lahir') ?>" type="text" placeholder="Tempat Lahir"
                           class="form-control" name="tempat_lahir">
                    <small style="color: red"><?= form_error('tempat_lahir'); ?></small>
                </div>
                <div class="col-sm-3">
                    <input value="<?= set_value('tanggal_lahir') ?>" type="text" placeholder="Tanggal Lahir"
                           id="tanggal" class="form-control" name="tanggal_lahir">
                    <small style="color: red"><?= form_error('tanggal_lahir'); ?></small>
                </div>


            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Alamat Lengkap <label style="color: red">*</label> :</label>
                <div class="col-sm-5">
                    <textarea name="alamat_lengkap" placeholder="Alamat Lengkap" class="form-control" cols="20"
                              rows="5"><?= set_value('alamat_lengkap') ?></textarea>
                    <small style="color: red"><?= form_error('alamat_lengkap'); ?></small>
                </div>

            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Kota dan Propinsi <label style="color: red">*</label> :</label>
                <div class="col-sm-2">
                    <input value="<?= set_value('kota') ?>" type="text" placeholder="Kota" class="form-control"
                           name="kota">
                    <small style="color: red"><?= form_error('kota'); ?></small>
                </div>

                <div class="col-sm-3">
                    <select class="form-control" name="propinsi">
                        <option selected disabled>Pilih Propinsi</option>
                        <?php foreach ($provinsi as $row) { ?>
                            <option <?= set_select('propinsi', $row->nama) ?>
                                    value="<?= e($row->nama) ?>"><?= e($row->nama) ?></option>
                        <?php } ?>
                    </select>
                    <small style="color: red"><?= form_error('propinsi'); ?></small>
                </div>

            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Telepon <label style="color: red">*</label> :</label>
                <div class="col-sm-2">
                    <input value="<?= set_value('telepon') ?>" type="text" placeholder="Nomor Telepon"
                           class="form-control" name="telepon">
                    <small style="color: red"><?= form_error('telepon'); ?></small>
                </div>

            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Jenis Kelamin <label style="color: red">*</label> :</label>
                <div class="col-sm-2">
                    <input <?= set_radio('jenis_kelamin', 'L'); ?> type="radio" value="L" id="jk" name="jenis_kelamin">
                    Laki-Laki
                </div>
                <div class="col-sm-2">
                    <input <?= set_radio('jenis_kelamin', 'P'); ?> type="radio" value="P" id="jk" name="jenis_kelamin">
                    Perempuan
                </div>
                <small style="color: red"><?= form_error('jenis_kelamin'); ?></small>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Agama <label style="color: red">*</label> :</label>
                <div class="col-sm-2">
                    <select id="agama" name="agama" class="form-control">
                        <option selected disabled>Pilih Agama</option>
                        <option <?= set_select('agama', 'Islam') ?> value="Islam">Islam</option>
                        <option <?= set_select('agama', 'Hindu') ?> value="Hindu">Hindu</option>
                        <option <?= set_select('agama', 'Kristen') ?> value="Kristen">Kristen</option>
                        <option <?= set_select('agama', 'Katolik') ?> value="Katolik">Katolik</option>
                        <option <?= set_select('agama', 'Budha') ?> value="Budha">Budha</option>
                        <option <?= set_select('agama', 'Konghucu') ?> value="Konghucu">Konghucu</option>
                    </select>
                    <small style="color: red"><?= form_error('agama'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Golongan Darah <label style="color: red">*</label> :</label>
                <div class="col-sm-1">
                    <input <?= set_radio('golongan_darah', 'A'); ?> value="A" type="radio" class="iradio_minimal-blue"
                                                                    id="gd" name="golongan_darah"> A
                </div>
                <div class="col-sm-1">
                    <input <?= set_radio('golongan_darah', 'B'); ?> value="B" type="radio" id="gd"
                                                                    name="golongan_darah"> B
                </div>
                <div class="col-sm-1">
                    <input <?= set_radio('golongan_darah', 'AB'); ?> value="AB" type="radio" id="gd"
                                                                     name="golongan_darah"> AB
                </div>
                <div class="col-sm-1">
                    <input <?= set_radio('golongan_darah', 'O'); ?> value="O" type="radio" id="gd"
                                                                    name="golongan_darah"> O
                </div>
                <div class="col-sm-2">
                    <input <?= set_radio('golongan_darah', '-'); ?> value="-" type="radio" id="gd"
                                                                    name="golongan_darah"> Tidak Diketahui
                </div>
                <small style="color: red"><?= form_error('golongan_darah'); ?></small>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Kewarganegaraan <label style="color: red">*</label> :</label>
                <div class="col-sm-3">
                    <input <?= set_radio('kewarganegaraan', 'WNI'); ?> value="WNI" type="radio" id="warga_negara"
                                                                       name="kewarganegaraan"> WNI (Warga Negara
                    Indonesia)
                </div>
                <div class="col-sm-3">
                    <input <?= set_radio('kewarganegaraan', 'WNA'); ?> value="WNA" type="radio" id="warga_negara"
                                                                       name="kewarganegaraan"> WNA (Warga Negara Asing)
                </div>
                <small style="color: red"><?= form_error('kewarganegaraan'); ?></small>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Nama Instansi/Tempat Kerja :</label>
                <div class="col-sm-4">
                    <input value="<?= set_value('nama_instansi') ?>" type="text" class="form-control"
                           placeholder="Nama Instansi/Tempat Kerja" name="nama_instansi">
                    <small style="color: red"><?= form_error('nama_instansi'); ?></small>
                </div>
            </div>
            <b>II. DATA ORANG TUA</b>
            <hr>
            <div class="form-group">
                <label class="control-label col-sm-3">Nama Ayah <label style="color: red">*</label> :</label>
                <div class="col-sm-3">
                    <input value="<?= set_value('nama_ayah') ?>" type="text" class="form-control"
                           placeholder="Nama Ayah" name="nama_ayah">
                    <small style="color: red"><?= form_error('nama_ayah'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Agama Ayah <label style="color: red">*</label> :</label>
                <div class="col-sm-2">
                    <select id="agama" name="agama_ayah" class="form-control">
                        <option selected disabled>Pilih Agama</option>
                        <option <?= set_select('agama_ayah', 'Islam') ?> value="Islam">Islam</option>
                        <option <?= set_select('agama_ayah', 'Hindu') ?> value="Hindu">Hindu</option>
                        <option <?= set_select('agama_ayah', 'Kristen') ?> value="Kristen">Kristen</option>
                        <option <?= set_select('agama_ayah', 'Katolik') ?> value="Katolik">Katolik</option>
                        <option <?= set_select('agama_ayah', 'Budha') ?> value="Budha">Budha</option>
                        <option <?= set_select('agama_ayah', 'Konghucu') ?> value="Konghucu">Konghucu</option>
                    </select>
                    <small style="color: red"><?= form_error('agama_ayah'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Pekerjaan Ayah <label style="color: red">*</label> :</label>
                <div class="col-sm-3">
                    <select id="agama" name="pekerjaan_ayah" class="form-control">
                        <option selected disabled>Pilih Pekerjaan</option>
                        <option <?= set_select('pekerjaan_ayah', 'Pegawai Negeri Sipil') ?>value="Pegawai Negeri Sipil">
                            Pegawai Negeri Sipil (PNS)
                        </option>
                        <option <?= set_select('pekerjaan_ayah', 'Pegawai Swasta') ?> value="Pegawai Swasta">Pegawai
                            Swasta
                        </option>
                        <option <?= set_select('pekerjaan_ayah', 'Wiraswasta') ?> value="Wiraswasta">Wiraswasta</option>
                        <option <?= set_select('pekerjaan_ayah', 'TNI/Polri') ?> value="TNI/Polri">TNI/Polri</option>
                        <option <?= set_select('pekerjaan_ayah', 'Dosen') ?> value="Dosen">Dosen</option>
                        <option <?= set_select('pekerjaan_ayah', 'Guru') ?> value="Guru">Guru</option>
                        <option <?= set_select('pekerjaan_ayah', 'Petani') ?> value="Petani">Petani</option>
                        <option <?= set_select('pekerjaan_ayah', 'Rumah Tangga') ?> value="Rumah Tangga">Rumah Tangga
                        </option>
                        <option <?= set_select('pekerjaan_ayah', 'Lainnya') ?> value="Lainnya">Lainnya</option>
                    </select>
                    <small style="color: red"><?= form_error('pekerjaan_ayah'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Nama Ibu <label style="color: red">*</label> :</label>
                <div class="col-sm-3">
                    <input value="<?= set_value('nama_ibu') ?>" type="text" class="form-control" placeholder="Nama Ibu"
                           name="nama_ibu">
                    <small style="color: red"><?= form_error('nama_ibu'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Agama Ibu <label style="color: red">*</label> :</label>
                <div class="col-sm-2">
                    <select id="agama" name="agama_ibu" class="form-control">
                        <option selected disabled>Pilih Agama</option>
                        <option <?= set_select('agama_ayah', 'Islam') ?> value="Islam">Islam</option>
                        <option <?= set_select('agama_ayah', 'Hindu') ?> value="Hindu">Hindu</option>
                        <option <?= set_select('agama_ayah', 'Kristen') ?> value="Kristen">Kristen</option>
                        <option <?= set_select('agama_ayah', 'Katolik') ?> value="Katolik">Katolik</option>
                        <option <?= set_select('agama_ayah', 'Budha') ?> value="Budha">Budha</option>
                        <option <?= set_select('agama_ayah', 'Konghucu') ?> value="Konghucu">Konghucu</option>
                    </select>
                    <small style="color: red"><?= form_error('agama_ibu'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Pekerjaan Ibu <label style="color: red">*</label> :</label>
                <div class="col-sm-3">
                    <select id="pekerjaan_ibu" name="pekerjaan_ibu" class="form-control">
                        <option selected disabled>Pilih Pekerjaan</option>
                        <option <?= set_select('pekerjaan_ibu', 'Pegawai Negeri Sipil') ?> value="Pegawai Negeri Sipil">
                            Pegawai Negeri Sipil (PNS)
                        </option>
                        <option <?= set_select('pekerjaan_ibu', 'Pegawai Swasta') ?> value="Pegawai Swasta">Pegawai
                            Swasta
                        </option>
                        <option <?= set_select('pekerjaan_ibu', 'Wiraswasta') ?> value="Wiraswasta">Wiraswasta</option>
                        <option <?= set_select('pekerjaan_ibu', 'TNI/Polri') ?> value="TNI/Polri">TNI/Polri</option>
                        <option <?= set_select('pekerjaan_ibu', 'Dosen') ?> value="Dosen">Dosen</option>
                        <option <?= set_select('pekerjaan_ibu', 'Guru') ?> value="Guru">Guru</option>
                        <option <?= set_select('pekerjaan_ibu', 'Petani') ?> value="Petani">Petani</option>
                        <option <?= set_select('pekerjaan_ibu', 'Rumah Tangga') ?> value="Rumah Tangga">Rumah Tangga
                        </option>
                        <option <?= set_select('pekerjaan_ibu', 'Lainnya') ?> value="Lainnya">Lainnya</option>
                    </select>
                    <small style="color: red"><?= form_error('pekerjaan_ibu'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Alamat Orang Tua <label style="color: red">*</label> :</label>
                <div class="col-sm-5">
                    <textarea name="alamat_orangtua" placeholder="Alamat Orang Tua" class="form-control" cols="20"
                              rows="5"><?= set_value('alamat_orangtua') ?></textarea>
                    <small style="color: red"><?= form_error('alamat_orangtua'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Kota dan Propinsi <label style="color: red">*</label> :</label>
                <div class="col-sm-2">
                    <input value="<?= set_value('kota_orangtua') ?>" type="text" class="form-control" placeholder="Kota"
                           name="kota_orangtua">
                    <small style="color: red"><?= form_error('kota_orangtua'); ?></small>
                </div>
                <div class="col-sm-3">
                    <select class="form-control" name="propinsi_orangtua">
                        <option selected disabled>Pilih Propinsi</option>
                        <?php foreach ($provinsi as $row) { ?>
                            <option <?= set_select('propinsi_orangtua', $row->nama) ?>
                                    value="<?= e($row->nama) ?>"><?= e($row->nama) ?></option>
                        <?php } ?>
                    </select>
                    <small style="color: red"><?= form_error('propinsi_orangtua'); ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Nomor Telepon Orang Tua :</label>
                <div class="col-sm-3">
                    <input value="<?= set_value('telepon_orangtua') ?>" type="text" class="form-control"
                           placeholder="No. Telpon Orang Tua" name="telepon_orangtua">
                </div>
            </div>
            <b>III. FILE FOTO</b>
            <hr>
            <div class="form-group">
                <label class="control-label col-sm-3">Unggah File Foto :</label>
                <div class="col-sm-3">
                    <input type="file" class="form-control" name="file_foto">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-3">
                    <button type="reset" class="btn btn-danger flat"><i class="fa fa-refresh"></i> Reset</button>
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-check-circle"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

