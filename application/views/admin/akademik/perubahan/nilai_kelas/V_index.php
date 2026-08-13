<div class="box box-primary flat">
    <div class="box-header">
        <h3 class="box-title"><strong>Perubahan Nilai Per Kelas</strong></h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Tahun Akademik</label>
                    <select id="tahun_akademik" class="form-control select2" style="width:100%;">
                        <option value="">Pilih Tahun Akademik</option>
                        <?php foreach ($tahun_akademik as $row) : ?>
                            <option value="<?= e($row->kode_tahun_akademik) ?>"><?= e($row->tahun_akademik) ?> - <?= $row->semester == 1 ? 'Ganjil' : 'Genap' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Matakuliah</label>
                    <select id="matakuliah" class="form-control select2" style="width:100%;">
                        <option value="">Pilih Tahun Akademik dahulu</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Kelas</label>
                    <select id="kelas" class="form-control select2" style="width:100%;">
                        <option value="">Pilih Matakuliah dahulu</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="hasil-kelas"></div>

<script type="text/javascript">
    $('#tahun_akademik').change(function () {
        var ta = $(this).val();
        $('#matakuliah').html('<option value="">Memuat...</option>');
        $('#kelas').html('<option value="">Pilih Matakuliah dahulu</option>');
        $('#hasil-kelas').html('');
        if (ta) {
            $.get('<?= site_url('admin/akademik/perubahan/nilai_kelas/get_matakuliah') ?>/' + ta, function (data) {
                $('#matakuliah').html(data);
            });
        }
    });

    $('#matakuliah').change(function () {
        var ta = $('#tahun_akademik').val();
        var mk = $(this).val();
        $('#kelas').html('<option value="">Memuat...</option>');
        $('#hasil-kelas').html('');
        if (ta && mk) {
            $.get('<?= site_url('admin/akademik/perubahan/nilai_kelas/get_kelas') ?>/' + ta + '/' + mk, function (data) {
                $('#kelas').html(data);
            });
        }
    });

    $('#kelas').change(function () {
        var ta = $('#tahun_akademik').val();
        var mk = $('#matakuliah').val();
        var kl = $(this).val();
        $('#hasil-kelas').html('');
        if (ta && mk && kl) {
            $.get('<?= site_url('admin/akademik/perubahan/nilai_kelas/hasil') ?>/' + ta + '/' + mk + '/' + kl, function (data) {
                $('#hasil-kelas').html(data);
            });
        }
    });
</script>
