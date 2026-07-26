<?php if(count($data) > 0 ) : ?>
<div class="box box-success flat">
    <div class="box-header">
        <button class="btn btn-link btn-xs flat">terdapat <strong><?= isset($jumlah_data) ? $jumlah_data : " 0 " ?>Data</strong></button>
        <div class="pull-right">
            <?= isset($halaman) ? $halaman : "" ?>
        </div>
    </div>
	<div class="box-body">
        <div class="table-responsive">
		<table class="table demo-table">
			<thead>
				<tr>
					<th width="20" id="color">No.</th>
					<th id="color"><center>NIM</center></th>
					<th id="color"><center>Nama Mahasiswa</center></th>
					<th id="color"><center>Dosen Wali</center></th>
					<th id="color"><center>Aksi</center></th>
				</tr>
			</thead>
			<tbody>
			<?php $i=1 + $this->uri->segment(5); foreach ($data as $row) : ?>
				<tr>
					<td><center><?= $i++ ?>.</center></td>
					<td><center><?= $row->nim ?></center></td>
					<td><?= $row->nama_mahasiswa ?></td>
					<td><?= $row->nama_dosen ?></td>
					<td style="text-align: center;">
						<a href="#"  onclick="view('<?= $row->nim ?>')" class="btn btn-info btn-xs flat"><i class="fa fa fa-eye" aria-hidden="true"></i> Lihat</a>&nbsp;
                        <a href="<?= site_url('admin/jurusan/konsultasi_perwalian/print_view/'.$row->nim) ?>" target="_blank" class="btn btn-danger btn-xs flat" id="coba"><i class="fa fa-print"></i> Print</a>
                        <a href="#" onclick="grafik('<?= $row->nim ?>')" class="btn btn-warning btn-xs flat"><i class="fa fa-line-chart"></i> Grafik Nilai</a>
					</td>
				</tr>
			<?php endforeach; ?>
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
<?php endif; ?>
<!--modal view-->
<div class="modal fade" id="modal-view" style="display: none;">
    <div class="modal-dialog" style="max-width: 80%; width: 100%">
        <div class="modal-content" id="landing-modal" style="border-radius: 10px">
        </div>
    </div>
</div>
<div class="modal fade" id="modal-grafik" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content" id="content-grafik" style="border-radius: 10px">
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#halaman a').on('click', function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url : url,
                success : function (res) {
                    $('#landing').html(res);
                },
                error : function () {
                    console.log('gagal load');
                }
            })
        });
    })

    function view(id) {
        var url = "<?= site_url('admin/jurusan/konsultasi_perwalian/detail') ?>/"+id;
        $.ajax({
            url : url,
            success : function (res) {
                $('#landing-modal').html(res);
                $("#modal-view").modal('show');
            },
        })
    }
    function grafik(id) {
        var url = "<?= site_url('admin/jurusan/konsultasi_perwalian/grafik_nilai') ?>/"+id;
        $.ajax({
            url : url,
            success : function (res) {
                $('#content-grafik').html(res);
                $("#modal-grafik").modal('show');
            },
        })
    }

</script>
