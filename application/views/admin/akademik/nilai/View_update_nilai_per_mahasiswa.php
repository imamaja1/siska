<?= $this->session->flashdata('info') ? $this->session->flashdata('info') : '' ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/nilai'); ?>" class="btn-sm btn-success flat"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="box box-primary flat">
    <div class="box-body"><br>
        <form class="form-horizontal" method="POST" action="<?= site_url('admin/akademik/nilai/get_update_nilai_per_mahasiswa_process'); ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

            <div class="form-group">
                <label class="control-label col-sm-3"> Jurusan <label style="color: red;">*</label> :</label>
                <div class="col-sm-3">
                    <select required class="form-control" name="jurusan" id="jurusan">
                        <option value="" selected disabled>Pilih Jurusan</option>
                        <?php foreach ($program_studi as $data) { ?>
                            <option value="<?= e($data->kode_program_studi) ?>"><?= e($data->nama_program_studi) ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"> Matakuliah <label style="color: red;">*</label> :</label>
                <div class="col-sm-3">
                    <select required class="form-control select2" name="matakuliah" id="matakuliah">
                        <option value="" selected disabled>Pilih Matakuliah</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"> NIM <label style="color: red;">*</label> :</label>
                <div class="col-sm-3">
                    <input  value="<?= set_value('nim') ?>" type="text" maxlength="10" name="nim" id="nim" placeholder="NIM" class="form-control">
                    <small class="text-red"><?= form_error('nim') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-3">
                    <button class="btn btn-primary flat" type="submit"><i class="fa fa-cog"></i> Proses</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#jurusan').change(function () {
            $.post("<?= site_url(); ?>admin/akademik/nilai/get_matakuliah/" + $('#jurusan').val(), {}, function (obj) {
                $('#matakuliah').html(obj);
            });
        });
    });
</script>  