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

<!-- Statistik Dashboard -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="fa fa-envelope"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tiket Baru (Open)</span>
                <span class="info-box-number"><?= $stats->open ?></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Menunggu tindak lanjut</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-cogs"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Dalam Proses</span>
                <span class="info-box-number"><?= $stats->in_progress ?></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Sedang ditinjau / dikerjakan</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Terselesaikan (Resolved)</span>
                <span class="info-box-number"><?= $stats->resolved ?></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Menunggu konfirmasi pelapor</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-archive"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Ditutup (Closed)</span>
                <span class="info-box-number"><?= $stats->closed ?></span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Total semua: <?= $stats->total ?> tiket</span>
            </div>
        </div>
    </div>
</div>

<!-- Filter dan Meja Kerja -->
<div class="box box-solid flat">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-filter text-primary"></i> <strong>Filter Tiket Masuk</strong></h4>
        <div class="box-tools pull-right">
            <a href="<?= site_url('admin/dukungan/tiket/kategori') ?>" class="btn btn-default btn-sm flat">
                <i class="fa fa-tags"></i> Kelola Kategori & Form Dinamis
            </a>
        </div>
    </div>
    <div class="box-body" style="padding-top:15px; padding-bottom:15px;">
        <form method="get" action="<?= site_url('admin/dukungan/tiket/data') ?>">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="form-group" style="margin-bottom:10px;">
                        <label style="font-size:12px; font-weight:600; color:#555;">Status Tiket</label>
                        <select name="status" class="form-control select2" style="width:100%;">
                            <option value="all">Semua Status</option>
                            <option value="OPEN" <?= (isset($filter['status']) && $filter['status'] === 'OPEN') ? 'selected' : '' ?>>OPEN (Baru Masuk)</option>
                            <option value="IN_REVIEW" <?= (isset($filter['status']) && $filter['status'] === 'IN_REVIEW') ? 'selected' : '' ?>>IN REVIEW (Ditinjau)</option>
                            <option value="IN_PROGRESS" <?= (isset($filter['status']) && $filter['status'] === 'IN_PROGRESS') ? 'selected' : '' ?>>IN PROGRESS (Dikerjakan)</option>
                            <option value="RESOLVED" <?= (isset($filter['status']) && $filter['status'] === 'RESOLVED') ? 'selected' : '' ?>>RESOLVED (Tuntas)</option>
                            <option value="CLOSED" <?= (isset($filter['status']) && $filter['status'] === 'CLOSED') ? 'selected' : '' ?>>CLOSED (Ditutup)</option>
                            <option value="SUBMITTED" <?= (isset($filter['status']) && $filter['status'] === 'SUBMITTED') ? 'selected' : '' ?>>SUBMITTED (Usulan Baru)</option>
                            <option value="PLANNED" <?= (isset($filter['status']) && $filter['status'] === 'PLANNED') ? 'selected' : '' ?>>PLANNED (Masuk Roadmap)</option>
                            <option value="IN_DEVELOPMENT" <?= (isset($filter['status']) && $filter['status'] === 'IN_DEVELOPMENT') ? 'selected' : '' ?>>IN DEVELOPMENT</option>
                            <option value="RELEASED" <?= (isset($filter['status']) && $filter['status'] === 'RELEASED') ? 'selected' : '' ?>>RELEASED (Rilis)</option>
                            <option value="DECLINED" <?= (isset($filter['status']) && $filter['status'] === 'DECLINED') ? 'selected' : '' ?>>DECLINED (Ditolak)</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="form-group" style="margin-bottom:10px;">
                        <label style="font-size:12px; font-weight:600; color:#555;">Kategori</label>
                        <select name="kategori" class="form-control select2" style="width:100%;">
                            <option value="all">Semua Kategori</option>
                            <?php foreach ($kategori_list as $kat): ?>
                                <option value="<?= $kat->id ?>" <?= (isset($filter['kategori_id']) && $filter['kategori_id'] == $kat->id) ? 'selected' : '' ?>>
                                    [<?= e($kat->kode_prefix) ?>] <?= e($kat->nama_kategori) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6">
                    <div class="form-group" style="margin-bottom:10px;">
                        <label style="font-size:12px; font-weight:600; color:#555;">Urgensi</label>
                        <select name="urgensi" class="form-control" style="width:100%;">
                            <option value="all">Semua Urgensi</option>
                            <option value="rendah" <?= (isset($filter['urgensi']) && $filter['urgensi'] === 'rendah') ? 'selected' : '' ?>>Rendah</option>
                            <option value="sedang" <?= (isset($filter['urgensi']) && $filter['urgensi'] === 'sedang') ? 'selected' : '' ?>>Sedang</option>
                            <option value="tinggi" <?= (isset($filter['urgensi']) && $filter['urgensi'] === 'tinggi') ? 'selected' : '' ?>>Tinggi</option>
                            <option value="mendesak" <?= (isset($filter['urgensi']) && $filter['urgensi'] === 'mendesak') ? 'selected' : '' ?>>Mendesak</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6">
                    <div class="form-group" style="margin-bottom:10px;">
                        <label style="font-size:12px; font-weight:600; color:#555;">Role Pelapor</label>
                        <select name="user_type" class="form-control" style="width:100%;">
                            <option value="all">Semua Role</option>
                            <option value="dosen" <?= (isset($filter['user_type']) && $filter['user_type'] === 'dosen') ? 'selected' : '' ?>>Dosen</option>
                            <option value="mahasiswa" <?= (isset($filter['user_type']) && $filter['user_type'] === 'mahasiswa') ? 'selected' : '' ?>>Mahasiswa</option>
                            <option value="admin" <?= (isset($filter['user_type']) && $filter['user_type'] === 'admin') ? 'selected' : '' ?>>Admin / Staff</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2 col-sm-12">
                    <div class="form-group" style="margin-bottom:10px;">
                        <label style="font-size:12px; font-weight:600; color:#555;">Aksi</label>
                        <div style="display:flex; gap:5px;">
                            <button type="submit" class="btn btn-primary flat" style="flex:1;"><i class="fa fa-filter"></i> Filter</button>
                            <a href="<?= site_url('admin/dukungan/tiket/data') ?>" class="btn btn-default flat" title="Reset Filter"><i class="fa fa-refresh"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top:6px;">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" name="keyword" class="form-control" placeholder="Cari berdasarkan Nomor Tiket, Judul Kendala, Nama Pelapor, atau Kata Kunci..." value="<?= e(isset($filter['keyword']) ? $filter['keyword'] : '') ?>">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Daftar Tiket -->
