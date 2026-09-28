<?php
$stat = isset($stat) ? $stat : array('bayar_spp' => 0, 'isi_krs' => 0, 'masuk_kelas' => 0, 'tanpa_kelas' => 0);
?>
<!-- BEGIN: Statistik Ringkas -->
<div class="row">
    <div class="col-md-12">
        <div class="stat-grid">
            <div class="stat-card stat-card-blue">
                <div class="stat-icon"><i class="fa fa-credit-card"></i></div>
                <div class="stat-body">
                    <span class="stat-value"><?= number_format((int) $stat['bayar_spp'], 0, ',', '.') ?></span>
                    <span class="stat-label">Mahasiswa Bayar SPP</span>
                </div>
            </div>
            <div class="stat-card stat-card-teal">
                <div class="stat-icon"><i class="fa fa-pencil-square-o"></i></div>
                <div class="stat-body">
                    <span class="stat-value"><?= number_format((int) $stat['isi_krs'], 0, ',', '.') ?></span>
                    <span class="stat-label">KRS MHS</span>
                </div>
            </div>
            <div class="stat-card stat-card-amber">
                <div class="stat-icon"><i class="fa fa-user-times"></i></div>
                <div class="stat-body">
                    <span class="stat-value"><?= number_format((int) $stat['tanpa_kelas'], 0, ',', '.') ?></span>
                    <span class="stat-label">Tidak Punya Kelas</span>
                </div>
            </div>
            <div class="stat-card stat-card-green">
                <div class="stat-icon"><i class="fa fa-users"></i></div>
                <div class="stat-body">
                    <span class="stat-value"><?= number_format((int) $stat['masuk_kelas'], 0, ',', '.') ?></span>
                    <span class="stat-label">Sudah Masuk Kelas</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Statistik Ringkas -->

<?php $stat_prodi = isset($stat_prodi) ? $stat_prodi : array(); ?>
<!-- BEGIN: Mahasiswa Belum Masuk Kelas per Prodi -->
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-user-times"></i> Distribusi Kelas per Program Studi</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="50" rowspan="2" class="th-center">No</th>
                                <th rowspan="2">Program Studi</th>
                                <th width="130" rowspan="2" class="th-center">KRS MHS</th>
                                <th colspan="2" class="th-center">Distribusi Kelas</th>
                            </tr>
                            <tr>
                                <th width="140" class="th-center">Sudah</th>
                                <th width="150" class="th-center">Belum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($stat_prodi) > 0): ?>
                                <?php $no = 1; $total_tanpa = 0; ?>
                                <?php foreach ($stat_prodi as $row): ?>
                                    <?php $total_tanpa += $row['tanpa_kelas']; ?>
                                    <tr>
                                        <td class="th-center"><?= $no++ ?></td>
                                        <td><?= e($row['nama_program_studi']) ?></td>
                                        <td class="th-center"><?= number_format($row['isi_krs'], 0, ',', '.') ?></td>
                                        <td class="th-center"><?= number_format($row['masuk_kelas'], 0, ',', '.') ?></td>
                                        <td class="th-center"><span class="label label-warning"><?= number_format($row['tanpa_kelas'], 0, ',', '.') ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <th colspan="4" class="text-right">Total</th>
                                    <th class="th-center"><?= number_format($total_tanpa, 0, ',', '.') ?></th>
                                </tr>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center text-muted">Tidak ada data.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Mahasiswa Belum Masuk Kelas per Prodi -->

<div class="row">
    <div class="col-md-12">
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <!-- Penjelasan Menu Badge -->
            <div>
                <span class="label" style="background-color: #198754; color: #fff; font-size: 12px; font-weight: 600; padding: 6px 14px; border-radius: 4px; display: inline-block; box-shadow: 0 1px 2px rgba(0,0,0,0.08);">Penjelasan Menu</span>
            </div>

            <!-- JURUSAN Card -->
            <div class="admin-dash-card" style="background: #ffffff; border: 1px solid #dce4ec; box-shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; align-items: stretch; border-radius: 4px; overflow: hidden;">
                <div style="width: 64px; background: rgba(233, 238, 242, 0.6); display: flex; align-items: center; justify-content: center; padding: 12px; flex-shrink: 0; border-right: 1px solid #dce4ec;">
                    <div style="width: 38px; height: 38px; border-radius: 50%; background: #0e8a94; color: #fff; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                        <i class="fa fa-map" style="font-size: 15px;"></i>
                    </div>
                </div>
                <div style="flex: 1; padding: 16px 20px;">
                    <h2 style="font-size: 12px; font-weight: 700; color: #0e8a94; letter-spacing: 0.5px; margin: 0 0 8px 0; text-transform: uppercase;">JURUSAN</h2>
                    <p style="font-size: 13px; color: #334155; margin: 0 0 8px 0; line-height: 1.5;">Menu <strong style="font-weight: 700;">Jurusan</strong> berisi beberapa menu lagi antara lain:</p>
                    <ul style="font-size: 13px; color: #334155; padding-left: 20px; margin: 0; line-height: 1.6;">
                        <li>Institusi</li>
                        <li>Program Studi</li>
                        <li>Kurikulum</li>
                        <li>Dosen</li>
                        <li>Perwalian</li>
                        <li>Tahun Akademik</li>
                    </ul>
                </div>
            </div>

            <!-- LOGOUT Card -->
            <div class="admin-dash-card" style="background: #ffffff; border: 1px solid #dce4ec; box-shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; align-items: stretch; border-radius: 4px; overflow: hidden;">
                <div style="width: 64px; background: rgba(233, 238, 242, 0.6); display: flex; align-items: center; justify-content: center; padding: 12px; flex-shrink: 0; border-right: 1px solid #dce4ec;">
                    <div style="width: 38px; height: 38px; border-radius: 50%; background: #f39c12; color: #fff; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                        <i class="fa fa-lock" style="font-size: 15px;"></i>
                    </div>
                </div>
                <div style="flex: 1; padding: 16px 20px;">
                    <h2 style="font-size: 12px; font-weight: 700; color: #578EF5; letter-spacing: 0.5px; margin: 0 0 8px 0; text-transform: uppercase;">LOGOUT</h2>
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #334155;">
                        <span>Klik tombol</span>
                        <a href="<?php echo site_url('/admin/login_admin/logout'); ?>" onclick="return confirm('Anda Yakin ?')" class="btn btn-danger btn-xs"><i class="fa fa-sign-out"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
