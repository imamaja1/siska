<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-body">
                <div class="badge bg-aqua"  style="width: 100%;padding-top:10px; padding-bottom: 10px"><?= e($kode_matakuliah . ' - ' . $nama_matakuliah) ?></div>
                <!--                <a href="#" onclick="window.top.close()" class="btn btn-success btn-xs pull-right"><i-->
                <!--                            class="fa fa-arrow-left"></i> Kembali</a>-->
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12col-xs-12" style="padding: 0;">
        <?php if (!count($nama_kelas)): ?>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="badge" style="width: 100%; background-color: brown; padding-top:15px; padding-bottom: 15px;">Tidak Ada Kelas</div>
            </div>
        <?php endif; ?>
        <?php foreach ($nama_kelas as $row): ?>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="info-box bg-aqua">
                    <span class="info-box-icon"><?= e($row->nama_kelas) ?></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Mahasiswa</span>
                        <span class="info-box-number"><?= e($row->jml) ?></span>
                        <div class="progress">
                            <div class="progress-bar" style="width:<?= e($row->jml * (100 / 50)) ?>%"></div>
                        </div>
                        <span class="progress-description">
                            <!--TODO::ubah disini untuk export data kuisioner mahasiswa-->
                            <form action="<?= site_url('admin/kuisioner/kuisioner/filter') ?>" method="post"
                                target="_blank">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                                <a href="#" onclick="lihat('<?= e($row->kelas_id) ?>')" style="color: #ffffff">Lihat Data <i
                                        class="fa fa-arrow-circle-right"></i></a>
                                <input type="hidden" name="kode_tahun_akademik" id="kode_tahun_akademik"
                                    value="<?= e($_SESSION['ta_sess']) ?>">
                                <input type="hidden" name="id_matakuliah" id="matakuliah"
                                    value="<?= e($_SESSION['id_matakuliah_sess']) ?>">
                                <input type="hidden" name="kelas_id" value="<?= e($row->kelas_id) ?>">
                            </form>
                        </span>
                    </div>
                </div>
            </div>
            <?php $last_id = $row->nama_kelas_id ?>
        <?php endforeach; ?>
    </div>
</div>
<!--modal lihat data mahasiswa-->
<div class="modal fade" id="modal-mahasiswa" style="display: none;">
    <div class="modal-dialog modal-lg" id="landing-modal-mahasiswa">

    </div>
</div>
<!--modal tambah kelas-->

<script>
    function lihat(kelas_id) {
        super_kelas_id = kelas_id
        $.ajax({
            url: "<?= site_url('dosen/kaprodi/kelas/data_mahasiswa') ?>/" + kelas_id + "/" + <?= e($tahun) ?>,
            type: "get",
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