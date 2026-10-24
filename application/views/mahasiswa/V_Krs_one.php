<div class="mhs-profile">
    <!-- Dosen Wali -->
    <div class="pf-card">
        <div class="pf-card-body">
            <div class="krs-advisor">
                <span class="krs-advisor-label">Dosen Wali :</span>
                <span class="pf-chip"><?= e($dosen_wali) ?></span>
                <?php if (isset($dosen_perwakilan)) : ?>
                    <span class="pf-chip is-alt">
                        <i class="fa fa-phone"></i> <?= is_array($dosen_perwakilan) ? e($dosen_perwakilan['nama_dosen'] . ' (' . $dosen_perwakilan['no_telp'] . ')') : e($dosen_perwakilan) ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

<div class="box box-primary flat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file-text-o"></i> Kartu Rencana Studi</h3>
    </div>
    <div class="box-body">
        <form id="form-krs-mahasiswa" action="<?= site_url('mahasiswa/Krs/add_one') ?>" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <?php if (count($matakuliah_awal) > 0) : ?>
                    <div class="table-responsive mhs-table-wrap">
                    <table class="table mhs-table">
                        <thead>
                        <tr>
                            <th class="mhs-c-no" rowspan="2">NO.</th>
                            <th class="mhs-c-kode" rowspan="2">KODE MK</th>
                            <th rowspan="2">MATAKULIAH</th>
                            <th colspan="3">SKS</th>
                            <th width="50" rowspan="2">B</th>
                            <th width="50" rowspan="2">U</th>
                        </tr>
                        <tr>
                            <th width="50">T</th>
                            <th width="50">PK</th>
                            <th width="50">PT</th>
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
</div>
