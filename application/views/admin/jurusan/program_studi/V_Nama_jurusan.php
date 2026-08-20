<?= $this->session->flashdata('info') ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a href="#" onclick="$('#tambah-nama-jurusan').modal('toggle')" class="btn-primary btn-sm flat"><i class="fa fa-plus-circle"></i> Tambah</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary flat">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table demo-table data-nilai">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Fakultas</th>
                            <th>Nama Prodi</th>
                            <th>Singkatan Prodi</th>
                            <th>Kompetensi</th>
                            <th class="no-sort">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 1;
                        foreach ($data as $row) {
                            ?>
                            <tr>
                                <td align="center"><?= $no++ ?>.</td>
                                <td><?= e($row->nama_fakultas) ?></td>
                                <td id="nama-program-studi-<?= e($row->kode_program_studi) ?>"><?= e($row->nama_program_studi) ?></td>
                                <td id="singkatan-<?= e($row->kode_program_studi) ?>"><?= e($row->singkatan_program_studi) ?></td>
                                <td><?= $row->kompetensi == 'Y' ? 'Ada' : 'Tidak Ada'; ?></td>
                                <td width="150" align="center">
                                    <a href="#" class="btn btn-xs btn-info flat"
                                       onclick="javascript:editNamajurusan('<?= e($row->kode_program_studi) ?>')"><i class="fa fa-edit"></i> Ubah</a>
                                    <a href="#" class="btn btn-xs btn-danger flat hide"
                                       onclick="hapus('<?= site_url('admin/jurusan/program_studi/nama_jurusan/hapus/' . $row->kode_program_studi) ?>')"><i class="fa fa-trash"></i> Hapus</a>
                                </td>
                            <p hidden id="id-jurusan-<?= e($row->kode_program_studi) ?>"><?= e($row->id_jurusan) ?></p>
                            <p hidden id="id-jenjang-<?= e($row->kode_program_studi) ?>"><?= e($row->id_jenjang) ?></p>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambah-nama-jurusan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Tambah Jurusan</b></h4>
            </div>
            <form method="POST" action="<?= site_url('admin/jurusan/program_studi/nama_jurusan/simpan'); ?>">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Fakultas</label>
                        <select class="form-control" name="kode_fakultas" id="" required>
                            <option value="" disabled selected>Pilih Nama Fakultas</option>
                            <?php foreach ($fakultas as $row) { ?>
                                <option value="<?= e($row->kode_fakultas) ?>"><?= e($row->nama_fakultas) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jenjang</label>
                        <select class="form-control" name="jenjang" id="" required>
                            <option value="" disabled selected>Jenjang Pendidikan</option>
                            <option value="2">Diploma</option>
                            <option value="1">Strata 1</option>
                            <option value="3">Strata 2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kode Program Studi</label>
                        <input class="form-control" type="number" name="kode_prodi_univ" maxlength="2" placeholder="Kode Program Studi" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Program Studi</label>
                        <input required class="form-control" type="text" name="nama_jurusan" id="" placeholder="Nama Jurusan" required>
                    </div>
                    <div class="form-group">
                        <label>Singkatan Program Studi</label>
                        <input required class="form-control" type="text" name="singkatan" id="" placeholder="Singkatan" required>
                    </div>
                    <div class="form-group">
                        <label>Sistem Kompetensi</label>
                        <select class="form-control" name="kompetensi" id="" required>
                            <option value="" disabled selected>Pilih ya jika Prodi mempunyai MK Kompetensi</option>
                            <option value="Y">Ya</option>
                            <option value="N">Tidak</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
                    <button type="submit" class="btn btn-success flat "><i class="fa fa-check-circle"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="content-edit-prodi">
        </div>
    </div>
</div>

<script type="text/javascript">
    function editNamajurusan(id) {
        var url = "<?= site_url('admin/jurusan/program_studi/nama_jurusan/edit') ?>/" + id;
        $.ajax({
            url: url,
            success: function (res) {
                $("#content-edit-prodi").html(res);
            },
            error: function () {
                console.log('gagal load');
            }
        })
        $('#edit-modal').modal('show');
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