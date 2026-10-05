<?= $this->session->flashdata('info') ?>
<div id="notif-ajax-box"></div>

<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/laporan/kelulusan') ?>" class="btn btn-success btn-sm flat">
            <i class="fa fa-arrow-left"></i> Kembali ke Filter
        </a>
        <a href="<?= site_url('admin/laporan/kelulusan/cetak_excel') ?>" class="btn btn-primary btn-sm flat">
            <i class="fa fa-file-excel-o"></i> Cetak to Excel
        </a>
        <div class="pull-right">
            <?php if (!empty($prodi)): ?>
                <span class="badge bg-blue" style="padding: 7px 14px; font-size: 13px; font-weight: 600; border-radius: 4px; margin-left: 3px;"><?= e($prodi->singkatan_program_studi) ?> - <?= e($prodi->nama_program_studi) ?></span>
            <?php endif; ?>
            <?php if (!empty($ta_info)): ?>
                <span class="badge bg-blue" style="padding: 7px 14px; font-size: 13px; font-weight: 600; border-radius: 4px; margin-left: 3px;"><?= e($ta_info->tahun_akademik) ?> - <?= $ta_info->semester == 0 ? 'Genap' : 'Ganjil' ?></span>
            <?php else: ?>
                <span class="badge bg-blue" style="padding: 7px 14px; font-size: 13px; font-weight: 600; border-radius: 4px; margin-left: 3px;">Semua Periode Kelulusan</span>
            <?php endif; ?>
            <span class="badge bg-blue" id="badge-total-mhs" style="padding: 7px 14px; font-size: 13px; font-weight: 600; border-radius: 4px; margin-left: 3px;">Total: <span id="total-count"><?= count($data) ?></span> Mahasiswa</span>
        </div>
    </div>
</div>

