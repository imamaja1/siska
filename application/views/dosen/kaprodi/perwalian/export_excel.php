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
<p style="text-align: center; font-weight: bold; font-size: 12pt">REKAPITULASI PERWALIAN MAHASISWA</p>
<p style="text-align: center;">Tanggal Export : <?=date('d-M-Y H:i:s')?></p>
<table id="customers">
    <thead>
    <tr>
        <th style="text-align: center">NO.</th>
        <th style="text-align: center">NAMA DOSEN</th>
        <th style="text-align: center">HOMEBASE</th>
        <th style="text-align: center">NIM</th>
        <th style="text-align: center">NAMA MAHSISWA</th>
        <th style="text-align: center">PRODI</th>
    </tr>
    </thead>
    <tbody>
    <?php $no=1; foreach ($data as $row) : ?>
        <tr>
            <td><?= $no++ ?> </td>
            <td><?= e($row->nama_dosen) ?></td>
            <td><?= e($row->homebase) ?></td>
            <td style="text-align: center"><?= e($row->nim) ?></td>
            <td><?= e($row->nama_mahasiswa) ?></td>
            <td><?= e($row->jurusan) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
