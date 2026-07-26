<div class="box box-danger">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-line-chart"></i> Pendistribusian Nilai</h3>
        <div class="box-tools">
            <a href="<?= site_url('admin/akademik/nilai/distribusi_excel/'.$kode_tahun_akademik.'/'.$kode_program_studi) ?>" class="btn btn-success flat btn-sm" title="Export Excel"><i class="fa fa-file-excel-o"></i> Excel</a>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="demo-table data-table">
                <thead>
                <tr>
                    <th rowspan="3" style="text-align: center">NO.</th>
                    <th rowspan="3" style="text-align: center">MATAKULIAH</th>
                    <th rowspan="3" style="text-align: center">KELAS/SEMESTER</th>
                    <th rowspan="3" style="text-align: center">NAMA DOSEN</th>
                    <th rowspan="3" style="text-align: center">TOTAL MHS</th>
                    <th colspan="14" style="text-align: center">DISTRIBUSI NILAI</th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: center">A</th>
                    <th colspan="2" style="text-align: center">B+</th>
                    <th colspan="2" style="text-align: center">B</th>
                    <th colspan="2" style="text-align: center">C+</th>
                    <th colspan="2" style="text-align: center">C</th>
                    <th colspan="2" style="text-align: center">D</th>
                    <th colspan="2" style="text-align: center">E</th>
                </tr>
                <tr>
                    <th style="text-align: center">ANGKA</th>
                    <th style="text-align: center">%</th>
                    <th style="text-align: center">ANGKA</th>
                    <th style="text-align: center">%</th>
                    <th style="text-align: center">ANGKA</th>
                    <th style="text-align: center">%</th>
                    <th style="text-align: center">ANGKA</th>
                    <th style="text-align: center">%</th>
                    <th style="text-align: center">ANGKA</th>
                    <th style="text-align: center">%</th>
                    <th style="text-align: center">ANGKA</th>
                    <th style="text-align: center">%</th>
                    <th style="text-align: center">ANGKA</th>
                    <th style="text-align: center">%</th>
                </tr>
                </thead>
                <tbody>
                <?php $no=1; foreach ($data as $row) : ?>
                <tr>
                    <td><?= $no++ ?>.</td>
                    <td><?= $row['nama_matakuliah'] ?></td>
                    <td style="text-align: center"><?= $row['nama_kelas'] ?> / <?= $row['semester'] ?></td>
                    <td><?= $row['nama_dosen'] ?></td>
                    <td style="text-align: center"><?= $row['total'] ?></td>
                    <?php $x=0; foreach ($row['data'] as $item) : ?>
                    <td style="text-align: center"><?= number_format($item->jumlah,0) ?></td>
                    <td style="text-align: center"><?= number_format($item->persen,0) ?></td>
                    <?php if($x++ >= 6) { break; } endforeach;  ?>
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