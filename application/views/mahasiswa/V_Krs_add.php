<?= $this->session->flashdata('info') ? $this->session->flashdata('info') : '' ?>
<div class="box box-solid flat">
    <div class="box-body">
        <strong>Dosen Wali :</strong> <span class="badge bg-navy"><?= e($dosen_wali) ?></span>&nbsp;
        <?php if (isset($dosen_perwakilan)) : ?>
            <strong>Dosen Perwakilan :</strong> <span class="badge bg-orange"><?= e($dosen_perwakilan) ?></span>
        <?php endif; ?>
    </div>
</div>
<div class="box box-solid flat">
    <div class="box-body">
        <?php $new_semester=0; foreach ($krs_mhs as $row) : ?>
            <?php if($new_semester == 0) $new_semester = $row->kode_tahun_akademik   ?>
            <a href="<?= site_url('mahasiswa/krs/old/'.$row->kode_tahun_akademik.'/'.$row->semester) ?>" class="btn bg-navy flat btn-xs"><i class="fa fa-arrow-circle-right"></i> | SEMESTER  <?= $row->semester ?></a>
        <?php endforeach; ?>
        <a href="<?= site_url('mahasiswa/krs/index/') ?>" class="btn bg-navy flat btn-xs"><i class="fa fa-arrow-circle-right"></i> | SEMESTER <?= $semester ?></a>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-body table-responsive">
        <p style="text-align: center"><strong>KARTU RECANA STUDI (KRS) JENJANG <?= strtoupper(e($prodi->nama_program_studi)) ?> (<?= strtoupper(e($prodi->singkatan_program_studi)) ?>)</strong></p>
        <p style="text-align: center"><strong>SEMESTER <?= $tahun_akademik->semester % 2 == (0)? "GENAP" : "GANJIL" ?></strong></p>
        <form id="form-krs-mahasiswa" action="<?= site_url('mahasiswa/Krs/simpan_krs') ?>" method="post" name="krs_form">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <input type="hidden" name="total_sks_dipilih" id="total-sks-dipilih">
            <?php foreach ($data_matakuliah as $row): ?>

            <?php if (count($row['data']) > 0) : ?>
                <p><strong>SEMESTER <?= $row['semester'] ?></strong></p>
                <table class="table demo-table">
                    <thead>
                    <tr>
                        <th id="th" width="20" rowspan="2" style="padding-bottom: 25px;">NO.</th>
                        <th id="th" width="200" rowspan="2" style="padding-bottom: 25px;">KODE MK</th>
                        <th id="th" rowspan="2" style="padding-bottom: 25px;">MATAKULIAH</th>
                        <th id="th" colspan="3">SKS</th>
                        <th id="th" rowspan="2" style="padding-bottom: 25px;">B</th>
                        <th id="th" rowspan="2" style="padding-bottom: 25px;">U</th>
                    </tr>
                    <tr>
                        <th id="th">T</th>
                        <th id="th">PK</th>
                        <th id="th">PT</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1;
                    $batas = array();
                    foreach ($row['data'] as $d) {
                       	if (isset($d['jenis']) && $d['jenis'] == '1') {
                            continue;
                        }
                        if (in_array($d['kode_matakuliah'],$batas) || $d['kode_matakuliah'] == null) {
                            $batas[] = $d['kode_matakuliah'];
                            continue;
                        }
                        $batas[] = $d['kode_matakuliah'];
                      ?>
                        <tr>
                            <td align="center"><?= $i++ ?>.</td>
                            <td align="center" id="kode-matakuliah-<?= e($d['kode_nama_kurikulum']) ?>"><?= e($d['kode_matakuliah']) ?></td>
                            <td ><?= e($d['nama_matakuliah']) ?></td>
                            <td align="center" width="100"><?= $d['sks_teori'] ?></td>
                            <td align="center" width="100"><?= $d['sks_praktek'] ?></td>
                            <td align="center" width="100"><?= $d['sks_praktikum'] ?></td>
                        <?php if (in_array($d['id_matakuliah'], $krs_lalu)) : ?>
                            <td align="center" width="100"></td>
                            <td align="center" width="100">
                                <input type="checkbox" id="cek-<?= $d['id_matakuliah'] ?>" class="check-kpat" onclick="calculate('<?= $d['id_matakuliah'] ?>')" name="ulang[]" value="<?= $d['id_matakuliah'] ?>,<?= $d['sks_teori']+$d['sks_praktek']+$d['sks_praktikum'] ?>">
                            </td>
                        <?php else : ?>
                            <td align="center" width="100">
                                <input type="checkbox" id="cek-<?= $d['id_matakuliah'] ?>" class="check-kpat" onclick="calculate('<?= $d['id_matakuliah'] ?>')" name="baru[]" value="<?= $d['id_matakuliah'] ?>,<?= $d['sks_teori']+$d['sks_praktek']+$d['sks_praktikum'] ?>">
                            </td>
                            <td width="100"></td>
                        <?php endif; ?>
                        </tr>
                    <?php } ?>
                    <?php
                        $filtered_data = array_filter($row['data'], function($item) {
                            return ($item['jenis'] ?? '') == 1;
                        });
                        if (count($filtered_data) > 0 && !isset($row['pilihan'])):
                    ?>
                    <tr>
                        <td align="center" colspan="8"> Pilihan </td>
                    </tr>
                    <?php endif; ?>
                    <?php $i = 1;
                    foreach ($row['data'] as $key => $d) { 
                        if (isset($d['jenis']) && $d['jenis'] == '0') {
                            continue;
                        }
                        if (in_array($d['kode_matakuliah'],$batas) || $d['kode_matakuliah'] == null) {
                            $batas[] = $d['kode_matakuliah'];
                            continue;
                        }
                        $batas[] = $d['kode_matakuliah'];
                        ?>
                        <tr>
                            <td align="center"><?= $i++ ?>.</td>
                            <td align="center" id="kode-matakuliah-<?= e($d['kode_nama_kurikulum']) ?>"><?= e($d['kode_matakuliah']) ?></td>
                            <td ><?= e($d['nama_matakuliah']) ?></td>
                            <td align="center" width="100"><?= $d['sks_teori'] ?></td>
                            <td align="center" width="100"><?= $d['sks_praktek'] ?></td>
                            <td align="center" width="100"><?= $d['sks_praktikum'] ?></td>
                        <?php if (in_array($d['id_matakuliah'], $krs_lalu)) : ?>
                            <td align="center" width="100"></td>
                            <td align="center" width="100">
                                <input type="checkbox" id="cek-<?= $d['id_matakuliah'] ?>" class="check-kpat" onclick="calculate('<?= $d['id_matakuliah'] ?>')" name="ulang[]" value="<?= $d['id_matakuliah'] ?>,<?= $d['sks_teori']+$d['sks_praktek']+$d['sks_praktikum'] ?>">
                            </td>
                        <?php else : ?>
                            <td align="center" width="100">
                                <input type="checkbox" id="cek-<?= $d['id_matakuliah'] ?>" class="check-kpat" onclick="calculate('<?= $d['id_matakuliah'] ?>')" name="baru[]" value="<?= $d['id_matakuliah'] ?>,<?= $d['sks_teori']+$d['sks_praktek']+$d['sks_praktikum'] ?>">
                            </td>
                            <td width="100"></td>
                        <?php endif; ?>
                        </tr>
                    <?php } ?>
                    <?php if (isset($row['pilihan'])) : ?>
                        <tr>
                            <td align="center" colspan="8"> Pilihan </td>
                        </tr>
                    <?php foreach ($row['pilihan'] as $pilih) : ?>
                            <tr>
                                <td align="center"><?= $i++ ?>.</td>
                                <td align="center" id="kode-matakuliah-<?= e($pilih['kode_nama_kurikulum']) ?>"><?= e($pilih['kode_matakuliah']) ?></td>
                                <td ><?= e($pilih['nama_matakuliah']) ?></td>
                                <td align="center" width="100"><?= $pilih['sks_teori'] ?></td>
                                <td align="center" width="100"><?= $pilih['sks_praktek'] ?></td>
                                <td align="center" width="100"><?= $pilih['sks_praktikum'] ?></td>
                                <?php if (in_array($pilih['id_matakuliah'], $krs_lalu)) : ?>
                                    <td align="center" width="100"></td>
                                    <td align="center" width="100">
                                        <input type="checkbox" id="cek-<?= $pilih['id_matakuliah'] ?>" class="check-kpat" onclick="calculate('<?= $pilih['id_matakuliah'] ?>')" name="ulang[]" value="<?= $pilih['id_matakuliah'] ?>,<?= $pilih['sks_teori']+$pilih['sks_praktek']+$pilih['sks_praktikum'] ?>">
                                    </td>
                                <?php else : ?>
                                    <td align="center" width="100">
                                        <input type="checkbox" id="cek-<?= $pilih['id_matakuliah'] ?>" class="check-kpat" onclick="calculate('<?= $pilih['id_matakuliah'] ?>')" name="baru[]" value="<?= $pilih['id_matakuliah'] ?>,<?= $pilih['sks_teori']+$pilih['sks_praktek']+$pilih['sks_praktikum'] ?>">
                                    </td>
                                    <td width="100"></td>
                                <?php endif; ?>
                            </tr>
                    <?php endforeach;
                    endif;
                    ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <hr>
        <?php endforeach; ?>
            <div id="loading">
                <a href="#" class="btn btn-danger flat" onclick="batal()"><i class="fa fa-times"></i> Batal</a>
                <button type="submit" name="submit" id="submit" class="btn btn-primary flat"><i class="fa fa-check-square-o"></i> Simpan</button>
            </div>
        </form>
        <hr>
        <h4>Keterangan</h4>
        <p><b>Status Pengambilan Matakuliah</b><br />
            <b>B</b> : Baru &nbsp;&nbsp;&nbsp; <b>U</b> : Ulang<br />
            <b>Jenis SKS Matakuliah</b><br />
            <b>T</b> : Teori &nbsp;&nbsp;&nbsp; <b>PK</b> : Praktek &nbsp;&nbsp;&nbsp; <b>PT</b> : Praktikum
        </p>
    </div>
