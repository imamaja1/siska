<?php if (!empty($kompetensi_mahasiswa)) : ?>
    <div class="box box-primary flat">
        <div class="box-body">
            <table class="table demo-table">
                <thead>
                <tr>
                    <th id="th">NIM</th>
                    <th id="th">Kompetensi</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td align="center"><?= e($kompetensi_mahasiswa->nim) ?></td>
                    <td align="center"><?= e($kompetensi_mahasiswa->nama_kompetensi) ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <div class="modal fade" id="tambah-kompetensi">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Kompetensi Mahasiswa</h4>
                </div>
                <form action="<?= site_url('mahasiswa/kompetensi/simpan') ?>" method="post">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for=""> Kompetensi</label>
                            <select name="kode_kompetensi" required id="kode_kompetensi" class="form-control">
                                <option selected disabled>Pilih</option>
                                <?php foreach ($data_kompetensi as $row) : ?>
                                    <option value="<?= e($row->kode_kompetensi) ?>"><?= e($row->singkatan_kompetensi) ?>
                                        - <?= e($row->nama_kompetensi) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-info"><i class="fa fa-info-circle"></i> Pilih kompetensi anda. pilih salah satu kompetensi di atas untuk mengecek matakulah konsentrasi dan
                            untuk mengecek ketersediaan konsntrasi pada kurikulum anda.</small>
                        </div>
                        <div id="landing">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger flat" data-dismiss="modal"><i
                                    class="fa fa-times"></i> Batal
                        </button>
                        <button type="submit" name="submit" id="submit" disabled class="btn btn-primary flat"><i
                                    class="fa fa-check-square-o"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <script>
        $(document).ready(function () {
            $('#tambah-kompetensi').modal('show');

            $("#kode_kompetensi").change(function (e) {
                var kode_kompetensi = $(this).val();
                var url = "<?= site_url('mahasiswa/kompetensi/matakuliah_konsentrasi') ?>/"+kode_kompetensi;
                $.ajax({
                    url : url,
                    type : 'get',
                    success : function (res) {
                        obj = JSON.parse(res);
                        // console.log(obj);
                        $("#landing").html(obj.view)
                        if (obj.status){
                            $("#submit").prop('disabled',false)
                        }else{
                            $("#submit").prop('disabled',true)
                        }
                    },
                    error : function (err) {
                        console.log(err);
                    }
                })
            })
        })
    </script>
<?php endif; ?>
