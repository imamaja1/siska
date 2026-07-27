<?php if (count($data) > 0) : ?>

    <div class="callout callout-success">

        <p>Penginputan nilai UTS mahasiswa dapat dilakukan mulai tanggal 
            <span style="font-weight:bold;"><?= tgl_indo($time->tgl_awal_uts); ?> <?= date('h:i:s A', strtotime($time->tgl_awal_uts)) ?></span> 
            sampai tanggal
            <span style="font-weight:bold;"><?= tgl_indo($time->tgl_akhir_uts); ?> <?= date('h:i:s A', strtotime($time->tgl_akhir_uts)) ?></span>, jika lewat dari tanggal tersebut maka tempat input nilai UTS akan tertutup secara otomatis.
        </p> 


    </div>
    <table class="table table-bordered demo-table">
        <thead>
            <tr>
                <th rowspan="2" style="white-space: nowrap;width: 1px; text-align: center">Kode</th>
                <th rowspan="2">Matakuliah</th>
                <th rowspan="2" style="text-align: center;">SMT</th>
                <th rowspan="2" style="white-space: nowrap;width: 1px;">Dosen</th>
                <th colspan="2"><center>Validasi</center></th>
                <th rowspan="2" style="text-align: center;">Tindakan</th>
                <th rowspan="2" style="text-align: center;">Pesan</th>
            </tr>
            <tr>
                <th style="white-space: nowrap;width: 1px;">Prodi</th>
                <th style="white-space: nowrap;width: 1px;">Dekan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($data as $key => $row) :
                ?>
                <tr>
                    <td align="center"><?= e($row->kelas_id) ?></td>
                    <td><?= e($row->kode_matakuliah) ?> - <?= e($row->nama_matakuliah) ?> Kelas - <?= e($row->nama_kelas) ?></td>
                    <td align="center"><?= e($row->semester) ?></td>
                    <td><?= nilai_validasi($row->status_nilai_uts) ?></td>
                    <td><?= nilai_validasi($row->validasi_nilai_uts) ?></td>
                    <td><?= nilai_validasi($row->validasi_dekan_uts) ?></td>
                    <td style="white-space: nowrap;width: 1px;">
                        <a class="btn btn-<?= e($row->status_nilai_uts != 'T' ? 'warning' : 'info') ?> btn-xs btn-flat" href="<?= site_url('dosen/penilaian/nilai_mahasiswa_uts/' . $row->kelas_id) ?>"> <i class="fa fa-arrow-circle-right"></i>
                            <?= e($row->status_nilai_uts == 'R' ? 'Update Nilai' : ($row->status_nilai_uts == 'T' ? 'Lihat Nilai' : 'Isi Nilai')) ?>
                        </a>
                    </td>
                    <td style="white-space: nowrap;width: 1px;">
                        <button id="pesan_prodi_<?= e($row->kelas_id) ?>" onclick="pesan_uts_prodi('<?= e($row->kelas_id) ?>')" class="btn btn-xs
                            btn-primary btn-flat badge-notif" 
                            <?php
                            if ($pesan_prodi[$key] != 0) {
                                echo 'data-badge="' . e($pesan_prodi[$key]) . '"';
                            }
                            ?> 
                            ><i class="fa fa-envelope"></i> Kaprodi</button>
                            <button id="pesan_dekan_<?= e($row->kelas_id) ?>" onclick="pesan_uts_dekan('<?= e($row->kelas_id) ?>')" class="btn btn-xs btn-flat btn-primary badge-notif" 
                                <?php
                                if ($pesan_dekan[$key] != 0) {
                                    echo 'data-badge="' . e($pesan_dekan[$key]) . '"';
                                }
                                ?> 
                                ><i class="fa fa-envelope"></i> Dekan</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php else: ?>
            <div class="col-md-12 col-xs-12">
                <div class="callout callout-warning">
                    <h4><i class="fa fa-warning"></i> Peringatan</h4>
                    <p>Tidak ada hasil kuisioner untuk tahun akademik tersebut</p>
                </div>
            </div>
            <?php endif; ?>