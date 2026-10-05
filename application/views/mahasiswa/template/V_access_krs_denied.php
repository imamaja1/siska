<div class="box box-solid flat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-history"></i> <b>Riwayat Semester KRS</b></h3>
    </div>
    <div class="box-body">
        <div class="krs-pick">
            <?php $i = 1; $has_active = false; foreach ($krs_mhs as $row) : ?>
                <?php $is_act = (isset($ta) && $row->kode_tahun_akademik == $ta); if ($is_act) $has_active = true; ?>
                <a href="<?= site_url($is_act ? 'mahasiswa/krs/index/' : 'mahasiswa/krs/old/'.$row->semester) ?>" class="btn <?= $is_act ? 'btn-primary' : 'btn-default' ?> btn-sm">
                    <i class="fa fa-calendar-check-o"></i>
                    <?php if ($is_act) : ?>
                        Semester <?= isset($semester_aktif) ? $semester_aktif : $row->semester ?> (Aktif)
                    <?php else : ?>
                        <?= ($row->semester == 'K') ? 'Konversi' : 'Semester '.$i++ ?>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
            <?php if (!$has_active) : ?>
                <a href="<?= site_url('mahasiswa/krs/index/') ?>" class="btn btn-primary btn-sm">
                    <i class="fa fa-calendar-check-o"></i> Semester <?= isset($semester_aktif) ? $semester_aktif : '' ?> (Aktif)
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->session->flashdata('info') ?>
