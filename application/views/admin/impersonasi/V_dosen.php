<div class="box box-primary flat">
    <div class="box-header">
        <h5><b>Pencarian Dosen</b></h5>
    </div>
    <div class="box-body">
        <form method="get" class="form-inline">
            <div class="form-group">
                <select name="prodi" class="form-control">
                    <option value="">Semua Prodi</option>
                    <?php foreach ($list_prodi as $p): ?>
                        <option value="<?= e($p->kode_program_studi) ?>" <?= $prodi_filter == $p->kode_program_studi ? 'selected' : '' ?>><?= e($p->nama_program_studi) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="q" class="form-control" placeholder="Cari nama dosen..." value="<?= e($this->input->get('q')) ?>">
            </div>
            <button type="submit" class="btn btn-primary flat"><i class="fa fa-search"></i> Cari</button>
            <a href="<?= site_url('admin/impersonasi_dosen') ?>" class="btn btn-default flat"><i class="fa fa-refresh"></i> Reset</a>
        </form>
    </div>
</div>

<div class="box box-primary flat">
    <div class="box-header">
        <div class="pull-right"><?= isset($pagination) ? $pagination : '' ?></div>
        <h4>Daftar Dosen</h4>
    </div>
    <div class="box-body table-responsive">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Dosen</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($dosen as $row): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td style="text-align: left;"><?= e($row->nama_dosen) ?></td>
                    <td>
                        <a href="<?= site_url('admin/impersonasi_dosen/menyamar/' . $row->kode_dosen) ?>" class="btn btn-warning btn-xs flat" onclick="return confirm('Menyamar sebagai <?= e($row->nama_dosen) ?>?')">
                            <i class="fa fa-user-secret"></i> Menyamar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
