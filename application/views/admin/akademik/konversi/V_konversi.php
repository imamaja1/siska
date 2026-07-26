<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/konversi') ?>" class="btn btn-success btn-sm flat"><i class="fa fa-arrow-left"></i> Kembali</a>
        <span class="badge bg-aqua pull-right">Nama Mahasiswa : <?= $data_mahasiswa->nama_mahasiswa ?></span>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-body">
        <form id="form-konversi" action="<?= site_url('admin/akademik/konversi/simpan_konversi') ?>" method="post">
        <div class="table-responsive">
            <table class="table demo-table">
                <thead>
                <tr>
                    <th id="th">CEK</th>
                    <th id="th">KODE MATAKULAIH</th>
                    <th id="th">NAMA MATAKULIAH</th>
                    <th id="th">NILAI AKHIR</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data_kurikulum as $row) : ?>
                    <tr>
                        <td colspan="4" align="center"><strong>SEMESTER <?= $row['semester']?></strong></td>
                    </tr>
                    <?php $i=0; foreach ($row['data'] as $mk) : ?>
                    <tr>
                        <td align="center" width="3%">
                            <input type="checkbox" name="id_matakuliah[]" value="<?= $mk->id_matakuliah?>" id="<?= $mk->id_matakuliah ?>" onclick="mati('<?= $mk->id_matakuliah ?>')">
                        </td>
                        <td align="center"><?= $mk->kode_matakuliah ?></td>
                        <td><?= $mk->nama_matakuliah ?></td>
                        <td align="center">
                            <input required disabled id="mk-<?= $mk->id_matakuliah ?>" type="number" max="100" name="nilai_akhir[]" class="form-control">
                            <input name="nim" type="hidden" value="<?= $data_mahasiswa->nim ?>">
                        </td>
                    </tr>
                    <?php $i++; endforeach; ?>
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