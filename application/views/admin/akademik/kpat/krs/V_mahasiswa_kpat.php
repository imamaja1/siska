<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/kpat/krs') ?>" class="btn btn-success btn-sm flat"><i class="fa fa-arrow-left"></i> Kembali</a>
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
<td style="text-align: center;"><?= e($row->nim)  ?></td>
                        <td><?= e($row->nama_mahasiswa)  ?></td>
						<td style="text-align: center;">
                            <div class="btn-group btn-group-xs">
							<a href="<?= site_url('admin/akademik/kpat/krs/edit/'.$row->kode_krs.'/'.$row->nim)  ?>" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>&nbsp;
							<a href="<?= site_url('admin/akademik/kpat/krs/lihat_krs/'.$row->kode_krs)  ?>" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Lihat</a>&nbsp;
							<a href="<?= site_url('admin/akademik/kpat/krs/cetak/'.$row->kode_krs)  ?>" class="btn btn-danger btn-xs"><i class="fa fa-print"></i> Cetak</a>
							<a href="#" class="btn btn-danger btn-xs" onclick="hapusKrs(<?= (int) $row->kode_krs ?>);"><i class="fa fa-trash"></i> Hapus</a>
                            </div>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
        </div>
	</div>
</div>
<?php else : ?>
    <div class="callout callout-warning flat">
        <h4><i class="fa fa-warning"></i> Peringatan!</h4>

        <p>Data tidak ditemukan.</p>
    </div>
<?php endif ?>

<script type="text/javascript">
    function hapusKrs(kodeKrs) {
        swal({
            title: "Yakin?",
            text: "Seluruh KRS KPAT beserta matakuliah dan nilainya akan dihapus permanen!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dd4b39",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal"
        }).then(function (result) {
            var ok = result === true || !!(result && result.value);
            if (!ok) { return; }
            $.ajax({
                url: "<?= site_url('admin/akademik/kpat/krs/hapus_krs_ajax/') ?>" + kodeKrs,
                type: "POST",
                dataType: "json",
                success: function (data) {
                    if (data.status) {
                        swal("Berhasil!", data.message, "success");
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    } else {
                        swal("Gagal!", data.message, "error");
                    }
                },
                error: function () {
                    swal("Gagal!", "Terjadi kesalahan saat menghapus KRS", "error");
                }
            });
        }).catch(swal.noop);
    }
</script>
