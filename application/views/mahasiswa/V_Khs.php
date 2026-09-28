<div class="box box-solid flat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-calendar-check-o"></i> Pilih Semester</h3>
    </div>
    <div class="box-body">
        <div class="krs-pick">
            <?php foreach ($kode_krs as $row) : ?>
                <?php $isActive = isset($data['krs']) && $data['krs'] == $row->kode_krs; ?>
                <a href="<?= site_url('mahasiswa/khs/index/'.$row->semester) ?>" class="btn <?= $isActive ? 'btn-primary' : 'btn-default' ?> btn-sm"><i class="fa fa-calendar-check-o"></i> <?= ($row->semester == 'K') ? 'Konversi' : 'Semester '.$row->semester ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php if (isset($data['data_nilai'])) :?>
<div class="box box-primary flat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-bar-chart"></i> Kartu Hasil Studi</h3>
        <div class="box-tools">
            <a href="<?= site_url('mahasiswa/khs/cetak/'.$data['krs'].'/'.$data['nim']) ?>" class="btn btn-danger btn-sm"><i class="fa fa-download"></i> Unduh KHS</a>
        </div>
    </div>
    <div class="box-body">
        <p class="mhs-subtitle"><?= ($data['semester'] == 'K') ? 'KONVERSI' : 'SEMESTER '.($data['semester'] % 2 == (0) ? "GENAP" : "GANJIL") ; ?> TA. <?= e($data['tahun_akademik']) ?></p>
        <div class="row mhs-info">
            <div class="col-sm-6">
                <div class="mhs-info-card">
                    <div class="mhs-info-row"><span class="mhs-info-label">Nama Mahasiswa</span><span class="mhs-info-value"><?= e($data['nama_mahasiswa']) ?></span></div>
                    <div class="mhs-info-row"><span class="mhs-info-label">NIM</span><span class="mhs-info-value"><?= e($data['nim']) ?></span></div>
                    <div class="mhs-info-row"><span class="mhs-info-label">Semester</span><span class="mhs-info-value"><?= ($data['semester'] == 'K') ? 'Konversi' : $data['semester'] ?></span></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mhs-info-card">
                    <div class="mhs-info-row"><span class="mhs-info-label">Program Studi</span><span class="mhs-info-value"><?= e($prodi->nama_program_studi) ?></span></div>
                    <div class="mhs-info-row"><span class="mhs-info-label">Fakultas</span><span class="mhs-info-value"><?= e($prodi->nama_fakultas) ?></span></div>
                    <div class="mhs-info-row"><span class="mhs-info-label">Kurikulum</span><span class="mhs-info-value"><?= e($data['kurikulum']) ?></span></div>
                </div>
            </div>
        </div>
        <div class="table-responsive mhs-table-wrap">
            <table class="table mhs-table">
                <thead>
                <tr>
                    <th class="mhs-c-no">No.</th>
                    <th class="mhs-c-kode">Kode</th>
                    <th class="mhs-c-mk">Matakuliah</th>
                    <th class="mhs-c-num">SKS</th>
                    <th class="mhs-c-num">Grade</th>
                    <th class="mhs-c-num">SKSN</th>
                </tr>
                </thead>
                <tbody>
                <?php $i=1; $sksn=0; $sks=0; foreach ($data['data_nilai'] as $row) : ?>
                    <tr>
                        <td class="mhs-num"><?= $i++ ?>.</td>
                        <td class="mhs-kode"><?= e($row['kode_matakuliah']) ?></td>
                        <td><?= e($row['nama_matakuliah']) ?></td>
                        <td class="mhs-num"><?= $row['sks'] ?></td>
                        <td class="mhs-num"><?= e($row['grade']) ?></td>
                        <td class="mhs-num"><?= $row['sksn'] ?></td>
                    </tr>
                    <?php
                    $sksn = $sksn + $row['sksn'];
                    $sks = $sks + $row['sks'];
                    ?>
                <?php endforeach; ?>
                <tr class="mhs-total">
                    <td colspan="3"><strong>Jumlah</strong></td>
                    <td class="mhs-num"><strong><?= $sks ?></strong></td>
                    <td></td>
                    <td class="mhs-num"><strong><?= $sksn ?></strong></td>
                </tr>
                </tbody>
            </table>
        </div>
        <br>
        <?php
        $ipk = $sks > 0 ? $sksn/$sks : 0;

        if ($ipk >= 3.5)
        {
            $jumlah_maksimum_sks = 24;
        }
        elseif ($ipk >= 3.25)
        {
            $jumlah_maksimum_sks = 23;
        }
        elseif ($ipk >= 3)
        {
            $jumlah_maksimum_sks = 22;
        }
        elseif ($ipk >= 2.75)
        {
            $jumlah_maksimum_sks = 21;
        }
        elseif ($ipk >= 2.5)
        {
            $jumlah_maksimum_sks = 20;
        }
        elseif ($ipk >= 2.25)
        {
            $jumlah_maksimum_sks = 19;
        }
        elseif ($ipk >= 2)
        {
            $jumlah_maksimum_sks = 18;
        }
        elseif ($ipk >= 1.75)
        {
            $jumlah_maksimum_sks = 16;
        }
        elseif ($ipk >= 1.5)
        {
            $jumlah_maksimum_sks = 14;
        }
        else
        {
            $jumlah_maksimum_sks = 12;
        }
        ?>

        <div class="row mhs-bottom">
            <div class="col-md-6 col-sm-12">
                <div class="mhs-summary">
                    <div class="mhs-summary-row"><span>Jumlah SKS yang ditempuh</span><strong><?= $sks ?></strong></div>
                    <div class="mhs-summary-row"><span>IP Semester ini</span><strong><?= number_format($ipk, 2) ?></strong></div>
                    <?php if (substr($data['nim'], 4, 1) != 3) : ?>
                        <div class="mhs-summary-row"><span>Maksimum SKS Semester Depan</span><strong><?= $jumlah_maksimum_sks ?></strong></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- end.box-body -->
</div>
<?php else: ?>
    <div class="callout callout-danger">
        <h4>Peringatan!</h4>

        <p>Data <strong>KHS</strong> tidak ditemukan.</p>
    </div>
<?php endif; ?>
