<?= $this->session->flashdata('info') ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/jurusan/dosen/tambah'); ?>" class="btn btn-xs btn-primary flat"> <i class="fa fa-plus-circle"></i> Tambah</a>
        <?php if (count($data_dosen) > 0): ?>
		<button class="btn btn-flat btn-default btn-xs ">Terdapat <b><?= e($jumlah_data) ?> Record</b></button>
            <div class="pull-right">
                <?= $halaman ?>
            </div>
        <?php else: ?>

        <?php endif; ?>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-body table-responsive">
        <table class="table demo-table data-table">
            <thead>
                <tr>
                    <th id="th">No.</th>
                    <th id="th">NIK</th>
                    <th id="th">Nama Dosen</th>
                    <th id="th">Homebase</th>
                    <th id="th">Status Dosen</th>
                    <th id="th">Status Login</th>
                    <th id="th">Alamat Email</th>
                    <th id="th">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1 + $this->uri->segment(5);
                foreach ($data_dosen as $d) {
                    ?>
                    <tr>
                        <td style="text-align: center;"><?= $i++ ?>.</td>
                        <td id="nik-<?= e($d->kode_dosen) ?>"><?= e($d->nik) ?></td>
                        <td id="nama-dosen-<?= e($d->kode_dosen) ?>"><?= e($d->nama_dosen) ?></td>
                        <td ><?= e($d->nama_program_studi) ?></td>
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
                        <td id="alamat-email-<?= e($d->kode_dosen) ?>"><?= e($d->alamat_email) ?></td>
                        <td align="center" width="220">
                            <a href="#" onclick="gantipassword('<?= e($d->kode_dosen) ?>')" class="btn btn-xs btn-primary flat"><i class="fa fa-refresh"></i> Reset Sandi</a>
                            <a href="<?= site_url('admin/jurusan/dosen/edit/' . $d->kode_dosen); ?>" class="btn btn-xs btn-info flat"><i class="fa fa-edit"></i> Ubah</a>
                            <a href="#" class="btn btn-xs btn-danger flat" onclick="hapus('<?= e($d->kode_dosen) ?>')"><i class="fa fa-trash"></i> Hapus</a>
                        </td>

                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    function hapus(kode_dosen) {
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
            $.ajax({
                url: "<?= site_url('admin/jurusan/dosen/hapus') ?>/" + kode_dosen,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res.status) {
                        swal('Success!', res.msg, 'success').then(function() {
                            location.reload();
                        });
                    } else {
                        swal('Gagal!', res.msg, 'error');
                    }
                }
            });
        });
    }

    function gantipassword(kode_dosen) {
        swal({
            title: "",
            text: "Anda yakin ingin mereset password dosen ini?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: 'red',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            $.ajax({
                url: "<?= site_url('admin/jurusan/dosen/generate_sandi') ?>/" + kode_dosen,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res.status) {
                        swal({
                            title: 'Sukses!',
                            text: 'Password baru: ' + res.password_string,
                            type: 'success',
                            html: true
                        });
                    } else {
                        swal('Gagal!', res.msg, 'error');
                    }
                }
            });
        });
    }
</script>