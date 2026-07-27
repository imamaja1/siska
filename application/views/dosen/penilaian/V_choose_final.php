<?php if (count($data) > 0): ?>
<div class="box box-solid box-warning">
    <div class="box-header with-border">Daftar Kelas — Perbandingan Nilai Dummy vs KHS</div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Matakuliah</th>
                        <th>Kls</th>
                        <th><center>SMT</center></th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?= e($row->kode_matakuliah) ?></td>
                            <td><?= e($row->nama_matakuliah) ?></td>
                            <td><?= e($row->nama_kelas) ?></td>
                            <td align="center"><?= e($row->semester) ?></td>
                            <td>
                                <a href="<?= site_url('dosen/penilaian/' . $row->final_route . '/' . $row->kelas_id) ?>"
                                    class="btn btn-info btn-xs btn-flat">
                                    <i class="fa fa-exchange"></i> Perbandingan Nilai
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php else: ?>
<div class="callout callout-warning">
    <h4><i class="fa fa-warning"></i> Peringatan</h4>
    <p>Tidak ada kelas untuk periode ini</p>
</div>
<?php endif; ?>
