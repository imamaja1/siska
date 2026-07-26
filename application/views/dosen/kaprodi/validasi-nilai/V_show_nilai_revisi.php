<div role="tabpanel " >
  <!-- List group -->
  <div class="list-group col-md-2" id="myList" role="tablist ">
    <?php foreach ($all as $key => $value) : ?>
        <?php if ($key == '0'):?>
            <button class="list-group-item list-group-item-action active" id='target<?= e($value->level) ?>' data-toggle="list">Nilai ke-<?= e($value->level) ?></button>
        <?php else:?>
            <button class="list-group-item list-group-item-action" id='target<?= e($value->level) ?>' data-toggle="list">Nilai ke-<?= e($value->level) ?></button>
        <?php endif;  ?>
            
    <?php endforeach; ?>
  </div>

    <!-- Tab panes -->
    <div class="tab-content col-md-10">
        <?php foreach ($all as $num => $value) : ?>
                <table class="table table-bordered demo-table">
                <thead>
                    <tr>
                        <th style="white-space: nowrap;width: 1px; text-align: center">No</th>
                        <th>Nim</th>
                        <th>Nama Mahasiswa</th>
                        <th>Harian</th>
                        <th>UTS</th>
                        <th>UAS</th>
                        <th>Grade</th>
                        <th>Status</th>
                    </tr>
                </thead>                                                                                                      
                <tbody>
                    <?php foreach ($value->nilai_mhs as $key => $row) : ?>
                    <tr>
                        <td align="center"><?= e($key+1) ?></td>
                        <td align="center"><?= e($row->nim); ?></td>
                        <td align="center"><?= e($row->nama_mahasiswa); ?></td>
                        <td align="center"><?= e($row->Harian); ?></td>
                        <td align="center"><?= e($row->uts); ?></td>
                        <td align="center"><?= e($row->uas); ?></td>
                        <td align="center"><?= e($row->grade); ?></td>
                        <td align="center"><?= e($row->mbkm_id ? 'MBKM' : $row->block_id ? 'Block' : '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>
</div>