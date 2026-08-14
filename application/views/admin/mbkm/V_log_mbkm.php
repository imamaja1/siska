<div class="box box-success flat">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-history"></i> Log Aktivitas MBKM</h4>
    </div>
    <div class="box-body table-responsive">
        <?php if (!empty($log)) : ?>
            <table id="tabel-log-mbkm" class="table table-bordered table-striped data-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Matakuliah</th>
                        <th>Tahun Akademik</th>
                        <th>Aksi</th>
                        <th>Nilai (Lama &rarr; Baru)</th>
                        <th>Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($log as $row) : ?>
                        <?php
                        $lama_json = json_decode($row->nilai_lama, true);
                        $baru_json = json_decode($row->nilai_baru, true);
                        ?>
                        <tr>
                            <td><?= e($row->created_at) ?></td>
                            <td><?= e($row->nim) ?></td>
                            <td><?= e($row->nama_mahasiswa) ?></td>
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
                            <td><?= e($row->nama_login) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <script>
                $('#tabel-log-mbkm').DataTable({
                    order: [[0, 'desc']],
                    pageLength: 25
                });
            </script>
        <?php else : ?>
            <p class="text-muted">Belum ada log aktivitas MBKM.</p>
        <?php endif; ?>
    </div>
</div>