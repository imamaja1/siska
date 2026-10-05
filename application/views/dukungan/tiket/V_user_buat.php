<style>
@media (max-width: 767px) {
    .box-header.with-border {
        display: flex !important;
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 10px;
    }
    .box-header .box-tools {
        position: static !important;
        float: none !important;
        width: 100%;
    }
    .box-header .box-tools .btn {
        display: block;
        width: 100%;
        text-align: center;
    }
    .form-btn-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .form-btn-actions .btn {
        width: 100%;
        margin-left: 0 !important;
        text-align: center;
    }
}
</style>

<?= $this->session->flashdata('info') ?>

<div class="box box-solid flat">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-pencil-square-o text-primary"></i> <strong>Form Pengajuan Tiket Bantuan & Kendala</strong></h4>
        <div class="box-tools pull-right">
            <a href="<?= site_url('dukungan/tiket/data') ?>" class="btn btn-default btn-sm flat">
                <i class="fa fa-arrow-left"></i> Kembali ke Tiket Saya
            </a>
        </div>
    </div>
    <div class="box-body">
        <p class="text-muted" style="margin-bottom:20px;">
            Pilih kategori kendala atau usulan Anda. Formulir isian di bawah akan otomatis menyesuaikan kebutuhan data sesuai kategori yang dipilih.
        </p>

        <form method="post" action="<?= site_url('dukungan/tiket/simpan') ?>" enctype="multipart/form-data" class="form-horizontal">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

            <!-- Kategori -->
            <div class="form-group">
                <label class="control-label col-sm-3">Kategori Tiket <span class="text-danger">*</span> :</label>
                <div class="col-sm-7">
                    <select name="kategori_id" id="select-kategori" class="form-control select2" required>
                        <option value="" selected disabled>-- Pilih Kategori Kendala / Pengajuan --</option>
                        <?php foreach ($kategori_list as $kat): ?>
                            <option value="<?= $kat->id ?>">
                                [<?= e($kat->kode_prefix) ?>] <?= e($kat->nama_kategori) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="kat-deskripsi-info" class="text-muted" style="font-size:12px; margin-top:4px;"></div>
                </div>
            </div>

            <!-- Judul -->
            <div class="form-group">
                <label class="control-label col-sm-3">Judul Tiket <span class="text-danger">*</span> :</label>
                <div class="col-sm-7">
                    <input type="text" name="judul" class="form-control" placeholder="Tuliskan ringkasan kendala (contoh: Nilai UTS tidak tersimpan di kelas A)" required>
                </div>
            </div>

            <!-- Urgensi -->
            <div class="form-group">
                <label class="control-label col-sm-3">Tingkat Urgensi <span class="text-danger">*</span> :</label>
                <div class="col-sm-4">
                    <select name="urgensi" class="form-control" required>
                        <option value="rendah">Rendah (Pertanyaan umum / usulan santai)</option>
                        <option value="sedang" selected>Sedang (Operasional normal)</option>
                        <option value="tinggi">Tinggi (Mengganggu pekerjaan utama)</option>
                        <option value="mendesak">Mendesak (Sistem macet / batas waktu segera)</option>
                    </select>
                </div>
            </div>

            <!-- Dynamic Custom Form Fields Container -->
            <div id="dynamic-fields-container" style="display:none; padding:15px; margin-bottom:15px; background:#f9fbfe; border:1px solid #d9e6f7; border-radius:4px;">
                <div style="font-weight:bold; margin-bottom:15px; color:#0073b7;">
                    <i class="fa fa-wpforms"></i> Data Isian Khusus Kategori Ini:
                </div>
                <div id="dynamic-fields-body"></div>
            </div>

            <!-- Deskripsi Utama -->
            <div class="form-group">
                <label class="control-label col-sm-3">Deskripsi Lengkap <span class="text-danger">*</span> :</label>
                <div class="col-sm-7">
                    <textarea name="deskripsi" rows="5" class="form-control" placeholder="Jelaskan secara rinci detail kendala yang dialami atau usulan yang diajukan..." required></textarea>
                </div>
            </div>

            <!-- Lampiran File / Screenshot -->
            <div class="form-group">
                <label class="control-label col-sm-3">Lampiran Gambar / Berkas :</label>
                <div class="col-sm-7">
                    <input type="file" name="lampiran" class="form-control">
                    <small class="text-muted">Format didukung: JPG, PNG, GIF, PDF, DOC, ZIP (Maks. 5MB). Sangat disarankan melampirkan screenshot bila terjadi error.</small>
                </div>
            </div>

            <div class="form-group" style="margin-top:25px;">
                <div class="col-sm-7 col-sm-offset-3 form-btn-actions">
                    <button type="submit" class="btn btn-primary flat" style="padding:8px 25px;">
                        <i class="fa fa-paper-plane"></i> Kirim Tiket Sekarang
                    </button>
                    <a href="<?= site_url('dukungan/tiket/data') ?>" class="btn btn-default flat" style="margin-left:10px;">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#select-kategori').on('change', function() {
        var katId = $(this).val();
        if (!katId) return;

        $('#dynamic-fields-container').hide();
        $('#dynamic-fields-body').empty();
        $('#kat-deskripsi-info').html('<i class="fa fa-spinner fa-spin"></i> Memuat form...');

        $.ajax({
            url: '<?= site_url('dukungan/tiket/get_form_fields_ajax/') ?>' + katId,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.deskripsi) {
                    $('#kat-deskripsi-info').html('<i class="fa fa-info-circle"></i> ' + res.deskripsi);
                } else {
                    $('#kat-deskripsi-info').empty();
                }

                if (res.fields && res.fields.length > 0) {
                    var html = '';
                    $.each(res.fields, function(i, f) {
                        var reqAttr = f.required ? 'required' : '';
                        var reqStar = f.required ? ' <span class="text-danger">*</span>' : '';

                        html += '<div class="form-group">' +
                                '<label class="control-label col-sm-3">' + f.label + reqStar + ' :</label>' +
                                '<div class="col-sm-7">';

                        if (f.type === 'textarea') {
                            html += '<textarea name="custom_' + f.name + '" rows="3" class="form-control" placeholder="' + (f.placeholder || '') + '" ' + reqAttr + '></textarea>';
                        } else if (f.type === 'number') {
                            html += '<input type="number" name="custom_' + f.name + '" class="form-control" placeholder="' + (f.placeholder || '') + '" ' + reqAttr + '>';
                        } else {
                            html += '<input type="text" name="custom_' + f.name + '" class="form-control" placeholder="' + (f.placeholder || '') + '" ' + reqAttr + '>';
                        }

                        html += '</div></div>';
                    });

                    $('#dynamic-fields-body').html(html);
                    $('#dynamic-fields-container').slideDown(200);
                }
            }
        });
    });
});
</script>
