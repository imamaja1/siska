<?= $this->session->flashdata('pesan') ?>

<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/pengaturan/api_tokens/tambah') ?>" class="btn btn-xs btn-primary flat">
            <i class="fa fa-plus-circle"></i> Tambah Token
        </a>
    </div>
</div>

<!-- Daftar Token -->
<div class="box box-primary flat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-key"></i> Daftar Token API</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="40">No</th>
                    <th>Nama Aplikasi</th>
                    <th>URL Endpoint</th>
                    <th>Token</th>
                    <th width="80">Status</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tokens)): ?>
                <tr>
                    <td colspan="6" class="text-center">Belum ada token.</td>
                </tr>
                <?php else: ?>
                <?php $no = 1; foreach ($tokens as $token): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><strong><?= e($token->nama_aplikasi) ?></strong></td>
                    <td><small><?= e($token->api_url) ?></small></td>
                    <td>
                        <code class="token-mask" id="token-<?= $token->id ?>">
                            <?= substr($token->bearer_token, 0, 8) ?>...<?= substr($token->bearer_token, -4) ?>
                        </code>
                        <button type="button" class="btn btn-xs btn-default flat btn-toggle-token" data-id="<?= $token->id ?>" data-token="<?= e($token->bearer_token) ?>" title="Lihat Token">
                            <i class="fa fa-eye"></i>
                        </button>
                    </td>
                    <td class="text-center">
                        <?php if ($token->is_active): ?>
                            <span class="label label-success">Aktif</span>
                        <?php else: ?>
                            <span class="label label-danger">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button type="button" class="btn btn-xs btn-success flat btn-toggle-status" data-id="<?= $token->id ?>" title="Toggle Status">
                            <i class="fa fa-power-off"></i>
                        </button>
                        <a href="<?= site_url('admin/pengaturan/api_tokens/edit/' . $token->id) ?>" class="btn btn-xs btn-info flat" title="Edit">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-xs btn-warning flat btn-sync" data-id="<?= $token->id ?>" data-nama="<?= e($token->nama_aplikasi) ?>" title="Sinkronisasi dari PMB">
                            <i class="fa fa-refresh"></i> Sync
                        </button>
                        <a href="<?= site_url('admin/pengaturan/api_tokens/hapus/' . $token->id) ?>" class="btn btn-xs btn-danger flat" onclick="return confirm('Hapus token ini?')" title="Hapus">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Log Aktivitas -->
<div class="box box-success flat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-history"></i> Log Aktivitas Sinkronisasi (9 Terakhir)</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="40">No</th>
                    <th width="140">Waktu</th>
                    <th>Aplikasi</th>
                    <th width="90">Status</th>
                    <th width="80">Data</th>
                    <th width="60">Code</th>
                    <th width="120">IP Address</th>
                    <th width="60">Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada log.</td>
                </tr>
                <?php else: ?>
                <?php $no = 1; foreach ($logs as $log): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><small><?= date('d-m-Y H:i', strtotime($log->created_at)) ?></small></td>
                    <td><?= e($log->nama_aplikasi ?? '-') ?></td>
                    <td>
                        <?php if ($log->status_sync === 'success'): ?>
                            <span class="label label-success">Berhasil</span>
                        <?php else: ?>
                            <span class="label label-danger">Gagal</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $log->total_data ?> data</td>
                    <td><?= $log->response_code ?? '-' ?></td>
                    <td><small><?= e($log->ip_address ?? '-') ?></small></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-xs btn-info flat btn-detail-log"
                            data-log-id="<?= $log->id ?>"
                            title="Lihat Detail">
                            <i class="fa fa-eye"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail Log -->
<div class="modal fade" id="modalDetailLog" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-aqua">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-list"></i> Detail Sinkronisasi</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-sm-4">
                        <strong>Waktu:</strong> <span id="modal-waktu">-</span>
                    </div>
                    <div class="col-sm-4">
                        <strong>Status:</strong> <span id="modal-status">-</span>
                    </div>
                    <div class="col-sm-4">
                        <strong>Total Data:</strong> <span id="modal-total">-</span>
                    </div>
                </div>
                <hr style="margin: 10px 0;">
                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered table-striped table-condensed" id="table-detail-log">
                        <thead>
                            <tr>
                                <th width="40">No</th>
                                <th width="150">NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th width="80">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="modal-detail-body">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default flat" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sync Progress -->
