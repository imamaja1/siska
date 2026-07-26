<?php
$flashmessage = $this->session->flashdata('message');
echo isset($flashmessage) ? $flashmessage : '';
?>

<div class="box box-primary flat">
    <div class="box-header">
        <h5><b>Edit Data Kompetensi : <?= $data_mahasiswa->nim . ' - ' . $data_mahasiswa->nama_mahasiswa ?></b></h5>
        <hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" method="post"
              action="<?= site_url('admin/akademik/kompetensi/proses_update'); ?>">
            <div class="form-group">
                <label class="control-label col-sm-3">NIM</label>
                <div class="col-sm-4">
                    <input value="<?= $data_mahasiswa->nim ?>" type="text" class="form-control" name="nim"
                           id="kata_kunci" readonly>
                    <input value="<?= $data_mahasiswa->kode_kompetensi_mahasiswa ?>" type="hidden" class="form-control" name="kode_kompetensi_mahasiswa"
                           id="kata_kunci" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Kompetensi</label>
                <div class="col-sm-4">
                    <select name="kode_kompetensi" class="form-control">
                        <?php foreach ($kompetensi_prodi as $row): ?>
                            <option <?= ($data_mahasiswa->kode_kompetensi == $row->kode_kompetensi) ? 'selected' : '' ?>
                                    value="<?= $row->kode_kompetensi ?>"><?= $row->nama_kompetensi ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-cog"></i> Simpan Perubahan</button>
                    <a href="<?= site_url('admin/akademik/kompetensi'); ?>" class="btn btn-default flat"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
