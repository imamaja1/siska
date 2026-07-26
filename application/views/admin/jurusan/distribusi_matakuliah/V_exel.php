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
    <table border="1" style="width: 100%">
    <thead>
    <tr>
        <th>NO</th>
        <th>NAMA DOSEN</th>
        <th>MATAKULIAH</th>
        <th>T</th>
        <th>PK</th>
        <th>PT</th>
        <th>SEMESTER</th>
        <th>KELAS </th>
        <th>BEBAN</th>
        <th>BEBAN PERJURUSAN</th>
    </tr>
    </thead>
    <tbody>
    <?php  $no=1; foreach ($data as $key) : ?>
        <tr>
        <td rowspan="<?= $key['rowspan'] ?>"><?= $no++ ?>.</td>
        <td rowspan="<?= $key['rowspan'] ?>"><?= $key['nama_dosen'] ?></td>
        <?php $i=0; foreach ($key['data'] as $row) : ?>
            <td><?= $row->nama_matakuliah ?></td>
            <td style="text-align: center"><?= $row->sks_teori ?></td>
            <td style="text-align: center"><?= $row->sks_praktek ?></td>
            <td style="text-align: center"><?= $row->sks_praktikum ?></td>
            <td style="text-align: center"><?= $row->semester ?> - (<?= $row->nama_kelas ?>)</td>
            <td style="text-align: center"><?= $row->jml_kelas ?></td>
            <td style="text-align: center"><?= $row->beban ?> - (<?= $row->singkatan_program_studi ?>)</td>
            <?php if ($i == 0) : ?>
                <td rowspan="<?= $key['rowspan'] ?>" style="text-align: center"><?= $key['total_beban'] ?></td>
                <?php $i++; endif; ?>
            </tr>
        <?php  endforeach; ?>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
