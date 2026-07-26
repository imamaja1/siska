<?php if (count($pengampu) > 0) : ?>
    <p><b>Dosen Pengampu : </b></p>
    <ul>
        <?php foreach ($pengampu as $row) : ?>
            <li><?= e($row->nama_dosen) ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i> Alert!</h4>
        Tidak ada dosen pengampu!
    </div>
<?php endif; ?>