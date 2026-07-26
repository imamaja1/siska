<?php if ($jenis == 'kuisioner') : ?>
    <?php if (count($komentar) > 0) : ?>
        <div class="table-responsive">
            <table class="table demo-table">
                <thead>
                <tr>
                    <th>Yang harus di pertahankan</th>
                    <th>Yang harus di tingkatkan</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($komentar as $row) : ?>
                    <tr>
                        <td><?= $row->kritik ?></td>
                        <td><?= $row->saran ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="text-align: center; font-size: 20pt"><i>"Tidak ada saran untuk anda"</i></p>
    <?php endif; ?>
<?php else: ?>
    <div class="table-responsive">
        <table class="table demo-table">
            <thead>
            <tr>
                <th>Catatan Prodi</th>
                <th>Catatan Dekan</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?= $komentar->catatan_prodi ?></td>
                <td><?= $komentar->catatan_dekan; ?></td>
            </tr>
            </tbody>
        </table>
    </div>
<?php endif; ?>
