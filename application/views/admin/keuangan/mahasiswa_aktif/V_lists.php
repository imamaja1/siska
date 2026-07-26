<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title">Mahasiswa Aktif TA. <?= $tahun_akademik->tahun_akademik ?> <?= $tahun_akademik->semester == '1' ? 'GANJIL' : 'GENAP' ?></h3>
        <button onclick="cetak()" class="btn btn-success btn-sm pull-right"><i class="fa fa-file-excel-o"></i> Excel</button>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered demo-table data-table">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>NIM</th>
                    <th>Nama Mahasiwa</th>
                    <th>No. Telp</th>
                    <th>Email</th>
                    <th>Program Studi</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($mahasiswa_aktif as $key => $row) : ?>
                <tr>
                    <td><?= $key + 1 ?>.</td>
                    <td><?= $row->nim ?></td>
                    <td><?= $row->nama_mahasiswa ?></td>
                    <td><?= $row->telepon ?></td>
                    <td><?= $row->email ?></td>
                    <td><?= $row->nama_program_studi ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(".data-table").dataTable({
        "ordering": false,
        "info": false,
        "pageLength": 50
    });

    function cetak() {
        var url = "<?= site_url('admin/keuangan/mahasiswa_aktif/excel') ?>/"+super_kode_tahun_akademik;
        window.location.href = url;
    }
</script>