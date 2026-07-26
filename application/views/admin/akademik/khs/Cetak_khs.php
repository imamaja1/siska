<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		.header{
			float: center;
			width: 100%;
		}
		table, td, th {    
			border: 1px solid #ddd;
			text-align: left;
		}

		table {
			border-collapse: collapse;
			width: 100%;
		}

		th, td {
			padding: 3px;
		}
		.row{
			clear: left;
    		width: 100%;
    		margin-top: 20px;
    		padding: 0px;
		}
		.col{
			float: left;
    		width: 400px;
		    height: 190px;
		}
		.right{
			/* position: absolute; */
    		right: 0px;
    		width: 300px;
    		padding: 10px;
    		float: right;
    		clear: right;
		}
		.left{
			/* position: absolute; */
    		left: 0px;
    		width: 300px;
    		padding: 10px;
    		float: left;
    		clear: left;
		}

	</style>
</head>
<body style="font-family: arial; font-size:12px;">
<div class="row">
	<img src="<?= base_url('assets/gambar/header_khs.png')  ?>" style="height:120px;">
</div>
	<div class="header">
		<p style="text-align: center;"><strong>Kartu Hasil Studi (KHS)</strong></p>
		<p style="text-align: center;"><strong>Semester <?= $data['semester'] % 2 == (0) ? "Genap" : "Ganjil" ; ?> TA. <?= $data['tahun_akademik'] ?></strong></p>
	</div>

	<div class="row">
	<div class="left" >
		<table style="border: 0px; width:100%;">
			<tr style="border: 0px;">
				<td style="border: 0px;"><strong>Nama Mahasiswa</strong></td>
				<td style="border: 0px;"><strong>:</strong></td>
				<td style="border: 0px;"><?= $data['nama_mahasiswa']  ?></td>
			</tr>
			<tr style="border: 0px;">
				<td style="border: 0px;"><strong>NIM</strong></td>
				<td style="border: 0px;"><strong>:</strong></td>
				<td style="border: 0px;"><?= $data['nim']  ?></td>
			</tr>
			<tr style="border: 0px;">
				<td style="border: 0px;"><strong>Semester</strong></td>
				<td style="border: 0px;"><strong>:</strong></td>
				<td style="border: 0px;"><?= $data['semester'] % 2 == (0) ? "Genap" : "Ganjil" ; ?></td>
			</tr>
		</table>
	</div>
	<div class="right">
		<table style="border: 0px; float: right; width:100%; ">
			<tr style="border: 0px;">
				<td style="border: 0px;"><strong>Program Studi</strong></td>
				<td style="border: 0px;"><strong>:</strong></td>
				<td style="border: 0px;"><?= $data['nama_jurusan']  ?></td>
			</tr>
			<tr style="border: 0px;">
				<td style="border: 0px;"><strong>Jenjang</strong></td>
				<td style="border: 0px;"><strong>:</strong></td>
				<td style="border: 0px;"><?= $data['nama_jenjang']  ?></td>
			</tr>
			<tr>
				<td style="border: 0px;"><strong>Kurikulum</strong></td>
				<td style="border: 0px;"><strong>:</strong></td>
				<td style="border: 0px;"><?= $data['kurikulum']?></td>
			</tr>
		</table>
	</div>
	</div>
	<div class="row">
	<table class="table">
		<thead>
			<tr>
				<th id="color" width="20"><center>No.</center></th>
				<th id="color"><center>Kode</center></th>
				<th id="color"><center>Matakuliah</center></th>
				<th id="color"><center>SKS</center></th>
				<th id="color"><center>Grade</center></th>
				<th id="color"><center>SKSN</center></th>
				<th id="color"><center>Ket</center></th>
			</tr>
		</thead>
		<tbody>
			<?php $i=1; $sksn=0; $sks=0; foreach ($data['data_nilai'] as $row) : ?>
			<tr>
				<td><center><?= $i++ ?></center></td>
				<td><center><?= $row['kode_matakuliah'] ?></center></td>
				<td><?= $row['nama_matakuliah'] ?></td>
				<td><center><?= $row['sks'] ?></center></td>
				<td><center><?= $row['grade'] ?></center></td>
				<td><center><?= $row['sksn'] ?></center></td>
				<td><center><?= $row['tb'] == 'A' ? 'TB' : '' ?></center></td>
			</tr>
			<?php 
			$sksn = $sksn + $row['sksn']; 
			$sks = $sks + $row['sks']; 

			?>
		<?php endforeach; ?>
		<tr>
			<td colspan="5"></td>
			<td id="color"><center><strong><?= $sksn ?></strong></center></td>
		</tr>
	</tbody>
	</table>
	</div>
	<div class="row">

	<div class="left">
		<table class="table" style="border: 0px;">
			<tr style="border: 0px;">
				<td style="border: 0px;"><strong>Jumlah SKS yang ditempuh</strong></td>
				<td style="border: 0px;"><strong>:</strong></td>
				<td style="border: 0px;"><?= $sks  ?></td>
			</tr>
			<tr style="border: 0px;">
				<td style="border: 0px;"><strong>IP Semester ini</strong></td>
				<td style="border: 0px;"><strong>:</strong></td>
				<td style="border: 0px;"><?= number_format($sksn/$sks,2)  ?></td>
			</tr>
            <?php if (substr($data['nim'],4,1) !=3) : ?>
			<tr style="border: 0px;">
				<td style="border: 0px;"><strong>Maksismum SKS Semester Depan</strong></td>
				<td style="border: 0px;"><strong>:</strong></td>
				<td style="border: 0px;"><?= $data['maksimum_sks'] ?></td>
			</tr>
            <?php endif; ?>
		</table>
	</div>

</div>
	<div class="right" style="margin-right:8px;">
		<p>Mataram, <?= date("d M Y") ?></p>
		<p>Pembantu Ketua I,</p>
		<br>
		<br>
		<p><u>Dr.Khasnur Hidjah, S.Kom., M.Cs.</u></p>
        <p>NIP : 197202072005012001 </p>
	</div>
</body>
</html>
