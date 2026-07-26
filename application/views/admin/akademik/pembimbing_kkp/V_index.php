<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="box box-solid flat">
            <div class="box-body">
                <button class="btn btn-primary btn-flat" onclick="add()"><i class="fa fa-plus-circle"></i> Berikan Pembimbing</button>
                <a href="<?= site_url('admin/akademik/pembimbing_kkp/rekap_kkp') ?>" class="btn btn-danger btn-flat"><i class="fa fa-file-excel-o"></i> Rekap KKP</a>
                <div class="pull-right col-xs-4" style="padding: 0">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input id="cari" type="text" class="form-control" placeholder="NIM / Nama mahasiswa">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->session->flashdata('info') ? $this->session->flashdata('info') : ''?>
<div id="hasil-cari">

</div>
<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="box box-warning flat" id="cont">
            <?php if (count($data) > 0) : ?>
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-table"></i> Daftar Pembimbing KKP</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table demo-table data-table">
                            <thead>
                                <tr>
                                    <th style="text-align: center;width:3%;">NO.</th>
                                    <th style="text-align: center">PEMBIMBING</th>
                                    <th style="text-align: center">JUMLAH BIMBINGAN</th>
                                    <th style="text-align: center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $no=1; foreach ($data as $row) : ?>
                                <tr>
                                    <td><?= $no++ ?>.</td>
                                    <td><?= $row->nama_dosen ?></td>
                                    <td align="center"><span class="badge bg-info"><?= $row->jumlah_bimbingan ?> Mahasiswa</span></td>
                                    <td align="center">
                                        <a href="<?= site_url('admin/akademik/pembimbing_kkp/view/'.$row->kode_dosen) ?>" class="btn bg-maroon btn-xs">
                                            <i class="fa fa-eye"></i> Lihat Bimbingan
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="box-body" style="height: 20%; text-align: center">
                    <span class="text-orange"><i class="fa fa-warning fa-4x"></i></span>
                    <p><b>Tidak ada data bimibingan</b></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!--modal tambah bimbingan-->
<div class="modal fade" id="modal-tambah" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-cont">

        </div>
    </div>
</div>
<!--end modal tambah bimbingan-->
<script>
    function add() {
        var url = "<?= site_url('admin/akademik/pembimbing_kkp/tambah')?>";
        $.ajax({
            url : url,
            success : function (res) {
                $('#modal-cont').html(res);
                $('#modal-tambah').modal('show');
            },
            error : function () {
                console.log('gagal load');
            }
        })
    }

    $('#cari').keyup(function () {
        var keyword = $(this).val();
        var url = "<?= site_url('admin/akademik/pembimbing_kkp/cari') ?>";
        if  (keyword != "")
        {
            $('#cont').hide();
            $.ajax({
                url : url,
                data : 'keyword='+keyword,
                type :'post',
                success : function (res) {
                    console.log('berhsil');
                    $('#hasil-cari').html(res);
                },
                error : function () {
                    console.log('gagal load');
                }
            });
        }else{
            $('#cont').show();
        }

    })
</script>