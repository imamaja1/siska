<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="box box-solid">
            <div class="box-body">
                <a href="<?= site_url('admin/keuangan/pembayaran/add') ?>" class="btn btn-primary bg-blue-gradient"><i
                            class="fa fa-plus"></i> Tambah Pembayaran</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3 col-sm-12">
        <div class="box box-solid">
            <div class="box-header">
                <h4>Cari Pembayaran</h4>
            </div>
            <div class="box-body">
                <form id="form-nim-pembayaran" action="<?= site_url('admin/keuangan/pembayaran/history_pembayaran') ?>" method="post">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group">
                        <input type="hidden" name="nim" id="nim" value="">
                        <input type="text" autocomplete="off" required placeholder="Masukkan NIM atau Nama" id="search-box" class="form-control">
                        <div id="suggesstion-box"></div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success pull-right"><i class="fa fa-search"></i> Cari</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-9 col-sm-12" id="landing">

    </div>
</div>

<script>
    $("#form-nim-pembayaran").submit(function (e) {
        e.preventDefault();
        var url = $(this).prop('action');
        var data = $(this).serialize();
        $.ajax({
            url : url,
            data : data,
            type : 'post',
            beforeSend : function () {
                $("#landing").html("<div class='text-center'><img src='https://assets.website-files.com/5c7fdbdd4e3feeee8dd96dd2/5ce46f8ffd710a2c22c15e48_cust_ami.gif'/></div>");
            },
            success : function (res) {
                $("#landing").html(res);
            },
            error : function (err) {
                console.log(err);
            }
        })
    })
    $("#search-box").keyup(function () {
        $.ajax({
            type: "POST",
            url: "<?= site_url('admin/keuangan/pembayaran/autocomplate') ?>",
            data: 'keyword=' + $(this).val(),
            beforeSend: function () {
                $("#search-box").css("background", "#FFF");
            },
            success: function (data) {
                $("#suggesstion-box").show();
                $("#suggesstion-box").html(data);
                $("#search-box-dosen").css("background", "#FFF");
            }
        });
    });

    //To select country name
    function selectNim(val, nama_mahasiswa) {
        $("#search-box").val(val+" - "+nama_mahasiswa);
        $("#nim").val(val);
        $("#suggesstion-box").hide();
    }

</script>