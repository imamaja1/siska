<option value="" selected disabled>Pilih</option>
<?php if (count($data) > 0) : ?>
<?php foreach ($data as $row) : ?>
    <option value="<?= $row->id_matakuliah ?>"><?= $row->kode_matakuliah ?> - <?= $row->nama_matakuliah ?></option>
<?php endforeach; ?>
<?php endif; ?>
