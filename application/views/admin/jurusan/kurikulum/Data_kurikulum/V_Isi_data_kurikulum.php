<div class="box box-primary flat">
    <div class="box-body flat">
        <form action="" method="post">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
        <?php foreach ($data_matakuliah as $row) : ?>
            <div class="col-xs-4">
                <div class="icheckbox_flat-green" aria-checked="true" aria-disabled="false">
                    <label class="">
                        <input type="checkbox" value="<?= e($row->kode_matakuliah) ?>" class="flat-red">
                        <?= e($row->nama_matakuliah) ?>
                    </label>
                </div>
            </div>

        <?php endforeach; ?>
        </form>
    </div>
</div>