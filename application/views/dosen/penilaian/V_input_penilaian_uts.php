<div class="row">
    <div class="col-md-12">
        <div class="box box-solid flat">
            <div class="box-body">
                <div class="pull-right">
                    <a href="<?= site_url('dosen/penilaian/penilaian_uts') ?>" class="btn btn-success btn-xs flat"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box box-info flat">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-chain-broken"></i><b> KELAS - <?= e($data_kelas->nama_kelas) ?> </b>
                    (<?= e($data_kelas->kode_matakuliah) ?> - <?= e($data_kelas->nama_matakuliah) ?>)</h3>
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
                                    <th style="text-align: center" class="no-sort">NILAI UTS</th>
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
                                            <?= e($row->nim) ?>
                                        </td>
                                        <td><?= e($row->nama_mahasiswa) ?></td>

                                        <td>
                                            <div class="form-group nilai-uts-<?= e($row->kode_khs_detail) ?> " style="margin: 0px">
                                                <input type="text" onchange="uts(<?= e($row->kode_khs_detail) ?>,<?= e($row->kode_krs_detail) ?>, this)" value="<?= e($row->dummy_uts) ?>"
                                                       class="form-control input-uts-<?= e($row->kode_khs_detail) ?>"
                                                       placeholder="Enter ..." <?php if ($data_kelas->status_nilai_uts == "T" || $exp): ?> disabled <?php endif; ?>>
                                            </div>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                  
                    <?php if ($data_kelas->param_uts == "0"): ?>
                    <?php else: ?>
                        <?php if ($data_kelas->status_nilai_uts != 'T' && !$exp): ?>
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
                    <?php endif ?>
                <?php else: ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<script>

    function uts(id, krs, cont) {
        var nilai = $(cont).val();

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

    function selesai() {
        var url = "<?= site_url('dosen/penilaian/selesai_uts/' . $data_kelas->kelas_id . '/' . $data_kelas->validasi_nilai_uts) ?>";
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
            window.location.href = url;
        });
    }

<?= $this->session->flashdata('info') ? $this->session->flashdata('info') : '' ?>
</script>