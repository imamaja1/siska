<table class="table table-bordered demo-table">
    <thead>
        <tr>
            <th style="white-space: nowrap;width: 1px; text-align: center">No</th>
            <th>Nim</th>
            <th>Nama Mahasiswa</th>
            <?php if ($nilai): ?>
            <th>Harian</th>
            <th>UTS</th>
            <th>UAS</th>
            <th>Grade</th>
            <th>Status</th>
            <th>Keterangan</th>
            <?php endif; ?>
        </tr>
    </thead>                                                                                                      
    <tbody>
        <?php foreach ($data as $key => $row) : ?>
        <tr>
            <td align="center"><?= $key+1; ?></td>
            <td ><?= $row->nim; ?></td>
            <td ><?= $row->nama_mahasiswa; ?></td>
            <?php if ($nilai): ?>
                <td align="center"><?= $row->harian ?></td>
                <td align="center"><?= $row->uts; ?></td>
                <td align="center"><?= $row->uas; ?></td>
                <td align="center"><?= $row->grade; ?></td>
                <td align="center"><?= $row->mbkm_id ? 'MBKM' : ($row->block_id ? 'Block' : '-'); ?></td>
                <td align="center"><?= $row->ket; ?></td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php if ($nilai): ?>
<?php if ($kelas->status_dosen == 'T' && $kelas->status_prodi != 'T' && $kelas->status_dekan != 'T'):?>
 <div class="row" style="margin-top: 15px">
     <div class="col-md-12 col-xs-12">
        <textarea class="form-control " name="pesantext" id="pesantext" rows="5"></textarea><br>
        <div class = "pt-3 mt-3">
            <button class="btn btn-success pull-right" onclick="selesai(<?= $kelas->id_kelas ?>,<?= $kelas->level ?>)" ><i class="fa fa-check-square-o"></i>Validasi Nilai</button>
            <button class="btn btn-danger pull-left" onclick="revisi(<?= $kelas->id_kelas ?>,<?= $kelas->level ?>)" ><i class="fa fa-check-square-o"></i>Revisi Nilai</button>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>