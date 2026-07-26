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
        <p align="center"><strong>SEMESTER <?= $data['semester'] % 2 == (0) ? "GENAP" : "GANJIL" ; ?> TA. <?= $data['tahun_akademik'] ?></strong></p>
        <br>
        <div class="col-sm-6 col-md-6 col-lg-6">
            <table class="table">
                <tr>
                    <td><strong>Nama Mahasiswa</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data['nama_mahasiswa']  ?></td>
                </tr>
                <tr>
                    <td><strong>NIM</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data['nim']  ?></td>
                </tr>
                <tr>
                    <td><strong>Semester</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data['semester'] ?></td>
                </tr>
            </table>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-6">
            <table class="table">
                <tr>
                    <td><strong>Program Studi</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $prodi->nama_program_studi  ?></td>
                </tr>
                <tr>
                    <td><strong>Fakultas</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $prodi->nama_fakultas  ?></td>
                </tr>
                <tr>
                    <td><strong>Kurikulum</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data['kurikulum']?></td>
                </tr>
            </table>
        </div>
        <table class="table demo-table">
            <thead>
            <tr>
                <th id="th" width="20">No.</th>
                <th id="th">Kode</th>
                <th id="th">Matakuliah</th>
                <th id="th">SKS</th>
                <th id="th">Grade</th>
                <th id="th">SKSN</th>
                <th id="th">Ket</th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1; $sksn=0; $sks=0; foreach ($data['data_nilai'] as $row) : ?>
                <tr>
                    <td align="center"><?= $i++ ?>.</td>
                    <td align="center"><?= $row['kode_matakuliah'] ?></td>
                    <td><?= $row['nama_matakuliah'] ?></td>
                    <td align="center"><?= $row['sks'] ?></td>
                    <td align="center"><?= $row['grade'] ?></td>
                    <td align="center"><?= $row['sksn'] ?></td>
                    <td align="center">-</td>
                </tr>
                <?php
                $sksn = $sksn + $row['sksn'];
                $sks = $sks + $row['sks'];

                ?>
            <?php endforeach; ?>
            <tr>
                <td colspan="5"></td>
                <td align="center"><strong><?= $sksn ?></strong></td>
            </tr>
            </tbody>
        </table>
        <br>
        <?php
        $ipk = $sksn/$sks;

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

        <div class="col-lg-4">
            <table class="table">
                <tr>
                    <td><strong>Jumlah SKS yang ditempuh</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $sks  ?></td>
                </tr>
                <tr>
                    <td><strong>IP Semester ini</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= number_format($ipk,2)  ?></td>
                </tr>
                <?php if (substr($data['nim'],4,1) !=3 ) : ?>
                <tr>
                    <td><strong>Maksismum SKS Semester Depan</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $jumlah_maksimum_sks ?></td>
                </tr>
                <?php endif;?>
            </table>
        </div>
        <br>
        <br>
        <br>
        <br>
        <div class="col-lg-4 pull-right">
            <p>Mataram, <?= date("d M Y") ?></p>
            <p>Wakil Rektor I,</p>
            <br>
            <br>
           	<p><u>Dr. Khasnur Hidjah, S.Kom, M.Cs</u></p>
            <p>NIP : 197202072005012001</p>
        </div>
    </div>
    <!-- end.box-body -->
</div>
<?php else: ?>
    <div class="callout callout-danger">
        <h4>Pringatan!</h4>

        <p>Data <strong>KHS</strong> tidak ditemukan.</p>
    </div>
<?php endif; ?>