<div class="row">
    <div class="col-md-12">
        <div class="box box-solid flat">
            <div class="box-body">
                <div class="box-header with-border">
                    <div style="size : 20px;" ><b> KELAS - <?= e($data_kelas->nama_kelas) ?> </b>
                        (<?= e($data_kelas->kode_matakuliah) ?> - <?= e($data_kelas->nama_matakuliah) ?>)
                        <div class="pull-right">
                            <a href="<?= site_url('dosen/penilaian_kpat/penilaian_revisi') ?>" class="btn btn-success btn-xs flat"><i
                                    class="fa fa-arrow-circle-left"></i> Kembali</a>
                            </div>
                        </div>
                        </div>
                    <div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box box-info flat">
            <div class="box-body">
                <table class="table table-bordered demo-table">
                    <thead>
                        <tr>
                            <th rowspan="2">Pengajuan Nilai </th>
                            <th colspan="3"><center>Validasi</center></th>
                            <th rowspan="2" style="text-align: center;">Tindakan</th>
                        </tr>
                        <tr>
                            <th style="white-space: nowrap;width: 1px;">Dosen</th>
                            <th style="white-space: nowrap;width: 1px;">Prodi</th>
                            <th style="white-space: nowrap;width: 1px;">Dekan</th>
                        </tr>
                    </thead>                                                                                                      
                    <tbody>
                        <?php
                        foreach ($semua_kelas as $key => $row) :
                            ?>
                            <tr>
                            <td>Pengajuan Nilai Ke - <?= e($row->level) ?></td>
                            <td><?= nilai_validasi($row->status_dosen ? $row->status_dosen:"F") ?></td>
                            <td><?= nilai_validasi($row->status_prodi ? $row->status_prodi:"F") ?></td>
                            <td><?= nilai_validasi($row->status_dekan ? $row->status_dekan:"F") ?></td>
                            <td style="white-space: nowrap;width: 1px;">
                                <button class="btn btn-success btn-xs btn-flat" data-toggle="modal" data-target="#ModalNilai" onclick="show_nilai(<?= e($kelas_id) ?>,<?= e($row->level) ?>)"></i>Lihat Nilai</button>
                                <?php if ($row->status_prodi == 'F' ) :?>
                                    <button class="btn btn-danger btn-xs btn-flat" onclick="pembatalan(<?= e($kelas_id) ?>,<?= e($row->level) ?>)"></i>Delete</button>
                                <?php endif; ?>
                                <?php 
                                if ($row->status_dekan == 'T') {
                                    ?>
                                    <a href="<?= site_url('dosen/penilaian/cetak_nilai_revisi_kelas/' .$row->id_kelas.'/'.$row->level.'/'.$ta) ?>" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-print"></i> Cetak</a>
                                    <?php
                                } 
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box box-info flat">
            <?php if ($data_kelas->status_dosen != 'T'): ?>
            <div class="box-header with-border">
                <h3 class="box-title"><b> KELAS - <?= e($data_kelas->nama_kelas) ?> </b>
                    (<?= e($data_kelas->kode_matakuliah) ?> - <?= e($data_kelas->nama_matakuliah) ?>)</h3>
                    <br>
                <b> PENGISIAN NILAI KE - <?= e($data_kelas->level) ?> </b>
            </div>
            <div class="box-body">
                <?php if (count($data) > 0 ) : ?>
                <div class="table-responsive">
                    <table class="table demo-table">
                        <thead>
                            <tr style="background-color: #00c0ef">
                                <th style="text-align: center">NO.</th>
                                <th style="text-align: center">NIM</th>
                                <th style="text-align: center">NAMA</th>
                                <th style="text-align: center">NILAI HARIAN</th>
                                <th style="text-align: center">NILAI UTS</th>
                                <th style="text-align: center">NILAI UAS</th>
                                <th style="text-align: center">NILAI AKHIR</th>
                                <th style="text-align: center">GRADE</th>
                                <th style="text-align: center">KETERANGAN</th>
                                <th style="text-align: center">ACTION</th>
                                <th style="text-align: center">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($data as $key => $row) :
                                ?>
                                <tr>
                                    <td style="text-align: center; width: 3%"><?= $no++ ?>.</td>
                                    <td style="text-align: center"><?= e($row->nim) ?></td>
                                    <td><?= e($row->nama_mahasiswa) ?></td>
                                    <td >
                                        <input name="id<?= e($key) ?>" value = "<?= e($row->kode_khs_detail) ?>" hidden>
                                        <div class="form-group"
                                            style="margin: 0px">
                                            <input style="text-align: center" name="harian<?= e($key) ?>" type="text" id="<?= e($key) ?>"
                                                value="<?= e($row->mbkm_id ? '0' : ($row->harian ? $row->harian:$row->nilai_harian)) ?>"
                                                class="form-control harian-<?= e($row->kode_khs_detail)?>"
                                                <?= e($data_kelas->status_dosen == 'T' ? 'disabled':'') ?><?= e($row->mbkm_id ? 'disabled':'') ?>>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group "
                                            style="margin: 0px">
                                            <input style="text-align: center" name="uts<?= e($key) ?>" type="text" id="<?= e($key) ?>"
                                                value="<?=  e($row->mbkm_id ? '0' : ($row->uts ? $row->uts:$row->nilai_uts))?>"
                                                class="form-control uts-<?= e($row->kode_khs_detail)?> "
                                                <?= e($data_kelas->status_dosen == 'T' ? 'disabled':'') ?><?= e($row->mbkm_id ? 'disabled':'') ?>>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-group"
                                            style="margin: 0px">
                                            <input style="text-align: center" name="uas<?= e($key) ?>" type="text" id="<?= e($key) ?>"
                                                value="<?=  e($row->mbkm_id ? '0' : ($row->uas ? $row->uas:$row->nilai_uas))?>"
                                                class="form-control uas-<?= e($row->kode_khs_detail)?>"
                                                <?= e($data_kelas->status_dosen == 'T' ? 'disabled':'') ?>
                                                <?= e($row->mbkm_id ? 'disabled':'') ?>>
                                        </div>
                                    </td>
                                    <td style="text-align:center"><p id="na<?= e($key) ?>"><?= e(ceil($row->na ? $row->na:$row->nilai_akhir)) ?> </p></td>
                                    <td style="text-align:center"><p id="grade<?= e($key) ?>"><?= e(isset($row->grade) ? $row->grade : '-') ?></p></td>
                                    <td style="text-align: center">
                                        <button class="btn <?= e($row->ket ? "btn-success":"btn-primary") ?> btn-xs btn-flat" id="ket<?= e($key) ?>" data-toggle="modal" data-target="#ModalKet" onclick="show_ket(<?= e($row->kode_khs_detail) ?>,<?= e($kelas_id) ?>,<?= e($data_kelas->level) ?>,<?= e($key) ?>)"></i><?= e($row->ket ? "Ubah":"Tambah") ?></button>
                                    </td>
                                    <td style="text-align: center"><button type="submit" id="button<?= e($key) ?>" class="btn btn-success btn-xs flat"  onclick="nilai(<?= e($row->kode_khs_detail)?>,<?= e($kelas_id) ?>,<?= e($key) ?>,<?= e($data_kelas->level) ?>)" <?= e($data_kelas->status_dosen == 'T' ? 'disabled':'') ?>> Simpan </button></td>
                                    <td style="text-align:center"><?= e($row->block_id ? "Block": ($row->mbkm_id ? 'MBKM': '')) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="row" style="margin-top: 15px;margin-bottom: 15px">
                    <div class="col-md-12 col-xs-12" style="padding: 15px">
                        <button class="btn btn-success pull-right" onclick="selesai(<?= e($kelas_id) ?>,<?= e($key) ?>,<?= e($data_kelas->level) ?>)" ><i class="fa fa-check-square-o"></i> Kirim Nilai</button>
                        <button class="btn btn-danger pull-right" style="margin: 0 15px 0 0" onclick="pembatalan(<?= e($kelas_id) ?>,<?= e($data_kelas->level) ?>)" ><i class="fa fa-check-square-o"></i> Batal</button>
                    </div>
                </div>
                <?php else: ?>
                <div class="row">
                    <div class="col-md-3 col-xs-12">
                        <button class="btn btn-success" onclick="new_penilaian(<?= e($kelas_id) ?>,<?= e($data_kelas->level) ?>)" ><i class="fa fa-check-square-o"></i> Pengajuan Baru</button>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ModalNilai" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Nilai Mahasiswa</h3>
      </div>
      <div class="modal-body">
        <div id='nilai_mahasiswa'></div>
        <!-- <p>Modal body text goes here.</p> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade " id="ModalKet" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Keterangan</h3>
      </div>
      <div class="modal-body">
        <textarea class="form-control"  name="" cols="10" rows="5" id='ket_nilai'>

        </textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         <button type="button" class="btn btn-success" onclick="simpan_ket()" >Simpan</button>
      </div>
    </div>
  </div>
