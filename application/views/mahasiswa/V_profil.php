<?= $this->session->flashdata('info') ?>
<?php
$foto_file = FCPATH . 'assets/foto/' . $data->foto;
$sudah_foto = !empty($data->foto) && $data->foto !== 'P.png' && $data->foto !== 'L.png' && is_file($foto_file);

$prodi = get_kode_prodi($data->nim);
$prodi_nama = ($prodi && !empty($prodi->nama_program_studi)) ? $prodi->nama_program_studi : '-';

$ta = tahun_akademik();
$ta_label = ($ta && !empty($ta->tahun_akademik)) ? $ta->tahun_akademik : '';
$semester_label = ($ta && isset($ta->semester)) ? ((int) $ta->semester % 2 === 0 ? 'Genap' : 'Ganjil') : '';

$status_aktif = ($data->status === 'A');
$status_label = $status_aktif ? 'Aktif Kuliah' : 'Tidak Aktif';

$jk_label = $data->jenis_kelamin == 'P' ? 'Perempuan' : ($data->jenis_kelamin == 'L' ? 'Laki-laki' : '-');
$ttl = (!empty($data->tempat_lahir) ? e($data->tempat_lahir) : '-');
if (!empty($data->tanggal_lahir) && strtotime($data->tanggal_lahir)) {
    $ttl .= ', ' . date('d-m-Y', strtotime($data->tanggal_lahir));
}

if ($sudah_foto) {
    $foto_url = base_url('assets/foto/' . $data->foto) . '?v=' . filemtime($foto_file);
} else {
    // Tidak ada foto (atau file hilang) -> pakai vektor user
    $foto_url = base_url('assets/siska/img/avatar-placeholder.svg');
}

