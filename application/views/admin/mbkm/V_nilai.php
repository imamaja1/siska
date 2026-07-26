<div class="row">
    <div class="col-md-12">
        <div class="box flat">
            <div class="box-body">
                <div class="row">
                    <div class="box-body table-responsive">
                        <p>
                            <center><strong>NILAI MBKM</strong></center>
                        </p>
                        <p>
                            <center><strong>SEMESTER <?= $semester->semester ? "GENAP" : "GANJIL" ?> TA.
                                    <?= $semester->tahun_akademik; ?></strong></center>
                        </p>
                        <br>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <table class="table">
                                <tr>
                                    <td><strong>Nama Mahasiswa</strong></td>
                                    <td><strong>:</strong></td>
                                    <td><?= $data_mhs->nama_mahasiswa ? $data_mhs->nama_mahasiswa : '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>NIM</strong></td>
                                    <td><strong>:</strong></td>
                                    <td><?= $data_mhs->nim ? $data_mhs->nim : '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Semester</strong></td>
                                    <td><strong>:</strong></td>
                                    <td><?= $data_mhs->semester ? $data_mhs->semester : '-' ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <table class="table">
                                <tr>
                                    <td><strong>Program Studi</strong></td>
                                    <td><strong>:</strong></td>
                                    <td><?= $data_mhs->nama_program_studi ? $data_mhs->nama_program_studi : '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Fakultas</strong></td>
                                    <td><strong>:</strong></td>
                                    <td><?= $data_mhs->nama_fakultas ? $data_mhs->nama_fakultas : '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Kurikulum</strong></td>
                                    <td><strong>:</strong></td>
                                    <td><?= $kurikulum->nama_kurikulum ? $kurikulum->nama_kurikulum : '-'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box flat">
            <div style="padding:20px 20px 0 20px; text-align:right" >
                <a href="<?= base_url('admin/mbkm/daftar/print_view_non_aktif/'.$data_mhs->nim.'/'.$data_mhs->kode_tahun_akademik) ?>" type="button" id="status<?= $keys ?>" class="btn btn-sm btn-danger" data-toggle="modal">
                    Download Non Aktif
                </a>
                <a href="<?= base_url('admin/mbkm/daftar/print_view_aktif/'.$data_mhs->nim.'/'.$data_mhs->kode_tahun_akademik) ?>" type="button" id="status<?= $keys ?>" class="btn btn-sm btn-danger" data-toggle="modal">
                    Download Aktif
                </a>
            </div>
            <div class="box-body">
                <div class="box-body table-responsive">
                    <table class="table demo-table" id="table-edit">
                        <thead>
                            <tr>
                                <th id="color" width="20">
                                    <center>No.</center>
                                </th>
                                <th id="color">
                                    <center>Kode</center>
                                </th>
                                <th id="color">
                                    <center>Matakuliah</center>
                                </th>
                                <th id="color" width="200">
                                    <center>Nilai Akhir</center>
                                </th>
                                <th id="color">
                                    <center>Grade</center>
                                </th>
                                <th id="color" width="100">
                                    <center>Aksi</center>
                                </th>
                                <th id="color" width="100">
                                    <center>Status</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_nilai as $keys => $value): ?>
                                <tr>
                                    <td style="padding-top:15px"><?= $keys + 1 ?>.</td>
                                    <td style="padding-top:15px"><?= $value->kode_matakuliah ?></td>
                                    <td style="padding-top:15px"><?= $value->nama_matakuliah ?></td>
                                    <td><input type="" class="form-control" id="<?= $keys ?>"
                                            value="<?= $value->nilai_akhir ?>"></td>
                                    <td style="padding-top:15px">
                                        <div id='grade<?= $keys ?>'>
                                            <?php
                                            $data_penilaian = sistem_penilaian($value->nim);
                                            foreach ($data_penilaian as $key) {
                                                if (($key['nilai_minimum'] <= $value->nilai_akhir) && ($value->nilai_akhir <= $key['nilai_maksimum'])) {
                                                    echo $key['grade'];
                                                }
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td style="padding-top:15px"><button type="button" id="button<?= $keys ?>"
                                            class="btn btn-primary"
                                            onclick="nilai(<?= $value->kode_khs_detail ?>,<?= $keys ?>)"
                                            value='<?= $value->nilai_akhir ?>'>
                                            Update
                                        </button>
                                    <td style="padding-top:15px" class="text-center">
                                        <button type="button" id="status<?= $keys ?>"
                                            class="btn btn-sm <?= $value->status_mbkm == 1 ? "btn-success" : "btn-danger" ?>"
                                            data-toggle="modal"
                                            onclick="status(<?= $value->kode_krs_detail ?>,<?= $keys ?>)">
                                            <?= $value->status_mbkm == 1 ? "Aktif" : "Non Aktif" ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- </div> -->
    </div>
</div>


<script>
    var kode_program_studi = '1';
    var matakuliah_id = '';
    var super_kelas_id = '';
    var default_landing = '<div style="height: 200px; border-radius: 20px; background-color: #00a7d0; padding: 20px">\n' +
        '            <p style="text-align: center; color: white"><i>"Landing data"</i></p>\n' +
        '        </div>';
    var loader = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
    function nilai(id, key) {
        na = $('#' + key).val();
        $.ajax({
            url: '<?= site_url('admin/mbkm/daftar/nilai_mhs') ?>/' + id + '/' + na,
            type: 'get',
            success: function (data) {
              console.log(data)
                if (jQuery.parseJSON(data).status) {
                    $("#button" + key).addClass("btn-primary");
                    $("#button" + key).removeClass("btn-success");
                    $("#" + key).removeClass("btn-danger");
                    $("#grade" + key).text(jQuery.parseJSON(data).grade);
                    swal({
                        title: '',
                        html: "Data Telah Diperbaharui",
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                    })
                    location.reload();
                }
            },
            error: function () {
                console.log('gagal');
            }
        })
    }
    function status(id, params) {

        $.ajax({
            url: '<?= site_url('admin/mbkm/daftar/status') ?>/' + id,
            type: 'get',
            success: function (data) {
                if (jQuery.parseJSON(data).status == 1) {
                    $("#status" + params).addClass("btn-success");
                    $("#status" + params).removeClass("btn-danger");
                    $("#status" + params).text('Aktif');
                } else {
                    $("#status" + params).addClass("btn-danger");
                    $("#status" + params).removeClass("btn-success");
                    $("#status" + params).text('Non Aktif');
                }
            }
        })
        swal({
            title: '',
            html: "Data Telah Diperbaharui",
            type: 'success',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
        })
    }
    $("input")
        .on("keyup", function () {
            if ($("#button" + $(this).attr("id")).val() === $(this).val()) {
                $($("#button" + $(this).attr("id"))).addClass("btn-primary");
                $($("#button" + $(this).attr("id"))).removeClass("btn-success");
                $(this).removeClass("btn-danger");
            } else {
                $($("#button" + $(this).attr("id"))).removeClass("btn-primary");
                $($("#button" + $(this).attr("id"))).addClass("btn-success");
                $(this).addClass("btn-danger");
            }
        })
        .trigger("keyup");
</script>