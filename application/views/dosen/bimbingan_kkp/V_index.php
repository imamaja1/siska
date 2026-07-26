<?= $this->session->flashdata('info') ? $this->session->flashdata('info') : '' ?>
<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="box box-primary flat">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-list"></i> Daftar Bimbingan </h3>
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
                                    <th style="text-align: center">NIM</th>
                                    <th style="text-align: center">NAMA MAHASISWA</th>
                                    <th style="text-align: center">TGL. PELAKSANAAN</th>
                                    <th style="text-align: center">BATAS LAPORAN</th>
                                    <th style="text-align: center">SISA WAKTU</th>
                                    <th style="text-align: center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $no=1; foreach ($data as $row) : ?>
                                <tr>
                                    <td><?= $no++ ?>.</td>
                                    <td align="center"><?= e($row->nim) ?></td>
                                    <td><?= e($row->nama_mahasiswa) ?></td>
                                    <td><?= date('d M Y', strtotime($row->tgl_pelaksanaan)) ?></td>
                                    <td><?= date('d M Y', strtotime($row->batas_laporan)) ?></td>
                                    <td><?= day_left($row->batas_laporan) ?></td>
                                    <td align="center">
                                        <div class="btn-group btn-group-xs">
                                            <?php if ($row->id_nilai == null) : ?>
                                                <a href="#" id="<?= e($row->id_pembimbing_kkp) ?>" class="btn btn-success pindah"><i class="fa fa-pencil"></i> Beri Nilai</a>
                                            <?php else: ?>
                                                <a href="#" id="<?= e($row->id_pembimbing_kkp) ?>" class="btn btn-warning pindah"><i class="fa fa-pencil-square-o"></i> Update Nilai</a>
                                            <?php endif;?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.pindah').popover({
            placement: 'left',
            html:true,
            title : "<i class='fa fa-file-archive-o'></i> <span class='text-blue'><b>NILAI KKP</b></span>",
            trigger: "click",
            container : 'body',
            content:  kontent,
        }).click(function () {
            $('.pindah').not(this).popover('hide');
        })


        function kontent() {
            var re ='';
            var id = $(this).attr('id');
            var url = "<?= site_url('dosen/bimbingan_kkp/get_content') ?>/"+id;
            $.ajax({
                url : url,
                async : false,
                success : function (res) {
                    re = res;
                }
            })
            return re;
        }
    })


</script>