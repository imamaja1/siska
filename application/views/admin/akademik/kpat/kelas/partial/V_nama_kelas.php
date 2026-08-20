<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-body">
                <div class="badge bg-aqua"><?= e($kode_matakuliah . ' - ' . $nama_matakuliah) ?></div>
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
                        <span class="info-box-number"><?= e($row->jml) ?></span>
                        <div class="progress">
                            <div class="progress-bar" style="width:<?= e($row->jml * (100 / 50)) ?>%"></div>
                        </div>
                        <span class="progress-description">
                            <a href="#" onclick="lihat('<?= e($row->kelas_id) ?>')" style="color: #ffffff">Lihat Data <i
                                class="fa fa-arrow-circle-right"></i></a>
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
            <form id="form-tambah-kelas" action="<?= site_url('admin/akademik/kpat/kelas/add_kelas') ?>" method="post">
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
                          	<input name="ta" value="<?= e($ta) ?>"  hidden />
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
            url: "<?= site_url('admin/akademik/kpat/kelas/data_mahasiswa') ?>/" + kelas_id + "/" + kode_tahun_akademik_mega,
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
        var url = "<?= site_url('admin/akademik/kpat/kelas/hapus_kelas') ?>/" + kelas_id;
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
                    $("#landing-kelas").html(loader);
                },
                success: function () {
                    kelas_kpat()
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
              	console.log(obj);
                if (!obj.status) {
                    swal('Gagal', 'Data gagal ditambah', 'error');
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
            url: "<?= site_url('admin/akademik/kpat/kelas/tidak_kelas') ?>/" + matakuliah_id + "/" + kode_tahun_akademik_mega,
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