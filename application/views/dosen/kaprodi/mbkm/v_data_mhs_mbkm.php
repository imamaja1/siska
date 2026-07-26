<div class="box box-solid box-warning">
    <div class="box-header with-border">Data Mahasiswa MBKM</div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table data-table ">
                <thead>
                    <tr>
                        <th>no</th>
                        <th>Nim</th>
                        <th>Nama</th>
                        <th>Prodi</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  foreach ($mahasiswa as $key => $value) { ?> 
                        <tr>
                            <td><?= $key+1 ?></td>
                            <td><?= e($value->nim) ?></td>
                            <td><?= e($value->nama_mahasiswa) ?></td>
                            <td><?= e($value->nama_program_studi) ?></td>
                            <td> 
                                <button type="button" class="btn btn-danger btn-sm" onclick="hapus(<?= e($value->id_fix) ?>)">Hapus</button>
                            </td>
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
