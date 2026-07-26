<style>
    .btn-ta {
        font-size: 16pt;
        font-weight: bold;
        padding: 5px 15px 5px 15px;
        background-color: #3c8dbc;
        color: white;
        margin-bottom: 5px;
        border-radius: 15px;
        box-shadow: 0px 0px 13px -3px rgba(0, 0, 0, 0.75);
        -webkit-box-shadow: 0px 0px 13px -3px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 0px 0px 13px -3px rgba(0, 0, 0, 0.75);
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="pull-right">
            <div class="btn-ta">
                TA. <?= get_cookie('tahun_akademik') ?> <?= get_cookie('semester') == '1' ? "GANJIL" : "GENAP" ?> |
                <a href="#" onclick="$('#modal-tahun-akademik').modal('toggle')" title="Ganti Tahun Akademik"><i
                            style="color: white" class="fa fa-edit"></i></a>
            </div>
        </div>
    </div>
</div>
<div class="box box-solid flat">
    <div class="box-body">
        <div class="btn-group">
            <button type="button" class="btn btn-success btn-flat"><i class="fa fa-plus-circle"></i> Tambah Status
                Perkuliahan
            </button>
            <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown"
                    aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#" onclick="tambah()"><i style="color: green" class="fa fa-users"></i> Tambah status
                        perkuliahan / tahun angkatan & jurusan</a></li>
                <li><a href="#" onclick="tambah1()"><i style="color: green" class="fa fa-user"></i> Tambah status
                        perkuliahan / mahasiswa berdasarkan NIM</a></li>
            </ul>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-danger btn-flat"><i class="fa fa-file-text"></i> Rekap</button>
            <button type="button" class="btn btn-danger btn-flat dropdown-toggle" data-toggle="dropdown"
                    aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="<?= site_url('admin/keuangan/status_perkuliahan/rekap') ?>"><i class="fa fa-user"></i>
                        Rekap status perkuliahan</a></li>
                <li><a href="<?= site_url('admin/keuangan/status_perkuliahan/rekap_sks') ?>"><i class="fa fa-file"></i>
                        Rekap SKS</a></li>
                <li><a href="<?= site_url('admin/keuangan/pembayaran') ?>"><i class="fa fa-money"></i> Rekap Pembayaran</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="box box-solid flat" id="filter">
    <div class="box-body">
        <form class="form-horizontal" action="<?= site_url('admin/keuangan/Status_perkuliahan/filter') ?>"
              method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-2">Tahun Akademik</label>
                <div class="col-sm-3">
                    <select required class="form-control select2" name="tahun_akademik">
                        <option value="">Pilih</option>
                        <?php foreach ($tahun_akademik

                        as $row) { ?>
                        <option value="<?= e($row->kode_tahun_akademik) ?>"><?php echo e($row->tahun_akademik); ?>
                            <?= $row->semester == 0 ? "- Genap" : "- Ganjil" ?>
                            <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Angkatan</label>
                <div class="col-sm-3">
                    <select required class="form-control" name="angkatan">
                        <option value="" selected disabled>Pilih</option>
                        <?php foreach ($tahun_angkatan as $row) { ?>
                            <option value="<?= e(substr($row->tahun_akademik, 2, 2)) ?>"><?= e(substr($row->tahun_akademik, 0, 4)) ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Jurusan</label>
                <div class="col-sm-3">
                    <select required class="form-control" name="prodi">
                        <option value="" selected disabled>Pilih</option>
                        <?php foreach ($nama_jurusan as $row) { ?>
                            <option value="<?= e($row->kode_program_studi) ?>"><?= e($row->singkatan_program_studi) ?> -
                                (<?= e($row->nama_program_studi) ?>)
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2"></div>
                <div class="col-sm-3">
                    <button class="btn btn-primary flat" type="sumbit" name="sumbit"><i class="fa fa-gear"></i> Proses
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="box box-solid flat" id="filter1" style="display: none;">
    <div class="box-body">
        <a href="#" onclick="tutup()" class="pull-right text-danger"><i class="fa fa-remove"></i></a>
        <form class="form-horizontal" method="POST"
              action="<?= site_url('admin/keuangan/Status_perkuliahan/filter1') ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-2">Angkatan</label>
                <div class="col-sm-3">
                    <select required class="form-control" name="angkatan">
                        <option value="" selected selected>Pilih</option>
                        <?php foreach ($tahun_angkatan as $row) { ?>
                            <option value="<?= e(substr($row->tahun_akademik, 2, 2)) ?>"><?= e(substr($row->tahun_akademik, 0, 4)) ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Jurusan</label>
                <div class="col-sm-3">
                    <select required class="form-control" name="prodi">
                        <option value="" selected disabled>Pilih</option>
                        <?php foreach ($nama_jurusan as $row) { ?>
                            <option value="<?= e($row->kode_program_studi) ?>"><?= e($row->singkatan_program_studi) ?>
                                - <?= e($row->nama_program_studi) ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2"></div>
                <div class="col-sm-3">
                    <button class="btn btn-primary flat" type="sumbit" name="sumbit"><i class="fa fa-gear"></i> Proses
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="box box-solid flat" style="display: none;" id="filter-nim">
    <div class="box-body">
        <a href="#" onclick="tutup1()" class="pull-right text-danger"><i class="fa fa-remove"></i></a>
        <div class="col-xs-4">
            <div class="input-group ">
                <input maxlength="11" type="text" autocomplete="off" id="search-box" name="nim"
                       placeholder="Masukan NIM" class="form-control"/>
                <span class="input-group-btn">
                  <button class="btn btn-primary flat" onclick="cek()"><i class="fa fa-search"></i></button>
                </span>
            </div>
            <div id="suggesstion-box"></div>
        </div>
    </div>
</div>
<div class="box box-solid flat" id="find-box" style="display: none;">
    <div class="box-body">
        <table class="table demo-table">
            <thead>
            <tr>
                <th style="text-align: center;">NIM</th>
                <th style="text-align: center;">NAMA MAHASISWA</th>
                <th style="text-align: center;">Status Perkuliahan</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td id="find-nim" style="text-align: center;"></td>
                <td id="find-nama-mahasiswa" style="text-align: center;"></td>
                <td>
                    <form method="POST">
                        <input type="radio" checked name="status-radio" value="A" id="radio-status">
                        <label>Aktif</label>&nbsp;
                        <input type="radio" name="status-radio" value="C" id="radio-status">
                        <label>Cuti</label>&nbsp;
                        <input type="radio" name="status-radio" value="B" id="radio-status">
                        <label>Berhenti</label>&nbsp;
                        <input type="radio" name="status-radio" value="T" id="radio-status">
                        <label>Tanpa Keterangan</label>&nbsp;
                        <input type="radio" name="status-radio" value="P" id="radio-status">
                        <label>Pindah</label>
                        <input type="radio" name="status-radio" value="L" id="radio-status">
                        <label>Lulus</label>
                    </form>
                </td>
            </tr>
            </tbody>
        </table>
        <br>
        <button class="btn btn-success btn-sm flat" onclick="simpan_status()"><i class="fa fa-check-circle"></i> Simpan
        </button>
    </div>
</div>

<div class="modal fade" id="modal-tahun-akademik" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Ganti Tahun Akademik</h4>
            </div>
            <form action="<?= site_url('admin/keuangan/status_perkuliahan/ganti_tahun_akademik') ?>" method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Tahun Akademik <span class="text-danger">*</span></label>
                        <select name="kode_tahun_akademik" class="form-control">
                            <?php foreach ($tahun_akademik as $row ):?>
                                <option <?= get_cookie('kode_tahun_akademik') ==  $row->kode_tahun_akademik ? 'selected' : '' ?> value="<?= e($row->kode_tahun_akademik) ?>"><?= e($row->tahun_akademik) ?> <?= $row->semester == '0' ? "GENAP" : "GANJIL" ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    function tambah() {
        $('#filter').hide();
        $('#filter-nim').hide();
        $('#find-box').hide();
        $('#filter1').show();
    }

    function tambah1() {
        $('#filter').hide();
        $('#find-box').hide();
        $('#filter-nim').show();
        $('#filter1').hide();
    }

    function tutup() {
        $('#filter1').hide();
        $('#filter-nim').hide();
        $('#find-box').hide();
        $('#filter').show();
    }

    function tutup1() {
        // body...
        $('#filter1').hide();
        $('#filter-nim').hide();
        $('#find-box').hide();
        $('#filter').show();
    }

    function cek() {
        var nim = $('#search-box').val();
        $.ajax({
            url: "<?= site_url('admin/keuangan/Status_perkuliahan/cek_exis') ?>",
            type: "POST",
            data: "&nim=" + nim,
            cache: false,
            success: function (data) {
                var obj = JSON.parse(data);
                // if (data == 0) {
                if (obj.status == 0) {
                    // console.log(obj.data);
                    if (obj.data !== null) {
                        $('#find-box').show();
                        $('#find-nim').html(nim);
                        $('#find-nama-mahasiswa').html(obj.data.nama_mahasiswa);
                        $('#validasi-nim').hide();
                    } else {
                        $('#validasi-nim').html("<i class='fa fa-times-circle'></i> Inputan harus nim");
                        $('#validasi-nim').show();
                    }
                } else {
                    swal(
                        'Oops...',
                        'Data Sudah Ada',
                        'error'
                    )
                }
            }
        });

        $('#filter').hide();
        $('#filter-nim').show();
        $('#filter1').hide();
    }

    function simpan_status() {
        var nim = $('#find-nim').html();
        var status = $('input[name=status-radio]:checked').val();
        if (!status) {
            swal('Info', 'Silahkan pilih salah satu status perkuliahan', 'info');
        } else {
            $.ajax({
                url: "<?= site_url('admin/keuangan/Status_perkuliahan/simpan_status') ?>",
                data: "nim=" + nim + "&status=" + status,
                type: "POST",
                cache: false,
                success: function (data) {
                    if (data == 1) {
                        swal("Success", "Data berhasil disimpan", "success");
                        $('#find-box').hide();
                        $('#nim').val(null);
                        //$('#select2-nim-container').html("Pilih");
                    } else {
                        swal("Gagal", "Data gagal disimpan", "error");
                        $('#find-box').hide();
                        $('#nim').val(null);
                        //$('#select2-nim-container').html("Pilih");
                    }
                }

            });
        }

    }

</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#search-box").keyup(function () {
            $.ajax({
                type: "POST",
                url: "<?= site_url('admin/keuangan/status_perkuliahan/autocomplate') ?>",
                data: 'keyword=' + $(this).val(),
                beforeSend: function () {
                    $("#search-box").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function (data) {
                    $("#suggesstion-box").show();
                    $("#suggesstion-box").html(data);
                    $("#search-box").css("background", "#FFF");
                }
            });
        });
    });

    //To select country name
    function selectNim(val) {
        $("#search-box").val(val);
        $("#suggesstion-box").hide();
    }

    $('form select').on('change invalid', function () {
        var textfield = $(this).get(0);

        // hapus dulu pesan yang sudah ada
        textfield.setCustomValidity('');

        if (!textfield.validity.valid) {
            textfield.setCustomValidity('Tidak boleh kosong!');
        }
    });
</script>