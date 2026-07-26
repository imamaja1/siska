<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$file_name.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .page
        {
            -webkit-transform: rotate(-90deg);
            -moz-transform:rotate(-90deg);
            filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
        }
    </style>
</head>
<body class="page">


<p><img src="<?= base_url('assets/gambar/header_krs.png') ?>" alt=""><p align="right"><strong>BG/BAA/QSR/007-00/09</strong></p>
<hr size="2"></p>
<p align="center">DAFTAR HADIR PERKULIAHAN SEMESTER <?= $tahun_akademik->semester == 1 ? 'GANJIL' : 'GENAP' ?> TAHUN AKADEMIK <?= $tahun_akademik->ta ?></p>
<table style="font-size: 12px;">
    <tr>
        <td colspan="2">KODE MATAKULIAH</td>
        <td colspan="16">: <?= $kode_matakuliah ?></td>
    </tr>
    <tr>
        <td colspan="2">MATAKULIAH</td>
        <td colspan="16">: <?= $nama_matakuliah ?> / <?= $nama_kelas->nama_kelas ?></td>
    </tr>
    <tr>
        <td colspan="2">JUMLAH SKS </td>
        <td colspan="16">: <?=  substr($kode_matakuliah,4,1) ?> SKS</td>
    </tr>
    <tr>
        <td colspan="2">SEMESTER </td>
        <td colspan="16">: <?=  substr($kode_matakuliah,5,1) ?></td>
    </tr>
    <tr>
        <td colspan="2">PROGRAM STUDI </td>
        <td colspan="16">: <?= $prodi->nama_program_studi ?></td>
    </tr>
    <tr>
        <td colspan="2">DOSEN </td>
        <td colspan="16">: <?php
            if (count($pengajar) == 0) {
                echo '-';
            }elseif(count($pengajar) == 1){
                foreach ($pengajar as $row) {
                    echo $row->nama_dosen;
                }
            }else{
                foreach ($pengajar as $row) {
                    echo $row->nama_dosen.' /';
                }
            }
            ?></td>
    </tr>
    <tr><td></td></tr>
    <thead>
    <tr>
        <th align="center" rowspan="3" style="border: 0.1pt solid black;">No.</th>
        <th align="center" rowspan="3" width="100" style="border: 0.1pt solid black;">NIM</th>
        <th align="center" rowspan="3" style="border: 0.1pt solid black;">Nama Mahasiswa</th>
        <th align="center" colspan="16" style="border: 0.1pt solid black;">TANGGAL PERTEMUAN / TANDA TANGAN</th>
        <th align="center" style="border: 0.1pt solid black;"></th>
    </tr>
    <tr>
        <th width="50" style="border: 0.1pt solid black;" align="center">1</th>
        <th width="50" style="border: 0.1pt solid black;" align="center">2</th>
        <th width="50" style="border: 0.1pt solid black;" align="center">3</th>
        <th width="50" style="border: 0.1pt solid black;" align="center">4</th>
        <th width="50" style="border: 0.1pt solid black;" align="center">5</th>
        <th width="50" style="border: 0.1pt solid black;" align="center">6</th>
        <th width="50" style="border: 0.1pt solid black;" align="center">7</th>
        <th width="50" style="border: 0.1pt solid black;" align="center">8</th>
        <th width="50" style="border: 0.1pt solid black;" align="center">9</th>
        <th width="50" style="border: 0.1pt solid black;" align="center">10</th>
        <th width="50" style="border: 0.1pt solid black;" align="center">11</th>
        <th width="50" style="border: 0.1pt solid black;" align="center">12</th>
        <th width="50" style="border: 0.1pt solid black;" align="center">13</th>
        <th width="50" style="border: 0.1pt solid black;" align="center">14</th>
        <th width="50" style="border: 0.1pt solid black;" align="center">15</th>
        <th width="50" style="border: 0.1pt solid black;" align="center">16</th>
        <th width="50" style="border: 0.1pt solid black;" align="center"></th>
    </tr>
    <tr>
        <th height="20" align="center" style="border: 0.1pt solid black;"></th>
        <th align="center" style="border: 0.1pt solid black;"></th>
        <th align="center" style="border: 0.1pt solid black;"></th>
        <th align="center" style="border: 0.1pt solid black;"></th>
        <th align="center" style="border: 0.1pt solid black;"></th>
        <th align="center" style="border: 0.1pt solid black;"></th>
        <th align="center" style="border: 0.1pt solid black;"></th>
        <th align="center" style="border: 0.1pt solid black;"></th>
        <th align="center" style="border: 0.1pt solid black;"></th>
        <th align="center" style="border: 0.1pt solid black;"></th>
        <th align="center" style="border: 0.1pt solid black;"></th>
        <th align="center" style="border: 0.1pt solid black;"></th>
        <th align="center" style="border: 0.1pt solid black;"></th>
        <th align="center" style="border: 0.1pt solid black;"></th>
        <th align="center" style="border: 0.1pt solid black;"></th>
        <th align="center" style="border: 0.1pt solid black;"></th>
        <th align="center" style="border: 0.1pt solid black;"></th>
    </tr>
    </thead>
    <tbody>
    <?php $no=1; foreach ($data as $row) : ?>
        <tr>
            <td align="center" style="border: 0.1pt solid black;"><?= $no++ ?>.</td>
            <td align="center" style="border: 0.1pt solid black;"><?= $row->nim ?></td>
            <td align="left" style="border: 0.1pt solid black;"><?= $row->nama_mahasiswa ?></td>
            <td style="border: 0.1pt solid black;"></td>
            <td style="border: 0.1pt solid black;"></td>
            <td style="border: 0.1pt solid black;"></td>
            <td style="border: 0.1pt solid black;"></td>
            <td style="border: 0.1pt solid black;"></td>
            <td style="border: 0.1pt solid black;"></td>
            <td style="border: 0.1pt solid black;"></td>
            <td style="border: 0.1pt solid black;"></td>
            <td style="border: 0.1pt solid black;"></td>
            <td style="border: 0.1pt solid black;"></td>
            <td style="border: 0.1pt solid black;"></td>
            <td style="border: 0.1pt solid black;"></td>
            <td style="border: 0.1pt solid black;"></td>
            <td style="border: 0.1pt solid black;"></td>
            <td style="border: 0.1pt solid black;"></td>
            <td style="border: 0.1pt solid black;"></td>
            <td style="border: 0.1pt solid black;"></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="3" align="center" style="border: 0.1pt solid black;">PARAP DOSEN</td>
        <td style="border: 0.1pt solid black;"></td>
        <td style="border: 0.1pt solid black;"></td>
        <td style="border: 0.1pt solid black;"></td>
        <td style="border: 0.1pt solid black;"></td>
        <td style="border: 0.1pt solid black;"></td>
        <td style="border: 0.1pt solid black;"></td>
        <td style="border: 0.1pt solid black;"></td>
        <td style="border: 0.1pt solid black;"></td>
        <td style="border: 0.1pt solid black;"></td>
        <td style="border: 0.1pt solid black;"></td>
        <td style="border: 0.1pt solid black;"></td>
        <td style="border: 0.1pt solid black;"></td>
        <td style="border: 0.1pt solid black;"></td>
        <td style="border: 0.1pt solid black;"></td>
        <td style="border: 0.1pt solid black;"></td>
        <td style="border: 0.1pt solid black;"></td>
        <td style="border: 0.1pt solid black;"></td>
    </tr>
    </tbody>
</table>
<br>
<p align="right" style="margin-right: 500px;">Dosen Pengampu</p>
<br><br>
<p align="right" style="margin-right: 500px;"><?php
    if (count($pengajar) == 0) {
        echo '-';
    }elseif(count($pengajar) == 1){
        foreach ($pengajar as $row) {
            echo $row->nama_dosen;
        }
    }else{
        foreach ($pengajar as $row) {
            echo $row->nama_dosen.' /';
        }
    }
    ?></p>
</body>
</html>

