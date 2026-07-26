<?php if (count($pengampu) > 0) : ?>
    <p><b>Dosen Pengampu : </b></p>
    <ul>
        <?php foreach ($pengampu as $row) : ?>
            <li><?= e($row->nama_dosen) ?> - <a href="#" onclick="hapus_pengampu('<?= e($row->mengajar_id) ?>')"><span class="text-danger"><i class="fa fa-trash"></i></span></a></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i> Alert!</h4>
        Tidak ada dosen pengampu!
    </div>
<?php endif; ?>