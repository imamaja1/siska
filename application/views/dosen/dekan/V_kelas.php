<div class="box box-solid box-warning">
    <div class="box-header with-border">Dekan</div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table data-table ">
                <thead>
                <tr>
                    <th rowspan="2">Kode</th>
                    <th rowspan="2">Prodi</th>
                    <th rowspan="2">Nama MK</th>
                    <th rowspan="2">Dosen</th>
                    <th rowspan="2">Nilai</th>
                    <th colspan="2">Validasi</th>
                    <th rowspan="2" class="no-sort">Aksi</th>
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
                            <td><?= $row->kelas_id; ?></td>
                            <td><?= $row->singkatan_program_studi; ?></td>
                            <td><?= $row->kode_matakuliah; ?> - <?= $row->nama_matakuliah; ?> - Kelas
                                : <?= $row->nama_kelas; ?></td>
                            <td><?= $row->nama_dosen; ?></td>
                            <td><?= nilai_validasi($row->status_nilai) ?></td>
                            <td><?= nilai_validasi($row->validasi_nilai); ?></td>
                            <td><?= nilai_validasi($row->validasi_dekan); ?></td>
                            <td>
                                <?php if ($row->status_nilai == 'T') : ?>
                                    <a href="#" onclick="lihat('<?= $row->kelas_id ?>')"
                                       class="btn btn-xs btn-info btn-flat" title="Detail Nilai"><i
                                                class="fa fa-eye"></i></a>
                                <?php endif; ?>
                                <?php if (cek_komentar_revisi($row->kelas_id)) : ?>
                                    <a href="#" onclick="show_history_comment('<?= $row->kelas_id ?>')"
                                       class="btn btn-xs bg-purple btn-flat pull-right"><i class="fa fa-comments"></i></a>
                                <?php endif; ?>
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