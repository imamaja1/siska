<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/laporan/rekap_ipk') ?>" class="btn btn-success btn-sm flat"><i class="fa fa-arrow-left"></i> Kembali</a>
        <div class="pull-right">
            <a href="<?= site_url('admin/laporan/rekap_ipk/data_rekap_ipk') ?>" class="btn btn-sm btn-primary"><i class="fa fa-line-chart"></i> Rekap IPK</a>
        </div>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-calendar"></i><strong> TA. <?= $tahun_akademik->tahun_akademik ?> <?= $tahun_akademik->semester == 0 ? "GENAP" : "GANJIL" ?></strong> (<?= $prodi->singkatan_program_studi ?> - <?= strtoupper($prodi->nama_program_studi )?>)</h4>
        <div class="box-tools pull-right">
            <a href="<?= site_url('admin/laporan/rekap_ipk/cetak_ipk_rata') ?>" class="btn btn-danger btn-sm flat"><i class="fa fa-file-excel-o"></i> Export</a>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table">
                <thead>
                    <tr>
                        <th id="th">NO.</th>
                        <th id="th">NIM</th>
                        <th id="th">NAMA</th>
                        <th id="th">P/L</th>
                        <th id="th">IP (SEMESTER)</th>
                        <th id="th">IPK</th>
                        <th id="th">SKS</th>
                        <th id="th">KET</th>
                        <th id="th">KKP</th>
                        <th id="th">PRAKTIKUM</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = 1 + $this->uri->segment(5); foreach ($data as $row) : ?>
                    <tr>
                        <td width="3%" align="center"><?= $i++ ?>.</td>
                        <td align="center"><?= $row->nim?></td>
                        <td ><?= $row->nama_mahasiswa ?></td>
                        <td align="center"><?= $row->jenis_kelamin ?></td>
                        <td align="center"><?= $row->ip ?></td>
                        <td align="center"><?= $row->ipk ?></td>
                        <td align="center"><?= $row->total_sks ?></td>
                        <td align="center"><?= $row->skripsi == 0 ? '' : 'SKRIPSI' ?></td>
                        <td align="center"><?= $row->kkp == 0 ? '' : 'KKP' ?></td>
                        <td align="center"><?= $row->praktikum == 0 ? '' : 'PRAKTIKUM' ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div>
                <?php if (count($data) > 0): ?>
                    <button class="btn btn-default btn-sm ">Terdapat <b><?= $jumlah_data; ?> Record</b></button>
                    <div class="pull-right">
                        <?= $halaman; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>