<?php if (count($data) > 0) : ?>
<div class="box box-primary flat">
    <div class="table-responsive">
        <table class="table demo-table">
            <thead>
            <tr>
                <th id="th">NO</th>
                <th id="th">NAMA DOSEN</th>
                <th id="th">AKSI</th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1; foreach ($data as $row) : ?>
                <tr>
                    <td align="center"><?= $i++ ?>.</td>
                    <td align="center"><?= $row->nama_dosen ?></td>
                    <td align="center">
                        <a href="<?= site_url('admin/kuisioner/mengajar/hapus/'.$row->mengajar_id) ?>" class="btn btn-danger btn-sm flat"><i class="fa fa-trash"></i> Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php else: ?>
    <div class="callout callout-info">
        <h4><i class="fa fa-info-circle"></i> Informasi!</h4>

        <p>Belum ada Dosen/Pengajar untuk kelas tersebut.</p>
    </div>
<?php endif; ?>