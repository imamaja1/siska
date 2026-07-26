<?php if ($this->session->flashdata('info')) : ?>
    <?= $this->session->flashdata('info') ?>
<?php endif; ?>
<div class="box box-solid">
    <div class="box-body">
        <?php if ($exis_kelas <= 0) : ?>
            <div id="loading">
                <a id="genrate" href="<?= site_url('admin/kuisioner/kelas/generate_kelas') ?>"
                   class="btn btn-primary flat"><i class="fa fa-gear"></i> Generate Kelas</a>
                <a href="<?= site_url('admin/kuisioner/kelas') ?>" class="btn btn-success btn-sm flat pull-right"><i
                            class="fa fa-arrow-left"></i> Kembali</a>
            </div>
        <?php else: ?>
            <div class="badge bg-aqua"><?= $kode_matakuliah . ' - ' . $nama_matakuliah ?></div>
            <a href="<?= site_url('admin/kuisioner/kelas') ?>" class="btn btn-success btn-sm flat pull-right"><i
                        class="fa fa-arrow-left"></i> Kembali</a>
        <?php endif; ?>
    </div>
</div>
<?php if ($exis_kelas > 0) : ?>
    <div class="row">
        <div class="col-md-3 col-sm-4 col-xs-4" style="padding: 0;">
            <?php foreach ($nama_kelas as $row) : ?>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="info-box bg-aqua">
                        <span class="info-box-icon"><?= $row->nama_kelas ?></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Mahasiswa</span><a href="#" onclick="hapus_kelas('<?= $row->kelas_id ?>')" class="pull-right" style="color: white" title="Hapus"><i class="fa fa-times"></i></a>
                            <span class="info-box-number"><?= $row->jml ?></span>
                            <div class="progress">
                                <div class="progress-bar" style="width:<?= $row->jml * (100 / 50) ?>%"></div>
                            </div>
                            <span class="progress-description">
                    <a href="#" onclick="lihat('<?= $row->kelas_id ?>')" style="color: #ffffff">Lihat Data <i
                                class="fa fa-arrow-circle-right"></i></a>
                  </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <?php $last_id = $row->nama_kelas_id ?>
            <?php endforeach; ?>
            <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center">
                <a href="<?= site_url('admin/kuisioner/kelas/add_kelas/'.$last_id) ?>" class="btn btn-info btn-lg" style="border-radius: 50px" title="Tambah Kelas"><i class="fa fa-plus"></i></a>
            </div>
        </div>
        <div class="col-md-9 col-sm-8 col-xs-8" id="data-mahasiswa">
        </div>
    </div>

<?php endif; ?>
<script>
    $("#genrate").on('click', function () {
        var button = $("#loading");
        button.html("<button class='btn btn-default flat' disabled>Permintaan sedang diprosess...</button>");
        console.log('bisa');
    });

    function lihat(kelas_id) {
        $('#ct').toggleClass('bg-navy');
        $.ajax({
            url: "<?= site_url('admin/kuisioner/kelas/data_mahasiswa') ?>/" + kelas_id,
            type: "get",
            success: function (data) {
                $("#data-mahasiswa").css('display', 'none');
//                $("#data-mahasiswa").fadeIn("slow");
                $("#data-mahasiswa").animate({
                    width : "show",
                });
                $('#data-mahasiswa').html(data);
            },
            error: function () {
                console.log('gagal');
            }
        });
    }

    function hapus_kelas(kelas_id) {
        var url = "<?= site_url('admin/kuisioner/kelas/hapus_kelas') ?>/"+kelas_id;
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
            window.location.href = url;
        });
    }
</script>

