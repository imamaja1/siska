<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/AdminLTE.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/skins/_all-skins.min.css'); ?>">
    <title>Detail</title>
</head>
<body>
<div class="container-fluid">
    <h1>
        Detail <a href="<?=site_url('admin/double/index/21')?>" class="btn btn-info">Kembali</a>
    </h1>
    <hr>
    <div class="row">
        <div class="col-md-5">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Kode KRS</th>
                    <th>KTA</th>
                    <th>Nim</th>
                    <th>Semester</th>
                    <th>#</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $no = 1;
                foreach ($krs as $d) :
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= e($d->kode_krs) ?></td>
                        <td><?= e($d->kode_tahun_akademik) ?></td>
                        <td><?= e($d->nim) ?></td>
                        <td><?= $d->semester; ?></td>
                        <td>
                            <a href="<?= site_url('admin/double/hapus_krs/' . $d->kode_krs) ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Pastikan Semua nilai sudah terisi !')">Hapus</a>
                        </td>
                    </tr>
                <?php
                endforeach;
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <table class="table table-bordered table-striped dataTable">
        <thead>
        <tr>
            <th>No</th>
            <td style="display:none;">id</td>
            <th>Kode KRS</th>
            <th>KTA</th>
            <th>KKRSD</th>
            <th>KKHSD</th>
            <th>ID MK</th>
            <th>Harian</th>
            <th>UTS</th>
            <th>UAS</th>
            <th>Akhir</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        foreach ($data as $d) :
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td style="display:none;"><?= e($d->kode_khs_detail) ?></td>
                <td><?= e($d->kode_krs) ?></td>
                <td><?= e($d->kode_tahun_akademik) ?></td>
                <td><?= e($d->kode_krs_detail) ?></td>
                <td><?= e($d->kode_khs_detail) ?></td>
                <td style="font-weight: bold"><?= e($d->id_matakuliah) ?></td>
                <td style="text-align:center;">
                    <div class="form-group nilai-<?= $d->kode_khs_detail ?>">
                        <input style="width: 100px;" type="text" name="nilai_harian"
                                onblur="nilai(<?= e($d->kode_khs_detail) ?>,this)" value="<?= e($d->nilai_harian) ?>"
                                class="form-control">
                            </div>
                        </td>
                        <td style="text-align:center;">
                            <div class="form-group nilai-<?= e($d->kode_khs_detail) ?>">
                                <input style="width: 100px;" type="text" name="nilai_uts"
                                onblur="nilai(<?= e($d->kode_khs_detail) ?>,this)" value="<?= e($d->nilai_uts) ?>"
                                class="form-control">
                            </div>
                        </td>
                        <td style="text-align:center;">
                            <div class="form-group nilai-<?= e($d->kode_khs_detail) ?> " style="margin: 0px">
                                <input style="width: 100px;" type="text" name="nilai_uas"
                                onblur="nilai(<?= e($d->kode_khs_detail) ?>,this)" value="<?= e($d->nilai_uas) ?>"
                                class="form-control">
                            </div>
                        </td>
                        <td style="text-align:center;">
                            <div class="form-group" style="margin: 0px">
                                <input style="width: 100px;" type="text" name="nilai_akhir"
                                onblur="nilai(<?= e($d->kode_khs_detail) ?>,this)" value="<?= e($d->nilai_akhir) ?>"
                                class="form-control">
                            </div>
                        </td>
            </tr>
        <?php
        endforeach;
        ?>
        </tbody>
    </table>
</div>

<script src="<?= base_url('assets/plugins/jQuery/jQuery-2.2.0.min.js'); ?>"></script>
<!--"></script>-->
<script src="<?= base_url('assets/siska/admin.js'); ?>"></script>
<script src="<?= base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/slimScroll/jquery.slimscroll.min.js'); ?>"></script>
<script src="<?= base_url('assets/dist/js/app.min.js'); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>

<script type="text/javascript">
    function nilai(id, contex) {
        var value = $(contex).val();
        var name = $(contex).attr('name');
        $(contex).click(function (e) {
            $(contex).closest(".form-group").tooltip("hide");
        });
        if (value) {
            if ($.isNumeric(value)) {
                if (value >= 0 && value <= 100) {
                    $.ajax({
                        url: "<?= site_url('admin/double/update_nilai') ?>/" + id,
                        type: "post",
                        data: "input_name=" + name + "&nilai=" + value,
                        success: function (res) {
                            if (res) {
                                $(contex).closest(".form-group").removeClass('has-error').addClass('has-success');
                            } else {
                                $(contex).closest(".form-group").removeClass('has-success').addClass('has-error');
                                $(contex).closest(".form-group").tooltip({
                                    title: 'Nilai gagal diubah',
                                    trigger: "manual"
                                });
                                $(contex).closest(".form-group").tooltip("show");
                                $(contex).focus();
                            }
                        }
                    })
                } else {
                    $(contex).closest(".form-group").removeClass('has-success').addClass('has-error');
                    $(contex).closest(".form-group").tooltip({
                        title: 'Nilai harus 0 - 100',
                        trigger: "manual"
                    });
                    $(contex).closest(".form-group").tooltip("show");
                    $(contex).focus();
                }
            } else {
                $(contex).closest(".form-group").removeClass('has-success').addClass('has-error');
                $(contex).closest(".form-group").tooltip({
                    title: 'Nilai harus berupa angka',
                    trigger: "manual"
                });
                $(contex).closest(".form-group").tooltip("show");
                $(contex).focus();
            }
        } else {
            $.ajax({
                url: "<?= site_url('admin/double/update_nilai') ?>/" + id,
                type: "post",
                data: "input_name=" + name + "&nilai=" + value,
                success: function (res) {
                    if (res) {
                        $(contex).closest(".form-group").removeClass('has-success');
                        $(contex).closest(".form-group").removeClass('has-error');
                    } else {
                        $(contex).closest(".form-group").removeClass('has-success').addClass('has-error');
                        $(contex).closest(".form-group").tooltip({
                            title: 'Nilai gagal diubah',
                            trigger: "manual"
                        });
                        $(contex).closest(".form-group").tooltip("show");
                        $(contex).focus();
                    }
                }
            })

        }
    }
</script>

</body>
</html>