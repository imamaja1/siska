<?= $this->session->flashdata('info') ?>
<div class="box box-solid flat">
    <div class="box-body">
        <div class="col-md-6 col-sm-12" style="padding: 0px">
            <a href="#" id="tambah" class="btn btn-primary flat" data-toggle="modal" onclick="$('#myModal').modal('toggle')"> <i class="fa fa-plus-circle"></i> Tambah</a>
        </div>
        <div class="col-md-6 col-sm-12" style="padding: 0px">
            <form class="form-horizontal" id="form-filter">
                <div class="form-group" style="margin-bottom: 0px">
                    <label for="inputEmail3" class="col-sm-3 control-label">Program Studi</label>
                    <div class="col-sm-9">
                        <select name="kode_program_studi" id="kode-program-studi" class="form-control">
                            <?php foreach ($jurusan as $row): ?>
                                <option <?= $row->kode_program_studi == 2 ? 'selected' : '' ?> value="<?= e($row->kode_program_studi) ?>"><?= e($row->nama_program_studi) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="landing"></div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Tambah Matakuliah</b></h4>
            </div>
            <div class="modal-body">
                <form id="form-add-matakuliah" method="POST" action="<?= site_url('admin/jurusan/kurikulum/matakuliah/tambah_matakuliah') ?>">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group">
                        <label>Kode Matakuliah<span class="text-danger">*</span></label>
                        <input required type="text" name="kode_matakuliah" class="form-control" placeholder="Kode Matakuliah">
                    </div>
                    <div class="form-group">
                        <label>Nama Matakuliah<span class="text-danger">*</span></label>
                        <input required type="text" name="nama_matakuliah" class="form-control" placeholder="Nama Matakuliah">
                    </div>
                    <div class="form-group"><div class="row">
                        <div class="col-xs-4"><label>SKS Teori</label>
                            <select class="form-control" name="sks_teori" id="sks-teori">
                                <option value="0">SKS Teori</option>
                                <option value="1">1</option><option value="2">2</option><option value="3">3</option>
                                <option value="4">4</option><option value="5">5</option><option value="6">6</option>
                                <option value="7">7</option><option value="8">8</option>
                            </select>
                        </div>
                        <div class="col-xs-4"><label>SKS Praktek</label>
                            <select class="form-control" name="sks_praktek" id="sks-praktek">
                                <option value="0">SKS Praktek</option>
                                <option value="1">1</option><option value="2">2</option><option value="3">3</option>
                                <option value="4">4</option><option value="5">5</option><option value="6">6</option>
                                <option value="7">7</option><option value="8">8</option>
                            </select>
                        </div>
                        <div class="col-xs-4"><label>SKS Praktikum</label>
                            <select class="form-control" name="sks_praktikum" id="sks-praktikum">
                                <option value="0">SKS Praktikum</option>
                                <option value="1">1</option><option value="2">2</option><option value="3">3</option>
                                <option value="4">4</option><option value="5">5</option><option value="6">6</option>
                                <option value="7">7</option><option value="8">8</option>
                            </select>
                        </div>
                    </div></div>
                    <div class="form-group"><div class="row">
                        <div class="col-xs-4"><label>Nama Kompetensi</label>
                            <select name="kode_kompetensi" class="form-control">
                                <option value="">Pilih Kompetensi</option>
                                <?php foreach ($kompetensi as $k) { ?>
                                    <option value="<?= e($k->kode_kompetensi) ?>"> <?= e($k->nama_kompetensi) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-4"><label>Jenis</label>
                            <select name="jenis" class="form-control"><option value="">Wajib</option><option value="1">Pilihan</option></select>
                        </div>
                        <div class="col-xs-4"><label>Block (Kedokteran)</label>
                            <select name="block" class="form-control"><option value="0">Non Block</option><option value="1">Block</option></select>
                        </div>
                    </div></div>
                    <div class="form-group">
                        <label>Nama Program Studi<span class="text-danger">*</span></label>
                        <select required name="kode_nama_jurusan" class="form-control">
                            <option value="">Pilih Program Studi</option>
                            <?php foreach ($jurusan as $j) { ?>
                                <option value="<?= e($j->kode_program_studi) ?>"><?= e($j->singkatan_program_studi) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa  fa-remove"></i> Batal</button>
                    <button type="submit" name="submit" class="btn btn-success flat"><i class="fa fa-check-circle"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-matakuliah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Ubah Matakuliah</b></h4>
            </div>
            <div class="modal-body">
                <form method="POST" id="form-edit-matakuliah" action="<?= site_url('admin/jurusan/kurikulum/matakuliah/ubah_matakuliah') ?>">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group">
                        <label>Kode Matakuliah</label>
                        <input readonly type="hidden" name="param_edit" id="param">
                        <input required type="text" name="kode_matakuliah" class="form-control" placeholder="Kode Matakuliah" id="edit-kode-matakuliah">
                    </div>
                    <div class="form-group">
                        <label>Nama Matakuliah</label>
                        <input required type="text" name="nama_matakuliah" class="form-control" placeholder="Nama Matakuliah" id="edit-nama-matakuliah">
                    </div>
                    <div class="form-group"><div class="row">
                        <div class="col-xs-4"><label>SKS Teori</label>
                            <select class="form-control" name="sks_teori" id="edit-sks-teori">
                                <option value="0">SKS Teori</option>
                                <option value="1">1</option><option value="2">2</option><option value="3">3</option>
                                <option value="4">4</option><option value="5">5</option><option value="6">6</option>
                            </select>
                        </div>
                        <div class="col-xs-4"><label>SKS Praktek</label>
                            <select class="form-control" name="sks_praktek" id="edit-sks-praktek">
                                <option value="0">SKS Praktek</option>
                                <option value="1">1</option><option value="2">2</option><option value="3">3</option>
                                <option value="4">4</option><option value="5">5</option><option value="6">6</option>
                            </select>
                        </div>
                        <div class="col-xs-4"><label>SKS Praktikum</label>
                            <select class="form-control" name="sks_praktikum" id="edit-sks-praktikum">
                                <option value="0">SKS Praktikum</option>
                                <option value="1">1</option><option value="2">2</option><option value="3">3</option>
                                <option value="4">4</option><option value="5">5</option><option value="6">6</option>
                            </select>
                        </div>
                    </div></div>
                    <div class="form-group"><div class="row">
                        <div class="col-xs-4"><label>Nama Kompetensi</label>
                            <select name="kode_kompetensi" class="form-control" id="edit-kode-kompetensi">
                                <?php foreach ($kompetensi as $k) { ?>
                                    <option value="<?= e($k->kode_kompetensi) ?>"> <?= e($k->nama_kompetensi) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-4"><label>Jenis</label>
                            <select name="jenis" class="form-control" id="edit-jenis"><option value="0">Wajib</option><option value="1">Pilihan</option></select>
                        </div>
                        <div class="col-xs-4"><label>Block (Kedokteran)</label>
                            <select name="block" class="form-control" id="edit-block"><option value="0">Non Block</option><option value="1">Block</option></select>
                        </div>
                    </div></div>
                    <div class="form-group">
                        <label>Nama Program Studi</label>
                        <select name="kode_nama_jurusan" class="form-control" id="edit-kode-nama-jurusan">
                            <?php foreach ($jurusan as $j) { ?>
                                <option value="<?= e($j->kode_program_studi) ?>"><?= e($j->singkatan_program_studi) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa  fa-remove"></i> Batal</button>
                    <button type="submit" name="submit" class="btn btn-success flat"><i class="fa fa-check-circle"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () { $('.dataTables-example').dataTable(); });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        all();
        $("#form-add-matakuliah").submit(function (e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var data = $(this).serialize();
            if (!$(this)[0].checkValidity()) { console.log('form harus di isi semua');
            } else {
                $.ajax({
                    url: url, data: data, type: "post",
                    success: function (res) {
                        var obj = JSON.parse(res);
                        if (obj.status) { all(); $("#myModal").modal('hide'); swal('Success!', obj.msg, 'success');
                        } else { swal('Gagal!', obj.msg, 'error'); }
                    }
                })
            }
        });
        $("#form-edit-matakuliah").submit(function (e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var data = $(this).serialize();
            if (!$(this)[0].checkValidity()) { console.log('form harus di isi semua');
            } else {
                $.ajax({
                    url: url, data: data, type: "post",
                    success: function (res) {
                        var obj = JSON.parse(res);
                        if (obj.status) { all(); $("#edit-matakuliah").modal('hide'); swal('Success!', obj.msg, 'success');
                        } else { swal('Gagal!', obj.msg, 'error'); }
                    }
                })
            }
        });
        $('#kode-program-studi').change(function () {
            var url = "<?= site_url('admin/jurusan/kurikulum/matakuliah/all') ?>";
            var prodi = $("#kode-program-studi").val();
            $.ajax({ url: url, data: 'kode_program_studi=' + prodi, type: "post", success: function (res) { $("#landing").html(res); } })
        });
    });
    function all() {
        var url = "<?= site_url('admin/jurusan/kurikulum/matakuliah/all') ?>";
        var prodi = $("#kode-program-studi").val();
        $.ajax({ url: url, data: 'kode_program_studi=' + prodi, type: "post", success: function (res) { $("#landing").html(res); } })
    }
    function editMatakuliah(id) {
        var kode_matakuliah = $("#kode-matakuliah-" + id).html();
        var nama_matakuliah = $("#nama-matakuliah-" + id).html();
        var sks_teori = $("#sks-teori-" + id).html();
        var sks_praktek = $("#sks-praktek-" + id).html();
        var sks_praktikum = $("#sks-praktikum-" + id).html();
        var kode_kompetensi = $("#kode-kompetensi-" + id).text().trim();
        var kode_nama_jurusan = $("#kode-nama-jurusan-" + id).text().trim();
        var jenis = $("#jenis-" + id).text().trim();
        var block = $("#block-" + id).text().trim();
        $("#param").val(id);
        $("#edit-kode-matakuliah").val(kode_matakuliah);
        $("#edit-nama-matakuliah").val(nama_matakuliah);
        $("#edit-sks-teori").val(sks_teori);
        $("#edit-sks-praktek").val(sks_praktek);
        $("#edit-sks-praktikum").val(sks_praktikum);
        $("#edit-kode-kompetensi").val(kode_kompetensi);
        $("#edit-kode-nama-jurusan").val(kode_nama_jurusan);
        $("#edit-jenis").val(jenis);
        $("#edit-block").val(block);
        $("#edit-matakuliah").modal("show");
    }
</script>
<script type="text/javascript">
    function hapus(url) {
        swal({
            title: '', text: "Anda yaikin menghapus data ini?", type: 'warning',
            showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak', confirmButtonText: 'Ya'
        }).then(function () {
            $.ajax({
                url: url, success: function (res) {
                    var obj = JSON.parse(res);
                    if (obj.status) { all(); swal('Success!', obj.msg, 'success');
                    } else { swal('Gagal!', obj.msg, 'error'); }
                }
            })
        });
    }
</script>