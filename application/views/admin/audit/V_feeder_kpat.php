<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h4><i class="fa fa-search"></i> Audit Nilai SISKA vs Feeder - KPAT (Perkuliahan Pendek)</h4>
            </div>
            <div class="box-body">
                <form id="form-filter" class="form-horizontal" method="post"
                      action="<?= site_url('admin/audit/kpat/hasil') ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group">
                        <label class="control-label col-sm-2">Tahun Akademik <span class="text-danger">*</span></label>
                        <div class="col-sm-4 col-xs-12">
                            <select required name="tahun_akademik" id="kode-tahun-akademik" class="form-control select2">
                                <option value="" selected disabled>Pilih Tahun Akademik</option>
                                <?php foreach ($tahun_akademik as $row) : ?>
                                    <option value="<?= e($row->tahun_akademik) ?>"><?= e($row->tahun_akademik) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Program Studi <span class="text-danger">*</span></label>
                        <div class="col-sm-4 col-xs-12">
                            <select required name="kode_program_studi" id="kode-program-studi" class="form-control select2">
                                <option value="" selected disabled>Pilih</option>
                                <?php foreach ($prodi as $row) : ?>
                                    <option value="<?= e($row->kode_program_studi) ?>"><?= e($row->nama_program_studi) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Matakuliah KPAT <span class="text-danger">*</span></label>
                        <div class="col-sm-4 col-xs-12">
                            <select required name="id_matakuliah" id="id-matakuliah" class="form-control select2">
                                <option value="" selected disabled>Pilih Tahun Akademik dan Program Studi dahulu</option>
                            </select>
                            <input type="hidden" name="kode_matakuliah" id="kode-matakuliah">
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
    var CSRF_NAME = '<?= $this->security->get_csrf_token_name() ?>';
    var CSRF_HASH = '<?= $this->security->get_csrf_hash() ?>';
    var URL_MATAKULIAH = '<?= site_url("admin/audit/kpat/get_matakuliah") ?>';

    function loadMatakuliah() {
        var ta = $('#kode-tahun-akademik').val();
        var prodi = $('#kode-program-studi').val();
        var $mk = $('#id-matakuliah');
        $mk.html('<option value="">Memuat...</option>');
        $('#kode-matakuliah').val('');

        if (!ta || !prodi) {
            $mk.html('<option value="" selected disabled>Pilih Tahun Akademik dan Program Studi dahulu</option>');
            return;
        }

        var postData = {};
        postData[CSRF_NAME] = CSRF_HASH;
        postData.tahun_akademik = ta;
        postData.kode_program_studi = prodi;

        $.post(URL_MATAKULIAH, postData, function (rows) {
            var html = '<option value="" selected disabled>Pilih Matakuliah</option>';
            if (!rows || rows.length === 0) {
                html = '<option value="" selected disabled>Tidak ada matakuliah KPAT untuk filter ini</option>';
            } else {
                for (var i = 0; i < rows.length; i++) {
                    html += '<option value="' + rows[i].id_matakuliah + '" data-kode="' + rows[i].kode_matakuliah + '">' + rows[i].kode_matakuliah + ' - ' + rows[i].nama_matakuliah + '</option>';
                }
            }
            $mk.html(html);
            if ($mk.hasClass('select2')) {
                $mk.trigger('change.select2');
            }
        }, 'json');
    }

    $('#kode-program-studi').on('change', loadMatakuliah);
    $('#kode-tahun-akademik').on('change', loadMatakuliah);
    $('#id-matakuliah').on('change', function () {
        $('#kode-matakuliah').val($('#id-matakuliah option:selected').data('kode') || '');
    });

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