</div>

<!-- kotak peas -->
<div class="row" id="kotak-pesan">
    Daftar Matakuliah
</div>
<!--script-->
<script type='text/javascript'>
    function batal() {
        $('.check-kpat').attr('checked',false);
        $('#kotak-pesan').html('Daftar Maatakuliah');
    }

    $(window).bind("load", function() {
        $('#kotak-pesan').animate({bottom:"50px"}, 1000);
    });
</script>
<script type="text/javascript">
    var calculate;
    function calculate(id_matakuliah)
    {
        var elems = document.forms['krs_form'].elements;
        var total = 0;
        var jumlah_maksimum_sks = 0;
        var sisa = 0;
        var cek = "#cek-"+id_matakuliah+"";
        jumlah_maksimum_sks = <?= $jumlah_maksimum_sks['beban_sks'] ?>;//document.getElementById('jumlah_maksimum_sks').value;
        // console.log(elems[1]);
        $.ajax({
            url : "<?= site_url('mahasiswa/krs/cek_prasyarat') ?>",
            type : "POST",
            data : "id_matakuliah="+id_matakuliah,
            success : function (data) {
                var obj = JSON.parse(data);
                if (obj.pra == false)
                {
                    if ($(cek).prop('checked') == false)
                    {
                        $("#cek-"+obj.mak_ambil).attr('checked', false);
                    }
                }
                //console.log(obj.semester);
                if (obj.status==false)
                {
                  	if (obj.semester == '2') {
                        Lobibox.notify('info', {
                            rounded: true,
                            sound: false,
                            delayIndicator: false,
                            msg: 'Mahasiswa Semester 2 hanya dapat mencetang matakuliah Semeseter 2',
                            position: 'top right',
                        });
                        $(cek).attr('checked', false);
                    }else{
                    //console.log(obj.res);
                    $.each(obj.res, function( index, value ) {
                       alert( index + ": " + value.la );
                        if (value.la == false)
                        {
                            if ($("#cek-"+value.kode_prasyarat).prop('checked') == false)
                            {
                                Lobibox.notify('info', {
                                    rounded: true,
                                    sound: false,
                                    delayIndicator: false,
                                    msg: value.msg,
                                    position: 'top right',
                                });
                                $(cek).attr('checked', false);
                            }
                        }else{
                            Lobibox.notify('info', {
                                rounded: true,
                                sound: false,
                                delayIndicator: false,
                                msg: value.msg,
                                position: 'top right',
                            });
                            $(cek).attr('checked', false);
                        }
                    });
                    }
                }else{
                    for(var i=0;i<elems.length;i++)
                    {
                        if (elems[i].checked)
                        {
                            str = elems[i].value;
                            ex = str.split(',');
                            // sks = ex[2].substr(4,1);
                            sks = ex[1];
                            total += +(sks);
                        }
                    }
                    sisa = jumlah_maksimum_sks - total;
                    $('#total-sks-dipilih').val(total);

                    if (total > jumlah_maksimum_sks)
                    {
                        $('#kotak-pesan').html('<font color=red>Jumlah SKS matakuliah yang telah Anda pilih adalah <b>'+ total +' SKS</b>, melebihi jumlah maksimum <b>'+ jumlah_maksimum_sks +' SKS</b> yang dapat diambil.</font>');
                        $('#submit').prop('disabled',true);
                    }
                    else if (total == jumlah_maksimum_sks)
                    {
                        $('#kotak-pesan').html('<font color=green>Jumlah SKS matakuliah yang Anda pilih telah sesuai dengan jumlah maksimum <b>'+ total +' SKS</b> yang dapat diambil.</font>');
                        $('#submit').prop('disabled',false);
                    }
                    else
                    {
                        $('#kotak-pesan').html('Jumlah SKS matakuliah yang telah Anda pilih adalah <b>'+ total +' SKS</b>, masih tersisa <b>'+ sisa +' SKS</b> yang dapat diambil.');
                        $('#submit').prop('disabled',false);
                    }
                }
            },
            error : function () {
                console.log('Kamu gagal');
            },
        });
    }

    $('#form-krs-mahasiswa').bind('submit', function (e) {
        if ($('.check-kpat:checked').length < 1)
        {
            swal('Gagal','Pilih minimal satu matakuilah','error');
            return false;
        }else{

            var button = $('#loading');
            // Disable the submit button while evaluating if the form should be submitted
            button.html('<button class="btn btn-default btn-sm flat" disabled><i class="fa fa-refresh fa-spin"></i> Permintaan sedang diproses..</button>');
            var valid = true;

            // Do stuff (validations, etc) here and set
            // "valid" to false if the validation fails

            if (!valid) {
                // Prevent form from submitting if validation failed
                e.preventDefault();

                // Reactivate the button if the form was not submitted
                button.html('<button class="btn btn-default btn-sm flat" disabled><i class="fa fa-refresh fa-spin"></i> Permintaan sedang diproses..</button>');

            }
        }
    });
</script>
