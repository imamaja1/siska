<option value="" selected disabled>Pilih</option>
<?php if (count($data) > 0) : ?>
<?php foreach ($data as $row) : ?>
    <option value="<?= e($row->id_matakuliah) ?>"><?= e($row->kode_matakuliah) ?> - <?= e($row->nama_matakuliah) ?></option>
<?php endforeach; ?>
<?php endif; ?>
