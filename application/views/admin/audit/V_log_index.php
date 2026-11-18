<div class="row">
    <div class="col-md-12">

        <!-- 1. Filter Card -->
        <div class="box box-primary flat">
            <div class="box-header with-border">
                <h3 class="box-title" style="font-size: 15px; font-weight: 700;">
                    <i class="fa fa-filter text-primary"></i> Filter Riwayat Log
                </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body" style="padding: 16px 20px 10px;">
                <form method="GET" action="<?= site_url('admin/audit/log') ?>">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label style="font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 5px;">Submodul Audit</label>
                                <select name="submodul" class="form-control select2" style="width: 100%;">
                                    <option value="">-- Semua Submodul --</option>
                                    <option value="mahasiswa" <?= ($filters['submodul'] ?? '') === 'mahasiswa' ? 'selected' : '' ?>>Feeder Mahasiswa (Biodata)</option>
                                    <option value="kelas" <?= ($filters['submodul'] ?? '') === 'kelas' ? 'selected' : '' ?>>Feeder Kelas Kuliah</option>
                                    <option value="kpat" <?= ($filters['submodul'] ?? '') === 'kpat' ? 'selected' : '' ?>>Feeder KPAT (Semester Pendek)</option>
                                    <option value="petikan" <?= ($filters['submodul'] ?? '') === 'petikan' ? 'selected' : '' ?>>Feeder Petikan Nilai</option>
                                    <option value="nilai" <?= ($filters['submodul'] ?? '') === 'nilai' ? 'selected' : '' ?>>Nilai Dosen &amp; KHS</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <div class="form-group">
                                <label style="font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 5px;">Jenis Aksi</label>
                                <select name="aksi" class="form-control select2" style="width: 100%;">
                                    <option value="">-- Semua Aksi --</option>
                                    <option value="SINKRON_BIODATA" <?= ($filters['aksi'] ?? '') === 'SINKRON_BIODATA' ? 'selected' : '' ?>>SINKRON_BIODATA</option>
                                    <option value="UPDATE" <?= ($filters['aksi'] ?? '') === 'UPDATE' ? 'selected' : '' ?>>UPDATE</option>
                                    <option value="DELETE" <?= ($filters['aksi'] ?? '') === 'DELETE' ? 'selected' : '' ?>>DELETE</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label style="font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 5px;">NIM / Identitas Objek</label>
                                <input type="text" name="identitas" class="form-control" placeholder="Contoh: 24010110311 / Kode MK..." value="<?= e($filters['identitas'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <div class="form-group">
                                <label style="font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 5px;">Tgl Mulai</label>
                                <input type="date" name="tgl_mulai" class="form-control" value="<?= e($filters['tgl_mulai'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <div class="form-group">
                                <label style="font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 5px;">Tgl Selesai</label>
                                <input type="date" name="tgl_selesai" class="form-control" value="<?= e($filters['tgl_selesai'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" style="margin-top: 4px; padding-bottom: 6px;">
                        <div class="col-xs-12 text-right">
                            <a href="<?= site_url('admin/audit/log') ?>" class="btn btn-default flat" style="padding: 6px 16px; margin-right: 6px;">
                                <i class="fa fa-undo"></i> Reset Filter
                            </a>
                            <button type="submit" class="btn btn-primary flat" style="padding: 6px 20px; font-weight: 600;">
                                <i class="fa fa-search"></i> Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2. Data Table Card -->
        <div class="box box-solid flat" style="border: 1px solid #E2E8F0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div class="box-header with-border" style="background: #F8FAFC; padding: 12px 18px; border-bottom: 1px solid #CBD5E1;">
                <h3 class="box-title" style="font-size: 14.5px; font-weight: 700; color: #0F172A; vertical-align: middle;">
                    <i class="fa fa-history text-primary" style="margin-right: 6px;"></i> Daftar Riwayat Perubahan Modul Audit
                </h3>
                <span class="label label-primary flat pull-right" style="font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 4px;">
                    Total: <?= number_format($total_rows) ?> Catatan
                </span>
            </div>
            
            <div class="box-body no-padding">
                <div class="table-responsive" style="margin: 0; border: none;">
                    <table class="table table-hover table-striped" style="margin-bottom: 0; font-size: 13px; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #F1F5F9; color: #475569; border-bottom: 2px solid #CBD5E1;">
                                <th style="width: 50px; text-align: center; vertical-align: middle;">NO</th>
                                <th style="width: 160px; vertical-align: middle;">WAKTU</th>
                                <th style="width: 150px; vertical-align: middle;">ADMIN</th>
                                <th style="width: 140px; text-align: center; vertical-align: middle;">SUBMODUL</th>
                                <th style="width: 150px; text-align: center; vertical-align: middle;">AKSI</th>
                                <th style="width: 140px; text-align: center; vertical-align: middle;">IDENTITAS</th>
                                <th style="vertical-align: middle;">DESKRIPSI PERUBAHAN</th>
                                <th style="width: 100px; text-align: center; vertical-align: middle;">RINCIAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logs)) : ?>
                                <tr>
                                    <td colspan="8" style="padding: 50px 20px; text-align: center; background: #FFFFFF;">
                                        <div style="max-width: 480px; margin: 0 auto; color: #64748B;">
                                            <div style="width: 60px; height: 60px; line-height: 60px; background: #F1F5F9; border-radius: 50%; margin: 0 auto 14px; font-size: 26px; color: #94A3B8;">
                                                <i class="fa fa-inbox"></i>
                                            </div>
                                            <h4 style="font-size: 16px; font-weight: 700; color: #1E293B; margin: 0 0 6px;">Belum Ada Riwayat Log</h4>
                                            <p style="font-size: 13px; margin: 0; color: #64748B; line-height: 1.5;">
                                                Tidak ditemukan catatan aktivitas perubahan atau sinkronisasi data pada modul audit sesuai kriteria filter yang dipilih.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else : ?>
                                <?php $no = $offset + 1; foreach ($logs as $l) : ?>
                                <tr>
                                    <td style="text-align: center; color: #64748B; font-weight: 600; vertical-align: middle;"><?= $no++ ?>.</td>
                                    <td style="vertical-align: middle;">
                                        <div style="font-weight: 600; color: #0F172A;"><?= date('d M Y', strtotime($l->created_at)) ?></div>
                                        <small style="color: #64748B;"><i class="fa fa-clock-o"></i> <?= date('H:i:s', strtotime($l->created_at)) ?> WITA</small>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <div style="font-weight: 600; color: #0F3D6E;">
                                            <i class="fa fa-user-circle text-primary"></i> <?= e($l->nama_user ?: 'Admin') ?>
                                        </div>
                                        <small style="color: #94A3B8;"><?= e($l->ip_address ?: '-') ?></small>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <span class="label label-primary flat" style="text-transform: capitalize; padding: 4px 8px; font-size: 11.5px;">
                                            <?= e($l->submodul) ?>
                                        </span>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <?php 
                                            $badge = 'label-info';
                                            if (stripos($l->aksi, 'SINKRON') !== FALSE) $badge = 'label-success';
                                            elseif (stripos($l->aksi, 'DELETE') !== FALSE) $badge = 'label-danger';
                                            elseif (stripos($l->aksi, 'UPDATE') !== FALSE) $badge = 'label-warning';
                                        ?>
                                        <span class="label <?= $badge ?> flat" style="padding: 4px 8px; font-size: 11.5px;"><?= e($l->aksi) ?></span>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <span class="label label-default flat" style="font-size: 12px; font-weight: 700; color: #0F3D6E; background: #F1F5F9; border: 1px solid #CBD5E1; padding: 3px 8px;">
                                            <?= e($l->identitas ?: '-') ?>
                                        </span>
                                    </td>
                                    <td style="vertical-align: middle; color: #334155; line-height: 1.45;">
                                        <?= e($l->deskripsi ?: '-') ?>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <?php if (!empty($l->data_sebelum) || !empty($l->data_sesudah)) : ?>
                                            <button type="button" class="btn btn-xs btn-default flat btn-detail-log" data-id="<?= $l->id ?>" style="border-color: #CBD5E1; color: #0F3D6E; font-weight: 600; padding: 3px 9px;" title="Lihat Nilai Sebelum vs Sesudah">
                                                <i class="fa fa-eye"></i> Detail
                                            </button>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <?php if (!empty($pagination) || $total_rows > 0) : ?>
            <div class="box-footer clearfix" style="background: #F8FAFC; border-top: 1px solid #CBD5E1; padding: 12px 18px;">
                <div class="pull-left" style="margin-top: 5px; color: #64748B; font-size: 12.5px;">
                    Menampilkan <b><?= count($logs) ?></b> dari total <b><?= number_format($total_rows) ?></b> riwayat log
                </div>
                <div class="pull-right">
                    <?= $pagination ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- Modal Detail Log (Diff Perubahan) -->
