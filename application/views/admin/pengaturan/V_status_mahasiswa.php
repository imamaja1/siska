<?= $this->session->flashdata('pesan') ?>
<div class="box box-solid flat">
    <div class="box-header">
        <h4><i class="fa fa-search" style="color: #1b6d85"></i><strong> Filter Status Mahasiswa</strong></h4>
        <hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="<?= site_url('admin/pengaturan/pengaturan/status_mahasiswa') ?>" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-2">NIM</label>
                <div class="col-sm-4">
                    <input type="text" name="nim" class="form-control" placeholder="Masukkan NIM Mahasiswa" value="<?= e($nim) ?>" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2"></div>
                <div class="col-sm-4">
                    <button class="btn btn-primary btn-sm flat" type="submit" name="proses" value="1"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if ($data !== null): ?>
<div class="box box-primary flat">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-user" style="color: #1b6d85"></i><strong> Data Mahasiswa</strong></h4>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="<?= site_url('admin/pengaturan/pengaturan/status_mahasiswa') ?>" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <input type="hidden" name="nim" value="<?= e($data->nim) ?>">

            <div class="form-group">
                <label class="control-label col-sm-2">NIM :</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" value="<?= e($data->nim) ?>" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Nama :</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" value="<?= e($data->nama_mahasiswa) ?>" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Program Studi :</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" value="<?= e($data->nama_program_studi) ?>" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Status Pendaftar :</label>
                <div class="col-sm-5">
                    <select name="status_pendaftaran" class="form-control">
                        <option value="B" <?= $data->status_pendaftaran == 'B' ? 'selected' : '' ?>>Baru</option>
                        <option value="T" <?= $data->status_pendaftaran == 'T' ? 'selected' : '' ?>>Transfer</option>
                        <option value="L" <?= $data->status_pendaftaran == 'L' ? 'selected' : '' ?>>Lanjut</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2"></div>
                <div class="col-sm-5">
                    <button type="submit" name="simpan" value="1" class="btn btn-success flat"><i class="fa fa-save"></i> Simpan Status Pendaftaran</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
