<?= $this->session->flashdata('info') ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a href="#" id="tambah" class=" btn-sm btn btn-primary flat" data-toggle="modal" onclick="$('#myModal').modal('toggle')"> <i class="fa fa-plus-circle"></i> Tambah</a>
    </div>
</div>

<div class="box box-primary">
    <div class="box-body">
        <table class="table demo-table data-table">
            <thead>
                <tr>
                    <th id="th">No</th>
                    <th id="th">Nama Kurikulum</th>
                    <th id="th">Nama Jurusan</th>
                    <th id="th">Tindakan</th>
                </tr>         
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($data as $d) {
                    ?>
                    <tr>
                        <td><center><?= $i++ ?></center></td>
                <td align="center" id="nama-kurikulum-<?= $d->kode_nama_kurikulum ?>"><?= $d->nama_kurikulum ?> </td>
                <td align="center" id="nama-jurusan-<?= $d->kode_nama_kurikulum ?>"><?= $d->singkatan_program_studi ?></td>
                <td align="center">
                    <a href="#" class="btn btn-xs btn-info flat" onclick="javascript:editNamakurikulum('<?= $d->kode_nama_kurikulum ?>')"><i class="fa fa-edit"></i> Edit</a>&nbsp;
                    <a href="#" class="btn btn-xs btn-danger flat" onclick="hapus('<?= site_url('admin/jurusan/kurikulum/nama_kurikulum/hapus/' . $d->kode_nama_kurikulum) ?>')"><i class="fa fa-trash"></i> Hapus</a>
                </td>
                <div style="display: none;" id="kode-jurusan-<?= $d->kode_nama_kurikulum ?>"><?= $d->kode_program_studi ?></div>
                </tr>
<?php } ?>

            </tbody>
        </table>
    </div>
</div>

<!-- modal tambah prasyarat-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Tambah Nama Kurikulum</b></h4>
            </div>
            <form method="POST" action="<?= site_url('admin/jurusan/kurikulum/nama_kurikulum/simpan') ?>">
            <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kurikulum</label>
                        <input type="text" name="nama_kurikulum" class="form-control" placeholder="Nama Kurikulum">
                    </div>
                    <div>
                        <label>Nama Jurusan</label>
                        <select name="jurusan" class="form-control">
                            <option value="">Pilih</option>
                            <?php foreach ($prodi as $j) { ?>
                                <option value="<?= $j->kode_program_studi ?>"><?= $j->singkatan_program_studi ?> - <?= $j->nama_program_studi ?></option>
<?php } ?>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa  fa-remove"></i> Close</button>
                <button type="submit" name="submit" class="btn btn-success flat"><i class="fa fa-check-circle"></i> Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- model edit-matakuliah -->
<div class="modal fade" id="edit-nama-kurikulum" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Ubah Nama Kurikulum</b></h4>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?= site_url('admin/jurusan/kurikulum/nama_kurikulum/ubah') ?>">
                    <div class="form-group">
                        <label>Nama Kurikulum</label>
                        <input type="hidden" name="param" id="param">
                        <input type="text" name="nama_kurikulum" class="form-control" placeholder="Nama Kurikulum" id="edit-kurikulum">
                    </div>
                    <div class="form-group">
                        <label>Nama Jurusan</label>
                        <select name="jurusan" class="form-control" id="edit-jurusan">  
                            <option value="">Pilih</option>
                            <?php foreach ($prodi as $j) { ?>
                                <option value="<?= $j->kode_program_studi ?>"><?= $j->singkatan_program_studi ?> - <?= $j->nama_program_studi ?></option>
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

<!-- script -->
<script type="text/javascript">
    $(function () {
        $('.dataTables-example').dataTable();
    });
</script>
<script type="text/javascript">
    function editNamakurikulum(id) {

        var nama_kurikulum = $("#nama-kurikulum-" + id).html();
        var nama_jurusan = $("#kode-jurusan-" + id).html();

        $("#param").val(id);
        $("#edit-kurikulum").val(nama_kurikulum);
        $("#edit-jurusan").val(nama_jurusan);


        $("#edit-nama-kurikulum").modal("show");
    }
</script>

<script type="text/javascript">
    function hapus(url)
    {
        swal({
            title: '',
            text: "Anda yakin ingin menghapus data ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya',
        }).then(function () {
            window.location.href = url;
        });
    }
</script>
