<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h4><i class="fa fa-file-excel-o"></i> Download Kuisioner PMB</h4>
            </div>
            <div class="box-body">
                <form id="form-filter" class="form-horizontal" method="get">
                    <div class="form-group">
                        <label class="control-label col-sm-2">Tahun Akademik <span class="text-danger">*</span></label>
                        <div class="col-sm-4 col-xs-12">
                            <select required name="kode_tahun_akademik" id="kode-tahun-akademik" class="form-control select2">
                                <option value="" selected disabled>Pilih</option>
                                <?php foreach ($tahun_akademik as $row) : ?>
                                    <option value="<?= e($row->kode_tahun_akademik) ?>"><?= e($row->tahun_akademik) ?> - <?= $row->semester == 1 ? 'Ganjil' : 'Genap' ?></option>
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
                        <div class="col-sm-offset-2 col-sm-4">
                            <button type="submit" class="btn btn-primary flat"><i class="fa fa-search"></i> Tampilkan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row" id="result">
</div>

<script>
    var loading = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
    var kelasList = [];
    var taGlobal = '';

    function downloadAllLoop() {
        if (kelasList.length === 0) return;
        var idx = 0;
        function next() {
            if (idx >= kelasList.length) return;
            var v = kelasList[idx];
            var url = '<?= site_url("admin/kuisioner/kuisioner/cetak_pmb") ?>/' + v.kelas_id + '?kode_tahun_akademik=' + taGlobal;
            var iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = url;
            document.body.appendChild(iframe);
            idx++;
            setTimeout(next, 1000);
        }
        next();
    }

    $("#form-filter").submit(function (e) {
        e.preventDefault();
        var kode_tahun_akademik = $("#kode-tahun-akademik").val();
        var kode_program_studi = $("#kode-program-studi").val();
        if (!kode_tahun_akademik || !kode_program_studi) return;
        taGlobal = kode_tahun_akademik;
        $("#result").html(loading);
        $.getJSON('<?= site_url("admin/kuisioner/kuisioner/kelas_json") ?>', {
            kode_tahun_akademik: kode_tahun_akademik,
            kode_program_studi: kode_program_studi
        }, function (res) {
            kelasList = res;
            var html = '<div class="col-md-12"><div class="box box-primary"><div class="box-header">';
            if (res.length > 0) {
                html += '<button onclick="downloadAllLoop()" class="btn btn-success flat"><i class="fa fa-download"></i> Download All</button>';
            }
            html += '</div><div class="box-body"><div class="table-responsive"><table class="table table-bordered data-table"><thead><tr><th>No</th><th>Kode MK</th><th>Matakuliah</th><th>Kelas</th><th>Aksi</th></tr></thead><tbody>';
            if (res.length > 0) {
                $.each(res, function (i, v) {
                    var url = '<?= site_url("admin/kuisioner/kuisioner/cetak_pmb") ?>/' + v.kelas_id + '?kode_tahun_akademik=' + kode_tahun_akademik;
                    html += '<tr><td>' + (i + 1) + '</td><td>' + v.kode_matakuliah + '</td><td>' + v.nama_matakuliah + '</td><td>' + v.nama_kelas + '</td><td><a href="' + url + '" class="btn btn-success btn-sm flat"><i class="fa fa-download"></i> Download</a></td></tr>';
                });
            } else {
                html += '<tr><td colspan="5" class="text-center">Tidak ada data kelas</td></tr>';
            }
            html += '</tbody></table></div></div></div></div>';
            $("#result").html(html);
            if ($.fn.DataTable && $.fn.DataTable.isDataTable('.data-table')) {
                $('.data-table').DataTable().destroy();
            }
            if ($.fn.DataTable) {
                $('.data-table').DataTable({
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
                });
            }
        });
    });
</script>