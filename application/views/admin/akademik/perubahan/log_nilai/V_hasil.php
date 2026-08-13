<div class="box box-solid flat">
    <div class="box-header with-border">
        <h3 class="box-title"><strong>Identitas Mahasiswa</strong></h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <strong>NIM</strong><br>
                <span><?= $mahasiswa && $mahasiswa->nim ? e($mahasiswa->nim) : e($nim) ?></span>
            </div>
            <div class="col-sm-4">
                <strong>Nama Mahasiswa</strong><br>
                <span><?= $mahasiswa && $mahasiswa->nama_mahasiswa ? e($mahasiswa->nama_mahasiswa) : '-' ?></span>
            </div>
            <div class="col-sm-4">
                <strong>Program Studi</strong><br>
                <span><?= $mahasiswa && $mahasiswa->nama_program_studi ? e($mahasiswa->nama_program_studi) : '-' ?></span>
            </div>
        </div>
    </div>
</div>

<div class="box box-success flat">
    <div class="box-header with-border">
        <h3 class="box-title"><strong>Riwayat Perubahan Nilai</strong></h3>
    </div>
    <div class="box-body table-responsive">
        <table class="table table-bordered table-striped data-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Matakuliah</th>
                    <th>Tahun Akademik</th>
                    <th>Aksi</th>
                    <th>Nilai (Lama &rarr; Baru)</th>
                    <th>Sumber</th>
                    <th>Oleh</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data)) : ?>
                    <?php foreach ($data as $row) : ?>
                        <?php
                        $lama_json = json_decode($row->nilai_lama, true);
                        $baru_json = json_decode($row->nilai_baru, true);
                        ?>
                        <tr>
                            <td><?= e($row->created_at) ?></td>
                            <td><?= e($row->kode_matakuliah) ?> - <?= e($row->nama_matakuliah) ?></td>
                            <td><?= e($row->kode_tahun_akademik) ?></td>
                            <td>
                                <?php if ($row->aksi == 'update') : ?>
                                    <span class="label label-info">Update</span>
                                <?php elseif ($row->aksi == 'delete') : ?>
                                    <span class="label label-danger">Delete</span>
                                <?php elseif ($row->aksi == 'soft_delete') : ?>
                                    <span class="label label-warning">Soft Delete</span>
                                <?php elseif ($row->aksi == 'restore') : ?>
                                    <span class="label label-success">Restore</span>
                                <?php else : ?>
                                    <span class="label label-default"><?= e($row->aksi) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (is_array($lama_json) && is_array($baru_json)) : ?>
                                    <?php $multi = count($lama_json) > 1; ?>
                                    <?php foreach ($lama_json as $field => $v) : ?>
                                        <?php $b = array_key_exists($field, $baru_json) ? $baru_json[$field] : null; ?>
                                        <?= $multi ? e($field) . ': ' : '' ?><?= $v === null ? '-' : e($v) ?> &rarr; <?= $b === null ? '-' : e($b) ?><br>
                                    <?php endforeach; ?>
                                <?php elseif (is_array($lama_json)) : ?>
                                    <?php $multi = count($lama_json) > 1; ?>
                                    <?php foreach ($lama_json as $field => $v) : ?>
                                        <?= $multi ? e($field) . ': ' : '' ?><?= $v === null ? '-' : e($v) ?> &rarr; -<br>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <?= $row->nilai_lama === null ? '-' : e($row->nilai_lama) ?> &rarr; <?= $row->nilai_baru === null ? '-' : e($row->nilai_baru) ?>
                                <?php endif; ?>
                            </td>
                            <td><?= e($row->sumber) ?></td>
                            <td><?= e($row->nama_login) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada riwayat perubahan nilai untuk NIM ini</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    $('#hasil-log table.data-table').DataTable({
        order: [[0, 'desc']],
        pageLength: 25
    });
</script>