<div class="box box-danger">
    <div class="box-header">
        <h4 class="box-title"><span class="text-danger"><i class="fa fa-ban"></i></span> Lists Blocking</h4>
    </div>
    <div class="box-body">
        <?php if (count($data) > 0) : ?>
            <div class="table-responsive">
                <table class="table demo-table datatable">
                    <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">NIM</th>
                        <th class="text-center">Nama Mahasiswa</th>
                      	<th class="text-center">Tahun Akademik</th>
                        <th class="text-center">Semester</th>
                        <th class="text-center">Tgl Dibuat</th>
                        <th class="text-center">#</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $key => $row) : ?>
                    <tr>
                        <td class="text-center"><?= $key + 1 ?>.</td>
                        <td class="text-center"><?= e($row->nim) ?></td>
                        <td><?= e($row->nama_mahasiswa) ?></td>
                       <td class="text-center"><?= e($row->tahun_akademik) ?></td>
                        <td class="text-center"><?= $row->semester == 1 ? 'Ganjil' : 'Genap'; ?></td>
                        <td class="text-center"><?= date('d M, Y', strtotime($row->created_at)); ?></td>
                        <td class="text-center">
                            <button onclick="hapus('<?= e($row->id) ?>')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Hapus</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
        <p style="font-weight: bold; font-size: 20pt; text-align: center"><span class="text-warning"><i class="fa fa-warning"></i></span> Tidak Ada Data</p>
        <?php endif; ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.datatable').dataTable();
    })
</script>