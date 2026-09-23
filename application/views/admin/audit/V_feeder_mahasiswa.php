<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h4><i class="fa fa-search"></i> Audit Nilai SISKA vs Feeder - Mahasiswa</h4>
            </div>
            <div class="box-body">
                <form id="form-filter" class="form-horizontal" method="post"
                      action="<?= site_url('admin/audit/mahasiswa/hasil') ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group">
                        <label class="control-label col-sm-2">NIM <span class="text-danger">*</span></label>
                        <div class="col-sm-4 col-xs-12">
                            <input required type="text" name="nim" class="form-control" placeholder="Masukkan NIM mahasiswa">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Tahun Akademik <span class="text-danger">*</span></label>
                        <div class="col-sm-4 col-xs-12">
                            <select required name="kode_tahun_akademik" class="form-control select2">
                                <option value="" selected disabled>Pilih</option>
                                <option value="all">Semua Tahun Akademik</option>
                                <?php foreach ($tahun_akademik as $row) : ?>
                                    <option value="<?= e($row->kode_tahun_akademik) ?>"><?= e($row->tahun_akademik) ?> - <?= $row->semester == 1 ? 'Ganjil' : 'Genap' ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Pilih "Semua Tahun Akademik" untuk audit dari TA angkatan mahasiswa sampai TA terbaru.</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-4">
                            <button type="submit" class="btn btn-primary flat"><i class="fa fa-search"></i> Tampilkan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row" id="result"></div>

<script>
    var loading = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";

    $("#form-filter").submit(function (e) {
        e.preventDefault();
        $("#result").html(loading);
        $.ajax({
            url: $(this).prop('action'),
            data: $(this).serialize(),
            type: 'post',
            success: function (res) {
                $("#result").html(res);
                initAuditFeederTable();
            },
            error: function () {
                $("#result").html("<div class='col-md-12'><div class='callout callout-danger flat'><p>Terjadi kesalahan koneksi.</p></div></div>");
            }
        });
    });

    function initAuditFeederTable() {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('.data-table')) {
            $('.data-table').DataTable().destroy();
        }
        if ($.fn.DataTable) {
            $('.data-table').DataTable({
                pageLength: 25,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
            });
        }
    }
</script>
