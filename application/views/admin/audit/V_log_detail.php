<div class="row" style="margin-bottom: 15px;">
    <div class="col-sm-6">
        <table class="table table-condensed table-borderless" style="font-size: 12.5px; margin-bottom: 0;">
            <tr>
                <td style="width: 120px; font-weight: 700; color: #64748B;">Waktu Aksi:</td>
                <td><i class="fa fa-calendar"></i> <?= date('d M Y, H:i:s', strtotime($log->created_at)) ?></td>
            </tr>
            <tr>
                <td style="font-weight: 700; color: #64748B;">Admin Pelaksana:</td>
                <td><i class="fa fa-user"></i> <b><?= e($log->nama_user ?: 'Admin') ?></b></td>
            </tr>
            <tr>
                <td style="font-weight: 700; color: #64748B;">Submodul:</td>
                <td><span class="label label-primary flat" style="text-transform: capitalize;"><?= e($log->submodul) ?></span></td>
            </tr>
        </table>
    </div>
    <div class="col-sm-6">
        <table class="table table-condensed table-borderless" style="font-size: 12.5px; margin-bottom: 0;">
            <tr>
                <td style="width: 120px; font-weight: 700; color: #64748B;">Jenis Aksi:</td>
                <td><span class="label label-success flat"><?= e($log->aksi) ?></span></td>
            </tr>
            <tr>
                <td style="font-weight: 700; color: #64748B;">Identitas / Target:</td>
                <td><strong style="color: #0F3D6E;"><?= e($log->identitas) ?></strong></td>
            </tr>
            <tr>
                <td style="font-weight: 700; color: #64748B;">IP Address:</td>
                <td><code><?= e($log->ip_address ?: '-') ?></code></td>
            </tr>
        </table>
    </div>
</div>

<?php if (!empty($log->deskripsi)) : ?>
<div class="callout callout-info flat" style="margin-bottom: 15px; padding: 10px 14px; font-size: 12.5px;">
    <i class="fa fa-info-circle"></i> <?= e($log->deskripsi) ?>
</div>
<?php endif; ?>

<h5 style="font-weight: 700; color: #0F172A; margin: 15px 0 10px;">
    <i class="fa fa-columns text-primary"></i> Perbandingan Nilai Sebelum vs Sesudah
</h5>

<?php
    $all_keys = array_unique(array_merge(array_keys($data_sebelum), array_keys($data_sesudah)));
?>

<?php if (empty($all_keys)) : ?>
    <div class="alert alert-warning flat" style="font-size: 12.5px;">
        Tidak ada rincian data nilai sebelum/sesudah yang tercatat.
    </div>
<?php else : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" style="font-size: 12.5px; margin-bottom: 0;">
            <thead>
                <tr style="background: #F1F5F9; color: #475569;">
                    <th style="width: 180px;">Atribut / Field</th>
                    <th style="width: 38%;">Nilai Sebelum (SIAKAD Lama)</th>
                    <th style="width: 38%;">Nilai Sesudah (Diperbarui)</th>
                    <th style="width: 90px; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_keys as $key) : 
                    $sebelum = $data_sebelum[$key] ?? '';
                    $sesudah = $data_sesudah[$key] ?? '';
                    $berubah = ($sebelum != $sesudah);
                ?>
                <tr class="<?= $berubah ? 'warning' : '' ?>">
                    <td><strong><?= e($key) ?></strong></td>
                    <td style="<?= $berubah ? 'background-color: #FFFBEB; color: #B45309;' : '' ?>">
                        <?= e($sebelum !== '' && $sebelum !== null ? (string)$sebelum : '-') ?>
                    </td>
                    <td style="<?= $berubah ? 'background-color: #F0FDF4; color: #15803D; font-weight: 600;' : '' ?>">
                        <?= e($sesudah !== '' && $sesudah !== null ? (string)$sesudah : '-') ?>
                    </td>
                    <td style="text-align: center;">
                        <?php if ($berubah) : ?>
                            <span class="label label-warning flat"><i class="fa fa-pencil"></i> Diubah</span>
                        <?php else : ?>
                            <span class="label label-default flat">Sama</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
