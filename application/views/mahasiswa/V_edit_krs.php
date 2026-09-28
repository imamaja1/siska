<?= $this->session->flashdata('info') ? $this->session->flashdata('info') : '' ?>
<div class="box box-primary flat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file-text-o"></i> Kartu Rencana Studi</h3>
    </div>
    <div class="box-body">
        <form id="form-krs-mahasiswa" action="<?= site_url('mahasiswa/Krs/simpan_ubah') ?>" method="post" name="krs_form">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <input type="hidden" name="total_sks_dipilih" id="total-sks-dipilih">
            <?php foreach ($data_matakuliah as $row): ?>

                <div class="mhs-semester-label">Semester <?= $row['semester'] ?></div>
                <?php if (count($row['data']) > 0) : ?>
                    <div class="table-responsive mhs-table-wrap"><table class="table mhs-table">
                        <thead>
                        <tr>
                            <th  width="20" rowspan="2" style="padding-bottom: 25px;">NO.</th>
                            <th  width="200" rowspan="2" style="padding-bottom: 25px;">KODE MK</th>
                            <th  rowspan="2" style="padding-bottom: 25px;">MATAKULIAH</th>
                            <th  colspan="3">SKS</th>
                            <th  rowspan="2" style="padding-bottom: 25px;">B</th>
                            <th  rowspan="2" style="padding-bottom: 25px;">U</th>
                        </tr>
                        <tr>
                            <th >T</th>
                            <th >PK</th>
                            <th >PT</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;
                        foreach ($row['data'] as $d) { ?>
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
                                        <input type="checkbox" <?= in_array($d['id_matakuliah'],$krs_exis) ? 'checked' : '' ?> id="cek-<?= $d['id_matakuliah'] ?>" class="check-kpat" onclick="calculate('<?= $d['id_matakuliah'] ?>')" name="ulang[]" value="<?= $d['id_matakuliah'] ?>,<?= $d['kode_matakuliah'] ?>,<?= $d['sks_teori']+$d['sks_praktek']+$d['sks_praktikum'] ?>">
                                    </td>
                                <?php else : ?>
                                    <td align="center" width="100">
                                        <input type="checkbox" <?= in_array($d['id_matakuliah'],$krs_exis) ? 'checked' : '' ?> id="cek-<?= $d['id_matakuliah'] ?>" class="check-kpat" onclick="calculate('<?= $d['id_matakuliah'] ?>')" name="baru[]" value="<?= $d['id_matakuliah'] ?>,<?= $d['kode_matakuliah'] ?>,<?= $d['sks_teori']+$d['sks_praktek']+$d['sks_praktikum'] ?>">
                                    </td>
                                    <td width="100"></td>
                                <?php endif; ?>
                            </tr>
                        <?php }
                        if (isset($row['pilihan'])) : ?>
                            <tr>
                                <td align="center" colspan="8"> Pilihan </td>
                            </tr>
                            <?php foreach ($row['pilihan'] as $pilih) : ?>
                                <tr>
                                    <td align="center"><?= $i++ ?>.</td>
                                    <td align="center" id="kode-matakuliah-<?= e($pilih['kode_nama_kurikulum']) ?>"><?= e($pilih['kode_matakuliah']) ?></td>
                                    <?php if(substr($this->session->userdata('nim'),0,2) == '16') : ?>
                                        <?php if($pilih['kode_matakuliah'] == 'TSKB351435') : ?>
                                            <td>Animasi 2 Dimensi</td>
                                        <?php else : ?>
                                            <td ><?= e($pilih['nama_matakuliah']) ?></td>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <td ><?= e($pilih['nama_matakuliah']) ?></td>
                                    <?php endif; ?>
                                    <td align="center" width="100"><?= $pilih['sks_teori'] ?></td>
                                    <td align="center" width="100"><?= $pilih['sks_praktek'] ?></td>
                                    <td align="center" width="100"><?= $pilih['sks_praktikum'] ?></td>
                                    <?php if (in_array($pilih['id_matakuliah'], $krs_lalu)) : ?>
                                        <td align="center" width="100"></td>
                                        <td align="center" width="100">
                                            <input type="checkbox" <?= in_array($pilih['id_matakuliah'],$krs_exis) ? 'checked' : '' ?> id="cek-<?= $pilih['id_matakuliah'] ?>" class="check-kpat" onclick="calculate('<?= $pilih['id_matakuliah'] ?>')" name="ulang[]" value="<?= $pilih['id_matakuliah'] ?>,<?= $pilih['kode_matakuliah'] ?>,<?= $pilih['sks_teori']+$pilih['sks_praktek']+$pilih['sks_praktikum'] ?>">
                                        </td>
                                    <?php else : ?>
                                        <td align="center" width="100">
                                            <input type="checkbox" <?= in_array($pilih['id_matakuliah'],$krs_exis) ? 'checked' : '' ?> id="cek-<?= $pilih['id_matakuliah'] ?>" class="check-kpat" onclick="calculate('<?= $pilih['id_matakuliah'] ?>')" name="baru[]" value="<?= $pilih['id_matakuliah'] ?>,<?= $pilih['kode_matakuliah'] ?>,<?= $pilih['sks_teori']+$pilih['sks_praktek']+$pilih['sks_praktikum'] ?>">
                                        </td>
                                        <td width="100"></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach;
                        endif;
                        ?>

                        </tbody>
                    </table></div>
                <?php else : ?>
                    <p class="alert alert-info"><strong><i class="fa fa-info-circle"></i> Data belum ada, silahkan lakukan pengisian data</strong>
                    </p>
                <?php endif; ?>
                
            <?php endforeach; ?>
            <div id="loading">
                <a href="<?= site_url('mahasiswa/krs') ?>" class="btn btn-danger flat"><i class="fa fa-times"></i> Batal</a>
                <button type="submit" name="submit" id="submit" class="btn btn-primary flat"><i class="fa fa-check-square-o"></i> Simpan</button>
            </div>
        </form>
        
        <h4>Keterangan</h4>
        <p><b>Status Pengambilan Matakuliah</b><br />
            <b>B</b> : Baru &nbsp;&nbsp;&nbsp; <b>U</b> : Ulang<br />
            <b>Jenis SKS Matakuliah</b><br />
            <b>T</b> : Teori &nbsp;&nbsp;&nbsp; <b>PK</b> : Praktek &nbsp;&nbsp;&nbsp; <b>PT</b> : Praktikum<br />
            <b>Kode Kompetensi khusus untuk Strata 1 (S1)</b><br />
            <b>RPL</b> : Rekayasa Perangkat Lunak &nbsp;&nbsp;&nbsp;&nbsp; <b>JK</b> : Jaringan Komputer &nbsp;&nbsp;&nbsp;&nbsp; <b>M</b> : Multimedia
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
        $('#kotak-pesan').removeClass('is-ok is-over').html('Daftar Matakuliah');
    }

    
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
                  
                    $.each(obj.res, function( index, value ) {
//                        alert( index + ": " + value.la );
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
                            //total += +(substr(elems[i].value,4,1));

                            str = elems[i].value;
                            ex = str.split(',');
                            //sks = ex[1].substr(4,1);
                            sks = ex[2];
                          	//console.log(sks);
                            total += +(sks)
                        }
                    }

                    sisa = jumlah_maksimum_sks - total;
                    $('#total-sks-dipilih').val(total);

                    if (total > jumlah_maksimum_sks)
                    {
                        $('#kotak-pesan').addClass('is-over').removeClass('is-ok');
                        $('#kotak-pesan').html('Jumlah SKS matakuliah yang telah Anda pilih adalah <b>'+ total +' SKS</b><br>melebihi jumlah maksimum <b>'+ jumlah_maksimum_sks +' SKS</b> yang dapat diambil.');
                        $('#submit').prop('disabled',true);
                    }
                    else if (total == jumlah_maksimum_sks)
                    {
                        $('#kotak-pesan').addClass('is-ok').removeClass('is-over');
                        $('#kotak-pesan').html('Jumlah SKS matakuliah yang Anda pilih telah sesuai<br>dengan jumlah maksimum <b>'+ total +' SKS</b> yang dapat diambil.');
                        $('#submit').prop('disabled',false);
                    }
                    else
                    {
                        $('#kotak-pesan').removeClass('is-over is-ok');
                        $('#kotak-pesan').html('Jumlah SKS matakuliah yang telah Anda pilih adalah <b>'+ total +' SKS</b><br>masih tersisa <b>'+ sisa +' SKS</b> yang dapat diambil.');
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
        }else {
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
