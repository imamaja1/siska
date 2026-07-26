<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-pencil"></i> Bagi bimbingan</h4>
</div>
<form class="form-horizontal" method="post" action="<?= site_url('admin/akademik/pembimbing_kkp/update/'.$id.'/'.$kode_dosen) ?>">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
    <div class="modal-body">
        <div class="box-body">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Lokasi KKP<span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <textarea name="lokasi_kkp" required class="form-control" cols="30" rows="3"><?= e($data->lokasi_kkp) ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Bidang/Topik KKP<span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <textarea name="bidang_kkp" required class="form-control" cols="30" rows="3"><?= e($data->bidang_kkp) ?></textarea>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Tgl. Pelaksanaan<span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" required name="tgl_pelaksanaan" value="<?= e($data->tgl_pelaksanaan) ?>" class="form-control datepicker">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Batas Pelaksanaan<span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" required name="batas_pelaksanaan" value="<?= e($data->batas_pelaksanaan) ?>" class="form-control datepicker">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Batas Laporan<span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" required name="batas_laporan" value="<?= e($data->batas_laporan) ?>" class="form-control datepicker">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
        <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-check-square-o"></i> Simpan</button>
    </div>
</form>
<script>
    $(document).ready(function () {
        $(".select2").select2({
            placeholder: "Silahkan Pilih",
            allowClear: true
        });

        $(".datepicker").datepicker({
            'format' : 'yyyy-mm-dd'
        });
    })
</script>
