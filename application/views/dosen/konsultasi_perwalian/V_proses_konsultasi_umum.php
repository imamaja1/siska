
<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('dosen/konsultasi_perwalian'); ?>" class="btn btn-xs flat btn-success"><i
                class="fa fa-arrow-left"></i> Kembali</a>
        <a href="<?= site_url('dosen/konsultasi_perwalian/pencarian_konsultasi_umum') ?>" class="btn btn-xs flat btn-info"><i class="fa fa-clone"></i> Konsultasi Umum</a>
    </div>
</div>
<?php if (count($data) > 0): ?>
    <div class="box box-solid flat">
        <div class="box-body">
            <div class="table-responsive">

                <table class="table demo-table">
                    <thead>
                        <tr>
                            <th id="th">No.</th>
                            <th id="th">NIM</th>
                            <th id="th">Nama Mahasiswa</th>
                            <th id="th">Dosen Perwakilan</th>
                            <th id="th">Data Akademik</th>
                            <th id="th">Konsultasi Umum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($data as $row) :
                            ?>
                            <tr>
                                <td align="center"><?= $i++ ?>.</td>
                                <td align="center" id="nim-<?= e($row->kode_konsultasi_perwalian) ?>"><?= e($row->nim) ?></td>
                                <td><?= e($row->nama_mahasiswa) ?></td>
                                <td align="center"><?php
                                    $dosen_perwakilan = $row->kode_dosen_perwakilan;
                                    if (empty($dosen_perwakilan)) {
                                        echo "-";
                                    } else {
                                        echo e($dosen_perwakilan);
                                    }
                                    ?></td>
                                <td align="center">
                                    <a href="#" class=" btn btn-default btn-xs flat" onclick="halaman('<?= site_url('dosen/Konsultasi_perwalian/biodata_mahasiswa/' . $row->nim) ?>');"><i class="fa fa-user"></i>
                                        Biodata</a>
                                    <a class=" btn btn-default btn-xs flat" onclick="halaman('<?= site_url('dosen/Konsultasi_perwalian/krs_mahasiswa/' . $row->nim) ?>');"><i class="fa fa-file-text"></i> KRS</a>&nbsp;
                                    <a class=" btn btn-default btn-xs text-warning flat" onclick="halaman('<?= site_url('dosen/Konsultasi_perwalian/khs_mahasiswa/' . $row->nim) ?>');"><i class="fa fa-file-text"></i> KHS</a>&nbsp;
                                    <a class=" btn btn-default btn-xs text-danger flat" onclick="halaman('<?= site_url('dosen/Konsultasi_perwalian/petikan_nilai_mahasiswa/' . $row->nim) ?>');"><i class="fa fa-clipboard"></i>Nilai</a>
                                </td>
                                <td align="center">
                                    <a href="#" class="btn btn-xs btn-primary flat" onclick="halaman('<?= site_url('dosen/Konsultasi_perwalian/konsultasi_umum/' . $row->nim) ?>');"><i class="fa fa-file-text"></i> Detail Konsultasi</a> 
                                </td>

                            </tr>
                        <div hidden id="kode_dosen-<?= e($row->kode_konsultasi_perwalian) ?>"><?= e($row->kode_dosen) ?></div>
                        <div hidden id="nama_mahasiswa-<?= e($row->kode_konsultasi_perwalian) ?>"><?= e($row->nama_mahasiswa) ?></div>
                        <div hidden id="nama_dosen-<?= e($row->kode_konsultasi_perwalian) ?>"><?= e($row->nama_dosen) ?></div>

                    <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
<?php else: ?>
    <div class="callout callout-info flat">
        <p>Tidak ada data yang ditemukan untuk angkatan dan jurusan tersebut</p>
    </div>
<?php endif; ?>
<!--Modal Konsultasi-->

<?= $this->session->flashdata('message') ?>
<script>


    function halaman(url) {
        var win = window.open(url, 'DUMET School', "height=600, width=1000, scrollbars=yes");
        win.focus();
    }

    function konsultasi() {
        $('#modal-konsultasi').modal('toggle');
    }

    function aktifkan(id) {
        var url = window.location.href;

        $.ajax({
            url: "<?= site_url('dosen/Konsultasi_perwalian/status_cetak') ?>",
            type: "POST",
            data: "status=A&param=" + id,
            success: function (data) {
                window.location.href = url;
            },
            error: function () {
                alert('gagal');
            },
        });
    }

    function nonaktif(id) {
        var url = window.location.href;
        $.ajax({
            url: "<?= site_url('dosen/Konsultasi_perwalian/status_cetak') ?>",
            type: "POST",
            data: "status=N&param=" + id,
            success: function (data) {
                window.location.href = url;
            },
            error: function () {
                alert('gagal');
            },
        });
    }

    function konf_status(url) {
        swal({
            title: '',
            text: "Anda yakin ingin mengubah status?",
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

