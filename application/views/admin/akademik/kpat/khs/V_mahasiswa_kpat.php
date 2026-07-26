<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/kpat/khs') ?>" class="btn btn-success btn-sm flat"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>
<?php if (count($data) > 0): ?>
<div class="box box-primary flat">
	<div class="box-body">
        <div class="table-responsive">
			<table class="table demo-table">
				<thead>
					<tr>
						<th id="th" width="20">No.</th>
						<th id="th">NIM</th>
						<th id="th">Nama Mahasiswa</th>
						<th id="th" width="240">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php $i=1;
					foreach ($data as $row) { ?>
					<tr>
						<td style="text-align: center;"><?= $i++."."  ?></td>
						<td style="text-align: center;"><?= $row->nim  ?></td>
						<td><?= $row->nama_mahasiswa  ?></td>
						<td style="text-align: center;">
							<!--<a href="<?= site_url('admin/akademik/kpat/khs/lihat_khs/'.$row->kode_krs.'/'.substr($row->nim,0,2).'/'.substr($row->nim,2,2).'/'.substr($row->nim,4,1)) ?>" class="btn btn-info btn-xs flat"><i class="fa fa-eye"></i> Lihat</a>&nbsp;-->
							<a href="<?= site_url('admin/akademik/kpat/khs/lihat_khs/'.$row->kode_krs.'/'.$row->nim) ?>" class="btn btn-info btn-xs flat"><i class="fa fa-eye"></i> Lihat</a>&nbsp;
                            <!--<a href="<?= site_url('admin/akademik/kpat/khs/cetak/'.$row->kode_krs.'/'.substr($row->nim,0,2).'/'.substr($row->nim,2,2).'/'.substr($row->nim,4,1).'/'.$row->nim) ?>" class="btn btn-warning btn-xs flat"><i class="fa fa-print"></i> Cetak</a></center>-->
                            <a href="<?= site_url('admin/akademik/kpat/khs/cetak/'.$row->kode_krs.'/'.$row->nim) ?>" class="btn btn-warning btn-xs flat"><i class="fa fa-print"></i> Cetak</a></center>

						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
        </div>
	</div>
</div>
<?php else : ?>
    <div class="callout callout-warning">
        <h4><i class="fa fa-warning"></i> Peringatan!</h4>

        <p>Data tidak ditemukan.</p>
    </div>
<?php endif ?>