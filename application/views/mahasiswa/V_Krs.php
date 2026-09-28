<?= $this->session->flashdata('info') ?>
<div class="mhs-profile">

    <!-- Dosen Wali -->
    <div class="pf-card">
        <div class="pf-card-body">
            <div class="krs-advisor">
                <span class="krs-advisor-label">Dosen Wali :</span>
                <span class="pf-chip"><?= e($dosen_wali) ?></span>
                <?php if (!empty($dosen_perwakilan)) : ?>
                    <span class="pf-chip is-alt">
                        <i class="fa fa-phone"></i> <?= e($dosen_perwakilan['nama_dosen']) ?> (<?= e($dosen_perwakilan['no_telp']) ?>)
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Pilih Semester -->
    <div class="pf-card">
        <div class="pf-card-body">
            <div class="krs-pick">
                <?php $new_semester = 0; foreach ($krs_mhs as $row) : ?>
                    <?php if ($new_semester == 0) $new_semester = $row->kode_tahun_akademik ?>
                    <a href="<?= site_url('mahasiswa/krs/old/'.$row->semester) ?>" class="btn <?= (isset($ta_selected) && $ta_selected == $row->kode_tahun_akademik && $semester == $row->semester) ? 'btn-primary' : 'btn-default' ?> btn-sm"><i class="fa fa-calendar-check-o"></i> <?= ($row->semester == 'K') ? 'Konversi' : 'Semester '.$row->semester ?></a>
                <?php endforeach; ?>
                <a href="<?= site_url('mahasiswa/krs/index/') ?>" class="btn <?= ($ta == $tahun_akademik->kode_tahun_akademik) ? 'btn-primary' : 'btn-default' ?> btn-sm"><i class="fa fa-calendar-check-o"></i> Semester <?= isset($semester_aktif) ? $semester_aktif : $semester ?> (Aktif)</a>
            </div>
        </div>
    </div>

    <!-- Status KRS -->
    <div class="pf-card">
        <div class="pf-card-head">
            <h2><i class="fa fa-check-square-o"></i> Status KRS</h2>
        </div>
        <div class="pf-card-body">
            <div class="krs-status-row">
                <div class="krs-status-line">
                    <div class="krs-status-item">
                        <span>Aktifasi Dosen Wali :</span>
                        <span class="badge <?= !empty($aktif_dosen) ? "bg-green" : 'bg-red' ?>"><?= !empty($aktif_dosen) ? '<i class="fa fa-check"></i> Sudah' : '<i class="fa fa-times"></i> Belum' ?></span>
                    </div>
                    <?php if (substr($data_mahasiswa->nim, 0, 2) < 25) : ?>
                        <div class="krs-status-item">
                            <span>Validasi Pembayaran SKS :</span>
                            <span class="badge <?= !empty($bayar_sks) ? "bg-green" : 'bg-red' ?>"><?= !empty($bayar_sks) ? '<i class="fa fa-check"></i> Sudah' : '<i class="fa fa-times"></i> Belum' ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="krs-status-actions">
                    <?php if ($ta == $tahun_akademik->kode_tahun_akademik) : ?>
                        <?php if (empty($aktif_dosen) && empty($bayar_sks)) : ?>
                            <a href="<?= site_url('mahasiswa/krs/edit_krs') ?>" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Ubah</a>
                        <?php else : ?>
                            <?php if (empty($aktif_dosen) && substr($data_mahasiswa->nim, 0, 2) > 24) : ?>
                                <a href="<?= site_url('mahasiswa/krs/edit_krs') ?>" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Ubah</a>
                            <?php else : ?>
                                <a href="#" class="btn btn-default btn-sm disabled"><i class="fa fa-lock"></i> Tidak Dapat Diubah</a>
                            <?php endif; ?>
                            <?php if (!empty($aktif_dosen) && substr($data_mahasiswa->nim, 0, 2) > 24) : ?>
                                <a href="<?= site_url('mahasiswa/krs/print_view') ?>" class="btn btn-danger btn-sm"><i class="fa fa-print"></i> Cetak</a>
                                <a href="<?= site_url('mahasiswa/krs/cetak') ?>" class="btn btn-success btn-sm"><i class="fa fa-download"></i> Unduh</a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (!empty($aktif_dosen) && !empty($bayar_sks) && substr($data_mahasiswa->nim, 0, 2) < 25) : ?>
                            <a href="<?= site_url('mahasiswa/krs/print_view') ?>" class="btn btn-danger btn-sm"><i class="fa fa-print"></i> Cetak</a>
                            <a href="<?= site_url('mahasiswa/krs/cetak') ?>" class="btn btn-success btn-sm"><i class="fa fa-download"></i> Unduh</a>
                        <?php endif; ?>
                    <?php elseif ($semester != 'K') : ?>
                        <a href="<?= site_url('mahasiswa/krs/print_view_lalu/'.$tahun_akademik->kode_tahun_akademik.'/'.$semester) ?>" class="btn btn-danger btn-sm"><i class="fa fa-print"></i> Cetak</a>
                        <a href="<?= site_url('mahasiswa/krs/cetak_lalu/'.$tahun_akademik->kode_tahun_akademik.'/'.$semester) ?>" class="btn btn-success btn-sm"><i class="fa fa-download"></i> Unduh</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    $angkatan = substr($data_mahasiswa->nim, 0, 2);
    $foto = !(empty($data_mahasiswa->foto) || $data_mahasiswa->foto == 'P.png' || $data_mahasiswa->foto == 'L.png');
    ?>

    <!-- Kartu Rencana Studi -->
    <div class="pf-card">
        <div class="pf-card-body">
            <?php if (false) : // if ($this->session->flashdata('pesan')): ?>
                <div class="callout callout-warning">
                    <h4><i class="fa fa-info"></i> Perhatian!</h4>
                    <p>Maaf anda tidak bisa melakukan download KRS, Adapun penyebabnya sebagi berikut :</p>
                    <ul>
                        <?php if (empty($aktif_dosen)) : ?><li>Belum melakukan pengaktifan KRS ke dosen wali</li><?php endif; ?>
                        <?php if (empty($bayar_sks)) : ?>
                            <?php if ($angkatan == '22') : ?>
                                <li>Bagi angkatan 2022 validasi pembayaran SKS akan dilakukan bertahap oleh bagian Keuangan.</li>
                            <?php else : ?>
                                <li>Pembayaran SKS belum di validasi oleh bagian keuangan.</li>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (true) : ?>
                            <li>Belum melakukan upload foto profile. Silahkan upload foto dan tunggu proses Validasi pada link berikut :
                                <?php if ($angkatan == '22') : ?>
                                    <a target="_blank" class="btn btn-success btn-xs" href="https://berkas.universitasbumigora.ac.id/pmb/">Link Upload</a>
                                <?php else : ?>
                                    <a target="_blank" class="btn btn-success btn-xs" href="https://berkas.universitasbumigora.ac.id/">Link Upload</a>
                                <?php endif; ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <h2 class="krs-report-title">KARTU RENCANA STUDI (KRS) JENJANG <?= strtoupper(e($prodi->nama_program_studi)) ?> (<?= strtoupper(e($prodi->singkatan_program_studi)) ?>)</h2>

            <div class="row" style="margin-top:18px;">
                <div class="col-md-6 col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th>Nama Mahasiswa</th><td><?= e($nama_mahasiswa) ?></td></tr>
                                <tr><th>NIM</th><td><?= e($nim) ?></td></tr>
                                <tr><th>Semester</th><td><?= ($semester == 'K') ? 'Konversi' : $semester ?></td></tr>
                                <tr><th>Dosen Wali</th><td><?= e($dosen_wali) ?></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th>Alamat Sekarang</th><td><?= e($data_mahasiswa->alamat) . ', ' . e($data_mahasiswa->kota) . '<br>' . e($data_mahasiswa->propinsi) ?></td></tr>
                                <tr><th>No. Telp/HP</th><td><?= e($data_mahasiswa->telepon) ?></td></tr>
                                <tr><th>Alamat Orang Tua</th><td><?= e($data_mahasiswa->alamat_orangtua) . ', ' . e($data_mahasiswa->kota_orangtua) . '<br>' . e($data_mahasiswa->propinsi_orangtua) ?></td></tr>
                                <tr><th>Telp</th><td><?= e($data_mahasiswa->telepon_orangtua) ?></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <h4 class="krs-semester-label"><?= ($semester == 'K') ? 'KONVERSI' : 'SEMESTER '.$semester ?></h4>
            <div class="table-responsive">
                <table class="table table-bordered krs-table">
                    <thead>
                        <tr>
                            <th width="40" rowspan="2">NO.</th>
                            <th width="140" rowspan="2">KODE MK</th>
                            <th rowspan="2">MATAKULIAH</th>
                            <th colspan="3">SKS</th>
                            <th width="50" rowspan="2">B</th>
                            <th width="50" rowspan="2">U</th>
                        </tr>
                        <tr>
                            <th width="50">T</th>
                            <th width="50">PK</th>
                            <th width="50">PT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; $t = 0; $pk = 0; $pt = 0;
                        foreach ($data as $row) : ?>
                            <tr>
                                <td class="text-center"><?= $i++ ?>.</td>
                                <td class="text-center"><?= e($row->kode_matakuliah) ?></td>
                                <?php if (substr($this->session->userdata('nim'), 0, 2) == '16') : ?>
                                    <?php if ($row->kode_matakuliah == 'TSKB351435') : ?>
                                        <td class="krs-name">Animasi 2 Dimensi</td>
                                    <?php else : ?>
                                        <td class="krs-name"><?= e($row->nama_matakuliah) ?></td>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <td class="krs-name"><?= e($row->nama_matakuliah) ?></td>
                                <?php endif; ?>
                                <td class="text-center"><?= $row->sks_teori != 0 ? $row->sks_teori : '' ?></td>
                                <td class="text-center"><?= $row->sks_praktek != 0 ? $row->sks_praktek : '' ?></td>
                                <td class="text-center"><?= $row->sks_praktikum != 0 ? $row->sks_praktikum : '' ?></td>
                                <?php if ($row->status == "B") : ?>
                                    <td class="text-center"><b>&radic;</b></td>
                                    <td class="text-center"></td>
                                <?php else : ?>
                                    <td class="text-center"></td>
                                    <td class="text-center"><b>&radic;</b></td>
                                <?php endif; ?>
                            </tr>
                            <?php $t += $row->sks_teori; $pk += $row->sks_praktek; $pt += $row->sks_praktikum;
                        endforeach;
                        $total_sks = $t + $pk + $pt; ?>
                        <tr class="krs-total">
                            <td class="text-center" colspan="3"><strong>Jumlah</strong></td>
                            <td class="text-center"><?= $t ?></td>
                            <td class="text-center"><?= $pk ?></td>
                            <td class="text-center"><?= $pt ?></td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row" style="margin-top:16px;">
                <div class="col-md-6 col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr><th width="55%">IP Semester Lalu</th><td><?= ($semester != "1" && $semester != "K") ? number_format($beban_sks['ip_semester_lalu'], 2) : "-" ?></td></tr>
                                <?php if (substr($nim, 4, 1) != 3) : ?>
                                    <tr><th>Beban SKS Semester Sekarang</th><td><?= ($semester == 1 || $semester == 'K') ? $total_sks : $beban_sks['beban_sks'] ?></td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr><th width="55%">Jumlah kredit yang diambil</th><td><?= $total_sks ?></td></tr>
                                <tr><th>Jumlah kredit terakhir</th><td><?= $total_sks ?></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <p class="krs-note">Tanda <b>&radic;</b> di kolom <b>B</b> atau <b>U</b> menandakan matakuliah yang dipilih.</p>
        </div>
    </div>

    <!-- Keterangan -->
    <div class="pf-card">
        <div class="pf-card-head">
            <h2><i class="fa fa-info-circle"></i> Keterangan</h2>
        </div>
        <div class="pf-card-body">
            <p class="krs-legend">
                <b>Status Pengambilan Matakuliah</b><br/>
                <b>B</b> : Baru &nbsp;&nbsp;&nbsp; <b>U</b> : Ulang<br/>
                <b>Jenis SKS Matakuliah</b><br/>
                <b>T</b> : Teori &nbsp;&nbsp;&nbsp; <b>PK</b> : Praktek &nbsp;&nbsp;&nbsp; <b>PT</b> : Praktikum
            </p>
        </div>
    </div>

</div>
