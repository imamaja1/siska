<div class="box box-solid box-warning">
    <div class="box-header with-border">Ketua Program Studi</div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table data-table">
                <thead>
                <tr>
                    <th rowspan="2">Kode</th>
                        <th rowspan="2">Prodi</th>
                        <th rowspan="2">Nama Matakuliah</th>
                        <th rowspan="2">KLS/SMT</th>
                        <th rowspan="2">Dosen Pengampu</th>
                        <th rowspan="2">Input</th>
                        <th colspan="2"><center>Validasi Nilai UTS</center></th>
                        <th rowspan="2">Tindakan</th>
                </tr>
                <tr>
                    <th>Prodi</th>
                    <th>Dekan</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (count($kelas) > 0) :
                    foreach ($kelas as $row) : ?>
                        <tr>
                            <td><?= e($row->kelas_id); ?></td>
                            <td><?= e($row->singkatan_program_studi); ?></td>
                            <td><?= e($row->kode_matakuliah); ?> - <?= e($row->nama_matakuliah); ?> - Kelas
                                : <?= e($row->nama_kelas); ?></td>
                            <td><?= e($row->nama_kelas); ?>/<?= e($row->semester) ?></td>
                            <td><?= e($row->nama_dosen); ?></td>
                            <td><?= nilai_validasi($row->status_nilai) ?></td>
                            <td><?= nilai_validasi($row->validasi_nilai); ?></td>
                            <td><?= nilai_validasi($row->validasi_dekan); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="#" onclick="lihat_mhs('<?= e($row->kelas_id) ?>')" title="Lihat Mahasiswa" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></a>
                                    <?php if ($row->status_nilai == 'T') : ?>
                                        <a href="#" onclick="lihat('<?= e($row->kelas_id) ?>')" class="btn btn-xs btn-warning btn-flat" title="Validasi Nilai"><i class="fa fa-check-circle"></i></a>
                                    <?php endif; ?>
                                    <a href="#" id="pesan_<?= e($row->kelas_id) ?>"  onclick="pesan_uas('<?= e($row->kelas_id) ?>')" class="btn btn-xs btn-primary btn-flat badge-notif" 
                                    <?php if ($pesan_dosen[$row->kelas_id] != 0) {
                                        echo 'data-badge="' . $pesan_dosen[$row->kelas_id] . '"';
                                    } ?>
                                        title="Pesan Untuk Dosen"><i class="fa fa-envelope"></i></a>
                                    <?php if (cek_komentar_revisi($row->kelas_id)) : ?>
                                                <!-- <a href="#" onclick="show_history_comment('<?= e($row->kelas_id) ?>')" class="btn btn-xs bg-purple btn-flat pull-right"><i class="fa fa-comments"></i></a> -->
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Kelas Belum dibagi di bagian akademik</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.data-table').DataTable({
            ordering : false,
        });
    });
</script>