<div class="box box-solid flat">
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped demo-table">
                <thead>
                    <tr>
                        <th width="4%" style="text-align: center; white-space: nowrap;">NO.</th>
                        <th width="14%" style="white-space: nowrap;">NOMOR TIKET</th>
                        <th style="min-width: 200px;">JUDUL KENDALA / USULAN</th>
                        <th width="15%" style="white-space: nowrap;">KATEGORI</th>
                        <th width="13%" style="min-width: 130px;">PELAPOR</th>
                        <th width="8%" style="text-align: center; white-space: nowrap;">URGENSI</th>
                        <th width="10%" style="text-align: center; white-space: nowrap;">STATUS</th>
                        <th width="11%" style="text-align: center; white-space: nowrap;">TANGGAL</th>
                        <th width="7%" style="text-align: center; white-space: nowrap;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($tiket_list)): ?>
                    <tr>
                        <td colspan="9" align="center" class="text-muted" style="padding:20px;">Belum ada tiket yang sesuai dengan filter pencarian.</td>
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
                                <a href="<?= site_url('admin/dukungan/tiket/detail/' . $row->id) ?>" style="font-weight:bold;">
                                    <?= e($row->nomor_tiket) ?>
                                </a>
                            </td>
                            <td style="vertical-align: middle;">
                                <strong><?= e($row->judul) ?></strong>
                                <?php if (!empty($row->catatan_internal)): ?>
                                    <br><small class="text-danger"><i class="fa fa-lock"></i> Ada Catatan Internal Admin</small>
                                <?php endif; ?>
                            </td>
                            <td style="white-space: nowrap; vertical-align: middle;"><span class="label label-default"><?= e($row->nama_kategori) ?></span></td>
                            <td style="vertical-align: middle;">
                                <strong><?= e($row->user_nama) ?></strong>
                                <br><small class="text-muted"><span class="badge bg-gray"><?= strtoupper($row->user_type) ?></span></small>
                            </td>
                            <td align="center" style="white-space: nowrap; vertical-align: middle;"><span class="label <?= $badge_urgensi ?>"><?= strtoupper($row->urgensi) ?></span></td>
                            <td align="center" style="white-space: nowrap; vertical-align: middle;"><span class="label <?= $badge_status ?>"><?= str_replace('_', ' ', $row->status) ?></span></td>
                            <td align="center" style="white-space: nowrap; vertical-align: middle;"><small><?= date('d/m/Y H:i', strtotime($row->created_at)) ?></small></td>
                            <td align="center" style="white-space: nowrap; vertical-align: middle;">
                                <a href="<?= site_url('admin/dukungan/tiket/detail/' . $row->id) ?>" class="btn btn-primary btn-xs flat" title="Buka Meja Kerja Tiket">
                                    <i class="fa fa-folder-open"></i> Kelola
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
