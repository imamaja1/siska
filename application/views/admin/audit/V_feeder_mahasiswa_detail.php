<div class="row">
    <div class="col-md-12">
        <!-- Informasi Header Singkat -->
        <div class="callout callout-info flat" style="margin-bottom: 15px;">
            <div class="row">
                <div class="col-sm-6">
                    <strong>NIM:</strong> <?= e($nim) ?><br>
                    <strong>Nama di SISKA:</strong> <?= $siska ? e($siska->nama_mahasiswa) : '<span class="text-danger"><i>(Tidak ditemukan di SISKA)</i></span>' ?>
                </div>
                <div class="col-sm-6">
                    <strong>Status di SISKA:</strong> <?= $siska ? ($siska->status === 'A' ? '<span class="label label-success">Aktif</span>' : '<span class="label label-default">Non-Aktif</span>') : '-' ?><br>
                    <strong>Nama di Feeder:</strong> <?= $feeder_bio ? e($feeder_bio['nama_mahasiswa'] ?? '-') : ($feeder_list ? e($feeder_list['nama_mahasiswa'] ?? '-') : '<span class="text-danger"><i>(Tidak ditemukan di Feeder)</i></span>') ?>
                </div>
            </div>
        </div>

        <!-- Tabel Komparasi Side-by-Side Biodata -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped" style="font-size: 13px;">
                <thead>
                    <tr class="active">
                        <th width="180">Atribut Biodata</th>
                        <th width="35%">Data di SISKA (Lokal)</th>
                        <th width="35%">Data di Feeder (Web Service)</th>
                        <th width="15%" class="text-center">Kecocokan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($fields as $f) : 
                        $val_siska = trim((string) $f['siska']);
                        $val_feeder = trim((string) $f['feeder']);

                        $sama = FALSE;
                        if ($val_siska === '-' && $val_feeder === '-') {
                            $sama = TRUE;
                        } elseif ($val_siska !== '-' && $val_feeder !== '-') {
                            // Normalisasi sederhana pembanding (case insensitive & spasi)
                            $clean_s = strtoupper(preg_replace('/\s+/', ' ', $val_siska));
                            $clean_f = strtoupper(preg_replace('/\s+/', ' ', $val_feeder));
                            $sama = ($clean_s === $clean_f);

                            // Jika atribut NISN, toleransi perbedaan leading zeros (misal 84926727 vs 0084926727)
                            if (!$sama && $f['label'] === 'NISN') {
                                if (ltrim($clean_s, '0') !== '' && ltrim($clean_s, '0') === ltrim($clean_f, '0')) {
                                    $sama = TRUE;
                                }
                            }

                            // Jika atribut Tanggal Lahir, pastikan pencocokan tanggal valid
                            if (!$sama && $f['label'] === 'Tanggal Lahir') {
                                $ts_s = strtotime($clean_s);
                                $ts_f = strtotime($clean_f);
                                if ($ts_s && $ts_f && date('Y-m-d', $ts_s) === date('Y-m-d', $ts_f)) {
                                    $sama = TRUE;
                                }
                            }
                        }
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
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
