<html>
<head>
    <style>
        body {font-family: sans-serif;
            font-size: 8pt;
        }
        p.keterangan {
            margin: 0pt;
            font-family: sans-serif;
            font-size: 8pt;
            font-weight:bold;
            text-decoration:underline;
        }
        td { vertical-align: top; }
        .items td {
            border: 0.1mm solid #000000;
        }
        table thead td { background-color: #EEEEEE;
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
            text-decoration:underline;
        }
        h4 {
            text-align:center;
        }
    </style>
</head>
<body>
<!-- Start Header KHS -->
<br><br><br>
<h4>KARTU HASIL STUDI (KHS) KULIAH PROGRAM ALIH TAHUN (KPAT)<br />SEMESTER <?= $data['semester'] % 2 == (0) ? "GENAP" : "GANJIL" ; ?> TA. <?= e($data['tahun_akademik']) ?></h4>
<table width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
    <tr>
        <td>
            <table width="100%">
                <tr>
                    <td><b>Nama Mahasiswa</b></td>
                    <td><b>:</b></td>
                    <td><?= e($data['nama_mahasiswa'])  ?></td>
                </tr>
                <tr>
                    <td><b>NIM</b></td>
                    <td><b>:</b></td>
                    <td><?= e($data['nim'])  ?></td>
                </tr>
            </table>
        </td>
        <td width="10%">&nbsp;</td>
        <td>
            <table>
                <tr>
                    <td><b>Program Studi</b></td>
                    <td><b>:</b></td>
                    <td><?= e($prodi->nama_program_studi)  ?></td>
                </tr>
                <tr>
                    <td><b>Fakultas</b></td>
                    <td><b>:</b></td>
                    <td><?= e(bodo_kop($data['nim'])['nama_fakultas']) ?></td>
                </tr>
                <tr>
                    <td><b>Semester</b></td>
                    <td><b>:</b></td>
                    <td><?= e($semester) ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- End Header KHS -->
<br />
<!-- Start Content KHS -->
<table border="1" class="items" width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
    <tr>
        <th>NO.</th>
        <th align="center">KODE</th>
        <th>MATAKULIAH</th>
        <th align="center">SKS</th>
        <th align="center">GRADE</th>
        <th align="center">SKSN</th>
        <th align="center">KET</th>
    </tr>
    <?php $i=1; $sksn=0; $sks=0; foreach ($data['data_nilai'] as $row) : ?>
        <tr>
            <td style="text-align: center;"><?= $i++ ?></td>
            <td style="text-align: center;"><?= e($row['kode_matakuliah']) ?></td>
            <td><?= e($row['nama_matakuliah']) ?></td>
            <td style="text-align: center;"><?= e($row['sks']) ?></td>
            <td style="text-align: center;"><?= e($row['grade']) ?></td>
            <td style="text-align: center;"><?= e($row['sksn']) ?></td>
            <td style="text-align: center;">-</td>
        </tr>
        <?php
        $sksn = $sksn + $row['sksn'];
        $sks = $sks + $row['sks'];

        ?>
    <?php endforeach; ?>
    <tr class="dark">
        <td colspan="5" style="border-left:none; border-bottom:none;">&nbsp;</td>
        <td align="center"><?php echo e($sksn); ?></td>
        <td style="border-bottom:none; border-right:none;">&nbsp;</td>
    </tr>
</table>
<table>
    <tr>
        <td colspan="3"><strong>Jumlah SKS yang ditempuh</strong></td>
        <td><strong>=</strong></td>
        <td><strong><?php echo e($sks); ?></strong></td>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3"><strong>IP Semester ini</strong></td>
        <td><strong>=</strong></td>
        <td><strong>
                <?php
                $ipk = $sks ? $sksn / $sks : 0;
                $ipk = number_format($ipk,2);
                echo e($ipk);
                ?></strong>
        </td>
        <td colspan="2">&nbsp;</td>
    </tr>
</table>
<!-- End Content KHS -->
<!-- Start Footer KHS -->
<table width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
    <tr>
        <td>&nbsp;</td>
    </tr>
</table>
<br />
<table width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
    <tr>
        <td width="33%">&nbsp;</td>
        <td width="33%">&nbsp;</td>
        <td width="34%">Mataram, <?php echo date('d F Y'); ?></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>Wakil Rektor I,</td>
    </tr>
    <tr>
        <td height="60px">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>
          <p><u>Dr.Khasnur Hidjah, S.Kom., M.Cs.</u></p>
          <p>NIP : 197202072005012001 </p>
      	</td>
    </tr>
</table>
<!-- End Footer KHS -->
</body>
</html>
