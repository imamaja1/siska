<?php if (count($data) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th style="text-align: center">KODE MATAKULIAH</th>
                <th style="text-align: center">NAMA MATAKLIAH</th>
                <th style="text-align: center">SEMESTER</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $row) : ?>
                <tr>
                    <td style="text-align: center"><?= e($row->kode_matakuliah) ?></td>
                    <td><?= e($row->nama_matakuliah) ?></td>
                    <td style="text-align: center"><?= $row->semester ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i> Hay, Ada pesan unutk anda!</h4>
        Kompetensi / Konstrasi <b>tidak tersedia</b> pada kurikulum yang anda gunakan, Silahkan pilih kompetensi yang lain.
    </div>
<?php endif; ?>
