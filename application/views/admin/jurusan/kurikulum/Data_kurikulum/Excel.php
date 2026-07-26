<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=" . $file_name . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
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

<table id="customers">
    <thead>
    <tr>
        <th style="text-align: center">NO.</th>
        <th style="text-align: center">KODE MATAKULIAH</th>
        <th style="text-align: center">NAMA MATAKULIAH</th>
        <th style="text-align: center">SKS TEORI</th>
        <th style="text-align: center">SKS PRAKTEK</th>
        <th style="text-align: center">SKS PRAKTIKUM</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $row) : ?>
        <?php if (count($row['data']) > 0) : ?>
            <tr>
                <td colspan="6" style="text-align: center">SEMESTER <?= e($row['semester']) ?></td>
            </tr>
            <?php $i = 1;
            foreach ($row['data'] as $d) :?>
                <tr <?= in_array($d->id_matakuliah, $mk_pilihan) ? "style='font-style: italic'" : "style='font-weight: bold'" ?> <?=($d->jenis == '1')? 'style="font-style: italic"':'';?>>
                    <td style="text-align: center"><?= $i++ ?></td>
                    <td style="text-align: center"><?= e($d->kode_matakuliah) ?></td>
                     <td>
                        <?= e($d->nama_matakuliah) ?>
                        <?= ($nama_pilihan[$d->id_matakuliah] == true) ? ' - (Kompetensi : ' . $nama_pilihan[$d->id_matakuliah] . ')' : '' ?>
                        <?= ($d->jenis == 1) ? '- (Matakuliah Pilihan)' : '' ?>
                    </td>
                    <td style="text-align: center"><?= e($d->sks_teori) ?></td>
                    <td style="text-align: center"><?= e($d->sks_praktek) ?></td>
                    <td style="text-align: center"><?= e($d->sks_praktikum) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
