<div class="box box-solid flat">
    <div class="box-body">
        <strong>Dosen Wali :</strong> <span class="badge bg-navy"><?= e($dosen_wali) ?></span>&nbsp;
        <?php if (isset($dosen_perwakilan)) : ?>
            <strong>Dosen Perwakilan :</strong> <span class="badge bg-orange"><?= e($dosen_perwakilan) ?></span>
        <?php endif; ?>
    </div>
</div>
<div class="box box-solid flat">
    <div class="box-body">
        <?php $i=1; foreach ($krs_mhs as $row) : ?>
            <a href="<?= site_url('mahasiswa/krs/old/'.$row->kode_tahun_akademik.'/'.$row->semester) ?>" class="btn bg-navy flat btn-xs"><i class="fa fa-arrow-circle-right"></i> | SEMESTER <?= $i++ ?></a>
        <?php endforeach; ?>
        <a href="<?= site_url('mahasiswa/krs/index/') ?>" class="btn bg-navy flat btn-xs"><i class="fa fa-arrow-circle-right"></i> | SEMESTER <?= $i++ ?></a>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-body table-responsive">
        <p style="text-align: center"><strong>KARTU RECANA STUDI (KRS) JENJANG <?= strtoupper($prodi->nama_program_studi) ?> (<?= strtoupper($prodi->singkatan_program_studi) ?>)</strong></p>
        <p style="text-align: center"><strong>SEMESTER <?= $tahun_akademik->semester % 2 == (0)? "GENAP" : "GANJIL" ?></strong></p>
        <form id="form-krs-mahasiswa" action="<?= site_url('mahasiswa/Krs/simpan_krs') ?>" method="post" name="krs_form">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <?php foreach ($data_matakuliah as $row): ?>

                <?php if (count($row['data']) > 0) : ?>
                    <p><strong>SEMESTER <?= $row['semester'] ?></strong></p>
                    <table class="table demo-table">
                        <thead>
                        <tr>
                            <th id="th" width="20" rowspan="2" style="padding-bottom: 25px;">NO.</th>
                            <th id="th" width="200" rowspan="2" style="padding-bottom: 25px;">KODE MK</th>
                            <th id="th" rowspan="2" style="padding-bottom: 25px;">MATAKULIAH</th>
                            <th id="th" colspan="3">SKS</th>
                            <th id="th" rowspan="2" style="padding-bottom: 25px;">B</th>
                        </tr>
                        <tr>
                            <th id="th">T</th>
                            <th id="th">PK</th>
                            <th id="th">PT</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;
                        foreach ($row['data'] as $d) { ?>
                            <tr>
                                <td align="center"><?= $i++ ?>.</td>
                                <td align="center" id="kode-matakuliah-<?= $d['kode_nama_kurikulum'] ?>"><?= e($d['kode_matakuliah']) ?></td>
                                <td ><?= e($d['nama_matakuliah']) ?></td>
                                <td align="center" width="100"><?= $d['sks_teori'] ?></td>
                                <td align="center" width="100"><?= $d['sks_praktek'] ?></td>
                                <td align="center" width="100"><?= $d['sks_praktikum'] ?></td>
                                <td align="center" width="100">
                                    <input type="checkbox" id="cek-<?= $d['id_matakuliah'] ?>" checked onclick="return false" name="baru[]" value="<?= $d['id_matakuliah'] ?>">
                                </td>
                            </tr>
                        <?php }
                        if (isset($row['pilihan'])) : ?>
                            <tr>
                                <td align="center" colspan="8"> Pilihan </td>
                            </tr>
                            <?php foreach ($row['pilihan'] as $pilih) : ?>
                                <tr>
                                    <td align="center"><?= $i++ ?>.</td>
                                    <td align="center" id="kode-matakuliah-<?= $pilih['kode_nama_kurikulum'] ?>"><?= e($pilih['kode_matakuliah']) ?></td>
                                    <td ><?= e($pilih['nama_matakuliah']) ?></td>
                                    <td align="center" width="100"><?= $pilih['sks_teori'] ?></td>
                                    <td align="center" width="100"><?= $pilih['sks_praktek'] ?></td>
                                    <td align="center" width="100"><?= $pilih['sks_praktikum'] ?></td>
                                    <td align="center" width="100">
                                        <input type="checkbox" id="cek-<?= $pilih['id_matakuliah'] ?>" checked onclick="return false" name="baru[]" value="<?= $pilih['id_matakuliah'] ?>">
                                    </td>
                                </tr>
                            <?php endforeach;
                        endif;
                        ?>

                        </tbody>
                    </table>
                <?php endif; ?>
                <hr>
            <?php endforeach; ?>
            <div id="loading">
                <a href="#" class="btn btn-danger flat" onclick="batal()"><i class="fa fa-times"></i> Batal</a>
                <button type="submit" name="submit" id="submit" class="btn btn-primary flat"><i class="fa fa-check-square-o"></i> Simpan</button>
            </div>
        </form>
        <hr>
        <h4>Keterangan</h4>
        <p><b>Status Pengambilan Matakuliah</b><br />
            <b>B</b> : Baru &nbsp;&nbsp;&nbsp; <b>U</b> : Ulang<br />
            <b>Jenis SKS Matakuliah</b><br />
            <b>T</b> : Teori &nbsp;&nbsp;&nbsp; <b>PK</b> : Praktek &nbsp;&nbsp;&nbsp; <b>PT</b> : Praktikum<br />
            
        </p>
    </div>
</div>
<script type="text/javascript">

    $('#form-krs-mahasiswa').bind('submit', function (e) {
        var button = $('#loading');
        // Disable the submit button while evaluating if the form should be submitted
        button.html('<button class="btn btn-default btn-sm flat" disabled><i class="fa fa-refresh fa-spin"></i> Permintaan sedang diproses..</button>');
        var valid = true;

        // Do stuff (validations, etc) here and set
        // "valid" to false if the validation fails

        if (!valid) {
            // Prevent form from submitting if validation failed
            e.preventDefault();

            // Reactivate the button if the form was not submitted
            button.html('<button class="btn btn-default btn-sm flat" disabled><i class="fa fa-refresh fa-spin"></i> Permintaan sedang diproses..</button>');

        }
    });
</script>
