<div class="box box-solid flat">
    <div class="box-body">
        <?php $i=1; foreach ($kode_krs as $row) : ?>
            <a href="<?= site_url('mahasiswa/khs/index/'.$row->kode_krs) ?>" class="btn bg-navy flat btn-xs"><i class="fa fa-arrow-circle-right"></i> | SEMESTER <?= $i++ ?></a>
        <?php endforeach; ?>
    </div>
</div>

<?php if (isset($data['data_nilai'])) :?>
<div class="box box-primary flat">
    <div class="box-body table-responsive">
        <p align="right"><a href="<?= site_url('mahasiswa/khs/cetak/'.$data['krs'].'/'.$data['nim']) ?>" class="btn btn-danger btn-xs" ><i class="fa fa-download"></i> Download</a></p>    
        
        <p align="center"><strong>KARTU HASIL STUDI (KHS)</strong></p>
        <p align="center"><strong>SEMESTER <?= $data['semester'] % 2 == (0) ? "GENAP" : "GANJIL" ; ?> TA. <?= e($data['tahun_akademik']) ?></strong></p>
        <br>
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-6">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr><th width="40%">Nama Mahasiswa</th><td><?= e($data['nama_mahasiswa']) ?></td></tr>
                            <tr><th>NIM</th><td><?= e($data['nim']) ?></td></tr>
                            <tr><th>Semester</th><td><?= $data['semester'] ?></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr><th width="40%">Program Studi</th><td><?= e($prodi->nama_program_studi) ?></td></tr>
                            <tr><th>Fakultas</th><td><?= e($prodi->nama_fakultas) ?></td></tr>
                            <tr><th>Kurikulum</th><td><?= e($data['kurikulum']) ?></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="table-responsive">
        <table class="table table-bordered table-striped demo-table khs-table">
            <thead>
            <tr>
                <th class="th-center" width="40">No.</th>
                <th class="th-center">Kode</th>
                <th class="th-center">Matakuliah</th>
                <th class="th-center">SKS</th>
                <th class="th-center">Grade</th>
                <th class="th-center">SKSN</th>
                <th class="th-center">Ket</th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1; $sksn=0; $sks=0; foreach ($data['data_nilai'] as $row) : ?>
                <tr>
                    <td align="center"><?= $i++ ?>.</td>
                    <td align="center"><?= e($row['kode_matakuliah']) ?></td>
                    <td><?= e($row['nama_matakuliah']) ?></td>
                    <td align="center"><?= $row['sks'] ?></td>
                    <td align="center"><?= e($row['grade']) ?></td>
                    <td align="center"><?= $row['sksn'] ?></td>
                    <td align="center">-</td>
                </tr>
                <?php
                $sksn = $sksn + $row['sksn'];
                $sks = $sks + $row['sks'];

                ?>
            <?php endforeach; ?>
            <tr class="th-center">
                <td colspan="5"><strong>Jumlah</strong></td>
                <td><strong><?= $sksn ?></strong></td>
                <td></td>
            </tr>
            </tbody>
        </table>
        </div>
        <br>
        <?php
        $ipk = $sks > 0 ? $sksn/$sks : 0;

        if ($ipk >= 3.5)
        {
            $jumlah_maksimum_sks = 24;
        }
        elseif ($ipk >= 3.25)
        {
            $jumlah_maksimum_sks = 23;
        }
        elseif ($ipk >= 3)
        {
            $jumlah_maksimum_sks = 22;
        }
        elseif ($ipk >= 2.75)
        {
            $jumlah_maksimum_sks = 21;
        }
        elseif ($ipk >= 2.5)
        {
            $jumlah_maksimum_sks = 20;
        }
        elseif ($ipk >= 2.25)
        {
            $jumlah_maksimum_sks = 19;
        }
        elseif ($ipk >= 2)
        {
            $jumlah_maksimum_sks = 18;
        }
        elseif ($ipk >= 1.75)
        {
            $jumlah_maksimum_sks = 16;
        }
        elseif ($ipk >= 1.5)
        {
            $jumlah_maksimum_sks = 14;
        }
        else
        {
            $jumlah_maksimum_sks = 12;
        }
        ?>

        <div class="row" style="margin-top:15px;">
            <div class="col-md-6 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr><th width="55%">Jumlah SKS yang ditempuh</th><td><?= $sks ?></td></tr>
                            <tr><th>IP Semester ini</th><td><?= number_format($ipk, 2) ?></td></tr>
                            <?php if (substr($data['nim'], 4, 1) != 3) : ?>
                                <tr><th>Maksimum SKS Semester Depan</th><td><?= $jumlah_maksimum_sks ?></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <p>Mataram, <?= date("d M Y") ?></p>
                <p>Wakil Rektor I,</p>
                <br>
                <br>
                <p><u>Dr. Khasnur Hidjah, S.Kom, M.Cs</u></p>
                <p>NIP : 197202072005012001</p>
            </div>
        </div>
    </div>
    <!-- end.box-body -->
</div>
<?php else: ?>
    <div class="callout callout-danger">
        <h4>Peringatan!</h4>

        <p>Data <strong>KHS</strong> tidak ditemukan.</p>
    </div>
<?php endif; ?>
