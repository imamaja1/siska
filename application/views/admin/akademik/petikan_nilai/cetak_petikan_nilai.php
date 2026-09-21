<?php
if (!isset($total_sks) || !isset($total_sksn) || !isset($ipk)) {
    $total_sks = 0;
    $total_sksn = 0;
    foreach ((array) $data as $key) {
        if (!isset($key['data_nilai'])) {
            continue;
        }
        foreach ($key['data_nilai'] as $row) {
            if ((isset($row['semester']) && $row['semester'] <= $semester) || (isset($row['semester']) && $row['semester'] == 'K')) {
                $total_sks = $total_sks + ($row['sks_teori'] + $row['sks_praktek'] + $row['sks_praktikum']);
                $total_sksn = $total_sksn + $row['sksn'];
            }
        }
    }
    $ipk = $total_sks != 0 ? $total_sksn / $total_sks : 0;
}
?>
<html>
    <head>
        <style>
            body {
                font-family: sans-serif;
                font-size: 8pt;
            }

            p.keterangan {
                margin: 0pt;
                font-family: sans-serif;
                font-size: 8pt;
                font-weight: bold;
                text-decoration: underline;
            }

            td {
                vertical-align: top;
            }

            .items td {
                border: 0.1mm solid #000000;
            }

            table thead td {
                background-color: #EEEEEE;
                text-align: center;
                border: 0.1mm solid #000000;
            }

            td.right {
                text-align: right;
            }

            .items td.blanktotal {
                background-color: #FFFFFF;
                border: 0mm none #000000;
                border-top: 0.1mm solid #000000;
                border-right: 0.1mm solid #000000;
            }

            .items td.totals {
                text-align: right;
                border: 0.1mm solid #000000;
            }

            .footer {
                font-family: sans-serif;
                font-size: 8pt;
            }

            .garis_bawah {
                text-decoration: underline;
            }

            h4 {
                text-align: center;
            }
        </style>
    </head>
    <body>

        <!-- Start Header Petikan Nilai -->
        <hr style="border: 5px solid black">
        <h4>PETIKAN NILAI MAHASISWA SEMESTER <?= $tahun_akademik->semester != 1  ? "GENAP" : "GANJIL"; ?>
            TA. <?= e($tahun_akademik->ta) ?> ANGKATAN 20<?= e(substr($mahasiswa->nim, 0, 2)) ?></h4>
        <table width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td><b>Nama Mahasiswa</b></td>
                            <td><b>:</b></td>
                            <td><?php echo e($mahasiswa->nama_mahasiswa); ?></td>
                        </tr>
                        <tr>
                            <td><b>NIM</b></td>
                            <td><b>:</b></td>
                            <td><?php echo e($mahasiswa->nim); ?></td>
                        </tr>
                        <tr>
                            <td><b>NPM</b></td>
                            <td><b>:</b></td>
                            <td><?php echo e($mahasiswa->npm); ?></td>
                        </tr>
                    </table>
                </td>
                <td width="10%">&nbsp;</td>
                <td>
                    <table>
                        <tr>
                            <td><b>Jurusan</b></td>
                            <td><b>:</b></td>
                            <td><?php echo strtoupper(e($prodi->nama_program_studi)); ?></td>
                        </tr>
                        <tr>
                            <td><b>Fakultas</b></td>
                            <td><b>:</b></td>
                            <td><?php echo e(bodo_kop($mahasiswa->nim)['nama_fakultas']); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!-- End Header Petikan Nilai -->
        <!-- Start Content Petikan Nilai -->
        <div style="width: 50%; float: left;">
            <table border="1" class="items" width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
                <thead>
                    <tr>
                        <th width="20" style="text-align: center;">No.</th>
                        <th width="30" style="text-align: center;">KODE MK</th>
                        <th width="110" style="text-align: center;">MATAKULIAH</th>
                        <th width="20" style="text-align: center;">SKS</th>
                        <th width="30" style="text-align: center;">GRADE</th>
                        <th width="20" style="text-align: center;">SKSN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i <= 4; $i++) :
                        if (isset($data[$i]['data_nilai'])) :
                            ?>
                            <?php
                            $j = 1;
                            foreach ($data[$i]['data_nilai'] as $row) :
                                ?>
                                <tr>
                                    <td align="center">
                                        <?= $j++ ?>.
                                    </td>
                                    <td align="center"><?= e($row['kode_matakuliah']) ?></td>
                                    <td><?= e($row['nama_matakuliah']) ?></td>
                                    <td align="center">
                                        <?php
                                        if ($row['semester'] <= $semester || $row['semester'] == 'K') {
                                            echo e($row['sks']);
                                        } else {
                                            echo '0';
                                        }
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?php
                                        if ($row['semester'] <= $semester || $row['semester'] == 'K') {
                                            echo e($row['grade']);
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?php
                                        if ($row['semester'] <= $semester || $row['semester'] == 'K') {
                                            echo e($row['sksn']);
                                        } else {
                                            echo "0";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                            ?>
                            <tr>
                                <td colspan="6"></td>
                            </tr>
                            <?php
                        endif;
                    endfor;
                    ?>
                </tbody>
            </table>
        </div>

        <div style="width: 50%; float: left;">
            <div style="padding-left: 2px;">
                <table border="1" class="items" width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
                    <thead>
                        <tr>
                            <th width="20" style="text-align: center;">No.</th>
                            <th width="30" style="text-align: center;">KODE MK</th>
                            <th width="110" style="text-align: center;">MATAKULIAH</th>
                            <th width="20" style="text-align: center;">SKS</th>
                            <th width="30" style="text-align: center;">GRADE</th>
                            <th width="20" style="text-align: center;">SKSN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($k = 5; $k <= 7; $k++) :
                            if (isset($data[$k]['data_nilai'])) :
                                $j = 1;
                                foreach ($data[$k]['data_nilai'] as $row) :
                                    ?>
                                    <tr>
                                        <td align="center"><?= $j++ ?>.</td>
                                        <td align="center"><?= e($row['kode_matakuliah']) ?></td>
                                        <td><?= e($row['nama_matakuliah']) ?></td>
                                        <td align="center">
                                            <?php
                                            if ($row['semester'] <= $semester || $row['semester'] == 'K') {
                                                echo e($row['sks']);
                                            } else {
                                                echo "0";
                                            }
                                            ?>
                                        </td>
                                        <td align="center">
                                            <?php
                                            if ($row['semester'] <= $semester || $row['semester'] == 'K') {
                                                echo e($row['grade']);
                                            } else {
                                                echo "-";
                                            }
                                            ?>
                                        </td>
                                        <td align="center">
                                            <?php
                                            if ($row['semester'] <= $semester || $row['semester'] == 'K') {
                                                echo e($row['sksn']);
                                            } else {
                                                echo "0";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="6"> </td>
                                </tr>
                                <?php
                            endif;
                        endfor;
                        ?>
                    </tbody>
                </table>
            </div>
            <br>
            <div style="padding-left: 5px;">
                <table width="100%" style="font-weight: bold; font-size: 8pt; border-collapse: collapse; float:left; "
                       cellpadding="2" align="center">
                    <tr>
                        <td width="15%" rowspan="2" valign="middle" align="center">IPK</td>
                        <td width="5%" rowspan="2" valign="middle" align="center">=</td>
                        <td width="25%" style="border-bottom:1px solid black;" align="center">&#931; SKSN</td>
                        <td width="5%" rowspan="2" valign="middle" align="center">=</td>
                        <td width="30%" style="border-bottom:1px solid black;" align="center"><?php echo e($total_sksn); ?></td>
                        <td width="5%" rowspan="2" valign="middle" align="center">=</td>
                        <td width="15%" rowspan="2" valign="middle"
                            align="center"><?php echo e(number_format($ipk, 2, '.', '')); ?></td>
                    </tr>
                    <tr>
                        <td align="center">&#931; SKS</td>
                        <td align="center"><?= e($total_sks); ?></td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
                <br>
                <br>
                <table width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2" align="center">
                    <tr>
                        <td>Mataram, <?= tgl_indo(date('d F Y')) ?></td>
                    </tr>
                    <tr>
                        <td>Dekan,</td>
                    </tr>
                    <tr>
                        <td><img style="height: 50px;" src="<?= e(!empty($ttd) && file_exists(FCPATH . 'assets/signature-dosen/' . $ttd) ? base_url('assets/signature-dosen/'.rawurlencode($ttd)) : base_url('assets/gambar/notfound.png')) ?>">	</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="garis_bawah"><?php echo e(bodo_kop($mahasiswa->nim)['dekan']); ?></div>
                            NIK: <?php echo e(bodo_kop($mahasiswa->nim)['nik']); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>
