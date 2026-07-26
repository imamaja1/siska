<div class="box box-solid">
    <div class="box-header with-border">
        <form method="post" action="<?= site_url('dosen/validasikhusus/cari'); ?>">
            <div class="row">
                <div class="col-md-4">
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
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>

        </form>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table data-table ">
                <thead>
                    <tr>
                        <th rowspan="2">NO</th>
                        <th rowspan="2">Kode</th>
                        <th rowspan="2">Prodi</th>
                        <th rowspan="2">Nama MK</th>
                        <th rowspan="2">Dosen</th>
                      
                        <th rowspan="2">Nilai</th>
                        <th rowspan="2">Tanggal Ngirim Nilai</th>
                        <th colspan="2">Validasi</th>

                    </tr>
                    <tr>
                        <th>Prodi</th>
                        <th>Dekan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
					$no = 1;
                    if (count($kelas) > 0) :
                        foreach ($kelas as $row) :
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $row->kelas_id; ?></td>
                                <td><?= $row->singkatan_program_studi; ?></td>
                                <td><?= $row->kode_matakuliah; ?> - <?= $row->nama_matakuliah; ?> - Kelas
                                    : <?= $row->nama_kelas; ?></td>
                                <td><?= $row->nama_dosen; ?></td>
                                <td><?= nilai_validasi($row->status_nilai) ?></td>
                              <td><?=  date('d F Y h:i:s', strtotime($row->datecreate)); ?></td>
                                <td><?= nilai_validasi($row->validasi_nilai); ?></td>
                                <td><?= nilai_validasi($row->validasi_dekan); ?></td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">Kelas Belum dibagi di bagian akademik</td>
                        </tr>
                    <?php endif; ?>
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