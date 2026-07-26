<?= $this->session->flashdata('info') ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a href="#" class="btn-sm btn-primary flat" onclick="$('#tambah-kaprodi').modal('toggle')"><i class="fa fa-plus-circle"></i> Tambah</a>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-body">
        <?php if (count($data) > 0): ?>
            <table class="table demo-table">
                <thead>
                    <tr>
                        <th id="th">No</th>
                        <th id="th">Program Studi</th>
                        <th id="th">Nama Dosen</th>
                        <th id="th">Tanda Tangan Dosen</th>
                        <th id="th">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($data as $row) {
                        ?>
                        <tr>
                            <td align="center"><?= $i++ ?>.</td>
                            <td align="left"><?= e($row->nama_program_studi) ?></td>
                            <td><?= e($row->nama_dosen) ?></td>
                            <td align="center">
                                <form id="form-file-<?= e($row->kode_kaprodi) ?>" action="" enctype="multipart/form-data" method="post">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                                    <input id="upload-<?= e($row->kode_kaprodi) ?>" name="foto" type="file" style="display: none" onchange="readURL(this,<?= e($row->kode_kaprodi) ?>);">
                                </form>
                                <?php if (empty($row->tanda_tangan)) : ?>
                                    <img src="<?= base_url('assets/gambar/notfound.png') ?>" onclick="cot(<?= e($row->kode_kaprodi) ?>)" id="upload_link-<?= e($row->kode_kaprodi) ?>"  style="height:70px" alt="">
                                <?php else: ?>
                                    <img src="<?= base_url('assets/signature_kaprodi/'.$row->tanda_tangan) ?>" onclick="cot(<?= e($row->kode_kaprodi) ?>)" id="upload_link-<?= e($row->kode_kaprodi) ?>"  style="height: 70px" alt="">
                                <?php endif; ?>
                            </td>
                            <td align="center">
                                <a href="#" class="btn btn-xs btn-info flat" onclick="editKaprodi(<?= e($row->kode_kaprodi) ?>)"><i class="fa fa-edit"></i> Ubah</a>
                                <a href="#" class="btn btn-xs btn-danger flat" onclick="hapus('<?= site_url('admin/jurusan/program_studi/ketua_jurusan/hapus/' . $row->kode_kaprodi) ?>')"><i class="fa fa-trash"></i> Hapus</a>
                            </td>
                    <p style="display: none;" id="tanda-tangan-<?= e($row->kode_kaprodi) ?>"><?= e($row->tanda_tangan) ?></p>
                    <p style="display: none;" id="kode-nama-jurusan-<?= e($row->kode_kaprodi) ?>"><?= e($row->kode_program_studi) ?></p>
                    <p style="display: none;" id="kode-dosen-<?= e($row->kode_kaprodi) ?>"><?= e($row->kode_dosen) ?></p>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="alert">Tidak ada data Ketua Jurusan.</p>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="tambah-kaprodi" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Tambah Ketua Jurusan</b></h4>
            </div>
            <form enctype="multipart/form-data" method="POST" action="<?= site_url('admin/jurusan/program_studi/ketua_jurusan/simpan'); ?>">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Program Studi</label>
                        <select required class="form-control" name="kode_nama_jurusan">
                            <option value="" disabled selected>Pilih Program Studi</option>
                            <?php foreach ($nama_jurusan as $row) { ?>
                                <option value="<?= e($row->kode_program_studi) ?>"> <?= e($row->singkatan_program_studi) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Dosen</label>
                        <select style="width: 100%;"  class="form-control select2" name="kode_dosen" id="">
                            <option selected disabled>Pilih Nama Dosen</option>
                            <?php foreach ($dosen as $row) : ?>
                                <option value="<?= e($row->kode_dosen) ?>"><?= e($row->nama_dosen) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tandan Tangan Dosen</label>
                        <input type="file" accept="image/jpeg;image/x-png;image/gif" name="tanda_tangan" class="form-control"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
                    <button type="submit" class="btn btn-primary flat "><i class="fa fa-check-circle"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-kaprodi" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="content-edit">
        </div>
    </div>
</div>

<script>
    function editKaprodi(id) {
        var url = "<?= site_url('admin/jurusan/program_studi/ketua_jurusan/edit') ?>/"+id;
        $.ajax({
            url : url,
            success : function (res) {
                $("#content-edit").html(res);
                $(".select2").select2();
                $("#edit-kaprodi").modal("show");
            },
            error :function () {
                console.log('gagal load');
            }
        })
    }
    function hapus(url) {
        swal({
            title: '',
            text: "Anda yakin ingin menghapus data ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            window.location.href = url;
        });
    }
</script>
<script>
    function cot(id) {
        $("#upload-" + id + ":hidden").trigger('click');
    }
    function readURL(input,id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#img').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
            var action = "<?= site_url('admin/jurusan/program_studi/ketua_jurusan/upload_image') ?>/"+id;
            var form = $('#form-file-'+id)[0];
            $.ajax({
                url: action,
                type:"post",
                data: new FormData(form),
                processData:false,
                contentType:false,
                enctype: 'multipart/form-data',
                cache:false,
                async:false,
                success: function(res){
                    console.log(res);
                    var obj = JSON.parse(res)
                    if (obj.status == true) {
                        alert('Berhasil upload gambar');
                        location.reload();
                    }else{
                        alert(obj.msg);
                    }
                },
                error : function () {
                    console.log('gagal');
                }
            });
        }
    }
</script>