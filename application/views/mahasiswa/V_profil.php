<?= $this->session->flashdata('info') ?>
<div class="box box-solid flat">
    <div class="box-body">
        <strong>Dosen Wali :</strong> <span class="badge bg-navy"><?= e($dosen_wali) ?></span>&nbsp;
        <?php if (isset($dosen_perwakilan)) : ?>
            <strong>Dosen Perwakilan :</strong> <span class="badge bg-orange"><?= e($dosen_perwakilan) ?></span>
        <?php endif; ?>
        <div class="pull-right">
            <a href="<?= site_url('mahasiswa/profil/ubah_data_mahasiswa/' . $data->nim); ?>"
               class="btn btn-sm btn-primary flat"><i class="fa fa-edit"></i> Ubah Profil</a>
        </div>
    </div>
</div>
<?php
if(empty($data->foto) || $data->foto == 'P.png' || $data->foto == 'L.png'){
    $foto = false;
}else{
    $foto = true;
}
?>
<div class="box box-warning box-solid animated shake -repeat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="icon fa fa-info"></i> Perhatian!</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <b>Mulai Semester Genap 2020/2021 Mahasiswa diwajibkan memiliki foto Profile pada aplikasi SISKA. </b><br>
        Mohon MEMBACA dan MENGIKUTI aturan foto profile yang terdapat pada halaman upload agar memudahkan proses
        validasi. <br>
        Silahkan melakukan Upload foto sampai muncul:
        <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalUploadFoto">
            <i class="fa fa-upload"></i> Upload Foto
        </button>
    </div>
    <!-- /.box-body -->
</div>

<div class="modal fade" id="modalUploadFoto" tabindex="-1" role="dialog" aria-labelledby="modalUploadFotoLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formUploadFoto" action="<?= site_url('mahasiswa/profil/upload_foto') ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><i class="fa fa-upload"></i> Upload Foto Profile</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Foto (format: jpg, png, jpeg, maks 2MB):</label>
                        <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                        <small class="text-danger">Pastikan foto berukuran 3x4 dengan background merah.</small>
                    </div>
                    <div id="uploadMsg" style="display:none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-cloud-upload"></i> Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="panel box box-solid flat">
    <div class="alert  alert-dismissible flat"
         style="height: 30px; padding-top: 4px; font-size: 15px; background-color: #3c8dbc; color: white;">
        <b>Detail Data Mahasiswa</b>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="tabel" width="100%">
                <tr style="height: 30px;">
                    <td width="20%"><strong>NIM</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td><?= e($data->nim) ?></td>
                    <td rowspan="8">
                        <div style="width: 170px; height: 220px; margin: auto;">
                            <?php
                            $foto = $data->foto;
                            $jk = $data->jenis_kelamin;
                            if (empty($foto)) {
                                if ($jk == "L") {
                                    ?>
                                    <img style="width: 170px; height: 220px; border: #899bc1 solid 2px; margin: auto;"
                                         src="<?= base_url('assets/foto/L.png'); ?>" alt="Foto">
                                <?php } else { ?>
                                    <img style="width: 170px; height: 220px; border:#899bc1  solid 2px; margin: auto;"
                                         src="<?= base_url('assets/foto/P.png'); ?>" alt="Foto">
                                <?php } ?>
                            <?php } else { ?>
                                <img style="width: 170px; height: 220px; border:#899bc1  solid 2px; margin: auto;"
                                     src="<?= base_url('assets/foto/' . $data->foto); ?>" alt="Foto">
                            <?php } ?>

                        </div>
                    </td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>NISN</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->nisn) ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>NIK</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->nik) ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Nama Mahasiswa</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->nama_mahasiswa) ?></td>
                </tr>

                <tr style="height: 30px;">
                    <td><strong>Tempat/Tgl. lahir</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->tempat_lahir) ?>, <?= date('d-m-Y', strtotime($data->tanggal_lahir)) ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Alamat</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->alamat) ?>, <?= e($data->kota) ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Propinsi</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->propinsi) ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Jenis Kelamin</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <?php
                        $jenis_kelamin = $data->jenis_kelamin;
                        if ($jenis_kelamin == "P") {
                            echo "Perempuan";
                        } else if ($jenis_kelamin == "L") {
                            echo "Laki-laki";
                        } else {
                            echo "";
                        }
                        ?>
                    </td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Agama</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->agama) ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Kewarganegaraan</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->kewarganegaraan) ?></td>
                    <td align="center">
                        <?php
                        if (empty($foto) or ($foto == "P.png") or ($foto == "L.png")) {
                            echo "<b> Belum ada foto</b>";
                        } else {
                            echo "<b>" . e($data->nama_mahasiswa) . "</b>";
                        }
                        ?>
                        <br>
                        <!--<a href="https://docs.google.com/forms/d/e/1FAIpQLScvFOuVv9p-WYWD-1rk7ccbc6K-rnCh8YIcRxmLjE0VVSdJ9A/viewform" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit Foto</a> -->
                    </td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>No. Telepon</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->telepon) ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Nama Instansi/Tempat Kerja</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->nama_instansi) ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Email Kampus</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->email) ?></td>
                </tr>

            </table>
        </div>
    </div>
</div>
<div class="panel box box-solid flat">
    <div class="alert alert-dismissible flat"
         style="height: 30px; padding-top: 4px; font-size: 15px; background-color: #3c8dbc; color: white;">
        <b>Detail Orang Tua</b>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="tabel" width="100%">
                <tr style="height: 30px;">
                    <td width="20%"><strong>Nama Ayah</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td><?= strtoupper(e($data->nama_ayah)) ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Agama Ayah</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->agama_ayah) ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Pekerjaan Ayah</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->pekerjaan_ayah) ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Nama Ibu</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= strtoupper(e($data->nama_ibu)) ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Agama Ibu</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->agama_ibu) ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Pekerjaan Ibu</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->pekerjaan_ibu) ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Alamat Orang Tua</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->alamat_orangtua) ?>, <?= e($data->kota_orangtua) ?> </td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Propinsi Orang Tua</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->propinsi_orangtua) ?> </td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>No. Telepon Orang Tua</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->telepon_orangtua) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
</div>
