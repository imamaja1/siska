<div class="box box-solid flat">
    <div class="box-body">
        <?php if  (isset($data['data_nilai'])) : ?>
            <a class="btn btn-primary btn-sm pull-right flat" onclick="$('#tambah-matakuliah').modal('toggle');"><i
                        class="fa fa-plus"></i> Tambah Matakuliah</a>&nbsp;
        <?php endif; ?>
        <a href="#" onclick="history.back()" class="btn btn-success btn-sm flat"><i
                    class="fa fa-arrow-left"></i> Kembali</a>
        <?php if  (isset($data['kode_krs'])) : ?>
            <a href="#" class="btn btn-danger btn-sm flat" onclick="hapusKrs(<?= (int) $data['kode_krs'] ?>);"><i
                        class="fa fa-trash"></i> Hapus KRS</a>
        <?php endif; ?>
    </div>
</div>

<div class="box box-primary flat">
    <div class="box-header with-border">

    </div>
    <div class="box-body">
        <?php if  (isset($data['data_nilai'])) : ?>
            <p>
            <center><strong>PERUBAHAN KARTU RENCANA STUDI (KRS)/NILAI KAPT UNTUK SEMESTER INI</strong></center>
            </p>
            <br>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <div class="table-responsive">
                <table class="table table-bordered table-striped">
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
                    <tr>
                        <td><strong>Semester</strong></td>
                        <td><strong>:</strong></td>
                        <td><?= $data['semester'] % 2 == (0) ? "Genap" : "Ganjil"; ?></td>
                    </tr>
                </table>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tr>
                        <td><strong>Program Studi</strong></td>
                        <td><strong>:</strong></td>
                        <td><?= e($data['prodi']->nama_program_studi) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Fakultas</strong></td>
                        <td><strong>:</strong></td>
                        <td><?= e($data['prodi']->nama_fakultas) ?></td>
                    </tr>
<!--                    <tr>-->
<!--                        <td><strong>Kurikulum</strong></td>-->
<!--                        <td><strong>:</strong></td>-->
<!--                        <td>--><?//= $data['kurikulum'] ?><!--</td>-->
<!--                    </tr>-->
                </table>
                </div>
            </div>
            <div class="table-responsive">
            <table class="table table-bordered table-striped demo-table table-krs-mhs" id="table-edit">
                <thead>
                <tr>
                    <th id="color" width="20" style="text-align: center;">No.</th>
                    <th id="color" style="display:none;">Kode KRS Detail</th>
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
                        <td style="display:none;"><?= e($row['kode_krs_detail']) ?></td>
                        <td style="text-align: center;"><?= e($row['kode_matakuliah']) ?></td>
                        <td><?= e($row['nama_matakuliah']) ?></td>
                        <td style="text-align: center;"><?= !empty($row['nilai_harian']) ? e($row['nilai_harian']) : '' ?></td>
                        <td style="text-align:center;"><?= !empty($row['nilai_uts']) ? e($row['nilai_uts']) : '' ?></td>
                        <td style="text-align:center;"><?= !empty($row['nilai_uas']) ? e($row['nilai_uas']) : '' ?></td>
                        <td style="text-align: center;"><?= !empty($row['nilai_akhir']) ? e($row['nilai_akhir']) : '' ?></td>
                        <td style="text-align: center;"><?= !empty($row['grade']) ? e($row['grade']) : '-' ?></td>
                        <td style="text-align: center;"><?= e($row['tb'] == 'A' ? 'TB' : "") ?></td>
                    </tr>
                    <?php ?>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
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
                    <form action="<?= site_url('admin/akademik/kpat/krs/simpan') ?>" method="post">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <div class="form-group">
                            <label>Nama Matakuliah</label>
                            <input type="hidden" name="kode_krs" value="<?= e($data['kode_krs']) ?>">
                            <input type="hidden" name="nim" value="<?= e($data['nim']) ?>">
                            <select required style="width:100%;" name="id_matakuliah" class="form-control select2">
                                <option selected disabled>Pilih</option>
                                <?php foreach ($matakuliah as $row) : ?>
                                    <option value="<?= e($row->id_matakuliah) ?>"><?= e($row->kode_matakuliah) ?>
                                        - <?= e($row->nama_matakuliah) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>Nilai Harian</label>
                                    <input type="number" name="nilai_harian" step="any" min="0" max="100"
                                           class="form-control" placeholder="Nilai Harian">
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>Nilai UTS</label>
                                    <input type="number" name="nilai_uts" step="any" min="0" max="100"
                                           class="form-control" placeholder="Nilai UTS">
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>Nilai UAS</label>
                                    <input  type="number" step="any" name="nilai_uas" min="0" max="100"
                                            class="form-control" placeholder="Nilai UAS">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Nilai Akhir</label>
                                    <input type="number" step="any" name="nilai_akhir" min="0" max="100"
                                           class="form-control" placeholder="Nilai Akhir">
                                </div>
                            </div>
                            <div class="col-xs-6">
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


    <script type="text/javascript">
        $(document).ready(function () {
            if ($('#table-edit').length && $('#table-edit').is('table')) {
                $('#table-edit').Tabledit({
                    url: "<?= site_url('admin/akademik/kpat/krs/ubah_krs_nilai') ?>",
                    hideIdentifier: true,
                    deleteButton: true,
                    restoreButton: false,
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
                    onSuccess: function (data, textStatus, jqXHR) {
                        if (data && data.status === true) {
                            if (data.action === 'delete') {
                                $('#table-edit tbody tr.tabledit-deleted-row').remove();
                                swal("Berhasil!", "Matakuliah berhasil dihapus", "success");
                            }
                        } else {
                            swal("Gagal!", "Gagal memperbarui data", "error");
                        }
                    },
                    onFail: function (jqXHR, textStatus, errorThrown) {
                        swal("Gagal!", "Terjadi kesalahan saat menyimpan", "error");
                    }
                });
            }
        });

        function ref() {
            var url = window.location.href;
            window.location.href = url;
        }

        function hapusKrs(kodeKrs) {
            swal({
                title: "Yakin?",
                text: "Seluruh KRS KPAT beserta matakuliah dan nilainya akan dihapus permanen!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dd4b39",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
                closeOnConfirm: false
            }, function (isConfirm) {
                if (isConfirm) {
                    window.location.href = "<?= site_url('admin/akademik/kpat/krs/hapus_krs/') ?>" + kodeKrs;
                }
            });
        }
    </script>