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
<p style="text-align: center; font-weight: bold; font-size: 12pt">REKAPITULASI PENDISTRIBUSIAN NILAI MATAKULIAH <br>
    PROGRAM STUDI <?= strtoupper($program_studi->nama_program_studi) ?>
    <br>
    SEMESTER <?= $tahun_akademik->semester == '1' ? 'GANJIL' : 'GENAP' ?> <?= $tahun_akademik->tahun_akademik ?></p>
<hr>
<table id="customers">
    <thead>
    <tr>
        <th rowspan="3" style="text-align: center">NO.</th>
        <th rowspan="3" style="text-align: center">MATAKULIAH</th>
        <th rowspan="3" style="text-align: center">KELAS/SEMESTER</th>
        <th rowspan="3" style="text-align: center">NAMA DOSEN</th>
        <th rowspan="3" style="text-align: center">TOTAL MHS</th>
        <th colspan="14" style="text-align: center">DISTRIBUSI NILAI</th>
    </tr>
    <tr>
        <th colspan="2" style="text-align: center">A</th>
        <th colspan="2" style="text-align: center">B+</th>
        <th colspan="2" style="text-align: center">B</th>
        <th colspan="2" style="text-align: center">C+</th>
        <th colspan="2" style="text-align: center">C</th>
        <th colspan="2" style="text-align: center">D</th>
        <th colspan="2" style="text-align: center">E</th>
    </tr>
    <tr>
        <th style="text-align: center">ANGKA</th>
        <th style="text-align: center">%</th>
        <th style="text-align: center">ANGKA</th>
        <th style="text-align: center">%</th>
        <th style="text-align: center">ANGKA</th>
        <th style="text-align: center">%</th>
        <th style="text-align: center">ANGKA</th>
        <th style="text-align: center">%</th>
        <th style="text-align: center">ANGKA</th>
        <th style="text-align: center">%</th>
        <th style="text-align: center">ANGKA</th>
        <th style="text-align: center">%</th>
        <th style="text-align: center">ANGKA</th>
        <th style="text-align: center">%</th>
    </tr>
    </thead>
    <tbody>
    <?php $no=1; foreach ($data as $row) : ?>
        <tr>
            <td><?= $no++ ?> </td>
            <td><?= $row['nama_matakuliah'] ?></td>
            <td style="text-align: center"><?= $row['nama_kelas'] ?> / <?= $row['semester'] ?></td>
            <td><?= $row['nama_dosen'] ?></td>
            <td style="text-align: center"><?= $row['total'] ?> </td>
            <?php $x=0; foreach ($row['data'] as $item) : ?>
                <td style="text-align: center"><?= number_format($item->jumlah,0) ?> </td>
                <td style="text-align: center"><?= number_format($item->persen,0,',','.') ?> </td>
            <?php if($x++ >= 6) { break; } endforeach;  ?>
        </tr>
    <?php endforeach; ?>
    </tbody>

</table>

</body>
</html>
