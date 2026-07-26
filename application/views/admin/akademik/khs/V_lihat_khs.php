<div class="box box-solid flat">
    <div class="box-body">
        <!--<a href="<?= site_url('admin/akademik/khs/data_mahasiswa_khs') ?>" class="btn btn-success btn-sm flat"><i class="fa fa-arrow-left"></i> Kembali</a>-->
        <a href="#" onclick="history.back()" class="btn btn-success btn-sm flat"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-body table-responsive">
        <p><center><strong>KARTU HASIL STUDI (KHS)</strong></center></p>
        <p><center><strong>SEMESTER <?= $data['semester'] % 2 == (0) ? "GENAP" : "GANJIL"; ?> TA. <?= e($data['tahun_akademik']) ?></strong></center></p>
        <br>
        <div class="col-sm-6 col-md-6 col-lg-6">
            <table class="table">
                <tr>
                    <td><strong>Nama Mahasiswa</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data['nama_mahasiswa']) ?></td>
                </tr>
                <tr>
                    <td><strong>NIM</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data['nim']) ?></td>
                </tr>
                <tr>
                    <td><strong>Semester</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data['semester']) ?></td>
                </tr>
            </table>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-6">
            <table class="table">
                <tr>
                    <td><strong>Program Studi</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($prodi->nama_program_studi) ?></td>
                </tr>
                <tr>
                    <td><strong>Fakultas</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($prodi->nama_fakultas) ?></td>
                </tr>
                <tr>
                    <td><strong>Kurikulum</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data['kurikulum']) ?></td>
                </tr>
            </table>
        </div>
        <table class="table demo-table">
            <thead>
                <tr>
                    <th id="color" width="20"><center>No.</center></th>
            <th id="color"><center>Kode</center></th>
            <th id="color"><center>Matakuliah</center></th>
            <th id="color"><center>SKS</center></th>
            <th id="color"><center>Grade</center></th>
            <th id="color"><center>SKSN</center></th>
            <th id="color"><center>Ket</center></th>
            </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                $sksn = 0;
                $sks = 0;
                foreach ($data['data_nilai'] as $row) : ?>
                    <tr>
                        <td><center><?= $i++ ?></center></td>
                <td><center><?= e($row['kode_matakuliah']) ?></center></td>
                <td><?= e($row['nama_matakuliah']) ?></td>
                <td><center><?= e($row['sks']) ?></center></td>
                <td><center><?= e($row['grade']) ?></center></td>
                <td><center><?= e($row['sksn']) ?></center></td>
                <td><center><?= e($row['tb'] == 'A' ? 'TB' : '') ?></center></td>
                </tr>
                <?php
                $sksn = $sksn + $row['sksn'];
                $sks = $sks + $row['sks'];
                ?>
<?php endforeach; ?>
            <tr>
                <td colspan="5"></td>
                <td id="color"><center><strong><?= e($sksn) ?></strong></center></td>
            <td id="color"></center></td>
            </tr>
            </tbody>
        </table>
        <br>
        <div class="col-sm-4 col-md-4 col-lg-4">
            <table class="table">
                <tr>
                    <td><strong>Jumlah SKS yang ditempuh</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($sks) ?></td>
                </tr>
                <tr>
                    <td><strong>IP Semester ini</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e(sprintf("%.2f",$sksn / $sks)) ?></td>
                </tr>
                <?php if (substr($data['nim'],4,1) !=3) :?>
                <tr>
                    <td><strong>Maksismum SKS Semester Depan</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data['maksimum_sks']) ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
        <br>
        <br>
        <br>
        <br>
        <div class="col-sm-4 col-md-4 col-lg-4 pull-right">
            <p>Mataram, <?= date("d M Y") ?></p>
            <p>Wakil Rektor I,</p>
            <br>
            <br>
            <p><u>Dr.Khasnur Hidjah, S.Kom., M.Cs.</u></p>
            <p>NIP : 197202072005012001 </p>
        </div>
    </div>
    <!-- end.box-body -->
</div>