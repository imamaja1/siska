<div class="box box-primary flat">
    <div class="box-header">
        <h5><b>Pencarian data nilai KKP Mahasiswa</b></h5><hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" method="post" action="<?= site_url('admin/akademik/kkp/search_process'); ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-3">Pencarian Berdasarkan <label class="text-danger">*</label> :</label>
                <div class="col-sm-5">
                    <label><input type="radio" name="berdasarkan" value="nim" <?= set_radio('berdasarkan', 'nim') ?>> NIM (Nomor Induk Mahasiswa)</label>
                    &nbsp;&nbsp;
                    <label><input type="radio" name="berdasarkan" value="nama" <?= set_radio('berdasarkan', 'nama') ?>> Nama Mahasiswa </label>
                    <br>
                    <small class="text-danger"><?= form_error('berdasarkan'); ?></small>
                </div>

            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Masukkan Kata Kunci <label class="text-danger">*</label> :</label>
                <div class="col-sm-4">
                    <input value="<?= set_value('kata_kunci') ?>" type="text" class="form-control" placeholder="Ketik Kata Kunci disini" name="kata_kunci" id="kata_kunci">
                    <small class="text-danger"><?= form_error('kata_kunci') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-cog"></i>  Proses</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-body">
        <?php if (count($data) > 0) : ?>
            <div class="table-responsive">
                <table class="table demo-table find-nilai-kkp">
                    <thead>
                    <tr>
                        <th style="text-align: center; width: 3%">NO.</th>
                        <th style="text-align: center;">NIM</th>
                        <th style="text-align: center;">NAMA MAHSISWA</th>
                        <th style="text-align: center;">NILAI AKHIR</th>
                        <!--                        <th style="text-align: center;">AKSI</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=1; foreach ($data as $row) : ?>
                        <tr>
                            <td style="text-align: center"><?= $no++ ?>.</td>
                            <td style="display: none"><?= e($row->kode_krs_detail) ?></td>
                            <td style="text-align: center"><?= e($row->nim) ?></td>
                            <td style="text-align: left"><?= e($row->nama_mahasiswa) ?></td>
                            <td style="text-align: center"><?= number_format($row->nilai_akhir,2)?></td>
                            <!--                            <td style="text-align: center">-->
                            <!--                                <a href="#" class="btn btn-success btn-xs"><i class="fa fa-edit"></i> Update</a>-->
                            <!--                            </td>-->
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="message"><div class = "alert alert-warning alert-dismissible flat"><?= e($message) ?></p>
        <?php endif; ?>
    </div>
</div>
<script>
    if ($('.find-nilai-kkp').length && $('.find-nilai-kkp').is('table')) {
    $('.find-nilai-kkp').Tabledit({
        url: "<?= site_url('admin/akademik/perubahan/Semester_ini/ubah_krs_nilai') ?>",
        hideIdentifier: true,
        deleteButton: false,
        buttons: {
            confirm: {
                class: 'btn btn-sm btn-danger',
                html: 'Confirm'
            },
            save: {
                class: 'btn btn-sm btn-success',
                html: 'Save'
            }
        },
        columns: {
            identifier: [1, 'kode_krs_detail'],
            editable: [[4, 'edit_nilai_akhir']],
        }

    });
    }
</script>