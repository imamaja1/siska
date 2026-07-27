<?php
echo!empty($h2_title) ? '<h2>' . $h2_title . '</h2>' : '';
echo!empty($message) ? '<p class="message">' . $message . '</p>' : '';

$flashmessage = $this->session->flashdata('message');
echo!empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>' : '';
?>


    <div class="box box-solid flat" <?= e($hidden)  ?>>
        <div class="box-body"><br>
            <form class="form-horizontal" name="perwakilan_perwalian_form" method="post" action="<?php echo $form_action; ?>">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="form-group">
                    <label class="control-label col-sm-2">Nama Dosen</label>
                    <div class="col-sm-4">
                        <?php
                        $js = ' class="form-control" onChange="this.form.submit();"';
                        echo form_dropdown('kode_dosen', $options_dosen, isset($default['kode_dosen']) ? $default['kode_dosen'] : '', $js);
                        ?>
                        <?php echo form_error('kode_dosen', '<p class="field_error">', '</p>'); ?>
                    </div>
                </div>

            </form>
        </div>
    </div>


<?php
if (isset($table)) {
    echo '<div class="box box-primary flat"><div class="box-body">';
    echo!empty($header) ? $header : '';
    echo!empty($num_rows) ? "Terdapat <b>$num_rows records.</b>" : '';
    echo!empty($table) ? $table : '';
    echo '</div></div>';
}
if (!empty($link)) {
    echo '<p id="bottom_link">';
    foreach ($link as $links) {
        echo $links . ' ';
    }
    echo '</p>';
}
?>
    

