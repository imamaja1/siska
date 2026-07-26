<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/krs'); ?>" class="btn btn-xs btn-success flat"><i class="fa fa-arrow-left"></i> Kembali</a>        
    </div>
</div>
<div class="box box-success">
    <div class="box-header">
        <h4>Rekap Mahasiswa aktif "<?= $file_name ?>"</h4>
        <div class=box-tools>
            <a href="<?= site_url('admin/akademik/krs/download_rekap/' . $id_matakuliah); ?>" class="btn btn-sm btn-success"><i class="fa fa-file-excel-o"></i> Excel</a>
        </div>
    </div>
    <div class="box-body">
        <?php if (count($data) > 0): ?>
            <table class="table demo-table data-table">
                <thead>
                    <tr>
                        <th id="th">NO.</th>
                        <th id="th">NIM</th>
                        <th id="th">NAMA MAHASISWA</th>
                        <th id="th">PEMBAYARAN SKS</th>
                    </tr>
                </thead>
                <?php
                $no = 1;
                foreach ($data as $row) {
                    ?>
                    <tr>
                        <td align="center"><?= $no++ ?>.</td>
                        <td align="center"><?= $row->nim; ?></td>
                        <td><?= $row->nama_mahasiswa; ?></td>
                        <td style="text-align: center"><?= $row->kode_status_perkuliahan == '' ? "<span class='badge bg-red'><i class='fa fa-times'></i> Belum</span>" : "<span class='badge bg-green'><i class='fa fa-check'></i> Sudah</span>" ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php else: ?>
            <p class="alert">Tidak ada data matakuliah dengan Jurusan tersebut.</p>
        <?php endif; ?>
    </div>
</div>
