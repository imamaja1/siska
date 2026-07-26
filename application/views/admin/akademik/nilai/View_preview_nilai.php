<div class="col-md-12">
    <dl class="dl-horizontal">
        <dt>KODE MATAKULIAH :</dt>
        <dd><?= $kode_matakuliah == "" ? "<span class='badge bg-red'>Tidak boleh kosong</span>" : e($kode_matakuliah) ?></dd>
        <dt>NAMA MATAKULIAH :</dt>
        <dd><?= e($nama_matakuliah) ?></dd>
    </dl>
<!--    <div class="box box-primary">-->
<!--        <div class="box-body">-->
            <div class="table-responsive">
                <table class="table demo-table">
                    <thead>
                    <tr>
                        <th>NO.</th>
                        <th>NIM</th>
                        <th>NAMA</th>
                        <th>NILAI HARIAN</th>
                        <th>NILAI UTS</th>
                        <th>NILAI UAS</th>
                        <th>NILAI AKHIR</th>
                        <th>GRADE</th>
                        <th>TIDAK BERHAK</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=1; foreach ($data as $row) : ?>
                        <tr>
                            <td style="text-align: center"><?= $no++ ?>.</td>
                            <td style="text-align: center"><?= $row['nim'] == "" ? "<span class='badge bg-red'>Tidak boleh kosong</span>" : e($row['nim']) ?></td>
                            <td style="text-align: left"><?= e($row['nama']) ?></td>
                            <td style="text-align: center"><?= e($row['nilai_harian']) ?></td>
                            <td style="text-align: center"><?= e($row['nilai_uts']) ?></td>
                            <td style="text-align: center"><?= e($row['nilai_uas']) ?></td>
                            <td style="text-align: center"><?= $row['nilai_akhir'] == "" ? "<span class='badge bg-red'>Tidak boleh kosong</span>" : e($row['nilai_akhir']) ?></td>
                            <td style="text-align: center"><?= e($row['grade']) ?></td>
                            <td style="text-align: center"><?= $row['tidak_berhak'] == "" ? "<span class='badge bg-red'>Tidak boleh kosong</span>" : e($row['tidak_berhak']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
<!--        </div>-->
<!--    </div>-->
    <button onclick="impot()" class="btn btn-success"><i class="fa fa-upload"></i> Import</button>
</div>
<script>
    function impot() {
        var url = "<?= site_url('admin/akademik/nilai/import') ?>";
        $.ajax({
            url : url,
            beforeSend : function () {
                var html = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
                $("#landing-upload").html(html);
            },
            success : function (res) {
                $("#landing-upload").html(res);
            }
        })
    }
</script>