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
                    <p><i class="fa fa-exclamation-triangle"></i> <b>Catatan Feeder:</b> <?= e($summary['feeder_error']) ?> (Menampilkan data lokal SISKA)</p>
                </div>
            <?php endif; ?>
            <!-- Filter Kategori Tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#tab-mhs-semua" aria-controls="tab-mhs-semua" role="tab" data-toggle="tab">
                        <i class="fa fa-list"></i> Semua Data (<?= count($rows) ?>)
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab-mhs-cocok" aria-controls="tab-mhs-cocok" role="tab" data-toggle="tab">
                        <i class="fa fa-check-circle text-green"></i> Cocok (<?= $summary['total_cocok'] ?>)
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab-mhs-hanya-siska" aria-controls="tab-mhs-hanya-siska" role="tab" data-toggle="tab">
                        <i class="fa fa-exclamation-circle text-red"></i> Hanya di SISKA (<?= $summary['total_hanya_siska'] ?>)
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab-mhs-hanya-feeder" aria-controls="tab-mhs-hanya-feeder" role="tab" data-toggle="tab">
                        <i class="fa fa-info-circle text-blue"></i> Hanya di Feeder (<?= $summary['total_hanya_feeder'] ?>)
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab-mhs-beda-nama" aria-controls="tab-mhs-beda-nama" role="tab" data-toggle="tab">
                        <i class="fa fa-exchange text-yellow"></i> Nama Berbeda (<?= $summary['total_beda_nama'] ?>)
                    </a>
                </li>
            </ul>

            <div class="tab-content" style="padding-top: 15px;">
                <!-- Tab Semua Data -->
                <div role="tabpanel" class="tab-pane active" id="tab-mhs-semua">
                    <?php renderTabelMahasiswa($rows); ?>
                </div>

                <!-- Tab Cocok -->
                <div role="tabpanel" class="tab-pane" id="tab-mhs-cocok">
                    <?php 
                    $cocok_rows = array_filter($rows, function($r) { return $r->status === 'Cocok'; });
                    renderTabelMahasiswa($cocok_rows); 
                    ?>
                </div>

                <!-- Tab Hanya di SISKA -->
                <div role="tabpanel" class="tab-pane" id="tab-mhs-hanya-siska">
                    <?php 
                    $siska_only_rows = array_filter($rows, function($r) { return $r->status === 'Hanya di SISKA'; });
                    renderTabelMahasiswa($siska_only_rows); 
                    ?>
                </div>

                <!-- Tab Hanya di Feeder -->
                <div role="tabpanel" class="tab-pane" id="tab-mhs-hanya-feeder">
                    <?php 
                    $feeder_only_rows = array_filter($rows, function($r) { return $r->status === 'Hanya di Feeder'; });
                    renderTabelMahasiswa($feeder_only_rows); 
                    ?>
                </div>

                <!-- Tab Nama Berbeda -->
                <div role="tabpanel" class="tab-pane" id="tab-mhs-beda-nama">
                    <?php 
                    $beda_nama_rows = array_filter($rows, function($r) { return $r->status === 'Nama Berbeda'; });
                    renderTabelMahasiswa($beda_nama_rows); 
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
    if (empty($data_rows)) {
        echo '<div class="callout callout-info flat"><p><i class="fa fa-info-circle"></i> Tidak ada data mahasiswa pada kategori ini.</p></div>';
        return;
    }
?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover tbl-audit-mahasiswa" style="font-size: 13px;">
            <thead>
                <tr class="active">
                    <th width="40" class="text-center">No</th>
                    <th width="110">NIM</th>
                    <th>Nama Mahasiswa (SISKA)</th>
                    <th>Nama Mahasiswa (Feeder)</th>
                    <th>Program Studi</th>
                    <th width="100" class="text-center">Status Kuliah</th>
                    <th width="120" class="text-center">Status Audit</th>
                    <th width="100" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($data_rows as $row) : ?>
                <tr class="<?= $row->status_class === 'danger' ? 'danger' : ($row->status_class === 'warning' ? 'warning' : '') ?>">
                    <td class="text-center"><?= $no++ ?></td>
                    <td><b><?= e($row->nim) ?></b></td>
                    <td>
                        <?= $row->in_siska ? e($row->nama_siska) : '<span class="text-muted"><i>(Tidak ada di SISKA)</i></span>' ?>
                    </td>
                    <td>
                        <?= $row->in_feeder ? e($row->nama_feeder) : '<span class="text-muted"><i>(Tidak ada di Feeder)</i></span>' ?>
                    </td>
                    <td>
                        <small>
                            <b>SISKA:</b> <?= e($row->prodi_siska) ?><br>
                            <b>Feeder:</b> <?= e($row->prodi_feeder) ?>
                        </small>
                    </td>
                    <td class="text-center">
                        <small>
                            SISKA: <span class="label label-default"><?= e($row->status_siska) ?></span><br>
                            Feeder: <span class="label label-default"><?= e($row->status_feeder) ?></span>
                        </small>
                    </td>
                    <td class="text-center">
                        <span class="label label-<?= e($row->status_class) ?> flat" style="display:inline-block; padding:5px 8px; font-size:11px;">
                            <?= e($row->status) ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-xs btn-info flat" onclick="bukaModalBiodata('<?= e($row->nim) ?>')" title="Lihat detail biodata SISKA vs Feeder">
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
                            }
                        }
                    });
                }
            });
        }
    }
    initDataTablesMahasiswa();
</script>
