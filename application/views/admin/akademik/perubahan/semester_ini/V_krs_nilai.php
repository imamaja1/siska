<div class="box box-solid flat">
    <div class="box-body">
        <?php if  (isset($data['data_nilai'])) : ?>
        <a class="btn btn-primary btn-sm pull-right flat" onclick="$('#tambah-matakuliah').modal('toggle');"><i
                class="fa fa-plus"></i> Tambah Matakuliah</a>&nbsp;
        <?php endif; ?>
        <a href="<?= site_url('admin/akademik/perubahan/semester_ini') ?>" class="btn btn-success btn-sm flat"><i
                class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="box box-primary flat">
    <div class="box-header">

    </div>
    <div class="box-body">
        <?php if  (isset($data['data_nilai'])) : ?>
        <p>
        <center><strong>PERUBAHAN KARTU RENCANA STUDI (KRS)/NILAI UNTUK SEMESTER INI</strong></center>
        </p>
        <br>
        <div class="col-sm-6 col-md-6 col-lg-6">
            <table class="table">
                <tr>
                    <td><strong>Nama Mahasiswa</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data['nama_mahasiswa'] ?></td>
                </tr>
                <tr>
                    <td><strong>NIM</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data['nim'] ?></td>
                </tr>
                <tr>
                    <td><strong>Semester</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data['semester'] % 2 == (0) ? "Genap" : "Ganjil"; ?></td>
                </tr>
            </table>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-6">
            <table class="table">
                <tr>
                    <td><strong>Program Studi</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data['prodi']->nama_program_studi ?></td>
                </tr>
                <tr>
                    <td><strong>Fakultas</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data['prodi']->nama_fakultas ?></td>
                </tr>
                <tr>
                    <td><strong>Kurikulum</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data['kurikulum'] ?></td>
                </tr>
            </table>
        </div>
        <table class="table demo-table" id="table-edit">
            <thead>
                <tr>
                    <th id="color" width="20" style="text-align: center;">No.</th>
                    <th id="color" style="text-align: center;">Kode</th>
                    <th id="color" style="text-align: center;">Matakuliah</th>
                    <th id="color" style="text-align: center;">Nilai Harian</th>
                    <th id="color" style="text-align: center;">Nilai UTS</th>
                    <th id="color" style="text-align: center;">Nilai UAS</th>
                    <th id="color" style="text-align: center;">Nilai Akhir</th>
                    <th id="color" style="text-align: center;">Grade</th>
                    <th id="color" style="text-align: center;">Ket</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                $sksn = 0;
                $sks = 0;
                foreach ($data['data_nilai'] as $row) :
                    ?>
                    <tr>
                        <td style="text-align: center;"><?= $i++ . "." ?></td>
                        <td style="display:none;"><?= $row['kode_krs_detail'] ?></td>
                        <td style="text-align: center;"><?= $row['kode_matakuliah'] ?></td>
                        <td><?= $row['nama_matakuliah'] ?></td>
                        <td style="text-align:center;"><?= $row['nilai_harian'] ?></td>
                        <td style="text-align:center;"><?= $row['nilai_uts'] ?></td>
                        <td style="text-align:center;"><?= $row['nilai_uas'] ?></td>
                        <td style="text-align: center;"><?= $row['nilai_akhir'] ?></td>
                        <td style="text-align: center;"><?= $row['grade'] ?></td>
                        <td style="text-align: center;"><?= $row['tb'] == 'A' ? 'TB' : "" ?></td>
                    </tr>
                    <?php ?>
                <?php endforeach; ?>
            </tbody>
        </table>
      <p>Total SKS : <?= $data['total_sks']; ?></p>
        <?php else: ?>
            <div class="callout callout-info flat">
                <h4><i class="fa fa-info-circle"></i> Informasi</h4>
                <p>Belum ada data matakulaih dan nilai pada semester ini.</p>
            </div>
        <?php endif; ?>
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
                    <form action="<?= site_url('admin/akademik/perubahan/semester_ini/simpan') ?>" method="post">
                        <div class="form-group">
                            <label>Nama Matakuliah</label>
                            <input type="hidden" name="kode_krs" value="<?= $data['kode_krs'] ?>">
                            <input type="hidden" name="nim" value="<?= $data['nim'] ?>">
                            <select required style="width:100%;" name="id_matakuliah" class="form-control select2">
                                <option selected disabled>Pilih</option>
                                <?php foreach ($matakuliah as $row) : ?>
                                    <option value="<?= $row->id_matakuliah ?>"><?= $row->kode_matakuliah ?>
                                        - <?= $row->nama_matakuliah ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label>Tidak Berhak</label>
                                    <select name="tidak_berhak" id="" class="form-control">
                                        <option selected value="">Pilih</option>
                                        <option value="N">Berhak</option>
                                        <option value="A">Tidak Berhak</option>
                                    </select>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script type="text/javascript">
        $('#table-edit').Tabledit({
            url: "<?= site_url('admin/akademik/perubahan/Semester_ini/ubah_krs_nilai') ?>",
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
                editable: [[4, 'edit_nilai_harian'], [5, 'edit_nilai_uts'], [6, 'edit_nilai_uas'], [7, 'edit_nilai_akhir'],[9, 'tidak_berhak', '{"N": "Berhak", "A": "TB"}']],
            },
            onSuccess: function(data, textStatus, jqXHR) {
                console.log(data);
                if (data) {
                    window.location.reload();    
                }else{
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Data Selain Skripsi dan KPP/KKN/Magang Tidak dapat Diubah',
                        icon: 'error'
                    });
                }
            }
        });

    </script>