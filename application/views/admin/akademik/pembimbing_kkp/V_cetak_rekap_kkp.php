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

<p align="center"><strong>REKAP KKP </strong><br><strong>TA. <?= $tahun_akademik->tahun_akademik ?> <?= $tahun_akademik->semester == 0 ? 'GENAP' : 'GANJIL' ?></strong></p>
<!--<p align="center"><strong>KULIAH KERJA PRAKTEK (KKP)</strong></p>-->
<table border="1" style="font-size: 12px; border: 1px solid black; border-collapse: collapse">
    <tr>
        <th>NO.</th>
        <th>NIM</th>
        <th>NAMA MAHASISWA</th>
        <th>NO. HP</th>
        <th>ALAMAT RUMAH</th>
        <th>LOKASI KKP</th>
        <th>TGL. PELAKSANAAN</th>
        <th>BIDANG PEKERJAAN</th>
    </tr>
    <thead>
    </thead>
    <tbody>
        <?php $no=1; foreach ($data as $row) : ?>
            <tr>
                <td><?= $no++ ?>.</td>
                <td><?= e($row->nim) ?></td>
                <td><?= e($row->nama_mahasiswa) ?></td>
                <td><?= e($row->telepon) ?></td>
                <td><?= e($row->alamat) ?></td>
                <td><?= e($row->lokasi_kkp) ?></td>
                <td><?= tgl_indo($row->tgl_pelaksanaan) ?>/<?= tgl_indo($row->batas_pelaksanaan) ?></td>
                <td><?= e($row->bidang_kkp) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>

