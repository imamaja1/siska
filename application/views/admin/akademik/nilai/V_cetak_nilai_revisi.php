<html>
    <head>
        <title>title</title>
        <style>
            .tableone{
                table-layout: fixed;
                width: 100%;
            }
            .padding{
                padding: 5px;
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
            DAFTAR NILAI UJIAN AKHIR SEMESTER <?= $label ?> TA. <?= $query1->tahun_akademik ?>
            <br><br>
        </div>
        <table style="border-collapse: separate;  font-size: 12px;">
            <thead>
                <tr>
                    <th style="text-align:left;">Fakultas</th>
                    <th>:<th>
                    <td><?= $query1->nama_fakultas; ?></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Program Studi</th>
                    <th>:<th>
                    <td><?= $query1->nama_program_studi; ?></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Kode Matakuliah</th>
                    <th>:<th>
                    <td><?= $query1->mtkm; ?></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Nama Matakuliah</th>
                    <th>:<th>
                    <td><?= $query1->nama_matakuliah; ?></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Jumlah SKS</th>
                    <th>:<th>
                    <td><?= $query1->sks_teori + $query1->sks_praktek + $query1->sks_praktikum; ?></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Semester / Kelas</th>
                    <th>:<th>
                    <td><?= $query1->kls; ?>/<?= $query1->nama_kelas; ?></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Dosen</th>
                    <th>:<th>
                    <td><?= $nama_dosen->nama_dosen; ?></td>
                </tr>

            </thead>
        </table>

        <table class="tableone" border="1" style="border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr>
                    <th class="padding" style="width: 15px;">NO</th>
                    <th class="padding" style="width: 80px;">NIM</th>
                    <th class="padding" style="width: 350px;">NAMA</th>
                    <td class="padding" align="center">NILAI HARIAN</td>
                    <td class="padding" align="center">NILAI UTS</td>
                    <td class="padding" align="center">NILAI UAS</td>
                    <td class="padding" align="center">NILAI AKHIR</td>
                    <td class="padding" align="center">GRADE</td>
                    <?php if ($dosen): ?><?php else: ?>
                        <td class="padding" align="center">Status</td>
                    <?php endif; ?>
                </tr>
                <tr>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($query2 as $row):
                    ?>
                    <tr>
                        <td class="padding"><?= $no++; ?></td>
                        <td class="padding"><?= $row->nim; ?></td>
                        <td><?= $row->nama_mahasiswa ?></td>
                        <td align="center"><?= $row->harian ?></td>
                        <td align="center"><?= $row->uts ?></td>
                        <td align="center"><?= $row->uas ?></td>
                        <td align="center"><?= $row->na ?></td>
                        <td align="center"><?= $row->grade ?></td>
                        <?php if ($dosen): ?><?php else: ?>
                            <td align="center"><?= $row->block_id ? 'Block' :($row->mbkm_id ? 'MBKM' : '-') ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <table style="border-collapse: collapse; font-size: 12px;">
            <tr>
                <td>Keterangan:</td>
                <td>NA = (HARIAN x <?= $persentase->nilai_harian ?>%)+(UTS x <?= $persentase->nilai_uts ?>%)+(UAS x <?= $persentase->nilai_uas ?>%)</td>
            </tr>
            <tr>
                <td>GRADE:<br>
                    <table style="border-collapse: collapse;">
                        <?php
                        foreach ($query4 as $grad) {
                            ?>
                            <tr>
                                <td><?= $grad->grade ?></td>
                                <td>=</td>
                                <td><?= number_format($grad->nilai_minimum,1) ?> - <?= trunc($grad->nilai_maksimum,1);?></td>
                            </tr> 
                            <?php
                        }
                        ?>
                    </table>
            </tr>
        </table>
        <br><br>

        <table border="0" align="right">
            <tr>

                <td style="text-align: center;">Dosen Pengampu,</td>
                <td style="width: 80; text-align: center;"></td>


            </tr>
            <tr>

                <td style="text-align: center;"> <?php
                    echo '<img src="' . base_url() . 'qrcodeimage/'.$query1->kelas_id.'.png" />';
                    ?>
                </td>
                <td></td>

            </tr>
            <tr>

                <td style="text-align: center;"> <?= $nama_dosen->nama_dosen; ?> <br> NIK <?= $nama_dosen->nik; ?>
                </td>
                <td></td>

            </tr>
        </table>


    </body>
</html>
