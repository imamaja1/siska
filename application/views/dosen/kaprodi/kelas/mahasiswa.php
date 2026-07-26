<div class="box">
    <div class="box-header">
        <div class="col-md-6 col-xs-12"><h3>Daftar Mahasiswa</h3></div>
        <div class="col-md-6 col-xs-12" id="landing-pengampu"></div>
    </div>
    <hr>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered data-tablegx">
                <thead>
                    <tr>
                        <th id="th" width="3%">NO.</th>
                        <th id="th">NIM</th>
                        <th id="th">NAMA MAHASISWA</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($data as $row): ?>
                        <tr>
                            <td align="center"><?= $i++ ?>.</td>
                            <td align="center"><?= e($row->nim) ?></td>
                            <td align="center"><?= e($row->nama_mahasiswa) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <hr>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= site_url('assets/plugins/datatables/dataTables.bootstrap.min.js'); ?>"></script>
<script>
     $(".data-tablegx").dataTable({
        "ordering": false,
        // "order": [[1, 'desc']]
        "info": false,
        "pageLength": 10
    });
    function load_pegampu() {
        var url = "<?= site_url('dosen/kaprodi/kelas/pengampu') ?>/" + super_kelas_id;
        $.ajax({
            url: url,
            success: function (res) {
                $("#landing-pengampu").html(res);
            }
        })
    }
    load_pegampu()
</script>