<div class="box box-primary">
    <div class="box-header">
        <!--        <div class="col-md-6 col-sm-12 col-md-offset-6" style="padding: 0px">-->
        <div class="pull-right">
            <form class="form-horizontal" id="form-cari">
                <div class="form-group" style="margin-bottom: 0px">
                    <div class="col-sm-4">
                        <div class="input-group input-group-sm">
                            <input type="text" placeholder="Search" class="form-control" name="keyword">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!--            </div>-->
        </div>
    </div>
    <div class="box-body">
        <table class="table demo-table">
            <thead>
                <tr>
                    <th id="th">No</th>
                    <th id="th">Kode Matakuliah</th>
                    <th id="th">Nama Matakuliah</th>
                    <th id="th">SKS Teori</th>
                    <th id="th">SKS Praktek</th>
                    <th id="th">SKS Praktikum</th>
                    <th id="th">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1 + $this->uri->segment(6);
                foreach ($data as $d) {
                    ?>
                    <tr <?= ($d->jenis == '1') ? 'style="font-style:italic;"' : '' ?>>
                        <td align="center"><?= $i++ ?></td>
                        <td align="center" id="kode-matakuliah-<?= $d->id_matakuliah ?>"><?= $d->kode_matakuliah ?></td>
                        <td id="nama-matakuliah-<?= $d->id_matakuliah ?>"><?= $d->nama_matakuliah ?></td>
                        <td align="center" id="sks-teori-<?= $d->id_matakuliah ?>"><?= $d->sks_teori ?></td>
                        <td align="center" id="sks-praktek-<?= $d->id_matakuliah ?>"><?= $d->sks_praktek ?></td>
                        <td align="center" id="sks-praktikum-<?= $d->id_matakuliah ?>"><?= $d->sks_praktikum ?></td>
                        <td align="center" style="display: none" id="jenis-<?= $d->id_matakuliah ?>"><?= $d->jenis ?></td>
                        <td align="center" style="display: none" id="kode-nama-jurusan-<?= $d->id_matakuliah ?>">
                            <?= $d->kode_program_studi ?>
                        </td>
                        <td align="center" style="display: none" id="block-<?= $d->id_matakuliah ?>">
                            <?= $d->block ?>
                        </td>
                        <td align="center">
                            <a href="#" class="btn btn-xs btn-info flat"
                                onclick="javascript:editMatakuliah('<?= $d->id_matakuliah ?>')"><i
                                    class="fa fa-edit"></i></a>&nbsp;
                            <a href="#" class="btn btn-xs btn-danger flat"
                                onclick="hapus('<?= site_url('admin/jurusan/kurikulum/matakuliah/hapus/' . $d->id_matakuliah) ?>')"><i
                                    class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    <div style="display: none;" id="kode-kompetensi-<?= $d->id_matakuliah ?>"><?= $d->kode_kompetensi ?>
                    </div>
                <?php } ?>

            </tbody>
        </table>
    </div>
    <div class="box-footer">
        <?php if (count($data) > 0): ?>
            <span class="btn flat btn-default btn-sm " style="cursor: default">Terdapat <b><?= $jumlah_data; ?> Record</b></span>
            <div class="pull-right" id="halaman">
                <?= $halaman; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#halaman a').on('click', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                success: function (res) {
                    $('#landing').html(res);
                },
                error: function () {
                    console.log('gagal load');
                }
            })
        });

        $("#form-cari").submit(function (e) {
            e.preventDefault();
            var url = "<?= site_url('admin/jurusan/kurikulum/matakuliah/cari') ?>";
            var data = $(this).serialize();

            $.ajax({
                url: url,
                data: data,
                type: "post",
                success: function (res) {
                    $("#landing").html(res);
                }
            })
        })
    })
</script>