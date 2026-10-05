<?= $this->session->flashdata('info') ?>

<div class="box box-solid flat">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-tags text-primary"></i> <strong>Manajemen Kategori & Form Dinamis Tiket</strong></h4>
        <div class="box-tools pull-right">
            <a href="<?= site_url('admin/dukungan/tiket/data') ?>" class="btn btn-default btn-sm flat">
                <i class="fa fa-arrow-left"></i> Kembali ke Inbox Tiket
            </a>
            <button type="button" class="btn btn-primary btn-sm flat" onclick="bukaModalKategori()">
                <i class="fa fa-plus-circle"></i> Tambah Kategori Baru
            </button>
        </div>
    </div>
    <div class="box-body">
        <p class="text-muted" style="margin-bottom:20px;">
            Atur kategori tiket dan rancang kolom isian formulir (Form Builder Dinamis) yang wajib diisi oleh pelapor saat memilih kategori tersebut.
        </p>

        <div class="table-responsive">
            <table class="table table-bordered table-striped demo-table">
                <thead>
                    <tr>
                        <th width="4%" style="text-align: center;">NO.</th>
                        <th width="12%">KODE PREFIX</th>
                        <th width="20%">NAMA KATEGORI</th>
                        <th width="12%" style="text-align: center;">ALUR SISTEM</th>
                        <th>KOLOM FORM DINAMIS</th>
                        <th width="10%" style="text-align: center;">STATUS</th>
                        <th width="12%" style="text-align: center;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = 1; foreach ($kategori_list as $kat): ?>
                    <?php 
                        $fields = json_decode($kat->form_fields, true) ?: [];
                    ?>
                    <tr>
                        <td align="center"><?= $i++ ?>.</td>
                        <td><span class="label label-primary" style="font-size:12px;"><?= e($kat->kode_prefix) ?></span></td>
                        <td>
                            <strong><?= e($kat->nama_kategori) ?></strong>
                            <br><small class="text-muted"><?= e($kat->deskripsi) ?></small>
                        </td>
                        <td align="center">
                            <?php if ($kat->tipe_alur === 'feature'): ?>
                                <span class="label label-info"><i class="fa fa-lightbulb-o"></i> Usulan Fitur</span>
                            <?php else: ?>
                                <span class="label label-danger"><i class="fa fa-bug"></i> Komplain/Bantuan</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (empty($fields)): ?>
                                <span class="text-muted"><em>Hanya form standar (Judul & Deskripsi)</em></span>
                            <?php else: ?>
                                <ul style="margin:0; padding-left:18px;">
                                    <?php foreach ($fields as $f): ?>
                                        <li>
                                            <strong><?= e($f['label']) ?></strong> 
                                            <small class="text-muted">(Tipe: <?= e($f['type']) ?><?= !empty($f['required']) ? ', <span class="text-danger">Wajib</span>' : '' ?>)</small>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </td>
                        <td align="center">
                            <?php if ($kat->is_active): ?>
                                <span class="label label-success">Aktif</span>
                            <?php else: ?>
                                <span class="label label-default">Non-Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td align="center">
                            <button type="button" class="btn btn-warning btn-xs flat btn-edit-kat" 
                                    data-json="<?= htmlspecialchars(json_encode($kat), ENT_QUOTES, 'UTF-8') ?>" 
                                    title="Edit Kategori & Form">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <a href="<?= site_url('admin/dukungan/tiket/toggle_kategori/' . $kat->id) ?>" 
                               class="btn btn-<?= $kat->is_active ? 'default' : 'success' ?> btn-xs flat" 
                               title="<?= $kat->is_active ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                <i class="fa fa-power-off"></i> <?= $kat->is_active ? 'Off' : 'On' ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form Kategori & Form Builder Dinamis -->
