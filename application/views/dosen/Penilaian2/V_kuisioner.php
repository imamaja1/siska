<?php if (count($kuisioner) > 0) : ?>
    <?php foreach ($kuisioner['isi'] as $key => $row) : ?>
        <?php if ($row->kelas_id != "") : ?>
        <div class="col-md-4 col-xs-12">
            <div class="info-box bg-yellow-gradient">
                <span class="info-box-icon" style="font-size: 24pt"><?= e($row->hasil_akhir) ?></span>

                <div class="info-box-content">
                    <span class="info-box-text"><?= e($row->kode_matakuliah) ?> - <?= e($row->nama_matakuliah) ?></span>
                    <span class="info-box-number">KELAS - <?= e($row->nama_kelas) ?></span>

                    <div class="progress">
                        <div class="progress-bar" style="width: <?= ($row->jml_mhs/$kuisioner['mhs'][$key])*100 ?>%"></div>
                    </div>
                    <span class="progress-description">
                        <a href="#" style="color: #ffffff"><i class="fa fa-user"></i> <?= e($row->jml_mhs) ?> dari <?= e($kuisioner['mhs'][$key]) ?> mahasiswa</a>
                        <a href="#" onclick="show_commen('<?= e($row->kelas_id) ?>')" style="color: #ffffff" title="Lihat Kritik dan Saran" class="pull-right"><i class="fa fa-comments-o"></i></a>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <?php else: ?>
            <div class="col-md-4 col-xs-12">
                <div class="callout callout-warning">
                    <h4><i class="fa fa-warning"></i> Peringatan</h4>

                    <p>Belum ada responden yang mengisi kuisioner</p>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <div class="col-md-12 col-xs-12">
        <div class="callout callout-warning">
            <h4><i class="fa fa-warning"></i> Peringatan</h4>

            <p>Tidak ada hasil kuisioner untuk tahun akademik tersebut</p>
        </div>
    </div>
<?php endif; ?>