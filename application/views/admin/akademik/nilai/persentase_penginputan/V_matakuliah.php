<div class="box box-danger">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-line-chart"></i> Matakuliah</h3>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="demo-table data-table">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode Matakuliah</th>
                    <th>Matakuliah</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php $no=1; foreach ($matakuliah as $row) : ?>
                <tr>
                    <td><?= $no++ ?>.</td>
                    <td><?= e($row['kode_matakuliah']) ?></td>
                    <td><?= e($row['nama_matakuliah']) ?></td>
                    <td>
                        <?php if ($row['status']) : ?>
                            <span class="badge bg-green"><i class="fa fa-check-square-o"></i> Selesai</span>
                        <?php else : ?>
                            <span class="badge bg-red"><i class="fa fa-pie-chart"></i> <?= number_format($row['terisi']/$row['semua']*100,2) ?> %</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js'); ?>"></script>
<script>
    $(".data-table").dataTable({
        "ordering": false,
        "info": false,
        "pageLength": 25
    });
</script>