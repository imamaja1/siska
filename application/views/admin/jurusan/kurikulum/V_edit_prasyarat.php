    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><b>Tambah Matakuliah Prasyarat</b></h4>
    </div>
    <form id="form-edit" method="POST" action="<?= site_url('admin/jurusan/kurikulum/matakuliah_prasyarat/ubah') ?>">
        <div class="modal-body">
            <div class="form-group">
                <label>Matakuliah ambil</label>
                <input type="hidden" name="id" value="<?= $data->kode_matakuliah_prasyarat ?>">
                <select name="id_matakuliah_ambil" class="form-control select2" style="width: 100%;">
                    <?php foreach ($data_kurikulum as $row) : ?>
                        <option value="<?= $row->id_matakuliah ?>" <?= $row->id_matakuliah == $data->id_matakuliah_ambil ? "selected" : '' ?>><?= $row->kode_matakuliah." - ".$row->nama_matakuliah  ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Matakuliah prasyarat</label>
                <select name="id_matakuliah_syarat" class="form-control select2" style="width: 100%;">
                    <?php foreach ($data_kurikulum as $row) : ?>
                        <option value="<?= $row->id_matakuliah ?>" <?= $row->id_matakuliah == $data->id_matakuliah_syarat ? "selected" : '' ?>><?= $row->kode_matakuliah." - ".$row->nama_matakuliah  ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Jenis Matakuliah Prasyarat</label>
                <select name="jenis_prasyarat" class="form-control" style="width: 100%;">
                    <option value="" selected disabled>Pilih</option>
                    <option value="LA" <?= $data->jenis_prasyarat == 'LA' ? 'selected' : '' ?>>LANSUNG</option>
                    <option value="LU" <?= $data->jenis_prasyarat == 'LU' ? 'selected' : '' ?>>LULUS</option>
                    <option value="AM" <?= $data->jenis_prasyarat == null ? 'selected' : '' ?>>AMBIL</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
            <button type="submit" name="submit" class="btn btn-success flat"><i class="fa fa-check-circle"></i> Update</button>
        </div>
    </form>
    <script>
        $(document).ready(function () {
            $(".select2").select2();
        })

        $("#form-edit").submit(function (e) {
            e.preventDefault();
            var url = $(this).prop('action');
            var data = $(this).serialize();
            $.ajax({
                url : url,
                data : data,
                type : 'post',
                success : function (res) {
                    ambil();
                    $("#edit-prasyarat").modal("toggle");
                },
                error : function () {
                    console.log('gagal simpan');
                }
            })
        })
    </script>
