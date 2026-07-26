<table class="table table-bordered table-striped data-table">
    <thead>
        <tr>
            <th>NO.</th>
            <th>KODE MATAKULIAH</th>
            <th>MATAKULIAH</th>
            <th>AKSI</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        foreach ($data as $key => $row): ?>
            <tr>
                <td><?= $no++ ?>.</td>
                <td>
                    <?= $row->kode_matakuliah ?>
                </td>
                <td><?= $row->nama_matakuliah ?></td>
                <td>
                    <?php if ($row->kelas_id): ?>
                        <button onclick="kelas('<?= $row->id_matakuliah ?>')" class="btn btn-xs btn-default"
                            title="Lihat Kelas"><i class="fa fa-eye"></i> View
                        </button>
                        <?php else: ?>
                        <button onclick="generate('<?= $row->id_matakuliah ?>')" class="btn btn-xs btn-primary"
                            title="Generate Kelas"><i class="fa fa-gear"></i> Generate
                        </button>
                        <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
    </tbody>
</table>