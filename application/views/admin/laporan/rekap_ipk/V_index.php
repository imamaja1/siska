<div class="box box-solid flat">
    <div class="box-header">
        <h4><i style="color: #017ebc;" class="fa fa-search"></i> <strong>Rekap IPK</strong> </h4>
        <hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" method="post" action="<?= site_url('admin/laporan/rekap_ipk/filter') ?>">
            <div class="form-group">
                <label class="control-label col-sm-2"> Tahun Akademik</label>
                <div class="col-sm-4">
                    <select required name="tahun_akademik" id="" class="form-control select2">
                        <option value="" selected disabled>Pilih</option>
                        <?php foreach ($tahun_akademik as $row) : ?>
                            <option value="<?= $row->kode_tahun_akademik ?>"><?= $row->tahun_akademik?> - <?= $row->semester == 0 ? 'Genap' : 'Ganjil' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2"> Angkatan</label>
                <div class="col-sm-4">
                    <select required name="angkatan" id="" class="form-control select2">
                        <option value="" selected disabled>Pilih</option>
                        <?php foreach ($tahun_angkatan as $row) { ?>
                            <option value="<?= substr($row->tahun_akademik,2, 2)?>"><?= substr($row->tahun_akademik,0, 4)?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2"> Jurusan</label>
                <div class="col-sm-4">
                    <select required name="prodi" id="" class="form-control select2">
                        <option value="" selected disabled>Pilih</option>
                        <?php foreach ($nama_jurusan as $row) { ?>
                            <option value="<?= $row->kode_program_studi?>"><?= $row->singkatan_program_studi?> - <?= $row->nama_program_studi?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3 col-sm-offset-2">
                    <button type="submit" name="submit" class="btn btn-primary btn-sm flat"><i class="fa fa-gear"></i> Proses</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('form select').on('change invalid', function() {
        var textfield = $(this).get(0);

        // hapus dulu pesan yang sudah ada
        textfield.setCustomValidity('');

        if (!textfield.validity.valid) {
            textfield.setCustomValidity('Tidak boleh kosong!');
        }
    });
</script>