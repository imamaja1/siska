<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="40" class="text-center">No</th>
                <th width="120">NIM</th>
                <th>Nama Mahasiswa</th>
                <th width="60" class="text-center">Level</th>
                <th width="80" class="text-center">Harian</th>
                <th width="80" class="text-center">UTS</th>
                <th width="80" class="text-center">UAS</th>
                <th width="90" class="text-center">Nilai Akhir</th>
                <th width="60" class="text-center">Grade</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($nilai)) : ?>
            <tr>
                <td colspan="9" class="text-center">Tidak ada data nilai.</td>
            </tr>
            <?php else : ?>
            <?php $no = 1; foreach ($nilai as $row) : ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= e($row->nim) ?></td>
                <td><?= e($row->nama_mahasiswa) ?></td>
                <td class="text-center"><?= e($row->level) ?></td>
                <td class="text-center"><?= e($row->harian !== NULL ? $row->harian : '-') ?></td>
                <td class="text-center"><?= e($row->uts !== NULL ? $row->uts : '-') ?></td>
                <td class="text-center"><?= e($row->uas !== NULL ? $row->uas : '-') ?></td>
                <td class="text-center"><?= e($row->na !== NULL ? $row->na : '-') ?></td>
                <td class="text-center"><?= e($row->grade !== NULL ? $row->grade : '-') ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
