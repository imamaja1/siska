<style>
.direct-chat-text {
    word-break: break-word;
    overflow-wrap: break-word;
}
@media (max-width: 767px) {
    .detail-toolbar {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 12px !important;
    }
    .detail-header-left {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
    }
    .detail-header-left span {
        margin-left: 0 !important;
    }
    .detail-header-right {
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 8px !important;
        width: 100%;
    }
    .detail-header-right form {
        display: flex !important;
        align-items: center;
        width: 100%;
        gap: 8px;
    }
    .detail-header-right form select {
        flex: 1;
    }
    .detail-header-right .badge {
        text-align: center;
        display: block;
        width: 100%;
    }
    .direct-chat-messages {
        height: 350px !important;
        padding: 10px !important;
    }
    .chat-footer-action {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 10px !important;
    }
    .chat-footer-action .btn-submit-chat {
        width: 100%;
    }
}
</style>

<?= $this->session->flashdata('info') ?>

<!-- Action Toolbar -->
<div class="box box-solid flat">
    <div class="box-body detail-toolbar" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <div class="detail-header-left">
            <a href="<?= site_url('admin/dukungan/tiket/data') ?>" class="btn btn-default btn-sm flat">
                <i class="fa fa-arrow-left"></i> Kembali ke Inbox
            </a>
            <span style="font-size:16px; font-weight:bold; margin-left:10px;">
                Tiket #<?= e($tiket->nomor_tiket) ?>
            </span>
            <span class="label label-primary" style="margin-left:5px; font-size:12px;"><?= e($tiket->nama_kategori) ?></span>
        </div>

        <div class="detail-header-right" style="display:flex; align-items:center; gap:8px;">
            <!-- Status Switcher Modal / Form -->
            <form method="post" action="<?= site_url('admin/dukungan/tiket/ubah_status') ?>" class="form-inline" style="display:inline;">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <input type="hidden" name="tiket_id" value="<?= $tiket->id ?>">
                <label>Ubah Status: &nbsp;</label>
                <select name="status_baru" class="form-control input-sm" onchange="this.form.submit()">
                    <?php if ($tiket->tipe_alur === 'feature'): ?>
                        <option value="SUBMITTED" <?= $tiket->status === 'SUBMITTED' ? 'selected' : '' ?>>SUBMITTED (Diajukan)</option>
                        <option value="UNDER_REVIEW" <?= $tiket->status === 'UNDER_REVIEW' ? 'selected' : '' ?>>UNDER REVIEW (Ditinjau)</option>
                        <option value="PLANNED" <?= $tiket->status === 'PLANNED' ? 'selected' : '' ?>>PLANNED (Masuk Jadwal)</option>
                        <option value="IN_DEVELOPMENT" <?= $tiket->status === 'IN_DEVELOPMENT' ? 'selected' : '' ?>>IN DEVELOPMENT (Dikerjakan)</option>
                        <option value="RELEASED" <?= $tiket->status === 'RELEASED' ? 'selected' : '' ?>>RELEASED (Rilis)</option>
                        <option value="DECLINED" <?= $tiket->status === 'DECLINED' ? 'selected' : '' ?>>DECLINED (Ditolak)</option>
                    <?php else: ?>
                        <option value="OPEN" <?= $tiket->status === 'OPEN' ? 'selected' : '' ?>>OPEN (Baru)</option>
                        <option value="IN_REVIEW" <?= $tiket->status === 'IN_REVIEW' ? 'selected' : '' ?>>IN REVIEW (Ditinjau)</option>
                        <option value="IN_PROGRESS" <?= $tiket->status === 'IN_PROGRESS' ? 'selected' : '' ?>>IN PROGRESS (Dikerjakan)</option>
                        <option value="RESOLVED" <?= $tiket->status === 'RESOLVED' ? 'selected' : '' ?>>RESOLVED (Tuntas)</option>
                        <option value="CLOSED" <?= $tiket->status === 'CLOSED' ? 'selected' : '' ?>>CLOSED (Ditutup)</option>
                    <?php endif; ?>
                </select>
            </form>

            <?php
                $badge_status = 'label-default';
                if (in_array($tiket->status, ['OPEN', 'SUBMITTED'])) $badge_status = 'label-danger';
                elseif (in_array($tiket->status, ['IN_REVIEW', 'UNDER_REVIEW'])) $badge_status = 'label-warning';
                elseif (in_array($tiket->status, ['IN_PROGRESS', 'PLANNED', 'IN_DEVELOPMENT'])) $badge_status = 'label-primary';
                elseif ($tiket->status === 'RESOLVED') $badge_status = 'label-info';
                elseif (in_array($tiket->status, ['CLOSED', 'RELEASED'])) $badge_status = 'label-success';
                elseif ($tiket->status === 'DECLINED') $badge_status = 'label-danger';
            ?>
            <span class="badge <?= ($badge_status === 'label-danger' ? 'bg-red' : ($badge_status === 'label-success' ? 'bg-green' : ($badge_status === 'label-warning' ? 'bg-yellow' : 'bg-blue'))) ?>" style="padding:6px 12px; font-size:12px;">
                STATUS: <?= str_replace('_', ' ', $tiket->status) ?>
            </span>
        </div>
    </div>
