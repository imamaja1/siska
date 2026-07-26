<div class="row">
    <div class="col-md-12">
        <div class="box flat">
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-8">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            Insert Mahasiswa MBKM
                        </button>
                    </div>
                    <div class="col-xs-4">
                        <div class="pull-right">
                            <div class="form-group">
                                <select class="form-control" id="ta" onchange="mhs_ta();">
                                    <?php foreach ($tahun_akademik as $key => $value) { ?>
                                        <option value='<?= e($value->kode_tahun_akademik) ?>' <?php if($value->kode_tahun_akademik == $semester->kode_tahun_akademik){ echo 'selected';}  ?>>Semester <?= e($value->semester == '1' ? 'Ganjil Tahun '.$value->tahun_akademik:'Genap Tahun '.$value->tahun_akademik)?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12" id="landing-mahasiswa">
        <div style="height: 200px; border-radius: 20px; background-color: #00a7d0; padding: 20px">
            <p style="text-align: center; color: white"><i>"Mahasiswa"</i></p>
        </div>
    </div>
</div>

<!-- Modal mhs mbkm -->
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title " id="exampleModalLabel">Data Mahasiswa</h3>
      </div>
      <div class="modal-body">
        <div class="form-group" style="margin: 20px 20px 20px 0px;">
            <div class="col-sm-10">
                <input type="text" class="form-control" id="search-nim" placeholder="NIM">
            </div>
            <button type="button" class="btn btn-info col-sm-2" onclick="Search()">Search</button>
        </div>
        <div class="row" style="margin: 70px 20px 20px 4px;">
            <div class="col-md-12 col-sm-12" id="search-mhs">
                <div style="height: 50px; border-radius: 20px; background-color: #00a7d0; padding: 13px">
                    <p style="text-align: center; color: white"><i>"Mahasiswa"</i></p>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
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
    function mahasiswa() {
        $.ajax({
            url: '<?= site_url('dosen/kaprodi/mbkm/get_mahasiswa') ?>',
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
    mahasiswa()
    function mhs_ta(){
        $ta = $('#ta').val();
         $.ajax({
            url: '<?= site_url('dosen/kaprodi/mbkm/get_mahasiswa') ?>/'+$ta,
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
    function Search(){
        $nim = $('#search-nim').val();
        $ta = $('#ta').val();
        $.ajax({
            //url : '<? //= site_url('admin/kuisioner/kelas/dropdown_makul') ?>///'+id,
            url: '<?= site_url('dosen/kaprodi/mbkm/search_mahasiswa') ?>/'+$nim +'/'+$ta,
            type: 'get',
            beforeSend: function () {
                $('#search-mhs').html(loader);
            },
            success: function (data) {
                // $('#kd_makul').html(data);
                $('#search-mhs').html(data);
                // $('#landing-kelas').html(default_landing);
            },
            error: function () {
                // alert('kamu cemen');
                console.log('gagal');
            }
        })
    }
    function update(nim){
        var ta = $('#ta').val();
        $.ajax({
            //url : '<? //= site_url('admin/kuisioner/kelas/dropdown_makul') ?>///'+id,
            url: '<?= site_url('dosen/kaprodi/mbkm/tambah_mhs_mbkm') ?>/'+nim +'/'+ta,
            type: 'get',
            success: function (data) {
                Search();
                mhs_ta();
            },
            error: function () {
                // alert('kamu cemen');
                console.log('gagal');
            }
        })
    }
    function hapus(id){
        $.ajax({
            //url : '<? //= site_url('admin/kuisioner/kelas/dropdown_makul') ?>///'+id,
            url: '<?= site_url('dosen/kaprodi/mbkm/hapus_mhs_mbkm') ?>/'+id ,
            type: 'get',
            success: function (data) {
                
                mhs_ta();
            },
            error: function () {
                // alert('kamu cemen');
                console.log('gagal');
            }
        })
    }
</script>