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
<hr style="border:5px solid black">
<h4>KARTU HASIL STUDI (KHS)<br />SEMESTER <?= $data['semester'] % 2 == (0) ? "GENAP" : "GANJIL"; ?> TA. <?= e($data['tahun_akademik']) ?></h4>
<table width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
    <tr>
        <td>
            <table width="100%">
                <tr>
                    <td><b>Nama Mahasiswa</b></td>
                    <td><b>:</b></td>
                    <td><?= e($data['nama_mahasiswa']) ?></td>
                </tr>
                <tr>
                    <td><b>NIM</b></td>
                    <td><b>:</b></td>
                    <td><?= e($data['nim']) ?></td>
                </tr>
            </table>
        </td>
        <td width="10%">&nbsp;</td>
        <td>
            <table>
                <tr>
                    <td><b>Program Studi</b></td>
                    <td><b>:</b></td>
                    <td><?= e($prodi->nama_program_studi) ?></td>
                </tr>
                <tr>
                    <td><b>Fakultas</b></td>
                    <td><b>:</b></td>
                    <td><?= e(bodo_kop($data['nim'])['nama_fakultas']) ?></td>
                </tr>
                <tr>
                    <td><b>Semester</b></td>
                    <td><b>:</b></td>
                    <td><?= e($data['semester']) ?></td>
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
    <?php
    // Variable untuk menampung NO.
    $i = 1;
    $sksn = 0;
    $sks = 0;
    // Variable untuk menampung jumlah SKS teori, praktek, dan praktikum
    foreach ($data['data_nilai'] as $row)
    {
        ?>
        <tr>
            <td align="center"><?php echo $i++ .'.'; ?></td>
            <td align="center"><?= e($row['kode_matakuliah']) ?></td>
            <td><?= e($row['nama_matakuliah']) ?></td>
            <td align="center"><?= e($row['sks']) ?></center></td>
            <td align="center"><?= e($row['grade']) ?></td>
            <td align="center"><?= e($row['sksn']) ?></td>
            <td align="center"><?= e($row['tb'] == 'A' ? 'TB' : '') ?></td>
        </tr>
        <?php
        $sksn = $sksn + $row['sksn'];
        $sks = $sks + $row['sks'];
    }
    ?>
    <tr class="dark">
        <td colspan="5" style="border-left:none; border-bottom:none;">&nbsp;</td>
        <td align="center"><?= e($sksn) ?></td>
        <td style="border-bottom:none; border-right:none;">&nbsp;</td>
    </tr>
</table>
<table>
    <tr>
        <td colspan="3"><strong>Jumlah SKS yang ditempuh</strong></td>
        <td><strong>=</strong></td>
        <td><strong><?= e($sks) ?></strong></td>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3"><strong>IP Semester ini</strong></td>
        <td><strong>=</strong></td>
        <td><strong><?= e($sks > 0 ? sprintf("%.2f", $sksn / $sks) : '0.00') ?></td>
        <td colspan="2">&nbsp;</td>
    </tr>
    <?php if (substr($data['nim'],4,1) !=3) : ?>
    <tr>
        <td colspan="3"><strong>Maksimum SKS semester depan</strong></td>
        <td><strong>=</strong></td>
        <td><strong><?= e($data['maksimum_sks']) ?></td>
        <td colspan="2">&nbsp;</td>
    </tr>
    <?php endif; ?>
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
        <td width="34%">Mataram, <?= tgl_indo(date('d F Y')) ?></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>Dekan,</td>
    </tr>
    <tr>
        <td height="10px">&nbsp;</td>
        <td>&nbsp;</td>
        <td><img src="<?= !empty($signature) && file_exists(FCPATH . 'assets/signature-dosen/' . $signature) ? base_url('assets/signature-dosen/'.$signature) : base_url('assets/gambar/notfound.png') ?>" onclick="cot(6)" id="upload_link-6"  style="height: 70px" alt=""></td>
        
      	
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div class="garis_bawah"><?php echo bodo_kop($data['nim'])['dekan']; ?></div>NIK: <?php echo bodo_kop($data['nim'])['nik']; ?></td>
    </tr>
</table>
<!-- End Footer KHS -->
</body>
</html>