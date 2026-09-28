<div class="box box-solid">
    <div class="box-header with-border">
        <form method="post" action="<?= site_url('admin/akademik/validasikhusus/cari'); ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="row">
                <div class="col-md-3">
                    <select name="kd_fk" class="form-control">
                        <option disabled>--Pilih Fakultas--</option>
                        <?php foreach ($kode_fakultas as $kd_fk): ?>
                            <?php if ($match_kode_fakultas == $kd_fk->dekan): ?>
                                <option selected value="<?= e($kd_fk->dekan) ?>"><?= e($kd_fk->nama_fakultas) ?></option>
                            <?php else: ?>
                                <option value="<?= e($kd_fk->dekan) ?>"><?= e($kd_fk->nama_fakultas) ?></option>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="kode_tahun_akademik" class="form-control select2">
                            <option value="" selected disabled>Tahun Akademik</option>
                            <?php foreach ($tahun_akademik as $row) : ?>
                                <option value="<?= e($row->kode_tahun_akademik) ?>"><?= e($row->tahun_akademik) ?>
                                    - <?= e($row->semester == 0 ? "GENAP" : "GANJIL") ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>


        </form>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table data-nilai2">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Prodi</th>
                        <th>Nama MK</th>
                        <th>Dosen</th>
                        <th>Nilai</th>
                        <th>Prodi</th>
                        <th>Dekan</th>
                        <th>Cetak</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($kelas as $row) {
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= e($row->kelas_id); ?></td>
                            <td><?= e($row->singkatan_program_studi); ?></td>
                            <td><?= e($row->kode_matakuliah); ?> - <?= e($row->nama_matakuliah); ?> - Kelas
                                : <?= e($row->nama_kelas); ?></td>
                            <td><?= e($row->nama_dosen); ?></td>
                            <td><?= nilai_validasi($row->status_nilai) ?></td>
                            <td><?= nilai_validasi($row->validasi_nilai); ?></td>
                            <td><?= nilai_validasi($row->validasi_dekan); ?></td>
                            <td>
                                <a class="btn btn-info btn-xs btn-flat btn-nilai-khusus"
                                   href="#"
                                   data-toggle="modal" data-target="#modal-nilai-khusus"
                                   data-kelas="<?= e($row->kelas_id) ?>"
                                   data-info="<?= e($row->kode_matakuliah . ' - ' . $row->nama_matakuliah . ' - Kelas ' . $row->nama_kelas) ?>">
                                    <i class="fa fa-file-text-o"></i> Nilai
                                </a>
                                <?php
                                if (($row->validasi_nilai == "T")) {
                                    ?>
                                    <a class="btn btn-primary btn-xs btn-flat"
                                       href="<?= site_url('admin/akademik/cetak_nilai/index/' . $row->kelas_id) ?>">
                                        <i class="fa fa-print"></i>
                                        Cetak
                                    </a>

                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-nilai-khusus" tabindex="-1" role="dialog" aria-labelledby="modal-nilai-khusus-label">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modal-nilai-khusus-label">
                    Data Nilai Mahasiswa <small class="modal-info-khusus"></small>
                </h4>
            </div>
            <div class="modal-body">
                <div id="data-nilai-khusus"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.data-table').DataTable({
            columnDefs: [{targets: 'no-sort', orderable: false}],
            order: [[4, 'desc'], [5, 'desc'], [6, 'asc']]
        });
    });

    $(document).on('click', '.btn-nilai-khusus', function () {
        var id = $(this).data('kelas');
        var info = $(this).data('info');

        $('#modal-nilai-khusus .modal-info-khusus').text(info ? '(' + info + ')' : '');

        var loading = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
        $('#data-nilai-khusus').html(loading);

        $.ajax({
            url: "<?= site_url('admin/akademik/validasikhusus/nilai_kelas/') ?>/" + id,
            success: function (res) {
                $('#data-nilai-khusus').html(res);
            },
            error: function () {
                $('#data-nilai-khusus').html("<div class='callout callout-danger flat'><p>Gagal memuat data nilai.</p></div>");
            }
        });
    });
</script>