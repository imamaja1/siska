<div class="row">
    <div class="col-md-12">
        <div class="box box-solid flat">
            <div class="box-body">
                <a href="<?= site_url('admin/akademik/nilai') ?>" class="btn btn-danger btn-xs" title="Kembali"><i class="fa fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-5">
        <div class="box box-warning">
            <div class="box-header">
                <h3 class="box-title">Program Studi</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="demo-table">
                        <thead>
                        <tr>
                            <th>Program Studi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $total = 0; $no=1; foreach ($prodi as $row) : ?>
                        <tr>
                            <td><?= e($row['nama_program_studi']) ?></td>
                            <td>
                                <?php if ($row['status']) : ?>
                                    <span class="badge bg-green"><i class="fa fa-check-square-o"></i> Selesai</span>
                                <?php else : ?>
                                    <span class="badge bg-red"><i class="fa fa-pie-chart"></i> <?= $row['semua'] != 0 ? number_format($row['persen'],2) : 0 ?> %</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="#" onclick="matakuliah('<?= e($row['kode_program_studi']) ?>')" class="btn btn-sm btn-danger" title="Lihat"><i class="fa fa-line-chart"></i> Lihat</a>
                            </td>
                        </tr>
                        <?php $total = $total + $row['persen']; endforeach; ?>
                        <tr style="background-color: #00a7d0">
                            <td style="text-align: center"><b>Total Persentasi</b></td>
                            <td colspan="2" style="text-align: center"><span class="badge bg-green"><b><?= number_format($total / (count($prodi) - 1),2) ?> %</b></span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7" id="landing">
        <div class="box box-solid" style="height: 300px; border: 2px solid #70c0ff">
            <div class="box-body">
                <p style="text-align: center; font-size: 12pt"><i>"Landing Page..."</i></p>
            </div>
        </div>
    </div>
</div>
<!--script-->
<script>
    var loader = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
    function matakuliah(kode_program_studi) {
        var url = "<?= site_url('admin/akademik/nilai/persentase_penginputan_prodi') ?>/"+kode_program_studi;
        $.ajax({
            url : url,
            beforeSend : function () {
                $("#landing").html(loader);
            },
            success : function (res) {
                $("#landing").html(res);
            }
        })
    }
</script>