<!--<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />-->
<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="box">
            <div class="box-header">
                <h4 class="box-title"><span class="text-danger"><i class="fa fa-ban"></i></span> Add Blocking</h4>
            </div>
            <div class="box-body">
                <form id="form-block" action="<?= site_url('admin/keuangan/block/store') ?>" method="post">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group">
                        <label for="">NIM/Nama Mahasiswa <span class="text-danger">*</span></label>
<!--                        <input type="nim" class="form-control" id="nim">-->
                        <select name="nim" class="form-control select2" id="nim">
                            <option value="" selected disabled>Pilih</option>
                            <?php foreach ($mahasiswa as $row) : ?>
                                <option value="<?= e($row->nim) ?>"><?= e($row->nim) ?> - <?= e($row->nama_mahasiswa) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                  <div class="form-group">
                        <label for="">Tahun Akademik <span class="text-danger">*</span></label>
                        <select name="kode_tahun_akademik" class="form-control select2" id="kode_tahun_akademik">
                            <?php foreach ($ta as $row) : ?>
                                <option <?= ($row->status == 'A' ? 'selected' : '') ?>
                                        value="<?= e($row->kode_tahun_akademik) ?>">
                                    <?= e($row->tahun_akademik) ?> - <?= $row->semester == 1 ? 'Ganjil' : 'Genap'; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group pull-right">
                        <button type="submit" class="btn btn-danger"><i class="fa fa-ban"></i> Block</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-sm-12" id="landing">
    </div>
</div>
<!--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>-->
<script>
    var loading = "<p style='text-align: center'><img src='<?= base_url('assets/siska/img/logo-ubg.gif') ?>' alt=''></p>";

    $(document).ready(function () {
        autoload();

        $("#form-block").submit(function (e) {
            e.preventDefault();
            var url = $(this).prop('action');
            var data = $(this).serialize();
            $.ajax({
                url: url,
                data : data,
                type : 'post',
                success : function () {
                    autoload();
                },
                error : function (err) {
                    console.log(err);
                }
            })
        })
        //$('#nim').select2({
        //    placeholder : 'Pilih',
        //    allowClear: true,
        //    ajax: {
        //        url: "<?//= site_url('admin/keuangan/block/get_mahasiswa') ?>//",
        //        dataType: 'json',
        //        type : 'get',
        //        data : function (params) {
        //            return {
        //                keyword: params.term,
        //            }
        //        },
        //        processResults: function (data, page) {
        //            return {
        //                results: $.map(data, function(obj) {
        //                    return {
        //                        id: obj.nim,
        //                        text: obj.nim+" - "+obj.nama_mahasiswa
        //                    };
        //                })
        //            };
        //        },
        //        cache: true
        //    },
        //
        //});
    })

    function autoload() {
        var url = "<?= site_url('admin/keuangan/block/get_data') ?>";
        $.ajax({
            url : url,
            beforeSend : function(){
                $("#landing").html(loading);
            },
            success : function (res) {
                $("#landing").html(res);
            }
        })
    }

    function hapus(id) {
        var url = "<?= site_url('admin/keuangan/block/delete') ?>/"+id;
        var conf = confirm('Yakin menghapus data ini?');
        if(conf){
            $.ajax({
                url : url,
                success : function () {
                    autoload();
                }
            })
        }
    }
</script>