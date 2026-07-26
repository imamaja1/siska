<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/keuangan/status_perkuliahan') ?>" class="btn btn-success btn-sm flat"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
    </div>
</div>
<div class="box box-solid flat" id="filter">
    <div class="box-body">
        <form class="form-horizontal" action="<?= site_url('admin/keuangan/status_perkuliahan/filter_rekap') ?>"
              method="POST">
            <div class="form-group">
                <label class="control-label col-sm-2">Tahun Akademik</label>
                <div class="col-sm-3">
                    <select required class="form-control select2" name="tahun_akademik">
                        <option value="">Pilih</option>
                        <?php foreach ($tahun_akademik as $row) { ?>
                        <option value="<?= $row->kode_tahun_akademik ?>"><?php echo $row->tahun_akademik; ?>
                            <?= $row->semester == 0 ? "- Genap" : "- Ganjil" ?>
                            <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Jurusan</label>
                <div class="col-sm-3">
                    <select required class="form-control" name="prodi">
                        <option value="" selected disabled>Pilih</option>
                        <?php foreach ($nama_jurusan as $row) { ?>
                            <option value="<?= $row->kode_program_studi ?>"><?= $row->singkatan_program_studi ?> - (<?= $row->nama_program_studi ?>)</option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2"></div>
                <div class="col-sm-3">
                    <button class="btn btn-primary flat" type="sumbit" name="sumbit"><i class="fa fa-gear"></i> Proses
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
