<div class="box box-primary flat">
    <div class="box-header">
        <h3 class="box-title"><strong>Log Aktivitas Nilai</strong></h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>NIM Mahasiswa</label>
                    <input type="text" id="nim" class="form-control" placeholder="Contoh: 2202010001">
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label>&nbsp;</label><br>
                    <button type="button" id="btn-cari" class="btn btn-primary flat"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="hasil-log"></div>

<script type="text/javascript">
    $('#btn-cari').click(function () {
        var nim = $('#nim').val().trim();
        $('#hasil-log').html('');
        if (nim) {
            $.get('<?= site_url('admin/akademik/perubahan/log_nilai/hasil') ?>/' + nim, function (data) {
                $('#hasil-log').html(data);
            });
        }
    });

    $('#nim').keypress(function (e) {
        if (e.which === 13) {
            $('#btn-cari').click();
        }
    });
</script>