<div class="row">
    <!-- Kotak Card Kiri: Tambah Mahasiswa Kelulusan -->
    <div class="col-md-4">
        <div class="box box-primary flat">
            <div class="box-header with-border">
                <h4 class="box-title text-primary"><i class="fa fa-user-plus"></i> <strong>Tambah Mahasiswa Lulus</strong></h4>
            </div>
            <div class="box-body">
                <form id="form-tambah-lulus" method="post" action="<?= site_url('admin/laporan/kelulusan/simpan_lulus') ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <input type="hidden" id="input-nim-selected" name="nim" value="">

                    <!-- Periode Kelulusan -->
                    <div class="form-group">
                        <label>Periode Kelulusan Target :</label>
                        <?php if (!empty($ta_info)): ?>
                            <input type="hidden" name="tahun_akademik" value="<?= e($ta_info->kode_tahun_akademik) ?>">
                            <div class="well well-sm" style="margin-bottom:0; background:#f4f6f9; font-weight:bold; color:#0073b7;">
                                <i class="fa fa-calendar-check-o"></i> <?= e($ta_info->tahun_akademik) ?> - <?= $ta_info->semester == 0 ? 'Genap' : 'Ganjil' ?>
                            </div>
                        <?php else: ?>
                            <select name="tahun_akademik" id="select-tahun-akademik" class="form-control" required>
                                <option value="" selected disabled>-- Pilih Periode Kelulusan --</option>
                                <?php foreach ($daftar_tahun_akademik as $ta): ?>
                                    <option value="<?= e($ta->kode_tahun_akademik) ?>">
                                        <?= e($ta->tahun_akademik) ?> - <?= $ta->semester == 0 ? 'Genap' : 'Ganjil' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <!-- Input Pencarian Mahasiswa -->
                    <div class="form-group" style="position: relative;">
                        <label for="input-cari-mhs">Cari Mahasiswa (NIM / Nama) :</label>
                        <div class="input-group">
                            <input type="text" id="input-cari-mhs" class="form-control" placeholder="Ketik NIM atau Nama..." autocomplete="off">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        </div>
                        <!-- Dropdown Hasil Pencarian -->
                        <div id="hasil-pencarian-box" style="display:none; position:absolute; z-index:999; left:15px; right:15px; background:#fff; border:1px solid #d2d6de; box-shadow:0 4px 8px rgba(0,0,0,0.1); max-height:220px; overflow-y:auto;">
                        </div>
                    </div>

                    <!-- Preview Mahasiswa Terpilih -->
                    <div id="preview-mhs" style="display:none; margin-bottom:15px; background:#ffffff; color:#333333; border:1px solid #d2d6de; border-left:4px solid #0073b7; padding:12px; border-radius:3px;">
                        <p style="margin:0 0 5px 0;"><strong>NIM:</strong> <span id="prev-nim" style="color:#0073b7; font-weight:bold;"></span></p>
                        <p style="margin:0 0 5px 0;"><strong>Nama:</strong> <span id="prev-nama" style="font-weight:600;"></span></p>
                        <p style="margin:0 0 5px 0;"><strong>Prodi:</strong> <span id="prev-prodi"></span></p>
                        <p style="margin:0;"><strong>Status:</strong> <span id="prev-status"></span></p>
                    </div>

                    <!-- Tombol Tambah -->
                    <button type="submit" id="btn-submit-lulus" class="btn btn-primary btn-block btn-flat" disabled>
                        <i class="fa fa-plus-circle"></i> Tambahkan ke Kelulusan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Kotak Card Kanan: Tabel Mahasiswa Kelulusan -->
    <div class="col-md-8">
        <div class="box box-solid flat">
            <div class="box-header with-border">
                <h4 class="box-title"><i class="fa fa-list"></i> <strong>Daftar Mahasiswa Kelulusan</strong></h4>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped demo-table" id="tabel-kelulusan">
                        <thead>
                            <tr>
                                <th width="4%" style="text-align: center;">NO.</th>
                                <th width="15%" style="text-align: center;">NIM</th>
                                <th>NAMA MAHASISWA</th>
                                <th width="22%">PROGRAM STUDI</th>
                                <th width="20%" style="text-align: center;">PERIODE LULUS</th>
                                <th width="8%" style="text-align: center;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-lulusan">
                        <?php if (empty($data)): ?>
                            <tr id="tr-kosong">
                                <td colspan="6" align="center" class="text-muted">Tidak ada data kelulusan yang ditemukan.</td>
                            </tr>
                        <?php else: ?>
                            <?php $i = 1; foreach ($data as $row) : ?>
                                <?php 
                                    $semester = ($row->semester_lulus == '0') ? 'Genap' : 'Ganjil';
                                    $ta_label = (!empty($row->tahun_lulus)) ? $row->tahun_lulus . ' - ' . $semester : '-';
                                ?>
                                <tr id="row-mhs-<?= e($row->nim) ?>">
                                    <td class="nomor-baris" align="center"><?= $i++ ?>.</td>
                                    <td align="center"><strong><?= !empty($row->nim) ? e($row->nim) : '-' ?></strong></td>
                                    <td><?= !empty($row->nama_mahasiswa) ? e($row->nama_mahasiswa) : '-' ?></td>
                                    <td><?= !empty($row->nama_program_studi) ? e($row->nama_program_studi) : '-' ?></td>
                                    <td align="center"><?= e($ta_label) ?></td>
                                    <td align="center">
                                        <button type="button" 
                                                class="btn btn-danger btn-xs flat btn-batal-lulus" 
                                                data-nim="<?= e($row->nim) ?>"
                                                title="Batalkan Kelulusan">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var timer;
    var csrfName = '<?= $this->security->get_csrf_token_name() ?>';
    var csrfHash = '<?= $this->security->get_csrf_hash() ?>';

    function reNumberTable() {
        var count = 0;
        $('#tbody-lulusan tr').not('#tr-kosong').each(function(index) {
            count++;
            $(this).find('.nomor-baris').text(count + '.');
        });
        $('#total-count').text(count);
        if (count === 0 && $('#tr-kosong').length === 0) {
            $('#tbody-lulusan').html('<tr id="tr-kosong"><td colspan="6" align="center" class="text-muted">Tidak ada data kelulusan yang ditemukan.</td></tr>');
        }
    }

    function showNotif(type, msg) {
        var icon = (type === 'success') ? 'fa-check' : 'fa-ban';
        var alertClass = (type === 'success') ? 'alert-success' : 'alert-danger';
        var html = '<div class="alert ' + alertClass + ' alert-dismissible">' +
                   '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                   '<i class="icon fa ' + icon + '"></i> ' + msg +
                   '</div>';
        $('#notif-ajax-box').html(html);
        $('html, body').animate({ scrollTop: $('#notif-ajax-box').offset().top - 20 }, 200);
    }

    // Live search input
    $('#input-cari-mhs').on('keyup', function() {
        var keyword = $(this).val().trim();
        clearTimeout(timer);

        if (keyword.length < 2) {
            $('#hasil-pencarian-box').hide().empty();
            return;
        }

        timer = setTimeout(function() {
            var postData = { keyword: keyword };
            postData[csrfName] = csrfHash;

            $.ajax({
                url: '<?= site_url('admin/laporan/kelulusan/cari_mahasiswa_ajax') ?>',
                type: 'POST',
                data: postData,
                dataType: 'json',
                success: function(res) {
                    var html = '';
                    if (res.length > 0) {
                        html += '<ul class="list-group" style="margin-bottom:0;">';
                        $.each(res, function(i, item) {
                            var statusText = 'Aktif';
                            var statusBadge = '<span class="label label-success">Aktif</span>';

                            if (item.ta_lulus) {
                                var smt = (item.semester_lulus === '0') ? 'Genap' : 'Ganjil';
                                var taLabel = item.tahun_lulus ? (item.tahun_lulus + ' - ' + smt) : 'TA ID ' + item.ta_lulus;
                                statusText = 'Sudah Lulus (' + taLabel + ')';
                                statusBadge = '<span class="label label-warning"><i class="fa fa-graduation-cap"></i> Lulus: ' + taLabel + '</span>';
                            } else if (item.status === 'N') {
                                statusText = 'Non-Aktif';
                                statusBadge = '<span class="label label-danger">Non-Aktif</span>';
                            }

                            html += '<li class="list-group-item item-pilih-mhs" style="cursor:pointer; padding:8px 12px;" ' +
                                    'data-nim="' + item.nim + '" ' +
                                    'data-nama="' + item.nama_mahasiswa + '" ' +
                                    'data-prodi="' + (item.nama_program_studi || '-') + '" ' +
                                    'data-status="' + statusText + '">' +
                                    '<strong>' + item.nim + '</strong> - ' + item.nama_mahasiswa + ' <br><small class="text-muted">' + (item.nama_program_studi || '') + '</small> ' + statusBadge +
                                    '</li>';
                        });
                        html += '</ul>';
                    } else {
                        html = '<div style="padding:10px; color:#999; text-align:center;">Mahasiswa tidak ditemukan</div>';
                    }
                    $('#hasil-pencarian-box').html(html).show();
                }
            });
        }, 300);
    });

    // Select student from search list
    $(document).on('click', '.item-pilih-mhs', function() {
        var nim = $(this).data('nim');
        var nama = $(this).data('nama');
        var prodi = $(this).data('prodi');
        var status = $(this).data('status');

        $('#input-nim-selected').val(nim);
        $('#input-cari-mhs').val(nim + ' - ' + nama);
        $('#prev-nim').text(nim);
        $('#prev-nama').text(nama);
        $('#prev-prodi').text(prodi);
        $('#prev-status').text(status);

        $('#preview-mhs').slideDown(150);
        $('#btn-submit-lulus').prop('disabled', false);
        $('#hasil-pencarian-box').hide().empty();
    });

    // Close search box on outside click
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#input-cari-mhs, #hasil-pencarian-box').length) {
            $('#hasil-pencarian-box').hide();
        }
    });

    // Pure AJAX Submit Tambah Kelulusan
    $('#form-tambah-lulus').on('submit', function(e) {
        e.preventDefault();

        var btn = $('#btn-submit-lulus');
        var originalBtnHtml = btn.html();
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

        var formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(res) {
                btn.prop('disabled', false).html(originalBtnHtml);

                if (res.status === 'success') {
                    showNotif('success', res.message);

                    // Hapus tr kosong jika ada
                    $('#tr-kosong').remove();

                    // Jika row sudah ada sebelumnya, hapus dulu agar diupdate di paling atas
                    $('#row-mhs-' + res.data.nim).remove();

                    // Buat baris baru
                    var newRowHtml = '<tr id="row-mhs-' + res.data.nim + '" style="background-color: #dff0d8; transition: background-color 1s ease;">' +
                        '<td class="nomor-baris" align="center">1.</td>' +
                        '<td align="center"><strong>' + res.data.nim + '</strong></td>' +
                        '<td>' + res.data.nama_mahasiswa + '</td>' +
                        '<td>' + res.data.nama_program_studi + '</td>' +
                        '<td align="center">' + res.data.periode_lulus + '</td>' +
                        '<td align="center">' +
                            '<button type="button" class="btn btn-danger btn-xs flat btn-batal-lulus" data-nim="' + res.data.nim + '" title="Batalkan Kelulusan">' +
                                '<i class="fa fa-trash"></i>' +
                            '</button>' +
                        '</td>' +
                    '</tr>';

                    $('#tbody-lulusan').prepend(newRowHtml);

                    // Re-index nomor baris dan total count
                    reNumberTable();

                    // Animasi warna baris
                    setTimeout(function() {
                        $('#row-mhs-' + res.data.nim).css('background-color', '');
                    }, 2000);

                    // Reset form input
                    $('#input-nim-selected').val('');
                    $('#input-cari-mhs').val('').focus();
                    $('#preview-mhs').slideUp(150);
                    btn.prop('disabled', true);
                } else {
                    showNotif('error', res.message || 'Terjadi kesalahan saat menambahkan.');
                }
            },
            error: function(xhr, status, error) {
                btn.prop('disabled', false).html(originalBtnHtml);
                showNotif('error', 'Gagal memproses request. Silakan coba kembali.');
            }
        });
    });

    // Pure AJAX Batal Kelulusan
    $(document).on('click', '.btn-batal-lulus', function(e) {
        e.preventDefault();
        var nim = $(this).data('nim');
        if (!nim) return;

        if (!confirm('Apakah Anda yakin ingin membatalkan status kelulusan mahasiswa ' + nim + '?')) {
            return;
        }

        var postData = { nim: nim };
        postData[csrfName] = csrfHash;

        $.ajax({
            url: '<?= site_url('admin/laporan/kelulusan/batal_lulus') ?>',
            type: 'POST',
            data: postData,
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(res) {
                if (res.status === 'success') {
                    showNotif('success', res.message);
                    $('#row-mhs-' + nim).fadeOut(300, function() {
                        $(this).remove();
                        reNumberTable();
                    });
                } else {
                    showNotif('error', res.message || 'Gagal membatalkan status kelulusan.');
                }
            },
            error: function() {
                showNotif('error', 'Terjadi kesalahan pada server saat membatalkan.');
            }
        });
    });
});
</script>
