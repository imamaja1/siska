<?= $this->session->flashdata('info')  ?>
<div class="box box-solid flat" id="filter">
    <div class="box-header">
        <h4><i class="fa fa-search" style="color: #017ebc"></i><strong> Pencarian KHS KPAT</strong></h4>
        <hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="<?= site_url('admin/akademik/kpat/khs/filter')  ?>" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-2">Tahun Akademik</label>
                <div class="col-sm-3">
                    <select required class="form-control" name="tahun_akademik" >
                        <option value="">Pilih</option>
                        <?php foreach ($tahun_akademik as $row) { ?>
                            <option value="<?= e($row->kode_tahun_akademik) ?>"><?php echo $row->tahun_akademik; if($row->semester == 0){echo "- Genap";}else{echo "- Ganjil";}?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Angkatan</label>
                <div class="col-sm-3">
                    <select required class="form-control" name="angkatan">
                        <option value="" selected disabled>Pilih</option>
                        <?php foreach ($tahun_angkatan as $row) { ?>
                            <option value="<?= e(substr($row->tahun_akademik,2, 2)) ?>"><?= e(substr($row->tahun_akademik,0, 4)) ?></option>
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
                            <option value="<?= e($row->kode_program_studi) ?>"><?= e($row->nama_program_studi) ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2"></div>
                <div class="col-sm-3">
                    <button class="btn btn-primary btn-sm flat" type="sumbit" name="sumbit"><i class="fa fa-gear"></i> Proses</button>
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

