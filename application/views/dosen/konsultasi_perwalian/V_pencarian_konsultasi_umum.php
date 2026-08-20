<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('dosen/konsultasi_perwalian') ?>" class="btn btn-xs btn-success flat"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="box box-primary flat">
    <div class="box-header">
        <h5><b>Pencarian Mahasiswa untuk konsultasi Umum</b></h5>
        <hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" method="post" action="<?= site_url('dosen/konsultasi_perwalian/proses_pencarian_konsultasi_umum'); ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

            <div class="form-group">
                <label class="control-label col-sm-3">NIM <label class="text-danger">*</label> :</label>
                <div class="col-sm-4">
                    <input value="<?= set_value('nim') ?>" type="text" class="form-control" placeholder="Masukkan NIM Mahasiswa" name="nim" id="kata_kunci">
                    <small class="text-danger"><?= form_error('nim') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-cog"></i> Proses</button>
                </div>
            </div>
        </form>
    </div>
</div>