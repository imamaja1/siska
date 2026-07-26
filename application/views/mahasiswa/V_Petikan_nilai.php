<div class="box box-primary flat">
    <div class="box-body">
        <p align="right"><a href="<?= site_url('mahasiswa/Petikan_nilai/cetak') ?>" class="btn btn-danger btn-xs" style="margin-right:5px"><i class="fa fa-download"></i> Sebelumnya </a><a href="<?= site_url('mahasiswa/Petikan_nilai/cetak_now') ?>" class="btn btn-danger btn-xs" ><i class="fa fa-download"></i> Terbaru </a></p>
        <p align="center"><strong>PETIKAN NILAI MAHASISWA</strong></p>
        <P align="center"><strong>SEMESTER <?= $tahun_akademik->semester % 2 == (0) ? "GENAP" : "GANJIL"; ?> TA. <?= e($tahun_akademik->ta) ?></strong></P>
        <P align="center"><strong>Angkatan  20<?= e(substr($mahasiswa->nim, 0, 2)) ?> </strong></P>
        <br>
        <div class="col-sm-6 col-md-6 col-lg-6">
            <table class="table">
                <tr><td><strong>NAMA</strong></td><td><strong>:</strong></td><td><?= e($mahasiswa->nama_mahasiswa) ?></td></tr>
                <tr><td><strong>NIM</strong></td><td><strong>:</strong></td><td><?= e($mahasiswa->nim) ?></td></tr>
            </table>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-6">
            <table class="table">
                <tr><td><strong>JURUSAN</strong></td><td><strong>:</strong></td><td><?= e($prodi->nama_program_studi) ?></td></tr>
                <tr><td><strong>FAKULTAS</strong></td><td><strong>:</strong></td><td><?= e($prodi->nama_fakultas) ?></td></tr>
            </table>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <?php
            $sks_final = 0;
            $sksn_final = 0;
            foreach ($data as $key) :
                ?>
                <p><strong>SEMESTER  <?= $key['semester'] ?></strong></p>
                <div class="table-responsive">
                    <table class="table demo-table text-center">
                        <thead>
                            <tr>
                        <th class="th-color" width="20">No.</th>
                        <th class="th-color" width="110">KODE MK</th>
                        <th class="th-color">MATAKULIAH</th>
                        <th class="th-color" width="50">SKS</th>
                        <th class="th-color" width="140">NA</th>
                        <th class="th-color" width="55">GRADE</th>
                        <th class="th-color" width="55">SKSN</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($key['data_nilai'])) :
                                $sks = 0;
                                $sksn = 0;
                                $j = 1;
                                foreach ($key['data_nilai'] as $row) :
                                    ?>
                                    <tr>
                                <td><?= $j++ ?></td>
                                <td><?= e($row['kode_matakuliah']) ?></td>
                                <td style="text-align: left;"><?= e($row['nama_matakuliah']) ?></td>
                                <td><?= isset($row['nilai_akhir']) ? $row['sks'] : '-' ?></td>
                                <td style="font-size: 11px;">
                                    <?php if (isset($row['attempts']) && count($row['attempts']) > 0): ?>
                                        <?php foreach ($row['attempts'] as $att): ?>
                                            Smt <?= $att['semester'] ?>: <?= number_format($att['nilai_akhir'], 2) ?> (<?= $att['grade'] ?>)<br>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <?php if (isset($row['nilai_akhir'])): ?>
                                            Smt <?= $row['semester'] ?? '-' ?>: <?= number_format($row['nilai_akhir'], 2) ?> (<?= $row['grade'] ?? '-' ?>)
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= isset($row['grade']) ? $row['grade'] : '-' ?></td>
                                <td><?= isset($row['nilai_akhir']) ? $row['sksn'] : '-' ?></td>
                                </tr>
                                <?php
                                if (!isset($row['nilai_akhir'])) {
                                } elseif ($row['grade'] == "E") {
                                    if($row['jumlah_data']==0){
                                      $sks = ($sks + $row['sks']) - ($row['sks_teori'] + $row['sks_praktek'] + $sks['sks_praktikum']);
                                      $sksn = $sksn + $row['sksn'];
                                    }
                                } else {
                                    $sks = $sks + $row['sks']; 
                                    $sksn = $sksn + $row['sksn'];
                                }
                                if(isset($row['semester']) && isset($row['nilai_akhir'])) {
                                  $sksn_final = $sksn_final + $row['sksn'];
                                  $sks_final = $sks_final + $row['sks'];
                                }
                                ?>
                            <?php endforeach;
                        endif;
                        ?>
                        </tbody>
                    </table>
                </div>
                <br>
            <?php endforeach; ?>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <table class="table demo-table">
                <tr>
                    <td align="center" style="background-color: #61b5ed;"><strong>JUMLAH SKS YANG DIAMBIL</strong></td>
                    <td align="center" width="70" style="background-color: #61b5ed;"><strong><?= $sks_final ?></strong></td>
                    <td align="center" width="70" style="background-color: #61b5ed;"></td>
                    <td align="center" width="70" style="background-color: #61b5ed;"></td>
                    <td align="center" width="70" style="background-color: #61b5ed;"></td>
                    <td align="center" width="70" style="background-color: #61b5ed;"></td>
                    <td align="center" width="70" style="background-color: #61b5ed;"></td>
                    <td align="center" width="70" style="background-color: #61b5ed;"><strong><?= $sksn_final ?></strong></td>
                </tr>
            </table>
            <br>
            <center>
                <table class="table" style="width:250px;">
                    <tr>
                        <td rowspan="2" style="padding-top: 25px;"><strong>IPK = </strong></td>
                        <td style="border-bottom:1px solid black;"><strong>SKSN</strong></td>
                        <td rowspan="2" style="padding-top: 25px;">=</td>
                        <td style="border-bottom:1px solid black;"><strong><?= $sksn_final ?></strong></td>
                        <td rowspan="2" style="padding-top: 25px;"><strong> = <?= $sks_final == 0 ? '0' : number_format($sksn_final / $sks_final, 2) ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong>SKS</strong></td>
                        <td><strong><?= $sks_final ?></strong></td>
                    </tr>
                </table>
            </center>
        </div>
    </div>
</div>
