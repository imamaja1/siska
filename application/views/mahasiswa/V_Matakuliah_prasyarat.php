<?= $this->session->flashdata('info')  ?>
<!--data semula-->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-sitemap"></i> Matakuliah Prasyarat</h3>
	</div>
	<div class="box-body">
		<div class="table-responsive mhs-table-wrap">
			<table class="table mhs-table">
				<thead>
					<tr>
						<th class="mhs-c-no">No.</th>
						<th>Kode Matakuliah yg diambil</th>
						<th>Nama Matakuliah yg diambil</th>
						<th>Kode Matakuliah Prasyarat</th>
						<th>Nama Matakuliah Prasyarat</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					foreach ($data_prasyarat as $data) { ?>
					<tr>
						<td class="mhs-num"><?= $i++ . "." ?></td>
						<td class="mhs-kode"><?= e($data['matakuliah_yg_diambil']) ?></td>
						<td><?= e($data['nama_matakuliah_yg_diambil']) ?></td>
						<td class="mhs-kode"><?= e($data['matakuliah_prasyarat']) ?></td>
						<td><?= e($data['nama_matakuliah_prasyarat']) ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- script -->