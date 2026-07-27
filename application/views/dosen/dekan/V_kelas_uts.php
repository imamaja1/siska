<div class="box box-solid box-warning">
    <div class="box-header with-border">Dekan</div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table data-table ">
                <thead>
                <tr>
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
                                <td><?= e($row->kode_matakuliah); ?> - <?= e($row->nama_matakuliah); ?>
                                </td>
                                <td align="center"><?= e($row->nama_kelas); ?>/<?= e($row->semester) ?></td>
                                <td><?= e($row->nama_dosen); ?></td>
                                <td>
                                    <?php if($row->param_uts == '1'):?>
                                        <?= nilai_validasi_telat($row->status_nilai_uts) ?>
                                    <?php else:?>
                                        <?= nilai_validasi($row->status_nilai_uts) ?>
                                    <?php endif;?>
                                </td>
                                <td>
                                    <?php if($row->param_uts == '1'):?>
                                        <?= nilai_validasi_telat($row->validasi_nilai_uts); ?>
                                    <?php else:?>
                                        <?= nilai_validasi($row->validasi_nilai_uts); ?>
                                    <?php endif;?>
                                </td>
                                <td>
                                    <?php if($row->param_uts == '1'):?>
                                        <?= nilai_validasi_telat($row->validasi_dekan_uts); ?>
                                    <?php else:?>
                                        <?= nilai_validasi($row->validasi_dekan_uts); ?>
                                    <?php endif;?>
                                </td>
                            <td>
                                <div class="btn-group">
                                    <a href="#" onclick="lihat_mhs('<?= e($row->kelas_id) ?>')" title="Lihat Mahasiswa" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></a>
                                <?php if ($row->status_nilai_uts == 'T') : ?>
                                    <a href="#" onclick="lihat('<?= e($row->kelas_id) ?>')"
                                       class="btn btn-xs btn-warning btn-flat" title="Validasi Nilai"><i
                                                class="fa fa-check-circle"></i></a>
                                <?php endif; ?>
                                <a href="#" id="pesan_<?= e($row->kelas_id) ?>" onclick="pesan_uts('<?= e($row->kelas_id) ?>')" class="btn btn-xs btn-primary  btn-flat pull-right badge-notif" 
                                <?php if ($pesan_dosen[$row->kelas_id] != 0) { echo 'data-badge="'.e($pesan_dosen[$row->kelas_id]).'"'; } ?>
                                title="Pesan Ke Dosen"><i class="fa fa-envelope"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Kelas Belum dibagi di bagian akademik</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $('.data-table').DataTable({
        columnDefs: [{targets: 'no-sort', orderable: false}],
        order: [[4, 'desc'], [5, 'desc'], [6, 'asc']]
    });

</script>