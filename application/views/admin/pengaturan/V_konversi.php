<?= $this->session->flashdata('pesan') ?>
<div class="box box-solid flat">
    <div class="box-header">
        <h4><i class="fa fa-filter" style="color: #1b6d85"></i><strong> Filter Konversi</strong></h4>
        <hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="<?= site_url('admin/pengaturan/pengaturan/konversi') ?>" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-2">Tahun Akademik</label>
                <div class="col-sm-4">
                    <select class="form-control" name="kode_tahun_akademik">
                        <option value="">Semua Tahun Akademik</option>
                        <?php foreach ($tahun_akademik_list as $ta) { ?>
                            <option value="<?= e($ta->kode_tahun_akademik) ?>" <?= (isset($filter['kode_tahun_akademik']) && $filter['kode_tahun_akademik'] == $ta->kode_tahun_akademik) ? 'selected' : '' ?>><?= e($ta->tahun_akademik) ?> - <?= $ta->semester == 0 ? 'Genap' : 'Ganjil' ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2"></div>
                <div class="col-sm-4">
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
                        <th>Program Studi</th>
                        <th>Tahun Akademik</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($data as $row) { ?>
                    <tr>
                        <td><?= $i++ . "." ?></td>
                        <td><?= e($row->nim) ?></td>
                        <td><?= e($row->nama_mahasiswa) ?></td>
                        <td><?= e($row->nama_program_studi) ?></td>
                        <td><?= e($row->tahun_akademik) ?> - <?= $row->semester == 0 ? 'Genap' : 'Ganjil' ?></td>
                        <td>
                            <a href="<?= site_url('admin/akademik/konversi/edit/' . $row->nim) ?>" class="btn btn-primary btn-xs flat"><i class="fa fa-edit"></i> Edit</a>
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
    <p>Data konversi tidak ditemukan.</p>
</div>
<?php endif ?>
<?php endif ?>
