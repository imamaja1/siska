 <div class="table-responsive">
    <table class="table demo-table data-nilai2">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nim</th>
                <th scope="col">Nama</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($kelas as $key => $value) { ?>
            <tr>
                <th scope="row"><?= $key+1 ?></th>
                <td><?= $value->nim ?></td>
                <td><?= $value->nama_mahasiswa ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>