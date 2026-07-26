<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=" . $file_name . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<p style="text-align: left; font-size: 12pt">
    Prodi : <?= $prodi->nama_program_studi ?> <br>
    Tahun Akademik : <?= $ta->tahun_akademik ?> - <?= $ta->semester == 1 ? 'Ganjil' : 'Genap' ?>
</p>
<p style="text-align: center;">Tanggal Export : <?= date('d-M-Y H:i:s') ?></p>
<hr>
<div style="text-align: left; font-weight: bold; font-size: 12pt">Mahasiswa Aktif</div>
<?php
echo !empty($table) ? $table : '';
?>
<div style="text-align: left; font-weight: bold; font-size: 12pt">Mahasiswa Tidak Aktif</div>
<?php
echo !empty($table2) ? $table2 : '';
?>
