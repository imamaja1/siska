<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/mahasiswa'); ?>" class="btn-success btn-sm flat"><i
                    class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<?php
echo isset($table) ? $table : '';
$flashmessage = $this->session->flashdata('message');
echo isset($flashmessage) ? $flashmessage : '';
?>

<div class="box box-primary flat">
    <div class="box-header">
        <h5><b>Reset Sandi Mahasiswa Berdasarkan NIM</b></h5>
        <hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" method="post"
              action="<?= site_url('admin/akademik/mahasiswa/reset_sandi_process'); ?>">

            <div class="form-group">
                <label class="control-label col-sm-3">Masukan NIM <label class="text-danger">* &nbsp;</label>:</label>
                <div class="col-sm-3">
                    <input value="<?= set_value('kata_kunci') ?>" type="text" class="form-control"
                           placeholder="Ketik NIM Mahasiswa" name="kata_kunci" id="kata_kunci">
                    <small class="text-danger"><?= form_error('kata_kunci') ?></small>
                </div>
                <button type="submit" name="reset_sandi" class="btn btn-primary flat"><i class="fa fa-cog"></i> Proses
                </button>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-3">

                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function gantipassword(url) {
        swal({
            title: 'Apa anda yakin?',
            text: "Password lama akan diganti!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin!',
            cancelButtonText: 'Tidak Yakin!',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        }).then(function () {
            window.location.href = url;
        }, function (dismiss) {
            // dismiss can be 'cancel', 'overlay',
            // 'close', and 'timer'
            if (dismiss === 'cancel') {
                swal(
                    'Gagal',
                    'Password lama gagal diganti',
                    'error'
                )
            }
        })
    }
</script>