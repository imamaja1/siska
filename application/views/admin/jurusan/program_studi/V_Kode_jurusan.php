<?= $this->session->flashdata('info') ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a href="#" onclick="$('#tambah-kode-jurusan').modal('toggle')" class="btn-sm btn-primary flat"><i class="fa fa-plus-circle"></i> Tambah</a>
    </div>
</div>

<div class="box box-primary flat">
    <div class="box-body">
        <table class="table demo-table">
            <thead>
                <tr>
                    <th id="th">No</th>
                    <th id="th">Kode Jurusan</th>
                    <th id="th">Nama Jurusan</th>
                    <th id="th">Tanggal dan Waktu terbuat</th>
                    <th id="th">Nama Institusi</th>
                    <th id="th">Tindakan</th>
                </tr>
            </thead>
            <?php
            $i = 1;
            foreach ($data as $row) {
                ?>
                <tr>
                    <td align="center"><?= $i++ ?></td>
                    <td align="center" id="kode-jurusan-<?= e($row->id_jurusan) ?>"><?= e($row->kode_jurusan) ?></td>
                    <td id="nama-jurusan-<?= e($row->id_jurusan) ?>"><?= e($row->nama_jurusan) ?></td>
                    <td align="center"><?= date('d F Y , H:i:s A', strtotime($row->tanggal_terbuat)); ?></td>
                    <td><?php $is = []; foreach($institusi as $ins) $is[$ins->kode_institusi] = $ins->singkatan; echo isset($is[$row->kode_institusi]) ? $is[$row->kode_institusi] : '-'; ?></td>
                    <td width="150" align="center">
                        <a href="#" class="btn btn-xs btn-info flat" onclick="javascript:editJurusan('<?= e($row->id_jurusan) ?>')"><i class="fa fa-edit"></i> Ubah</a>
                        <a href="#" class="btn btn-xs btn-danger flat" onclick="hapus('<?= site_url('admin/jurusan/program_studi/kode_jurusan/hapus/' . $row->id_jurusan) ?>')"><i class="fa fa-trash"></i> Hapus</a>
                    </td>
                <p id="kode-institusi-<?= e($row->id_jurusan) ?>" style="display: none;"><?= e($row->kode_institusi) ?></p>
                <p id="kode-pengguna-<?= e($row->id_jurusan) ?>" style="display: none;"><?= e($row->kode_pengguna) ?></p>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>

<div class="modal fade" id="tambah-kode-jurusan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Tambah Kode Jurusan</b></h4>
            </div>
            <form method="POST" action="<?= site_url('admin/jurusan/program_studi/kode_jurusan/simpan'); ?>">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Jurusan</label>
                        <input required  class="form-control" type="text"  name="kode_jurusan" id="" placeholder="Kode Jurusan">
                    </div>
                    <div class="form-group">
                        <label>Nama Jurusan</label>
                        <input required class="form-control" type="text" name="nama_jurusan" id="" placeholder="Nama Jurusan">
                    </div>
                    <div class="form-group">
                        <label>Nama Institusi</label>
                        <select class="form-control" name="nama_institusi" id="">
                            <option value="" disabled selected>Pilih Nama Institusi</option>
                            <?php foreach ($institusi as $row) { ?>
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

<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Edit Kode Jurusan</b></h4>
            </div>
            <form method="POST" action="<?= site_url('admin/jurusan/program_studi/kode_jurusan/ubah'); ?>">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Jurusan</label>
                        <input type="hidden" name="param" id="param">
                        <input required  class="form-control" type="text"  name="kode_jurusan" id="edit-kode-jurusan" placeholder="Kode Jurusan">
                    </div>
                    <div class="form-group">
                        <label>Nama Jurusan</label>
                        <input required class="form-control" type="text" name="nama_jurusan" id="edit-nama-jurusan" placeholder="Nama Jurusan">
                    </div>
                    <div class="form-group">
                        <label>Nama Institusi</label>
                        <select class="form-control" name="nama_institusi" id="edit-kode-institusi">
                            <option value="" disabled selected>Pilih Nama Institusi</option>
                            <?php foreach ($institusi as $row) { ?>
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
<script type="text/javascript">
    function editJurusan(id) {
        var kode_jurusan = $('#kode-jurusan-' + id).html();
        var nama_jurusan = $('#nama-jurusan-' + id).html();
        var kode_institusi = $('#kode-institusi-' + id).html();
        $('#param').val(id);
        $('#edit-kode-jurusan').val(kode_jurusan);
        $('#edit-nama-jurusan').val(nama_jurusan);
        $('#edit-kode-institusi').val(kode_institusi);
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