<div class="box box-primary flat">
    <div class="box-body table-responsive">
        <p><center><strong>PETIKAN NILAI MAHASISWA</strong></center></p>
        <P><center><strong>SEMESTER <?= e($tahun_akademik->semester % 2 == (0) ? "GENAP" : "GANJIL")  ?> TA. <?= e($tahun_akademik->ta)  ?></strong></center></P>
        <P><center><strong>ANGKATAN  20<?= e(substr($mahasiswa->nim, 0,2)) ?> </strong></center></P>
        <br>
        <div class="col-sm-6 col-md-6 col-lg-6">
            <table class="table">
                <tr>
                    <td><strong>NAMA</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($mahasiswa->nama_mahasiswa) ?></td>
                </tr>
                <tr>
                    <td><strong>NIM</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($mahasiswa->nim) ?></td>
                </tr>
                <tr>
                    <td><strong>NPM</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($mahasiswa->npm) ?></td>
                </tr>
            </table>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-6 ">
            <table class="table">
                <tr>
                    <td><strong>PROGRAM STUDI</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($prodi->nama_program_studi) ?></td>
                </tr>
                <tr>
                    <td><strong>FAKULTAS</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($prodi->nama_fakultas) ?></td>
                </tr>
            </table>
        </div>
        <br>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <?php $total_sks = 0; $total_sksn = 0; foreach ($data as $key) : ?>
                <p><strong>SEMESTER  <?= e($key['semester']) ?></strong></p>
                <table class="table demo-table">
                    <thead>
                    <tr>
                        <th class="th-color" width="20"><center>No.</center></th>
                        <th class="th-color" width="200"><center>KODE MK</center></th>
                        <th class="th-color"><center>MATAKULIAH</center></th>
                        <th class="th-color" width="100"><center>SKS</center></th>
                        <th class="th-color" width="100"><center>GRADE</center></th>
                        <th class="th-color" width="100"><center>SKSN</center></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (isset($key['data_nilai'])) :
                        $sks=0; $sksn=0; $j=1; foreach ($key['data_nilai'] as $row) : ?>
                        <tr>
                            <td><center><?= $j++ ?></center></td>
                            <td><center><?= e($row['kode_matakuliah']) ?></center></td>
                            <td><?= e($row['nama_matakuliah']) ?></td>
                            <td><center><?= e(substr($row['kode_matakuliah'], 4,1)) ?></center></td>
                            <td><center><?= e($row['grade']) ?></center></td>
                            <td><center><?= e($row['sksn']) ?></center></td>
                        </tr>

                    <?php endforeach;
                        $total_sks = $total_sks = $key['sks'];
                        $total_sksn = $total_sksn = $key['sksn'];
                    endif;
                    ?>
                    </tbody>
                </table>
                <br>
            <?php endforeach; ?>
        </div>
        <!-- end.col-12 -->
        <div class="col-sm-12 col-md-12 col-lg-12">
            <table class="table table-bordered" >
                <tr>
                    <td class="th-color" ><center><strong>JUMLAH</strong></center></td>
                    <td class="th-color" width="100"><center><strong><?= e($total_sks) ?></strong></center></td>
                    <td class="th-color" width="100"><center></center></td>
                    <td class="th-color" width="100"><center><strong><?= e($total_sksn) ?></strong></center></td>
                </tr>
            </table>
            <br>
            <center>
                <table class="table" style="width:220px;">
                    <tr>
                        <td rowspan="2"><center><strong>IPK = </strong></center></td>
                        <td style="border-bottom:1px solid black;"><strong>SKSN</strong></td>
                        <td rowspan="2">=</td>
                        <td style="border-bottom:1px solid black;"><strong><?= e($total_sksn) ?></strong></td>
                        <td rowspan="2"><strong>= <?= e($total_sks == 0 ? '0' : number_format($total_sksn/$total_sks, 2)) ?></strong></td>
                    </tr>
                    <tr>
                        <td ><strong>SKS</strong></td>
                        <td ><strong><?= e($total_sks) ?></strong></td>
                    </tr>
                </table>
            </center>
        </div>
    </div>
</div>

