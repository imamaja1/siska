<?= $this->session->flashdata('pesan') ?>
<div class="box box-solid flat">
    <div class="box-header">
        <h4><i class="fa fa-filter" style="color: #1b6d85"></i><strong> Filter KRS KPAT</strong></h4>
        <hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="<?= site_url('admin/pengaturan/pengaturan/krs_kpat') ?>" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-2">Tahun Akademik</label>
                <div class="col-sm-3">
                    <select required class="form-control" name="tahun_akademik">
                        <option value="" selected disabled>Pilih</option>
                        <?php foreach ($tahun as $row) { ?>
                            <option value="<?= e($row->tahun_akademik) ?>" <?= (isset($filter['tahun_akademik']) && $filter['tahun_akademik'] == $row->tahun_akademik) ? 'selected' : '' ?>><?= e($row->tahun_akademik) ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Semester</label>
                <div class="col-sm-3">
                    <select required class="form-control" name="semester">
                        <option value="" selected disabled>Pilih</option>
                        <option value="1" <?= (isset($filter['semester']) && $filter['semester'] === '1') ? 'selected' : '' ?>>Ganjil</option>
                        <option value="0" <?= (isset($filter['semester']) && $filter['semester'] === '0') ? 'selected' : '' ?>>Genap</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Program Studi</label>
                <div class="col-sm-3">
                    <select required class="form-control select2" name="prodi">
                        <option value="" selected disabled>Pilih</option>
                        <?php foreach ($nama_jurusan as $row) { ?>
                            <option value="<?= e($row->kode_program_studi) ?>" <?= (isset($filter['prodi']) && $filter['prodi'] == $row->kode_program_studi) ? 'selected' : '' ?>><?= e($row->nama_program_studi) ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2"></div>
                <div class="col-sm-3">
                    <button class="btn btn-primary btn-sm flat" type="submit" name="proses" value="1"><i class="fa fa-search"></i> Proses</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if ($data !== null): ?>
<?php if (count($data) > 0): ?>
<div class="box box-primary flat">
    <div class="box-body">
        <div class="table-responsive">
            <table class="table data-table">
                <thead>
                    <tr>
                        <th width="20">No.</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Tahun Akademik</th>
                        <th width="240">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($data as $row) { ?>
                    <tr>
                        <td><?= $i++ . "." ?></td>
                        <td><?= e($row->nim) ?></td>
                        <td><?= e($row->nama_mahasiswa) ?></td>
                        <td>
                            <a href="#" class="ta-pindah" data-kode="<?= (int) $row->kode_krs ?>" data-kode-ta="<?= (int) $row->kode_tahun_akademik ?>" data-ta="<?= e($row->tahun_akademik) ?> - <?= $row->semester == 0 ? 'Genap' : 'Ganjil' ?>" title="Klik untuk pindah tahun akademik">
                                <?= e($row->tahun_akademik) ?> - <?= $row->semester == 0 ? 'Genap' : 'Ganjil' ?>
                            </a>
                        </td>
                        <td>
                            <div class="btn-group btn-group-xs">
                                <a href="<?= site_url('admin/akademik/kpat/krs/edit/' . $row->kode_krs . '/' . $row->nim) ?>" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php else : ?>
<div class="callout callout-warning flat">
    <h4><i class="fa fa-warning"></i> Peringatan!</h4>
    <p>Data KRS KPAT tidak ditemukan.</p>
</div>
<?php endif ?>
<?php endif ?>

<!--modal pindah tahun akademik-->
<div class="modal fade" id="modal-pindah-ta" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Pindah Tahun Akademik KRS KPAT</b></h4>
            </div>
            <form id="form-pindah-ta">
                <div class="modal-body">
                    <input type="hidden" name="kode_krs" id="pindah-kode-krs">
                    <div class="form-group">
                        <label>Tahun Akademik Saat Ini</label>
                        <input type="text" id="pindah-ta-sekarang" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Tahun Akademik Tujuan</label>
                        <select required class="form-control" name="kode_tahun_akademik" id="pindah-kode-ta">
                            <option value="" selected disabled>Pilih</option>
                            <?php foreach ($tahun_akademik_list as $ta) { ?>
                                <option value="<?= e($ta->kode_tahun_akademik) ?>"><?= e($ta->tahun_akademik) ?> - <?= $ta->semester == 0 ? 'Genap' : 'Ganjil' ?></option>
                            <?php } ?>
                        </select>
                        <small class="text-muted">Semester mahasiswa akan dihitung ulang otomatis sesuai tahun akademik tujuan.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                    <button type="submit" class="btn btn-success flat"><i class="fa fa-exchange"></i> Pindahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click', '.ta-pindah', function (e) {
        e.preventDefault();
        $('#pindah-kode-krs').val($(this).data('kode'));
        $('#pindah-ta-sekarang').val($(this).data('ta'));
        $('#pindah-kode-ta').val('');
        $('#pindah-kode-ta option').prop('disabled', false);
        $('#pindah-kode-ta option[value="' + $(this).attr('data-kode-ta') + '"]').prop('disabled', true);
        $('#modal-pindah-ta').modal('show');
    });

    $(document).on('submit', '#form-pindah-ta', function (e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: "<?= site_url('admin/pengaturan/pengaturan/pindah_tahun_akademik') ?>",
            type: "POST",
            data: form.serialize(),
            dataType: "json",
            success: function (res) {
                if (res.status) {
                    swal("Berhasil!", res.message, "success");
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                } else {
                    swal("Gagal!", res.message, "error");
                }
            },
            error: function () {
                swal("Gagal!", "Terjadi kesalahan saat memindahkan tahun akademik", "error");
            }
        });
    });
</script>

