<?php if (!empty($hasil)) : ?>
    <div class="box box-primary">
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Matakuliah</th>
                        <th>Prasyarat</th>
                        <th>Jenis</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($hasil as $row) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= e($row['nama_matakuliah_yg_diambil']) ?></td>
                            <td><?= e($row['nama_matakuliah_prasyarat']) ?></td>
                            <td><?= e($row['jenis_prasyarat']) ?></td>
                            <td>
                                <button class="btn btn-xs btn-info flat" onclick="editPrasyarat('<?= e($row['kode_matakuliah_prasyarat']) ?>','<?= e($kode_nama_kurikulum) ?>')"><i class="fa fa-edit"></i> Edit</button>
                                <button class="btn btn-xs btn-danger flat" onclick="hapus('<?= e($row['kode_matakuliah_prasyarat']) ?>')"><i class="fa fa-trash"></i> Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else : ?>
    <div class="box box-primary">
        <div class="box-body">
            <div class="alert alert-info">
                <h4><i class="icon fa fa-info"></i> Info!</h4>
                Belum ada data matakuliah prasyarat untuk kurikulum ini.
            </div>
        </div>
    </div>
<?php endif; ?>

