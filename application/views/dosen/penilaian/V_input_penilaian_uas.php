<div class="row">
    <div class="col-md-12">
        <div class="box box-solid flat">
            <div class="box-body">
                <div class="pull-right">
                    <a href="<?= site_url('dosen/penilaian/penilaian_harian_uas') ?>" class="btn btn-success btn-xs flat"><i
                            class="fa fa-arrow-circle-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box box-info flat">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-chain-broken"></i><b> KELAS - <?= $data_kelas->nama_kelas ?> </b>
                    (<?= $data_kelas->kode_matakuliah ?> - <?= $data_kelas->nama_matakuliah ?>)</h3>
                <!-- <?php if (count($persentasi_nilai) != 0): ?>
                    <div class="box-tools">
                        <a href="#" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal-persentasi-nilai"><i
                                class="fa fa-edit"></i> Persentasi Penilaian</a>
                    </div>
                <?php endif; ?> -->
            </div>
            <div class="box-body">
                <?php if (count($data) > 0): ?>
                    <div class="table-responsive">
                        <table class="table demo-table data-table">
                            <thead>
                                <tr style="background-color: #00c0ef">
                                    <th style="text-align: center">NO.</th>
                                    <th style="text-align: center">NIM</th>
                                    <th style="text-align: center">NAMA</th>
                                    <th style="text-align: center" class="no-sort">NILAI HARIAN</th>
                                    <th style="text-align: center" class="no-sort">NILAI UTS</th>
                                    <th style="text-align: center" class="no-sort">NILAI UAS</th>
                                    <th style="text-align: center" class="no-sort">NILAI AKHIR</th>
                                    <th style="text-align: center" class="no-sort">STATUS MAHASISWA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($data as $row):
                                    ?>
                                    <tr>
                                        <td style="text-align: center; width: 3%"><?= $no++ ?>.</td>
                                        <td style="text-align: center">
                                            <?= $row->nim ?>
                                        </td>
                                        <td><?= $row->nama_mahasiswa ?></td>
                                        <td>
                                            <div class="form-group nilai-harian-<?= $row->kode_khs_detail ?> "
                                                 style="margin: 0px">
                                                <input type="text"
                                                       onchange="harian(<?= $row->kode_khs_detail ?>,<?= $row->kode_krs_detail ?>, this)"
                                                       value="<?= $row->dummy_harian ?>"
                                                       class="form-control input-harian-<?= $row->kode_khs_detail ?>"
                                                       placeholder="Enter ..." <?php if($exp) echo 'disabled' ?> >
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group nilai-uts-<?= $row->kode_khs_detail ?> " style="margin: 0px">
                                                <input <?php if($homebase != 8) echo 'readonly' ?>  type="text"
                                                       onchange="uts(<?= $row->kode_khs_detail ?>,<?= $row->kode_krs_detail ?>, this)"
                                                       value="<?= $row->dummy_uts ?>"
                                                       class="form-control input-uts-<?= $row->kode_khs_detail ?>"
                                                       placeholder="Enter ..." <?php if($exp) echo 'disabled' ?>>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group nilai-uas-<?= $row->kode_khs_detail ?> " style="margin: 0px">
                                                <input type="text"
                                                       onchange="uas(<?= $row->kode_khs_detail ?>,<?= $row->kode_krs_detail ?>, this)"
                                                       value="<?= $row->dummy_uas ?>"
                                                       class="form-control input-uas-<?= $row->kode_khs_detail ?>"
                                                       placeholder="Enter ..." <?php if($exp) echo 'disabled' ?>>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group nilai-<?= $row->kode_khs_detail ?> " style="margin: 0px">
                                                <input readonly type="text"
                                                       onchange="nilai(<?= $row->kode_khs_detail ?>,<?= $row->kode_krs_detail ?>, this)"
                                                       value="<?= $row->dummy_na ?>"
                                                       class="form-control input-<?= $row->kode_khs_detail ?>" placeholder="0">
                                            </div>
                                        </td>
                                        <td style="text-align:center"><?= $row->block_id ? "Block":"Aktif" ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($data_kelas->status_nilai != 'T' && !$exp): ?>
                        <div class="row" style="margin-top: 15px">
                            <div class="col-md-3 col-xs-12">
                                <a href="#" onclick="selesai()" class="btn btn-success"><i class="fa fa-check-square-o"></i>
                                    Kirim Nilai</a>
                            </div>
                            <div class="col-md-9 col-xs-12 text-right text-danger">Nilai yang diinputkan akan otomatis
                                tersimpan. Tombol kirim nilai di klik jika semua nilai
                                telah selesai diinputkan dan siap untuk di validasi oleh Ketua Prodi dan Dekan</div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!--modal show info-->
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><i class="fa fa-info-circle"></i> Info</h4>
            </div>
            <div class="modal-body">
                <code style="color: black">
                    <dl class="dl-horizontal">
                        <dt>Teori</dt>
                        <dd>= Nilai Teori <b>X</b> Jumlah SKS Teori</dd>
                        <dt>Praktikum</dt>
                        <dd>= Nilai Praktikum <b>X</b> Jumlah SKS Praktikum</dd>
                        <hr>
                        <dt>Total SKS</dt>
                        <dd>= SKS Teori <b>+</b> SKS Praktikum</dd>
                        <dt>Total Nilai</dt>
                        <dd>= Teori <b>+</b> Praktikum</dd>
                        <hr>
                        <dt>Nilai Akhir</dt>
                        <dd>= Total Nilai <b>/</b> Total SKS</dd>
                    </dl>
                </code>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--modal persentasi nilai-->
    <div class="modal fade" id="modal-persentasi-nilai">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form action="<?= site_url('dosen/penilaian/update_persentasi_penilaian/' . $persentasi_nilai->id) ?>"
                      method="post">
                    <div class="modal-header">
                
                        <h4 class="modal-title"><i class="fa fa-warning"></i> PEMBERITAHUAN </h4>
                    </div>
                    <div class="modal-body">
                        <div id="flash-info">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>
                            Tutup
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script>
    var cek_persentasi_nilai = <?= count($persentasi_nilai) ?>;
    var notif = ' <div class="alert alert-warning alert-dismissible flat">\n' +
            '                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>\n' +
            '                            Nilai tidak akan bisa dinputkan sebelum menginputkan <b>Persentasi penilaian</b> pada kelas ini. Pengisian presentasi penilaian dapat dilakukan pada menu <b>Penilaian <i class="fa fa-arrow-right"></i> Presentasi Penilaian<b>.\n'
            '                        </div>';

    function nilai(id, krs, cont) {
        var nilai = $(cont).val();
        if (cek_persentasi_nilai == 0) {
            $(cont).val('');
            $("#flash-info").html(notif);
            $("#modal-persentasi-nilai").modal('show');
        } else {
            $(".nilai-" + id).click(function (e) {
                $(this).tooltip("hide");
            });
            // console.log("ini nilainya : " + nilai);

            if (nilai) {
                if ($.isNumeric(nilai)) {
                    if (nilai >= 0 && nilai <= 100) {
                        $.ajax({
                            url: "<?= site_url('dosen/penilaian/nilai') ?>/" + id + "/" + krs,
                            type: "POST",
                            data: "nilai_akhir=" + nilai,
                            success: function (res) {
                                if (res) {
                                    $(".nilai-" + id).removeClass("has-error").addClass("has-success");
                                    //     lokasi untuk cek
                                } else {
                                    $(".nilai-" + id).removeClass("has-success").addClass("has-error");
                                    $(".nilai-" + id).tooltip({
                                        title: "Range nilai 0 - 100",
                                        trigger: "manual"
                                    });
                                    $(".nilai-" + id).tooltip("show");
                                    $(".input-" + id).focus();
                                }
                            },
                            error: function () {
                                console.log('gagal kirim data');
                            }
                        })
                        //                    $(".nilai-"+id).removeClass("has-error").addClass("has-success");
                    } else {
                        $(".nilai-" + id).removeClass("has-success").addClass("has-error");
                        $(".nilai-" + id).tooltip({
                            title: "Range nilai 0 - 100",
                            trigger: "manual"
                        });
                        $(".nilai-" + id).tooltip("show");
                        $(".input-" + id).focus();
                    }
                } else {
                    $(".nilai-" + id).removeClass("has-success").addClass("has-error");
                    $(".nilai-" + id).tooltip({
                        title: "Nilai berupa angka 0 - 100",
                        trigger: "manual"
                    });
                    $(".nilai-" + id).tooltip("show")
                    $(".input-" + id).focus();
                }
            } else {
                $.ajax({
                    url: "<?= site_url('dosen/penilaian/nilai') ?>/" + id + "/" + krs,
                    type: "POST",
                    data: "nilai_akhir=",
                    success: function (res) {
                        console.log(res);
                        if (res) {
                            $(".nilai-" + id).removeClass("has-success");
                            $(".nilai-" + id).removeClass("has-error");
                            $(".nilai-" + id).tooltip("hide");
                        } else {
                            $(".nilai-" + id).removeClass("has-success").addClass("has-error");
                            $(".nilai-" + id).tooltip({
                                title: "Ubah nilai gagal",
                                trigger: "manual"
                            });
                            $(".nilai-" + id).tooltip("show");
                            $(".input-" + id).focus();
                        }
                    },
                    error: function () {
                        console.log('gagal kirim data');
                    }
                });
            }
        }
    }

    function harian(id, krs, cont) {
        var nilai = $(cont).val();
        if (cek_persentasi_nilai == 0) {
            $(cont).val('');
            $("#flash-info").html(notif);
            $("#modal-persentasi-nilai").modal('show');
        } else {
            $(".nilai-harian-" + id).click(function (e) {
                $(this).tooltip("hide");
            });
            if (nilai) {
                if ($.isNumeric(nilai)) {
                    if (nilai >= 0 && nilai <= 100) {
                        $.ajax({
                            url: "<?= site_url('dosen/penilaian/harian') ?>/" + id + "/" + krs +"/"+ <?= $kelas_id ?>,
                            type: "POST",
                            data: "nilai_harian=" + nilai,
                            success: function (res) {
                                var obj = JSON.parse(res)
                                console.log(obj);
                                if (obj.status) {
                                    $(".nilai-harian-" + id).removeClass("has-error").addClass("has-success");
                                    $(".input-" + id).val(obj.na);
                                } else {
                                    $(".nilai-harian-" + id).removeClass("has-success").addClass("has-error");
                                    $(".nilai-harian-" + id).tooltip({
                                        title: "Range nilai 0 -100",
                                        trigger: "manual"
                                    });
                                    $(".nilai-harian-" + id).tooltip("show");
                                    $(".input-harian-" + id).focus();
                                }
                            },
                            error: function () {
                                console.log('gagal kirim data');
                            }
                        })
                        //                    $(".nilai-"+id).removeClass("has-error").addClass("has-success");
                    } else {
                        $(".nilai-harian-" + id).removeClass("has-success").addClass("has-error");
                        $(".nilai-harian-" + id).tooltip({
                            title: "Range nilai 0 -100",
                            trigger: "manual"
                        });
                        $(".nilai-harian-" + id).tooltip("show");
                        $(".input-harian-" + id).focus();
                    }
                } else {
                    $(".nilai-harian-" + id).removeClass("has-success").addClass("has-error");
                    $(".nilai-harian-" + id).tooltip({
                        title: "Nilai berupa angka 0 - 100",
                        trigger: "manual"
                    });
                    $(".nilai-harian-" + id).tooltip("show")
                    $(".input-harian-" + id).focus();
                }
            } else {
                $.ajax({
                    url: "<?= site_url('dosen/penilaian/harian') ?>/" + id + "/" + krs,
                    type: "POST",
                    data: "nilai_harian=",
                    success: function (res) {
                        var obj = JSON.parse(res);
                        if (obj.status) {
                            $(".nilai-harian-" + id).removeClass("has-success");
                            $(".nilai-harian-" + id).removeClass("has-error");
                            $(".nilai-harian-" + id).tooltip("hide");
                            $(".input-" + id).val(obj.na);
                        } else {
                            $(".nilai-harian-" + id).removeClass("has-success").addClass("has-error");
                            $(".nilai-harian-" + id).tooltip({
                                title: "Ubah nilai gagal",
                                trigger: "manual"
                            });
                            $(".nilai-harian-" + id).tooltip("show");
                            $(".input-harian-" + id).focus();
                        }
                    },
                    error: function () {
                        console.log('gagal kirim data');
                    }
                });
            }
        }
    }

    function uts(id, krs, cont) {
        var nilai = $(cont).val();
        if (cek_persentasi_nilai == 0) {
            $(cont).val('');
            $("#flash-info").html(notif);
            $("#modal-persentasi-nilai").modal('show');
        } else {
            $(".nilai-uts-" + id).click(function (e) {
                $(this).tooltip("hide");
            });
            console.log("ini nilainya : " + nilai);

            if (nilai) {
                if ($.isNumeric(nilai)) {
                    if (nilai >= 0 && nilai <= 100) {
                        $.ajax({
                            url: "<?= site_url('dosen/penilaian/uts') ?>/" + id + "/" + krs,
                            type: "POST",
                            data: "nilai_uts=" + nilai,
                            success: function (res) {
                                var obj = JSON.parse(res);
                                if (obj.status) {
                                    $(".nilai-uts-" + id).removeClass("has-error").addClass("has-success");
                                    $(".input-" + id).val(obj.na);
                                } else {
                                    $(".nilai-uts-" + id).removeClass("has-success").addClass("has-error");
                                    $(".nilai-uts-" + id).tooltip({
                                        title: "Range nilai 0 -100",
                                        trigger: "manual"
                                    });
                                    $(".nilai-uts-" + id).tooltip("show");
                                    $(".input-uts-" + id).focus();
                                }
                            },
                            error: function () {
                                console.log('gagal kirim data');
                            }
                        })
                        //                    $(".nilai-"+id).removeClass("has-error").addClass("has-success");
                    } else {
                        $(".nilai-uts-" + id).removeClass("has-success").addClass("has-error");
                        $(".nilai-uts-" + id).tooltip({
                            title: "Range nilai 0 -100",
                            trigger: "manual"
                        });
                        $(".nilai-uts-" + id).tooltip("show");
                        $(".input-uts-" + id).focus();
                    }
                } else {
                    $(".nilai-uts-" + id).removeClass("has-success").addClass("has-error");
                    $(".nilai-uts-" + id).tooltip({
                        title: "Nilai berupa angka 0 - 100",
                        trigger: "manual"
                    });
                    $(".nilai-uts-" + id).tooltip("show")
                    $(".input-uts-" + id).focus();
                }
            } else {
                $.ajax({
                    url: "<?= site_url('dosen/penilaian/uts') ?>/" + id + "/" + krs,
                    type: "POST",
                    data: "nilai_uts=",
                    success: function (res) {
                        var obj = JSON.parse(res);
                        if (obj.status) {
                            $(".nilai-uts-" + id).removeClass("has-success");
                            $(".nilai-uts-" + id).removeClass("has-error");
                            $(".nilai-uts-" + id).tooltip("hide");
                            $(".input-" + id).val(obj.na);
                        } else {
                            $(".nilai-uts-" + id).removeClass("has-success").addClass("has-error");
                            $(".nilai-uts-" + id).tooltip({
                                title: "Ubah nilai gagal",
                                trigger: "manual"
                            });
                            $(".nilai-uts-" + id).tooltip("show");
                            $(".input-uts-" + id).focus();
                        }
                    },
                    error: function () {
                        console.log('gagal kirim data');
                    }
                });
            }
        }
    }

    function uas(id, krs, cont) {
        var nilai = $(cont).val();
        if (cek_persentasi_nilai == 0) {
            $(cont).val('');
            $("#flash-info").html(notif);
            $("#modal-persentasi-nilai").modal('show');
        } else {
            $(".nilai-uas-" + id).click(function (e) {
                $(this).tooltip("hide");
            });

            if (nilai) {
                if ($.isNumeric(nilai)) {
                    if (nilai >= 0 && nilai <= 100) {
                        $.ajax({
                            url: "<?= site_url('dosen/penilaian/uas') ?>/" + id + "/" + krs +"/"+ <?= $kelas_id ?>,
                            type: "POST",
                            data: "nilai_uas=" + nilai,
                            success: function (res) {
                                var obj = JSON.parse(res);
                                if (obj.status) {
                                    $(".nilai-uas-" + id).removeClass("has-error").addClass("has-success");
                                    $(".input-" + id).val(obj.na);
                                } else {
                                    $(".nilai-uas-" + id).removeClass("has-success").addClass("has-error");
                                    $(".nilai-uas-" + id).tooltip({
                                        title: "Range nilai 0 -100",
                                        trigger: "manual"
                                    });
                                    $(".nilai-uas-" + id).tooltip("show");
                                    $(".input-uas-" + id).focus();
                                }
                            },
                            error: function () {
                                console.log('gagal kirim data');
                            }
                        })
                        //                    $(".nilai-"+id).removeClass("has-error").addClass("has-success");
                    } else {
                        $(".nilai-uas-" + id).removeClass("has-success").addClass("has-error");
                        $(".nilai-uas-" + id).tooltip({
                            title: "Range nilai 0 -100",
                            trigger: "manual"
                        });
                        $(".nilai-uas-" + id).tooltip("show");
                        $(".input-uas-" + id).focus();
                    }
                } else {
                    $(".nilai-uas-" + id).removeClass("has-success").addClass("has-error");
                    $(".nilai-uas-" + id).tooltip({
                        title: "Nilai berupa angka 0 - 100",
                        trigger: "manual"
                    });
                    $(".nilai-uas-" + id).tooltip("show")
                    $(".input-uas-" + id).focus();
                }
            } else {
                $.ajax({
                    url: "<?= site_url('dosen/penilaian/uas') ?>/" + id + "/" + krs,
                    type: "POST",
                    data: "nilai_uas=",
                    success: function (res) {
                        var obj = JSON.parse(res);
                        if (obj.status) {
                            $(".nilai-uas-" + id).removeClass("has-success");
                            $(".nilai-uas-" + id).removeClass("has-error");
                            $(".nilai-uas-" + id).tooltip("hide");
                            $(".input-" + id).val(obj.na);
                        } else {
                            $(".nilai-uas-" + id).removeClass("has-success").addClass("has-error");
                            $(".nilai-uas-" + id).tooltip({
                                title: "Ubah nilai gagal",
                                trigger: "manual"
                            });
                            $(".nilai-uas-" + id).tooltip("show");
                            $(".input-uas-" + id).focus();
                        }
                    },
                    error: function () {
                        console.log('gagal kirim data');
                    }
                });
            }
        }
    }

    function selesai() {
        var url = "<?= site_url('dosen/penilaian/selesai/' . $data_kelas->kelas_id . '/' . $data_kelas->validasi_nilai) ?>";
        swal({
            title: '',
            html: "Menekan tombol <strong>Kirim Nilai</strong> berarti data nilai mahasiswa sudah selesai di inputkan dan siap untuk di validasi oleh <strong>Kaprodi dan Dekan</strong>." +
                    "Nilai yang sudah selesai di input tidak bisa di ubah kembali. Tekan <strong>YA</strong> untuk melanjutkan dan <strong>Tidak</strong> untuk membatalkan.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            window.location.href = url;
        });
    }

<?= $this->session->flashdata('info') ? $this->session->flashdata('info') : '' ?>
</script>