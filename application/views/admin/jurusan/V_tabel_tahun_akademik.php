<table class="table data-table">
    <thead>
        <tr>
            <th id="th">No</th>
            <th id="th">Tahun Akademik</th>
            <th id="th">Semester</th>
            <th id="th">Tanggal mulai</th>
            <th id="th">Tanggal Berakhir</th>
            <th id="th">Awal KRS</th>
            <th id="th">Batas KRS</th>
            <th id="th">Status TA</th>
            <th id="th">Status KRS</th>
            <th id="th">Tindakan</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        $today = date('Y-m-d');
        foreach ($data as $d) {
            $tgl_awal_krs = (!empty($d->tgl_awal_krs) && $d->tgl_awal_krs !== '0000-00-00') ? $d->tgl_awal_krs : null;
            $tgl_batas_krs = (!empty($d->tgl_batas_krs) && $d->tgl_batas_krs !== '0000-00-00') ? $d->tgl_batas_krs : null;
            $status_krs = isset($d->status_krs) ? $d->status_krs : 'N';
            ?>
            <tr>
                <td align="center"><?= $i++ ?></td>
                <td align="center" id="tahun-akademik-<?= e($d->kode_tahun_akademik) ?>"><?= e($d->tahun_akademik) ?></td>
                <td align="center" ><?= $d->semester == 1 ? '<span class="label label-info">Ganjil</span>' : '<span class="label label-success">Genap</span>' ?></td>
                <td align="center" id="semester-<?= e($d->kode_tahun_akademik) ?>" style="display: none;"><?= e($d->semester) ?></td>
                <td align="center" id="tanggal-mulai-<?= e($d->kode_tahun_akademik) ?>"><?= e($d->tanggal_mulai) ?></td>
                <td align="center" id="tanggal-berakhir-<?= e($d->kode_tahun_akademik) ?>"><?= e($d->tanggal_berakhir) ?></td>
                <td align="center">
                    <?= $tgl_awal_krs !== null ? e($tgl_awal_krs) : '<span class="text-muted">-</span>' ?>
                </td>
                <td align="center">
                    <?php if ($tgl_batas_krs !== null) { ?>
                        <?= e($tgl_batas_krs) ?>
                        <?php if ($status_krs === 'A' && function_exists('day_left')) { ?>
                            <br><?= day_left($tgl_batas_krs) ?>
                        <?php } ?>
                    <?php } else { ?>
                        <span class="text-muted">-</span>
                    <?php } ?>
                </td>
                <td align="center" width="90">
                    <?php if ($d->status == "N") { ?>
                        <a href="#" id="aktifkan-<?= e($d->kode_tahun_akademik) ?>" onclick="aktif('<?= e($d->kode_tahun_akademik) ?>')" class="btn btn-xs btn-danger flat sak"><i class="fa fa-times-circle"></i> Nonaktif</a>&nbsp;
                    <?php } else { ?>
                        <a href="#" id="aktifkan-<?= e($d->kode_tahun_akademik) ?>" onclick="nonaktif('<?= e($d->kode_tahun_akademik) ?>')" class="btn btn-xs btn-success flat sak"><i class="fa fa-check-circle"></i> Aktif</a>&nbsp;
                    <?php } ?>
                    <p hidden id="status-<?= e($d->kode_tahun_akademik) ?>"><?= e($d->status) ?></p>
                </td>
                <td align="center" width="110">
                    <?php if ($status_krs === 'A') { ?>
                        <a href="#" onclick="nonaktifKrs('<?= e($d->kode_tahun_akademik) ?>'); return false;" class="btn btn-xs btn-success flat"><i class="fa fa-check-circle"></i> Aktif</a>
                        <?php if ($tgl_awal_krs !== null && $today < $tgl_awal_krs) { ?>
                            <br><small class="text-warning">(Belum Mulai)</small>
                        <?php } elseif ($tgl_batas_krs !== null && $today > $tgl_batas_krs) { ?>
                            <br><small class="text-danger">(Lewat Batas)</small>
                        <?php } ?>
                    <?php } else { ?>
                        <a href="#" onclick="aktifKrs('<?= e($d->kode_tahun_akademik) ?>'); return false;" class="btn btn-xs btn-danger flat"><i class="fa fa-times-circle"></i> Nonaktif</a>
                    <?php } ?>
                </td>
                <td width="130" align="center">
                    <a href="#" class="btn btn-xs btn-info flat" onclick="javascript:editTahunakademik('<?= e($d->kode_tahun_akademik) ?>')"><i class="fa fa-edit"></i> Edit</a>&nbsp;
                    <a href="#" class="btn btn-xs btn-danger flat" onclick="hapus('<?= e($d->kode_tahun_akademik) ?>')"><i class="fa fa-trash"></i> Hapus</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
