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
    <title><?= $file_name ?></title>
</head>
<body>
<table border="1" style="border-collapse: collapse">
    <thead>
    <tr>
        <th>No.</th>
        <th>NIM</th>
        <th>Nama Siswa</th>
        <th>L/P</th>
        <th>SKS</th>
        <th>KET</th>
        <th>KKP</th>
        <th>PRAKTIKUM</th>
    </tr>
    </thead>
    <tbody>
    <?php $no = 1; foreach ($data as $row) : ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= e($row->nim) ?></td>
            <td><?= e($row->nama_mahasiswa) ?></td>
            <td style="text-align: center"><?= e($row->jenis_kelamin) ?></td>
            <td style="text-align: center;"><?= $row->total_sks?></td>
            <td style="text-align: center;"><?= $row->skripsi == 0 ? "" : "SKRIPSI" ?></td>
            <td style="text-align: center;"><?= $row->kkp == 0 ? "" : "KKP" ?></td>
            <td style="text-align: center;"><?= $row->praktikum == 0 ? "" : "PRAKTIKUM" ?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

</body>
</html>
