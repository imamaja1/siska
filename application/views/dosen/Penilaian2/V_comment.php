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
                <td><?= e($row->kritik) ?></td>
                <td><?= e($row->saran) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
    <p style="text-align: center; font-size: 20pt"><i>"Tidak ada saran untuk anda"</i></p>
<?php endif; ?>