<?php
echo isset($table) ? $table : '';
?>

<div class="box box-primary flat">
    <div class="box-header">
        <h5><b>Pencarian Data Mahasiswa Berdasarkan Kata Kunci</b></h5>
        <hr>
    </div>
    <div class="box-body">
        <?php
        $flashmessage = $this->session->flashdata('message');
        echo isset($flashmessage) ? $flashmessage : '';
        ?>
        <form class="form-horizontal" method="post" action="<?= site_url('admin/akademik/kompetensi/search'); ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-3">Masukan Kata Kunci :</label>
                <div class="col-sm-4">
                    <input value="<?= set_value('kata_kunci') ?>" type="text" class="form-control"
                           placeholder="Ketik Kata Kunci disini" name="kata_kunci" id="kata_kunci">
                    <small class="text-danger"><?= form_error('kata_kunci') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-cog"></i> Proses</button>
                </div>
            </div>
        </form>
    </div>
</div>
