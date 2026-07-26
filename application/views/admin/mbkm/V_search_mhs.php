<?php if ($cek == 2) : ?>
    <div class="alert alert-danger center" role="alert">
        Data Mahasiswa Tersebut Bukan Termasuk Prodi <?= e($prodi->nama_program_studi) ?>
    </div>
<?php elseif ($cek == 3) : ?>
    <div class="alert alert-danger center" role="alert">
        NIM tidak Terdaftar, Mohon periksa kembali
    </div>
<?php else: ?>
    <table class="table demo-table ">
        <thead>
            <tr>
                <th>no</th>
                <th>Nim</th>
                <th>Nama</th>
                <th>Prodi</th>
                <th>Tahun Akademik MBKM</th>
                <th>Tindakan<?= e($ta) ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mahasiswa as $key => $value) {  ?> 
                    <tr>
                        <td><?= $key+1 ?></td>
                        <td><?= e($value->nim) ?></td>
                        <td><?= e($value->nama_mahasiswa) ?></td>
                        <td><?= e($value->nama_program_studi) ?></td>
                        <td><?= $ta->semester == 1 ? "Ganjil":"Genap" ?>, <?= e($ta->tahun_akademik) ?></td>
                        <td>
                            <?php if ($cek == 0) : ?>
                                <button type="button" class="btn btn-success btn-sm" onclick="update(<?= e($value->nim) ?>)">Verifikasi</button>
                            <?php else: ?>
                                <button type="button" class="btn btn-danger btn-sm" disabled>Terdaftar</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php 
                }
        ?>
        </tbody>
    </table>
<?php endif; ?>