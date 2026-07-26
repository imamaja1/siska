<div class="box box-solid flat">
    <div class="box-body">
        <div class="col-md-4">
            <div class="box box-solid flat">
                <div class="box-header">
                    <h4>1. Tindakan untuk memvalidasi nilai</h4>
                </div>
                <div class="box-body">
                    <a href="<?= site_url('admin/akademik/validasikhusus_uas'); ?>" class="btn btn-xs btn-dropbox flat"><i class="fa fa-file-text"></i> Nilai Tervalidasi (Lama)</a>
                    <a href="<?= site_url('admin/akademik/validasi_khusus'); ?>" class="btn btn-xs btn-dropbox flat"><i class="fa fa-file-text"></i> Nilai Tervalidasi (Baru)</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-solid flat">
                <div class="box-header">
                    <h4>2. Tindakan untuk update nilai</h4>
                </div>
                <div class="box-body">
                    <a href="<?= site_url('admin/akademik/nilai/get_update_nilai'); ?>" class="btn btn-xs btn-success flat"><i class="fa fa-file"></i> Per Matakuliah</a>
                    <a href="<?= site_url('admin/akademik/nilai/get_update_nilai_per_mahasiswa'); ?>" class="btn btn-xs btn-info flat"><i class="fa fa-file"></i> Per Mahasiswa</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-solid flat">
                <div class="box-header">
                    <h4>3. Tindakan untuk pelaporan nilai</h4>
                </div>
                <div class="box-body">
                    <a href="<?= site_url('admin/akademik/nilai/persentase_penginputan'); ?>" class="btn btn-xs btn-danger flat"><i class="fa fa-line-chart"></i> Persentase Nilai</a>
                    <a href="<?= site_url('admin/akademik/nilai/distribusi_nilai'); ?>" class="btn btn-xs btn-primary flat"><i class="fa fa-file-text"></i> Distribusi Nilai</a>

                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="callout callout-warning">
                <p><i>Pastikan nilai sudah tervalidasi pada nomor 1 sebelum diupdate pada nomor 2. Sebelum divalidasi pada nomor 1 pastikan sudah menerima form validasi nilai yang sudah ditanda tangani oleh Kaprodi dan Dekan</i></p>
            </div>
        </div>

    </div>

</div>
<div class="box box-primary flat">
    <div class="box-body"><br>
        <form class="form-horizontal" method="POST" action="<?= site_url('admin/akademik/nilai/get_nilai_process'); ?>">

            <div class="form-group">
                <label class="control-label col-sm-3"> Tahun Akademik <label style="color: red;">*</label> :</label>
                <div class="col-sm-3">
                    <select required class="form-control" name="tahun_akademik" id="tahun_akademik">
                        <option value="" selected disabled>Pilih Tahun Akademik</option>
                        <?php foreach ($tahun_akademik as $data) { ?>
                            <?php
                            if ($data->semester == 1) {
                                $periode = "Ganjil";
                            } else {
                                $periode = "Genap";
                            }
                            ?>
                            <option <?= set_select('tahun_akademik', $data->kode_tahun_akademik) ?>
                                value="<?= $data->kode_tahun_akademik ?>"><?= $data->tahun_akademik ?>
                                - <?= $periode; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"> Jurusan <label style="color: red;">*</label> :</label>
                <div class="col-sm-3">
                    <select required class="form-control" name="jurusan" id="jurusan">
                        <option value="" selected disabled>Pilih Jurusan</option>
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
        $('#tahun_akademik').change(function () {
            $.post("<?= site_url(); ?>admin/akademik/nilai/get_jurusan/" + $('#tahun_akademik').val(), {}, function (obj) {
                $('#jurusan').html(obj);
            });
        });
        $('#jurusan').change(function () {
            $.post("<?= site_url(); ?>admin/akademik/nilai/get_matakuliah/" + $('#jurusan').val() + "/" + $('#tahun_akademik').val(), {}, function (obj) {
                $('#matakuliah').html(obj);
            });
        });
    });
</script>  
