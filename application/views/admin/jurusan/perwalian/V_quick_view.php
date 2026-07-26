<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/jurusan/perwalian/rekap_dosen_wali') ?>" class="btn btn-success btn-sm btn-flat"><i class="fa fa-file-excel-o"></i> Rekap Excel</a>
        <div class="pull-right">
            <a href="<?= site_url('admin/jurusan/perwalian') ?>" class="btn btn-danger btn-sm btn-flat"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
</div>
<div class="row">
<div class="col-lg-6 col-sm-6">
    <div class="box box-primary flat">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table demo-table data-table">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 3%">NO</th>
                            <th style="text-align: center">NAMA DOSEN</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no=1; foreach ($data as $row) : ?>
                        <tr>
                            <td style="text-align: center;"><?= $no++ ?>.</td>
                            <td style="text-align: left;"><?= e($row->nama_dosen) ?>&nbsp;<span class="badge bg-success"><?= e($row->jml) ?></span>(<?= e($row->singkatan_program_studi) ?>)</td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

