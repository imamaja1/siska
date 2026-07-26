<?php if (count($data) > 0): ?>
    <div class="box box-primary flat">
        <div class="box-body">
            <table class="table demo-table">
                <thead>
                    <tr>
                        <th style="text-align: center;">NO.</th>
                        <th style="text-align: center;">NIM</th>
                        <th style="text-align: center;">NAMA MAHASISWA</th>
                        <th style="text-align: center;">AKSI</th>
                    </tr>
                </thead>

                <?php
                $no = 1;
                foreach ($data as $row) {
                    ?>
                    <tr>
                        <td align="center"><?= $no++ ?>.</td>
                        <td align="center"><?= e($row->nim) ?></td>
                        <td ><?= e($row->nama_mahasiswa) ?></td>
                        <td width="200" align="center">
                            <a href="<?= site_url('admin/akademik/krs/cetak_cepat/' . $row->nim); ?>"  class="btn btn-xs btn-info flat"><i class="fa fa-download"></i> Download</a>
                            <a href="<?= site_url('admin/akademik/krs/print_view/' . $row->nim); ?>" target="_blank"  class="btn btn-xs btn-danger flat"><i class="fa fa-print"></i> Cetak</a>
                        </td>
                    </tr>

                <?php } ?>

            </table>
        </div>
    </div>
<?php else: ?>
    <div class="callout callout-warning flat">
        <h5>Data Mahasiswa tidak ditemukan !</h5>
    </div>

<?php endif; ?>