</div>

<div class="row">
    <!-- Kolom Kiri: Detail Tiket & Catatan Internal -->
    <div class="col-md-5">
        <!-- Informasi Pelapor & Tiket -->
        <div class="box box-primary flat">
            <div class="box-header with-border">
                <h4 class="box-title"><i class="fa fa-info-circle text-primary"></i> <strong>Rincian Pengajuan</strong></h4>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped" style="margin-bottom:15px;">
                    <tr>
                        <th width="35%">Pelapor</th>
                        <td><strong><?= e($tiket->user_nama) ?></strong> (<?= strtoupper($tiket->user_type) ?>)</td>
                    </tr>
                    <tr>
                        <th>Email / Kontak</th>
                        <td><?= e($tiket->user_email ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Urgensi</th>
                        <td>
                            <?php
                                $urg_badge = ($tiket->urgensi === 'mendesak') ? 'label-danger' : (($tiket->urgensi === 'tinggi') ? 'label-warning' : 'label-primary');
                            ?>
                            <span class="label <?= $urg_badge ?>"><?= strtoupper($tiket->urgensi) ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th>Waktu Masuk</th>
                        <td><?= date('d M Y, H:i', strtotime($tiket->created_at)) ?></td>
                    </tr>
                </table>

                <div class="form-group">
                    <label>Judul Tiket:</label>
                    <div style="font-size:15px; font-weight:bold; color:#0073b7;"><?= e($tiket->judul) ?></div>
                </div>

                <div class="form-group">
                    <label>Deskripsi Kendala / Usulan:</label>
                    <div style="background:#f9f9f9; padding:12px; border-radius:4px; border:1px solid #e1e4e8; white-space:pre-wrap;"><?= e($tiket->deskripsi) ?></div>
                </div>

                <!-- Custom Dynamic Form Fields Data -->
                <?php
                    $custom_data = json_decode($tiket->custom_fields_data, true);
                    if (!empty($custom_data)):
                ?>
                    <hr>
                    <label><i class="fa fa-list-alt text-muted"></i> Data Form Dinamis Kategori:</label>
                    <table class="table table-condensed table-bordered" style="background:#fafafa;">
                        <?php foreach ($custom_data as $key => $fld): ?>
                            <tr>
                                <th width="40%" style="color:#555;"><?= e(isset($fld['label']) ? $fld['label'] : $key) ?></th>
                                <td><?= nl2br(e(isset($fld['value']) ? $fld['value'] : '-')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>

                <!-- Lampiran Bukti File / Gambar -->
                <?php if (!empty($tiket->lampiran)): ?>
                    <hr>
                    <label><i class="fa fa-paperclip"></i> Lampiran Awal Pelapor:</label>
                    <div style="margin-top:5px;">
                        <?php 
                            $ext = strtolower(pathinfo($tiket->lampiran, PATHINFO_EXTENSION));
                            $file_url = base_url('assets/uploads/tiket/' . $tiket->lampiran);
                        ?>
                        <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                            <a href="<?= $file_url ?>" target="_blank">
                                <img src="<?= $file_url ?>" alt="Lampiran" class="img-responsive img-thumbnail" style="max-height:220px;">
                            </a>
                        <?php else: ?>
                            <a href="<?= $file_url ?>" target="_blank" class="btn btn-default btn-sm flat">
                                <i class="fa fa-download"></i> Unduh Berkas Lampiran (<?= strtoupper($ext) ?>)
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Catatan Internal Super Admin (Hanya terlihat oleh Super Admin) -->
        <div class="box box-warning flat">
            <div class="box-header with-border">
                <h4 class="box-title text-yellow"><i class="fa fa-lock"></i> <strong>Catatan Internal Admin</strong></h4>
                <small class="pull-right text-muted">Privat (Pelapor tidak bisa melihat)</small>
            </div>
            <div class="box-body">
                <form method="post" action="<?= site_url('admin/dukungan/tiket/simpan_catatan_internal') ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <input type="hidden" name="tiket_id" value="<?= $tiket->id ?>">
                    <div class="form-group">
                        <textarea name="catatan_internal" rows="3" class="form-control" placeholder="Tuliskan catatan teknis/dokumentasi internal penanganan..."><?= e($tiket->catatan_internal) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning btn-sm flat pull-right">
                        <i class="fa fa-save"></i> Simpan Catatan
                    </button>
                </form>
            </div>
        </div>

        <!-- Riwayat Perubahan Status (Timeline) -->
        <div class="box box-default flat">
            <div class="box-header with-border">
                <h4 class="box-title"><i class="fa fa-history"></i> <strong>Riwayat Status</strong></h4>
            </div>
            <div class="box-body" style="max-height:250px; overflow-y:auto;">
                <ul class="timeline" style="margin-left:0; margin-right:0;">
                    <?php foreach ($riwayat_status as $rw): ?>
                        <li>
                            <i class="fa fa-circle bg-blue"></i>
                            <div class="timeline-item" style="box-shadow:none; border:1px solid #f0f0f0;">
                                <span class="time"><i class="fa fa-clock-o"></i> <?= date('d/m/Y H:i', strtotime($rw->created_at)) ?></span>
                                <h5 class="timeline-header" style="font-size:12px; font-weight:bold;">
                                    Status &rarr; <span class="label label-primary"><?= e($rw->status_baru) ?></span>
                                </h5>
                                <div class="timeline-body" style="font-size:11px; padding:5px 10px;">
                                    <?= e($rw->keterangan) ?> <em>(Oleh: <?= e($rw->diubah_oleh_nama) ?>)</em>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Ruang Diskusi / Chat Room -->
    <div class="col-md-7">
        <div class="box box-primary direct-chat direct-chat-primary flat">
            <div class="box-header with-border">
                <h4 class="box-title"><i class="fa fa-comments-o"></i> <strong>Ruang Diskusi Tiket</strong></h4>
                <div class="box-tools pull-right">
                    <span class="badge bg-aqua"><?= count($pesan_list) ?> Pesan</span>
                </div>
            </div>
            <div class="box-body">
                <!-- Direct Chat Messages -->
                <div class="direct-chat-messages" style="height:420px; padding:15px; overflow-y:auto;">
                    <!-- Pesan Pembuka dari Pelapor (Deskripsi Tiket) -->
                    <div class="direct-chat-msg">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left"><?= e($tiket->user_nama) ?> (Pelapor)</span>
                            <span class="direct-chat-timestamp pull-right"><?= date('d M Y H:i', strtotime($tiket->created_at)) ?></span>
                        </div>
                        <img class="direct-chat-img" src="<?= base_url('assets/institusi/logo.png') ?>" alt="User">
                        <div class="direct-chat-text" style="background:#f4f6f9; color:#333; border:1px solid #d2d6de;">
                            <strong><?= e($tiket->judul) ?></strong><br>
                            <?= nl2br(e($tiket->deskripsi)) ?>
                        </div>
                    </div>

                    <!-- Thread Balasan -->
                    <?php foreach ($pesan_list as $psn): ?>
                        <?php if ($psn->pengirim_type === 'admin'): ?>
                            <!-- Pesan dari Admin (Sebelah Kanan) -->
                            <div class="direct-chat-msg right">
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-right"><?= e($psn->pengirim_nama) ?> (Super Admin)</span>
                                    <span class="direct-chat-timestamp pull-left"><?= date('d M Y H:i', strtotime($psn->created_at)) ?></span>
                                </div>
                                <img class="direct-chat-img" src="<?= base_url('assets/institusi/logo.png') ?>" alt="Admin">
                                <div class="direct-chat-text" style="background:#0073b7; color:#fff;">
                                    <?= nl2br(e($psn->pesan)) ?>
                                    <?php if (!empty($psn->lampiran)): ?>
                                        <div style="margin-top:8px; padding-top:6px; border-top:1px dashed rgba(255,255,255,0.4);">
                                            <a href="<?= base_url('assets/uploads/tiket/' . $psn->lampiran) ?>" target="_blank" style="color:#fff; text-decoration:underline;">
                                                <i class="fa fa-paperclip"></i> Lihat Lampiran: <?= e($psn->lampiran) ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Pesan dari Pelapor (Sebelah Kiri) -->
                            <div class="direct-chat-msg">
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-left"><?= e($psn->pengirim_nama) ?> (Pelapor)</span>
                                    <span class="direct-chat-timestamp pull-right"><?= date('d M Y H:i', strtotime($psn->created_at)) ?></span>
                                </div>
                                <img class="direct-chat-img" src="<?= base_url('assets/institusi/logo.png') ?>" alt="User">
                                <div class="direct-chat-text" style="background:#f4f6f9; color:#333; border:1px solid #d2d6de;">
                                    <?= nl2br(e($psn->pesan)) ?>
                                    <?php if (!empty($psn->lampiran)): ?>
                                        <div style="margin-top:8px; padding-top:6px; border-top:1px dashed #ccc;">
                                            <a href="<?= base_url('assets/uploads/tiket/' . $psn->lampiran) ?>" target="_blank" class="text-primary" style="font-weight:bold;">
                                                <i class="fa fa-paperclip"></i> Lihat Lampiran: <?= e($psn->lampiran) ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Form Balas Pesan -->
            <div class="box-footer">
                <?php if ($tiket->status === 'CLOSED'): ?>
                    <div class="alert alert-default text-center" style="margin-bottom:0; background:#f0f0f0;">
                        <i class="fa fa-lock"></i> Tiket ini telah ditutup (CLOSED). Diskusi selesai.
                    </div>
                <?php else: ?>
                    <form method="post" action="<?= site_url('admin/dukungan/tiket/kirim_pesan') ?>" enctype="multipart/form-data">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" name="tiket_id" value="<?= $tiket->id ?>">
                        <div class="form-group">
                            <textarea name="pesan" rows="3" class="form-control" placeholder="Tuliskan respon / balasan solusi untuk pelapor..." required></textarea>
                        </div>
                        <div class="chat-footer-action" style="display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <label class="btn btn-default btn-xs flat" style="margin-bottom:0;">
                                    <i class="fa fa-paperclip"></i> Lampirkan File / Gambar
                                    <input type="file" name="lampiran" style="display:none;" onchange="$('#file-name-label').text(this.files[0].name);">
                                </label>
                                <span id="file-name-label" class="text-muted" style="margin-left:8px; font-size:12px;"></span>
                            </div>
                            <button type="submit" class="btn btn-primary flat btn-submit-chat">
                                <i class="fa fa-paper-plane"></i> Kirim Balasan
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
