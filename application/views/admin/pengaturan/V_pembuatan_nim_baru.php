<style>
@media (max-width: 767px) {
    .btn-mobile-block {
        display: block !important;
        width: 100% !important;
        margin-left: 0 !important;
        margin-bottom: 10px !important;
        text-align: center;
    }
    .form-mobile-gap {
        margin-bottom: 10px !important;
    }
    .control-label {
        margin-bottom: 5px !important;
        text-align: left !important;
    }
    #nim_preview {
        font-size: 15px !important;
    }
    .box-header h4 {
        font-size: 16px;
    }
}
</style>

<?= $this->session->flashdata('pesan'); ?>

<!-- Section 1: Konfigurasi Prodi & Angkatan -->
<div class="box box-primary flat">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-user-plus" style="color: #3c8dbc;"></i><strong> Pembuatan NIM Mahasiswa Baru</strong></h4>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-5 col-sm-12 form-mobile-gap">
                <div class="form-group" style="margin-bottom: 0;">
                    <label>Program Studi <span class="text-danger">*</span></label>
                    <select id="select_prodi" class="form-control select2" style="width: 100%;">
                        <option value="" disabled selected>-- Pilih Program Studi --</option>
                        <?php foreach ($prodi as $p): ?>
                            <option value="<?= e($p->kode_program_studi) ?>"><?= e($p->nama_program_studi) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-sm-12 form-mobile-gap">
                <div class="form-group" style="margin-bottom: 0;">
                    <label>Tahun Angkatan <span class="text-danger">*</span></label>
                    <select id="select_angkatan" class="form-control select2" style="width: 100%;">
                        <?php foreach ($angkatan_list as $a): ?>
                            <option value="<?= e($a['val']) ?>" <?= ($a['val'] == $default_angkatan) ? 'selected' : '' ?>>
                                <?= e($a['label']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="form-group" style="margin-bottom: 0;">
                    <label>Generated NIM Baru:</label>
                    <div class="input-group">
                        <input type="text" id="nim_preview" class="form-control input-lg text-bold" style="background: #eef9fe; color: #0073b7; font-size: 18px; letter-spacing: 1px;" readonly placeholder="Pilih prodi...">
                        <span class="input-group-btn">
                            <button id="btn_refresh_nim" class="btn btn-info btn-lg flat" type="button" title="Refresh NIM"><i class="fa fa-refresh"></i></button>
                        </span>
                    </div>
                    <small id="nim_status_help" class="text-muted">NIM otomatis bertambah +1 dari nomor urut terakhir pada prodi & angkatan yang dipilih.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 2: Form Input Data Mahasiswa Baru Murni -->
<div class="box box-solid flat" id="panel_form_mahasiswa" style="display: none;">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-edit" style="color: #00a65a;"></i><strong> Formulir Mahasiswa Baru Manual</strong></h4>
    </div>
    <div class="box-body">
        <form id="form_mahasiswa" class="form-horizontal" action="<?= site_url('admin/pengaturan/pembuatan_nim_baru/simpan') ?>" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <input type="hidden" name="program_studi_kode" id="form_prodi_kode" value="">
            <input type="hidden" name="is_transfer" value="0">

            <div class="alert alert-info flat" style="padding: 10px 15px; margin-bottom: 20px;">
                <i class="fa fa-info-circle"></i> Tanda bintang (<span class="text-danger">*</span>) menandakan kolom wajib diisi.
            </div>

            <h4 style="border-bottom: 2px solid #3c8dbc; padding-bottom: 5px; color: #3c8dbc; margin-top: 10px;">
                I. IDENTITAS CALON MAHASISWA
            </h4>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Nomor Induk Mahasiswa (NIM) <span class="text-danger">*</span> :</label>
                <div class="col-sm-4 col-xs-12">
                    <input type="text" id="form_nim" name="nim" class="form-control input-lg text-bold" style="background: #fdfae7; color: #d73925; font-size: 17px;" readonly required>
                    <small class="text-muted">NIM baru otomatis dibuat oleh sistem</small>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Program Studi Terpilih :</label>
                <div class="col-sm-5 col-xs-12">
                    <input type="text" id="form_prodi_nama" class="form-control" readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Status Pendaftaran <span class="text-danger">*</span> :</label>
                <div class="col-sm-4 col-xs-12">
                    <select name="status_pendaftaran" id="form_status_pendaftaran" class="form-control select2" style="width: 100%;" required>
                        <option value="B" selected>Baru (B)</option>
                        <option value="L">Lanjut (L)</option>
                    </select>
                    <small class="text-muted">Pilih status pendaftaran sesuai database.</small>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Status Kuliah <span class="text-danger">*</span> :</label>
                <div class="col-sm-3 col-xs-12">
                    <select name="status" class="form-control select2" style="width: 100%;" required>
                        <option value="A" selected>Aktif (A)</option>
                        <option value="N">Tidak Aktif (N)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Nama Mahasiswa <span class="text-danger">*</span> :</label>
                <div class="col-sm-5 col-xs-12">
                    <input type="text" name="nama_mahasiswa" id="form_nama_mahasiswa" class="form-control" placeholder="Nama Lengkap Mahasiswa" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Nomor Induk Kependudukan (NIK) <span class="text-danger">*</span> :</label>
                <div class="col-sm-4 col-xs-12">
                    <input type="text" name="nik" id="form_nik" class="form-control" placeholder="16 Digit NIK KTP/KK" maxlength="16" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Nomor Pokok Mahasiswa (NPM) :</label>
                <div class="col-sm-3 col-xs-12">
                    <input type="text" name="npm" id="form_npm" class="form-control" placeholder="NPM (Opsional)">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Nomor Pendaftaran :</label>
                <div class="col-sm-3 col-xs-12">
                    <input type="text" name="no_pendaftaran" id="form_no_pendaftaran" class="form-control" placeholder="No. Pendaftaran">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Nomor Pendaftaran Ulang :</label>
                <div class="col-sm-3 col-xs-12">
                    <input type="text" name="no_pendaftaran_ulang" id="form_no_pendaftaran_ulang" class="form-control" placeholder="No. Pendaftaran Ulang">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Password Akun Mahasiswa :</label>
                <div class="col-sm-4 col-xs-12">
                    <div class="input-group">
                        <input type="text" name="password" id="form_password" class="form-control text-bold" placeholder="Otomatis Tanggal Lahir (DDMMYYYY)" minlength="8" style="background: #fdfae7; color: #0073b7; letter-spacing: 1px;">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    </div>
                    <small class="text-muted"><i class="fa fa-info-circle text-primary"></i> Otomatis dari tanggal lahir format <strong>DDMMYYYY</strong> (contoh: <code>03092006</code>).</small>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Tempat & Tanggal Lahir :</label>
                <div class="col-sm-3 col-xs-12 form-mobile-gap">
                    <input type="text" name="tempat_lahir" id="form_tempat_lahir" class="form-control" placeholder="Tempat Lahir">
                </div>
                <div class="col-sm-3 col-xs-12">
                    <input type="text" name="tanggal_lahir" id="form_tanggal_lahir" class="form-control datepicker" placeholder="YYYY-MM-DD" autocomplete="off">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Jenis Kelamin <span class="text-danger">*</span> :</label>
                <div class="col-sm-4 col-xs-12">
                    <label class="radio-inline" style="font-weight: normal;">
                        <input type="radio" name="jenis_kelamin" id="jk_l" value="L" checked> Laki-Laki
                    </label>
                    <label class="radio-inline" style="font-weight: normal;">
                        <input type="radio" name="jenis_kelamin" id="jk_p" value="P"> Perempuan
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Agama :</label>
                <div class="col-sm-3 col-xs-12">
                    <select name="agama" id="form_agama" class="form-control select2" style="width: 100%;">
                        <option value="Islam" selected>Islam</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Budha">Budha</option>
                        <option value="Konghucu">Konghucu</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Golongan Darah :</label>
                <div class="col-sm-5 col-xs-12">
                    <label class="radio-inline"><input type="radio" name="golongan_darah" id="gd_a" value="A"> A</label>
                    <label class="radio-inline"><input type="radio" name="golongan_darah" id="gd_b" value="B"> B</label>
                    <label class="radio-inline"><input type="radio" name="golongan_darah" id="gd_ab" value="AB"> AB</label>
                    <label class="radio-inline"><input type="radio" name="golongan_darah" id="gd_o" value="O"> O</label>
                    <label class="radio-inline"><input type="radio" name="golongan_darah" id="gd_none" value="-" checked> Tidak Diketahui</label>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Kewarganegaraan :</label>
                <div class="col-sm-4 col-xs-12">
                    <label class="radio-inline"><input type="radio" name="kewarganegaraan" id="kw_wni" value="WNI" checked> WNI (Indonesia)</label>
                    <label class="radio-inline"><input type="radio" name="kewarganegaraan" id="kw_wna" value="WNA"> WNA (Asing)</label>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Nomor Telepon / WA :</label>
                <div class="col-sm-3 col-xs-12">
                    <input type="text" name="telepon" id="form_telepon" class="form-control" placeholder="Contoh: 08123456789">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Alamat Lengkap :</label>
                <div class="col-sm-5 col-xs-12">
                    <textarea name="alamat_lengkap" id="form_alamat_lengkap" class="form-control" rows="3" placeholder="Alamat tempat tinggal"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Kota & Propinsi :</label>
                <div class="col-sm-2 col-xs-12 form-mobile-gap">
                    <input type="text" name="kota" id="form_kota" class="form-control" placeholder="Kota / Kab">
                </div>
                <div class="col-sm-3 col-xs-12">
                    <select name="propinsi" id="form_propinsi" class="form-control select2" style="width: 100%;">
                        <option value="" disabled selected>Pilih Propinsi</option>
                        <?php foreach ($provinsi as $pv): ?>
                            <option value="<?= e($pv->nama) ?>"><?= e($pv->nama) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Nama Instansi / Kantor :</label>
                <div class="col-sm-4 col-xs-12">
                    <input type="text" name="nama_instansi" id="form_nama_instansi" class="form-control" placeholder="Instansi/Tempat Kerja (jika bekerja)">
                </div>
            </div>

            <h4 style="border-bottom: 2px solid #3c8dbc; padding-bottom: 5px; color: #3c8dbc; margin-top: 25px;">
                II. DATA ORANG TUA
            </h4>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Nama Ayah :</label>
                <div class="col-sm-4 col-xs-12">
                    <input type="text" name="nama_ayah" id="form_nama_ayah" class="form-control" placeholder="Nama Ayah Kandung">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Agama Ayah :</label>
                <div class="col-sm-3 col-xs-12">
                    <select name="agama_ayah" id="form_agama_ayah" class="form-control select2" style="width: 100%;">
                        <option value="Islam" selected>Islam</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Budha">Budha</option>
                        <option value="Konghucu">Konghucu</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Pekerjaan Ayah :</label>
                <div class="col-sm-3 col-xs-12">
                    <select name="pekerjaan_ayah" id="form_pekerjaan_ayah" class="form-control select2" style="width: 100%;">
                        <option value="Pegawai Negeri Sipil">Pegawai Negeri Sipil (PNS)</option>
                        <option value="Pegawai Swasta">Pegawai Swasta</option>
                        <option value="Wiraswasta" selected>Wiraswasta</option>
                        <option value="TNI/Polri">TNI/Polri</option>
                        <option value="Dosen">Dosen</option>
                        <option value="Guru">Guru</option>
                        <option value="Petani">Petani</option>
                        <option value="Rumah Tangga">Rumah Tangga</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Nama Ibu :</label>
                <div class="col-sm-4 col-xs-12">
                    <input type="text" name="nama_ibu" id="form_nama_ibu" class="form-control" placeholder="Nama Ibu Kandung">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Agama Ibu :</label>
                <div class="col-sm-3 col-xs-12">
                    <select name="agama_ibu" id="form_agama_ibu" class="form-control select2" style="width: 100%;">
                        <option value="Islam" selected>Islam</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Budha">Budha</option>
                        <option value="Konghucu">Konghucu</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Pekerjaan Ibu :</label>
                <div class="col-sm-3 col-xs-12">
                    <select name="pekerjaan_ibu" id="form_pekerjaan_ibu" class="form-control select2" style="width: 100%;">
                        <option value="Pegawai Negeri Sipil">Pegawai Negeri Sipil (PNS)</option>
                        <option value="Pegawai Swasta">Pegawai Swasta</option>
                        <option value="Wiraswasta">Wiraswasta</option>
                        <option value="TNI/Polri">TNI/Polri</option>
                        <option value="Dosen">Dosen</option>
                        <option value="Guru">Guru</option>
                        <option value="Petani">Petani</option>
                        <option value="Rumah Tangga" selected>Rumah Tangga</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Alamat Orang Tua :</label>
                <div class="col-sm-5 col-xs-12">
                    <textarea name="alamat_orangtua" id="form_alamat_orangtua" class="form-control" rows="3" placeholder="Alamat orang tua"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Kota & Propinsi Orang Tua :</label>
                <div class="col-sm-2 col-xs-12 form-mobile-gap">
                    <input type="text" name="kota_orangtua" id="form_kota_orangtua" class="form-control" placeholder="Kota Orang Tua">
                </div>
                <div class="col-sm-3 col-xs-12">
                    <select name="propinsi_orangtua" id="form_propinsi_orangtua" class="form-control select2" style="width: 100%;">
                        <option value="" disabled selected>Pilih Propinsi</option>
                        <?php foreach ($provinsi as $pv): ?>
                            <option value="<?= e($pv->nama) ?>"><?= e($pv->nama) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Telepon Orang Tua :</label>
                <div class="col-sm-3 col-xs-12">
                    <input type="text" name="telepon_orangtua" id="form_telepon_orangtua" class="form-control" placeholder="Nomor Telepon Orang Tua">
                </div>
            </div>

            <hr>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6 col-xs-12">
                    <button type="submit" id="btn_submit_mhs" class="btn btn-primary btn-lg flat btn-mobile-block"><i class="fa fa-save"></i> Simpan Mahasiswa Baru</button>
                    <a href="<?= site_url('admin/pengaturan/pembuatan_nim_baru') ?>" class="btn btn-default btn-lg flat btn-mobile-block" style="margin-left: 10px;"><i class="fa fa-refresh"></i> Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.select2').select2();
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    }).on('changeDate', function() {
        updatePasswordFromTglLahir();
    });

    function updatePasswordFromTglLahir() {
        var tgl = $.trim($('#form_tanggal_lahir').val());
        if (tgl) {
            var parts = tgl.split('-');
            if (parts.length === 3) {
                var d = ('0' + parts[2]).slice(-2);
                var m = ('0' + parts[1]).slice(-2);
                var y = parts[0];
                $('#form_password').val(d + m + y);
            }
        }
    }

    $('#form_tanggal_lahir').on('change input blur', function() {
        updatePasswordFromTglLahir();
    });

    $('#select_prodi, #select_angkatan').on('change', function() {
        generateNIM();
    });

    $('#btn_refresh_nim').on('click', function() {
        generateNIM();
    });

    function generateNIM() {
        var prodiId = $('#select_prodi').val();
        var angkatan = $('#select_angkatan').val();
        var prodiText = $('#select_prodi option:selected').text();

        if (!prodiId) {
            $('#nim_preview').val('');
            $('#panel_form_mahasiswa').hide();
            return;
        }

        $('#nim_preview').val('Menghitung...');
        $('#nim_status_help').html('<i class="fa fa-spinner fa-spin"></i> Menghitung NIM baru berikutnya...');

        $.ajax({
            url: '<?= site_url("admin/pengaturan/pembuatan_nim_baru/generate_nim") ?>',
            type: 'POST',
            data: {
                program_studi_kode: prodiId,
                angkatan: angkatan
            },
            dataType: 'json',
            success: function(res) {
                if (res.status) {
                    $('#nim_preview').val(res.nim);
                    $('#form_nim').val(res.nim);
                    $('#form_prodi_kode').val(prodiId);
                    $('#form_prodi_nama').val(prodiText);
                    $('#nim_status_help').html('<span class="text-green"><i class="fa fa-check"></i> NIM siap digunakan (+1 urutan: ' + res.seq + ')</span>');
                    $('#panel_form_mahasiswa').slideDown();
                } else {
                    $('#nim_preview').val('');
                    $('#nim_status_help').html('<span class="text-danger">' + (res.message || 'Gagal generate NIM') + '</span>');
                }
            },
            error: function() {
                $('#nim_preview').val('');
                $('#nim_status_help').html('<span class="text-danger">Gagal menghubungi server untuk generate NIM.</span>');
            }
        });
    }
});
</script>