</div>
<script>
    function nilai(id,kelas,key,level) {
        $.ajax({
            url: '<?= site_url('dosen/penilaian_kpat/nilai_revisi')?>',
            type: 'post',
            data: {
                "kelas":kelas,
                "level":level,
                "id": id,
                "harian": $('.harian-'+id).val(),
                "uts": $('.uts-'+id).val(),
                "uas": $('.uas-'+id).val(),
                "ket": $('.ket-'+id).val(),
            },
            success: function (data) {
                if (jQuery.parseJSON(data).status) {
                    $("#button"+key).addClass("btn-success");
                    $("#button"+key).removeClass("btn-primary");
                    $("#grade"+key).text(jQuery.parseJSON(data).data.grade);
                    $("#na"+key).text(Math.ceil(jQuery.parseJSON(data).data.na));
                }else{
                    swal({
                        title: '',
                        html: "Data presentasi harus diisi pada menu Persentasi Penilaian",
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonText: 'Tidak',
                    })
                }
            },
            error: function () {
                console.log('gagal');
            }
        })
    }
    
    $("input").keyup(function () {
        $($("#button"+$(this).attr("id"))).addClass("btn-primary");
        $($("#button"+$(this).attr("id"))).removeClass("btn-success");
    });

    function selesai(param1,param2,param3) {
        tmp = {};
        data = {};
        for (let index = 0; index <= param2; index++) {
            tmp = {};
            tmp['id'] = document.getElementsByName('id'+index)[0].value;
            tmp['harian'] = document.getElementsByName('harian'+index)[0].value;
            tmp['uts'] = document.getElementsByName('uts'+index)[0].value;
            tmp['uas'] = document.getElementsByName('uas'+index)[0].value;
            data['data'+index] = tmp;
        }
        data['kelas'] = param1;
        data['jum'] = param2;
        data['level'] = param3;
       swal({
            title: '',
            html: "Menekan tombol <strong>Kirim Nilai</strong> berarti data nilai mahasiswa sudah selesai di inputkan dan siap untuk di validasi oleh <strong>Kaprodi dan Dekan</strong>." +
                    "Nilai yang sudah selesai di input tidak bisa diubah kembali. Tekan <strong>YA</strong> untuk melanjutkan dan <strong>Tidak</strong> untuk membatalkan.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            $.ajax({
                url: '<?= site_url('dosen/penilaian_kpat/revisi_dosen_selesai')?>',
                type: 'post',
                data: data,
                success: function (data) {
                    location.reload();
                },
                error: function () {
                    console.log('gagal');
                }
            })
        });
    }
    function new_penilaian(param1,param2) {
        swal({
            title: '',
            html: "Menekan tombol <strong>Penilaian Baru</strong> berarti ingin membuat data penilai terbaru." +
                    "Tekan <strong>YA</strong> untuk melanjutkan dan <strong>Tidak</strong> untuk membatalkan.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            $.ajax({
                url: '<?= site_url('dosen/penilaian_kpat/revisi_new_penilaian')?>',
                type: 'post',
                data: {
                    "kelas": param1,
                    "level": param2,
                },
                success: function (data) {
                    location.reload();
                },
                error: function () {
                    console.log('gagal');
                }
            })
        });
        
    }
    function pembatalan(param1,param2) {
        swal({
            title: '',
            html: " <strong>Anda Yakin Ingin Menghapus Data ini ?</strong> ",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            $.ajax({
                url: '<?= site_url('dosen/penilaian_kpat/revisi_pebatalan_penilaian')?>',
                type: 'post',
                data: {
                    "kelas": param1,
                    "level": param2,
                },
                success: function (data) {
                    location.reload();
                },
                error: function () {
                    console.log('gagal');
                }
            })
        });
    }
    function show_nilai(param1,param2) {
        $.ajax({
            url: '<?= site_url('dosen/penilaian_kpat/revisi_nilai_mahasiswa')?>',
            type: 'post',
            data: {
                "kelas": param1,
                "level": param2,
            },
            success: function (res) {
                $("#nilai_mahasiswa").html(res);
            },
            error: function () {
                console.log('gagal');
            }
        })
    }
    var id, kelas, level, key_ket;
    function show_ket(param1,param2,param3,param4) {
        this.id = param1;
        this.kelas = param2;
        this.level = param3;
        this.key_ket = param4;
        $.ajax({
            url: '<?= site_url('dosen/penilaian_kpat/revisi_ket')?>',
            type: 'post',
            data: {
                "id": param1,
                "kelas": param2,
                "level": param3,
            },
            success: function (data) {
                if (jQuery.parseJSON(data)) {
                    $('#ket_nilai').val(jQuery.parseJSON(data).ket);
                }else{
                    $('#ket_nilai').val('');
                }
            },
            error: function () {
                console.log('gagal');
            }
        })
    }
    function simpan_ket() {
        $.ajax({
            url: '<?= site_url('dosen/penilaian_kpat/revisi_ket_val')?>',
            type: 'post',
            data: {
                "id": id,
                "kelas": kelas,
                "level": level,
                "ket": $('#ket_nilai').val(),
            },
            success: function (data) {
                // console.log(data);
                if (jQuery.parseJSON(data).status) {
                    swal({
                        title: '',
                        html: "Komentar Telah Sukses",
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                    })
                    $('#ket'+key_ket).text('Edit');
                    $('#ModalKet').modal('hide');
                    $($('#ket'+key_ket)).removeClass("btn-primary");
                    $($('#ket'+key_ket)).addClass("btn-success");
                }
            },
            error: function () {
                console.log('gagal');
            }
        })
    
    }
</script>