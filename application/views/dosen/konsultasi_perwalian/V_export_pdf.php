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
            background-color: #367fa9;
            color: white;
        }
    </style>
</head>
<body>
<p style="text-align: center; font-weight: bold; font-size: 16pt">FORM ABSENSI PERWALIAN</p>
<hr>
<table id="customers">
    <tr>
        <th style="text-align: center">No.</th>
        <th style="text-align: center">NIM</th>
        <th style="text-align: center">NAMA MAHASISWA</th>
        <th style="text-align: center">NO. TELP</th>
        <th style="width: 20%;text-align: center">TTD</th>
    </tr>
    <?php foreach ($perwalian as $key => $row) : ?>
        <tr>
            <td><?= e($key + 1) ?>.</td>
            <td><?= e($row->nim) ?></td>
            <td><?= e($row->nama_mahasiswa) ?></td>
            <td><?= e($row->telepon) ?></td>
            <td></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>