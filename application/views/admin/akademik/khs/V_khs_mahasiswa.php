<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/khs') ?>" class="btn btn-success btn-sm flat"><i class="fa fa-arrow-left"></i> Kembali</a>
        <button class="btn btn-link btn-xs flat">terdapat <strong><?= isset($jumlah_data) ? e($jumlah_data) : " 0 " ?> Data</strong></button>
        <div class="pull-right">
            <?= isset($halaman) ? $halaman : "" ?>
        </div>
    </div>
</div>
<?php if (count($data) > 0) : ?>
    <div class="box box-primary flat">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table demo-table">
                    <thead>
                        <tr>
                            <th width="20" id="color">No.</th>
                            <th id="color"><center>NIM</center></th>
                    <th id="color"><center>Nama Mahasiswa</center></th>
                    <th id="color"><center>Aksi</center></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1 + $this->uri->segment(5);
                        foreach ($data as $row) : ?>
                            <tr>
                                <td><center><?= $i++ ?>.</center></td>
                        <td><center><?= e($row->nim) ?></center></td>
                        <td><?= e($row->nama_mahasiswa) ?></td>
                        <td style="text-align: center;">
                            <a href="<?= site_url('admin/akademik/khs/lihat_khs/' . $row->kode_krs . '/' . $row->nim) ?>" class="btn btn-info btn-xs flat"><i class="fa fa-eye"></i> Lihat</a>&nbsp;
                            <a href="<?= site_url('admin/akademik/khs/cetak/' . $row->kode_krs . '/' . $row->nim) ?>" class="btn btn-warning btn-xs flat"><i class="fa fa-download"></i> Download</a></center>
                            <a href="<?= site_url('admin/akademik/khs/print_view/' . $row->kode_krs . '/' . $row->nim) ?>" target="_blank" class="btn btn-danger btn-xs flat"><i class="fa fa-print"></i> Cetak</a></center>
              				<a href="<?= site_url('admin/akademik/khs/print_excel/' . $row->kode_krs . '/' . $row->nim) ?>" target="_blank" class="btn btn-success btn-xs flat"><i class="fa fa-print"></i> Excel</a></center>
                        </td>
                        </tr>
    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="callout callout-warning">
        <h4><i class="fa fa-warning"></i> Peringatan!</h4>

        <p>Data tidak di temukan.</p>
    </div>
<?php endif; ?>