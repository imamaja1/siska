<?= $this->session->flashdata('pesan') ?>

<div class="box box-solid flat">
    <div class="box-header">
        <h4><i class="fa fa-filter" style="color: #1b6d85"></i><strong> Filter Mahasiswa</strong></h4>
        <hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="<?= site_url('admin/pengaturan/pengaturan/semester_masuk') ?>" method="GET">
            <div class="form-group">
                <label class="control-label col-sm-2">NIM / Nama</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="search" value="<?= e($filter['search']) ?>" placeholder="Cari NIM atau nama mahasiswa">
                </div>
                <label class="control-label col-sm-2">Program Studi</label>
                <div class="col-sm-4">
                    <select class="form-control" name="prodi">
                        <option value="0">Semua Program Studi</option>
                        <?php foreach ($prodi as $p) { ?>
                            <option value="<?= (int) $p->kode_program_studi ?>" <?= $filter['prodi'] == $p->kode_program_studi ? 'selected' : '' ?>><?= e($p->nama_program_studi) ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Angkatan</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="angkatan" maxlength="2" value="<?= e($filter['angkatan']) ?>" placeholder="Contoh: 24">
                </div>
                <label class="control-label col-sm-2">Semester Masuk</label>
                <div class="col-sm-4">
                    <select class="form-control" name="semester_masuk">
                        <option value="" <?= $filter['semester_masuk'] === '' ? 'selected' : '' ?>>Semua</option>
                        <option value="GANJIL" <?= $filter['semester_masuk'] === 'GANJIL' ? 'selected' : '' ?>>Ganjil</option>
                        <option value="GENAP" <?= $filter['semester_masuk'] === 'GENAP' ? 'selected' : '' ?>>Genap</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                    <button class="btn btn-primary btn-sm flat" type="submit"><i class="fa fa-search"></i> Cari</button>
                    <a href="<?= site_url('admin/pengaturan/pengaturan/semester_masuk') ?>" class="btn btn-default btn-sm flat"><i class="fa fa-refresh"></i> Reset</a>
                    <span class="text-muted" style="margin-left: 10px;">Total: <?= (int) $total ?> mahasiswa</span>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="box box-solid flat">
    <div class="box-header">
        <h4><i class="fa fa-calendar-check-o" style="color: #1b6d85"></i><strong> Atur Semester Masuk</strong></h4>
        <hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="<?= site_url('admin/pengaturan/pengaturan/simpan_semester_masuk') ?>" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-2">NIM</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="nim" value="<?= e($filter['search']) ?>" placeholder="Masukkan NIM" required>
                </div>
                <label class="control-label col-sm-2">Semester Masuk</label>
                <div class="col-sm-2">
                    <select class="form-control" name="semester_masuk" required>
                        <option value="GENAP">Genap</option>
                        <option value="GANJIL">Ganjil</option>
                    </select>
                </div>
                <label class="control-label col-sm-1">Keterangan</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="keterangan" placeholder="Opsional">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                    <button class="btn btn-success btn-sm flat" type="submit"><i class="fa fa-save"></i> Simpan</button>
                    <span class="text-muted" style="margin-left: 10px;">Mahasiswa tanpa data di tabel dianggap masuk GANJIL.</span>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="box box-primary flat">
    <div class="box-body">
        <?php if (count($data) > 0) { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="20">No.</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Program Studi</th>
                        <th>Jenjang</th>
                        <th width="90">Semester Masuk</th>
                        <th>Keterangan</th>
                        <th>Diubah Oleh</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = (($page - 1) * $per_page) + 1; foreach ($data as $row) { ?>
                    <tr>
                        <td><?= $i++ . "." ?></td>
                        <td><?= e($row->nim) ?></td>
                        <td><?= e($row->nama_mahasiswa) ?></td>
                        <td><?= e($row->nama_program_studi) ?></td>
                        <td><?= e($row->nama_jenjang) ?></td>
                        <td>
                            <?php if ($row->semester_masuk === 'GENAP') { ?>
                                <span class="label label-warning">GENAP</span>
                            <?php } else { ?>
                                <span class="label label-success">GANJIL</span>
                            <?php } ?>
                        </td>
                        <td><?= e($row->keterangan) ?></td>
                        <td><?= e($row->diubah_oleh) ?></td>
                        <td>
                            <form action="<?= site_url('admin/pengaturan/pengaturan/simpan_semester_masuk') ?>" method="POST" style="display: inline;">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                                <input type="hidden" name="nim" value="<?= e($row->nim) ?>">
                                <?php if ($row->semester_masuk === 'GENAP') { ?>
                                    <input type="hidden" name="semester_masuk" value="GANJIL">
                                    <button type="submit" class="btn btn-default btn-xs flat" onclick="return confirm('Jadikan mahasiswa ini masuk GANJIL?')"><i class="fa fa-undo"></i> Ganjil</button>
                                <?php } else { ?>
                                    <input type="hidden" name="semester_masuk" value="GENAP">
                                    <button type="submit" class="btn btn-warning btn-xs flat" onclick="return confirm('Jadikan mahasiswa ini masuk GENAP?')"><i class="fa fa-check"></i> Genap</button>
                                <?php } ?>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <?php
        $last_page = (int) ceil($total / $per_page);
        if ($last_page > 1) {
            $query = $filter;
            unset($query['page']);
        ?>
        <ul class="pagination pagination-sm no-margin">
            <?php for ($p = 1; $p <= $last_page; $p++) { ?>
                <li class="<?= $p == $page ? 'active' : '' ?>"><a href="<?= site_url('admin/pengaturan/pengaturan/semester_masuk?' . http_build_query(array_merge($query, ['page' => $p]))) ?>"><?= $p ?></a></li>
            <?php } ?>
        </ul>
        <?php } ?>

        <?php } else { ?>
        <div class="callout callout-warning flat">
            <h4><i class="fa fa-warning"></i> Peringatan!</h4>
            <p>Data mahasiswa tidak ditemukan.</p>
        </div>
        <?php } ?>
    </div>
</div>
