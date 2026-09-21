<?= $this->session->flashdata('info') ? $this->session->flashdata('info') : '' ?>
<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="box box-solid flat">
            <div class="box-body">
                <p class="text-muted" style="margin:0;">
                    <i class="fa fa-info-circle"></i> Unggah kop (letterhead) khusus untuk tiap program studi.
                    Kop ini dipakai pada cetak <b>KHS</b>, <b>KRS</b>, dan <b>Petikan Nilai</b>.
                    Jika dibiarkan kosong, akan digunakan kop default fakultas.
                    <br><i class="fa fa-file-image-o"></i> Format: <b>PNG / JPG</b>, maksimal <b>5 MB</b>.</p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="box box-primary flat">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-image"></i> Kop Prodi</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table demo-table data-table">
                        <thead>
                        <tr>
                            <th style="width:3%; text-align:center">NO.</th>
                            <th style="text-align:center">KODE</th>
                            <th>PROGRAM STUDI</th>
                            <th>FAKULTAS</th>
                            <th style="text-align:center">KOP SAAT INI</th>
                            <th style="text-align:center">AKSI</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; foreach ($prodi as $row) : ?>
                            <?php $file = isset($kop[$row->kode_program_studi]) ? $kop[$row->kode_program_studi] : null; ?>
                            <tr>
                                <td style="text-align:center"><?= $no++ ?>.</td>
                                <td style="text-align:center"><?= e($row->kode_program_studi) ?></td>
                                <td><?= e($row->nama_program_studi) ?> <span class="text-muted">(<?= e($row->singkatan_program_studi) ?>)</span></td>
                                <td><?= e($row->nama_fakultas) ?></td>
                                <td style="text-align:center">
                                    <?php if ($file) : ?>
                                        <img src="<?= e(base_url('assets/gambar/kop/' . rawurlencode($file))) ?>" style="height:60px" alt="">
                                        <div class="text-muted" style="font-size:11px">
                                            <?= e($file) ?> &middot;
                                            <a href="<?= e(base_url('assets/gambar/kop/' . rawurlencode($file))) ?>" target="_blank">Lihat</a>
                                        </div>
                                    <?php else : ?>
                                        <span class="text-muted">Default fakultas</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:center">
                                    <input type="file" accept="image/png,image/jpeg" id="foto-<?= e($row->kode_program_studi) ?>" style="display:none" onchange="upload_kop('<?= e($row->kode_program_studi) ?>', this)">
                                    <button type="button" class="btn btn-primary btn-xs" onclick="cot('<?= e($row->kode_program_studi) ?>')"><i class="fa fa-upload"></i> Upload</button>
                                    <?php if ($file) : ?>
                                        <button type="button" class="btn btn-danger btn-xs" onclick="reset_kop('<?= e($row->kode_program_studi) ?>')"><i class="fa fa-times"></i> Reset</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function cot(kode) {
        $('#foto-' + kode).trigger('click');
    }

    function upload_kop(kode, input) {
        if (!input.files || !input.files[0]) return;
        var form = new FormData();
        form.append('foto', input.files[0]);
        $.ajax({
            url: "<?= site_url('admin/akademik/kop_prodi/upload') ?>/" + kode,
            type: 'post',
            data: form,
            processData: false,
            contentType: false,
            cache: false,
            success: function (res) {
                var obj = JSON.parse(res);
                if (obj.status === true) {
                    swal('Berhasil', obj.msg, 'success').then(function () {
                        location.reload();
                    });
                } else {
                    swal('Gagal', obj.msg, 'error');
                    input.value = '';
                }
            },
            error: function () {
                swal('Gagal', 'Terjadi kesalahan saat upload', 'error');
                input.value = '';
            }
        });
    }

    function reset_kop(kode) {
        swal({
            title: 'Reset kop prodi?',
            text: 'Kop prodi ini akan dihapus dan kembali menggunakan kop default fakultas.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            $.ajax({
                url: "<?= site_url('admin/akademik/kop_prodi/reset') ?>/" + kode,
                type: 'post',
                success: function (res) {
                    var obj = JSON.parse(res);
                    if (obj.status === true) {
                        swal('Berhasil', obj.msg, 'success').then(function () {
                            location.reload();
                        });
                    } else {
                        swal('Gagal', obj.msg, 'error');
                    }
                },
                error: function () {
                    swal('Gagal', 'Terjadi kesalahan', 'error');
                }
            });
        });
    }
</script>
