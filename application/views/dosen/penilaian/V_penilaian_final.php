<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="box flat">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-exchange"></i> Penilaian Final (Perbandingan Dummy vs KHS)</h3>
                <div class="box-tools pull-right">
                    <div class="input-group input-group-sm">
                        <select class="form-control select2" id="nilai-akademik" style="max-width: 250px">
                            <?php foreach ($tahun_akademik as $row): ?>
                                <option value="<?= e($row->kode_tahun_akademik) ?>" <?= ($select == $row->kode_tahun_akademik) ? 'selected' : '' ?>>
                                    <?= e($row->tahun_akademik) ?> - <?= e($row->semester == 0 ? 'Genap' : 'Ganjil') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12" id="landing-nilai">
        <div style="height: 200px; border-radius: 20px; background-color: #00a7d0; padding: 20px">
            <p style="text-align: center; color: white"><i>"Pilih Tahun Akademik untuk melihat daftar kelas"</i></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        loadKelas();
    });

    var loader = '<p style="text-align: center"><img src="<?= base_url("assets/siska/img/logo-ubg.gif") ?>" alt=""></p>';

    function loadKelas() {
        var code = $('#nilai-akademik').val();
        $.ajax({
            url: '<?= site_url('dosen/penilaian/choose_final') ?>',
            type: 'POST',
            data: 'kode_nilai_akademik=' + code,
            beforeSend: function () {
                $('#landing-nilai').html(loader);
            },
            success: function (data) {
                $('#landing-nilai').html(data);
            }
        });
    }

    $('#nilai-akademik').change(function () {
        loadKelas();
    });

</script>
