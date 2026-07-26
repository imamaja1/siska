<div class="box box-solid">
    <div class="box-body">
        <?php if ($status == 'N') : ?>
        <a href="<?= site_url('admin/kuisioner/kuisioner/aktif') ?>" class="btn btn-danger flat pull-right"><i class="fa fa-times"></i> Tidak Aktif</a>
        <?php else:?>
        <a href="<?= site_url('admin/kuisioner/kuisioner/nonaktif') ?>" class="btn btn-success flat pull-right"><i class="fa fa-check-square-o"></i> Aktif</a>
        <?php endif;?>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-header">
        <h4><i class="fa fa-file-excel-o"></i> Laporan Hasil Kuisioner</h4>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="<?= site_url('admin/kuisioner/kuisioner/filter') ?>" method="post">
            <div class="form-group">
                <label for="tahun_akademik" class="control-label col-sm-2">Tahun Akademik</label>
                <div class="col-sm-4">
                    <select required name="kode_tahun_akademik" id="kode-tahun-akademik" class="form-control select2">
                        <option value="" selected disabled>Pilih</option>
                        <?php foreach ($tahun_akademik as $row) :?>
                            <option value="<?= $row->kode_tahun_akademik ?>"><?= $row->tahun_akademik ?> - <?= $row->semester == 1 ? 'Ganjil' : 'Genap' ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Matakuliah</label>
                <div class="col-sm-4">
                <select required name="id_matakuliah" id="matakuliah" class="form-control select2">
                    
                </select>
                </div>
            </div>
            <div class="form-group">
                <label for="kelas" class="control-label col-sm-2">Kelas</label>
                <div class="col-sm-4">
                    <select required name="kelas_id" id="nama-kelas-id" class="form-control">
                        <option value="" selected disabled>Pilih</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                    <button type="submit" name="sumbit" class="btn btn-primary flat"><i class="fa fa-gear"></i> Prosses</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('#kode-tahun-akademik').change(function () {
        var kode_tahun_akademik = $(this).val();
        $('#matakuliah').html('data tidak ditemukan');
        $('#matakuliah').load('<?= site_url("admin/kuisioner/kuisioner/combobox") ?>/'+kode_tahun_akademik);
    })

    $('#matakuliah').change(function () {
        var id_matakuliah = $(this).val();
        var kode_tahun_akademik = $('#kode-tahun-akademik').val();
        $.ajax({
            url : '<?= site_url("admin/kuisioner/kuisioner/get_kelas") ?>/'+id_matakuliah+'/'+kode_tahun_akademik,
            success : function (data) {
                $('#nama-kelas-id').html(data);
            },
            error : function () {
                console.log('kamu gagal load');
            }
        })
    })
</script>