<div class="col-md-12">
    <div class="box box-primary flat">
        <div class="box-header">
            <h4><i class="fa fa-file-text-o"></i> PETIKAN NILAI MAHASISWA (FEEDER)</h4>
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

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#tab-detail" role="tab" data-toggle="tab"><i class="fa fa-list"></i> Detail Nilai</a>
                </li>
                <li role="presentation">
                    <a href="#tab-petikan" role="tab" data-toggle="tab"><i class="fa fa-file-text"></i> Petikan Nilai</a>
                </li>
                <li role="presentation">
                    <a href="#tab-banding" role="tab" data-toggle="tab">
                        <i class="fa fa-exchange"></i> Perbandingan SISKA vs Feeder
                        <?php if (!empty($perbandingan)) : ?>
                            <span class="label label-danger"><?= (int) ($summary_banding['berbeda'] + $summary_banding['tidak_ada_siska'] + $summary_banding['tidak_ada_feeder']) ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>

            <div class="tab-content" style="padding-top: 15px;">
                <!-- Tab Detail Nilai -->
                <div role="tabpanel" class="tab-pane active" id="tab-detail">
                    <?php foreach ($semester as $smt) : ?>
                    <div class="table-responsive" style="margin-top: 10px;">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th colspan="9" class="bg-primary">
                                        <?= e($smt['nama_semester'] !== '' ? $smt['nama_semester'] : $smt['id_semester']) ?>
                                        <?php if (isset($smt['id_semester']) && $smt['id_semester'] === 'KONVERSI') : ?>
                                            <span class="label label-warning">Konversi</span>
                                        <?php endif; ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th width="40" class="text-center">No</th>
                                    <th width="120" class="text-center">Kode MK</th>
                                    <th>Matakuliah</th>
                                    <th width="80" class="text-center">Kelas</th>
                                    <th width="60" class="text-center">SKS</th>
                                    <th width="90" class="text-center">Nilai Angka</th>
                                    <th width="90" class="text-center">Nilai Huruf</th>
                                    <th width="80" class="text-center">Indeks</th>
                                    <th width="90" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($smt['items'] as $item) : ?>
                                <tr class="<?= !empty($item['highlight']) ? 'warning' : '' ?>">
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center"><?= e($item['kode_mata_kuliah']) ?></td>
                                    <td><?= e($item['nama_mata_kuliah']) ?></td>
                                    <td class="text-center"><?= e($item['nama_kelas'] !== '' ? $item['nama_kelas'] : '-') ?></td>
                                    <td class="text-center"><?= e($item['sks_tampil']) ?></td>
                                    <td class="text-center"><?= e($item['nilai_angka_tampil']) ?></td>
                                    <td class="text-center"><?= e($item['nilai_huruf_tampil']) ?></td>
                                    <td class="text-center"><?= e($item['nilai_indeks_tampil']) ?></td>
                                    <td class="text-center">
                                        <?php if (!empty($item['status'])) : ?>
                                            <span class="label label-primary"><?= e($item['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Jumlah</th>
                                    <th class="text-center"><?= number_format($smt['total_sks'], 2) ?></th>
                                    <th colspan="2" class="text-right">SKSN</th>
                                    <th class="text-center"><?= number_format($smt['total_sksn'], 2) ?></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th colspan="7" class="text-right">IPS</th>
                                    <th class="text-center"><?= number_format($smt['ips'], 2) ?></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Tab Petikan Nilai (GetTranskripMahasiswa) -->
                <div role="tabpanel" class="tab-pane" id="tab-petikan">
                    <p class="text-muted"><i class="fa fa-info-circle"></i> Sumber: Feeder <code>GetTranskripMahasiswa</code> (memuat nilai konversi/transfer).</p>
                    <?php if (!empty($transkrip_error)) : ?>
                        <div class="callout callout-warning flat"><p>Catatan transkrip: <?= e($transkrip_error) ?></p></div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="40" class="text-center">No</th>
                                    <th width="120" class="text-center">Kode MK</th>
                                    <th>Matakuliah</th>
                                    <th width="60" class="text-center">SKS</th>
                                    <th width="90" class="text-center">Nilai Angka</th>
                                    <th width="90" class="text-center">Nilai Huruf</th>
                                    <th width="80" class="text-center">Indeks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($petikan)) : ?>
                                <tr><td colspan="7" class="text-center">Tidak ada data transkrip.</td></tr>
                                <?php else : ?>
                                <?php $no = 1; foreach ($petikan as $item) : ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center"><?= e($item['kode_mata_kuliah']) ?></td>
                                    <td><?= e($item['nama_mata_kuliah']) ?></td>
                                    <td class="text-center"><?= e($item['sks_tampil']) ?></td>
                                    <td class="text-center"><?= e($item['nilai_angka_tampil']) ?></td>
                                    <td class="text-center"><?= e($item['nilai_huruf_tampil']) ?></td>
                                    <td class="text-center"><?= e($item['nilai_indeks_tampil']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th class="text-right" width="70%">TOTAL SKS</th>
                                <td class="text-center"><?= number_format($petikan_total_sks, 2) ?></td>
                            </tr>
                            <tr>
                                <th class="text-right">TOTAL SKSN</th>
                                <td class="text-center"><?= number_format($petikan_total_sksn, 2) ?></td>
                            </tr>
                            <tr>
                                <th class="text-right">IPK</th>
                                <td class="text-center"><strong><?= number_format($petikan_ipk, 2) ?></strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Tab Perbandingan SISKA vs Feeder -->
                <div role="tabpanel" class="tab-pane" id="tab-banding">
                    <div style="margin-bottom:10px;">
                        <span class="label label-success">Sesuai: <?= (int) ($summary_banding['sesuai'] ?? 0) ?></span>
                        <span class="label label-danger">Berbeda: <?= (int) ($summary_banding['berbeda'] ?? 0) ?></span>
                        <span class="label label-warning">SISKA belum ada nilai: <?= (int) ($summary_banding['siska_kosong'] ?? 0) ?></span>
                        <span class="label label-info">Feeder belum ada nilai: <?= (int) ($summary_banding['feeder_kosong'] ?? 0) ?></span>
                        <span class="label label-warning">Tidak ada di SISKA: <?= (int) ($summary_banding['tidak_ada_siska'] ?? 0) ?></span>
                        <span class="label label-info">Tidak ada di Feeder: <?= (int) ($summary_banding['tidak_ada_feeder'] ?? 0) ?></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="40" class="text-center" rowspan="2">No</th>
                                    <th width="110" class="text-center" rowspan="2">Kode MK</th>
                                    <th rowspan="2">Matakuliah</th>
                                    <th width="150" class="text-center" rowspan="2">Semester / Kelas Feeder</th>
                                    <th width="150" class="text-center" rowspan="2">Semester / Kelas SISKA</th>
                                    <th colspan="3" class="text-center bg-info">Feeder</th>
                                    <th colspan="3" class="text-center bg-warning">SISKA</th>
                                    <th width="120" class="text-center" rowspan="2">Status</th>
                                </tr>
                                <tr>
                                    <th width="60" class="text-center">SKS</th>
                                    <th width="70" class="text-center">Angka</th>
                                    <th width="60" class="text-center">Huruf</th>
                                    <th width="60" class="text-center">SKS</th>
                                    <th width="70" class="text-center">Akhir</th>
                                    <th width="60" class="text-center">Huruf</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($perbandingan)) : ?>
                                <tr><td colspan="12" class="text-center">Tidak ada data.</td></tr>
                                <?php else : ?>
                                <?php $no = 1; foreach ($perbandingan as $b) : ?>
                                <tr class="<?= $b['status'] === 'Berbeda' ? 'danger' : ($b['status'] === 'Sesuai' ? '' : 'warning') ?>">
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center">
                                        <?php if (!empty($b['matched_by']) && $b['matched_by'] === 'nama' && $b['feeder_kode'] !== '' && $b['siska_kode'] !== '' && $b['feeder_kode'] !== $b['siska_kode']) : ?>
                                            <small class="text-muted">Feeder:</small> <?= e($b['feeder_kode']) ?><br>
                                            <small class="text-muted">SISKA:</small> <?= e($b['siska_kode']) ?>
                                        <?php else : ?>
                                            <?= e($b['kode_mata_kuliah']) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= e($b['nama_mata_kuliah']) ?>
                                        <?php if (!empty($b['feeder_transfer'])) : ?>
                                            <span class="label label-info">Feeder Transfer</span>
                                        <?php endif; ?>
                                        <?php if (!empty($b['siska_konversi'])) : ?>
                                            <span class="label label-primary">Konversi</span>
                                        <?php endif; ?>
                                        <?php if (!empty($b['matched_by']) && $b['matched_by'] === 'nama') : ?>
                                            <span class="label label-default">Cocok nama</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?= e($b['feeder_semester'] !== '' ? $b['feeder_semester'] : '-') ?>
                                        <?php if ($b['feeder_kelas'] !== '') : ?><br><small class="text-muted">Kelas <?= e($b['feeder_kelas']) ?></small><?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?= e($b['siska_semester'] !== '' ? $b['siska_semester'] : '-') ?>
                                        <?php if ($b['siska_kelas'] !== '') : ?><br><small class="text-muted">Kelas <?= e($b['siska_kelas']) ?></small><?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= e($b['feeder_sks'] !== NULL && $b['feeder_sks'] !== '' ? number_format((float) $b['feeder_sks'], 2) : '-') ?></td>
                                    <td class="text-center"><?= e($b['feeder_angka']) ?></td>
                                    <td class="text-center"><?= e($b['feeder_huruf']) ?></td>
                                    <td class="text-center"><?= e($b['siska_sks'] !== NULL && $b['siska_sks'] !== '' ? number_format((float) $b['siska_sks'], 2) : '-') ?></td>
                                    <td class="text-center"><?= e($b['siska_angka']) ?></td>
                                    <td class="text-center"><?= e($b['siska_huruf']) ?></td>
                                    <td class="text-center">
                                        <?php if ($b['status'] === 'Sesuai') : ?>
                                            <span class="label label-success">Sesuai</span>
                                        <?php elseif ($b['status'] === 'Berbeda') : ?>
                                            <span class="label label-danger">Berbeda</span>
                                        <?php elseif ($b['status'] === 'SISKA belum ada nilai') : ?>
                                            <span class="label label-warning">SISKA belum ada nilai</span>
                                        <?php elseif ($b['status'] === 'Feeder belum ada nilai') : ?>
                                            <span class="label label-info">Feeder belum ada nilai</span>
                                        <?php elseif ($b['status'] === 'Tidak ada di SISKA') : ?>
                                            <span class="label label-warning">Tidak ada di SISKA</span>
                                        <?php else : ?>
                                            <span class="label label-info"><?= e($b['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
