<div class="row">
    <div class="col-md-3 col-sm-12">
        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">Rekap Pembayaran</h3>
            </div>
            <div class="box-body">
                <form action="<?= site_url('admin/keuangan/pembayaran/rekap') ?>" method="post" id="form-search">
                <div class="form-group">
                    <label for="">Program Studi <span class="text-danger">*</span></label>
                    <select name="kode_program_studi" required class="form-control">
                        <option value="" selected disabled>Pilih</option>
                        <?php foreach ($program_studi as $row) : ?>
                            <option value="<?= $row->kode_program_studi ?>"><?= $row->nama_program_studi ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary pull-right">Cari</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-9 col-sm-12" id="landing">
        <div class="box box-solid">
            <div class="box-body" style="height: 300px">
                <p style="text-align: center">Landing Page..</p>
            </div>
        </div>
    </div>
</div>
<script>
    var kode_program_studi_super = "";
    var loading = "<p style='text-align: center'><img src='<?= base_url('assets/siska/img/logo-ubg.gif') ?>' alt=''></p>";
    $(document).ready(function () {
        $("#form-search").submit(function (e) {
            e.preventDefault();
            var url = $(this).prop('action');
            var data = $(this).serialize();
            $.ajax({
                url : url,
                data : data,
                type: "post",
                beforeSend : function(){
                    $("#landing").html(loading);
                },
                success : function (res) {
                    $("#landing").html(res);
                }
            })
        })
    })
</script>
