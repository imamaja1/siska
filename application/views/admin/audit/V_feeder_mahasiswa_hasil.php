<div class="col-md-12">
    <!-- Kotak Hasil Audit -->
    <div class="box box-primary flat">
        <div class="box-header with-border">
            <h4>
                <i class="fa fa-table"></i> Hasil Komparasi NIM &amp; Mahasiswa
                <span class="text-muted" style="font-size:14px; font-weight:normal;">
                    | Angkatan: <b><?= e($angkatan_4digit) ?></b>
                    <?php if (!empty($prodi_name)) : ?> | Program Studi: <b><?= e($prodi_name) ?></b><?php endif; ?>
                </span>
            </h4>
        </div>
        <div class="box-body">
            <?php if (!empty($summary['feeder_error'])) : ?>
                <div class="callout callout-warning flat">
                    <p><i class="fa fa-exclamation-triangle"></i> <b>Catatan Feeder:</b> <?= e($summary['feeder_error']) ?> (Menampilkan data lokal SIAKAD)</p>
                </div>
            <?php endif; ?>
            <!-- Filter Kategori Tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#tab-mhs-semua" aria-controls="tab-mhs-semua" role="tab" data-toggle="tab">
                        <i class="fa fa-list"></i> Semua Data <span id="badge-count-semua">(<?= count($rows) ?>)</span>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab-mhs-cocok" aria-controls="tab-mhs-cocok" role="tab" data-toggle="tab">
                        <i class="fa fa-check-circle text-green"></i> Sesuai &ge; 90% <span id="badge-count-cocok">(<?= $summary['total_hijau'] ?>)</span>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab-mhs-beda-nama" aria-controls="tab-mhs-beda-nama" role="tab" data-toggle="tab">
                        <i class="fa fa-exclamation-triangle text-yellow"></i> Selisih &lt; 90% <span id="badge-count-beda-nama">(<?= $summary['total_oren'] ?>)</span>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab-mhs-tidak-sesuai" aria-controls="tab-mhs-tidak-sesuai" role="tab" data-toggle="tab">
                        <i class="fa fa-times-circle text-red"></i> Tidak Sesuai <span id="badge-count-tidak-sesuai">(<?= $summary['total_merah'] ?>)</span>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab-mhs-hanya-siska" aria-controls="tab-mhs-hanya-siska" role="tab" data-toggle="tab">
                        <i class="fa fa-exclamation-circle text-muted"></i> Hanya di SIAKAD <span id="badge-count-hanya-siska">(<?= $summary['total_hanya_siska'] ?>)</span>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab-mhs-hanya-feeder" aria-controls="tab-mhs-hanya-feeder" role="tab" data-toggle="tab">
                        <i class="fa fa-info-circle text-blue"></i> Hanya di Feeder <span id="badge-count-hanya-feeder">(<?= $summary['total_hanya_feeder'] ?>)</span>
                    </a>
                </li>
            </ul>

            <div class="tab-content" style="padding-top: 15px;">
                <!-- Tab Semua Data -->
                <div role="tabpanel" class="tab-pane active" id="tab-mhs-semua">
                    <?php renderTabelMahasiswa($rows); ?>
                </div>

                <!-- Tab Sesuai >= 90% -->
                <div role="tabpanel" class="tab-pane" id="tab-mhs-cocok">
                    <?php 
                    $cocok_rows = array_filter($rows, function($r) { return $r->status_class === 'success'; });
                    renderTabelMahasiswa($cocok_rows); 
                    ?>
                </div>

                <!-- Tab Selisih < 90% -->
                <div role="tabpanel" class="tab-pane" id="tab-mhs-beda-nama">
                    <?php 
                    $beda_nama_rows = array_filter($rows, function($r) { return $r->status_class === 'warning'; });
                    renderTabelMahasiswa($beda_nama_rows); 
                    ?>
                </div>

                <!-- Tab Tidak Sesuai / Selisih Berat -->
                <div role="tabpanel" class="tab-pane" id="tab-mhs-tidak-sesuai">
                    <?php 
                    $tidak_sesuai_rows = array_filter($rows, function($r) { return $r->status_class === 'danger'; });
                    renderTabelMahasiswa($tidak_sesuai_rows); 
                    ?>
                </div>

                <!-- Tab Hanya di SIAKAD -->
                <div role="tabpanel" class="tab-pane" id="tab-mhs-hanya-siska">
                    <?php 
                    $siska_only_rows = array_filter($rows, function($r) { return $r->in_siska && !$r->in_feeder; });
                    renderTabelMahasiswa($siska_only_rows); 
                    ?>
                </div>

                <!-- Tab Hanya di Feeder -->
                <div role="tabpanel" class="tab-pane" id="tab-mhs-hanya-feeder">
                    <?php 
                    $feeder_only_rows = array_filter($rows, function($r) { return !$r->in_siska && $r->in_feeder; });
                    renderTabelMahasiswa($feeder_only_rows); 
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * Helper render tabel mahasiswa agar rapi dan konsisten antar tab
 */
