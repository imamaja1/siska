<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-body">
                <div class="badge bg-aqua"><?= e($kode_matakuliah) . ' - ' . e($nama_matakuliah) ?></div>
<!--                <a href="#" onclick="window.top.close()" class="btn btn-success btn-xs pull-right"><i-->
                <!--                            class="fa fa-arrow-left"></i> Kembali</a>-->
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12col-xs-12" style="padding: 0;">
        <?php foreach ($nama_kelas as $row) : ?>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="info-box bg-aqua">
                    <span class="info-box-icon"><?= e($row->nama_kelas) ?></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Mahasiswa</span><a href="#"
                                                                        onclick="hapus_kelas('<?= e($row->kelas_id) ?>')"
                                                                       class="pull-right" style="color: white"
                                                                       title="Hapus"><i class="fa fa-times"></i></a>
                        <span class="info-box-number"><?= $row->jml ?></span>
                        <div class="progress">
                            <div class="progress-bar" style="width:<?= $row->jml * (100 / 50) ?>%"></div>
                        </div>
                        <span class="progress-description">
                            <!--TODO::ubah disini untuk export data kuisioner mahasiswa-->
            <form action="<?= site_url('admin/kuisioner/kuisioner/filter') ?>" method="post" target="_blank">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                                <a href="#" onclick="lihat('<?= e($row->kelas_id) ?>')" style="color: #ffffff">Lihat Data <i
                                        class="fa fa-arrow-circle-right"></i></a>
                                <input type="hidden" name="kode_tahun_akademik" id="kode_tahun_akademik"
                                       value="<?= e($_SESSION['ta_sess']) ?>">
                                <input type="hidden" name="id_matakuliah" id="matakuliah"
                                       value="<?= e($_SESSION['id_matakuliah_sess']) ?>">
                                <input type="hidden" name="kelas_id" value="<?= e($row->kelas_id) ?>">
                                <button type="submit"
                                        class="btn btn-success btn-xs pull-right"
                                        target="_blank" title="Cetak Data Quisioner PBM"><i
                                        class="fa fa-file-excel-o"></i>
                                </button>
                            </form>
                        </span>
                    </div>
                </div>
            </div>
            <?php $last_id = $row->nama_kelas_id ?>
        <?php endforeach; ?>
        <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center">
            <a href="#" onclick="$('#modal-tambah-kelas').modal('show')" class="btn btn-info btn-lg"
               style="border-radius: 50px" title="Tambah Kelas"><i class="fa fa-plus"></i></a>
            <a href="#" onclick="tidak_ada_kelas()" class="btn btn-danger btn-lg"
               style="border-radius: 50px" title="Tambah Mahsiswa"><i class="fa fa-users"></i></a>
        </div>
    </div>
</div>
<!--modal lihat data mahasiswa-->
<div class="modal fade" id="modal-mahasiswa" style="display: none;">
    <div class="modal-dialog modal-lg" id="landing-modal-mahasiswa">

    </div>
</div>
<!--modal tambah kelas-->
<div class="modal fade" id="modal-tambah-kelas">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="form-tambah-kelas" action="<?= site_url('admin/kuisioner/kelas/add_kelas') ?>" method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Tambah Kelas</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <select name="nama_kelas_id" class="form-control" required>
                            <option value="" selected disabled>Pilih Kelas</option>
                            <?php foreach ($kelas as $row) : ?>
                                <option value="<?= e($row->nama_kelas_id) ?>"><?= e($row->nama_kelas) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Add</button>
                </div>
            </form>
        </div>

    </div>
</div>
<script>
    function lihat(kelas_id) {
        super_kelas_id = kelas_id;
        $.ajax({
            url: "<?= site_url('admin/kuisioner/kelas/data_mahasiswa') ?>/" + kelas_id + "/" + kode_tahun_akademik_mega,
            type: "get",
            beforeSend: function () {
                $("#landing-modal-mahasiswa").html(loader);
            },
            success: function (data) {
                $('#landing-modal-mahasiswa').html(data);
                $("#modal-mahasiswa").modal('show');
            },
            error: function () {
                console.log('gagal');
            }
        });
    }

    function hapus_kelas(kelas_id) {
        var url = "<?= site_url('admin/kuisioner/kelas/hapus_kelas') ?>/" + kelas_id;
        swal({
            title: '',
            text: "Anda yaikin menghapus kelas ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            $.ajax({
                url: url,
                beforeSend: function () {
                    $("#landing-kelas").html(default_landing);
                },
                success: function () {
                    // makul(kode_program_studi);
                    kelas(matakuliah_id);
                }
            })
        });
    }

    $("#form-tambah-kelas").submit(function (e) {
        e.preventDefault();
        var url = $(this).prop('action');
        var data = $(this).serialize();
        $.ajax({
            url: url,
            data: data,
            type: 'post',
            success: function (res) {
                $("#modal-tambah-kelas").modal('hide');
                // $(".modal-backdrop").remove();
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                var obj = JSON.parse(res);
                if (!obj.status) {
                    swal('Gaga', 'Data gagal ditambah', 'error');
                }
                kelas(matakuliah_id);
            }
        })
    })

    $('#modal-mahasiswa').on('hidden.bs.modal', function () {
        kelas(matakuliah_id)
    })

    function tidak_ada_kelas() {
        $.ajax({
            url: "<?= site_url('admin/kuisioner/kelas/tidak_kelas') ?>/" + matakuliah_id + "/" + kode_tahun_akademik_mega,
            type: "get",
            beforeSend: function () {
                $("#landing-modal-mahasiswa").html(loader);
            },
            success: function (data) {
                $('#landing-modal-mahasiswa').html(data);
                $("#modal-mahasiswa").modal('show');
            },
            error: function () {
                console.log('gagal');
            }
        });
    }
</script>