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
    .input-group-mobile-stack,
    .input-group-mobile-stack .input-group-btn,
    .input-group-mobile-stack .form-control {
        display: block !important;
        width: 100% !important;
        border-radius: 0 !important;
    }
    .input-group-mobile-stack .input-group-btn {
        margin-top: 8px;
    }
    .input-group-mobile-stack .btn {
        width: 100% !important;
    }
    .btn-terapkan-mobile {
        float: none !important;
        display: block !important;
        width: 100% !important;
        margin-top: 10px !important;
        margin-bottom: 5px !important;
        text-align: center;
    }
}
</style>

<?= $this->session->flashdata('pesan'); ?>

<!-- Section 1: Konfigurasi Prodi Tujuan & Angkatan -->
<div class="box box-warning flat">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-exchange" style="color: #f39c12;"></i><strong> Pembuatan NIM Transfer Mahasiswa Bumigora</strong></h4>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-5 col-sm-12 form-mobile-gap">
                <div class="form-group" style="margin-bottom: 0;">
                    <label>Program Studi Tujuan Transfer <span class="text-danger">*</span></label>
                    <select id="select_prodi" class="form-control select2" style="width: 100%;">
                        <option value="" disabled selected>-- Pilih Program Studi Tujuan --</option>
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
                    <label>Generated NIM Baru (Tujuan):</label>
                    <div class="input-group">
                        <input type="text" id="nim_preview" class="form-control input-lg text-bold" style="background: #fff8e1; color: #b7791f; font-size: 18px; letter-spacing: 1px;" readonly placeholder="Pilih prodi tujuan...">
                        <span class="input-group-btn">
                            <button id="btn_refresh_nim" class="btn btn-warning btn-lg flat" type="button" title="Refresh NIM"><i class="fa fa-refresh"></i></button>
                        </span>
                    </div>
                    <small id="nim_status_help" class="text-muted">NIM baru otomatis dibuat (+1) berdasarkan prodi tujuan.</small>
                </div>
            </div>
        </div>

        <hr style="margin-top: 10px; margin-bottom: 15px;">

        <!-- Cari Data Mahasiswa Asal Bumigora -->
        <div class="row">
            <div class="col-md-7 col-sm-12">
                <label>Cari NIM Mahasiswa Bumigora Asal <span class="text-danger">*</span></label>
                <div class="input-group input-group-mobile-stack">
                    <input type="text" id="input_nim_asal" class="form-control input-lg" placeholder="Masukkan NIM mahasiswa Bumigora asal...">
                    <span class="input-group-btn">
                        <button id="btn_cari_nim_asal" class="btn btn-primary btn-lg flat" type="button"><i class="fa fa-search"></i> Cari Data Mahasiswa</button>
                    </span>
                </div>
                <small class="text-muted">Masukkan NIM lama mahasiswa UBG untuk menarik biodata ke prodi tujuan baru.</small>
                <div id="transfer_search_spinner" style="display: none; margin-top: 10px;">
                    <i class="fa fa-spinner fa-spin text-primary"></i> <span style="margin-left: 5px;">Mencari data mahasiswa di database...</span>
                </div>
            </div>
        </div>

        <!-- Preview Mahasiswa Ditemukan -->
        <div id="transfer_result_box" style="display: none; margin-top: 15px;">
            <div class="callout callout-info flat" style="margin-bottom: 0;">
                <button type="button" id="btn_terapkan_transfer" class="btn btn-success btn-sm flat pull-right btn-terapkan-mobile" style="margin-top: -3px;">
                    <i class="fa fa-check"></i> Gunakan Data Mahasiswa Ini
                </button>
                <h5 style="margin-top: 0; font-weight: bold;"><i class="fa fa-user"></i> Data Ditemukan: <span id="res_nama_mhs">-</span></h5>
                <p style="margin-bottom: 0; font-size: 13px;">
                    <strong>NIM Asal:</strong> <span id="res_nim_mhs">-</span> &nbsp;|&nbsp; 
                    <strong>Prodi Asal:</strong> <span id="res_prodi_mhs">-</span> &nbsp;|&nbsp; 
                    <strong>NIK:</strong> <span id="res_nik_mhs">-</span> &nbsp;|&nbsp; 
                    <strong>TTL:</strong> <span id="res_ttl_mhs">-</span>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Banner Indikator Transfer Aktif -->
<div id="transfer_alert_box" class="alert alert-warning alert-dismissible flat" style="display: none;">
    <h4><i class="icon fa fa-exchange"></i> Data Mahasiswa Bumigora Siap Ditransfer</h4>
    Biodata telah ditarik dari NIM Asal: <strong id="lbl_nim_asal">-</strong> (<span id="lbl_prodi_asal">-</span>) atas nama <strong id="lbl_nama_asal">-</strong>.
    Status Pendaftaran otomatis dikunci hanya <strong>Transfer (T)</strong> dengan NIM baru yang di-generate untuk prodi tujuan.
