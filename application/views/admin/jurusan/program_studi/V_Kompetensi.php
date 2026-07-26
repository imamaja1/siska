<?= $this->session->flashdata('info') ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a href="#" class="btn-sm btn-primary flat" onclick="$('#tambah-kompetensi').modal('toggle')"><i class="fa fa-plus-circle"></i> Tambah</a>
              <a href="<?=site_url('admin/jurusan/konsentrasi')?>" class="btn-sm btn-success flat" ><i class="fa fa-check-circle"></i> Data MK Komptetensi</a>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-body">
        <?php if (count($data) > 0): ?>
            <table  class="table demo-table">
                <thead>
                    <tr>
                        <th id="th">No</th>
                        <th id="th">Nama Kompetensi</th>
                        <th id="th">Singkatan</th>
                        <th id="th">Nama Jurusan</th>
                        <th id="th">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($data as $row) {
                        ?>
                        <tr>
                            <td align="center"><?= $i++ ?></td>
                            <td id="nama-kompetensi-<?= $row->kode_kompetensi ?>"><?= $row->nama_kompetensi ?></td>
                            <td align="center" id="singkatan-kompetensi-<?= $row->kode_kompetensi ?>"><?= $row->singkatan_kompetensi ?></td>

                            <td align="center"><?php $ns = []; foreach($kode_nama_jurusan as $n) $ns[$n->kode_program_studi ?? $n->kode_program_studi] = $n->nama_program_studi ?? $n->singkatan_program_studi; echo $ns[$row->kode_program_studi] ?? '-'; ?></td>
                    <p hidden id="kode-nama-jurusan-<?= $row->kode_kompetensi ?>"><?= $row->kode_program_studi ?></p>
                    <td align="center">
                        <a href="#" class="btn btn-xs btn-info flat" onclick="javascript:editKompetensi(<?= $row->kode_kompetensi ?>)"><i class="fa fa-edit"></i> Ubah</a>
                        <a href="#" class="btn btn-xs btn-danger flat" onclick="hapus('<?= site_url('admin/jurusan/program_studi/kompetensi/hapus/' . $row->kode_kompetensi) ?>')"><i class="fa fa-trash"></i> Hapus</a>
                    </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="alert">Tidak ada data Jenjang.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Tambah Jenjang -->
<div class="modal fade" id="tambah-kompetensi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Tambah Kompetensi</b></h4>
            </div>
            <form method="POST" action="<?= site_url('admin/jurusan/program_studi/Kompetensi/simpan'); ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kompetensi</label>
                        <input type="text" name="nama_kompetensi" placeholder="Nama Kompetensi" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Singkatan Kompetensi</label>
                        <input tpye="text" name="singkatan_kompetensi" placeholder="Singkatan Kompetensi" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Nama Program Studi</label>
                        <select name="kode_nama_jurusan" class="form-control">
                            <option value="" disabled selected >Pilih Program Studi</option>
                            <?php foreach ($kode_nama_jurusan as $row) { ?>
                                <option value="<?= $row->kode_program_studi ?>"><?= $row->singkatan_program_studi ?> </option>
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

<!-- Edit Jenjang -->
<div class="modal fade" id="edit-kompetensi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Edit Kompetensi</b></h4>
            </div>
            <form method="POST" action="<?= site_url('admin/jurusan/program_studi/kompetensi/ubah'); ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kompetensi</label>
                        <input type="hidden" name="param" id="param">
                        <input type="text" name="nama_kompetensi" placeholder="Nama Kompetensi" class="form-control" id="edit-nama-kompetensi">
                    </div>
                    <div class="form-group">
                        <label>Singkatan Kompetensi</label>
                        <input type="text" name="singkatan_kompetensi" placeholder="Singkatan Kompetensi" class="form-control" id="edit-singkatan-kompetensi">
                    </div>
                    <div class="form-group">
                        <label>Nama Program Studi</label>
                        <select name="kode_nama_jurusan" class="form-control" id="edit-kode-nama-jurusan">
                            <option value="" selected disabled>Pilih Program Studi</option>
                            <?php foreach ($kode_nama_jurusan as $row) { ?>
                                <option value="<?= $row->kode_program_studi ?>"><?= $row->singkatan_program_studi ?></option>
                            <?php } ?>
                        </select>
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

<!-- Hapus Institusi -->
<script>
    function editKompetensi(id) {
        var nama_kompetensi = $("#nama-kompetensi-" + id).html();
        var singkatan_kompetensi = $("#singkatan-kompetensi-" + id).html();
        var kode_nama_jurusan = $("#kode-nama-jurusan-" + id).html();

        $("#param").val(id);
        $("#edit-nama-kompetensi").val(nama_kompetensi);
        $("#edit-singkatan-kompetensi").val(singkatan_kompetensi);
        $("#edit-kode-nama-jurusan").val(kode_nama_jurusan);


        $("#edit-kompetensi").modal("show");
    }

    function hapus(url)
    {
        swal({
            title: '',
            text: "Anda yakin ingin menghapus data ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText : "Tidak",
            confirmButtonText: 'Ya'
        }).then(function () {
            window.location.href = url;
        });
    }
</script>