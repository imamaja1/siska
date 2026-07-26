<div class="row">
    <div class="col-md-12">
        <div class="box box-solid ">
            <div class="box-body">
                <button onclick="ambil()" class="btn btn-warning btn-sm"><i class="fa fa-refresh"></i> SKS NON SKRIPSI</button> &emsp;
                <button onclick="skripsi()" class="btn btn-danger btn-sm"><i class="fa fa-refresh"></i> SKRIPSI</button> &emsp;
                <a href="<?= site_url('admin/keuangan/status_perkuliahan/cetak_filter_rekap_sks') ?>" class="btn btn-info btn-sm "><i class="fa fa-file-excel-o"></i> Cetak SKS NON Skripsi</a> &emsp;
                <a href="<?= site_url('admin/keuangan/status_perkuliahan/cetak_filter_rekap_sks_skripsi') ?>" class="btn btn-success btn-sm "><i class="fa fa-file-excel-o"></i> Cetak SKS Skripsi</a>
                <a href="<?= site_url('admin/keuangan/status_perkuliahan') ?>" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
<!--    <div class="col-md-2 col-xs-12">-->
<!--        <div class="box box-danger">-->
<!--            <div class="box-header">-->
<!--                <h3 class="box-title"><i class="fa fa-search"></i> Filter</h3>-->
<!--            </div>-->
<!--            <div class="box-body">-->
<!--                <button onclick="ambil()" class="btn btn-warning btn-block"><i class="fa fa-refresh"></i> Refresh</button>-->
<!--                <form id="form-filter" action="--><?//= site_url('admin/keuangan/status_perkuliahan/filter_rekap_sks') ?><!--" method="post">-->
<!--                    <div class="form-group">-->
<!--                        <label for="">Dari Tanggal</label>-->
<!--                        <div class="input-group">-->
<!--                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>-->
<!--                            <input required type="text" name="start" class="form-control datepicker" placeholder="Dari tanggal">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="form-group">-->
<!--                        <label for="">Sampai Tanggal</label>-->
<!--                        <div class="input-group">-->
<!--                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>-->
<!--                            <input required type="text" name="end" class="form-control datepicker" placeholder="Sampai tanggal">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="form-group">-->
<!--                        <button type="submit" class="btn btn-info pull-right"><i class="fa fa-search"></i> Cari</button>-->
<!--                    </div>-->
<!--                </form>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <div class="col-md-12 col-xs-12" id="landing">
        <div class="box box-solid" style="height: 200px; border: 2px solid #00b3ee">
            <div class="box-body">
                <p style="text-align: center"><i>"Landing Page...."</i></p>
            </div>
        </div>
    </div>
</div>
<!--javascript-->
<script>
    var loading =  "<div class='text-center'><img src='https://assets.website-files.com/5c7fdbdd4e3feeee8dd96dd2/5ce46f8ffd710a2c22c15e48_cust_ami.gif'/></div>";
    $(function () {
        $(".datepicker").datepicker({
            format: "yyyy-mm-dd",
            autoclose: 'ture',
        });

        ambil();
        //
        // $("#form-filter").submit(function (e) {
        //     e.preventDefault();
        //     var url = $(this).prop('action');
        //     var data = $(this).serialize();
        //     $.ajax({
        //         url : url,
        //         data : data,
        //         type : 'post',
        //         beforeSend : function(){
        //             $("#landing").html(loading);
        //         },
        //         success : function (res) {
        //             $("#landing").html(res);
        //         }
        //     })
        // })
    });

    function ambil() {
        $.ajax({
            url : "<?= site_url('admin/keuangan/status_perkuliahan/filter_rekap_sks') ?>",
            beforeSend : function(){
                $("#landing").html(loading);
            },
            success : function (res) {
                $("#landing").html(res);
            }
        })
    }

    function skripsi() {
        $.ajax({
            url : "<?= site_url('admin/keuangan/status_perkuliahan/filter_rekap_sks_skripsi') ?>",
            beforeSend : function(){
                $("#landing").html(loading);
            },
            success : function (res) {
                $("#landing").html(res);
            }
        })
    }
</script>