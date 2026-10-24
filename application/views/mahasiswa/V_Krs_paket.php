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

    <!-- Pilih Semester -->
    <div class="pf-card">
        <div class="pf-card-body">
            <div class="krs-pick">
                <?php $i=1; foreach ($krs_mhs as $row) : ?>
                    <a href="<?= site_url('mahasiswa/krs/old/'.$row->semester) ?>" class="btn btn-default btn-sm"><i class="fa fa-calendar-check-o"></i> Semester <?= $i++ ?></a>
                <?php endforeach; ?>
                <a href="<?= site_url('mahasiswa/krs/index/') ?>" class="btn btn-primary btn-sm"><i class="fa fa-calendar-check-o"></i> Semester <?= $i++ ?> (Aktif)</a>
            </div>
        </div>
    </div>

<div class="box box-primary flat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file-text-o"></i> Kartu Rencana Studi</h3>
    </div>
    <div class="box-body">
        <form id="form-krs-mahasiswa" action="<?= site_url('mahasiswa/Krs/simpan_krs') ?>" method="post" name="krs_form">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <?php foreach ($data_matakuliah as $row): ?>

                <?php if (count($row['data']) > 0) : ?>
                    <div class="mhs-semester-label">SEMESTER <?= $row['semester'] ?></div>
                    <div class="table-responsive mhs-table-wrap">
                    <table class="table mhs-table">
                        <thead>
                        <tr>
                            <th class="mhs-c-no" rowspan="2">NO.</th>
                            <th class="mhs-c-kode" rowspan="2">KODE MK</th>
                            <th rowspan="2">MATAKULIAH</th>
                            <th colspan="3">SKS</th>
                            <th width="60" rowspan="2">B</th>
                        </tr>
                        <tr>
                            <th width="50">T</th>
                            <th width="50">PK</th>
                            <th width="50">PT</th>
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
                    </div>
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
</div>
