<?= $this->session->flashdata('pesan') ?>

<div class="box box-primary flat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-users"></i> Distribusi Perwalian Random</h3>
    </div>
    <div class="box-body">
        <p>Distribusikan mahasiswa yang belum memiliki dosen wali ke dosen dengan status login aktif, berdasarkan urutan NIM dan dirata-ratakan sesuai jumlah dosen dengan status login aktif per program studi.</p>

        <form id="form-distribusi" method="POST" action="<?= site_url('admin/pengaturan/distribusi_perwalian/proses') ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Program Studi</label>
                        <select required name="kode_program_studi" id="kode_program_studi" class="form-control select2" style="width: 100%;">
                            <option value="" selected disabled>Pilih Program Studi</option>
                            <?php foreach ($program_studi as $row): ?>
                            <option value="<?= e($row->kode_program_studi) ?>"><?= e($row->singkatan_program_studi) ?> - <?= e($row->nama_program_studi) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Tahun Akademik Aktif</label>
                        <input type="text" class="form-control" value="<?= e($tahun_akademik->ta ?? '-') ?>" disabled>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="button" class="btn btn-info flat" id="btn-preview"><i class="fa fa-eye"></i> Preview</button>
                <button type="submit" class="btn btn-primary flat" id="btn-proses"><i class="fa fa-refresh"></i> Distribusi Random</button>
            </div>
        </form>

        <hr>

        <div id="hasil-preview" style="display: none;">
            <h4>Preview Distribusi</h4>
            <p id="info-preview" class="text-muted"></p>
            <div id="table-preview"></div>
        </div>
    </div>
</div>

<div class="box box-success flat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-hand-pointer-o"></i> Distribusi Perwalian Manual</h3>
    </div>
    <div class="box-body">
        <p>Pilih program studi dan tahun angkatan, lalu centang mahasiswa untuk diberikan dosen wali (kategori <b>Tidak Ada Wali</b>) atau dipindahkan ke dosen wali lain (kategori <b>Ada Wali</b>). Satu proses berlaku untuk satu dosen tujuan.</p>

        <form id="form-manual-filter">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Program Studi</label>
                        <select id="manual_kode_program_studi" class="form-control select2" style="width: 100%;">
                            <option value="" selected disabled>Pilih Program Studi</option>
                            <?php foreach ($program_studi as $row): ?>
                            <option value="<?= e($row->kode_program_studi) ?>"><?= e($row->singkatan_program_studi) ?> - <?= e($row->nama_program_studi) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Tahun Angkatan</label>
                        <select id="manual_angkatan" class="form-control select2" style="width: 100%;">
                            <option value="" selected disabled>Pilih Tahun Angkatan</option>
                            <?php foreach ($angkatan_list as $a): ?>
                            <option value="<?= e($a->angkatan) ?>"><?= '20' . e($a->angkatan) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <div>
                            <button type="button" class="btn btn-info flat" id="btn-tampilkan-manual"><i class="fa fa-search"></i> Tampilkan Mahasiswa</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div id="hasil-manual" style="display: none;">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-belum" data-toggle="tab"><i class="fa fa-user-times"></i> Tidak Ada Wali <span class="badge bg-red" id="badge-belum">0</span></a></li>
                <li><a href="#tab-sudah" data-toggle="tab"><i class="fa fa-user"></i> Ada Wali <span class="badge bg-green" id="badge-sudah">0</span></a></li>
            </ul>
            <div class="tab-content" style="border: 1px solid #ddd; border-top: none; padding: 15px;">
                <div class="tab-pane active" id="tab-belum">
                    <p class="text-muted">Centang mahasiswa untuk <b>memberikan dosen wali</b>.</p>
                    <form id="form-manual-belum" method="POST" action="<?= site_url('admin/pengaturan/distribusi_perwalian/manual_proses') ?>">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" name="kode_program_studi" id="manual_prodi_belum">
                        <input type="hidden" name="angkatan" id="manual_angkatan_belum">
                        <input type="hidden" name="tipe" value="belum">
                        <div class="form-group">
                            <label class="control-label">Dosen Wali Tujuan</label>
                            <select required name="kode_dosen" id="dosen_belum" class="form-control" style="width: 100%;">
                                <option value="" selected disabled>Pilih Dosen Wali</option>
                            </select>
                        </div>
                        <div id="table-belum"></div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary flat" id="btn-simpan-belum"><i class="fa fa-save"></i> Simpan Pemberian Wali</button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="tab-sudah">
                    <p class="text-muted">Centang mahasiswa untuk <b>memindahkan dosen wali</b>.</p>
                    <form id="form-manual-sudah" method="POST" action="<?= site_url('admin/pengaturan/distribusi_perwalian/manual_proses') ?>">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" name="kode_program_studi" id="manual_prodi_sudah">
                        <input type="hidden" name="angkatan" id="manual_angkatan_sudah">
                        <input type="hidden" name="tipe" value="sudah">
                        <div class="form-group">
                            <label class="control-label">Dosen Wali Tujuan</label>
                            <select required name="kode_dosen" id="dosen_sudah" class="form-control" style="width: 100%;">
                                <option value="" selected disabled>Pilih Dosen Wali</option>
                            </select>
                        </div>
                        <div id="table-sudah"></div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary flat" id="btn-simpan-sudah"><i class="fa fa-save"></i> Simpan Pemindahan Wali</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box box-danger flat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-trash"></i> Data & Hapus Perwalian</h3>
    </div>
    <div class="box-body">
        <p>Lihat penugasan dosen wali dan hapus beserta data konsultasinya berdasarkan program studi dan tahun angkatan. Tindakan hapus bersifat permanen dan tidak dapat dibatalkan.</p>

        <form id="form-hapus" method="POST" action="<?= site_url('admin/pengaturan/distribusi_perwalian/hapus') ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Program Studi</label>
                        <select required name="kode_program_studi" id="hapus_kode_program_studi" class="form-control select2" style="width: 100%;">
                            <option value="" selected disabled>Pilih Program Studi</option>
                            <?php foreach ($program_studi as $row): ?>
                            <option value="<?= e($row->kode_program_studi) ?>"><?= e($row->singkatan_program_studi) ?> - <?= e($row->nama_program_studi) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Tahun Angkatan</label>
                        <select required name="angkatan" id="hapus_angkatan" class="form-control select2" style="width: 100%;">
                            <option value="" selected disabled>Pilih Tahun Angkatan</option>
                            <?php foreach ($angkatan_list as $a): ?>
                            <option value="<?= e($a->angkatan) ?>"><?= '20' . e($a->angkatan) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="button" class="btn btn-info flat" id="btn-view-data"><i class="fa fa-list"></i> Lihat Data Perwalian</button>
                <button type="button" class="btn btn-warning flat" id="btn-preview-hapus"><i class="fa fa-eye"></i> Preview Hapus</button>
                <button type="submit" class="btn btn-danger flat" id="btn-hapus"><i class="fa fa-trash"></i> Hapus Perwalian</button>
            </div>
        </form>

        <div id="hasil-view-data" style="display: none;">
            <h4>Data Perwalian</h4>
            <p id="info-view-data" class="text-muted"></p>
            <div id="table-view-data"></div>
        </div>

        <div id="hasil-preview-hapus" style="display: none;">
            <h4>Preview Hapus</h4>
            <p id="info-preview-hapus" class="text-muted"></p>
        </div>
    </div>
