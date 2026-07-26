<?php if ($data !== false) : ?>
<div class="box box-primary flat">
    <div class="box-header">

    </div>
    <div class="box-body table-responsive">
        <p><center><strong>Kartu Hasil Studi (KHS)</strong></center></p>
        <p><center><strong>Semester <?= $data['semester'] % 2 == (0) ? "Genap" : "Ganjil" ; ?> TA. <?= $data['tahun_akademik'] ?></strong></center></p>
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
                    <td><?= $data['semester'] % 2 == (0) ? "Genap" : "Ganjil" ; ?></td>
                </tr>
            </table>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-6">
            <table class="table">
                <tr>
                    <td><strong>Program Studi</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data['prodi']  ?></td>
                </tr>
                <tr>
                    <td><strong>Fakultas</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= bodo_kop($data['nim'])['nama_fakultas']  ?></td>
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
                <th class="th-color" width="20"><center>No.</center></th>
                <th class="th-color"><center>Kode</center></th>
                <th class="th-color"><center>Matakuliah</center></th>
                <th class="th-color"><center>SKS</center></th>
                <th class="th-color"><center>Grade</center></th>
                <th class="th-color"><center>SKSN</center></th>
                <th class="th-color"><center>Ket</center></th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1; $sksn=0; $sks=0; foreach ($data['data_nilai'] as $row) : ?>
                <tr>
                    <td><center><?= $i++ ?></center></td>
                    <td><center><?= $row['kode_matakuliah'] ?></center></td>
                    <td><?= $row['nama_matakuliah'] ?></td>
                    <td><center><?= $row['sks'] ?></center></td>
                    <td><center><?= $row['grade'] ?></center></td>
                    <td><center><?= $row['sksn'] ?></center></td>
                    <td><center>-</center></td>
                </tr>
                <?php
                $sksn = $sksn + $row['sksn'];
                $sks = $sks + $row['sks'];

                ?>
            <?php endforeach; ?>
            <tr>
                <td colspan="5"></td>
                <td class="th-color"><center><strong><?= $sksn ?></strong></center></td>
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
        /*$jumlah_maksimum_sks = 12;
        $ipk_semester_lalu = 0.00;	*/
        ?>
        <div class="col-sm-4 col-md-4 col-lg-4">
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
                <tr>
                    <td><strong>Maksismum SKS Semester Depan</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $jumlah_maksimum_sks ?></td>
                </tr>
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
<?php else:  ?>
    <div class="callout callout-info flat">
        <p>Tidak bisa menampilkan KHS semester lalu, karena mahasiswa yang bersangkutan masih semester 1.</p>
    </div>
<?php endif;  ?>