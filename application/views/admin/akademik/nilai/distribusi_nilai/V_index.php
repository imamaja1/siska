<div class="row">
    <div class="col-md-12">
        <div class="box box-solid flat">
            <div class="box-body">
                <a href="<?= site_url('admin/akademik/nilai') ?>" class="btn pull-right btn-danger btn-xs" title="Kembali"><i class="fa fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="box box-primary">
<!--            <div class="box-header">-->
<!--                <h3 class="box-title"><i class="fa fa-filter"></i> Filter</h3>-->
<!--            </div>-->
            <div class="box-body">
                <form action="<?=site_url('admin/akademik/nilai/filter_distribusi') ?>" id="form-filter" method="post">
                    <div class="row">
                        <div class="form-group col-md-4 col-xs-12">
                            <label for="">Tahun Kademik <span class="text-danger">*</span></label>
                            <select name="kode_tahun_akademik" id="" required class="form-control select2">
                                <option value="" selected disabled>Pilih</option>
                                <?php foreach ($tahun_akademik as $row) : ?>
                                    <option value="<?= $row->kode_tahun_akademik ?>" ><?= $row->tahun_akademik ?> - <?= $row->semester == '0' ? 'GENAP' : 'GANJIL' ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4 col-xs-12">
                            <label for="">Program Studi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="kode_program_studi" id="" required class="form-control">
                                    <option value="" selected disabled>Pilih</option>
                                    <?php foreach ($program_studi as $row) : ?>
                                        <option value="<?= $row->kode_program_studi ?>" ><?= $row->nama_program_studi ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="input-group-btn">
                                <button class="btn pull-right btn-primary"><i class="fa fa-gear"></i> Proses</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12" id="landing">
        <div class="box box-solid" style="height: 300px; ">
            <div class="box-body">
                <p style="text-align: center; font-size: 12pt"><i>"Landing Page..."</i></p>
            </div>
        </div>
    </div>
</div>
<!--script-->
<script>
    var loader = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";

    $("#form-filter").submit(function (e) {
        e.preventDefault();
        var url = $(this).prop('action');
        var data = $(this).serialize();
        $.ajax({
            url : url,
            data : data,
            type : 'post',
            beforeSend : function () {
                $("#landing").html(loader);
            },
            success : function (res) {
                $("#landing").html(res);
            }
        })
    })
</script>