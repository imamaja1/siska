<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
            <h4 class="modal-title"><i class="fa fa-users"></i> Mahasiswa tidak punya kelas</h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <form id="form-add-mahasiswa" method="post"
                      action="<?= site_url('admin/akademik/kpat/kelas/add_mahasiswa') ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th id="th" width="3%">NO.</th>
                            <th id="th">NIM</th>
                            <th id="th"><input type="checkbox" id="check-all"></th>
                            <th id="th">NAMA MAHASISWA</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;
                        foreach ($semua_mhs as $row) : ?>
                            <tr>
                                <td align="center"><?= $i++ ?>.</td>
                                <td align="center"><?= e($row->nim) ?></td>
                                <td align="center">
                                    <input type="checkbox" class="check-kode-krs-detail" name="kode_krs_detail[]"
                                           value="<?= e($row->kode_krs_detail) ?>">
                                </td>
                                <td><?= e($row->nama_mahasiswa) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <hr>
                    <div class="form-group">
                        <div class="col-sm-4 col-xs-12" style="padding: 0px">
                            <label> Tambah ke : </label>
                            <div class="input-group">
                                <select required name="kelas_id" class="form-control">
                                    <option value="" selected disabled>Pilih</option>
                                    <?php foreach ($nama_kelas as $row) : ?>
                                        <option value="<?= e($row->kelas_id) ?>">Kelas
                                            - <?= e($row->nama_kelas) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="input-group-btn">
                      <button type="submit" name="submit" class="btn btn-danger btn-flat"><i
                                  class="fa fa-arrow-circle-right"></i></button>
                    </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <!-- /.modal-content -->
</div>
    <script>
        $("#form-add-mahasiswa").submit(function (e) {
            e.preventDefault();
            var url = $(this).prop('action');
            var data = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: data,
                success: function () {
                    $('body').removeClass('skin-blue sidebar-mini modal-open');
                    $('body').removeAttr('style');
                    $('body').addClass('skin-blue sidebar-mini');
                    $('.modal-backdrop').remove();
                    // $("#modal-mahasiswa").modal('toggle');
                    swal("Success", "Data berhasil diubah", "success");
                    kelas(matakuliah_id);
                }
            })
        })

        $("#check-all").click(function () {
            var status = $(this).prop('checked');
            if (status){
                $('.check-kode-krs-detail').prop('checked', true);
            }else{
                $('.check-kode-krs-detail').prop('checked', false);
            }
        })
    </script>