<?php if(isset($data) && count($data) > 0 ) : ?>
<div class="box box-primary flat">
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
                  	<th id="color"><center>View</center></th>
					<th id="color"><center>Downlaod</center></th>
                    <th id="color"><center>Print</center></th>
				</tr>
			</thead>
			<tbody>
			<?php $i=1 + $this->uri->segment(5); foreach ($data as $row) : ?>
				<tr>
					<td><center><?= $i++ ?>.</center></td>
					<td><center><?= $row->nim ?></center></td>
					<td><?= $row->nama_mahasiswa ?></td>
                  	
                  	<td style="text-align: center;">
						<a href="#"  onclick="view_ganjil('<?= $row->nim ?>')" class="btn btn-primary btn-xs flat"><i class="fa fa fa-eye" aria-hidden="true"></i> Ganjil</a>
                        <a href="#"  onclick="view_genap('<?= $row->nim ?>')" class="btn btn-primary btn-xs flat"><i class="fa fa fa-eye" aria-hidden="true"></i> Genap</a>
					</td>
                  	<td style="text-align: center;">
						<!--<a href="#"  onclick="view('<?= $row->nim ?>')" class="btn btn-info btn-xs flat"><i class="fa fa fa-eye" aria-hidden="true"></i> Lihat</a>&nbsp;-->
                        <a href="<?= site_url('admin/akademik/Petikan_nilai/cetak_ganjil/'.$row->nim) ?>" class="btn btn-warning btn-xs flat"><i class="fa fa-download"></i> Ganjil</a>
                        <a href="<?= site_url('admin/akademik/Petikan_nilai/cetak_genap/'.$row->nim) ?>" class="btn btn-warning btn-xs flat"><i class="fa fa-download"></i> Genap</a>
					</td>
                    <td style="text-align: center;">
						<!--<a href="#"  onclick="view('<?= $row->nim ?>')" class="btn btn-info btn-xs flat"><i class="fa fa fa-eye" aria-hidden="true"></i> Lihat</a>&nbsp;-->
                        <a href="<?= site_url('admin/akademik/Petikan_nilai/print_view_ganjil/'.$row->nim) ?>" target="_blank" class="btn btn-danger btn-xs flat" id="coba"><i class="fa fa-print"></i> Ganjil </a>
                        <a href="<?= site_url('admin/akademik/Petikan_nilai/print_view_genap/'.$row->nim) ?>" target="_blank" class="btn btn-danger btn-xs flat" id="coba"><i class="fa fa-print"></i> Genap</a>
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
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Petikan Nilai</h4>
            </div>
            <div class="modal-body" id="landing-modal">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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

//    $(document).bind('keydown', function(e) {
//        var code = (e.keyCode ? e.keyCode : e.which);
//        if (code == 80 && !$('#print-modal').length) {
//            $.printPreview.loadPrintPreview();
//            return false;
//        }
//    });

    function view(id) {
        var url = "<?= site_url('admin/akademik/Petikan_nilai/detail') ?>/"+id;
        $.ajax({
            url : url,
            success : function (res) {
                $('#landing-modal').html(res);
                $("#modal-view").modal('show');
            },
            error : function () {
                console.log('gagal load');
            }
        })
    }
  	function view_ganjil(id) {
        var url = "<?= site_url('admin/akademik/Petikan_nilai/detail_ganjil') ?>/"+id;
        $.ajax({
            url : url,
            success : function (res) {
                $('#landing-modal').html(res);
                $("#modal-view").modal('show');
            },
            error : function () {
                console.log('gagal load');
            }
        })
    }
    function view_genap(id) {
        var url = "<?= site_url('admin/akademik/Petikan_nilai/detail_genap') ?>/"+id;
        $.ajax({
            url : url,
            success : function (res) {
                $('#landing-modal').html(res);
                $("#modal-view").modal('show');
            },
            error : function () {
                console.log('gagal load');
            }
        })
    }
</script>
