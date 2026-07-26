<div class="box box-success flat">
    <div class="box-header">
        Pencarian mahasiswa
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table">
                <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>#</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($data as $row) :
                    ?>
                    <tr>
                        <td><?= e($row->nim) ?></td>
                        <td><?= e($row->nama_mahasiswa) ?></td>
                        <td align="center">
                            <a href="#" onclick="grafik('<?= e($row->nim) ?>')" class="btn btn-warning btn-xs flat"><i
                                        class="fa fa-line-chart"></i> Grafik Nilai</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-grafik" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content" id="content-grafik" style="border-radius: 10px">
        </div>
    </div>
</div>
<script>
    function grafik(id) {
        var url = "<?= site_url('dosen/kaprodi/konsultasi_perwalian/grafik_nilai') ?>/" + id;
        $.ajax({
            url: url,
            success: function (res) {
                $('#content-grafik').html(res);
                $("#modal-grafik").modal('show');
            },
        })
    }
</script>