<div class="modal fade" id="modal-kategori" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content flat">
            <form method="post" action="<?= site_url('admin/dukungan/tiket/simpan_kategori') ?>">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <input type="hidden" name="id" id="kat-id" value="">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal-kat-title"><i class="fa fa-tags"></i> Tambah Kategori Tiket</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" name="nama_kategori" id="kat-nama" class="form-control" placeholder="Contoh: Fitur Bermasalah (Bug)" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Kode Prefix <span class="text-danger">*</span></label>
                                <input type="text" name="kode_prefix" id="kat-prefix" class="form-control" placeholder="BUG / REQ / SUP" maxlength="10" required style="text-transform:uppercase;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tipe Alur Tiket <span class="text-danger">*</span></label>
                                <select name="tipe_alur" id="kat-alur" class="form-control">
                                    <option value="incident">Komplain / Bug / Bantuan (Incident Flow)</option>
                                    <option value="feature">Pengajuan Fitur Baru (Feature Flow)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi Kategori</label>
                        <textarea name="deskripsi" id="kat-deskripsi" rows="2" class="form-control" placeholder="Penjelasan singkat tujuan kategori ini..."></textarea>
                    </div>

                    <hr>
                    <!-- Form Builder Dinamis -->
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                        <label style="font-size:14px; margin:0;">
                            <i class="fa fa-wpforms text-primary"></i> <strong>Form Builder Dinamis</strong> 
                            <small class="text-muted">(Kolom khusus yang wajib diisi pelapor)</small>
                        </label>
                        <button type="button" class="btn btn-success btn-xs flat" onclick="tambahBarisField()">
                            <i class="fa fa-plus"></i> Tambah Kolom Isian
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="tabel-form-builder">
                            <thead>
                                <tr style="background:#f9f9f9;">
                                    <th width="25%">Nama Variabel Field</th>
                                    <th width="30%">Label Judul Input</th>
                                    <th width="20%">Tipe Input</th>
                                    <th width="15%" style="text-align: center;">Wajib Diisi?</th>
                                    <th width="10%" style="text-align: center;">Hapus</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-fields">
                                <!-- Dinamis via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-save"></i> Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function renderFieldRow(f) {
    f = f || { name: '', label: '', type: 'text', required: true, placeholder: '' };
    var html = '<tr>' +
        '<td><input type="text" name="field_name[]" class="form-control input-sm" placeholder="url_menu / langkah_error" value="' + (f.name || '') + '" required></td>' +
        '<td><input type="text" name="field_label[]" class="form-control input-sm" placeholder="URL Menu / Langkah Error" value="' + (f.label || '') + '" required></td>' +
        '<td>' +
            '<select name="field_type[]" class="form-control input-sm">' +
                '<option value="text" ' + (f.type === 'text' ? 'selected' : '') + '>Teks Singkat (Text)</option>' +
                '<option value="textarea" ' + (f.type === 'textarea' ? 'selected' : '') + '>Teks Paragraf (Textarea)</option>' +
                '<option value="number" ' + (f.type === 'number' ? 'selected' : '') + '>Angka (Number)</option>' +
            '</select>' +
        '</td>' +
        '<td align="center">' +
            '<input type="checkbox" name="field_required[]" value="1" ' + (f.required ? 'checked' : '') + '>' +
        '</td>' +
        '<td align="center">' +
            '<button type="button" class="btn btn-danger btn-xs flat" onclick="$(this).closest(\'tr\').remove();"><i class="fa fa-trash"></i></button>' +
        '</td>' +
    '</tr>';
    $('#tbody-fields').append(html);
}

function tambahBarisField() {
    renderFieldRow();
}

function bukaModalKategori() {
    $('#modal-kat-title').html('<i class="fa fa-tags"></i> Tambah Kategori Tiket');
    $('#kat-id').val('');
    $('#kat-nama').val('');
    $('#kat-prefix').val('');
    $('#kat-alur').val('incident');
    $('#kat-deskripsi').val('');
    $('#tbody-fields').empty();
    $('#modal-kategori').modal('show');
}

$(document).on('click', '.btn-edit-kat', function() {
    var data = $(this).data('json');
    $('#modal-kat-title').html('<i class="fa fa-edit"></i> Edit Kategori Tiket');
    $('#kat-id').val(data.id);
    $('#kat-nama').val(data.nama_kategori);
    $('#kat-prefix').val(data.kode_prefix);
    $('#kat-alur').val(data.tipe_alur);
    $('#kat-deskripsi').val(data.deskripsi);

    $('#tbody-fields').empty();
    var fields = [];
    try {
        fields = JSON.parse(data.form_fields) || [];
    } catch(e) {}

    if (fields.length > 0) {
        $.each(fields, function(i, f) {
            renderFieldRow(f);
        });
    }

    $('#modal-kategori').modal('show');
});
</script>
