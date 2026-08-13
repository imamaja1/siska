<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/pengguna/pengguna/tambah'); ?>" class="btn btn-xs btn-primary flat"><i class="fa fa-plus-circle"></i> Tambah Data</a>
        <?php if (count($data_pengguna) > 0): ?>
            <button class="btn flat btn-default btn-xs ">Terdapat <b><?= $jumlah_data; ?> Record</b></button>
            <div class="pull-right">
                <?= $halaman; ?>
            </div>
        <?php else: ?>

        <?php endif; ?>
    </div>
</div>
<div class="box box-solid flat">
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table">
                <thead>
                    <tr>
                        <th id="th">No.</th>
                        <th id="th">Nama Pengguna</th>
                        <th id="th">Nama Login</th>
                        <th id="th">Role</th>
                        <th id="th">Tindakan</th>
                    </tr>
                </thead>
                <?php
                $no = 1 + $this->uri->segment(5);
                foreach ($data_pengguna as $data) {
                    ?>
                    <tr>
                        <td align="center"><?= $no++ ?>.</td>
                        <td align="center"><?= e($data->nama_pengguna) ?></td>
                        <td align="center"><?= e($data->nama_login) ?></td>
                        <td align="center"><?= e($data->nama_role) ?></td>
                        <td align="center" width="130">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-flat btn-xs">Tindakan</button>
                                <button type="button" class="btn btn-default btn-flat btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="<?= site_url('admin/pengguna/pengguna/edit/'. $data->kode_pengguna); ?>"><i style="color: #00c0ef" class="fa fa-edit"></i> Ubah</a></li>
                                    <li><a href="#" onclick="menyamar('<?= site_url('admin/pengguna/pengguna/menyamar/' . $data->kode_pengguna) ?>')"><i style="color: #f39c12" class="fa fa-user-secret"></i> Menyamar</a></li>
                                    <li><a href="#" onclick="hapus('<?= site_url('admin/pengguna/pengguna/hapus/' . $data->kode_pengguna) ?>')"><i style="color: #dd4b39" class="fa fa-remove"></i> Hapus</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<?= $this->session->flashdata('pesan') ?>
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

    function menyamar(url) {
        swal({
            title: '',
            text: "Anda yakin ingin menyamar sebagai pengguna ini?",
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
</script>