<div class="modal fade" id="modalSync" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-aqua">
                <h4 class="modal-title"><i class="fa fa-refresh fa-spin"></i> Sinkronisasi dari PMB</h4>
            </div>
            <div class="modal-body">
                <div class="progress progress-striped active" style="margin-bottom: 15px;">
                    <div class="progress-bar progress-bar-success" style="width: 0%">0%</div>
                </div>
                <p id="sync-status" class="text-center" style="font-size: 14px;">Memulai...</p>
                <div id="sync-detail" style="max-height: 200px; overflow-y: auto; margin-top: 10px;"></div>
            </div>
        </div>
    </div>
</div>

<style>
#modalSync .modal-content {
    border-top: 3px solid #00c0ef;
}
#modalSync .progress {
    height: 25px;
    border-radius: 4px;
}
#modalSync .progress-bar {
    line-height: 25px;
    font-size: 13px;
    font-weight: bold;
    transition: width 0.5s ease;
}
#sync-status {
    font-weight: bold;
    color: #333;
}
#sync-detail {
    font-size: 12px;
    color: #555;
}
.sync-log-item {
    padding: 4px 8px;
    border-bottom: 1px solid #eee;
    font-size: 12px;
    text-align: left;
}
.sync-log-item:last-child {
    border-bottom: none;
}
</style>

<script>
$(document).ready(function() {
    // Toggle show/hide token
    $('.btn-toggle-token').on('click', function() {
        var id = $(this).data('id');
        var token = $(this).data('token');
        var el = $('#token-' + id);
        if (el.hasClass('token-mask')) {
            el.text(token);
            el.removeClass('token-mask');
            $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            el.html(token.substring(0, 8) + '...' + token.substring(token.length - 4));
            el.addClass('token-mask');
            $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Toggle status
    $('.btn-toggle-status').on('click', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '<?= site_url("admin/pengaturan/api_tokens/toggle/") ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status) {
                    swal('Berhasil', res.message, 'success').then(function() {
                        location.reload();
                    });
                } else {
                    swal('Gagal', res.message, 'error');
                }
            }
        });
    });

    // Sinkronisasi dengan progress
    $('.btn-sync').on('click', function() {
        var id = $(this).data('id');
        var nama = $(this).data('nama');
        swal({
            title: 'Sinkronisasi',
            text: 'Ambil data dari ' + nama + '?',
            icon: 'info',
            buttons: true,
            dangerMode: false,
        }).then(function(ok) {
            if (ok) {
                startSync(id);
            }
        });
    });

    // Tampilkan modal detail log via AJAX
    $('.btn-detail-log').on('click', function() {
        var logId = $(this).data('log-id');

        $('#modal-waktu').text('Memuat...');
        $('#modal-total').text('-');
        $('#modal-status').html('<i class="fa fa-spinner fa-spin"></i>');
        $('#modal-detail-body').html('<tr><td colspan="4" class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat data...</td></tr>');
        $('#modalDetailLog').modal('show');

        $.ajax({
            url: '<?= site_url("admin/pengaturan/api_tokens/get_log_detail/") ?>' + logId,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (!res.status) {
                    $('#modal-detail-body').html('<tr><td colspan="4" class="text-center">Gagal memuat data.</td></tr>');
                    return;
                }

                $('#modal-waktu').text(res.waktu);
                $('#modal-total').text(res.total + ' data');
                $('#modal-status').html(res.status_sync === 'success'
                    ? '<span class="label label-success">Berhasil</span>'
                    : '<span class="label label-danger">Gagal</span>');

                var detail = res.detail || [];
                var html = '';
                if (detail.length > 0) {
                    for (var i = 0; i < detail.length; i++) {
                        var item = detail[i];
                        var aksiLabel = item.aksi === 'insert'
                            ? '<span class="label label-primary">Baru</span>'
                            : '<span class="label label-warning">Update</span>';
                        html += '<tr>';
                        html += '<td>' + (i + 1) + '</td>';
                        html += '<td><code>' + (item.nim || '-') + '</code></td>';
                        html += '<td>' + (item.nama || '-') + '</td>';
                        html += '<td class="text-center">' + aksiLabel + '</td>';
                        html += '</tr>';
                    }
                } else {
                    html = '<tr><td colspan="4" class="text-center">Tidak ada detail data.</td></tr>';
                }
                $('#modal-detail-body').html(html);
            },
            error: function() {
                $('#modal-detail-body').html('<tr><td colspan="4" class="text-center">Terjadi kesalahan koneksi.</td></tr>');
            }
        });
    });
});

// ─── Real Per-Page Sync ──────────────────────────────────────────
var SYNC_URLS = {
    start:  '<?= site_url("admin/pengaturan/api_tokens/sync_start/") ?>',
    page:   '<?= site_url("admin/pengaturan/api_tokens/sync_page/") ?>',
    finish: '<?= site_url("admin/pengaturan/api_tokens/sync_finish/") ?>'
};

