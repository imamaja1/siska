<div class="box box-solid box-warning">
    <div class="box-header with-border">Ketua Program Studi</div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table data-table">
                <thead>
                <tr>
                    <th rowspan="2">Kode</th>
                    <th rowspan="2">Prodi</th>
                    <th rowspan="2">Nama MK</th>
                    <th rowspan="2">Dosen</th>
                    <th rowspan="2">Input Nilai</th>
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
                if (count($kelas) > 0) :
                    foreach ($kelas as $row) : ?>
                        <tr>
                            <td><?= e($row->kelas_id); ?></td>
                            <td><?= e($row->singkatan_program_studi); ?></td>
                            <td><?= e($row->kode_matakuliah); ?> - <?= e($row->nama_matakuliah); ?> - Kelas
                                : <?= e($row->nama_kelas); ?></td>
                            <td><?= e($row->nama_dosen); ?></td>
                            <td><?= nilai_validasi($row->status_nilai) ?></td>
                            <td><?= nilai_validasi($row->validasi_nilai); ?></td>
                            <td><?= nilai_validasi($row->validasi_dekan); ?></td>
                            <td>
                                <div class="btn-group-xs">
                                    <?php if ($row->status_nilai == 'T') : ?>
                                        <a href="#" onclick="lihat('<?= e($row->kelas_id) ?>')"
                                           class="btn btn-xs btn-info btn-flat" title="Detail Nilai"><i
                                                    class="fa fa-eye"></i></a>
                                    <?php endif; ?>
                                    <?php if (cek_komentar_revisi($row->kelas_id)) : ?>
                                        <a href="#" onclick="show_history_comment('<?= e($row->kelas_id) ?>')"
                                           class="btn btn-xs bg-purple btn-flat pull-right"><i class="fa fa-comments"></i></a>
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
    $('.data-table').DataTable({
        columnDefs: [
            {targets: 'no-sort', orderable: false}
        ],
        order: [[4, 'desc'], [5, 'asc']],
    });
</script>