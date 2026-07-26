<div class="box box-warning">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-list"></i> KELAS - <?= e($data_kelas->nama_kelas) ?>
            / <?= e($data_kelas->kode_matakuliah . " - " . $data_kelas->nama_matakuliah) ?></h3>
    </div>
    <div class="box-body">
        <?php if (count($data) > 0) : ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped demo-table">
                    <thead>
                        <tr>
                            <th style="text-align: center">NO.</th>
                            <th style="text-align: center">NIM</th>
                            <th style="text-align: center">NAMA</th>
                            <!--<th style="text-align: center">UTS</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($data as $row) :
                            ?>
                            <tr>
                                <td><?= $no++ ?>.</td>
                                <td><?= e($row->nim) ?></td>
                                <td><?= e($row->nama_mahasiswa) ?></td>
                                <!--<td><?= e($row->dummy_uts) ?></td>-->

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
          
        <?php endif; ?>
    </div>
</div>