<style>
@media (max-width: 767px) {
    .btn-mobile-block {
        display: block !important;
        width: 100% !important;
        margin-left: 0 !important;
        margin-bottom: 8px !important;
        text-align: center;
    }
    .form-mobile-gap {
        margin-bottom: 10px !important;
    }
}
</style>

<?= $this->session->flashdata('pesan'); ?>

<!-- Filter Riwayat -->
<div class="box box-primary flat">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-filter" style="color: #3c8dbc;"></i><strong> Filter Riwayat Pembuatan NIM</strong></h4>
        <div class="box-tools pull-right">
            <a href="<?= site_url('admin/pengaturan/pembuatan_nim_baru') ?>" class="btn btn-xs btn-primary flat"><i class="fa fa-plus"></i> Buat NIM Baru</a>
            <a href="<?= site_url('admin/pengaturan/pembuatan_nim_transfer') ?>" class="btn btn-xs btn-warning flat"><i class="fa fa-exchange"></i> Buat NIM Transfer</a>
        </div>
    </div>
    <div class="box-body">
        <form method="GET" action="<?= site_url('admin/pengaturan/riwayat_pembuatan_nim') ?>" class="form-horizontal">
            <div class="row">
                <div class="col-md-5 col-sm-12 form-mobile-gap">
                    <label>Filter Program Studi:</label>
                    <select name="prodi" class="form-control select2" style="width: 100%;">
                        <option value="0">-- Semua Program Studi --</option>
                        <?php foreach ($prodi as $p): ?>
                            <option value="<?= e($p->kode_program_studi) ?>" <?= ($filter_prodi == $p->kode_program_studi) ? 'selected' : '' ?>>
                                <?= e($p->nama_program_studi) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 col-sm-12 form-mobile-gap">
                    <label>Filter Jalur Pembuatan:</label>
                    <select name="jenis" class="form-control select2" style="width: 100%;">
                        <option value="" <?= ($filter_jenis == '') ? 'selected' : '' ?>>-- Semua Jalur --</option>
                        <option value="baru" <?= ($filter_jenis == 'baru') ? 'selected' : '' ?>>Mahasiswa Baru</option>
                        <option value="transfer" <?= ($filter_jenis == 'transfer') ? 'selected' : '' ?>>Transfer Bumigora</option>
                    </select>
                </div>
                <div class="col-md-4 col-sm-12" style="margin-top: 25px;">
                    <button type="submit" class="btn btn-primary btn-sm flat btn-mobile-block"><i class="fa fa-search"></i> Terapkan Filter</button>
                    <a href="<?= site_url('admin/pengaturan/riwayat_pembuatan_nim') ?>" class="btn btn-default btn-sm flat btn-mobile-block" style="margin-left: 5px;"><i class="fa fa-refresh"></i> Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Riwayat Data -->
<div class="box box-solid flat">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-history" style="color: #00a65a;"></i><strong> Data Riwayat Pembuatan NIM</strong></h4>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover data-table" style="font-size: 13px;">
                <thead>
                    <tr class="bg-gray-active">
                        <th style="width: 40px;" class="text-center">No</th>
                        <th style="width: 130px;">Waktu Dibuat</th>
                        <th>NIM Baru</th>
                        <th>Nama Mahasiswa</th>
                        <th>Program Studi</th>
                        <th class="text-center" style="width: 70px;">Angkatan</th>
                        <th class="text-center" style="width: 110px;">Jalur</th>
                        <th>NIM Asal</th>
                        <th class="text-center" style="width: 80px;">Status</th>
                        <th style="width: 100px;">Admin</th>
                        <th class="text-center" style="width: 90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($riwayat)): ?>
                        <tr>
                            <td colspan="11" class="text-center text-muted" style="padding: 20px;">
                                <em>Belum ada data riwayat pembuatan NIM mahasiswa.</em>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($riwayat as $r): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($r->created_at)) ?></td>
                                <td><strong class="text-primary"><?= e($r->nim) ?></strong></td>
                                <td><strong><?= e($r->nama_mahasiswa) ?></strong></td>
                                <td><?= e($r->nama_program_studi ?? '-') ?></td>
                                <td class="text-center"><span class="label label-default"><?= e($r->tahun_angkatan) ?></span></td>
                                <td class="text-center">
                                    <?php if ($r->jenis == 'transfer'): ?>
                                        <span class="label label-warning flat"><i class="fa fa-exchange"></i> Transfer</span>
                                    <?php else: ?>
                                        <span class="label label-success flat"><i class="fa fa-user-plus"></i> Baru Murni</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($r->nim_asal)): ?>
                                        <span class="text-muted"><i class="fa fa-arrow-right text-orange"></i> <?= e($r->nim_asal) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($r->status_pendaftaran == 'T'): ?>
                                        <span class="badge bg-yellow">Transfer (T)</span>
                                    <?php elseif ($r->status_pendaftaran == 'L'): ?>
                                        <span class="badge bg-blue">Lanjut (L)</span>
                                    <?php else: ?>
                                        <span class="badge bg-green">Baru (B)</span>
                                    <?php endif; ?>
                                </td>
                                <td><small class="text-muted"><i class="fa fa-user"></i> <?= e($r->created_by) ?></small></td>
                                <td class="text-center">
                                    <a href="<?= site_url('admin/akademik/mahasiswa/biodata_mahasiswa/' . rawurlencode($r->nim)) ?>" target="_blank" class="btn btn-xs btn-info flat" title="Lihat Biodata">
                                        <i class="fa fa-eye"></i> Biodata
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

<script>
$(document).ready(function() {
    $('.select2').select2();
});
</script>