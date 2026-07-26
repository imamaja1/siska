<div class="box box-solid box-warning">
    <div class="box-header with-border">Data KRS Mahasiswa</div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table data-table ">
                <thead>
                    <tr>
                        <th>no</th>
                        <th>Nim</th>
                        <th>Nama</th>
                        <th>Nama Dosen Wali</th>
                        <th>Pengisian KRS</th>
                        <th>Validasi Dosen</th>
                        <th>Validasi SKS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  foreach ($data as $key => $value) { ?> 
                        <tr>
                            <td><?= $key+1 ?></td>
                            <td><?= $value->nim ?></td>
                            <td><?= $value->nama_mahasiswa ?></td>
                            <td><?= $value->nama_dosen ?></td>
                            <td>
                                <?php
                                    if ($value->kode_krs == null) {
                                        echo "<span class='badge bg-red'>Belum <i class='fa fa-times'></i></span>";
                                    }else{
                                        echo "<span class='badge bg-green'>Sudah <i class='fa fa-check'></i></span>";
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    if ($value->status_cetak == 'N') {
                                        echo "<span class='badge bg-red'>Belum <i class='fa fa-times'></i></span>";
                                    }else{
                                        echo "<span class='badge bg-green'>Sudah <i class='fa fa-check'></i></span>";
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    if ($value->pembayaran_sks == 0) {
                                        echo "<span class='badge bg-red'>Belum <i class='fa fa-times'></i></span>";
                                    }else{
                                        echo "<span class='badge bg-green'>Sudah <i class='fa fa-check'></i></span>";
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $('.data-table').DataTable();
    </script>
</div>
