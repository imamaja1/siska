<?php if (count($data) > 0): ?>
    <div class="box box-primary flat">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-list"></i> List Matakuliah</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped data-table">
                    <thead>
                        <tr>
                            <th>NO.</th>
                            <th>KODE MATAKULIAH</th>
                            <th>MATAKULIAH</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($data as $key => $row):
                            ?>
                            <tr>
                                <td><?= $no++ ?>.</td>
                                <td>
                                    <?= $row->kode_matakuliah ?>
                                </td>
                                <td><?= $row->nama_matakuliah ?></td>
                                <td>
                                    <?php if ($row->kelas_id): ?>
                                        <button onclick="kelas('<?= $row->id_matakuliah ?>')" class="btn btn-xs btn-default"
                                                title="Lihat Kelas"><i class="fa fa-eye"></i> View
                                        </button>
                                    <?php else: ?>
                                        <button onclick="generate('<?= $row->id_matakuliah ?>')" class="btn btn-xs btn-primary"
                                                title="Generate Kelas"><i class="fa fa-gear"></i> Generate
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="callout callout-warning">
        <h4><i class="fa fa-warning"></i> Peringatan</h4>
        <p>Belum ada data matakuliah untuk program studi tersebut!</p>
    </div>
<?php endif; ?>
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script>
    $('.data-table').dataTable();
    function generate(id_matakuliah) {
        var url = "<?= site_url('admin/akademik/kpat/kelas/generate_kelas') ?>/" + kode_program_studi + "/" + id_matakuliah + "/" + kode_tahun_akademik_mega;
        $.ajax({
            url: url,
            beforeSend: function () {
                $('#landing-matakuliah').html(loader);
            },
            success: function () {
                kelas_kpat();
                kelas(id_matakuliah);
            }
        })
    }

    function kelas(id_matakuliah) {
        matakuliah_id = id_matakuliah;
        var url = "<?= site_url('admin/akademik/kpat/kelas/get_nama_kelas') ?>/" + kode_program_studi + "/" + id_matakuliah + "/" + kode_tahun_akademik_mega;
        $.ajax({
            url: url,
            type: 'get',
            beforeSend: function () {
                $('#landing-kelas').html(loader);
            },
            success: function (data) {
                $('#landing-kelas').html(data);
            },
            error: function () {
                console.log('gagal');
            }
        })
    }
</script>