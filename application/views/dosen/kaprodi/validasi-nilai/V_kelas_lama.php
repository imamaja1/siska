<?php if (count($kelas) > 0) : ?>
<?php foreach ($kelas as $row) : ?>
    <?php
        if ($row->validasi_nilai == 'T' && $row->status_nilai == 'F'){
            $bg = 'bg-green-gradient';
            $icon = 'fa-check-square-o';
        }elseif ($row->validasi_nilai == 'F' && $row->status_nilai == 'F'){
            $bg = 'bg-blue-gradient';
            $icon = 'fa-history';
        }else{
            $bg = 'bg-red-gradient';
            $icon = 'fa-times';
        }
    ?>
    <a href="#" onclick="lihat('<?= $row->kelas_id ?>')" style="color: white">
        <div class="row">
            <div class="info-box <?= $bg ?> ">
                <span class="info-box-icon"><i class="fa <?= $icon ?>"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?= $row->kode_matakuliah ?> - <?= $row->nama_matakuliah ?></span>
                    <span class="info-box-number">KELAS - <?= $row->nama_kelas ?></span>

                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">
                            </span>
                    <span class="info-box-text"><?= $row->nama_dosen ?></span>
                </div>
            </div>
        </div>
    </a>
<?php endforeach; ?>
<?php else: ?>
    <div class="callout callout-info">
        <h4><i class="fa fa-info-circle"></i> Info</h4>

        <p>Data kelas tidak ditemukan.</p>
    </div>
<?php endif; ?>