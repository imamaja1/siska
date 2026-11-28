<?php $this->load->view('admin/mbkm/partial/V_header'); ?>

<!-- Card 1: Filter / Search Data Mahasiswa MBKM -->
<div class="box box-primary flat">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-filter"></i> Filter Mahasiswa MBKM</h4>
    </div>
    <div class="box-body">
        <form action="<?= site_url('admin/mbkm/daftar') ?>" method="get">
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="kode_tahun_akademik">Tahun Akademik</label>
                        <select name="ta" id="kode_tahun_akademik" class="form-control">
                            <?php foreach ($tahun_akademik as $value): ?>
                                <option value='<?= e($value->kode_tahun_akademik) ?>' <?= ($value->kode_tahun_akademik == $kode_tahun_akademik) ? 'selected' : '' ?>>Semester <?= $value->semester == '1' ? 'Ganjil Tahun '.e($value->tahun_akademik) : 'Genap Tahun '.e($value->tahun_akademik) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="kode_program_studi">Program Studi</label>
                        <select name="prodi" id="kode_program_studi" class="form-control select2">
                            <option value="">Semua</option>
                            <?php foreach ($prodi as $value): ?>
                                <option value="<?= e($value->kode_program_studi) ?>" <?= ($kode_program_studi_filter == $value->kode_program_studi) ? 'selected' : '' ?>><?= e($value->singkatan_program_studi) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="hidden-xs hidden-sm">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-search"></i> Filter</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Card 2: Insert Mahasiswa MBKM -->
<div class="box box-success flat">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-user-plus"></i> Insert Mahasiswa MBKM</h4>
        <div class="box-tools pull-right">
            <span class="text-muted">Tahun Akademik Tujuan: <strong id="label-ta-mbkm"></strong></span>
        </div>
    </div>
    <div class="box-body">
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-sm-9 col-md-10">
                <input type="text" class="form-control" id="search-nim" placeholder="Masukkan NIM Mahasiswa untuk ditambahkan ke MBKM">
            </div>
            <div class="col-sm-3 col-md-2">
                <button type="button" class="btn btn-success btn-block" onclick="Search()"><i class="fa fa-search"></i> Search</button>
            </div>
        </div>
        <div id="search-mhs">
            <div class="alert alert-info text-center" role="alert" style="margin-bottom: 0;">
                Masukkan NIM mahasiswa lalu klik <strong>Search</strong> untuk memverifikasi dan menambahkan ke MBKM.
            </div>
        </div>
    </div>
</div>

<!-- Card 3: Tabel Data Mahasiswa MBKM -->
<div class="box box-primary flat">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-users"></i> Data Mahasiswa MBKM</h4>
        <div class="box-tools pull-right">
            <a href="<?= site_url('admin/mbkm/daftar/log') ?>" class="btn btn-default btn-xs"><i class="fa fa-history"></i> Log Aktivitas MBKM</a>
        </div>
    </div>
    <div class="box-body">
        <?php if (!empty($mahasiswa)): ?>
        <div class="table-responsive">
            <table class="table demo-table data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Prodi</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mahasiswa as $key => $value): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= !empty($value->nim) ? e($value->nim) : '-' ?></td>
                            <td><?= !empty($value->nama_mahasiswa) ? e($value->nama_mahasiswa) : '-' ?></td>
                            <td><?= !empty($value->nama_program_studi) ? e($value->nama_program_studi) : '-' ?></td>
                            <td>
                                <a href="<?= e(site_url('admin/mbkm/daftar/nilai/'.rawurlencode($value->id_fix).'/'.rawurlencode($kode_tahun_akademik))) ?>" class="btn btn-success btn-sm">Penilaian</a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="hapus(<?= e($value->id_fix) ?>)">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p class="text-muted">Tidak ada data mahasiswa MBKM.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    var loader = "<p class='text-center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt='Loading...'></p>";

    function syncLabelTaMbkm() {
        var taText = $('#kode_tahun_akademik option:selected').text();
        $('#label-ta-mbkm').text(taText);
    }

    $(document).ready(function () {
        syncLabelTaMbkm();
        $('#kode_tahun_akademik').on('change', syncLabelTaMbkm);
    });

    $('#search-nim').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            Search();
        }
    });

    function Search() {
        var nim = $.trim($('#search-nim').val());
        var ta = $('#kode_tahun_akademik').val();
        if (!nim) {
            $('#search-mhs').html('<div class="alert alert-warning text-center" role="alert" style="margin-bottom: 0;">Silakan masukkan NIM terlebih dahulu.</div>');
            return;
        }
        $.ajax({
            url: '<?= site_url('admin/mbkm/daftar/search_mahasiswa') ?>/' + encodeURIComponent(nim) + '/' + encodeURIComponent(ta),
            type: 'get',
            beforeSend: function () {
                $('#search-mhs').html(loader);
            },
            success: function (data) {
                $('#search-mhs').html(data);
            },
            error: function () {
                $('#search-mhs').html('<div class="alert alert-danger text-center" role="alert" style="margin-bottom: 0;">Gagal mencari data mahasiswa.</div>');
            }
        });
    }

    function update(nim) {
        var ta = $('#kode_tahun_akademik').val();
        $.ajax({
            url: '<?= site_url('admin/mbkm/daftar/tambah_mhs_mbkm') ?>/' + encodeURIComponent(nim) + '/' + encodeURIComponent(ta),
            type: 'post',
            success: function (data) {
                var res = {};
                try {
                    res = typeof data === 'string' ? JSON.parse(data) : data;
                } catch (e) {
                    res = {status: 0};
                }
                if (res.status == 1) {
                    swal({
                        title: '',
                        html: 'Mahasiswa berhasil ditambahkan ke MBKM',
                        type: 'success',
                        confirmButtonColor: '#3085d6'
                    }).then(function () {
                        location.reload();
                    }, function () {
                        location.reload();
                    });
                } else if (res.status == 2) {
                    swal({
                        title: '',
                        html: 'Mahasiswa sudah terdaftar pada tahun akademik ini',
                        type: 'info',
                        confirmButtonColor: '#3085d6'
                    });
                    Search();
                } else {
                    swal({
                        title: '',
                        html: res.msg || 'Gagal menambahkan mahasiswa MBKM',
                        type: 'error',
                        confirmButtonColor: '#d33'
                    });
                }
            },
            error: function () {
                swal({
                    title: '',
                    html: 'Terjadi kesalahan saat menyimpan data',
                    type: 'error',
                    confirmButtonColor: '#d33'
                });
            }
        });
    }

    function hapus(id) {
        if (confirm('Anda yakin ingin menghapus data ini?')) {
            $.ajax({
                url: '<?= site_url('admin/mbkm/daftar/hapus_mhs_mbkm') ?>/' + encodeURIComponent(id),
                type: 'post',
                success: function () {
                    location.reload();
                },
                error: function () {
                    alert('Gagal menghapus data mahasiswa MBKM.');
                }
            });
        }
    }
</script>
