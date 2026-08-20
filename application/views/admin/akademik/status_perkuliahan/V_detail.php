<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Detail Status Perkuliahan Mahasiswa TA. <?= tahun_akademik()->tahun_akademik ?> <?= tahun_akademik()->semester == '0' ? 'GENAP' : 'GANJIL' ?></h3>
        <div class="box-tools">
            <a href="<?= site_url('admin/akademik/status_perkuliahan/excel/'.$angkatan.'/'.$kode_program_studi) ?>" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Excel</a>
        </div>
    </div>
    <div class="box-body">
        <?php if(count($data) > 0) : ?>
        <div class="table-responsive">
            <table class="table demo-table data-table">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>NIM</th>
                    <th>Nama Siswa</th>
                    <th>L/P</th>
                    <th>SKS</th>
                    <th>KET</th>
                    <th>KKP</th>
                    <th>PRAKTIKUM</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1; foreach ($data as $row) : ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= e($row->nim) ?></td>
                    <td><?= e($row->nama_mahasiswa) ?></td>
                    <td style="text-align: center"><?= e($row->jenis_kelamin) ?></td>
                    <td style="text-align: center;"><?= $row->total_sks?></td>
                    <td style="text-align: center;"><?= $row->skripsi == 0 ? "" : "SKRIPSI" ?></td>
                    <td style="text-align: center;"><?= $row->kkp == 0 ? "" : "KKP" ?></td>
                    <td style="text-align: center;"><?= $row->praktikum == 0 ? "" : "PRAKTIKUM" ?></td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p style="text-align: center; font-size: 20pt; font-weight: bold"><i> Tidak ada data ditemukan</i></p>
        <?php endif; ?>
    </div>
</div>
<!--script-->
<script>
    $(function () {
        $(".data-table").dataTable();
    })

</script>