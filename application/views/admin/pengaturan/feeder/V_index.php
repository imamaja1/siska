<?= $this->session->flashdata('pesan') ?>

<div class="box box-solid flat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-plug"></i> Konfigurasi API Feeder PDDIKTI</h3>
    </div>
    <div class="box-body"><br>
        <form action="<?= site_url('admin/pengaturan/feeder/simpan'); ?>" method="POST" class="form-horizontal" id="form-feeder">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-3">URL API Feeder :</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" name="feeder_url" placeholder="http://localhost" value="<?= set_value('feeder_url', e($feeder['feeder_url'])) ?>">
                    <small class="text-muted">Alamat host Neo Feeder, tanpa port dan tanpa path.</small>
                    <small style="color: red"><?= form_error('feeder_url') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Port API Feeder :</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" name="feeder_port" placeholder="3003" value="<?= set_value('feeder_port', e($feeder['feeder_port'])) ?>">
                    <small class="text-muted">Port web service Neo Feeder (default 3003).</small>
                    <small style="color: red"><?= form_error('feeder_port') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Username Feeder :</label>
                <div class="col-sm-5">
                    <input class="form-control" type="text" name="feeder_username" placeholder="username feeder" value="<?= set_value('feeder_username', e($feeder['feeder_username'])) ?>">
                    <small style="color: red"><?= form_error('feeder_username') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Password Feeder :</label>
                <div class="col-sm-5">
                    <input class="form-control" type="password" name="feeder_password" placeholder="Kosongkan jika tidak diubah" autocomplete="new-password">
                    <small class="text-muted">Password disimpan terenkripsi. Biarkan kosong untuk mempertahankan password yang tersimpan.</small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-5">
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-check-circle"></i> Simpan</button>
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