</div>

<!-- Section 3: Form Input Data Mahasiswa Transfer -->
<div class="box box-solid flat" id="panel_form_mahasiswa" style="display: none;">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-edit" style="color: #f39c12;"></i><strong> Formulir Mahasiswa Transfer</strong></h4>
    </div>
    <div class="box-body">
        <form id="form_mahasiswa" class="form-horizontal" action="<?= site_url('admin/pengaturan/pembuatan_nim_transfer/simpan') ?>" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <input type="hidden" name="program_studi_kode" id="form_prodi_kode" value="">
            <input type="hidden" name="is_transfer" value="1">
            <input type="hidden" name="nim_asal" id="form_nim_asal" value="">

            <div class="alert alert-warning flat" style="padding: 10px 15px; margin-bottom: 20px;">
                <i class="fa fa-info-circle"></i> Status Pendaftaran terkunci hanya <strong>Transfer (T)</strong>. Silakan periksa atau lengkapi data mahasiswa di bawah ini.
            </div>

            <h4 style="border-bottom: 2px solid #f39c12; padding-bottom: 5px; color: #f39c12; margin-top: 10px;">
                I. IDENTITAS MAHASISWA TRANSFER
            </h4>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Nomor Induk Mahasiswa (NIM Baru) <span class="text-danger">*</span> :</label>
                <div class="col-sm-4 col-xs-12">
                    <input type="text" id="form_nim" name="nim" class="form-control input-lg text-bold" style="background: #fdfae7; color: #d73925; font-size: 17px;" readonly required>
                    <small class="text-muted">NIM baru di prodi tujuan (+1 urutan)</small>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Program Studi Tujuan :</label>
                <div class="col-sm-5 col-xs-12">
                    <input type="text" id="form_prodi_nama" class="form-control" readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 col-xs-12">Status Pendaftaran <span class="text-danger">*</span> :</label>
                <div class="col-sm-4 col-xs-12">
                    <select name="status_pendaftaran" id="form_status_pendaftaran" class="form-control" readonly required>
                        <option value="T" selected>Transfer (T)</option>
                    </select>
                    <small class="text-orange"><i class="fa fa-lock"></i> Terkunci hanya status <strong>Transfer (T)</strong> untuk transfer mahasiswa.</small>
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
                        <input type="text" name="password" id="form_password" class="form-control text-bold" placeholder="Otomatis Tanggal Lahir (DDMMYYYY)" minlength="8" style="background: #fdfae7; color: #b7791f; letter-spacing: 1px;">
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

            <h4 style="border-bottom: 2px solid #f39c12; padding-bottom: 5px; color: #f39c12; margin-top: 25px;">
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
                    <button type="submit" id="btn_submit_mhs" class="btn btn-warning btn-lg flat btn-mobile-block"><i class="fa fa-save"></i> Simpan Mahasiswa Transfer</button>
                    <a href="<?= site_url('admin/pengaturan/pembuatan_nim_transfer') ?>" class="btn btn-default btn-lg flat btn-mobile-block" style="margin-left: 10px;"><i class="fa fa-refresh"></i> Reset</a>
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

    var tempTransferData = null;

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
        $('#nim_status_help').html('<i class="fa fa-spinner fa-spin"></i> Menghitung NIM tujuan berikutnya...');

        $.ajax({
            url: '<?= site_url("admin/pengaturan/pembuatan_nim_transfer/generate_nim") ?>',
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
                    $('#nim_status_help').html('<span class="text-green"><i class="fa fa-check"></i> NIM tujuan siap (+1 urutan: ' + res.seq + ')</span>');
                    if (tempTransferData) {
                        $('#panel_form_mahasiswa').slideDown();
                    }
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

    // Enter di input NIM Asal
    $('#input_nim_asal').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#btn_cari_nim_asal').click();
        }
    });

    // Cari NIM Asal
    $('#btn_cari_nim_asal').on('click', function() {
        var nim = $.trim($('#input_nim_asal').val());
        if (!nim) {
            alert('Masukkan NIM mahasiswa asal Bumigora.');
            return;
        }

        $('#transfer_search_spinner').show();
        $('#transfer_result_box').hide();
        tempTransferData = null;

        $.ajax({
            url: '<?= site_url("admin/pengaturan/pembuatan_nim_transfer/cari_transfer") ?>',
            type: 'POST',
            data: { nim: nim },
            dataType: 'json',
            success: function(res) {
                $('#transfer_search_spinner').hide();
                if (res.status && res.data) {
                    tempTransferData = res.data;
                    $('#res_nama_mhs').text(res.data.nama_mahasiswa);
                    $('#res_nim_mhs').text(res.data.nim_asal);
                    $('#res_prodi_mhs').text(res.data.nama_prodi_asal);
                    $('#res_nik_mhs').text(res.data.nik || '-');
                    $('#res_ttl_mhs').text((res.data.tempat_lahir || '-') + ' / ' + (res.data.tanggal_lahir || '-'));
                    $('#transfer_result_box').fadeIn();
                } else {
                    alert(res.message || 'Mahasiswa tidak ditemukan di database Bumigora.');
                }
            },
            error: function() {
                $('#transfer_search_spinner').hide();
                alert('Terjadi kesalahan saat mencari data mahasiswa.');
            }
        });
    });

    // Terapkan Data Mahasiswa Asal ke Form
    $('#btn_terapkan_transfer').on('click', function() {
        if (!tempTransferData) return;

        var prodiId = $('#select_prodi').val();
        if (!prodiId) {
            alert('Silakan pilih Program Studi Tujuan Transfer terlebih dahulu di atas.');
            $('#select_prodi').select2('open');
            return;
        }

        $('#form_nim_asal').val(tempTransferData.nim_asal);

        // Isi form data mahasiswa
        $('#form_nama_mahasiswa').val(tempTransferData.nama_mahasiswa);
        $('#form_nik').val(tempTransferData.nik);
        $('#form_npm').val(tempTransferData.npm);
        $('#form_tempat_lahir').val(tempTransferData.tempat_lahir);
        $('#form_tanggal_lahir').val(tempTransferData.tanggal_lahir);
        updatePasswordFromTglLahir();
        $('#form_telepon').val(tempTransferData.telepon);
        $('#form_alamat_lengkap').val(tempTransferData.alamat_lengkap);
        $('#form_kota').val(tempTransferData.kota);
        $('#form_nama_instansi').val(tempTransferData.nama_instansi);

        if (tempTransferData.propinsi) {
            $('#form_propinsi').val(tempTransferData.propinsi).trigger('change');
        }
        if (tempTransferData.jenis_kelamin === 'P') {
            $('#jk_p').prop('checked', true);
        } else {
            $('#jk_l').prop('checked', true);
        }
        if (tempTransferData.agama) {
            $('#form_agama').val(tempTransferData.agama).trigger('change');
        }
        if (tempTransferData.golongan_darah) {
            $('input[name="golongan_darah"][value="' + tempTransferData.golongan_darah + '"]').prop('checked', true);
        }
        if (tempTransferData.kewarganegaraan === 'WNA') {
            $('#kw_wna').prop('checked', true);
        } else {
            $('#kw_wni').prop('checked', true);
        }

        // Data Orang Tua
        $('#form_nama_ayah').val(tempTransferData.nama_ayah);
        if (tempTransferData.agama_ayah) {
            $('#form_agama_ayah').val(tempTransferData.agama_ayah).trigger('change');
        }
        if (tempTransferData.pekerjaan_ayah) {
            $('#form_pekerjaan_ayah').val(tempTransferData.pekerjaan_ayah).trigger('change');
        }
        $('#form_nama_ibu').val(tempTransferData.nama_ibu);
        if (tempTransferData.agama_ibu) {
            $('#form_agama_ibu').val(tempTransferData.agama_ibu).trigger('change');
        }
        if (tempTransferData.pekerjaan_ibu) {
            $('#form_pekerjaan_ibu').val(tempTransferData.pekerjaan_ibu).trigger('change');
        }
        $('#form_alamat_orangtua').val(tempTransferData.alamat_orangtua);
        $('#form_kota_orangtua').val(tempTransferData.kota_orangtua);
        if (tempTransferData.propinsi_orangtua) {
            $('#form_propinsi_orangtua').val(tempTransferData.propinsi_orangtua).trigger('change');
        }
        $('#form_telepon_orangtua').val(tempTransferData.telepon_orangtua);

        // Update Banner Indikator
        $('#lbl_nim_asal').text(tempTransferData.nim_asal);
        $('#lbl_nama_asal').text(tempTransferData.nama_mahasiswa);
        $('#lbl_prodi_asal').text(tempTransferData.nama_prodi_asal);
        $('#transfer_alert_box').slideDown();

        // Tampilkan Form
        $('#panel_form_mahasiswa').slideDown();

        // Scroll ke form
        $('html, body').animate({
            scrollTop: $('#panel_form_mahasiswa').offset().top - 20
        }, 500);
    });
});
</script>