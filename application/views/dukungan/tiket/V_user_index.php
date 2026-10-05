<style>
@media (max-width: 767px) {
    .box-header.with-border {
        display: flex !important;
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 10px;
    }
    .box-header .box-tools {
        position: static !important;
        float: none !important;
        width: 100%;
    }
    .box-header .box-tools .btn {
        display: block;
        width: 100%;
        text-align: center;
    }
    .info-box {
        min-height: 70px;
        margin-bottom: 10px;
    }
    .info-box-icon {
        height: 70px;
        line-height: 70px;
        width: 60px;
        font-size: 28px;
    }
    .info-box-content {
        margin-left: 60px;
        padding: 5px 10px;
    }
    .info-box-number {
        font-size: 18px;
    }
    .info-box-text {
        font-size: 12px;
    }
}
</style>

<?= $this->session->flashdata('info') ?>

<!-- Ringkasan Tiket Saya -->
<div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="fa fa-envelope"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tiket Terbuka (Open)</span>
                <span class="info-box-number"><?= $stats->open ?></span>
                <span class="progress-description">Menunggu respon Admin</span>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-cogs"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Dalam Penanganan</span>
                <span class="info-box-number"><?= $stats->in_progress ?></span>
                <span class="progress-description">Sedang dikerjakan</span>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Selesai / Tuntas</span>
                <span class="info-box-number"><?= $stats->selesai ?></span>
                <span class="progress-description">Total semua: <?= $stats->total ?> tiket</span>
            </div>
        </div>
    </div>
</div>

<div class="box box-solid flat">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-ticket text-primary"></i> <strong>Daftar Tiket Pengajuan Saya</strong></h4>
        <div class="box-tools pull-right">
            <a href="<?= site_url('dukungan/tiket/buat') ?>" class="btn btn-primary btn-sm flat">
                <i class="fa fa-plus-circle"></i> Buat Tiket Baru
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped demo-table">
                <thead>
                    <tr>
                        <th width="4%" style="text-align: center; white-space: nowrap;">NO.</th>
                        <th width="15%" style="white-space: nowrap;">NOMOR TIKET</th>
                        <th style="min-width: 180px;">JUDUL KENDALA / USULAN</th>
                        <th width="18%" style="white-space: nowrap;">KATEGORI</th>
                        <th width="10%" style="text-align: center; white-space: nowrap;">URGENSI</th>
                        <th width="12%" style="text-align: center; white-space: nowrap;">STATUS</th>
                        <th width="14%" style="text-align: center; white-space: nowrap;">TANGGAL DIAJUKAN</th>
                        <th width="8%" style="text-align: center; white-space: nowrap;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($tiket_list)): ?>
                    <tr>
                        <td colspan="8" align="center" class="text-muted" style="padding:25px;">
                            <i class="fa fa-info-circle fa-2x text-muted" style="display:block; margin-bottom:10px;"></i>
                            Anda belum pernah mengajukan tiket komplain atau bantuan sistem.<br>
                            <a href="<?= site_url('dukungan/tiket/buat') ?>" class="btn btn-primary btn-sm flat" style="margin-top:10px;">
                                <i class="fa fa-plus-circle"></i> Buat Tiket Pertama Anda
                            </a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $i = 1; foreach ($tiket_list as $row): ?>
                        <?php
                            $badge_status = 'label-default';
                            if (in_array($row->status, ['OPEN', 'SUBMITTED'])) $badge_status = 'label-danger';
                            elseif (in_array($row->status, ['IN_REVIEW', 'UNDER_REVIEW'])) $badge_status = 'label-warning';
                            elseif (in_array($row->status, ['IN_PROGRESS', 'PLANNED', 'IN_DEVELOPMENT'])) $badge_status = 'label-primary';
                            elseif ($row->status === 'RESOLVED') $badge_status = 'label-info';
                            elseif (in_array($row->status, ['CLOSED', 'RELEASED'])) $badge_status = 'label-success';
                            elseif ($row->status === 'DECLINED') $badge_status = 'label-danger';

                            $badge_urgensi = 'label-default';
                            if ($row->urgensi === 'mendesak') $badge_urgensi = 'label-danger';
                            elseif ($row->urgensi === 'tinggi') $badge_urgensi = 'label-warning';
                            elseif ($row->urgensi === 'sedang') $badge_urgensi = 'label-primary';
                        ?>
                        <tr>
                            <td align="center" style="white-space: nowrap; vertical-align: middle;"><?= $i++ ?>.</td>
                            <td style="white-space: nowrap; vertical-align: middle;">
                                <a href="<?= site_url('dukungan/tiket/detail/' . $row->id) ?>" style="font-weight:bold;">
                                    <?= e($row->nomor_tiket) ?>
                                </a>
                            </td>
                            <td style="vertical-align: middle;"><strong><?= e($row->judul) ?></strong></td>
                            <td style="white-space: nowrap; vertical-align: middle;"><span class="label label-default"><?= e($row->nama_kategori) ?></span></td>
                            <td align="center" style="white-space: nowrap; vertical-align: middle;"><span class="label <?= $badge_urgensi ?>"><?= strtoupper($row->urgensi) ?></span></td>
                            <td align="center" style="white-space: nowrap; vertical-align: middle;"><span class="label <?= $badge_status ?>"><?= str_replace('_', ' ', $row->status) ?></span></td>
                            <td align="center" style="white-space: nowrap; vertical-align: middle;"><small><?= date('d/m/Y H:i', strtotime($row->created_at)) ?></small></td>
                            <td align="center" style="white-space: nowrap; vertical-align: middle;">
                                <a href="<?= site_url('dukungan/tiket/detail/' . $row->id) ?>" class="btn btn-info btn-xs flat">
                                    <i class="fa fa-comments"></i> Buka Chat
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
