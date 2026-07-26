<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/kkp'); ?>" class="btn-success btn-sm flat"><i class="fa fa-arrow-left"></i> Kembali</a>
        <div class="pull-right">
        <?= $halaman ?>
        </div>
    </div>
</div>
<div class="box box-primary">
    <div class="box-body">
        <?php if ($jumlah_data > 0)  : ?>
            <div class="table-responsive">
                <table class="table demo-table nilai-kkp">
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
    $('.nilai-kkp').Tabledit({
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
</script>
