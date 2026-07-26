<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <h4><span class="text-warning"><i class="fa fa-book"></i></span> Konsentrasi</h4>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped data-table">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Konsentrasi</th>
                            <th>Singkatan</th>
                            <th>Program Studi</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($lists as $key => $row) : ?>
                            <tr>
                                <td><?= $key + 1 ?>.</td>
                                <td><?= e($row->nama_kompetensi) ?></td>
                                <td><?= e($row->singkatan_kompetensi) ?></td>
                                <td><?= e($row->nama_program_studi) ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= site_url('admin/jurusan/konsentrasi/matakuliah_konsentrasi/'.$row->kode_kompetensi) ?>" class="btn btn-danger btn-sm"  title="Matakuliah Konsentrasi"><i class="fa fa-file-text"></i> Matakuliah</a>
                                    </div>
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

