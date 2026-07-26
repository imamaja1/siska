<nav>
  <div class="" id="nav-tab" role="tablist" style="text-decoration: none;" >
    <?php foreach ($kelas as $key => $value):?>
        <button class="nav-link btn btn-primary" id="nav-home_<?= $value->level ?>-tab" data-toggle="tab" data-target="#nav-home-<?= $value->level ?>" type="button" role="tab" aria-controls="nav-home" aria-selected="<?= ($key == 0) ? 'true':'false'; ?>">NIlai Ke - <?= $value->level ?> </button>
    <?php endforeach; ?>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <?php foreach ($kelas as $no => $value):?>
        <div class="tab-pane fade" id="nav-home-<?= $value->level ?>" role="tabpanel" aria-labelledby="nav-home-tab">
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
                        <th>Keterangan</th>
                    </tr>
                </thead>                                                                                                      
                <tbody>
                    <?php foreach ($value->isi_nilai as $nomor => $row) : ?>
                    <tr>
                        <td align="center"><?= $nomor+1; ?></td>
                        <td ><?= $row->nim; ?></td>
                        <td ><?= $row->nama_mahasiswa; ?></td>
                        <td align="center"><?= $row->harian ?></td>
                        <td align="center"><?= $row->uts; ?></td>
                        <td align="center"><?= $row->uas; ?></td>
                        <td align="center"><?= $row->grade; ?></td>
                        <td align="center"><?= $row->mbkm_id ? 'MBKM' : ($row->block_id ? 'Block' : '-'); ?></td>
                        <td align="center"><?= $row->ket; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
</div>