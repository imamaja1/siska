<?= $this->session->flashdata('info')  ?>
<!--data semula-->
<div class="box box-primary" >
	<div class="box-body table-responsive">
		<table class="table demo-table" >
			<thead>
				<tr>
					<th class="th-color"><center>No.</center></th>
					<th class="th-color">Kode Matakuliah yg diambil</th>
					<th class="th-color">Nama Matakuliah yg diambil</th>
					<th class="th-color">Kode Matakuliah Prasyarat</th>
					<th class="th-color">Nama Matakuliah Prasyarat</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i=1;
				foreach ($data_prasyarat as $data) {?>
				<tr>
					<td><center><?= $i++."."  ?></center></td>
					<td><center><?= e($data['matakuliah_yg_diambil'])  ?></center></td>
					<td><?= e($data['nama_matakuliah_yg_diambil'])  ?></td>
					<td><center><?= e($data['matakuliah_prasyarat'])  ?></center></td>
					<td><?= e($data['nama_matakuliah_prasyarat'])  ?></td>

				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>


<!-- script -->