$val = function ($v) {
    return (trim((string) $v) !== '') ? e($v) : '-';
};
?>
<div class="mhs-profile">

    <!-- Page Context Banner -->
    <div class="pf-banner">
        <div class="pf-banner-left">
            <span class="pf-banner-icon"><i class="fa fa-user"></i></span>
            <h1>Profil Mahasiswa</h1>
        </div>
        <span class="pf-ta-pill">
            <span class="pf-dot"></span>
            <?php if ($ta_label !== ''): ?>
                Tahun Ajaran <?= e($ta_label) ?> <?= e($semester_label) ?>
            <?php else: ?>
                Tahun Ajaran Aktif
            <?php endif; ?>
        </span>
    </div>

    <!-- Academic Advisor & Action Toolbar -->
    <div class="pf-toolbar">
        <div class="pf-advisor">
            <span class="pf-advisor-label"><i class="fa fa-graduation-cap"></i> Dosen Wali (Pembimbing Akademik):</span>
            <span class="pf-chip"><?= e($dosen_wali) ?></span>
            <?php if (!empty($dosen_perwakilan)): ?>
                <span class="pf-chip is-alt" title="Dosen Perwakilan">
                    <i class="fa fa-phone"></i> <?= e($dosen_perwakilan['nama_dosen']) ?> (<?= e($dosen_perwakilan['no_telp']) ?>)
                </span>
            <?php endif; ?>
        </div>
        <div class="pf-actions">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalUploadFoto" <?= $sudah_foto ? 'data-ganti="1"' : '' ?>>
                <i class="fa fa-camera"></i> <?= $sudah_foto ? 'Ganti Foto' : 'Upload Foto' ?>
            </button>
            <a href="<?= site_url('mahasiswa/profil/ubah_data_mahasiswa'); ?>" class="btn btn-primary">
                <i class="fa fa-pencil-square-o"></i> Ubah Profil
            </a>
        </div>
    </div>

    <!-- Main Bento Card: Detail Data Mahasiswa -->
    <div class="pf-card">
        <div class="pf-card-head">
            <h2><i class="fa fa-id-card-o"></i> Detail Data Mahasiswa</h2>
            <div class="pf-tags">
                <span class="pf-status <?= $status_aktif ? 'is-active' : 'is-inactive' ?>">
                    <i class="fa <?= $status_aktif ? 'fa-check-circle' : 'fa-times-circle' ?>"></i> <?= e($status_label) ?>
                </span>
            </div>
        </div>
        <div class="pf-card-body">
            <div class="row">
                <div class="col-md-8 col-sm-7">
                    <div class="pf-rows">
                        <div class="pf-row">
                            <span class="pf-row-label">NIM</span>
                            <div class="pf-row-inline">
                                <span class="pf-row-value" style="color:#1C6DD0;font-weight:700;letter-spacing:0.03em;"><?= e($data->nim) ?></span>
                                <button type="button" class="pf-copy" title="Salin NIM" onclick="siskaCopy('<?= e($data->nim) ?>', this)"><i class="fa fa-copy"></i></button>
                            </div>
                        </div>
                        <div class="pf-row">
                            <span class="pf-row-label">NISN</span>
                            <span class="pf-row-value"><?= $val($data->nisn) ?></span>
                        </div>
                        <div class="pf-row">
                            <span class="pf-row-label">NIK</span>
                            <span class="pf-row-value"><?= $val($data->nik) ?></span>
                        </div>
                        <div class="pf-row">
                            <span class="pf-row-label">Nama Mahasiswa</span>
                            <span class="pf-row-value"><?= e($data->nama_mahasiswa) ?></span>
                        </div>
                        <div class="pf-row">
                            <span class="pf-row-label">Tempat/Tgl. Lahir</span>
                            <span class="pf-row-value"><?= $ttl ?></span>
                        </div>
                        <div class="pf-row">
                            <span class="pf-row-label">Alamat</span>
                            <span class="pf-row-value"><?= $val($data->alamat) ?>, <?= $val($data->kota) ?></span>
                        </div>
                        <div class="pf-row">
                            <span class="pf-row-label">Propinsi</span>
                            <span class="pf-row-value"><?= $val($data->propinsi) ?></span>
                        </div>
                        <div class="pf-row">
                            <span class="pf-row-label">Jenis Kelamin</span>
                            <span class="pf-row-value"><?= e($jk_label) ?></span>
                        </div>
                        <div class="pf-row">
                            <span class="pf-row-label">Agama</span>
                            <span class="pf-row-value"><?= $val($data->agama) ?></span>
                        </div>
                        <div class="pf-row">
                            <span class="pf-row-label">Kewarganegaraan</span>
                            <span class="pf-row-value"><?= $val($data->kewarganegaraan) ?></span>
                        </div>
                        <div class="pf-row">
                            <span class="pf-row-label">No. Telepon</span>
                            <span class="pf-row-value"><?= $val($data->telepon) ?></span>
                        </div>
                        <div class="pf-row">
                            <span class="pf-row-label">Email Kampus</span>
                            <span class="pf-row-value"><?= $val($data->email) ?></span>
                        </div>
                        <div class="pf-row">
                            <span class="pf-row-label">Program Studi</span>
                            <span class="pf-row-value" style="font-weight:700;"><?= e($prodi_nama) ?></span>
                        </div>
                        <div class="pf-row">
                            <span class="pf-row-label">Nama Instansi/Tempat Kerja</span>
                            <span class="pf-row-value"><?= $val($data->nama_instansi) ?></span>
                        </div>
                        <div class="pf-row">
                            <span class="pf-row-label">Status Akademik</span>
                            <span class="pf-row-value">
                                <span class="pf-status <?= $status_aktif ? 'is-active' : 'is-inactive' ?>" style="text-transform:none;letter-spacing:0;">
                                    <i class="fa <?= $status_aktif ? 'fa-check-circle' : 'fa-times-circle' ?>"></i> <?= e($status_label) ?>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Formal Portrait Card -->
                <div class="col-md-4 col-sm-5">
                    <div class="pf-photo">
                        <span class="pf-photo-label">Foto Formal Mahasiswa</span>
                        <div class="pf-photo-frame <?= $sudah_foto ? '' : 'is-empty' ?>" data-toggle="modal" data-target="#modalUploadFoto" <?= $sudah_foto ? 'data-ganti="1"' : '' ?>>
                            <img src="<?= $foto_url ?>" alt="Foto <?= e($data->nama_mahasiswa) ?>">
                            <div class="pf-photo-overlay"><span><i class="fa fa-camera"></i> <?= $sudah_foto ? 'Ganti Foto' : 'Upload Foto' ?></span></div>
                            <div class="pf-photo-badge">Pasfoto BAAK 3x4</div>
                        </div>
                        <h3 class="pf-photo-name"><?= e($data->nama_mahasiswa) ?></h3>
                        <p class="pf-photo-nim">NIM: <?= e($data->nim) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Orang Tua -->
    <div class="pf-card">
        <div class="pf-card-head">
            <h2><i class="fa fa-users"></i> Detail Data Orang Tua</h2>
        </div>
        <div class="pf-card-body">
            <div class="pf-rows">
                <div class="pf-row">
                    <span class="pf-row-label">Nama Ayah</span>
                    <span class="pf-row-value"><?= strtoupper($val($data->nama_ayah)) ?></span>
                </div>
                <div class="pf-row">
                    <span class="pf-row-label">Agama Ayah</span>
                    <span class="pf-row-value"><?= $val($data->agama_ayah) ?></span>
                </div>
                <div class="pf-row">
                    <span class="pf-row-label">Pekerjaan Ayah</span>
                    <span class="pf-row-value"><?= $val($data->pekerjaan_ayah) ?></span>
                </div>
                <div class="pf-row">
                    <span class="pf-row-label">Nama Ibu</span>
                    <span class="pf-row-value"><?= strtoupper($val($data->nama_ibu)) ?></span>
                </div>
                <div class="pf-row">
                    <span class="pf-row-label">Agama Ibu</span>
                    <span class="pf-row-value"><?= $val($data->agama_ibu) ?></span>
                </div>
                <div class="pf-row">
                    <span class="pf-row-label">Pekerjaan Ibu</span>
                    <span class="pf-row-value"><?= $val($data->pekerjaan_ibu) ?></span>
                </div>
                <div class="pf-row">
                    <span class="pf-row-label">Alamat Orang Tua</span>
                    <span class="pf-row-value"><?= $val($data->alamat_orangtua) ?><?= !empty($data->kota_orangtua) ? ', ' . e($data->kota_orangtua) : '' ?></span>
                </div>
                <div class="pf-row">
                    <span class="pf-row-label">Propinsi Orang Tua</span>
                    <span class="pf-row-value"><?= $val($data->propinsi_orangtua) ?></span>
                </div>
                <div class="pf-row">
                    <span class="pf-row-label">No. Telepon Orang Tua</span>
                    <span class="pf-row-value"><?= $val($data->telepon_orangtua) ?></span>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal Upload Foto -->
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
                        <small class="text-danger" style="display:block; margin-top:4px;">Pastikan foto berukuran 3x4 dengan background merah.</small>
                    </div>
                    <div id="uploadMsg" style="display:none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-cloud-upload"></i> Upload</button>
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

    function siskaCopy(text, btn) {
        function done() {
            if (!btn) return;
            var old = btn.innerHTML;
            btn.innerHTML = '<i class="fa fa-check"></i>';
            setTimeout(function () { btn.innerHTML = old; }, 1200);
        }
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(done, done);
        } else {
            var ta = document.createElement('textarea');
            ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
            document.body.appendChild(ta); ta.select();
            try { document.execCommand('copy'); } catch (e) {}
            document.body.removeChild(ta); done();
        }
    }
</script>
