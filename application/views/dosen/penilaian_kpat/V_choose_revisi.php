<?php if ($kode_tahun_akademik >= 26) : ?> <!-- batas untuk program penilaian -->
<?php if (count($data) > 0) : ?>
    <table class="table table-bordered demo-table">
        <thead>
            <tr>
                <th style="white-space: nowrap;width: 1px; text-align: center">Kode</th>
                <th>Prodi</th>
                <th>Matakuliah</th>
                <th><center>SMT<center></th>
                <th><center>Sataus Validasi</center></th>
                <th style="text-align: center;">Tindakan</th>
                <th style="text-align: center;">Pesan</th>
            </tr>
        </thead>                                                                                                      
        <tbody>
            <?php
            foreach ($data as $key1 => $row) :
                ?>
                <tr>
                <td align="center"><?= e($row->kelas_id) ?></td>
                <td><?= e($row->singkatan_program_studi) ?></td>
                <td><?= e($row->kode_matakuliah) ?> - <?= e($row->nama_matakuliah) ?> Kelas - <?= e($row->nama_kelas) ?></td>
                <td align="center"><?= e($row->semester) ?></td>
                <td>
                    <?php foreach ($row->validasi as $key2 => $val): ?>
                        <b>Pengajuan Nilai ke-<?= e($val->level) ?> : </b> 
                        <?php if ($val->status == 4):?>
                            <?php if ($val->status_dosen == 'R'):?>
                                <span class="label label-warning">Revisi</span>
                            <?php else: ?>   
                                <span class="label label-info">Pengisisan</span>
                            <?php endif; ?>
                        <?php elseif ($val->status == 3):?>
                            <span class="label label-success">NIlai Disetujui</span>
                        <?php elseif ($val->status == 1):?>
                            <span class="label label-primary">Proses Validasi Kaprodi</span>
                        <?php elseif ($val->status == 2):?>
                            <span class="label label-primary">Proses Validasi Dekan</span>
                        <?php endif; ?>
                        <br>
                    <?php endforeach; ?> 
                </td>
                <td style="white-space: nowrap;width: 1px;">
                    <a class="btn btn-<?= e($row->status_dosen != 'T' ? 'warning' : 'info') ?> btn-xs btn-flat" href="<?= site_url('dosen/penilaian_kpat/nilai_mahasiswa_uas_revisi/' . $row->kelas_id) ?>"> <i class="fa fa-arrow-circle-right"></i>
                        <?= e($row->status_dosen == 'R' ? 'Update Nilai' : ($row->status_nilai == 'T' ? 'Lihat Nilai' : 'Isi Nilai')) ?>
                    </a>
                    <?php 
                    if ($row->status_dekan == 'T') {
                        ?>
                        <a href="<?= site_url('dosen/penilaian_kpat/cetak_validasi_uas/' . $row->kelas_id) ?>" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-print"></i> Cetak</a>
                        <?php
                    } 
                    ?>
                </td>
                <td style="white-space: nowrap;width: 1px;">
                    <button id="pesan_prodi_<?= e($row->kelas_id) ?>" onclick="pesan_uas_prodi('<?= e($row->kelas_id) ?>')" class="btn btn-xs
                            btn-primary btn-flat badge-notif" 
                            <?php if ($pesan_prodi[$key1] != 0) {
                                    echo 'data-badge="' . e($pesan_prodi[$key1]) . '"';
                                }
                            ?> 
                            ><i class="fa fa-envelope"></i> Kaprodi</button>
                    <button id="pesan_dekan_<?= e($row->kelas_id) ?>" onclick="pesan_uas_dekan('<?= e($row->kelas_id) ?>')" class="btn btn-xs btn-flat btn-primary badge-notif" 
                    <?php
                    if ($pesan_dekan[$key1] != 0) {
                        echo 'data-badge="' . e($pesan_dekan[$key1]) . '"';
                    }
                    ?> 
                    ><i class="fa fa-envelope"></i> Dekan </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>
    <div class="col-md-12 col-xs-12">
        <div class="callout callout-warning">
            <h4><i class="fa fa-warning"></i> Peringatan</h4>
            <p>Tidak ada kelas untuk priode ini</p>
        </div>
    </div>
<?php endif; ?>
<?php else: ?>
    <div class="col-md-12 col-xs-12">
        <div class="callout callout-danger">
            <h4><i class="fa fa-warning"></i>Peringatan</h4>
            <p>Untuk Priode Tidak Dapat Dakses Karna Program Baru, Untuk Melihat Nilai Priode ini Silahkan Hubungi Bagian Akademik</p>
        </div>
    </div>
<?php endif; ?>
<!-- ================================================================ pesan ======================================= -->
<div class="modal fade" id="modal-pesan" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content" id="landing-pesan">

        </div>
    </div>
</div>

<script>
    function pesan_uas_dekan(kelas_id) {
        super_kelas_id = kelas_id;
        var url = "<?= site_url('dosen/penilaian_kpat/pesan_kpat') ?>/" + kelas_id +"/dosen/dekan";
        $.ajax({
            url: url,
            success: function (res) {
                $("#landing-pesan").html(res);
                $("#modal-pesan").modal('show');
                $("#pesan_dekan_" + kelas_id).addClass('btn-primary').removeClass('btn-danger').removeClass('badge-notif');
                console.log('ada');
            }
        })
    }
    function pesan_uas_prodi(kelas_id) {
        super_kelas_id = kelas_id;
        var url = "<?= site_url('dosen/penilaian_kpat/pesan_kpat') ?>/" + kelas_id +"/dosen/prodi";
        $.ajax({
            url: url,
            success: function (res) {
                $("#landing-pesan").html(res);
                $("#modal-pesan").modal('show');
                $("#pesan_prodi_" + kelas_id).addClass('btn-primary').removeClass('btn-danger').removeClass('badge-notif');
            }
        })
    }
</script>