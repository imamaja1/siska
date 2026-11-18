<div class="row">
    <div class="col-md-12">
        <div class="box box-primary flat">
            <div class="box-header with-border">
                <h4><i class="fa fa-users"></i> Audit Mahasiswa: Neo Feeder vs SIAKAD</h4>
                <p class="text-muted" style="margin-bottom:0;">
                    Bandingkan data NIM dan Mahasiswa per Program Studi &amp; Angkatan antara Neo Feeder dengan Database SIAKAD Lokal, serta telusuri biodata lengkap per NIM.
                </p>
            </div>
            <div class="box-body">
                <!-- Tab Pilihan: Berdasarkan Angkatan & Prodi vs Langsung NIM -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#tab-filter-prodi" aria-controls="tab-filter-prodi" role="tab" data-toggle="tab">
                            <i class="fa fa-filter"></i> Filter Angkatan &amp; Program Studi
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#tab-filter-nim" aria-controls="tab-filter-nim" role="tab" data-toggle="tab">
                            <i class="fa fa-search"></i> Pencarian Cepat per NIM
                        </a>
                    </li>
                </ul>

                <div class="tab-content" style="padding-top: 15px;">
                    <!-- Panel 1: Filter Angkatan & Prodi -->
                    <div role="tabpanel" class="tab-pane active" id="tab-filter-prodi">
                        <form id="form-filter" class="form-horizontal" method="post" action="<?= site_url('admin/audit/mahasiswa/hasil') ?>">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Angkatan <span class="text-danger">*</span></label>
                                <div class="col-sm-4 col-xs-12">
                                    <select required name="angkatan" id="angkatan" class="form-control select2">
                                        <option value="" selected disabled>Pilih Angkatan</option>
                                        <?php 
                                        $seen_angkatan = [];
                                        foreach ($tahun_akademik as $row) : 
                                            $thn = substr($row->tahun_akademik, 0, 4);
                                            if (in_array($thn, $seen_angkatan)) continue;
                                            $seen_angkatan[] = $thn;
                                        ?>
                                            <option value="<?= e($thn) ?>">Angkatan <?= e($thn) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Program Studi</label>
                                <div class="col-sm-4 col-xs-12">
                                    <select name="kode_program_studi" id="kode-program-studi" class="form-control select2">
                                        <option value="">-- Semua Program Studi --</option>
                                        <?php foreach ($prodi as $row) : ?>
                                            <option value="<?= e($row->kode_program_studi) ?>"><?= e($row->singkatan_program_studi ? $row->singkatan_program_studi . ' - ' : '') ?><?= e($row->nama_program_studi) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-4">
                                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-search"></i> Bandingkan Data</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Panel 2: Pencarian Cepat per NIM -->
                    <div role="tabpanel" class="tab-pane" id="tab-filter-nim">
                        <form id="form-filter-nim" class="form-horizontal" method="post" action="<?= site_url('admin/audit/mahasiswa/detail_nim') ?>">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                            <div class="form-group">
                                <label class="control-label col-sm-2">NIM Mahasiswa <span class="text-danger">*</span></label>
                                <div class="col-sm-4 col-xs-12">
                                    <input required type="text" name="nim" id="input-nim-cepat" class="form-control" placeholder="Contoh: 210101001">
                                    <small class="text-muted">Langsung menampilkan perbandingan biodata lokal SIAKAD vs Feeder.</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-4">
                                    <button type="submit" class="btn btn-info flat"><i class="fa fa-id-card-o"></i> Lihat Biodata Feeder &amp; SIAKAD</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" id="result"></div>

<!-- Modal Detail Biodata Mahasiswa -->
<div class="modal fade" id="modal-biodata" tabindex="-1" role="dialog" aria-labelledby="modalBiodataLabel">
    <div class="modal-dialog modal-lg" role="document" style="width: 85%; max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalBiodataLabel"><i class="fa fa-id-card-o"></i> Detail Biodata Mahasiswa (SIAKAD vs Feeder)</h4>
            </div>
            <div class="modal-body" id="modal-biodata-content">
                <p class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i><br>Memuat data biodata...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    var loading = "<div class='col-md-12 text-center' style='padding:40px;'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''><p class='text-muted' style='margin-top:10px;'><i class='fa fa-spinner fa-spin'></i> Menghubungkan ke Feeder dan menyinkronkan data mahasiswa...</p></div>";
    var csrfName = '<?= $this->security->get_csrf_token_name() ?>';
    var csrfHash = '<?= $this->security->get_csrf_hash() ?>';

    // Submit Filter Angkatan & Prodi
    $("#form-filter").submit(function (e) {
        e.preventDefault();
        $("#result").html(loading);
        $.ajax({
            url: $(this).prop('action'),
            data: $(this).serialize(),
            type: 'post',
            success: function (res) {
                $("#result").html(res);
                if (typeof initDataTablesMahasiswa === 'function') {
                    initDataTablesMahasiswa();
                }
            },
            error: function () {
                $("#result").html("<div class='col-md-12'><div class='callout callout-danger flat'><p><i class='fa fa-times-circle'></i> Terjadi kesalahan koneksi saat memproses data.</p></div></div>");
            }
        });
    });

    // Submit Filter Langsung NIM
    $("#form-filter-nim").submit(function (e) {
        e.preventDefault();
        var nim = $("#input-nim-cepat").val().trim();
        if (!nim) return;
        bukaModalBiodata(nim);
    });

    // Fungsi Buka Modal Detail Biodata per NIM
    function bukaModalBiodata(nim) {
        $("#modal-biodata").modal('show');
        $("#modal-biodata-content").html("<div class='text-center' style='padding:40px;'><i class='fa fa-spinner fa-spin fa-2x'></i><br><span class='text-muted'>Mengambil biodata NIM " + nim + " dari Feeder &amp; SIAKAD...</span></div>");

        var postData = {};
        postData[csrfName] = csrfHash;
        postData['nim'] = nim;

        $.ajax({
            url: '<?= site_url("admin/audit/mahasiswa/detail_nim") ?>',
            data: postData,
            type: 'post',
            success: function (res) {
                $("#modal-biodata-content").html(res);
            },
            error: function () {
                $("#modal-biodata-content").html("<div class='callout callout-danger flat'><p><i class='fa fa-times-circle'></i> Gagal memuat data biodata NIM " + nim + ".</p></div>");
            }
        });
    }
</script>
