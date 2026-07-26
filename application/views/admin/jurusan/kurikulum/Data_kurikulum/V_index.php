<div class="box box-solid flat">
    <div class="box-body flat">
        <form action="<?= site_url('admin/jurusan/kurikulum/Data_kurikulum/filter') ?>" method="POST" class="form-horizontal">

            <div class="col-sm-5">
                <select required class="form-control select2" name="nama_kurikulum">
                    <option selected disabled>Pilih Kurikulum</option>
                    <?php foreach ($nama_kurikulum as $row) : ?>
                        <option value="<?= $row->kode_nama_kurikulum ?>"> <?= $row->nama_kurikulum . " (" . $row->singkatan_program_studi . ")" ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-primary flat" name="submit"><i class="fa fa-gear"></i> Proses</button>
            </div>
        </form>
    </div>
</div>

<script>
    $('form select').on('change invalid', function () {
        var textfield = $(this).get(0);

        // hapus dulu pesan yang sudah ada
        textfield.setCustomValidity('');

        if (!textfield.validity.valid) {
            textfield.setCustomValidity('Tidak boleh kosong!');
        }
    });
</script>