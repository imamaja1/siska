<div class="box box-solid box-warning">
    <div class="box-header with-border">Data Nilai KPAT Mahasiswa</div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table data-table ">
                <thead>
                    <tr>
                        <th>no</th>
                        <th>Nim</th>
                        <th>Nama</th>
                        <th>Harian</th>
                        <th>UTS</th>
                        <th>UAS</th>
                        <th>Nilai Akhir</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  foreach ($data as $key => $value) { ?> 
                        <tr>
                            <td><?= $key+1 ?></td>
                            <td><?= e($value->nim) ?></td>
                            <td><?= e($value->nama_mahasiswa) ?></td>
                            <td><?= e($value->nilai_harian) ?></td>
                            <td><?= e($value->nilai_uts) ?></td>
                            <td><?= e($value->nilai_uas) ?></td>
                            <td><?= e($value->nilai_akhir) ?></td>
                            <td><?= e(substr($value->nim,1,2) < 22 ? $value->grade1:$value->grade2) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $('.data-table').DataTable();
    </script>
</div>
