<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default flat">
            <div class="panel-body">
                <div class="pull-right">
                    <a href="<?= site_url('admin/jurusan/distribusi_matakuliah') ?>" class="btn btn-success btn-xs btn-flat"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php if ($data) : ?>
        <div class="box box-primary flat">
            <div class="box-header">
                <h4 class="box-title"><i class="fa fa-table"></i> Data Distribusi Matakuliah</h4>
                <div class="box-tools pull-right">
                    <a href="<?= site_url('admin/jurusan/distribusi_matakuliah/export') ?>" class="btn pull-right btn-danger btn-sm" title="Export exel"><i class="fa fa-file-excel-o"></i> Excel</a>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="demo-table" style="width: 100%">
                        <thead>
                        <tr>
                            <th>NO</th>
                            <th>NAMA DOSEN</th>
                            <th>MATAKULIAH</th>
                            <th>T</th>
                            <th>PK</th>
                            <th>PT</th>
                            <th>SEMESTER</th>
                            <th>KELAS </th>
                            <th>BEBAN</th>
                            <th>BEBAN PERJURUSAN</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php  $no=1; foreach ($data as $key) : ?>
                            <tr>
                                <td rowspan="<?= e($key['rowspan']) ?>"><?= $no++ ?>.</td>
                                <td rowspan="<?= e($key['rowspan']) ?>"><?= e($key['nama_dosen']) ?></td>
                            <?php $i=0; foreach ($key['data'] as $row) : ?>
                                <td><?= e($row->nama_matakuliah) ?></td>
                                <td style="text-align: center"><?= e($row->sks_teori) ?></td>
                                <td style="text-align: center"><?= e($row->sks_praktek) ?></td>
                                <td style="text-align: center"><?= e($row->sks_praktikum) ?></td>
                                <td style="text-align: center"><?= e($row->semester) ?> - (<?= e($row->nama_kelas) ?>)</td>
                                <td style="text-align: center"><?= e($row->jml_kelas) ?></td>
                                <td style="text-align: center"><?= e($row->beban) ?> - (<?= e($row->singkatan_program_studi) ?>)</td>
                                <?php if ($i == 0) : ?>
                                    <td rowspan="<?= e($key['rowspan']) ?>" style="text-align: center"><?= e($key['total_beban']) ?></td>
                                <?php $i++; endif; ?>
                                </tr>
                            <?php  endforeach; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php else: ?>
            <div class="callout callout-warning">
                <h4><i class="fa fa-warning"></i> Warnign</h4>

                <p>Tidak di temukan data distribusi matakuliah untuk tahun akademik tersebut</p>
            </div>
        <?php endif; ?>
    </div>
</div>
