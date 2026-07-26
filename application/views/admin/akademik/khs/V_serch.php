<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="box box-solid">
            <div class="box-body">
                <div class="pull-right">
                    <a href="<?= site_url('admin/akademik/khs') ?>" class="btn btn-success btn-flat btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="box box-solid">
            <div class="box-body">
                <form class="form-horizontal" action="">
                    <div class="form-group">
                        <label for="" class="control-label col-xs-12 col-sm-3 col-md-3">NIM/Nama : </label>
                        <div class="col-xs-12 col-md-5 col-sm-5">
                            <input autofocus type="text" onkeyup="find()" id="keyword" name="keyword" class="form-control" placeholder="NIM/Nama Mahasiswa">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div style="display:block; margin-left: auto;margin-right: auto;width: 20%; display: none" id="load">
    <img src="<?= base_url('assets/gambar/loading_page.gif') ?>" alt="">
</div>

<div class="row" id="content-find" style="display: none">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="box box-solid">
            <div class="box-body" id="bo">
            </div>
        </div>
    </div>
</div>

<!--script javascript-->
<script>
    function find() {
        var key = $("#keyword").val();
        var url = "<?= site_url('admin/akademik/khs/find') ?>/"+key;
        $.ajax({
            url : url,
            beforeSend : function () {
                $("#load").show();
            },
            success : function (res) {
                $("#load").hide();
                $("#content-find").show();
                $("#bo").html(res);
            },
            error : function () {
                console.log("gagal load data");
            }
        })
    }
//    $(document)
//        .ajaxStart(function () {
////            find();
//            $("#modal-load").modal().show();
//        })
//        .ajaxStop(function () {
//            $("#modal-load").modal().hide();
//            $(".modal-backdrop").hide();
//        });
</script>