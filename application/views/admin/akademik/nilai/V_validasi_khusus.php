<div class="box box-solid">
    <div class="box-header with-border">
        <form method="post" action="<?= site_url('admin/akademik/validasikhusus/cari'); ?>">
            <div class="row">
                <div class="col-md-3">
                    <select name="kd_fk" class="form-control">
                        <option disabled>--Pilih Fakultas--</option>
                        <?php foreach ($kode_fakultas as $kd_fk): ?>
                            <?php if ($match_kode_fakultas == $kd_fk->dekan): ?>
                                <option selected value="<?= $kd_fk->dekan ?>"><?= $kd_fk->nama_fakultas ?></option>
                            <?php else: ?>
                                <option value="<?= $kd_fk->dekan ?>"><?= $kd_fk->nama_fakultas ?></option>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="kode_tahun_akademik" class="form-control select2">
                            <option value="" selected disabled>Tahun Akademik</option>
                            <?php foreach ($tahun_akademik as $row) : ?>
                                <option value="<?= $row->kode_tahun_akademik ?>"><?= $row->tahun_akademik ?>
                                    - <?= $row->semester == 0 ? "GENAP" : "GANJIL" ?></option>
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
                      	if($row->nama_dosen){
                          continue;
                        }
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row->kelas_id; ?></td>
                            <td><?= $row->singkatan_program_studi; ?></td>
                            <td><?= $row->kode_matakuliah; ?> - <?= $row->nama_matakuliah; ?> - Kelas
                                : <?= $row->nama_kelas; ?></td>
                            <td><?= $row->nama_dosen; ?></td>
                            <td><?= nilai_validasi($row->status_nilai) ?></td>
                            <td><?= nilai_validasi($row->validasi_nilai); ?></td>
                            <td><?= nilai_validasi($row->validasi_dekan); ?></td>
                            <td>
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
<script>
    $('.data-table').DataTable({
        columnDefs: [{targets: 'no-sort', orderable: false}],
        order: [[4, 'desc'], [5, 'desc'], [6, 'asc']]
    });

</script>