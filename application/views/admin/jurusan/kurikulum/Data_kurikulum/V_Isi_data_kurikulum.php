<div class="box box-primary flat">
    <div class="box-body flat">
        <form action="" method="post">
        <?php foreach ($data_matakuliah as $row) : ?>
            <div class="col-xs-4">
                <div class="icheckbox_flat-green" aria-checked="true" aria-disabled="false">
                    <label class="">
                        <input type="checkbox" value="<?= $row->kode_matakuliah ?>" class="flat-red">
                        <?= $row->nama_matakuliah ?>
                    </label>
                </div>
            </div>

        <?php endforeach; ?>
        </form>
    </div>
</div>