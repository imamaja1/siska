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

            .header-text {
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
                size: auto;
                /* auto is the initial value */

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
                <img style="height: 120px;"
                    src="<?= base_url('assets/gambar/kop/' . bodo_kop($mahasiswa->nim)['kop']); ?>">
            </td>
        </tr>
    </table>
    <!-- Start Header Petikan Nilai -->
    <hr style="border: 2px solid black;">
    <h4 class="header-text" style="text-align: center; font-size: 8pt">PETIKAN NILAI <?= $status ? 'NON' : '' ?> MBKM
        MAHASISWA SEMESTER <?= $tahun_akademik->semester != 1 ? "GENAP" : "GANJIL"; ?>
        TA. <?= e($tahun_akademik->ta) ?> ANGKATAN 20<?= e(substr($mahasiswa->nim, 0, 2)) ?></h4>
    <table width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
        <tr>
            <td>
                <table width="100%">
                    <tr>
                        <td style="font-size: 8pt;"><b>Nama Mahasiswa</b></td>
                        <td style="font-size: 8pt;"><b>:</b></td>
                        <td style="font-size: 8pt;"><?= e($mahasiswa->nama_mahasiswa) ?></td>
                    </tr>
                    <tr>
                        <td style="font-size: 8pt;"><b>NIM</b></td>
                        <td style="font-size: 8pt;"><b>:</b></td>
                        <td style="font-size: 8pt;"><?= e($mahasiswa->nim) ?></td>
                    </tr>
                    <tr>
                        <td style="font-size: 8pt;"><b>NPM</b></td>
                        <td style="font-size: 8pt;"><b>:</b></td>
                        <td style="font-size: 8pt;"><?= e($mahasiswa->npm) ?></td>
                    </tr>
                </table>
            </td>
            <td width="10%">&nbsp;</td>
            <td>
                <table>
                    <tr>
                        <td style="font-size: 8pt;"><b>Jurusan</b></td>
                        <td style="font-size: 8pt;"><b>:</b></td>
                        <td style="font-size: 8pt;"><?= strtoupper(e($prodi->nama_program_studi)) ?></td>
                    </tr>
                    <tr>
                        <td style="font-size: 8pt;"><b>Fakultas</b></td>
                        <td style="font-size: 8pt;"><b>:</b></td>
                        <td style="font-size: 8pt;"><?= e(bodo_kop($mahasiswa->nim)['nama_fakultas']) ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="width: 50%; float: left; text-align: center; ">
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
                <?php $tmp = 1;
                $nomor = 1;
                $sks = 0;
                $sksn = 0;
                foreach ($data1 as $key => $value):
                    if ($value->grade) {
                        $sks += $value->sks;
                        $sksn += $value->nilai;
                    }
                    ?>
                    <tr>
                        <td><?= $nomor++ ?> </td>
                            <td><?= e($value->kode_matakuliah) ?> </td>
                            <td style="text-align: left;"><?= e($value->nama_matakuliah) ?> </td>
                            <td><?= e($value->sks) ?> </td>
                            <td><?= $value->grade ? e($value->grade) : '-' ?> </td>
                            <td><?= $value->nilai ? e($value->nilai) : '-' ?> </td>
                        </tr>
                        <?php
                        if ($value->semester != $tmp) {
                            $tmp = $value->semester;
                            $nomor = 1;
                            echo "<tr><td></td></td>";
                    }
                    ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div style="width: 50%; float: left; ">
        <div style="padding-left: 2px; text-align: center;">
            <table border="1" class="items" width="100%" style="font-size: 8pt; border-collapse: collapse;"
                cellpadding="2">
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
                    <?php $tmp = 6;
                    $nomor = 1;
                    foreach ($data2 as $key => $value):
                        if ($value->grade) {
                            $sks += $value->sks;
                            $sksn += $value->nilai;
                        }
                        ?>
                        <tr>
                            <td><?= $nomor++ ?> </td>
                            <td><?= e($value->kode_matakuliah) ?> </td>
                            <td style="text-align: left;"><?= e($value->nama_matakuliah) ?> </td>
                            <td><?= e($value->sks) ?> </td>
                            <td><?= $value->grade ? e($value->grade) : '-' ?> </td>
                            <td><?= $value->nilai ? e($value->nilai) : '-' ?> </td>
                        </tr>
                        <?php
                        if ($value->semester != $tmp) {
                            $tmp = $value->semester;
                            $nomor = 1;
                            echo "<tr><td></td></td>";
                        }
                        ?>
                    <?php endforeach;
                    $ipk = $sks ? $sksn / $sks : 0;
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
                    <td width="30%" style="border-bottom:1px solid black;" align="center"><?php echo $sksn; ?></td>
                    <td width="5%" rowspan="2" valign="middle" align="center">=</td>
                    <!-- <td width="15%" rowspan="2" valign="middle" align="center"><?php echo number_format($ipk, 2, '.', ''); ?></td> -->
                    <td width="15%" rowspan="2" valign="middle" align="center"><?= sprintf("%.2f", $ipk); ?>
                </tr>
                <tr>
                    <td align="center">&#931; SKS</td>
                    <td align="center"><?php echo $sks; ?></td>
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
                    <td>
                        <img style="height: 50px;" src="<?= base_url('assets/signature-dosen/' . $ttd); ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="garis_bawah"><?= e(bodo_kop($mahasiswa->nim)['dekan']) ?></div>
                        NIK: <?= e(bodo_kop($mahasiswa->nim)['nik']) ?>
                    </td>
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