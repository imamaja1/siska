<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/krs') ?>" class="btn btn-xs btn-success flat"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="box box-primary flat">
            <div class="box-header">
                <h5><b>KRS Mahasiswa Tahun Akademik Aktif</b></h5>
                <hr>
            </div>
            <div class="box-body">
                <form id="form-quick-search" method="post" action="<?= site_url('admin/akademik/krs/quick_search_proses'); ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group">
                        <label class="control-label">Masukan Kata Kunci <label class="text-danger">*</label> :</label>
                        <div class="input-group">
                            <input value="" type="text" autocomplete="off" required class="form-control" placeholder="NIM atau Nama Mahasiswa" name="keyword" >
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-sm-12">
        <div id="landing-page">
            <div class="box">
                <div class="box-body" style="height: 300px">
                    <p style="text-align: center"><i><b>Landing Page...</b></i></p>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function () {
        $("#form-quick-search").submit(function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            var url = $(this).attr('action');
            $.ajax({
                url : url,
                data : data,
                type : "post",
                success : function (res) {
                    $("#landing-page").html(res);
                }
            })
        })
    })
</script>