<div class="modal fade" id="modal-log-detail" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content flat">
            <div class="modal-header" style="background: #0F3D6E; color: #FFFFFF;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #FFFFFF; opacity: 0.85;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" style="font-weight: 700; font-size: 15px;">
                    <i class="fa fa-exchange" style="margin-right: 6px;"></i> Detail Rincian Perubahan Data (Audit Log)
                </h4>
            </div>
            <div class="modal-body" id="modal-log-body" style="padding: 20px;">
                <div class="text-center text-muted" style="padding: 30px;">
                    <i class="fa fa-spinner fa-spin fa-2x"></i> Memuat rincian perubahan...
                </div>
            </div>
            <div class="modal-footer" style="background: #F8FAFC; border-top: 1px solid #E2E8F0;">
                <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    if ($.fn.select2) {
        $('.select2').select2();
    }

    $('.btn-detail-log').on('click', function() {
        var id = $(this).data('id');
        var url = '<?= site_url("admin/audit/log/detail") ?>/' + id;
        $('#modal-log-detail').modal('show');
        $('#modal-log-body').html('<div class="text-center text-muted" style="padding: 30px;"><i class="fa fa-spinner fa-spin fa-2x"></i> Memuat rincian perubahan...</div>');
        
        $.ajax({
            url: url,
            type: 'GET',
            success: function(html) {
                $('#modal-log-body').html(html);
            },
            error: function() {
                $('#modal-log-body').html('<div class="alert alert-danger flat">Gagal memuat rincian log. Silakan coba kembali.</div>');
            }
        });
    });
});
</script>
