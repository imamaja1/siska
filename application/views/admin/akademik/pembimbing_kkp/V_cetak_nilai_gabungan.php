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
<p><img class="img" src="<?= base_url('assets/gambar/header_krs.png') ?>" alt=""><p>
<hr size="2"></p>

<p align="center"><strong>LEMBAR PENILAIAN (PEMBIMBINGAN ) LEMBAR PENILAIAN (PEMBIMBINGAN )</strong><br><strong>KULIAH KERJA PRAKTEK (KKP)</strong></p>
<!--<p align="center"><strong>KULIAH KERJA PRAKTEK (KKP)</strong></p>-->
<table style="font-size: 12px;">
    <tr>
        <td colspan="2">Nama Mahasiswa</td>
        <td colspan="3">: <?= e($data->nama_mahasiswa) ?></td>
    </tr>
    <tr>
        <td colspan="2">Nim</td>
        <td colspan="3">: <?= e($data->nim) ?></td>
    </tr>
    <tr>
        <td colspan="2">Lokasi Kerja Praktek</td>
        <td colspan="3">: <?= e($data->lokasi_kkp) ?></td>
    </tr>
    <tr>
        <td colspan="2">Lama Kerja Praktek</td>
        <td colspan="3">:</td>
    </tr>
    <tr>
        <td colspan="2">Bidang Kerja Praktek</td>
        <td colspan="3">: <?= e($data->bidang_kkp) ?></td>
    </tr>

    <thead>
    </thead>
    <tbody>
        <tr>
            <td align="center" style="border: 0.1pt solid black;">NO.</td>
            <td align="center" style="border: 0.1pt solid black;">UNSUR YANG DINILAI</td>
            <td align="center" style="border: 0.1pt solid black;">NILAI</td>
            <td align="center" style="border: 0.1pt solid black;">BOBOT</td>
            <td align="center" style="border: 0.1pt solid black;">HASIL</td>
        </tr>
        <tr>
            <td align="center" style="border: 0.1pt solid black;">1.</td>
            <td align="left" style="border: 0.1pt solid black;">Kinerja Kerja Praktek </td>
            <td align="center" style="border: 0.1pt solid black;"><?= $data->kinerja ?></td>
            <td align="center" style="border: 0.1pt solid black;">60%</td>
            <td align="center" style="border: 0.1pt solid black;"><?php $h_kinerja = $data->kinerja * 0.6; echo number_format($h_kinerja,2)?></td>
        </tr>
        <tr>
            <td align="center" style="border: 0.1pt solid black;">2.</td>
            <td align="left" style="border: 0.1pt solid black;">Pelaporan</td>
            <td align="center" style="border: 0.1pt solid black;"><?= $data->laporan ?></td>
            <td align="center" style="border: 0.1pt solid black;">40%</td>
            <td align="center" style="border: 0.1pt solid black;"><?php $h_laporan = $data->laporan* 0.4; echo number_format($h_laporan,2)?></td>
        </tr>
        <tr>
            <td align="center" colspan="2" style="border: 0.1pt solid black;"></td>
            <td align="left" colspan="2" style="border: 0.1pt solid black;">TOTAL NILAI</td>
            <td align="center" style="border: 0.1pt solid black;"><?= number_format($h_kinerja+$h_laporan,2) ?></td>
        </tr>
    </tbody>
</table>
<br>
<p align="left">Keterangan : <br> Range nilai untuk setiap unsur adalah 0 - 100</p>
<p align="right" style="margin-right: 500px;">Mataram,................20 <br> Yang Menilai(Pembimbing)</p>
<br>
<p align="right" style="margin-right: 500px;">(&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;) <br>
NIK/NIP. <?= e($data->nik) ?>&emsp;&emsp;&emsp;&emsp;&emsp;</p>

</body>
</html>

