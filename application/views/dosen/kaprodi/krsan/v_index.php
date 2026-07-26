<div class="row">
    <div class="col-md-3">
        <div class="box flat ">
            <div class="box-header with-border" >Data Mahasiswa</div>
            <div class="box-body">
                <div class="form-group">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Tahun Akademik</label>
                        <select class="form-control select2" id="ta">
                            <?php foreach ($tahun_akademik as $key => $value) { ?>
                                <option value='<?= e($value->kode_tahun_akademik) ?>' <?php if($value->kode_tahun_akademik == $semester->kode_tahun_akademik){ echo 'selected';}  ?>>Semester <?= e($value->semester == '1' ? 'Ganjil Tahun '.$value->tahun_akademik:'Genap Tahun '.$value->tahun_akademik)?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Mahasiswa Angkatan</label>
                        <select class="form-control select2" id="angkatan">
                            <option value="0"> Semua </option>
                            <?php foreach ($angkatan as $key => $value) { ?>
                                <option value='<?= substr($value->tahun_akademik,2,2) ?>' ><?= e($value->tahun_akademik) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group pull-right">
                        <button class="btn btn-primary" onclick="mahasiswa()"> Search </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9 " id="landing-mahasiswa">
        <div style="height: 200px; border-radius: 20px; background-color: #00a7d0; padding: 20px">
            <p style="text-align: center; color: white"><i>"Mahasiswa"</i></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
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
    function mahasiswa() {
        var ta = $('#ta').val();
        var angkatan = $('#angkatan').val();
        // var status = $('#status').val();
        console.log(status);
        $.ajax({
            url: '<?= site_url('dosen/kaprodi/KRSAN/get_mahasiswa/') ?>/'+ta+'/'+angkatan+'/'+status,
            type: 'get',
            beforeSend: function () {
                $('#landing-mahasiswa').html(loader);
            },
            success: function (data) {
                
                $('#landing-mahasiswa').html(data);
            },
            error: function () {
                console.log('gagal');
            }
        })
    }
    mahasiswa();
</script>