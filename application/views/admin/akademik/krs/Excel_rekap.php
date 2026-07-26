<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$file_name.".xls");
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
<p style="text-align: center"><?= e($file_name) ?><br>Aktif pembayaran SKS</p>
<hr>
<table id="customers">
    <thead>
    <tr>
        <th id="th">NO.</th>
        <th id="th">NIM</th>
        <th id="th">NAMA MAHASISWA</th>
    </tr>
    </thead>
    <?php
    $no = 1;
    foreach ($data as $row) {
        ?>
        <tr>
            <td align="center"><?= $no++ ?>.</td>
            <td align="center"><?= e($row->nim) ?></td>
            <td <?= $row->kode_status_perkuliahan == '' ? "style=\"background-color: #00b3ee\"" : "" ?>><?= e($row->nama_mahasiswa) ?></td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
