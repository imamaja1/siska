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
        <th style="text-align: center">NO.</th>
        <th style="text-align: center">Tahun Angkatan</th>
        <th style="text-align: center">Laki-Laki</th>
        <th style="text-align: center">Perempuan</th>
        <th style="text-align: center">Total</th>
    </tr>
    </thead>
    <tbody>
    <?php $total = 0 ; $total_x = 0; $total_y = 0; $no = 1;
    foreach ($data as $row) : ?>
        <tr>
            <td style="text-align: center"><?= $no++ ?>.</td>
            <td style="text-align: center"><?= $row['angkatan'] ?></td>
            <td style="text-align: center"><?= $row['laki']  ?></td>
            <td style="text-align: center"><?= $row['perempuan'] ?></td>
            <td style="text-align: center"><?= $row['total'] ?></td>
        </tr>
        <?php
        $total =  $total + $row['total'];
        $total_x =  $total_x + $row['laki'];
        $total_y =  $total_y + $row['perempuan'];
    endforeach; ?>
    <tr>
        <td colspan="2" style="text-align: center"><b>Total Keseluruhan Mahasiswa Aktif</b></td>
        <td style="text-align: center; font-weight: bold"><?= $total_x ?></td>
        <td style="text-align: center; font-weight: bold"><?= $total_y ?></td>
        <td style="text-align: center; font-weight: bold"><?= $total ?></td>
    </tr>
    </tbody>
</table>

</body>
</html>
