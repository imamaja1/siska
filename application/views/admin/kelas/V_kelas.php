<div class="row">
    <div class="col-md-12">
        <div class="box flat">
            <div class="box-body">
                <div class="row">
                    <form action="<?= site_url('admin/kuisioner/kelas/filter') ?>" method="post">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <div class="col-xs-12 col-sm-4">
                            <div class="form-group" style="margin: 0px">
                                <select name="kode_tahun_akademik" onchange="change_tahun_akademik(this.value)"
                                        class="form-control select2">
                                    <option value="" selected disabled>Tahun Akademik</option>
                                    <?php foreach ($tahun_akademik as $row): ?>
                                        <option <?= $row->kode_tahun_akademik == $kode_tahun_akademik ? 'selected' : '' ?>
                                            value="<?= e($row->kode_tahun_akademik) ?>">
                                                <?= e($row->tahun_akademik) ?>
                                            - <?= $row->semester == 0 ? 'GENAP' : 'GANJIL' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="form-group" style="margin: 0px">
                                <select name="kode_program_studi" onchange="makul(this.value)" class="form-control">
                                    <option value="" selected disabled>Pilih Program Studi</option>
                                    <?php foreach ($nama_jurusan as $row): ?>
                                        <option value="<?= e($row->kode_program_studi) ?>">
                                            <?= e($row->singkatan_program_studi) ?>
                                            - <?= e($row->nama_program_studi) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="col-xs-12 col-sm-4" style="margin-top: 5px;">
                        <div class="pull-right">
                            <a href="<?= site_url('admin/kuisioner/kelas/aktivasi_nilai'); ?>" class="btn btn-sm btn-primary"><i class="fa fa-file-text"></i> Aktivasi Nilai</a>
                            <?php if ($setting->aktif_kuisioner == 'A'): ?>
                                <a href="<?= site_url('admin/kuisioner/kelas/nonaktif') ?>" class="btn btn-sm btn-success"
                                   title="Pengisian Nilai Aktif"><i class="fa fa-check-square-o"></i> Aktif</a>
                               <?php else: ?>
                                <a href="<?= site_url('admin/kuisioner/kelas/aktif') ?>" class="btn btn-sm btn-danger"
                                   title="Pengisian Nilai nonAktif"><i class="fa fa-check-square-o"></i> Non Aktif</a>
                               <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8 col-sm-12" id="landing-matakuliah">
        <div style="height: 200px; border-radius: 20px; background-color: #00a7d0; padding: 20px">
            <p style="text-align: center; color: white"><i>"Landing data matakuliah"</i></p>
        </div>
    </div>
    <div class="col-md-4 col-sm-12" id="landing-kelas">
        <div style="height: 200px; border-radius: 20px; background-color: #00a7d0; padding: 20px">
            <p style="text-align: center; color: white"><i>"Landing data Kelas"</i></p>
        </div>
    </div>
</div>

<script>
    var kode_program_studi = '1';
    var kode_tahun_akademik_mega = '<?= e($kode_tahun_akademik) ?>'
    var matakuliah_id = '';
    var super_kelas_id = '';
    var default_landing = '<div style="height: 200px; border-radius: 20px; background-color: #00a7d0; padding: 20px">\n' +
            '            <p style="text-align: center; color: white"><i>"Landing data"</i></p>\n' +
            '        </div>';
    var loader = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";

    function change_tahun_akademik(kode_tahun_akademik) {
        kode_tahun_akademik_mega = kode_tahun_akademik
        makul(kode_program_studi);
    }
    function makul(id) {
        kode_program_studi = id;
        $.ajax({
            //url : '<? //= site_url('admin/kuisioner/kelas/dropdown_makul') ?>///'+id,
            url: '<?= site_url('admin/kuisioner/kelas/get_matakuliah') ?>/' + id + '/' + kode_tahun_akademik_mega,
            type: 'get',
            beforeSend: function () {
                $('#landing-matakuliah').html(loader);
            },
            success: function (data) {
                // $('#kd_makul').html(data);
                $('#landing-matakuliah').html(data);
                $('#landing-kelas').html(default_landing);
            },
            error: function () {
                //                alert('kamu cemen');
                console.log('gagal');
            }
        })
    }


</script>