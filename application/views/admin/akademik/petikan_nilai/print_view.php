<html>
    <head>
        <link rel="stylesheet" href="<?= base_url('assets/print-preview/src/css/print-preview.css') ?>">
        <script src="<?= base_url('assets/plugins/jQuery/jQuery-2.2.0.min.js'); ?>"></script>
        <script src="<?= base_url('assets/print-preview/src/jquery.print-preview.js') ?>"></script>

        <style>
            @media all {
                body {
                    font-family: sans-serif;
                    font-size: 8pt;
                    padding: 0px;
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
                .header-text{
                    font-size: 10pt;
                    text-align: center;
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
                .data-diri {
                    font-size: 8pt;
                }
                @page {
                    size: auto;   /* auto is the initial value */

                    /* this affects the margin in the printer settings */
                    margin: 4mm 10mm 10mm 10mm;
                }
            }

        </style>
    </head>
    <body style="font-size: 8pt; font-family: sans-serif; margin: 0px">
        <table width="100%" style="margin-top: 0px">
            <tr>
                <td colspan="2" align="right" style="font-size: 8pt;">BG/BAA/QSR/032-00/09</td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align: bottom; font-family:serif; font-size: 8pt;">
                    <!--<img style="height: 120px;" src="<?= base_url('assets/gambar/kop/' . $prodi->kode_fakultas . '.png') ?>" />-->
                    <img style="height: 120px;" src="<?= base_url('assets/gambar/kop/' . bodo_kop($mahasiswa->nim)['kop']); ?>">
                </td>
            </tr>
        </table>
        <!-- Start Header Petikan Nilai -->
        <hr style="border: 2px solid black;">
        <h4 class="header-text" style="text-align: center; font-size: 8pt">PETIKAN NILAI MAHASISWA SEMESTER <?= $tahun_akademik->semester % 2 == (0) ? "GENAP" : "GANJIL"; ?>
            TA. <?= $tahun_akademik->ta ?> ANGKATAN 20<?= substr($mahasiswa->nim, 0, 2) ?></h4>
        <table width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td style="font-size: 8pt;"><b>Nama Mahasiswa</b></td>
                            <td style="font-size: 8pt;"><b>:</b></td>
                            <td style="font-size: 8pt;"><?php echo $mahasiswa->nama_mahasiswa; ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 8pt;"><b>NIM</b></td>
                            <td style="font-size: 8pt;"><b>:</b></td>
                            <td style="font-size: 8pt;"><?php echo $mahasiswa->nim; ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 8pt;"><b>NPM</b></td>
                            <td style="font-size: 8pt;"><b>:</b></td>
                            <td style="font-size: 8pt;"><?php echo $mahasiswa->npm; ?></td>
                        </tr>
                    </table>
                </td>
                <td width="10%">&nbsp;</td>
                <td>
                    <table>
                        <tr>
                            <td style="font-size: 8pt;"><b>Jurusan</b></td>
                            <td style="font-size: 8pt;"><b>:</b></td>
                            <td style="font-size: 8pt;"><?php echo strtoupper($prodi->nama_program_studi); ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 8pt;"><b>Fakultas</b></td>
                            <td style="font-size: 8pt;"><b>:</b></td>
                            <td style="font-size: 8pt;"><?php echo bodo_kop($mahasiswa->nim)['nama_fakultas']; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

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
                    $total_sks = 0;
                    $total_sksn = 0;
                    for ($i = 0; $i <= 4; $i++) :
                        if (isset($data[$i]['data_nilai'])) :
                            ?>
                            <?php
                            $sks = 0;
                            $sksn = 0;
                            $j = 1;
                            foreach ($data[$i]['data_nilai'] as $row) :
                                ?>
                                <tr>
                                    <td align="center"><?= $j++ ?>.</td>
                                    <td align="center"><?= $row['kode_matakuliah'] ?></td>
                                    <td><?= $row['nama_matakuliah'] ?></td>
                                    <td align="center">
                                        <?php
                                  if (($row['grade'] == "E") || ($row['grade'] == "-") || !$row['nilai_harian'] || !$row['nilai_uas']) {
                                            echo "0";
                                        } else {
                                            echo $row['sks'];
                                        }
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?= isset($row['grade']) ? $row['grade'] : '-' ?>
                                    </td>
                                    <td align="center">
                                      <?php
                                        if ($row['grade'] == "E"|| !$row['nilai_harian'] || !$row['nilai_uas']) {
                                            echo "0";
                                        } else {
                                            echo $row['sksn'];
                                        }
                                        ?>
                                  </td>
                                </tr>
                                <?php
//                    if ($row['sksn'] != 0) {
                                if ($row['grade'] == "E") {
                                  if($row['jumlah_data'] == 0 ){
                                      $sks = ($sks + $row['sks']) - ($row['sks_teori'] + $row['sks_praktek'] + $sks['sks_praktikum']);
                                      $sksn = $sksn + $row['sksn'];
                                  }
                                } else {
                                    $sks = $sks + $row['sks'];
                                    $sksn = $sksn + $row['sksn'];
                                }
//                    } else {
//                        $sks = $sks + 0;
//                        $sksn = $sksn + 0;
//                    }
                                ?>
                                <?php
                            endforeach;
                            ?>
                            <tr>
                                <td colspan="6"></td>
                            </tr>
                            <?php
                            $total_sks = $total_sks + $sks;
                            $total_sksn = $total_sksn + $sksn;
//                    $ipk = $total_sksn / $total_sks;
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
                        $total_sks1 = 0;
                        $total_sksn1 = 0;
                        for ($k = 5; $k <= 7; $k++) :
                            if (isset($data[$k]['data_nilai'])) :
                                $sks1 = 0;
                                $sksn1 = 0;
                                $j = 1;
                                foreach ($data[$k]['data_nilai'] as $row) :
                                    ?>
                                    <tr>
                                        <td align="center"><?= $j++ ?>.</td>
                                        <td align="center"><?= $row['kode_matakuliah'] ?></td>
                                        <td><?= $row['nama_matakuliah'] ?></td>
                                        <!--<td align="center"><?= substr($row['kode_matakuliah'], 4, 1) ?></td>-->
                                        <td align="center">
                                            <?php
                                            if (($row['grade'] == "E") || ($row['grade'] == "-") || !$row['nilai_harian'] || !$row['nilai_uas']) {
                                                echo "0";
                                            } else {
                                                echo substr($row['kode_matakuliah'], 4, 1);
                                            }
                                            ?>
                                        </td>
                                        <td align="center">
                                            <?php
                                            if (($row['grade'] == "E") || ($row['grade'] == "-") || !$row['nilai_harian'] || !$row['nilai_uas']) {
                                                echo "-";
                                            } else {
                                                echo $row['grade'];
                                            }
                                            ?>
                                        </td>
                                        <td align="center">
                                          <?php
                                            if (($row['grade'] == "E") || ($row['grade'] == "-") || !$row['nilai_harian'] || !$row['nilai_uas']) {
                                                echo "0";
                                            } else {
                                                echo $row['sksn'];
                                            }
                                            ?>
                                      </td>
                                    </tr>
                                    <?php
//                        if ($row['sks'] == ") {

                                    if ($row['grade'] == "E" ) {
                                      	if($row['jumlah_data'] == 0){
                                            $sks = ($sks + $row['sks']) - ($row['sks_teori'] + $row['sks_praktek'] + $sks['sks_praktikum']);
                                            $sksn = $sksn + $row['sksn'];
                                        }
                                    } else {
                                        $sks1 = $sks1 + $row['sks'];
                                        $sksn1 = $sksn1 + $row['sksn'];
                                    }


//                        } else {
//                            $sks1 = $sks1 + 0;
//                            $sksn1 = $sksn1 + 0;
//                        }
                                    ?>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="6"></td>
                                </tr>

                                <?php
                                $total_sks1 = $total_sks1 + $sks1;
                                $total_sksn1 = $total_sksn1 + $sksn1;
//                    $ipk = $total_sksn / $total_sks;
                                ?>
                                <?php
                            endif;
                        endfor;
                        $super_sks = $total_sks + $total_sks1;
                        $super_sksn = $total_sksn + $total_sksn1;
                        $super_sks !== 0 ? $ipk = $super_sksn / $super_sks : $ipk = 0;
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
                        <td width="30%" style="border-bottom:1px solid black;" align="center"><?php echo $super_sksn; ?></td>
                        <td width="5%" rowspan="2" valign="middle" align="center">=</td>
                        <td width="15%" rowspan="2" valign="middle"
                            align="center"><?php echo number_format($ipk, 2, '.', ''); ?></td>
                    </tr>
                    <tr>
                        <td align="center">&#931; SKS</td>
                        <td align="center"><?php echo $super_sks; ?></td>
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
                       <img style="height: 50px" src="<?= base_url('assets/signature-dosen/' . $ttd) ?>"/>
                    </tr>
                    <tr>
                        <td>
                            <div class="garis_bawah"><?php echo bodo_kop($mahasiswa->nim)['dekan']; ?></div>
                            NIK: <?php echo bodo_kop($mahasiswa->nim)['nik']; ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <script>
            $(document).ready(function (e) {
                $.printPreview.loadPrintPreview();
                $(".close").click(function () {
                    window.top.close();
                });
            })

            $('body').click(function () {
                if (!$(this.target).is('#print-modal')) {
                window.top.close();
            }
            });

        </script>
    </body>
</html>