<div class="row">
    <div class="col-md-12">
        <!-- Informasi Header Mahasiswa (Model Desain Modern) -->
        <div style="background: #ffffff; border: 1px solid #e5e9f2; border-radius: 14px; box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04); padding: 22px 26px; margin-bottom: 22px;">
            <div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">
                <!-- Kolom Kiri: Identitas Mahasiswa SIAKAD vs Feeder -->
                <div class="col-sm-7 col-xs-12" style="border-right: 1px solid #edf2f7; padding-right: 24px;">
                    <!-- Baris Atas: NIM & Badge Status -->
                    <div style="display: flex; align-items: center; margin-bottom: 16px;">
                        <span style="font-size: 26px; font-weight: 800; color: #0a0e1a; letter-spacing: -0.5px; line-height: 1;">
                            <?= e($nim) ?>
                        </span>
                        <?php if ($siska) : ?>
                            <?= $siska->status === 'A' 
                                ? '<span style="background: #00b050; color: #ffffff; font-size: 13px; font-weight: 600; padding: 4px 14px; border-radius: 50px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(0,176,80,0.22); margin-left: 14px;"><i class="fa fa-check-circle"></i> Mahasiswa Aktif</span>' 
                                : '<span style="background: #94a3b8; color: #ffffff; font-size: 13px; font-weight: 600; padding: 4px 14px; border-radius: 50px; display: inline-flex; align-items: center; gap: 6px; margin-left: 14px;"><i class="fa fa-info-circle"></i> Non-Aktif</span>' ?>
                        <?php else : ?>
                            <span style="background: #ef4444; color: #ffffff; font-size: 13px; font-weight: 600; padding: 4px 14px; border-radius: 50px; display: inline-flex; align-items: center; gap: 6px; margin-left: 14px;"><i class="fa fa-times-circle"></i> Tidak Ada di SIAKAD</span>
                        <?php endif; ?>
                    </div>

                    <!-- Baris Bawah: Dua Kolom Nama Sejajar dengan Pembatas -->
                    <div style="display: flex; align-items: center;">
                        <div style="flex: 1; padding-right: 16px;">
                            <div style="font-size: 13px; font-weight: 500; color: #64748b; margin-bottom: 4px;">Nama di SIAKAD (Lokal)</div>
                            <div style="font-size: 16px; font-weight: 700; color: #0a0e1a; line-height: 1.3;">
                                <?= $siska ? e($siska->nama_mahasiswa) : '<span class="text-danger"><i>(Tidak ditemukan)</i></span>' ?>
                            </div>
                        </div>
                        <div style="width: 1px; height: 38px; background: #e2e8f0; flex-shrink: 0;"></div>
                        <div style="flex: 1; padding-left: 18px;">
                            <div style="font-size: 13px; font-weight: 500; color: #64748b; margin-bottom: 4px;">Nama di Feeder (Web Service)</div>
                            <div style="font-size: 16px; font-weight: 700; color: #0a0e1a; line-height: 1.3;">
                                <?= $feeder_bio ? e($feeder_bio['nama_mahasiswa'] ?? '-') : ($feeder_list ? e($feeder_list['nama_mahasiswa'] ?? '-') : '<span class="text-danger"><i>(Tidak ditemukan)</i></span>') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Skor Persentase & Tombol Sinkron Semua -->
                <div class="col-sm-5 col-xs-12 text-center" style="padding-left: 24px;">
                    <?php if (isset($summary_audit)) : 
                        $status_color = $summary_audit['status_class'] === 'success' ? '#00b050' : ($summary_audit['status_class'] === 'warning' ? '#f59e0b' : '#ef4444');
                        $syncable = $summary_audit['total_syncable'] ?? 0;
                    ?>
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <div style="font-size: 52px; font-weight: 800; color: <?= $status_color ?>; line-height: 1; letter-spacing: -1.5px; margin-bottom: 12px;">
                                <?= e($summary_audit['persen']) ?>%
                            </div>
                            <?php if ($syncable > 0) : ?>
                                <button type="button" class="btn btn-sync-feeder-action" data-nim="<?= e($nim) ?>" data-key="all" style="background: #00b050; border: none; color: #ffffff; padding: 10px 22px; font-size: 14px; font-weight: 600; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(0, 176, 80, 0.28); transition: all 0.2s ease; cursor: pointer;" title="Sinkronkan seluruh data selisih dari Feeder ke SIAKAD">
                                    <i class="fa fa-refresh" style="font-size: 15px;"></i> Sinkronkan Semua ke SIAKAD (<?= $syncable ?>)
                                </button>
                            <?php else : ?>
                                <button type="button" class="btn" disabled style="background: #f1f5f9; border: 1px solid #e2e8f0; color: #00b050; padding: 10px 22px; font-size: 14px; font-weight: 600; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; cursor: default;">
                                    <i class="fa fa-check-circle" style="font-size: 15px;"></i> Semua Data Sudah Sesuai
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tabel Komparasi Side-by-Side Biodata -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" style="font-size: 13px;">
                <thead>
                    <tr class="active">
                        <th width="170">Atribut Biodata</th>
                        <th width="30%">Data di SIAKAD (Lokal)</th>
                        <th width="30%">Data di Feeder (Web Service)</th>
                        <th width="110" class="text-center">Kecocokan</th>
                        <th width="130" class="text-center">Sinkron ke SIAKAD</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($fields as $f) : 
                        $val_siska = trim((string) ($f['siska'] ?? ''));
                        $val_feeder = trim((string) ($f['feeder'] ?? ''));
                        $sama = !empty($f['sama']);
                        $has_feeder_val = !empty($f['feeder_has_val']);
                        $key = $f['key'] ?? '';
                        $is_syncable_field = ($key !== 'nim' && $key !== 'program_studi');
                    ?>
                    <tr class="<?= !$sama ? 'warning' : '' ?>">
                        <td><strong><?= e($f['label']) ?></strong></td>
                        <td><?= e($val_siska !== '' ? $val_siska : '-') ?></td>
                        <td><?= e($val_feeder !== '' ? $val_feeder : '-') ?></td>
                        <td class="text-center">
                            <?php if ($sama) : ?>
                                <span class="label label-success flat"><i class="fa fa-check"></i> Sesuai</span>
                            <?php else : ?>
                                <span class="label label-warning flat"><i class="fa fa-exclamation-triangle"></i> Selisih</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($sama) : ?>
                                <span class="text-success" style="font-size: 11px;"><i class="fa fa-check"></i> Sesuai</span>
                            <?php elseif (!$has_feeder_val) : ?>
                                <span class="text-muted" style="font-size: 11px;" title="Feeder tidak memiliki nilai untuk atribut ini"><i class="fa fa-ban"></i> Feeder Kosong</span>
                            <?php elseif ($is_syncable_field) : ?>
                                <button type="button" class="btn btn-xs btn-primary flat btn-sync-feeder-action" data-nim="<?= e($nim) ?>" data-key="<?= e($key) ?>" title="Perbarui nilai di database SIAKAD menggunakan data dari Feeder">
                                    <i class="fa fa-download"></i> Sinkron
                                </button>
                            <?php else : ?>
                                <span class="text-muted" style="font-size: 11px;">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    (function () {
        // Handle klik tombol sinkron (per field atau all)
        $('.btn-sync-feeder-action').off('click').on('click', function (e) {
            e.preventDefault();
            var btn = $(this);
            var nim = btn.data('nim');
            var key = btn.data('key');
            var originalHtml = btn.html();

            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyinkronkan...');

            var postData = {};
            postData['<?= $this->security->get_csrf_token_name() ?>'] = '<?= $this->security->get_csrf_hash() ?>';
            postData['nim'] = nim;
            postData['field_key'] = key;

            $.ajax({
                url: '<?= site_url("admin/audit/mahasiswa/sinkron_feeder_ke_siska") ?>',
                type: 'POST',
                data: postData,
                dataType: 'json',
                success: function (resp) {
                    if (resp && resp.status) {
                        // Refresh konten modal instan dari cache sesi Feeder (tanpa request ulang ke server Feeder)
                        reloadModalBiodataSync(nim);
                        // Update baris pada tabel utama & pindahkan antar-tab secara instan
                        if (resp.summary && typeof window.updateMahasiswaAuditRow === 'function') {
                            window.updateMahasiswaAuditRow(resp.summary);
                        }
                    } else {
                        alert(resp && resp.msg ? resp.msg : 'Gagal menyinkronkan data.');
                        btn.prop('disabled', false).html(originalHtml);
                    }
                },
                error: function (xhr) {
                    var errorMsg = 'Terjadi kesalahan koneksi server saat menyinkronkan data.';
                    if (xhr && xhr.responseJSON && xhr.responseJSON.msg) {
                        errorMsg = xhr.responseJSON.msg;
                    }
                    alert(errorMsg);
                    btn.prop('disabled', false).html(originalHtml);
                }
            });
        });

        // Fungsi reload modal super cepat menggunakan cache Feeder khusus pasca-sinkronisasi
        function reloadModalBiodataSync(nim) {
            var reloadData = {};
            reloadData['<?= $this->security->get_csrf_token_name() ?>'] = '<?= $this->security->get_csrf_hash() ?>';
            reloadData['nim'] = nim;
            reloadData['from_sync'] = 1;

            $.ajax({
                url: '<?= site_url("admin/audit/mahasiswa/detail_nim") ?>',
                type: 'POST',
                data: reloadData,
                success: function (res) {
                    $('#modal-biodata-content').html(res);
                },
                error: function () {
                    if (typeof bukaModalBiodata === 'function') {
                        bukaModalBiodata(nim);
                    }
                }
            });
        }

        // Sinkronkan status baris di tabel luar saat modal dimuat / di-reload
        <?php if (isset($summary_audit)) : ?>
        if (typeof window.updateMahasiswaAuditRow === 'function') {
            window.updateMahasiswaAuditRow({
                nim: '<?= e($nim) ?>',
                persen: <?= (float) $summary_audit['persen'] ?>,
                status_class: '<?= e($summary_audit['status_class']) ?>',
                sesuai: <?= (int) $summary_audit['sesuai'] ?>,
                selisih: <?= (int) $summary_audit['selisih'] ?>,
                status_keterangan: '<?= e($summary_audit['sesuai']) ?> Sesuai, <?= e($summary_audit['selisih']) ?> Selisih',
                nama_siska: '<?= $siska ? addslashes($siska->nama_mahasiswa) : '' ?>',
                status_siska: '<?= $siska ? ($siska->status === 'A' ? 'Aktif' : 'Non-Aktif') : '-' ?>'
            });
        }
        <?php endif; ?>
    })();
</script>
