<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
      integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw=="
      crossorigin="anonymous"/>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="box box-primary">
            <div class="box-header">
                <h4><i class="fa fa-plus"></i> Add Pembayaran</h4>
                <div class="box-tools">
                    <a href="<?= site_url('admin/keuangan/pembayaran/index_pembayaran') ?>" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
            <div class="box-body">
                <form id="form-pembayaran" action="<?= site_url('admin/keuangan/pembayaran/store') ?>" method="post">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="">Mahasiswa <span class="text-danger">*</span></label>
                            <select name="nim" class="form-control select2" required>
                                <option value="" selected disabled>Pilih</option>
                                <?php foreach ($mahasiswa as $row) : ?>
                                    <option value="<?= e($row->nim) ?>"><?= e($row->nim) ?>
                                        - <?= e($row->nama_mahasiswa) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Jenis Pembayaran <span class="text-danger">*</span></label>
                        <select name="jenis_pembayaran_id" id="jenis-bayar" class="form-control select2" required>
                            <option value="" selected disabled>Pilih</option>
                            <?php foreach ($jenis_pembayaran as $row) : ?>
                                <option value="<?= e($row->id) ?>"><?= e($row->nama_pembayaran) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group form-group-lg">
                        <label for="">Jumlah Bayar <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                Rp.
                            </div>
                            <input type="text" name="nominal_pembayaran" required class="form-control uang"
                                   placeholder="0.-">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="">Rekening <span class="text-danger">*</span></label>
                            <select name="rekening_id" class="form-control select2" required>
                                <option value="" selected disabled>Pilih</option>
                                <?php foreach ($rekening as $row) : ?>
                                    <option value="<?= e($row->id) ?>"><?= e($row->nama_rek) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="">Tgl. Bayar <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" required name="tgl_pembayaran" value="<?= date('Y-m-d') ?>"
                                       class="form-control datepicker">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="">Tahun Akademik <span class="text-danger">*</span></label>
                            <select name="kode_tahun_akademik" class="form-control select2" required>
                                <option value="" selected disabled>Pilih</option>
                                <?php foreach ($tahun_akademik as $row) : ?>
                                    <option value="<?= e($row->kode_tahun_akademik) ?>"><?= e($row->tahun_akademik) ?> - <?= $row->semester == '0' ? 'GENAP' : 'GANJIL' ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="">Semester <span class="text-danger">*</span></label>
                            <select name="semester" class="form-control" required>
                                <option value="" selected disabled>Pilih</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                            </select>
                        </div>
                        <div class="col-sm-3" id="jum-sks" style="display: none">
                            <label for="">Jum. SKS <span class="text-danger">*</span></label>
                            <input type="number" required name="jml_sks" max="24" min="1" class="form-control" placeholder="SKS">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Keterangan <span>(Optional)</span></label>
                        <textarea name="keterangan" cols="30" rows="3" class="form-control"
                                  placeholder="Keterangan"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12" id="notif">

    </div>
</div>
<div class="row">
    <div class="col-md-12" id="landing">

    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"
        integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw=="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"
        integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ=="
        crossorigin="anonymous"></script>
<script>
    var loading = "<p style='text-align: center'><img src='<?= base_url('assets/siska/img/logo-ubg.gif') ?>' alt=''></p>";
    var success = '<div class="alert alert-success alert-dismissible">\n' +
        '                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\n' +
        '                <h4><i class="icon fa fa-check"></i> Success!</h4>\n' +
        '                Pembayaran berhasil disimpan.\n' +
        '              </div>';
    var gagal = '<div class="alert alert-danger alert-dismissible">\n' +
        '                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\n' +
        '                <h4><i class="icon fa fa-ban"></i> Gagal!</h4>\n' +
        '                Pembayaran gagal disimpan.\n' +
        '              </div>';
    $(".datepicker").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        defaultViewDate: 'today'
    });

    load_data();

    $("#form-pembayaran").submit(function (e) {
        e.preventDefault();
        var url = $(this).prop('action');
        var data = $(this).serialize();
        $.ajax({
            url: url,
            data: data,
            type: 'post',
            success: function () {
                load_data();
                $('#form-pembayaran').trigger("reset");
                $("#notif").html(success)
            },
            error : function () {
                $("#notif").html(gagal)
            }
        })
    })

    function load_data() {
        var url = "<?= site_url('admin/keuangan/pembayaran/last_pembayaran') ?>";
        $.ajax({
            url: url,
            beforeSend: function () {
                $("#landing").html(loading);
            },
            success: function (res) {
                $("#landing").html(res);
            }
        })
    }

    function hapus(id) {
        var url = "<?= site_url('admin/keuangan/pembayaran/delete') ?>/" + id;
        var conf = confirm("Yakin menghapus data ini?")
        if (conf) {
            $.ajax({
                url: url,
                success: function () {
                    load_data();
                }
            })
        }
    }

    $(document).ready(function () {

        // Format mata uang.
        $('.uang').mask('000.000.000', {reverse: true});

    })

    $("#jenis-bayar").change(function (e) {
        var val = $(this).val();
        if(val == '4' || val == '21' || val == '22'){
            $("#jum-sks").show();
            $("#jum-sks").find('input').prop('required',true);
        }else {
            $("#jum-sks").hide();
            $("#jum-sks").find('input').prop('required',false);
        }
    })
</script>