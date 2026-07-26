<div class="row">
    <div class="col-md-12">
        <div class="box flat">
            <div class="box-body">
                <div class="row">
                    <form action="<?= site_url('dosen/kaprodi/Kelas') ?>" method="get">
                        <div class="col-xs-4">
                            <div class="form-group" style="margin: 0px">
                                <select name="kode_tahun_akademik" id="tahun" class="form-control select2">
                                    <option value="" selected disabled>Tahun Akademik</option>
                                    <?php foreach ($tahun_akademik as $row): ?>
                                        <option <?= e($row->kode_tahun_akademik == $tahun_now ? 'selected' : '') ?>
                                            value="<?= e($row->kode_tahun_akademik) ?>">
                                            <?= e($row->tahun_akademik) ?>
                                            - <?= e($row->semester == 0 ? 'GENAP' : 'GANJIL') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-header">
                List Matakuliah
            </div>
            <div class="box-body">
                <table class="table data-tablex">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Kode Matakuliah</th>
                            <th scope="col">Matakuliah</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kelas as $key => $value): ?>
                            <tr>
                                <th scope="row"><?= e($key + 1) ?></th>
                                <td><?= e($value->kode_matakuliah) ?></td>
                                <td><?= e($value->nama_matakuliah) ?></td>
                                <td>
                                    <button onclick="kelas('<?= e($value->id_matakuliah) ?>')" class="btn btn-xs btn-default"
                                        title="Lihat Kelas">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box" id="landing-kelas" style="padding:10px">
            <div style="height: 200px; background-color: #00a7d0; padding: 20px">
                <p style="text-align: center; color: white"><i>"Landing data Kelas"</i></p>
            </div>
        </div>
    </div>
</div>
<script src="<?= site_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= site_url('assets/plugins/datatables/dataTables.bootstrap.min.js'); ?>"></script>
<script>
     $(".data-tablex").dataTable({
        "ordering": false,
        // "order": [[1, 'desc']]
        "info": false,
        "pageLength": 10
    });
    function kelas(id_matakuliah) {
        tahun_akademik_glob = <?= e($tahun_now) ?>;
        var url = "<?= site_url('dosen/kaprodi/kelas/get_nama_kelas') ?>/" + <?= e($prodi) ?> + "/" + id_matakuliah + "/" + <?= e($tahun_now) ?>;
        $.ajax({
            url: url,
            type: 'get',
            success: function (data) {
                $('#landing-kelas').html(data);
            },
            error: function () {
                console.log('gagal');
            }
        })
    }
</script>