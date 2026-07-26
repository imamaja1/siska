<?= $this->session->flashdata('info') ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a href="#" class="btn-sm btn-primary flat" onclick="$('#tambah-jenjang').modal('toggle')"><i class="fa fa-plus-circle"></i> Tambah</a>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-body">
        <div class=" box-default flat table-responsive">
            <?php if (count($data_jenjang) > 0): ?>
                <table  class="table demo-table">
                    <thead>
                        <tr>
                            <th id="th">No</th>
                            <th id="th">Kode Jenjang</th>
                            <th id="th">Nama Jenjang</th>
                            <th id="th">Tanggal dan Waktu Terbuat</th>
                            <th id="th">Nama Institusi</th>
                            <th id="th">Tindakan</th>
                        </tr>
                    </thead>

                    <?php
                    $no = 1;
                    foreach ($data_jenjang as $row) {
                        ?>
                        <tr>
                            <td align="center"><?= $no++ ?></td>
                            <td align="center" id="kode_jenjang-<?= e($row->id_jenjang) ?>"><?= e($row->kode_jenjang) ?></td>
                            <td align="center" id="nama_jenjang-<?= e($row->id_jenjang) ?>"><?= e($row->nama_jenjang) ?></td>
                            <td align="center"><?= date('d F Y , H:i:s A', strtotime($row->tanggal_terbuat)); ?></td>
                            <td><?php $ins = []; foreach($data_institusi as $inst) $ins[$inst->kode_institusi] = $inst->singkatan; echo isset($ins[$row->kode_institusi]) ? $ins[$row->kode_institusi] : '-'; ?></td>
                        <p hidden id="kode_institusi-<?= e($row->id_jenjang) ?>"><?= e($row->kode_institusi) ?></p>
                        <td width="150" align="center">
                            <a href="#!" class="btn btn-xs btn-info flat" onclick="javascript:editJenjang(<?= e($row->id_jenjang) ?>)"><i class="fa fa-edit"></i> Ubah</a>&nbsp;
                            <a href="#!" class="btn btn-xs btn-danger flat" onclick="hapus('<?= site_url('admin/jurusan/program_studi/jenjang/hapus/' . $row->id_jenjang) ?>')"><i class="fa fa-trash"></i> Hapus</a>
                        </td>
                        </tr>
                    <?php } ?>

                </table>
            <?php else: ?>
                <p class="alert">Tidak ada data Jenjang.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="tambah-jenjang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Tambah Jenjang</b></h4>
            </div>
            <form method="POST" action="<?= site_url('admin/jurusan/program_studi/jenjang/tambah'); ?>">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Jenjang</label>
                        <input required  class="form-control" type="text"  name="kode_jenjang" id="kode_jenjang" placeholder="Kode Jenjang">
                    </div>
                    <div class="form-group">
                        <label>Nama Jenjang</label>
                        <input required class="form-control" type="text" name="nama_jenjang" id="nama_jenjang" placeholder="Nama Jenjang">
                    </div>
                    <div class="form-group">
                        <label>Nama Institusi</label>
                        <select class="form-control" name="nama_institusi" id="nama_institusi">
                            <option value="" disabled selected>Pilih Nama Institusi</option>
                            <?php foreach ($data_institusi as $row) { ?>
                                <option value="<?= e($row->kode_institusi) ?>"><?= e($row->singkatan) ?></option>
                            <?php } ?>
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

<div class="modal fade" id="edit-jenjang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Edit Jenjang</b></h4>
            </div>
            <form method="POST" action="<?= site_url('admin/jurusan/program_studi/jenjang/ubah'); ?>">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="id_jenjang-edit" id="id_jenjang-edit">
                        <label>Kode Jenjang</label>
                        <input required  class="form-control" type="text"  name="kode_jenjang-edit" id="kode_jenjang-edit" placeholder="Kode Jenjang">
                    </div>
                    <div class="form-group">
                        <label>Nama Jenjang</label>
                        <input required class="form-control" type="text" name="nama_jenjang-edit" id="nama_jenjang-edit" placeholder="Nama Jenjang">
                    </div>
                    <div class="form-group">
                        <label>Nama Institusi</label>
                        <select class="form-control" name="nama_institusi-edit" id="nama_institusi-edit">
                            <option value="" disabled selected>Pilih Nama Institusi</option>
                            <?php foreach ($data_institusi as $row) { ?>
                                <option value="<?= e($row->kode_institusi) ?>"><?= e($row->singkatan) ?></option>
                            <?php } ?>
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

<script>
    function editJenjang(id) {
        var kode_jenjang = $("#kode_jenjang-" + id).html();
        var nama_jenjang = $("#nama_jenjang-" + id).html();
        var kode_institusi = $("#kode_institusi-" + id).html();

        $("#id_jenjang-edit").val(id);
        $("#kode_jenjang-edit").val(kode_jenjang);
        $("#nama_jenjang-edit").val(nama_jenjang);
        $("#nama_institusi-edit").val(kode_institusi);

        $("#edit-jenjang").modal("show");
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