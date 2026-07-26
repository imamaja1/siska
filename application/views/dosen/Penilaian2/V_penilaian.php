<div class="row">
    <div class="col-md-12">
        <div class="box box-solid flat">
            <div class="box-body">
                <div class="pull-right">
                    <a href="<?= site_url('dosen/penilaian') ?>" class="btn btn-success btn-xs flat"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box box-info flat">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-chain-broken"></i><b> KELAS - <?= $data_kelas->nama_kelas ?> </b> (<?= $data_kelas->kode_matakuliah ?> - <?= $data_kelas->nama_matakuliah ?>)</h3>
            </div>
            <div class="box-body">
                <?php if (count($data) > 0) : ?>
                    <div class="alert alert-warning alert-dismissible flat">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-info-circle"></i> Info!</h4>
                        Bagi mahasiswa angkatan 2016 ke atas (nim 16xxxxxxxx) nilai yang di inputkan berupa gabungan nilai teori dan praktikum, jika pada matakuliah tersebut terdapat matakuliah praktikum.
                        Cara perhitungan dapat dilihat pada link berikut
                        <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal-default">
                            Show
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table demo-table">
                            <thead>
                                <tr style="background-color: #00c0ef">
                                    <th style="text-align: center">NO.</th>
                                    <th style="text-align: center">NIM</th>
                                    <th style="text-align: center">NAMA</th>
                                    <th style="text-align: center">NILAI AKHIR</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $no=1; foreach ($data as $row) : ?>
                                <tr>
                                    <td style="text-align: center; width: 3%"><?= $no++ ?>.</td>
                                    <td style="text-align: center"><?= $row->nim ?></td>
                                    <td><?= $row->nama_mahasiswa ?></td>
                                    <td>
                                        <div class="form-group nilai-<?= $row->kode_khs_detail ?> " style="margin: 0px">
                                            <input type="text" onchange="nilai(<?= $row->kode_khs_detail ?>,this)" value="<?= $row->nilai_akhir ?>" class="form-control input-<?= $row->kode_khs_detail ?>" placeholder="Enter ...">
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
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
                        <dt>var Teori</dt>
                        <dd>= Nilai Teori <b>X</b> Jumlah SKS Teori</dd>
                        <dt>var Praktikum</dt>
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
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    function nilai(id,cont) {
        var nilai = $(cont).val();
//        nilai = parseFloat(nilai);
//        console.log("kode krs detail "+$(cont).val());

        $(".nilai-"+id).click(function(e) {
            $(this).tooltip("hide");
        });
        console.log("ini nilainya : "+nilai);

        if (nilai)
        {
            if ($.isNumeric(nilai))
            {
                if (nilai >= 0 && nilai <= 100)
                {
                    $.ajax({
                        url : "<?= site_url('dosen/penilaian/nilai') ?>/"+id,
                        type : "POST",
                        data : "nilai_akhir="+nilai,
                        success : function (res) {
                            if (res)
                            {
                                $(".nilai-"+id).removeClass("has-error").addClass("has-success");
                            }else{
                                $(".nilai-"+id).removeClass("has-success").addClass("has-error");
                                $(".nilai-"+id).tooltip({
                                    title: "Nilai tidak boleh kurang dari 0 dan tidak boleh lebih dari 100",
                                    trigger: "manual"
                                });
                                $(".nilai-"+id).tooltip("show");
                                $(".input-"+id).focus();
                            }
                        },
                        error : function () {
                            console.log('gagal kirim data');
                        }
                    })
//                    $(".nilai-"+id).removeClass("has-error").addClass("has-success");
                }else{
                    $(".nilai-"+id).removeClass("has-success").addClass("has-error");
                    $(".nilai-"+id).tooltip({
                        title: "Nilai tidak boleh kurang dari 0 dan tidak boleh lebih dari 100",
                        trigger: "manual"
                    });
                    $(".nilai-"+id).tooltip("show");
                    $(".input-"+id).focus();
                }
            }else{
                $(".nilai-"+id).removeClass("has-success").addClass("has-error");
                $(".nilai-"+id).tooltip({
                    title: "Nilai harus berupa angka",
                    trigger: "manual"
                });
                $(".nilai-"+id).tooltip("show")
                $(".input-"+id).focus();
            }
        }else{
            $.ajax({
                url : "<?= site_url('dosen/penilaian/nilai') ?>/"+id,
                type : "POST",
                data : "nilai_akhir=",
                success : function (res) {
                    console.log(res);
                    if (res)
                    {
                        $(".nilai-"+id).removeClass("has-success");
                        $(".nilai-"+id).removeClass("has-error");
                        $(".nilai-"+id).tooltip("hide");
                    }else{
                        $(".nilai-"+id).removeClass("has-success").addClass("has-error");
                        $(".nilai-"+id).tooltip({
                            title: "Ubah nilai gagal",
                            trigger: "manual"
                        });
                        $(".nilai-"+id).tooltip("show");
                        $(".input-"+id).focus();
                    }
                },
                error : function () {
                    console.log('gagal kirim data');
                }
            });
        }
    }
</script>