</div>

<style>
.siska-toast {
    position: fixed;
    top: 15px;
    right: 15px;
    z-index: 99999;
    max-width: 360px;
    min-width: 280px;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.2);
    border-left: 5px solid #3c8dbc;
    cursor: pointer;
}
.siska-toast.alert-success { border-left-color: #00a65a; }
.siska-toast.alert-danger { border-left-color: #dd4b39; }
.siska-toast h6 { margin-bottom: 4px; }
.siska-toast p { margin: 0; color: #666; }
</style>

<script>
var csrf_name = '<?= $this->security->get_csrf_token_name() ?>';
var csrf_hash = '<?= $this->security->get_csrf_hash() ?>';

function toastTampil(tipe, judul, pesan) {
    var isSukses = tipe === 'success';
    var $toast = $('<div class="alert animated fadeInUp siska-toast"></div>')
        .addClass(isSukses ? 'alert-success' : 'alert-danger');
    $('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>')
        .appendTo($toast);
    var $h6 = $('<h6></h6>');
    $('<i class="fa"></i>')
        .addClass(isSukses ? 'fa-check-circle text-green' : 'fa-times-circle text-red')
        .appendTo($h6);
    $('<strong></strong>').text(' ' + judul).appendTo($h6);
    $h6.appendTo($toast);
    $('<p></p>').text(pesan).appendTo($toast);
    $('body').append($toast);
    $toast.on('click', function() { $toast.fadeOut(300, function() { $(this).remove(); }); });
    setTimeout(function() {
        $toast.fadeOut(300, function() { $(this).remove(); });
    }, 3000);
}

function toastSukses(pesan) {
    toastTampil('success', 'Sukses', pesan);
}

function toastGagal(pesan) {
    toastTampil('error', 'Gagal', pesan);
}

function konfirmasi(pesan, onYa) {
    var $modal = $('<div class="modal fade" tabindex="-1" role="dialog">' +
        '<div class="modal-dialog"><div class="modal-content">' +
        '<div class="modal-header">' +
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
        '<h4 class="modal-title"><i class="fa fa-question-circle text-yellow"></i> Konfirmasi</h4>' +
        '</div>' +
        '<div class="modal-body"></div>' +
        '<div class="modal-footer">' +
        '<button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Tidak</button>' +
        '<button type="button" class="btn btn-primary flat" id="btn-konfirmasi-ya"><i class="fa fa-check"></i> Ya</button>' +
        '</div>' +
        '</div></div></div>');
    $modal.find('.modal-body').text(pesan);
    $modal.on('hidden.bs.modal', function() { $modal.remove(); });
    $modal.find('#btn-konfirmasi-ya').on('click', function() {
        $modal.modal('hide');
        onYa();
    });
    $modal.appendTo('body').modal('show');
}

function muatDataManual() {
    var kode = $('#manual_kode_program_studi').val();
    var angkatan = $('#manual_angkatan').val();
    if (!kode || !angkatan) {
        toastGagal('Silakan lengkapi program studi dan tahun angkatan terlebih dahulu.');
        return;
    }
    var data = { kode_program_studi: kode, angkatan: angkatan };
    data[csrf_name] = csrf_hash;
    $.ajax({
        url: '<?= site_url("admin/pengaturan/distribusi_perwalian/manual_data") ?>',
        type: 'POST',
        dataType: 'json',
        data: data,
        success: function(res) {
            if (res.status) {
                $('#manual_prodi_belum').val(kode);
                $('#manual_angkatan_belum').val(angkatan);
                $('#manual_prodi_sudah').val(kode);
                $('#manual_angkatan_sudah').val(angkatan);

                if ($('#dosen_belum').hasClass('select2-hidden-accessible')) {
                    $('#dosen_belum').select2('destroy');
                }
                if ($('#dosen_sudah').hasClass('select2-hidden-accessible')) {
                    $('#dosen_sudah').select2('destroy');
                }
                $('#dosen_belum').html(res.dosen_options).select2({ width: '100%' });
                $('#dosen_sudah').html(res.dosen_options).select2({ width: '100%' });

                $('#table-belum').html(res.table_belum);
                $('#table-sudah').html(res.table_sudah);
                $('#badge-belum').text(res.jumlah_belum);
                $('#badge-sudah').text(res.jumlah_sudah);
                $('#hasil-manual').show();
            } else {
                toastGagal(res.message || 'Terjadi kesalahan.');
            }
        },
        error: function() {
            toastGagal('Terjadi kesalahan koneksi.');
        }
    });
}

function simpanManual(form, tipe) {
    var btn = tipe === 'belum' ? '#btn-simpan-belum' : '#btn-simpan-sudah';
    var label = tipe === 'belum' ? 'Simpan Pemberian Wali' : 'Simpan Pemindahan Wali';
    $.ajax({
        url: $(form).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(form).serialize(),
        beforeSend: function() {
            $(btn).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
        },
        success: function(res) {
            $(btn).prop('disabled', false).html('<i class="fa fa-save"></i> ' + label);
            if (res.status) {
                toastSukses(res.message);
                muatDataManual();
            } else {
                toastGagal(res.message || 'Terjadi kesalahan.');
            }
        },
        error: function() {
            $(btn).prop('disabled', false).html('<i class="fa fa-save"></i> ' + label);
            toastGagal('Terjadi kesalahan koneksi.');
        }
    });
}

$(document).ready(function() {
    $('#btn-preview').on('click', function() {
        var kode = $('#kode_program_studi').val();
        if (!kode) {
            toastGagal('Silakan pilih program studi terlebih dahulu.');
            return;
        }
        var data = { kode_program_studi: kode };
        data[csrf_name] = csrf_hash;
        $.ajax({
            url: '<?= site_url("admin/pengaturan/distribusi_perwalian/preview") ?>',
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(res) {
                if (res.status) {
                    $('#hasil-preview').show();
                    $('#info-preview').text('Jumlah dosen status login aktif: ' + res.jumlah_dosen + ' | Jumlah mahasiswa belum ber-wali: ' + res.jumlah_mahasiswa);
                    $('#table-preview').html(res.html);
                } else {
                    toastGagal('Terjadi kesalahan.');
                }
            },
            error: function() {
                toastGagal('Terjadi kesalahan koneksi.');
            }
        });
    });

    $('#form-distribusi').on('submit', function(e) {
        var kode = $('#kode_program_studi').val();
        if (!kode) {
            e.preventDefault();
            toastGagal('Silakan pilih program studi terlebih dahulu.');
            return;
        }
        e.preventDefault();
        var form = this;
        konfirmasi('Yakin ingin melakukan distribusi perwalian? Data perwalian baru akan ditambahkan untuk mahasiswa yang belum memiliki dosen wali.', function() {
            form.submit();
        });
    });

    $('#btn-view-data').on('click', function() {
        var kode = $('#hapus_kode_program_studi').val();
        var angkatan = $('#hapus_angkatan').val();
        if (!kode || !angkatan) {
            toastGagal('Silakan lengkapi program studi dan tahun angkatan terlebih dahulu.');
            return;
        }
        var data = { kode_program_studi: kode, angkatan: angkatan };
        data[csrf_name] = csrf_hash;
        $.ajax({
            url: '<?= site_url("admin/pengaturan/distribusi_perwalian/view_data") ?>',
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(res) {
                if (res.status) {
                    $('#hasil-view-data').show();
                    $('#info-view-data').text('Jumlah data perwalian: ' + res.jumlah);
                    $('#table-view-data').html(res.html);
                } else {
                    toastGagal(res.message || 'Terjadi kesalahan.');
                }
            },
            error: function() {
                toastGagal('Terjadi kesalahan koneksi.');
            }
        });
    });

    $('#btn-preview-hapus').on('click', function() {
        var kode = $('#hapus_kode_program_studi').val();
        var angkatan = $('#hapus_angkatan').val();
        if (!kode || !angkatan) {
            toastGagal('Silakan lengkapi program studi dan tahun angkatan terlebih dahulu.');
            return;
        }
        var data = { kode_program_studi: kode, angkatan: angkatan };
        data[csrf_name] = csrf_hash;
        $.ajax({
            url: '<?= site_url("admin/pengaturan/distribusi_perwalian/preview_hapus") ?>',
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(res) {
                if (res.status) {
                    $('#hasil-preview-hapus').show();
                    $('#info-preview-hapus').text('Data yang akan dihapus: ' + res.jumlah_perwalian + ' data perwalian dan ' + res.jumlah_konsultasi + ' data konsultasi.');
                } else {
                    toastGagal(res.message || 'Terjadi kesalahan.');
                }
            },
            error: function() {
                toastGagal('Terjadi kesalahan koneksi.');
            }
        });
    });

    $('#form-hapus').on('submit', function(e) {
        var kode = $('#hapus_kode_program_studi').val();
        var angkatan = $('#hapus_angkatan').val();
        if (!kode || !angkatan) {
            e.preventDefault();
            toastGagal('Silakan lengkapi program studi dan tahun angkatan terlebih dahulu.');
            return;
        }
        e.preventDefault();
        var form = this;
        konfirmasi('Yakin ingin menghapus perwalian untuk angkatan ini? Data perwalian beserta konsultasi yang cocok akan dihapus permanen.', function() {
            form.submit();
        });
    });

    $('#btn-tampilkan-manual').on('click', function() {
        muatDataManual();
    });

    $(document).on('change', '#check-all-belum', function() {
        $('.check-belum').prop('checked', $(this).prop('checked'));
    });

    $(document).on('change', '#check-all-sudah', function() {
        $('.check-sudah').prop('checked', $(this).prop('checked'));
    });

    $('#form-manual-belum').on('submit', function(e) {
        e.preventDefault();
        var form = this;
        if (!$('#dosen_belum').val()) {
            toastGagal('Silakan pilih dosen wali tujuan terlebih dahulu.');
            return;
        }
        if ($('.check-belum:checked').length === 0) {
            toastGagal('Silakan centang minimal satu mahasiswa.');
            return;
        }
        konfirmasi('Yakin ingin memberikan dosen wali kepada mahasiswa yang dicentang?', function() {
            simpanManual(form, 'belum');
        });
    });

    $('#form-manual-sudah').on('submit', function(e) {
        e.preventDefault();
        var form = this;
        if (!$('#dosen_sudah').val()) {
            toastGagal('Silakan pilih dosen wali tujuan terlebih dahulu.');
            return;
        }
        if ($('.check-sudah:checked').length === 0) {
            toastGagal('Silakan centang minimal satu mahasiswa.');
            return;
        }
        konfirmasi('Yakin ingin memindahkan dosen wali mahasiswa yang dicentang?', function() {
            simpanManual(form, 'sudah');
        });
    });
});
</script>
