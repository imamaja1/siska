<div class="box box-primary flat">
    <div class="box-header">
        <h5><b>Pencarian Mahasiswa</b></h5>
    </div>
    <div class="box-body">
        <form method="get" class="form-inline">
            <div class="form-group">
                <select name="prodi" class="form-control">
                    <option value="">Semua Prodi</option>
                    <?php foreach ($list_prodi as $p): ?>
                        <option value="<?= $p->kode_program_studi ?>" <?= $prodi_filter == $p->kode_program_studi ? 'selected' : '' ?>><?= $p->nama_program_studi ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <select name="angkatan" class="form-control">
                    <option value="">Semua Angkatan</option>
                    <?php foreach ($list_angkatan as $a): ?>
                        <option value="<?= $a->angkatan ?>" <?= $angkatan_filter == $a->angkatan ? 'selected' : '' ?>>20<?= $a->angkatan ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="q" class="form-control" placeholder="Cari nama..." value="<?= $this->input->get('q') ?>">
            </div>
            <button type="submit" class="btn btn-primary flat"><i class="fa fa-search"></i> Cari</button>
            <a href="<?= site_url('admin/impersonasi_mahasiswa') ?>" class="btn btn-default flat"><i class="fa fa-refresh"></i> Reset</a>
        </form>
    </div>
</div>

<div class="box box-primary flat">
    <div class="box-header">
        <div class="pull-right"><?= isset($pagination) ? $pagination : '' ?></div>
        <h4>Daftar Mahasiswa</h4>
    </div>
    <div class="box-body table-responsive">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Program Studi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1 + ($this->uri->segment(4) ?: 0); foreach ($mahasiswa as $row): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $row->nim ?></td>
                    <td style="text-align: left;"><?= $row->nama_mahasiswa ?></td>
                    <td><?= isset($row->nama_program_studi) ? $row->nama_program_studi : '-' ?></td>
                    <td>
                        <a href="<?= site_url('admin/impersonasi_mahasiswa/menyamar/' . $row->nim) ?>" class="btn btn-warning btn-xs flat" onclick="return confirm('Menyamar sebagai <?= $row->nama_mahasiswa ?>?')">
                            <i class="fa fa-user-secret"></i> Menyamar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (empty($mahasiswa)): ?>
            <p class="text-center">Data tidak ditemukan</p>
        <?php endif; ?>
    </div>
</div>
