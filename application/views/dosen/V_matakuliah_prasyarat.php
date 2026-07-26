<div class="box box-solid flat">
    <div class="box-body"><br>
        <form name="kurikulum_form" class="form-horizontal"  action="<?= $form_action; ?>" method="POST">
            <div class="form-group">
                <label class="control-label col-sm-3">Kurikulum <label class="text-danger">*</label> :</label>
                <div class="col-sm-3">
                    <?php
                    $js = 'class="form-control select2" onChange="this.form.submit();"';
                    echo form_dropdown('kode_nama_kurikulum', $option_nama_kurikulum, isset($default['kode_nama_kurikulum']) ? $default['kode_nama_kurikulum'] : '', $js);
                    ?>
                </div>
                <small class="text-danger"><?= form_error('kode_nama_kurikulum'); ?></small>
            </div>
        </form>
    </div>
</div>

<?= isset($table) ? $table : ""; ?>








