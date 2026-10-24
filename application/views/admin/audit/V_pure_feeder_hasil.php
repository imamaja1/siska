<div class="col-md-12">
    <div class="box box-primary flat">
        <div class="box-header">
            <h4><i class="fa fa-code"></i> PURE FEEDER (DATA MENTAH)</h4>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table">
                        <tr>
                            <td width="180"><strong>NAMA</strong></td>
                            <td width="10"><strong>:</strong></td>
                            <td><?= e($header['nama_mahasiswa'] !== '' ? $header['nama_mahasiswa'] : '-') ?></td>
                        </tr>
                        <tr>
                            <td><strong>NIM</strong></td>
                            <td><strong>:</strong></td>
                            <td><?= e($header['nim']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>ANGKATAN</strong></td>
                            <td><strong>:</strong></td>
                            <td><?= e($header['angkatan'] !== '' ? $header['angkatan'] : '-') ?></td>
                        </tr>
                        <tr>
                            <td><strong>PROGRAM STUDI</strong></td>
                            <td><strong>:</strong></td>
                            <td><?= e($header['nama_program_studi'] !== '' ? $header['nama_program_studi'] : '-') ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Data Mentah Detail -->
            <h4><i class="fa fa-list"></i> Detail Nilai (GetDetailNilaiPerkuliahanKelas)</h4>
            <?php if (empty($raw_rows)) : ?>
                <div class="callout callout-info flat"><p>Tidak ada data mentah detail Feeder.</p></div>
            <?php else : ?>
            <?php $raw_keys = array_keys($raw_rows[0]); ?>
            <p class="text-muted"><i class="fa fa-info-circle"></i> <?= count($raw_rows) ?> baris, <?= count($raw_keys) ?> field, persis seperti respons Feeder.</p>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="font-size:12px;">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <?php foreach ($raw_keys as $k) : ?>
                                <th><?= e($k) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($raw_rows as $raw) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <?php foreach ($raw_keys as $k) : ?>
                                <td><?= e(isset($raw[$k]) ? (string) $raw[$k] : '') ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-default btn-sm flat" data-toggle="collapse" data-target="#raw-json">
                <i class="fa fa-code"></i> Tampilkan JSON Mentah (Detail)
            </button>
            <div class="collapse" id="raw-json" style="margin-top:10px;">
                <pre style="max-height:500px;overflow:auto;"><?= e(json_encode($raw_rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?></pre>
            </div>
            <?php endif; ?>

            <hr>

            <!-- Data Mentah Transkrip -->
            <h4><i class="fa fa-graduation-cap"></i> Transkrip Mahasiswa (GetTranskripMahasiswa)</h4>
            <?php if (!empty($transkrip_error)) : ?>
                <div class="callout callout-warning flat"><p>Catatan transkrip: <?= e($transkrip_error) ?></p></div>
            <?php endif; ?>
            <?php if (empty($transkrip_raw)) : ?>
                <div class="callout callout-info flat"><p>Tidak ada data transkrip Feeder.</p></div>
            <?php else : ?>
            <?php $tr_keys = array_keys($transkrip_raw[0]); ?>
            <p class="text-muted"><i class="fa fa-info-circle"></i> <?= count($transkrip_raw) ?> baris, <?= count($tr_keys) ?> field.</p>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="font-size:12px;">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <?php foreach ($tr_keys as $k) : ?>
                                <th><?= e($k) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($transkrip_raw as $raw) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <?php foreach ($tr_keys as $k) : ?>
                                <td><?= e(isset($raw[$k]) ? (string) $raw[$k] : '') ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-default btn-sm flat" data-toggle="collapse" data-target="#raw-json-tr">
                <i class="fa fa-code"></i> Tampilkan JSON Mentah (Transkrip)
            </button>
            <div class="collapse" id="raw-json-tr" style="margin-top:10px;">
                <pre style="max-height:500px;overflow:auto;"><?= e(json_encode($transkrip_raw, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?></pre>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
