<div class="table-responsive">
    <dl class="dl-horizontal">
        <dt>Dosen</dt>
        <?php
        foreach ($dosen as $item) :
            ?>
            <dd><?= e($item->nama_dosen) ?></dd>
        <?php
        endforeach;
        ?>
        <dt>Matakuliah</dt>
        <dd><?= e($detail->kode_matakuliah) ?> - <?= e($detail->nama_matakuliah) ?> - Kelas : <?= e($detail->nama_kelas) ?></dd>
    </dl>
    <table class="table demo-table">
        <thead>
        <tr>
            <th rowspan="2">Isian</th>
            <th colspan="2">Validasi</th>
            <th colspan="2">Catatan</th>
            <th rowspan="2">Update</th>
        </tr>
        <tr>
            <th>Prodi</th>
            <th>Dekan</th>
            <th>Prodi</th>
            <th>Dekan</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $time = time();
        foreach ($komentar as $row) : ?>
            <tr>
                <td><?= nilai_validasi($row->isian) ?></td>
                <td><?= nilai_validasi($row->validasi_prodi) ?></td>
                <td><?= nilai_validasi($row->validasi_dekan) ?></td>
                <td><?= e($row->catatan_prodi) ?></td>
                <td><?= e($row->catatan_dekan) ?></td>
                <td><?= timespan(strtotime($row->updated_at), $time) . ' ago' ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
