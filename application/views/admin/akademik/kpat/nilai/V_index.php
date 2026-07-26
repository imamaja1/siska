<div class="box box-primary flat">
    <div class="box-header">
        <h4><i class="fa fa-search" style="color: #017ebc"></i><strong> Pencarian data penilaian mahasiswa</strong></h4>
        <hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" method="POST" action="<?= site_url('admin/akademik/kpat/Nilai/filter') ?>">
            <div class="form-group">	
                <label class="control-label col-sm-2">Tahun Akadmeik : </label>
                <div class="col-sm-4">
                    <select required class="form-control" name="tahun_akademik" id="kode-tahun-akademik">
                        <option value="" selected disabled>Pilih</option>
                        <?php foreach ($tahun_akademik as $row) : ?>
                            <option value="<?= $row->kode_tahun_akademik ?>"><?= $row->tahun_akademik ?> - <?= $row->semester % 2 == (0) ? "Genap" : "Ganjil" ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class=" control-label col-sm-2">Program Studi : </label>
                <div class="col-sm-4">
                    <select required class="form-control" name="prodi" id="prodi">
                        <option value="" selected disabled>Pilih</option>
                        <?php foreach ($prodi as $row) : ?>
                            <option value="<?= $row->kode_program_studi ?>"><?= $row->nama_program_studi ?> </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class=" control-label col-sm-2">Matakuliah : </label>
                <div class="col-sm-4">
                    <select required class="form-control select2" name="id_matakuliah" id="kode-matakuliah">
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class=" control-label col-sm-2"></label>
                <div class="col-sm-4">
                    <button class="btn btn-primary btn-sm flat"><i class="fa fa-gear"></i> Proses</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $('#prodi').change(function () {
        var kode_program_studi = $('#prodi').val();
        var kode_tahun_akademik = $('#kode-tahun-akademik').val();

        $.ajax({
            url: "<?= site_url('admin/akademik/kpat/nilai/get_matakuliah') ?>",
            type: "POST",
            data: "kode_program_studi=" + kode_program_studi+"&kode_tahun_akademik="+kode_tahun_akademik,
            success: function (data) {
                $('#kode-matakuliah').html(data);
            },
            error: function () {
                console.log = "gagal mengambil data";
            }
        });

    });

    $('form select').on('change invalid', function () {
        var textfield = $(this).get(0);

        // hapus dulu pesan yang sudah ada
        textfield.setCustomValidity('');

        if (!textfield.validity.valid) {
            textfield.setCustomValidity('Tidak boleh kosong!');
        }
    });
</script>