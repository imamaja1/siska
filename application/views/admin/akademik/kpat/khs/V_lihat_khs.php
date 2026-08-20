<div class="box box-solid">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/kpat/khs/data_khs_kpat') ?>" class="btn btn-success btn-sm flat"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>
<div class="box box-primary flat">
	<div class="box-body table-responsive">
	<p style="text-align: center;"><strong>KARTU HASIL STUDI (KHS) KPAT</strong></p>
	<p style="text-align: center;"><strong>SEMESTER <?= $data['semester'] % 2 == (0) ? "GENAP" : "GANJIL" ; ?> TA. <?= e($data['tahun_akademik']) ?></strong></p>
	<br>
	<div class="col-sm-6 col-md-6 col-lg-6">
		<table class="table">
			<tr>
				<td><strong>Nama Mahasiswa</strong></td>
				<td><strong>:</strong></td>
<td><?= e($data['nama_mahasiswa'])  ?></td>
            </tr>
            <tr>
                <td><strong>NIM</strong></td>
                <td><strong>:</strong></td>
                <td><?= e($data['nim'])  ?></td>
			</tr>
			<tr>
				<td><strong>Semester</strong></td>
				<td><strong>:</strong></td>
				<td><?= $data['semester'] % 2 == (0) ? "Genap" : "Ganjil" ; ?></td>
			</tr>
		</table>
	</div>
	<div class="col-sm-6 col-md-6 col-lg-6">
		<table class="table">
			<tr>
				<td><strong>Program Studi</strong></td>
				<td><strong>:</strong></td>
<td><?= e($prodi->nama_program_studi)  ?></td>
            </tr>
            <tr>
                <td><strong>Fakultas</strong></td>
                <td><strong>:</strong></td>
                <td><?= e($prodi->nama_fakultas)  ?></td>
            </tr>
            <tr>
                <td><strong>Kurikulum</strong></td>
                <td><strong>:</strong></td>
                <td><?= e($data['kurikulum']) ?></td>
			</tr>
		</table>
	</div>
	<table class="table demo-table">
		<thead>
			<tr>
				<th id="th" width="20">No.</th>
				<th id="th">Kode</th>
				<th id="th">Matakuliah</th>
				<th id="th">SKS</th>
				<th id="th">Grade</th>
				<th id="th">SKSN</th>
				<th id="th">Ket</th>
			</tr>
		</thead>
		<tbody>
		<?php $i=1; $sksn=0; $sks=0; foreach ($data['data_nilai'] as $row) : ?>
			<tr>
				<td style="text-align: center;"><?= $i++ ?></td>
<td style="text-align: center;"><?= e($row['kode_matakuliah']) ?></td>
                <td><?= e($row['nama_matakuliah']) ?></td>
                <td style="text-align: center;"><?= e($row['sks']) ?></td>
                <td style="text-align: center;"><?= e($row['grade']) ?></td>
                <td style="text-align: center;"><?= e($row['sksn']) ?></td>
				<td style="text-align: center;">-</td>
			</tr>
			<?php 
				$sksn = $sksn + $row['sksn']; 
				$sks = $sks + $row['sks']; 

			?>
		<?php endforeach; ?>
			<tr>
				<td colspan="5"></td>
<td id="th"><strong><?= e($sksn) ?></strong></td>
            </tr>
        </tbody>
    </table>
    <br>
    <div class="col-sm-4 col-md-4 col-lg-4">
        <table class="table">
            <tr>
                <td><strong>Jumlah SKS yang ditempuh</strong></td>
                <td><strong>:</strong></td>
                <td><?= e($sks)  ?></td>
			</tr>
			<tr>
				<td><strong>IP Semester ini</strong></td>
				<td><strong>:</strong></td>
				<td><?= $sks > 0 ? number_format($sksn/$sks,2) : '0.00' ?></td>
			</tr>
			<tr>
				<td><strong>Maksimum SKS Semester Depan</strong></td>
				<td><strong>:</strong></td>
				<td>-</td>
			</tr>
		</table>
	</div>
	<br>
	<br>
	<br>
	<br>
	<div class="col-sm-4 col-md-4 col-lg-4 pull-right">
		<p>Mataram, <?= date("d M Y") ?></p>
		<p>Wakil Rektor I,</p>
		<br>
		<br>
		<p><u>Dr.Khasnur Hidjah, S.Kom., M.Cs.</u></p>
        <p>NIP : 197202072005012001 </p>
	</div>
	</div>
	<!-- end.box-body -->
</div>