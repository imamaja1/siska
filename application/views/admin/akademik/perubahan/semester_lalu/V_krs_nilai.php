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
				<td><?= $data['nama_mahasiswa']  ?></td>
			</tr>
			<tr>
				<td><strong>NIM</strong></td>
				<td><strong>:</strong></td>
				<td><?= $data['nim']  ?></td>
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
				<td><?= $data['prodi']->nama_program_studi  ?></td>
			</tr>
			<tr>
				<td><strong>Fakultas</strong></td>
				<td><strong>:</strong></td>
				<td><?= $data['prodi']->nama_fakultas  ?></td>
			</tr>
			<tr>
				<td><strong>Kurikulum</strong></td>
				<td><strong>:</strong></td>
				<td><?= $data['kurikulum']?></td>
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
				<td style="display:none;"><?= $row['kode_krs_detail'] ?></td>
				<td><?= $row['kode_matakuliah'] ?></td>
				<td><?= $row['nama_matakuliah'] ?></td>
				<td style="text-align:center;"><?= $row['nilai_harian'] ?></td>
				<td style="text-align:center;"><?= $row['nilai_uts'] ?></td>
				<td style="text-align:center;"><?= $row['nilai_uas'] ?></td>
				<td><?= $row['nilai_akhir'] ?></td>
				<td><?= $row['grade'] ?></td>
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
                        <div class="form-group">
                            <label>Nama Matakuliah</label>
                            <input type="hidden" name="kode_krs" value="<?= $data['kode_krs'] ?>">
                            <input type="hidden" name="nim" value="<?= $data['nim'] ?>">
                            <select required style="width:100%;" name="id_matakuliah" class="form-control select2">
                                <option selected disabled>Pilih</option>
                                <?php foreach ($matakuliah as $row) : ?>
                                    <option value="<?= $row->id_matakuliah ?>"><?= $row->kode_matakuliah ?>
                                        - <?= $row->nama_matakuliah ?></option>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript">
	$('#table-edit').Tabledit({
        url: "<?= site_url('admin/akademik/perubahan/Semester_lalu/ubah_krs_nilai')  ?>",
        hideIdentifier: true,
        columns: {
            identifier: [1, 'kode_krs_detail'],
            editable: [[4, 'nilai_harian'], [5, 'nilai_uts'],[6, 'nilai_uas'], [7, 'nilai_akhir'], [9, 'tidak_berhak', '{"N": "Berhak", "A": "TB"}']],
        },
        onSuccess: function(data, textStatus, jqXHR) {
            console.log(data);
            if (data) {
                window.location.reload();    
            }else{
                Swal.fire({
                    title: 'Warning!',
                    text: 'Data Selain Skripsi dan KPP/KKN/Magang Tidak dapat Diubah',
                    icon: 'error'
                });
            }
        }
    });
</script>