<div class="box box-solid flat">
    <div class="box-body">
        <strong>Dosen Wali :</strong> <span class="badge bg-navy"><?= e($dosen_wali) ?></span>&nbsp;
        <?php if (isset($dosen_perwakilan)) : ?>
            <strong>Dosen Perwakilan :</strong> <span class="badge bg-orange"><?= e($dosen_perwakilan) ?></span>
        <?php endif; ?>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-body">
        <p style="text-align: center"><strong>KARTU RECANA STUDI (KRS) JENJANG <?= strtoupper(e($prodi->nama_program_studi)) ?> (<?= strtoupper(e($prodi->singkatan_program_studi)) ?>)</strong></p>
        <p style="text-align: center"><strong>SEMESTER <?= $tahun_akademik->semester % 2 == (0)? "GENAP" : "GANJIL" ?></strong></p>
        <form id="form-krs-mahasiswa" action="<?= site_url('mahasiswa/Krs/add_one') ?>" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <?php if (count($matakuliah_awal) > 0) : ?>
                    <div class="table-responsive">
                    <table class="table demo-table">
                        <thead>
                        <tr>
                            <th id="th" width="20" rowspan="2" style="padding-bottom: 25px;">NO.</th>
                            <th id="th" width="200" rowspan="2" style="padding-bottom: 25px;">KODE MK</th>
                            <th id="th" rowspan="2" style="padding-bottom: 25px;">MATAKULIAH</th>
                            <th id="th" colspan="3">SKS</th>
                            <th id="th" rowspan="2" style="padding-bottom: 25px;">B</th>
                            <th id="th" rowspan="2" style="padding-bottom: 25px;">U</th>
                        </tr>
                        <tr>
                            <th id="th">T</th>
                            <th id="th">PK</th>
                            <th id="th">PT</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;
                        foreach ($matakuliah_awal as $row) : ?>
                            <tr>
                                <td align="center"><?= $i++ ?>.</td>
                                <td style="text-align: center;"><?= e($row->kode_matakuliah) ?></td>
                                <td ><?= e($row->nama_matakuliah) ?></td>
                                <td style="text-align: center;" width="100"><?= $row->sks_teori ?></td>
                                <td style="text-align: center;" width="100"><?= $row->sks_praktek ?></td>
                                <td style="text-align: center;" width="100"><?= $row->sks_praktikum ?></td>
                                <td width="100" align="center">
                                    <input type="checkbox" name="id_matakuliah[]" value="<?= $row->id_matakuliah ?>" checked onclick="return false">
                                </td>
                                <td width="100"></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                <?php else : ?>
                    <p class="alert alert-warning flat"><strong>Data belum ada, silahkan lakukan pengisian data</strong>
                    </p>
                <?php endif; ?>
                <hr>
            <div id="loading">
                <button type="submit" name="submit" id="submits"  class="btn btn-primary btn-sm flat"><i class="fa fa-check-square-o"></i> Simpan</button>
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

<script>
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
