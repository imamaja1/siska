<?php if (isset($data)) : ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a class="btn btn-primary btn-sm pull-right flat" onclick="$('#tambah-matakuliah').modal('toggle');"><i
                    class="fa fa-plus"></i> Tambah Matakuliah</a>&nbsp;
        <a href="<?= site_url('admin/akademik/perubahan/semester_lalu') ?>" class="btn btn-danger btn-sm flat"><i
                    class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>
<div class="box box-primary flat">
	<div class="box-header">
		
	</div>
	<div class="box-body">
	<p><center><strong>PERUBAHAN KARTU RENCANA STUDI (KRS)/NILAI UNTUK SEMESTER LALU</strong></center></p>
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
<td><?= e($data['prodi']->nama_program_studi)  ?></td>
            </tr>
            <tr>
                <td><strong>Fakultas</strong></td>
                <td><strong>:</strong></td>
                <td><?= e($data['prodi']->nama_fakultas)  ?></td>
			</tr>
			<tr>
				<td><strong>Kurikulum</strong></td>
				<td><strong>:</strong></td>
				<td><?= e($data['kurikulum'])?></td>
			</tr>
		</table>
	</div>
	<table class="table demo-table" id="table-edit">
		<thead>
			<tr>
				<th id="color" width="20"><center>No.</center></th>
				<th id="color"><center>Kode</center></th>
				<th id="color"><center>Matakuliah</center></th>
				<th id="color"><center>Nilai Harian</center></th>
				<th id="color"><center>Nilai UTS</center></th>
				<th id="color"><center>Nilai UAS</center></th>
				<th id="color"><center>Nilai Akhir</center></th>
				<th id="color"><center>Grade</center></th>
				<th id="color"><center>Ket</center></th>
				<th id="color"><center>Aksi</center></th>
			</tr>
		</thead>
		<tbody>
		<?php $i=1; $sksn=0; $sks=0; foreach ($data['data_nilai'] as $row) : ?>
			<tr>
				<td><?= $i++."." ?></td>
				<td style="display:none;"><?= e($row['kode_krs_detail']) ?></td>
				<td><?= e($row['kode_matakuliah']) ?></td>
				<td><?= e($row['nama_matakuliah']) ?></td>
				<td style="text-align:center;"><?= e($row['nilai_harian']) ?></td>
				<td style="text-align:center;"><?= e($row['nilai_uts']) ?></td>
				<td style="text-align:center;"><?= e($row['nilai_uas']) ?></td>
				<td><?= e($row['nilai_akhir']) ?></td>
				<td><?= e($row['grade']) ?></td>
				<td><?= $row['tb'] == 'A' ? 'TB' : '' ?></td>
			</tr>
			<?php 
				$sksn = $sksn + $row['sksn']; 
				$sks = $sks + $row['sks']; 

			?>
		<?php endforeach; ?>
		</tbody>
	</table>
      <p>Total SKS : <?= $data['total_sks']; ?></p>
</div>
    <!--    modal tambah matakuliah-->
    <div class="modal fade" id="tambah-matakuliah" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Tambah Matakuliah</h4>
                </div>
                <div class="modal-body">
                    <form action="<?= site_url('admin/akademik/perubahan/semester_lalu/simpan') ?>" method="post">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <div class="form-group">
                            <label>Nama Matakuliah</label>
                            <input type="hidden" name="kode_krs" value="<?= e($data['kode_krs']) ?>">
                            <input type="hidden" name="nim" value="<?= e($data['nim']) ?>">
                            <select required style="width:100%;" name="id_matakuliah" class="form-control select2">
                                <option selected disabled>Pilih</option>
                                <?php foreach ($matakuliah as $row) : ?>
                                    <option value="<?= e($row->id_matakuliah) ?>"><?= e($row->kode_matakuliah) ?>
                                        - <?= e($row->nama_matakuliah) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label>Tidak Berhak</label>
                                    <select name="tidak_berhak" id="" class="form-control">
                                        <option selected value="">Pilih</option>
                                        <option value="N">Berhak</option>
                                        <option value="A">Tidak Berhak</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger flat" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-check-square-o"></i> Simpan</button>
                </div>
                </form>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
<?php else: ?>
    <div class="callout callout-info flat">
        <h4>Info!</h4>

        <p>Data KRS dan Nilai semseter lalu tidak di temukan untuk nim mahasiswa bersangkutan.</p>
    </div>
<?php endif; ?>

<script type="text/javascript">
	var lastKodeKrsDetail = '';
	$('#table-edit').Tabledit({
        url: "<?= site_url('admin/akademik/perubahan/Semester_lalu/ubah_krs_nilai')  ?>",
        hideIdentifier: true,
        restoreButton: false,
        onAjax: function (action, serialize) {
            if (action === 'edit') {
                var match = serialize.match(/(?:^|&)kode_krs_detail=([^&]*)/);
                lastKodeKrsDetail = match ? decodeURIComponent(match[1]) : '';
            }
            return true;
        },
        columns: {
            identifier: [1, 'kode_krs_detail'],
            editable: [[4, 'nilai_harian'], [5, 'nilai_uts'],[6, 'nilai_uas'], [7, 'nilai_akhir'], [9, 'tidak_berhak', '{"N": "Berhak", "A": "TB"}']],
        },
        onSuccess: function(data, textStatus, jqXHR) {
            if (data && data.status === true) {
                if (data.action === 'edit' && lastKodeKrsDetail) {
                    $('#table-edit tbody tr').each(function () {
                        if ($(this).find('td:eq(1)').text() === lastKodeKrsDetail) {
                            $(this).find('td:eq(8)').text(data.grade || '-');
                        }
                    });
                    swal("Berhasil!", "Pembaruan selesai", "success");
                } else if (data.action === 'delete') {
                    $('#table-edit tbody tr.tabledit-deleted-row').remove();
                    swal("Berhasil!", "Penghapusan berhasil", "success");
                }
            } else {
                swal("Gagal!", "Gagal memperbarui data", "error");
            }
        },
        onFail: function (jqXHR, textStatus, errorThrown) {
            swal("Gagal!", "Terjadi kesalahan saat menyimpan", "error");
        }
    });
</script>