function renderTabelMahasiswa($data_rows) {
?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover tbl-audit-mahasiswa" style="font-size: 13px;">
            <thead>
                <tr class="active">
                    <th width="40" class="text-center">No</th>
                    <th width="110">NIM</th>
                    <th>Nama Mahasiswa (SIAKAD)</th>
                    <th>Nama Mahasiswa (Feeder)</th>
                    <th>Program Studi</th>
                    <th width="100" class="text-center">Status Kuliah</th>
                    <th width="120" class="text-center">Status Audit</th>
                    <th width="100" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($data_rows as $row) : ?>
                <tr data-nim="<?= e($row->nim) ?>" data-status-class="<?= e($row->status_class) ?>" class="<?= $row->status_class === 'danger' ? 'danger' : ($row->status_class === 'warning' ? 'warning' : '') ?>">
                    <td class="text-center col-no"><?= $no++ ?></td>
                    <td class="col-nim"><b><?= e($row->nim) ?></b></td>
                    <td class="col-nama-siska">
                        <?= $row->in_siska ? e($row->nama_siska) : '<span class="text-muted"><i>(Tidak ada di SIAKAD)</i></span>' ?>
                    </td>
                    <td class="col-nama-feeder">
                        <?= $row->in_feeder ? e($row->nama_feeder) : '<span class="text-muted"><i>(Tidak ada di Feeder)</i></span>' ?>
                    </td>
                    <td class="col-prodi">
                        <small>
                            <b>SIAKAD:</b> <?= e($row->prodi_siska) ?><br>
                            <b>Feeder:</b> <?= e($row->prodi_feeder) ?>
                        </small>
                    </td>
                    <td class="text-center col-status-kuliah">
                        <small>
                            SIAKAD: <span class="label label-default status-siska-text"><?= e($row->status_siska) ?></span><br>
                            Feeder: <span class="label label-default"><?= e($row->status_feeder) ?></span>
                        </small>
                    </td>
                    <td class="text-center col-status-audit">
                        <span class="label label-<?= e($row->status_class) ?> flat badge-persen-audit" style="display:inline-block; padding:5px 9px; font-size:12px; font-weight:bold;">
                            <?= e($row->persentase_audit) ?>%
                        </span>
                        <?php if (!empty($row->status_keterangan)) : ?>
                            <br><small class="text-muted text-keterangan-audit" style="font-size:11px;"><?= e($row->status_keterangan) ?></small>
                        <?php endif; ?>
                    </td>
                    <td class="text-center col-aksi">
                        <button type="button" class="btn btn-xs btn-info flat" onclick="bukaModalBiodata('<?= e($row->nim) ?>')" title="Lihat detail biodata SIAKAD vs Feeder">
                            <i class="fa fa-id-card-o"></i> Lihat NIM
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php
}
?>

