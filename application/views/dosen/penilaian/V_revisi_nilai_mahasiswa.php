<table class="table table-bordered demo-table">
    <thead>
        <tr>
            <th style="white-space: nowrap;width: 1px; text-align: center">No</th>
            <th>Nim</th>
            <th>Nama Mahasiswa</th>
            <th>Harian</th>
            <th>UTS</th>
            <th>UAS</th>
          	<th>Nilai Akhir</th>
            <th>Grade</th>
            <th>Status</th>
            <th>Keteragnan</th>
        </tr>
    </thead>                                                                                                      
    <tbody>
        <?php foreach ($data as $key => $row) : ?>
        <tr>
            <td align="center"><?= e($key+1) ?></td>
            <td ><?= e($row->nim) ?></td>
            <td ><?= e($row->nama_mahasiswa) ?></td>
            <td align="center"><?= e($row->harian) ?></td>
            <td align="center"><?= e($row->uts) ?></td>
            <td align="center"><?= e($row->uas) ?></td>
          	<td align="center"><?= e($row->na) ?></td>
            <td align="center"><?= e($row->grade) ?></td>
            <td align="center"><?= ($row->mbkm_id) ? 'MBKM' : ($row->block_id ? 'Block' : '-'); ?></td>
            <td align="center"><?= e($row->ket ? $row->ket:'-') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>