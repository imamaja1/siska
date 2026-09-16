<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title">Mahasiswa Aktif TA. <?= e($tahun_akademik->tahun_akademik) ?> <?= $tahun_akademik->semester == '1' ? 'GANJIL' : 'GENAP' ?></h3>
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
                    <td><?= !empty($row->nim) ? e($row->nim) : '-' ?></td>
                    <td><?= !empty($row->nama_mahasiswa) ? e($row->nama_mahasiswa) : '-' ?></td>
                    <td><?= !empty($row->telepon) ? e($row->telepon) : '-' ?></td>
                    <td><?= !empty($row->email) ? e($row->email) : '-' ?></td>
                    <td><?= !empty($row->nama_program_studi) ? e($row->nama_program_studi) : '<span class="text-muted">-</span>' ?></td>
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