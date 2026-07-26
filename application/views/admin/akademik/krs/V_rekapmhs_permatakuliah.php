
<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/krs'); ?>" class="btn btn-xs btn-success flat"><i class="fa fa-arrow-left"></i> Kembali</a>        
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-body"><br>
        <form class="form-horizontal" method="POST" action="<?= site_url('admin/akademik/krs/get_rekapitulasi_mahasiswa_per_matakuliah'); ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-2">Jurusan <lable class="text-danger">*</lable> :</label>
                <div class="col-sm-3">
                    <select class="form-control" name="kode_program_studi" id="kode_jurusan_jenjang">
                        <option value="" disabled selected>Pilih Jurusan</option>
                        <?php foreach ($program_studi as $row) { ?>
                            <option <?= set_select('kode_program_studi', $row->kode_program_studi) ?> value="<?= e($row->kode_program_studi) ?>"><?= e($row->nama_program_studi) ?></option>
                        <?php } ?>
                    </select>
                    <small class="text-danger"><?= form_error('kode_program_studi'); ?></small>
                </div>
                <button type="submit" class="btn btn-primary flat"><i class="fa fa-cog"></i> Proses</button>
            </div>    
        </form>
    </div>
</div>
<div class="box box-primary flat" <?= $hidden; ?>>
    <div class="box-body">
        <?php if (count($data) > 0): ?>
            <table class="table demo-table">
                <thead>
                    <tr>
                        <th id="th">NO.</th>
                        <th id="th">KODE MATAKULIAH</th>
                        <th id="th">NAMA MATAKULIAH</th>
                        <th id="th">PRAKTUKUM</th>
                        <th id="th">JUMLAH MAHASISWA</th>
                        <th id="th">TINDAKAN</th>
                    </tr>
                </thead>
                <?php
                $no = 1;
                foreach ($data as $row) {
                    ?>
                    <tr>
                        <td align="center"><?= $no++ ?>.</td>
                        <td align="center"><?= e($row->kode_matakuliah) ?></td>
                        <td><?= e($row->nama_matakuliah) ?></td>
                        <td align="center"><?= e($row->sks_praktikum != '0' ? "<span class='badge bg-green'>Avaible - ".$row->sks_praktikum." SKS</span>" : "") ?></td>
                        <td style="text-align: center"><?= e($row->jml) ?></td>
                        <td align="center">
                            <a class="btn btn-xs btn-info" href="<?= site_url('admin/akademik/krs/download/' . $row->id_matakuliah); ?>"><i class="fa fa-download"></i> Download</a>
                            <a class="btn btn-xs btn-warning" target="_blank" href="<?= site_url('admin/akademik/krs/lihat_rekap/' . $row->id_matakuliah); ?>"><i class="fa fa-eye"></i> Lihat</a>
                        </td>
                    </tr>
                <?php } ?>

            </table>
        <?php else: ?>
            <p class="alert">Tidak ada data matakuliah dengan Jurusan tersebut.</p>
        <?php endif; ?>
    </div>
</div>
