<html>
    <head>
        <title>title</title>
        <style>
            .tableone{
                table-layout: fixed;
                width: 100%;
            }
            .padding{
                /*padding: 1px;*/
            }
        </style>
    </head>
    <body style="font-family: cambria;">
        <div style="text-align: center;">
            <?php
            if ($query1->tas == 1) {
                $label = "GANJIL";
            } else {
                $label = "GENAP";
            }
            ?>
            FORM VALIDASI NILAI UJIAN TENGAH SEMESTER <?= e($label) ?> TA. <?= e($query1->tahun_akademik) ?>
      

            <br><br>
        </div>
        <table style="border-collapse: separate;  font-size: 12px;">
            <thead>
                <tr>
                    <th style="text-align:left;">Program Studi</th>
                    <th>:<th>
                    <td><?= e($query1->nama_program_studi) ?> (<?= e($query1->nama_fakultas) ?>)</td>
                </tr>

                <tr>
                    <th style="text-align:left;">Nama Matakuliah</th>
                    <th>:<th>
                    <td><?= e($query1->mtkm) ?> - <?= e($query1->nama_matakuliah) ?> (<?= e($query1->sks_teori + $query1->sks_praktek + $query1->sks_praktikum) ?> SKS)</td>
                </tr>

                <tr>
                    <th style="text-align:left;">Semester / Kelas</th>
                    <th>:<th>
                    <td><?= e($query1->kls) ?>/<?= e($query1->nama_kelas) ?></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Dosen</th>
                    <th>:<th>
                    <td><?= e($nama_dosen->nama_dosen) ?></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Tanggal Cetak Form</th>
                    <th>:<th>
                    <td><?= tgl_indo(date('Y-m-d')) ?> <?= date('h:i:s A') ?></td>
                </tr>

            </thead>
        </table>

        <table class="tableone" border="1" style="border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr>
                    <th class="padding" align="center" style="width: 15px;">NO</th>
                    <th class="padding" align="center" style="width: 80px;">NIM</th>
                    <th class="padding">NAMA</th>
                    <th class="padding" align="center" style="width: 100px;">NILAI UTS</th>
                    <th class="padding" align="center" style="width: 100px;">GRADE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($query2 as $row):
                    ?>
                    <tr>
                        <td class="padding" align="center"><?= e($no++) ?></td>
                        <td class="padding" align="center"><?= e($row->nim) ?></td>
                        <td><?= e($row->nama_mahasiswa) ?></td>
                        <td align="center"><?= e($row->dummy_uts) ?></td>
                        <td align="center">
                            <?php
                            $data_penilaian = sistem_penilaian($row->nim);
                            foreach ($data_penilaian as $key) {
                                if (($key['nilai_minimum'] <= $row->dummy_uts) && ($row->dummy_uts <= $key['nilai_maksimum'])) {
                                    echo $key['grade'];
                                }
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br><br>

        <table border="0" align="center">
            <tr>
                <td>
                    <table border="0">
                        <tr>
                            <td style="text-align: center;">Kaprodi <?= e($query1->nama_program_studi) ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: center;"><br><br><br><br><br></td>
                        </tr>
                        <tr>
                            <td style="text-align: center;"> <?= e($query4->dosen_kaprodi) ?> <br> NIK <?= e($query4->nik_dosen_kaprodi) ?>
                        </tr>
                    </table>
                </td>
                <td style="width: 150px;"></td>
                <td>
                    <table border="0">
                        <tr>
                            <td style="text-align: center;">Dekan <?= e($query1->nama_fakultas) ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: center;"><br><br><br><br><br></td>
                        </tr>
                        <tr>
                            <td style="text-align: center;"> <?= e($query3->dosen_fakultas) ?> <br> NIK <?= e($query3->nik_dosen_fakultas) ?>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
