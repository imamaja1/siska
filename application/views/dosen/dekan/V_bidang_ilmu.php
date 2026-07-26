<?= e($this->session->flashdata('info')) ?>
<div class="box box-solid flat">
    <div class="box-body">
        <table class="table table-bordered table-condensed">
            <tr>
                <th>Jumlah dosen pada fakultas anda (HomeBase)</th>
                <th>Yang sudah ngisi</th>
                <th>Yang belum ngisi</th>
            </tr>
            <tr>
                <td><button class="btn btn-default btn-xs"><?= e($total_dosen) ?> Dosen (100%) </button></td>
                <td>
                    <button class="btn btn-default btn-xs"><?= e($sudah_ngisi) ?> Dosen (<?= number_format(($sudah_ngisi / $total_dosen) * 100, 2) ?>%)</button>
                    <button class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal_sudah_ngisi"><i class="fa fa-eye"></i></button>
                </td>

                <td>
                    <button class="btn btn-default btn-xs"><?= e($total_dosen - $sudah_ngisi) ?> Dosen (<?= number_format((($total_dosen - $sudah_ngisi) / $total_dosen) * 100, 2) ?>%)</button>
                    <!-- <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal_belum_ngisi"><i class="fa fa-eye"></i></button> -->
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="box box-primary flat">
            <div class="box-body">
                <table class="table demo-table data-nilai3">
                    <thead>
                        <tr>
                            <th id="th">No</th>
                            <th id="th">Bidang Ilmu</th>
                            <th id="th">Prodi</th>
                            <th id="th">Jumlah Dosen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($data_bidang_ilmu as $dbi) {
                            ?>
                            <tr>
                                <td align="center"><?= $i++ ?></td>
                                <td><?= e($dbi->nama_bidang) ?></td>
                                <td><?= e($dbi->nama_program_studi) ?></td>
                                <td align="center">

                                    <button class="btn btn-default btn-xs"><?= e($this->db->query("select count(*) as cnt from bidang_ilmu_detail where id_bidang_ilmu=?", array($dbi->id_bidang_ilmu))->row()->cnt) ?></button>
                                    <button class="btn btn-primary btn-xs" onclick="show_jumlah_dosen('<?= e($dbi->id_bidang_ilmu) ?>')">Lihat</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-primary flat">
            <div class="box-body">
                <table class="table demo-table data-nilai3">
                    <thead>
                        <tr>
                            <th id="th">No</th>
                            <th id="th">Nama Dosen</th>
                            <th id="th">Bidang Ilmu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ii = 1;
                        foreach ($data_bidang_ilmu2 as $dbii) {
                            ?>
                            <tr>
                                <td align="center"><?= $ii++ ?></td>
                                <td><?= e($dbii->nama_dosen) ?></td>
                                <td><?= e($dbii->nama_bidang) ?></td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_sudah_ngisi" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Daftar dosen yang sudah input bidang ilmunya</h4>
            </div>
            <div class="modal-body">
                <div class="box box-solid flat">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Dosen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $non = 1;
                                    foreach ($dosen_sudah_ngisi as $dbg) {
                                        ?>
                                        <tr>
                                            <td><?= $non++ ?></td>
                                            <td><?= e($dbg->nama_dosen) ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_belum_ngisi" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Daftar dosen yang sudah input bidang ilmunya</h4>
            </div>
            <div class="modal-body">
                <div class="box box-solid flat">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Dosen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $nonx = 1;
                                    foreach ($dosen_belum_ngisi as $dbg) {
                                        ?>
                                        <tr>
                                            <td><?= $nonx++ ?></td>
                                            <td><?= e($dbg->nama_dosen) ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-jumlah_dosen" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="judul_nama_bidang"></h4>
            </div>
            <div class="modal-body">
                <div class="box box-solid flat">
                    <div class="box-body">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Dosen</th>
                                </tr>
                            </thead>
                            <tbody class="tampil_data_dosen">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<script>
    function show_jumlah_dosen(id_bidang) {
//        alert(id_bidang);
        $.ajax({
            url: "<?= site_url('dosen/bidang_ilmu/jumlah_dosen') ?>/" + id_bidang,
            type: "GET",
            dataType: "json",
            data: "id_bidang=" + id_bidang,
            success: function (data) {

//               
                var nomor = 0;
                $('.tampil_data_dosen').empty();

                for (i = 0; i < data.length; i++) {
                    nomor++;

                    $('.tampil_data_dosen').append('<tr><td>' + nomor + '</td><td>' + data[i].nama_dosen + '</td></tr>');
                }
                $('#judul_nama_bidang').empty();
                $('#judul_nama_bidang').append('Nama dosen dengan bidang ilmu ' + data[0].nama_bidang);
                $("#modal-jumlah_dosen").modal('show');
            },
        });

    }
</script>