<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h4><i class="fa fa-file-text-o"></i> Feeder Petikan Nilai</h4>
            </div>
            <div class="box-body">
                <form id="form-filter" class="form-horizontal" method="post"
                      action="<?= site_url('admin/audit/petikan/hasil') ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group">
                        <label class="control-label col-sm-2">NIM <span class="text-danger">*</span></label>
                        <div class="col-sm-4 col-xs-12">
                            <input required type="text" name="nim" class="form-control" placeholder="Masukkan NIM mahasiswa">
                            <small class="text-muted">Menampilkan seluruh nilai mahasiswa dari Feeder (semua semester).</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-4">
                            <button type="submit" class="btn btn-primary flat"><i class="fa fa-search"></i> Tampilkan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row" id="result"></div>

<script>
    var loading = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";

    $("#form-filter").submit(function (e) {
        e.preventDefault();
        $("#result").html(loading);
        $.ajax({
            url: $(this).prop('action'),
            data: $(this).serialize(),
            type: 'post',
            success: function (res) {
                $("#result").html(res);
            },
            error: function () {
                $("#result").html("<div class='col-md-12'><div class='callout callout-danger flat'><p>Terjadi kesalahan koneksi.</p></div></div>");
            }
        });
    });
</script>
