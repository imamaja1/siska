<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/laporan/aktif_perkuliahan') ?>" class="btn btn-success btn-sm flat"><i class="fa fa-arrow-left"></i> Kembali</a>
        <a href="<?= site_url('admin/laporan/aktif_perkuliahan/cetak_aktif_perkuliahan') ?>" class="btn btn-warning btn-sm flat"><i class="fa fa-file-excel-o"></i> Cetak to Excel</a>
        <span class="badge bg-aqua pull-right"><?= $prodi->singkatan_program_studi ?> - <?= $prodi->nama_program_studi ?></span>
    </div>
</div>
<div class="box box-solid flat">
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table">
                <thead>
                    <tr>
                        <th id="th">NO.</th>
                        <th id="th">NIM</th>
                        <th id="th">NAMA</th>
                        <th id="th">JUMLAH SKS</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1; foreach ($data as $row) : ?>
                    <tr>
                        <td width="3%" align="center"><?= $i++ ?>.</td>
                        <td align="center"><?= $row->nim ?></td>
                        <td ><?= $row->nama_mahasiswa ?></td>
                        <td align="center"><?= $row->jumlah_sks ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>