function startSync(id) {
    $('#modalSync').modal('show');
    updateProgress(0, 'Menghubungi server...', '');
    $('#sync-detail').html('');

    // Step 1: Ambil page 1 + total_pages
    $.ajax({
        url: SYNC_URLS.start + id,
        type: 'POST',
        dataType: 'json',
        timeout: 60000,
        success: function(res) {
            if (!res.status) {
                showError(res.message);
                return;
            }

            var totalPages = res.total_pages;
            var totalInsert = res.insert || 0;
            var totalUpdate = res.update || 0;
            var totalSkip   = res.skip || 0;
            var allDetailLogs = res.detail_logs || [];

            // Log halaman 1
            addSyncLog(1, totalPages, res.insert, res.update);
            updateProgress((1 / totalPages) * 100, 'Halaman 1/' + totalPages + ' selesai', '');

            // Step 2: Loop halaman 2..N
            var current = 2;
            function processNextPage() {
                if (current > totalPages) {
                    finishSync(id, totalPages, totalInsert, totalUpdate, totalSkip, allDetailLogs);
                    return;
                }

                updateProgress(((current - 1) / totalPages) * 100, 'Memproses halaman ' + current + '/' + totalPages + '...', '');

                $.ajax({
                    url: SYNC_URLS.page + id + '/' + current,
                    type: 'POST',
                    dataType: 'json',
                    timeout: 60000,
                    success: function(res) {
                        if (res.status) {
                            totalInsert += res.insert || 0;
                            totalUpdate += res.update || 0;
                            totalSkip   += res.skip || 0;
                            if (res.detail_logs && res.detail_logs.length) {
                                allDetailLogs = allDetailLogs.concat(res.detail_logs);
                            }
                            addSyncLog(current, totalPages, res.insert, res.update);
                            updateProgress((current / totalPages) * 100, 'Halaman ' + current + '/' + totalPages + ' selesai', '');
                        } else {
                            showError('Gagal di halaman ' + current + ': ' + res.message);
                            return;
                        }
                        current++;
                        processNextPage();
                    },
                    error: function() {
                        showError('Koneksi gagal di halaman ' + current);
                    }
                });
            }
            processNextPage();
        },
        error: function() {
            showError('Gagal koneksi ke server');
        }
    });
}

function finishSync(id, totalPages, totalInsert, totalUpdate, totalSkip, detailLogs) {
    updateProgress(95, 'Menyimpan log...', '');

    $.ajax({
        url: SYNC_URLS.finish + id,
        type: 'POST',
        dataType: 'json',
        data: {
            total_pages:  totalPages,
            total_insert: totalInsert,
            total_update: totalUpdate,
            total_skip:   totalSkip,
            detail_data:  JSON.stringify(detailLogs)
        },
        success: function(res) {
            updateProgress(100, res.message || 'Selesai!', 'text-green');
            setTimeout(function() {
                $('#modalSync').modal('hide');
                location.reload();
            }, 2000);
        },
        error: function() {
            updateProgress(100, 'Selesai (log gagal disimpan)', 'text-green');
            setTimeout(function() {
                $('#modalSync').modal('hide');
                location.reload();
            }, 2000);
        }
    });
}

function addSyncLog(page, total, insert, update) {
    var icon = '<i class="fa fa-check text-green"></i>';
    var html = '<div class="sync-log-item">' + icon + ' Hal ' + page + '/' + total + ': <strong>' + insert + ' baru</strong>, <strong>' + update + ' update</strong></div>';
    $('#sync-detail').append(html);
    // Auto-scroll ke bawah
    var el = document.getElementById('sync-detail');
    el.scrollTop = el.scrollHeight;
}

function showError(msg) {
    updateProgress(0, 'Gagal: ' + msg, 'text-red');
    setTimeout(function() {
        $('#modalSync').modal('hide');
        swal('Gagal', msg, 'error');
    }, 2000);
}

function updateProgress(pct, text, colorClass) {
    var $bar = $('.progress-bar');
    $bar.css('width', pct + '%').text(Math.round(pct) + '%');

    if (pct >= 100) {
        $bar.removeClass('progress-bar-danger').addClass('progress-bar-success');
    } else if (pct === 0 && text.indexOf('Gagal') >= 0) {
        $bar.removeClass('progress-bar-success').addClass('progress-bar-danger');
    } else {
        $bar.removeClass('progress-bar-success progress-bar-danger').addClass('progress-bar-info');
    }

    $('#sync-status').text(text).removeClass('text-green text-red').addClass(colorClass);
}
</script>
