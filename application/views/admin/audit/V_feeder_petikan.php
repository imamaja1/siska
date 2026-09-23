<div class="col-md-12">
    <div class="box box-primary flat">
        <div class="box-header">
            <h4><i class="fa fa-file-text-o"></i> PETIKAN NILAI MAHASISWA (FEEDER)</h4>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-6">
                    <table class="table">
                        <tr>
                            <td width="140"><strong>NAMA</strong></td>
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
                    </table>
                </div>
                <div class="col-sm-6">
                    <table class="table">
                        <tr>
                            <td width="140"><strong>PROGRAM STUDI</strong></td>
                            <td width="10"><strong>:</strong></td>
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
            </ul>

            <div class="tab-content" style="padding-top: 15px;">
                <!-- Tab Detail Nilai -->
                <div role="tabpanel" class="tab-pane active" id="tab-detail">
                    <?php foreach ($semester as $smt) : ?>
                    <div class="table-responsive" style="margin-top: 10px;">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th colspan="7" class="bg-primary"><?= e($smt['nama_semester'] !== '' ? $smt['nama_semester'] : $smt['id_semester']) ?></th>
                                </tr>
                                <tr>
                                    <th width="40" class="text-center">No</th>
                                    <th width="130" class="text-center">Kode MK</th>
                                    <th>Matakuliah</th>
                                    <th width="60" class="text-center">SKS</th>
                                    <th width="90" class="text-center">Nilai Angka</th>
                                    <th width="90" class="text-center">Nilai Huruf</th>
                                    <th width="80" class="text-center">Indeks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($smt['items'] as $item) : ?>
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
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Jumlah</th>
                                    <th class="text-center"><?= number_format($smt['total_sks'], 2) ?></th>
                                    <th colspan="2" class="text-right">SKSN</th>
                                    <th class="text-center"><?= number_format($smt['total_sksn'], 2) ?></th>
                                </tr>
                                <tr>
                                    <th colspan="6" class="text-right">IPS</th>
                                    <th class="text-center"><?= number_format($smt['ips'], 2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Tab Petikan Nilai (konsolidasi) -->
                <div role="tabpanel" class="tab-pane" id="tab-petikan">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="40" class="text-center">No</th>
                                    <th width="130" class="text-center">Kode MK</th>
                                    <th>Matakuliah</th>
                                    <th width="160" class="text-center">Semester</th>
                                    <th width="60" class="text-center">SKS</th>
                                    <th width="90" class="text-center">Nilai Angka</th>
                                    <th width="90" class="text-center">Nilai Huruf</th>
                                    <th width="80" class="text-center">Indeks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($konsolidasi)) : ?>
                                <tr><td colspan="8" class="text-center">Tidak ada data.</td></tr>
                                <?php else : ?>
                                <?php $no = 1; foreach ($konsolidasi as $item) : ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center"><?= e($item['kode_mata_kuliah']) ?></td>
                                    <td><?= e($item['nama_mata_kuliah']) ?></td>
                                    <td class="text-center"><?= e($item['nama_semester']) ?></td>
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
                                <td class="text-center"><?= number_format($total_sks, 2) ?></td>
                            </tr>
                            <tr>
                                <th class="text-right">TOTAL SKSN</th>
                                <td class="text-center"><?= number_format($total_sksn, 2) ?></td>
                            </tr>
                            <tr>
                                <th class="text-right">IPK</th>
                                <td class="text-center"><strong><?= number_format($ipk, 2) ?></strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
