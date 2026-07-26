
        <option value="" selected disabled>Pilih</option>
        <?php if (count($datas) > 0) : ?>
            <?php foreach ($datas as $data) : ?>
                <option value="<?= $data->id_matakuliah ?>"><?=$data->kode_matakuliah.' - '.$data->nama_matakuliah ?></option>
            <?php endforeach; ?>
        <?php else: ?>
            <option value="">Data tidak ditemukan</option>
        <?php endif; ?>
