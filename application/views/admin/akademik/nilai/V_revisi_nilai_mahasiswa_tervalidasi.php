<div class="row">
<nav class="col-md-3 pl-3">
  <div class="" id="nav-tab mt-5" role="tablist" style="text-decoration: none;" >
    <?php foreach ($kelas as $key => $value):?>
        <button class="form-control btn btn-primary" width="100%" style="margin-top : 3px;" id="nav-home_<?= e($value->level) ?>-tab" data-toggle="tab" data-target="#nav-home-<?= e($value->level) ?>" type="button" role="tab" aria-controls="nav-home" aria-selected="<?= ($key == 0) ? 'true':'false'; ?>">NIlai Ke - <?= e($value->level) ?> </button>
    <?php endforeach; ?>
  </div>
</nav>
<div class="tab-content col-md-9" id="nav-tabContent">
    <?php foreach ($kelas as $no => $value):?>
        <div class="tab-pane fade <?php if ($no==0):?> <?php endif; ?>" id="nav-home-<?= e($value->level) ?>" role="tabpanel" aria-labelledby="nav-home-tab">
            <span style="font-size:20px;"><b>Data Nilai KE - <?= e($value->level) ?></b><span>
            <a href="<?= site_url('admin/akademik/validasi_khusus/cetak_nilai_revisi_kelas/'.$value->id_kelas.'/'.$value->level.'/'.$ta); ?>" class="btn btn-success pull-right" >
            <i class="fa fa-download" aria-hidden="true"></i> Unduh
            </a>
            <table class="table table-bordered demo-table">
                <thead>
                    <tr>
                        <th style="white-space: nowrap;width: 1px; text-align: center">No</th>
                        <th>Nim</th>
                        <th>Nama Mahasiswa</th>
                        <th>Harian</th>
                        <th>UTS</th>
                        <th>UAS</th>
                        <th>Nilai Akhir</th>
                        <th>Grade</th>
                        <th>Status</th>
                    </tr>
                </thead>                                                                                                      
                <tbody>
                    <?php foreach ($value->isi_nilai as $nomor => $row) : ?>
                    <tr>
                        <td align="center"><?= $nomor+1; ?></td>
                        <td ><?= e($row->nim); ?></td>
                        <td ><?= e($row->nama_mahasiswa); ?></td>
                        <td align="center"><?= e($row->harian) ?></td>
                        <td align="center"><?= e($row->uts); ?></td>
                        <td align="center"><?= e($row->uas); ?></td>
                      	<td align="center"><?= e($row->na); ?></td>
                        <td align="center"><?= e($row->grade); ?></td>
                        <td align="center"><?= $row->mbkm_id ? 'MBKM' : ($row->block_id ? 'Block' : '-'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
</div>
</div>