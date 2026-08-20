<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="box box-solid flat">
            <div class="box-body">
                <a href="<?= site_url('admin/akademik/pembimbing_kkp') ?>" class="btn btn-danger btn-sm"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="box box-primary flat">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-list"></i> Daftar Bimbingan <span class="text-orange"> <?= e($dosen) ?> </span></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table demo-table data-table">
                            <thead>
                                <tr>
                                    <th style="text-align: center;width:3%;">NO.</th>
                                    <th style="text-align: center">NIM</th>
                                    <th style="text-align: center">NAMA MAHASISWA</th>
                                    <th style="text-align: center">TGL. PELAKSANAAN</th>
                                    <th style="text-align: center">BATAS LAPORAN</th>
                                    <th style="text-align: center">SISA WAKTU</th>
                                    <th style="text-align: center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $no=1; foreach ($data as $row) : ?>
                                <tr>
                                    <td><?= $no++ ?>.</td>
                                    <td align="center"><?= e($row->nim) ?></td>
                                    <td><?= e($row->nama_mahasiswa) ?></td>
                                    <td><?= !empty($row->tgl_pelaksanaan) ? date('d M Y',strtotime($row->tgl_pelaksanaan)) : '-' ?></td>
                                    <td><?= !empty($row->batas_laporan) ? date('d M Y',strtotime($row->batas_laporan)) : '-' ?></td>
                                    <td><?= !empty($row->batas_laporan) ? day_left($row->batas_laporan) : '-' ?></td>
                                    <td align="center">
                                        <div class="btn-group btn-group-xs">
                                            <a href="#" id="<?= $row->id_pembimbing_kkp ?>" class="btn btn-default pindah" title="Pindah Pembimbing"><i class="fa fa-arrow-circle-o-up"></i></a>
                                            <a href="#" onclick="edit('<?= $row->id_pembimbing_kkp ?>','<?= $kode_dosen ?>')" class="btn btn-info" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-edit"></i></a>
                                            <a href="#" onclick="hapus('<?= $row->id_pembimbing_kkp ?>')" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Hapus"><i class="fa fa-trash"></i></a>
                                            <?php if ($row->id_nilai != null) : ?>
                                            <a href="<?= site_url('admin/akademik/pembimbing_kkp/penilaian_pembimbing/'.$row->id_pembimbing_kkp) ?>" class="btn btn-warning" title="Penilaian pembimbing"><i class="fa fa-file-excel-o"></i></a>
                                            <a href="<?= site_url('admin/akademik/pembimbing_kkp/nilai_gabungan/'.$row->id_pembimbing_kkp) ?>" class="btn btn-success" title="Nilai Gabungan"><i class="fa fa-file-excel-o"></i></a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</div>
<!--modal tambah bimbingan-->
<div class="modal fade" id="modal-edit" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-content-edit">

        </div>
    </div>
</div>
<!--end modal tambah bimbingan-->
<div id="form" class="hide">
    <form method="post" id="form-pindah" action="<?= site_url('admin/akademik/pembimbing_kkp/pindah') ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label for="">Dosen</label>
                <div class="input-group">
                    <input type="hidden" name="id" id="id-pembimbing-kkp">
                    <select name="kode_dosen" id="kode-dosen" required class="form-control select2">
                        <option value=""></option>
                        <?php foreach ($all_dosen as $row) : ?>
                            <option value="<?= e($row->kode_dosen) ?>"><?= e($row->nama_dosen) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="input-group-btn">
                      <button type="submit" class="btn btn-info btn-flat">Go!</button>
                    </span>
                </div>
            </div>
    </form>
</div>
<script>
    var id;
    function hapus(id) {
        var url = "<?= site_url('admin/akademik/pembimbing_kkp/hapus') ?>/"+id;
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

    function edit(id,kode_dosen) {
        var url = "<?= site_url('admin/akademik/pembimbing_kkp/edit') ?>/"+id+"/"+kode_dosen;
        $.ajax({
            url : url,
            success : function (res) {
                $('#modal-content-edit').html(res);
                $('#modal-edit').modal('show');
            },
            error : function () {
                console.log('gagal load');
            }
        })

    }

    $(document).ready(function () {
        $('.pindah').popover({
            placement: 'left',
            html:true,
            trigger: "click",
            container : 'body',
            content:  $('#form').html(),
        }).click(function () {
            $('.pindah').not(this).popover('hide');
            id = $(this).attr("id");
            $('.popover-content').find('#id-pembimbing-kkp').val(id);
            console.log($('.popover-content').find('#id-pembimbing-kkp').val());
            $(".select2").select2({
                placeholder : 'Pilih',
            });
            $(".select2").on("select2:open", function () {
                $(".select2-dropdown").css('z-index',9991);
            });
        })

    })


</script>