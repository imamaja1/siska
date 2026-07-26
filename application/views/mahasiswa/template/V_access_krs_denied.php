<div class="box box-solid flat">
    <div class="box-body">
        <?php $i=1; foreach ($krs_mhs as $row) : ?>
            <a href="<?= site_url('mahasiswa/krs/old/'.$row->kode_tahun_akademik.'/'.$row->semester) ?>" class="btn bg-navy flat btn-xs"><i class="fa fa-arrow-circle-right"></i> | SEMESTER <?= $i++ ?></a>
        <?php endforeach; ?>
        <a href="<?= site_url('mahasiswa/krs/index/') ?>" class="btn bg-navy flat btn-xs"><i class="fa fa-arrow-circle-right"></i> | SEMESTER <?= $i++ ?></a>
    </div>
</div>
<?= $this->session->flashdata('info') ?>