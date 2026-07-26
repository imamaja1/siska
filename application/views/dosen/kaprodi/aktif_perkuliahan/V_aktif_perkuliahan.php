<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('dosen/kaprodi/aktif_perkuliahan') ?>" class="btn btn-success btn-sm flat"><i
                    class="fa fa-arrow-left"></i> Kembali</a>
        <a href="<?= site_url('dosen/kaprodi/aktif_perkuliahan/cetak_aktif_perkuliahan') ?>"
           class="btn btn-warning btn-sm flat"><i class="fa fa-file-excel-o"></i> Cetak Excel</a>

        <span class="badge bg-aqua pull-right">Angkatan : 20<?= $angkatan ?></span>
        <span class="badge bg-blue-active pull-right">Tahun Akademik : <?= $ta->tahun_akademik ?> - <?= $ta->semester == 1 ? 'Ganjil' : 'Genap' ?></span>
        <span class="badge bg-aqua pull-right"><?= $prodi->singkatan_program_studi ?> - <?= $prodi->nama_program_studi ?></span>
    </div>
</div>
<div class="box box-solid flat">
    <div class="box-body">
        <h2>Aktif KRS</h2>
        <hr>
        <div class="table-responsive">
            <table class="table demo-table data-table example1 data" id="data">
                <thead>
                <tr>
                    <th>NO.</th>
                    <th>NIM</th>
                    <th>NAMA</th>
                    <th>Telf</th>
                  	<th>Dosen Wali</th>
                    <th>Aktivasi Dosen</th>
                    <th>Detail</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1;
                foreach ($data as $row) : ?>
                    <tr>
                        <td width="3%" align="center"><?= $i++ ?>.</td>
                        <td align="center"><?= $row->nim ?></td>
                        <td><?= $row->nama_mahasiswa ?></td>
                        <td><?= $row->telepon ?></td>
                      	<td><?= $row->nama_dosen ?></td>
                        <td>
                            <?php if ($row->status_cetak == 'A') : ?>
                                <span class="badge bg-green"><i class="fa fa-check"></i> Sudah</span>
                            <?php else : ?>
                                <span class="badge bg-red"><i class="fa fa-times"></i> Belum</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a class="btn btn-info btn-xs" href="#"
                               onclick="halaman('<?= site_url('dosen/Konsultasi_perwalian/biodata_mahasiswa/' . $row->nim) ?>');">
                                <i class="fa fa-user"></i> Biodata</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="box box-solid flat">
    <div class="box-body">
        <h2>Tidak Aktif KRS</h2>
        <hr>
        <div class="table-responsive">
            <table class="table demo-table data-table example1 data" id="data">
                <thead>
                <tr>
                    <th id="th">NO.</th>
                    <th id="th">NIM</th>
                    <th id="th">NAMA</th>
                    <th id="th">Telf</th>
                  	<th id="th">Dosen Wali</th>
                    <th>Detail</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1;
                foreach ($tidak_aktif as $row) : ?>
                    <tr>
                        <td width="3%" align="center"><?= $i++ ?>.</td>
                        <td align="center"><?= $row->nim ?></td>
                        <td><?= $row->nama_mahasiswa ?></td>
                        <td><?= $row->telepon ?></td>
                      	<td><?= $row->nama_dosen ?></td>
                        <td>
                            <a class="btn btn-info btn-xs" href="#"
                               onclick="halaman('<?= site_url('dosen/Konsultasi_perwalian/biodata_mahasiswa/' . $row->nim) ?>');">
                                <i class="fa fa-user"></i> Biodata</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function halaman(url) {
        var win = window.open(url, 'DUMET School', "height=600, width=1000, scrollbars=yes");
        win.focus();
    }
</script>