<?php $this->load->view('admin/mbkm/partial/V_header'); ?>
<div class="box box-primary flat">
    <div class="box-header">
        <h4 class="box-title"><i class="fa fa-users"></i> Data Mahasiswa MBKM</h4>
    </div>
    <div class="box-body">
        <form action="<?= site_url('admin/mbkm/mahasiswa') ?>" method="post">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label for="kode_tahun_akademik">Tahun Akademik</label>
                <select name="kode_tahun_akademik" id="kode_tahun_akademik" class="form-control">
                    <?php foreach ($tahun_akademik as $value): ?>
                        <option value='<?= e($value->kode_tahun_akademik) ?>' <?php if($value->kode_tahun_akademik == $semester->kode_tahun_akademik){ echo 'selected';}  ?>>Semester <?= $value->semester == '1' ? 'Ganjil Tahun '.$value->tahun_akademik:'Genap Tahun '.$value->tahun_akademik?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="kode_program_studi">Program Studi</label>
                <select name="kode_program_studi" id="kode_program_studi" class="form-control">
                    <option value="">Pilih</option>
                    <?php foreach ($prodi as $value): ?>
                        <option value="<?= e($value->kode_program_studi) ?>"><?= e($value->singkatan_program_studi) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>
        <hr>
        <div id="data-mahasiswa"></div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#kode_tahun_akademik, #kode_program_studi').change(function () {
            var kode_tahun_akademik = $('#kode_tahun_akademik').val();
            var kode_program_studi = $('#kode_program_studi').val();
            $.ajax({
                url: '<?= site_url('admin/mbkm/mahasiswa/filter') ?>',
                type: 'post',
                data: {kode_tahun_akademik: kode_tahun_akademik, kode_program_studi: kode_program_studi},
                success: function (data) {
                    $('#data-mahasiswa').html(data);
                }
            });
        });
    });
</script>