<script>
    function initDataTablesMahasiswa() {
        if ($.fn.DataTable) {
            $('.tbl-audit-mahasiswa').each(function () {
                if (!$.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable({
                        "pageLength": 25,
                        "language": {
                            "search": "Cari Mahasiswa:",
                            "lengthMenu": "Tampilkan _MENU_ data",
                            "info": "Menampilkan _START_ s/d _END_ dari _TOTAL_ mahasiswa",
                            "paginate": {
                                "first": "Awal",
                                "last": "Akhir",
                                "next": "Berikutnya",
                                "previous": "Sebelumnya"
                            },
                            "emptyTable": "Tidak ada data mahasiswa pada kategori ini."
                        }
                    });
                }
            });
        }
    }
    initDataTablesMahasiswa();

    /**
     * Update baris mahasiswa dan otomatis pindahkan antar-tab secara real-time
     */
    window.updateMahasiswaAuditRow = function (summary) {
        if (!summary || !summary.nim) return;

        var nim = String(summary.nim);
        var persen = summary.persen;
        var statusClass = summary.status_class; // 'success' (>=90%), 'warning' (50-89%), 'danger' (<50%)
        var statusKeterangan = summary.status_keterangan || (summary.sesuai + ' Sesuai, ' + summary.selisih + ' Selisih');
        var namaSiska = summary.nama_siska || '';
        var statusSiska = summary.status_siska || '';

        // 1. Update pada tab "Semua Data" (#tab-mhs-semua)
        var $rowSemua = $('#tab-mhs-semua tr[data-nim="' + nim + '"]');
        if ($rowSemua.length) {
            $rowSemua.removeClass('danger warning').addClass(statusClass === 'danger' ? 'danger' : (statusClass === 'warning' ? 'warning' : ''));
            $rowSemua.attr('data-status-class', statusClass);

            if (namaSiska) {
                $rowSemua.find('.col-nama-siska').text(namaSiska);
            }
            if (statusSiska) {
                $rowSemua.find('.status-siska-text').text(statusSiska);
            }

            var $badge = $rowSemua.find('.badge-persen-audit');
            $badge.removeClass('label-danger label-warning label-success').addClass('label-' + statusClass).text(persen + '%');
            $rowSemua.find('.text-keterangan-audit').text(statusKeterangan);

            if ($.fn.DataTable && $.fn.DataTable.isDataTable('#tab-mhs-semua .tbl-audit-mahasiswa')) {
                $('#tab-mhs-semua .tbl-audit-mahasiswa').DataTable().row($rowSemua).invalidate().draw(false);
            }
        }

        // 2. Perpindahan tab kategori
        var $rowBeda = $('#tab-mhs-beda-nama tr[data-nim="' + nim + '"]');
        var $rowTidak = $('#tab-mhs-tidak-sesuai tr[data-nim="' + nim + '"]');
        var $rowCocok = $('#tab-mhs-cocok tr[data-nim="' + nim + '"]');

        var targetTabId = (statusClass === 'success') ? '#tab-mhs-cocok' : ((statusClass === 'warning') ? '#tab-mhs-beda-nama' : '#tab-mhs-tidak-sesuai');
        var targetCountId = (statusClass === 'success') ? '#badge-count-cocok' : ((statusClass === 'warning') ? '#badge-count-beda-nama' : '#badge-count-tidak-sesuai');

        function updateCounter(badgeId, delta) {
            var $el = $(badgeId);
            if ($el.length) {
                var current = parseInt($el.text().replace(/\D/g, '')) || 0;
                var updated = Math.max(0, current + delta);
                $el.text('(' + updated + ')');
            }
        }

        // Pindah dari Tab "Selisih < 90%" ke Tab lain (misal "Sesuai >= 90%")
        if ($rowBeda.length && targetTabId !== '#tab-mhs-beda-nama') {
            if ($.fn.DataTable && $.fn.DataTable.isDataTable('#tab-mhs-beda-nama .tbl-audit-mahasiswa')) {
                $('#tab-mhs-beda-nama .tbl-audit-mahasiswa').DataTable().row($rowBeda).remove().draw(false);
            } else {
                $rowBeda.remove();
            }
            updateCounter('#badge-count-beda-nama', -1);

            if ($rowSemua.length && $.fn.DataTable && $.fn.DataTable.isDataTable(targetTabId + ' .tbl-audit-mahasiswa')) {
                var $newTr = $rowSemua.clone();
                $(targetTabId + ' .tbl-audit-mahasiswa').DataTable().row.add($newTr).draw(false);
                updateCounter(targetCountId, +1);
            }
        }
        // Pindah dari Tab "Tidak Sesuai" ke Tab lain
        else if ($rowTidak.length && targetTabId !== '#tab-mhs-tidak-sesuai') {
            if ($.fn.DataTable && $.fn.DataTable.isDataTable('#tab-mhs-tidak-sesuai .tbl-audit-mahasiswa')) {
                $('#tab-mhs-tidak-sesuai .tbl-audit-mahasiswa').DataTable().row($rowTidak).remove().draw(false);
            } else {
                $rowTidak.remove();
            }
            updateCounter('#badge-count-tidak-sesuai', -1);

            if ($rowSemua.length && $.fn.DataTable && $.fn.DataTable.isDataTable(targetTabId + ' .tbl-audit-mahasiswa')) {
                var $newTr = $rowSemua.clone();
                $(targetTabId + ' .tbl-audit-mahasiswa').DataTable().row.add($newTr).draw(false);
                updateCounter(targetCountId, +1);
            }
        }
        // Jika data tetap di kategori tab yang sama (misal tetap di Selisih, hanya nilainya bertambah)
        else {
            var $currentCategoryRow = $(targetTabId + ' tr[data-nim="' + nim + '"]');
            if ($currentCategoryRow.length) {
                $currentCategoryRow.removeClass('danger warning').addClass(statusClass === 'danger' ? 'danger' : (statusClass === 'warning' ? 'warning' : ''));
                if (namaSiska) $currentCategoryRow.find('.col-nama-siska').text(namaSiska);
                if (statusSiska) $currentCategoryRow.find('.status-siska-text').text(statusSiska);

                var $b = $currentCategoryRow.find('.badge-persen-audit');
                $b.removeClass('label-danger label-warning label-success').addClass('label-' + statusClass).text(persen + '%');
                $currentCategoryRow.find('.text-keterangan-audit').text(statusKeterangan);

                if ($.fn.DataTable && $.fn.DataTable.isDataTable(targetTabId + ' .tbl-audit-mahasiswa')) {
                    $(targetTabId + ' .tbl-audit-mahasiswa').DataTable().row($currentCategoryRow).invalidate().draw(false);
                }
            }
        }

        // Re-index kolom "No" pada tabel yang terdampak
        $('.tbl-audit-mahasiswa').each(function () {
            if ($.fn.DataTable && $.fn.DataTable.isDataTable(this)) {
                var dt = $(this).DataTable();
                dt.rows().every(function (idx) {
                    $(this.node()).find('.col-no').text(idx + 1);
                });
            }
        });
    };
</script>
