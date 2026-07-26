<?php
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=$filename.xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
<p style="text-align: center; font-weight: bold; font-size: 16pt">Mahasiswa Aktif TA. <?= $tahun_akademik->tahun_akademik ?> <?= $tahun_akademik->semester == '1' ? 'GANJIL' : 'GENAP' ?></p>
<hr>
<table id="customers">
    <thead>
    <tr>
        <th>No.</th>
        <th>NIM</th>
        <th>Nama Mahasiwa</th>
        <th>No. Telp</th>
        <th>Email</th>
        <th>Program Studi</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($mahasiswa_aktif as $key => $row) : ?>
        <tr>
            <td><?= $key + 1 ?>.</td>
            <td><?= $row->nim ?></td>
            <td><?= $row->nama_mahasiswa ?></td>
            <td><?= $row->telepon ?></td>
            <td><?= $row->email ?></td>
            <td><?= $row->nama_program_studi ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>

</table>

</body>
</html>
