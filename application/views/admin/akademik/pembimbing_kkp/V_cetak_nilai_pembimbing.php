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
        <td colspan="3">: <?= $data->nama_mahasiswa ?></td>
    </tr>
    <tr>
        <td colspan="2">Nim</td>
        <td colspan="3">: <?= $data->nim ?></td>
    </tr>
    <tr>
        <td colspan="2">Prodi</td>
        <td colspan="3">: <?= get_kode_prodi($data->nim)->nama_program_studi ?></td>
    </tr>
    <tr>
        <td colspan="2">Lokasi Kerja Praktek</td>
        <td colspan="3">: <?= $data->lokasi_kkp ?></td>
    </tr>
    <tr>
        <td colspan="2">Pembimbing </td>
        <td colspan="3">: <?=  $data->nama_dosen ?></td>
    </tr>
    <tr>
        <td colspan="2">Topik / Bidang</td>
        <td colspan="3">: <?= $data->bidang_kkp ?></td>
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
            <td align="center" style="border: 0.1pt solid black;"></td>
            <td align="center" style="border: 0.1pt solid black;">PELAPORAN</td>
            <td align="center" style="border: 0.1pt solid black;"></td>
            <td align="center" style="border: 0.1pt solid black;"></td>
            <td align="center" style="border: 0.1pt solid black;"></td>
        </tr>
        <tr>
            <td align="center" style="border: 0.1pt solid black;">1.</td>
            <td align="left" style="border: 0.1pt solid black;">BAB I Pendahuluan </td>
            <td align="center" style="border: 0.1pt solid black;"><?= $data->bab_1 ?></td>
            <td align="center" style="border: 0.1pt solid black;">15%</td>
            <td align="center" style="border: 0.1pt solid black;"><?php $h_b1 = $data->bab_1 * 0.15; echo number_format($h_b1,2) ?></td>
        </tr>
        <tr>
            <td align="center" style="border: 0.1pt solid black;">2.</td>
            <td align="left" style="border: 0.1pt solid black;">BAB II Profil Lokasi KKP</td>
            <td align="center" style="border: 0.1pt solid black;"><?= $data->bab_2 ?></td>
            <td align="center" style="border: 0.1pt solid black;">15%</td>
            <td align="center" style="border: 0.1pt solid black;"><?php $h_b2 = $data->bab_2 * 0.15; echo number_format($h_b2,2) ?></td>
        </tr>
        <tr>
            <td align="center" style="border: 0.1pt solid black;">3.</td>
            <td align="left" style="border: 0.1pt solid black;">BAB III Deskripsi Tugas</td>
            <td align="center" style="border: 0.1pt solid black;"><?= $data->bab_3 ?></td>
            <td align="center" style="border: 0.1pt solid black;">25%</td>
            <td align="center" style="border: 0.1pt solid black;"><?php $h_b3 = $data->bab_3 * 0.25; echo number_format($h_b3,2) ?></td>
        </tr>
        <tr>
            <td align="center" style="border: 0.1pt solid black;">4.</td>
            <td align="left" style="border: 0.1pt solid black;">BAB IV Paparan Tugas  </td>
            <td align="center" style="border: 0.1pt solid black;"><?= $data->bab_4 ?></td>
            <td align="center" style="border: 0.1pt solid black;">30%</td>
            <td align="center" style="border: 0.1pt solid black;"><?php $h_b4 = $data->bab_4 * 0.3; echo number_format($h_b4,2) ?></td>
        </tr>
        <tr>
            <td align="center" style="border: 0.1pt solid black;">5.</td>
            <td align="left" style="border: 0.1pt solid black;">BAB V Penutup (Kesimpulan dan Saran)</td>
            <td align="center" style="border: 0.1pt solid black;"><?= $data->bab_5 ?></td>
            <td align="center" style="border: 0.1pt solid black;">15%</td>
            <td align="center" style="border: 0.1pt solid black;"><?php $h_b5 = $data->bab_5 * 0.15; echo number_format($h_b5,2) ?></td>
        </tr>
        <tr>
            <td align="center" colspan="2" style="border: 0.1pt solid black;"></td>
            <td align="left" colspan="2" style="border: 0.1pt solid black;">TOTAL NILAI</td>
            <td align="center" style="border: 0.1pt solid black;"><?= number_format($data->laporan,2) ?></td>
        </tr>
    </tbody>
</table>
<br>
<p align="left">Keterangan : <br> Range nilai untuk setiap unsur adalah 0 - 100</p>
<p align="right" style="margin-right: 500px;">Mataram,................20 <br> Yang Menilai(Pembimbing)</p>
<br>
<p align="right" style="margin-right: 500px;">(&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;) <br>
NIK/NIP. <?= $data->nik ?>&emsp;&emsp;&emsp;&emsp;&emsp;</p>

</body>
</html>

