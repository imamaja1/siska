<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="box box-solid">
            <div class="box-body">
                <a href="<?= site_url('admin/akademik/khs/serch') ?>" class="btn btn-success btn-flat btn-sm"><i class="fa fa-search"></i> Cari KHS</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <strong><i class="fa fa-search-plus"></i> Filter KHS</strong>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="POST" action="Khs/filter">
                    <div class="form-group">
                        <label class="control-label col-sm-2">Angkatan <span class="text-danger">*</span> : </label>
                        <div class="col-sm-4">
                            <select required name="angkatan" class="form-control">
                                <option value="" selected disabled>Pilih</option>
                                <?php foreach ($tahun_angkatan as $row) { ?>
                                    <option value="<?= substr($row->tahun_akademik, 2, 2) ?>"><?= substr($row->tahun_akademik, 0, 4) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Jurusan <span class="text-danger">*</span> : </label>
                        <div class="col-sm-4">
                            <select required class="form-control" name="prodi">
                                <option value="" selected disabled>Pilih</option>
                                <?php foreach ($nama_jurusan as $row) { ?>
                                    <option value="<?= $row->kode_program_studi ?>"><?= $row->nama_program_studi ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Semester <span class="text-danger">*</span> : </label>
                        <div class="col-sm-4">
                            <select required class="form-control" name="semester">
                                <option value="" selected disabled>Pilih</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4">
                            <button type="submit" name="submit" class="btn btn-primary btn-sm flat"><i class="fa fa-gear"></i> Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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