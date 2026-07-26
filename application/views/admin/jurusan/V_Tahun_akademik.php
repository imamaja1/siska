<?= $this->session->flashdata('info') ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a href="#" class="btn btn-primary btn-sm flat" onclick="$('#tambah-tahun-akademik').modal('toggle')"><i class="fa fa-plus-circle"></i> Tambah</a>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-body">
        <table class="table demo-table">
            <thead>
                <tr>
                    <th id="th">No</th>
                    <th id="th">Tahun Akademik</th>
                    <th id="th">Semester</th>
                    <th id="th">Tanggal mulai</th>
                    <th id="th">Tanggal Berakhir</th>
                    <th id="th">Status</th>
                    <th id="th">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($data as $d) {
                    ?>
                    <tr>
                        <td align="center"><?= $i++ ?></td>
                        <td align="center" id="tahun-akademik-<?= e($d->kode_tahun_akademik) ?>"><?= e($d->tahun_akademik) ?></td>
                        <td align="center" ><?= $d->semester == 1 ? 'Ganjil' : 'Genap' ?></td>
                        <td align="center" id="semester-<?= e($d->kode_tahun_akademik) ?>" style="display: none;"><?= e($d->semester) ?></td>
                        <td align="center" id="tanggal-mulai-<?= e($d->kode_tahun_akademik) ?>"><?= e($d->tanggal_mulai) ?></td>
                        <td align="center" id="tanggal-berakhir-<?= e($d->kode_tahun_akademik) ?>"><?= e($d->tanggal_berakhir) ?></td>
                        <td align="center" width="90">
                            <?php if ($d->status == "N") { ?>
                                <a href="#" id="aktifkan-<?= e($d->kode_tahun_akademik) ?>" onclick="aktif('<?= e($d->kode_tahun_akademik) ?>')" class="btn btn-xs btn-danger flat sak"><i class="fa fa-times-circle"></i> Nonaktif</a>&nbsp;
                            <?php } else { ?>
                                <a href="#" id="aktifkan-<?= e($d->kode_tahun_akademik) ?>" onclick="nonaktif('<?= e($d->kode_tahun_akademik) ?>')" class="btn btn-xs btn-success flat sak"><i class="fa fa-check-circle"></i> Aktif</a>&nbsp;
                            <?php } ?>
                        </td>
                <p hidden id="status-<?= e($d->kode_tahun_akademik) ?>"><?= e($d->status) ?></p>
                <td width="130" align="center">
                    <a href="#" class="btn btn-xs btn-info flat" onclick="javascript:editTahunakademik('<?= e($d->kode_tahun_akademik) ?>')"><i class="fa fa-edit"></i> Edit</a>&nbsp;
                    <a href="#" class="btn btn-xs btn-danger flat" onclick="hapus('<?= site_url('admin/jurusan/tahun_akademik/hapus/' . $d->kode_tahun_akademik) ?>')"><i class="fa fa-trash"></i> Hapus</a>
                </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!--modal tambah tahun akademik-->
<div class="modal fade" id="tambah-tahun-akademik" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Tambah Tahun Akademik</b></h4>
            </div>
<form class="form-horizontal" id="form" method="POST" action="<?= site_url('admin/jurusan/tahun_akademik/simpan') ?>">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Tahun Akademik</label>
                        <div class="col-sm-5">
                            <input type="hidden" name="param" id="param">
                            <input type="text" required name="tahun_akademik" id="edit-tahun-akademik" placeholder="Tahun Akademik" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Semester</label>
                        <div class="col-sm-5">
                            <select required class="form-control" id="edit-semester" name="semester">
                                <option value="">Pilih</option>
                                <option value="1">Ganjil</option>
                                <option value="0">Genap</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Tanggal Mulai</label>
                        <div class="col-sm-5">
                            <div class="input-group date">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" name="tanggal_mulai" id="edit-tanggal-mulai" placeholder="Tahun Akademik" class="form-control datepicker">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Tanggal Berakhir</label>
                        <div class="col-sm-5">
                            <div class="input-group date">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" name="tanggal_berakhir" id="edit-tanggal-berakhir" placeholder="Tahun Akademik" class="form-control datepicker">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                    <button type="submit" id="submit" name="submit" class="btn btn-success flat"><i class="fa fa-check-circle"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--edit tahun akademik-->
<div class="modal fade" id="modal-edit" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="content-edit">
        </div>
    </div>
</div>
<script>
    $(function () {
        $(".datepicker").datepicker({
            format: "yyyy/mm/dd",
        });
    });

    function hapus(url)
    {
        swal({
            title: '',
            text: "Anda yaikin menghapus data ini?",
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

    function editTahunakademik(id) {
        var url = "<?= site_url('admin/jurusan/tahun_akademik/edit') ?>/"+id;
        $.ajax({
            url : url,
            success : function (res) {
                $("#content-edit").html(res);
                $("#modal-edit").modal('show');
            },
            error : function () {
                console.log('gagal load data');
            }
        });
    }

    function aktif(id) {
        $.ajax({
            url: "<?= site_url('admin/jurusan/tahun_akademik/aktifkan/') ?>",
            type: "POST",
            data: "&id=" + id,
            cache: false,
            success: function () {
                location.reload();
            }
        });
    }


</script>