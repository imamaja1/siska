<?= $this->session->flashdata('pesan') ?>

<div class="box box-solid flat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-plug"></i> Konfigurasi API Feeder PDDIKTI</h3>
    </div>
    <div class="box-body"><br>
        <div class="alert alert-info">
            <i class="fa fa-info-circle"></i> Konfigurasi Feeder dikelola melalui aplikasi <strong>Filament</strong> (tabel <code>feeder_credentials</code>).
            Halaman ini hanya menampilkan konfigurasi tersebut dan tidak dapat mengubahnya.
        </div>
        <form class="form-horizontal" id="form-feeder">
            <div class="form-group">
                <label class="control-label col-sm-3">URL API Feeder :</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" value="<?= e($feeder['feeder_url']) ?>" readonly disabled>
                    <small class="text-muted">Alamat host Neo Feeder, tanpa port dan tanpa path.</small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Port API Feeder :</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" value="<?= e($feeder['feeder_port']) ?>" readonly disabled>
                    <small class="text-muted">Port web service Neo Feeder (default 3003).</small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Username Feeder :</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" value="<?= e($feeder['feeder_username']) ?>" readonly disabled>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Password Feeder :</label>
                <div class="col-sm-5">
                    <input class="form-control" type="password" value="<?= $feeder['feeder_password'] !== '' ? '********' : '' ?>" readonly disabled>
                    <small class="text-muted">Password disimpan terenkripsi oleh aplikasi Filament.</small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Endpoint Feeder :</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" value="<?= e($feeder['feeder_endpoint']) ?>" readonly disabled>
                    <small class="text-muted">Endpoint web service Feeder (default /ws/live2.php).</small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-5">
                    <button type="button" class="btn btn-warning flat" id="btn-test-koneksi"><i class="fa fa-wifi"></i> Test Koneksi</button>
                    <button type="reset" class="btn btn-default flat"><i class="fa fa-refresh"></i> Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#btn-test-koneksi').on('click', function() {
        var $btn = $(this);
        swal({
            title: 'Test Koneksi Feeder',
            text: 'Hubungi API Feeder menggunakan konfigurasi yang tersimpan?',
            icon: 'info',
            buttons: true,
            dangerMode: false,
        }).then(function(ok) {
            if (!ok) return;

            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menguji...');

            $.ajax({
                url: '<?= site_url("admin/pengaturan/feeder/test_koneksi") ?>',
                type: 'POST',
                dataType: 'json',
                timeout: 40000,
                success: function(res) {
                    if (res.status) {
                        swal('Berhasil', res.message, 'success');
                    } else {
                        swal('Gagal', res.message, 'error');
                    }
                },
                error: function() {
                    swal('Gagal', 'Terjadi kesalahan koneksi ke server.', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i class="fa fa-wifi"></i> Test Koneksi');
                }
            });
        });
    });
});
</script>
