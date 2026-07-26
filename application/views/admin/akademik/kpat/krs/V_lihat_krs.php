<div class="box box-solid">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/kpat/krs/data_krs_kpat') ?>" class="btn btn-success btn-sm flat"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>
<div class="box box-primary flat">
	<div class="box-body table-responsive">
	<p style="text-align: center;"><strong>KARTU RENCANA STUDI (KRS) KPAT</strong></p>
	<p style="text-align: center;"><strong>SEMESTER <?= $data['data_mahasiswa']->semester % 2 == (0) ? "GENAP" : "GANJIL" ; ?> TA. <?= $data['data_mahasiswa']->tahun_akademik ?></strong></p>
	<br>
	<div class="col-sm-6 col-md-6 col-lg-6">
		<table class="table">
			<tr>
				<td><strong>Nama Mahasiswa</strong></td>
				<td><strong>:</strong></td>
				<td><?= $data['data_mahasiswa']->nama_mahasiswa  ?></td>
			</tr>
			<tr>
				<td><strong>NIM</strong></td>
				<td><strong>:</strong></td>
				<td><?= $data['data_mahasiswa']->nim  ?></td>
			</tr>
			<tr>
				<td><strong>Semester</strong></td>
				<td><strong>:</strong></td>
				<td><?= $data['data_mahasiswa']->semester % 2 == (0) ? "Genap" : "Ganjil" ; ?></td>
			</tr>
		</table>
	</div>
	<div class="col-sm-6 col-md-6 col-lg-6">
		<table class="table">
			<tr>
				<td><strong>Program Studi</strong></td>
				<td><strong>:</strong></td>
				<td><?= $prodi->nama_program_studi  ?></td>
			</tr>
			<tr>
				<td><strong>Fakultas</strong></td>
				<td><strong>:</strong></td>
				<td><?= $prodi->nama_fakultas  ?></td>
			</tr>
		</table>
	</div>
    <br>
	<table class="table demo-table">
		<thead>
			<tr>
				<th id="th" width="20" rowspan="2">No.</th>
				<th id="th" rowspan="2">Kode</th>
				<th id="th" rowspan="2">Matakuliah</th>
				<th id="th" colspan="3">SKS</th>
				<th id="th" rowspan="2">UTS</th>
				<th id="th" rowspan="2">UAS</th>
				<th id="th" rowspan="2">Ket</th>
			</tr>
			<tr>
				<th id="th">T</th>
				<th id="th">PK</th>
				<th id="th">PT</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$teori = 0;
		$praktek = 0;
		$praktikum = 0;
		$i=1; foreach ($data['data_matakuliah'] as $row) : ?>
			<tr>
				<td style="text-align: center;"><?= $i++."." ?></td>
				<td style="text-align: center;"><?= $row->kode_matakuliah ?></td>
				<td><?= $row->nama_matakuliah ?></td>
				<td style="text-align: center;"><?= $row->sks_teori == (0) ? "" : $row->sks_teori ?></td>
				<td style="text-align: center;"><?= $row->sks_praktek == (0) ? "" : $row->sks_praktek ?></td>
				<td style="text-align: center;"><?= $row->sks_praktikum == (0) ? "" : $row->sks_praktikum?></td>
				<td style="text-align: center;"></td>
				<td style="text-align: center;"></td>
				<td style="text-align: center;"></td>
			</tr>
		<?php
		$teori = $teori + $row->sks_teori;
		$praktek = $praktek + $row->sks_praktek;
		$praktikum = $praktikum + $row->sks_praktikum; 
		 ?>
		<?php endforeach; ?>
			<tr>
				<td id="th" colspan="3"><strong>Jumlah</strong></td>
				<td id="th"><strong><?= $teori  ?></strong></td>
				<td id="th"><strong><?= $praktek  ?></strong></td>
				<td id="th"><strong><?= $praktikum  ?></strong></td>
				<td id="th" colspan="3"></td>
			</tr>
		</tbody>
	</table>
        <hr>
        <h4>Keterangan</h4>
        <p><b>Status Pengambilan Matakuliah</b><br />
            <b>B</b> : Baru &nbsp;&nbsp;&nbsp; <b>U</b> : Ulang<br />
            <b>Jenis SKS Matakuliah</b><br />
            <b>T</b> : Teori &nbsp;&nbsp;&nbsp; <b>PK</b> : Praktek &nbsp;&nbsp;&nbsp; <b>PT</b> : Praktikum<br />
            <b>Kode Kompetensi khusus untuk Strata 1 (S1)</b><br />
            <b>RPL</b> : Rekayasa Perangkat Lunak &nbsp;&nbsp;&nbsp;&nbsp; <b>JK</b> : Jaringan Komputer &nbsp;&nbsp;&nbsp;&nbsp; <b>M</b> : Multimedia
        </p>
	<!-- end.box-body -->
</div>