<?php if (count($data) > 0) : ?>
    <table class="table table-bordered demo-table">
        <thead>
            <tr>

                <th rowspan="2" style="text-align: center;">Kode</th>
                <th rowspan="2">Matakuliah</th>
                <th rowspan="2">Pengisian</th>
                <th colspan="2">Validasi</th>
                <th rowspan="2">Aksi</th>
            </tr>
            <tr>
                <th>Prodi</th>
                <th>Dekan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($data as $row) :
                ?>
                <tr>

                    <td align="center"><?= e($row->kelas_id) ?></td>
                    <td><?= e($row->kode_matakuliah) ?> - <?= e($row->nama_matakuliah) ?> Kelas
                        - <?= e($row->nama_kelas) ?></td>
                    <td><?= nilai_validasi($row->status_nilai) ?></td>
                    <td><?= nilai_validasi($row->validasi_nilai) ?></td>
                    <td><?= nilai_validasi($row->validasi_dekan) ?></td>
                    <td>
                        <a class="btn btn-<?= e($row->status_nilai != 'T' ? 'warning' : 'info') ?> btn-xs btn-flat"
                           href="<?= site_url('dosen/penilaian/nilai_mahasiswa/' . $row->kelas_id) ?>">
                            <i class="fa fa-arrow-circle-right"></i>
                            <?= e($row->status_nilai == 'R' ? 'Update Nilai' : ($row->status_nilai == 'T' ? 'Lihat Nilai' : 'Isi Nilai')) ?>
                        </a>
                        <?php if (cek_komentar_revisi($row->kelas_id)) : ?>
                            <a href="#" onclick="show_history_comment('<?= e($row->kelas_id) ?>')"
                               title="Lihat Catatan Prodi / Dekan" class="btn pull-right bg-purple btn-xs btn-flat"><i
                                    class="fa fa-comment-o"></i> Komentar</a>
                            <?php endif; ?>

                        <?php
                        if (($row->validasi_nilai == "T") && ($row->validasi_dekan == "T")) {
                            ?>
                            <a class="btn btn-primary btn-xs btn-flat"
                               href="<?= site_url('dosen/cetak_nilai/index/' . $row->kelas_id) ?>">
                                <i class="fa fa-print"></i>
                                Cetak
                            </a>

                            <?php
                        }
                        ?>

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