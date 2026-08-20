<?= $this->session->flashdata('info') ?>
<?php
$sudah_foto = !empty($data->foto) && $data->foto !== 'P.png' && $data->foto !== 'L.png';
?>
<div class="box box-solid flat box-info-dosen">
    <div class="box-body">
        <div class="row" style="margin:0;">
            <div class="col-md-7 col-sm-7">
                <div class="info-dosen-item">
                    <span class="info-dosen-label">Dosen Wali</span>
                    <span class="badge bg-navy info-dosen-value"><?= e($dosen_wali) ?></span>
                </div>
                <?php if (!empty($dosen_perwakilan)) : ?>
                    <div class="info-dosen-item">
                        <span class="info-dosen-label">Dosen Perwakilan</span>
                        <span class="badge bg-orange info-dosen-value">
                            <i class="fa fa-phone"></i>&nbsp;<?= e($dosen_perwakilan['nama_dosen']) ?>&nbsp;(<?= e($dosen_perwakilan['no_telp']) ?>)
                        </span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-5 col-sm-5 text-right">
                <?php if ($sudah_foto): ?>
                <a href="#" class="btn btn-sm btn-success flat" data-toggle="modal" data-target="#modalUploadFoto" data-ganti="1"><i class="fa fa-upload"></i> Ganti Foto</a>&nbsp;
                <?php endif; ?>
                <a href="<?= site_url('mahasiswa/profil/ubah_data_mahasiswa'); ?>"
                   class="btn btn-sm btn-primary flat"><i class="fa fa-edit"></i> Ubah Profil</a>
            </div>
        </div>
    </div>
</div>
<?php if (!$sudah_foto): ?>
<div class="box box-warning box-solid animated shake -repeat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="icon fa fa-info"></i> Perhatian!</h3>
    </div>
    <div class="box-body">
        <b>Mulai Semester Genap 2020/2021 Mahasiswa diwajibkan memiliki foto Profile pada aplikasi SISKA. </b><br>
        Mohon MEMBACA dan MENGIKUTI aturan foto profile yang terdapat pada halaman upload agar memudahkan proses
        validasi. <br>
        Silahkan melakukan Upload foto sampai muncul:
        <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalUploadFoto">
            <i class="fa fa-upload"></i> Upload Foto
        </button>
    </div>
</div>
<?php endif; ?>

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
<script>
$('#modalUploadFoto').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var ganti = button && button.data('ganti') == 1;
    $(this).find('.modal-title').html('<i class="fa fa-upload"></i> ' + (ganti ? 'Ganti Foto Profile' : 'Upload Foto Profile'));
});
</script>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><b>Detail Data Mahasiswa</b></h3>
    </div>
    <div class="box-body box-detail-mahasiswa">
        <div class="row">
            <div class="col-md-8 col-sm-8">
                <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr><th width="35%">NIM</th><td><?= e($data->nim) ?></td></tr>
                        <tr><th>NISN</th><td><?= e($data->nisn) ?></td></tr>
                        <tr><th>NIK</th><td><?= e($data->nik) ?></td></tr>
                        <tr><th>Nama Mahasiswa</th><td><?= e($data->nama_mahasiswa) ?></td></tr>
                        <tr><th>Tempat/Tgl. Lahir</th><td><?= e($data->tempat_lahir) ?><?= (!empty($data->tanggal_lahir) && strtotime($data->tanggal_lahir)) ? ', ' . date('d-m-Y', strtotime($data->tanggal_lahir)) : '' ?></td></tr>
                        <tr><th>Alamat</th><td><?= e($data->alamat) ?>, <?= e($data->kota) ?></td></tr>
                        <tr><th>Propinsi</th><td><?= e($data->propinsi) ?></td></tr>
                        <tr><th>Jenis Kelamin</th><td><?= $data->jenis_kelamin == 'P' ? 'Perempuan' : ($data->jenis_kelamin == 'L' ? 'Laki-laki' : '') ?></td></tr>
                        <tr><th>Agama</th><td><?= e($data->agama) ?></td></tr>
                        <tr><th>Kewarganegaraan</th><td><?= e($data->kewarganegaraan) ?></td></tr>
                        <tr><th>No. Telepon</th><td><?= e($data->telepon) ?></td></tr>
                        <tr><th>Nama Instansi/Tempat Kerja</th><td><?= e($data->nama_instansi) ?></td></tr>
                        <tr><th>Email Kampus</th><td><?= e($data->email) ?></td></tr>
                    </tbody>
                </table>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="box box-solid box-foto-profil">
                    <div class="box-body">
                        <?php
                        $foto = $data->foto;
                        $jk = $data->jenis_kelamin;
                        if (empty($foto)) {
                            $foto_src = $jk == 'L' ? 'L.png' : 'P.png';
                        } else {
                            $foto_src = $foto;
                        }
                        $foto_file = FCPATH . 'assets/foto/' . $foto_src;
                        $foto_url = base_url('assets/foto/' . $foto_src) . (file_exists($foto_file) ? '?v=' . filemtime($foto_file) : '');
                        ?>
                        <img class="foto-img" style="<?= $sudah_foto ? 'cursor:pointer;' : '' ?>"
                             src="<?= $foto_url ?>" alt="Foto"
                             <?= $sudah_foto ? 'onclick="$(\'#modalUploadFoto\').modal(\'show\');"' : '' ?>>
                        <div class="foto-status">
                            <?php if ($sudah_foto): ?>
                                <b><?= e($data->nama_mahasiswa) ?></b>
                                <br>
                                <a href="#" data-toggle="modal" data-target="#modalUploadFoto" data-ganti="1"
                                   style="font-size:12px;"><i class="fa fa-camera"></i> Ganti Foto</a>
                            <?php else: ?>
                                <b>Belum ada foto</b>
                                <br>
                                <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalUploadFoto">
                                    <i class="fa fa-upload"></i> Upload Foto
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><b>Detail Orang Tua</b></h3>
    </div>
    <div class="box-body box-detail-orangtua">
        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr><th width="35%">Nama Ayah</th><td><?= strtoupper(e($data->nama_ayah)) ?></td></tr>
                <tr><th>Agama Ayah</th><td><?= e($data->agama_ayah) ?></td></tr>
                <tr><th>Pekerjaan Ayah</th><td><?= e($data->pekerjaan_ayah) ?></td></tr>
                <tr><th>Nama Ibu</th><td><?= strtoupper(e($data->nama_ibu)) ?></td></tr>
                <tr><th>Agama Ibu</th><td><?= e($data->agama_ibu) ?></td></tr>
                <tr><th>Pekerjaan Ibu</th><td><?= e($data->pekerjaan_ibu) ?></td></tr>
                <tr><th>Alamat Orang Tua</th><td><?= e($data->alamat_orangtua) ?>, <?= e($data->kota_orangtua) ?></td></tr>
                <tr><th>Propinsi Orang Tua</th><td><?= e($data->propinsi_orangtua) ?></td></tr>
                <tr><th>No. Telepon Orang Tua</th><td><?= e($data->telepon_orangtua) ?></td></tr>
            </tbody>
        </table>
        </div>
    </div>
</div>
</div>
