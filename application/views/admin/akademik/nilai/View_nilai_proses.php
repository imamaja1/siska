
<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/nilai'); ?>" class="btn btn-xs btn-success flat"><i class="fa fa-arrow-left"></i> Kembali</a>
        <a href="<?= site_url('admin/akademik/nilai/download'); ?>" class="btn btn-xs btn-info flat"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</a>
        <?php if (!empty($jumlah_data) > 0): ?>

            <button class="btn btn-flat btn-default btn-xs ">Terdapat <b><?= e($jumlah_data); ?> Record</b></button>
            <div class="pull-right">
                <?= e($halaman); ?>
            </div>

        <?php else: ?>

        <?php endif; ?>
    </div>
</div>
<?php
echo!empty($table) ? $table : '';

$flashmessage = $this->session->flashdata('message');
echo!empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>' : '';
?>
