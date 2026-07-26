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

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

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
<p style="text-align: center; font-weight: bold; font-size: 16pt">Rekap Pembayaran
    TA. <?= tahun_akademik()->tahun_akademik ?> <?= tahun_akademik()->semester == '0' ? 'GENAP' : 'GANJIL' ?></p>
<hr>
<table id="customers">
    <thead>
    <tr style="text-align: center">
        <th>No.</th>
        <th>NIM</th>
        <th>Nama Siswa</th>
        <th>Program Studi</th>
        <th>Semester</th>
        <th>SKS Teori</th>
        <th>SKS Praktikum</th>
        <th>Pembayaran SPP</th>
        <th>Pembayaran SKS</th>
        <th>Pembayaran LAB</th>
    </tr>
    </thead>
    <tbody>
    <?php $no = 1;
    foreach ($data as $row) : ?>
        <tr>
            <td style="text-align: center"><?= $no++ ?></td>
            <td style="text-align: center"><?= $row->nim ?></td>
            <td><?= $row->nama_mahasiswa ?></td>
            <td><?= get_kode_prodi($row->nim)->nama_program_studi ?></td>
            <td style="text-align: center"><?= $row->semester ?></td>
            <td style="text-align: center"><?= $row->teori ?></td>
            <td style="text-align: center"><?= $row->praktikum ?></td>
            <td style="text-align: center">
                <?php if ($row->pembayaran_spp == '0') : ?>
                    BELUM LUNAS
                <?php elseif ($row->pembayaran_spp == '1') : ?>
                    LUNAS
                <?php else : ?>
                    DISPENSASI
                <?php endif; ?></td>
            <td style="text-align: center">
                <?php if ($row->pembayaran_sks == '0') : ?>
                    BELUM LUNAS
                <?php elseif ($row->pembayaran_sks == '1') : ?>
                    LUNAS
                <?php else : ?>
                    DISPENSASI
                <?php endif; ?>
            </td>
            <td style="text-align: center">
                <?php if ($row->praktikum > 0) : ?>
                    <?php if ($row->pembayaran_lab == '0') : ?>
                        BELUM LUNAS
                    <?php elseif ($row->pembayaran_lab == '1') : ?>
                        LUNAS
                    <?php else : ?>
                        DISPENSASI
                    <?php endif; ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
