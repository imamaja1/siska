<div class="box box-solid flat">
    <div class="box-body">
        <a class="btn btn-primary btn-sm pull-right flat" onclick="$('#tambah-matakuliah').modal('toggle');"><i
                class="fa fa-plus"></i> Tambah Matakuliah</a>&nbsp;
        <a href="<?= site_url('admin/akademik/konversi') ?>" class="btn btn-success btn-sm flat"><i
                class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="box box-primary flat">
    <div class="box-header">

    </div>
    <div class="box-body">
        <p>
        <center><strong>PERUBAHAN KONVERSI MATAKULIAH MAHASISWA TRANSER/LANJUT</strong></center>
        </p>
        <br>
        <div class="col-sm-6 col-md-6 col-lg-6">
            <table class="table">
                <tr>
                    <td><strong>Nama Mahasiswa</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data['nama_mahasiswa']) ?></td>
                </tr>
                <tr>
                    <td><strong>NIM</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data['nim']) ?></td>
                </tr>
            </table>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-6">
            <table class="table">
                <tr>
                    <td><strong>Program Studi</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($prodi->nama_program_studi) ?></td>
                </tr>
                <tr>
                    <td><strong>Fakultas</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($prodi->nama_fakultas) ?></td>
                </tr>
            </table>
        </div>
        <table class="table demo-table" id="table-edit">
            <thead>
            <tr>
                <th id="color" width="20" style="text-align: center;">No.</th>
              <th id="color" style="text-align: center;">Semester</th>  
              <th id="color" style="text-align: center;">Kode MK</th>
                <th id="color" style="text-align: center;">Matakuliah</th>
                <th id="color" style="text-align: center;">Nilai Akhir</th>
                <th id="color" style="text-align: center;">Grade</th>
                <th id="color" style="text-align: center;">Ket</th>

            </tr>
            </thead>
            <tbody>
            <?php $i = 1;
            $sksn = 0;
            $sks = 0;
            foreach ($data['data_nilai'] as $row) : ?>
                <tr>
                    <td style="text-align: center;"><?= $i++ . "." ?></td>
                    <td style="display:none;"><?= e($row['kode_krs_detail']) ?></td>
                   	<td style="text-align: center;"><?= substr($row['kode_matakuliah'], 5, 1);?></td>

	                  <td style="text-align: center;"><?= e($row['kode_matakuliah']) ?></td>
                    <td><?= e($row['nama_matakuliah']) ?></td>
                    <td style="text-align: center;"><?= e($row['nilai_akhir']) ?></td>
                    <td style="text-align: center;"><?= e($row['grade']) ?></td>
                    <td style="text-align: center;"><?= $row['tb'] == 'A' ? 'TB' : '' ?></td>
                </tr>
                <?php
                ?>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!--    modal tambah matakuliah-->
    <div class="modal fade" id="tambah-matakuliah" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Tambah Matakuliah</h4>
                </div>
                <div class="modal-body">
                    <form action="<?= site_url('admin/akademik/konversi/simpan_tambah_konversi') ?>" method="post">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                        <div class="row">
                            <div class="col-xs-8">
                                <div class="form-group">
                                    <label>Nama Matakuliah</label>
                                    <input type="hidden" name="kode_krs" value="<?= e($data['kode_krs']) ?>">
                                    <input type="hidden" name="nim" value="<?= e($data['nim']) ?>">
                                    <select name="id_matakuliah" class="form-control select2" style="width: 100%;">
                                        <option selected disabled>Pilih</option>
                                        <?php foreach ($matakuliah as $row) : ?>
                                            <option value="<?= e($row->id_matakuliah) ?>"><?= e($row->kode_matakuliah) ?>
                                                - <?= e($row->nama_matakuliah) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>Nilai Akhir</label>
                                    <input type="number" name="nilai_ahir" min="1" max="100"
                                           class="form-control" placeholder="Nilai Akhir">
                                </div>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger flat" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-check-square-o"></i> Simpan</button>
                </div>
                </form>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <script type="text/javascript">
        $('#table-edit').Tabledit({
            url: "<?= site_url('admin/akademik/konversi/ubah_krs_nilai_konversi')  ?>",
            hideIdentifier: true,
            buttons: {
                confirm: {
                    class: 'btn btn-sm btn-danger',
                    html: 'Confirm'
                },
                save: {
                    class: 'btn btn-sm btn-success',
                    html: 'Save'
                }
            },
            columns: {
                identifier: [1, 'kode_krs_detail'],
                editable: [[5, 'edit_nilai_akhir'], [7, 'tidak_berhak', '{"N":"Berhak", "A":"TB"}' ]],
            }

        });

        function ref() {
            var url = window.location.href;
            window.location.href = url;
        }
    </script>
  <script type="text/javascript">
    $(function() {
        $('.demo-table').dataTable();
    });
</script>