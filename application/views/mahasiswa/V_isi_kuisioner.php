<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-pie-chart"></i> Kuisioner - <strong><?= e($matakuliah->nama_matakuliah) ?></strong></h4>
</div>
<form id="form-kuisioner" action="<?= site_url('mahasiswa/kuisioner/simpan') ?>" method="post">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
<div class="modal-body">
    <?php if (count($dosen) > 0) :?>
        <p><strong>Nama Dosen : </strong></p>
        <?php $i=1; foreach ($dosen as $row) : ?>
            <p><?= $i++ ?>. <?= e($row->nama_dosen) ?></p>
        <?php endforeach;?>
    <?php else: ?>
        <div class="badge bg-aqua">Dosen belum ada...!!!</div>
    <?php endif; ?>
    <div class="alert alert-info flat" style="margin-bottom: 14px;">
        <strong><i class="fa fa-info-circle"></i> Petunjuk:</strong> Pilihlah salah satu opsi pada kolom penilaian: <b>1</b> = Sangat tidak baik, <b>2</b> = Tidak baik, <b>3</b> = Cukup/Moderat, <b>4</b> = Baik, <b>5</b> = Sangat baik.
    </div>
        <div class="table-responsive mhs-table-wrap">
        <table class="table mhs-table">
            <thead>
            <tr>
                <th class="mhs-c-no">NO.</th>
                <th>Pertanyaan</th>
                <th class="mhs-c-num">1</th>
                <th class="mhs-c-num">2</th>
                <th class="mhs-c-num">3</th>
                <th class="mhs-c-num">4</th>
                <th class="mhs-c-num">5</th>
            </tr>
            </thead>
            <tbody>
            <input type="hidden" name="kelas_mahsiswa_id" value="<?= $kelas_mahasiswa_id ?>">
            <?php $kat=''; $i=1; foreach ($soal as $row) : ?>
            <?php $kategori = $row->kategori ?>
            <?php if ($kat != $kategori) : ?>
                    <tr style="background:#EBF4FC;">
                        <td colspan="7"><strong><i class="fa fa-bookmark text-primary"></i> <?= e($kategori) ?></strong></td>
                    </tr>
            <?php endif;?>
            <?php $kat = $kategori;?>
            <tr>
                <td align="center"><?= $i++ ?>.</td>
                <td ><?= e($row->soal) ?></td>
                <td align="center"><label><input required type="radio" name="hasil[<?= $row->soal_kuisioner_id ?>]" value="1"></label></td>
                <td align="center"><label><input required type="radio" name="hasil[<?= $row->soal_kuisioner_id ?>]" value="2"></label></td>
                <td align="center"><label><input required type="radio" name="hasil[<?= $row->soal_kuisioner_id ?>]" value="3"></label></td>
                <td align="center"><label><input required type="radio" name="hasil[<?= $row->soal_kuisioner_id ?>]" value="4"></label></td>
                <td align="center"><label><input required type="radio" name="hasil[<?= $row->soal_kuisioner_id ?>]" value="5"></label></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div class="form-group">
            <label>Sebutkan hal yang perlu dipertahankan (<i>Jika ada</i>) :</label>
            <textarea name="kritik" class="form-control" rows="4"></textarea>
        </div>
        <div class="form-group">
            <label>Sebutkan hal yang perlu ditingkatkan (<i>Jika ada</i>) :</label>
            <textarea name="saran" class="form-control" rows="4"></textarea>
        </div>
</div>
<div class="modal-footer">
    <div id="loading">
    <button type="button" class="btn btn-danger flat" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
    <button type="submit" name="submit" class="btn btn-primary flat"><i class="fa fa-check-square-o"></i> Simpan</button>
    </div>
</div>
</form>
<script>
    $('#form-kuisioner').bind('submit', function (e) {
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
