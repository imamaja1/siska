<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/jurusan/dosen'); ?>" class="btn btn-xs btn-success flat"><i class="fa fa-arrow-left"></i> Kembali</a>
        <button class="btn btn-flat btn-default btn-xs ">Terdapat <b><?php
                if ($jumlah_data > 0) {
                    echo $jumlah_data;
                } else {
                    echo "0";
                }
                ?> Record</b></button>
    </div>
</div>
<?php if (count($data_dosen) > 0): ?>
    <div class="box box-primary flat" <?= $hidden ?>>
        <div class="box-body table-responsive">
            <table class="table demo-table">
                <thead>
                    <tr>
                        <th id="th">No.</th>
                        <th id="th">NIK</th>
                        <th id="th">Nama Dosen</th>
                        <th id="th">Homebase</th>
                        <th id="th">Status Dosen</th>
                        <th id="th">Status Login</th>
                        <th id="th">Alamat Email</th>
                        <th id="th">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($data_dosen as $d) {
                        ?>
                        <tr>
                            <td align="center"><?= $i++; ?>.</td>
                            <td align="center" id="nik-<?= $d->kode_dosen ?>"><?= $d->nik ?></td>
                            <td id="nama-dosen-<?= $d->kode_dosen ?>"><?= $d->nama_dosen ?></td>
                            <td ><?= $d->nama_program_studi ?></td>
                            <td style="text-align: center;"><?php
                                $status_dosen = $d->status_dosen;
                                if ($status_dosen == "T") {
                                    echo "Tetap";
                                } else {
                                    echo "Tidak Tetap";
                                }
                                ?></td>
                            <td style="text-align: center;"><?php
                                $status_login = $d->status_login;
                                if ($status_login == "A") {
                                    echo "Aktif";
                                } else {
                                    echo "Tidak Aktif";
                                }
                                ?></td>
                            <td><?= $d->alamat_email ?></td>
                            <td align="center" width="220">
                                <a href="#" onclick="gantipassword('<?= site_url('admin/jurusan/dosen/generate_sandi/' . $d->kode_dosen) ?>')" class="btn btn-xs btn-primary flat"><i class="fa fa-refresh"></i> Reset Sandi</a>

                                <a href="<?= site_url('admin/jurusan/dosen/edit/' . $d->kode_dosen); ?>" class="btn btn-xs btn-info flat"><i class="fa fa-edit"></i> Ubah</a>
                                <a href="#" class="btn btn-xs btn-danger flat" onclick="hapus('<?= site_url('admin/jurusan/dosen/hapus') . '/' . $d->kode_dosen ?>')"><i class="fa fa-trash"></i> Hapus</a>
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <div class="callout callout-info flat">
        <p>Tidak ada data dosen dengan kata kunci tersebut.</p>
    </div>
<?php endif; ?>

<div class="box box-primary flat"><br>
    <div class="box-body">
        <form class="form-horizontal" method="POST" action="<?= site_url('admin/jurusan/dosen/search') ?>">
            <div class="form-group">
                <label class="control-label col-sm-3">Kata Kunci:</label>
                <div class="col-sm-4">
                    <input value="<?= set_value('nama_dosen') ?>" type="text" name="nama_dosen" placeholder="Nama Dosen" class="form-control">
                    <small class="text-danger"><?= form_error('nama_dosen'); ?></small>
                </div>
                <button type="submit" class="btn btn-primary flat"><i class="fa fa-cog"></i> Proses</button>
            </div>

        </form>
    </div>
</div>

<script>
    function hapus(url) {
        swal({
            title: '',
            text: "Anda yakin ingin menghapus data ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            window.location.href = url;
        });
    }
    function gantipassword(url) {
        swal({
            title: "",
            text: "Anda yakin ingin mereset password dosen ini?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: 'red',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya',
            closeOnConfirm: false
        }).then(function () {
            window.location.href = url;
        })
    }
</script>