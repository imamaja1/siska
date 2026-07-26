<?= $this->session->flashdata('pesan'); ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/krs') ?>" class="btn btn-success btn-xs flat"><i class="fa fa-arrow-left"></i> Kembali</a>
        <?php if (count($data_mahasiswa) > 0): ?>
            <button class="btn btn-flat btn-default btn-xs ">Terdapat <b><?= e($jumlah_data) ?> Record</b></button>
            <div class="pull-right">
                <?= $halaman; ?>
            </div>
        <?php else: ?>

        <?php endif; ?>
    </div>
</div>
<?php if (count($data_mahasiswa) > 0): ?>
    <div class="box box-primary flat">

        <div class="box-body">
            <table class="table demo-table">
                <thead>
                    <tr>
                        <th style="text-align: center;">NO.</th>
                        <th style="text-align: center;">NIM</th>
                        <th style="text-align: center;">NAMA MAHASISWA</th>
                        <th style="text-align: center;">AKSI</th>
                    </tr>
                </thead>

                <?php
                $no = 1 + $this->uri->segment(5);
                foreach ($data_mahasiswa as $data) {
                    ?>
                    <tr>
                        <td align="center"><?= $no++ ?>.</td>
                        <td align="center"><?= e($data->nim) ?></td>
                        <td ><?= e($data->nama_mahasiswa) ?></td>
                        <td width="200" align="center">
                            <a href="<?= site_url('admin/akademik/krs/cetak/' . $data->nim); ?>"  class="btn btn-xs btn-info flat"><i class="fa fa-download"></i> Download</a>
                            <a href="<?= site_url('admin/akademik/krs/print_view_any/' . $data->nim); ?>" target="_blank"  class="btn btn-xs btn-danger flat"><i class="fa fa-print"></i> Cetak</a>
                        </td>
                    </tr>

                <?php } ?>

            </table>
        </div>
    </div>
<?php else: ?>
    <div class="callout callout-warning flat">
        <h5>Data Mahasiswa tidak ditemukan !</h5>
    </div>

<?php endif; ?>
