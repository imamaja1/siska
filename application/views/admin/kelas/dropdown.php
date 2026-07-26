
        <option value="" selected disabled>Pilih</option>
        <?php if (count($datas) > 0) : ?>
            <?php foreach ($datas as $data) : ?>
                <option value="<?= e($data->id_matakuliah) ?>"><?= e($data->kode_matakuliah) . ' - ' . e($data->nama_matakuliah) ?></option>
            <?php endforeach; ?>
        <?php else: ?>
            <option value="">Data tidak ditemukan</option>
        <?php endif; ?>
