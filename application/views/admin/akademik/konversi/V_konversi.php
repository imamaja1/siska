<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/konversi') ?>" class="btn btn-success btn-sm flat"><i class="fa fa-arrow-left"></i> Kembali</a>
        <span class="badge bg-aqua pull-right">Nama Mahasiswa : <?= e($data_mahasiswa->nama_mahasiswa) ?></span>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-body">
        <form id="form-konversi" action="<?= site_url('admin/akademik/konversi/simpan_konversi') ?>" method="post">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th style="text-align: center; width: 50px;">CEK</th>
                    <th style="text-align: center; width: 120px;">KODE MATAKULIAH</th>
                    <th style="text-align: center;">NAMA MATAKULIAH</th>
                    <th style="text-align: center; width: 120px;">NILAI AKHIR</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data_kurikulum as $row) : ?>
                    <tr class="bg-gray">
                        <td colspan="4" style="text-align: center;"><strong>SEMESTER <?= $row['semester']?></strong></td>
                    </tr>
                    <?php foreach ($row['data'] as $mk) : ?>
                    <tr>
                        <td style="text-align: center;">
                            <input type="checkbox" name="id_matakuliah[]" value="<?= $mk->id_matakuliah?>" id="<?= $mk->id_matakuliah ?>" onclick="mati('<?= $mk->id_matakuliah ?>')">
                        </td>
                        <td style="text-align: center;"><?= e($mk->kode_matakuliah) ?></td>
                        <td><?= e($mk->nama_matakuliah) ?></td>
                        <td style="text-align: center;">
                            <input required disabled id="mk-<?= $mk->id_matakuliah ?>" type="number" max="100" min="0" name="nilai_akhir[]" class="form-control input-sm">
                            <input name="nim" type="hidden" value="<?= e($data_mahasiswa->nim) ?>">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
            <hr>
            <button type="submit" class="btn btn-sm btn-primary flat"><i class="fa fa-check-square-o"></i> Simpan</button>
        </form>
    </div>
</div>

<script>
    function mati(id) {
      var x = $('#'+id).is(':checked');
      if (x)
      {
          $('#mk-'+id).removeAttr('disabled');
      }else {
          $('#mk-'+id).prop('disabled', true);
          $('#mk-'+id).val('');
      }
    }
</script>