<?php if (count($data_non_aktif) > 0): ?>
    <div class="box box-solid flat">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table demo-table" id="example1">
                    <thead>
                        <tr>
                            <th id="th">No.</th>
                            <th id="th">NIM</th>
                            <th id="th">Nama Mahasiswa</th>
                            <th id="th">Semester</th>
                            <th id="th">No. HP</th>
                            <th id="th">Pembayaran SPP</th>
                            <th id="th">Pembayaran SKS</th>
                            <th id="th">Data Akademik</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($data_non_aktif as $row):
                            ?>
                            <tr>
                                <td align="center"><?= $i++ ?>.</td>
                                <td align="center"><?= $row->nim ?></td>
                                <td><?= $row->nama_mahasiswa ?> </td>
                                <td align="center"><?= $row->semester ?> </td>
                                <td><?= $row->telepon ?> </td>
                                <td align="center">

                                    <?php if (($row->pembayaran_spp == "0") || empty($row->pembayaran_spp) || is_null($row->pembayaran_spp)): ?>
                                        <span class='badge bg-red'>Belum  <i class='fa fa-times'></i></span>
                                    <?php else: ?>
                                        <span class='badge bg-green'>Sudah <i class='fa fa-check'></i></span>
                                    <?php endif; ?>
                                        
                                </td>
                                <td align="center">
                                    <?php if (($row->pembayaran_sks == "0") || empty($row->pembayaran_sks) || is_null($row->pembayaran_sks)): ?>
                                        <span class='badge bg-red'>Belum  <i class='fa fa-times'></i></span>
                                    <?php else: ?>
                                        <span class='badge bg-green'>Sudah <i class='fa fa-check'></i></span>
                                    <?php endif; ?>

                                </td>
                                <td align="center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-sm">Detail</button>
                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle"
                                                data-toggle="dropdown" aria-expanded="false">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#"
                                                   onclick="halaman('<?= site_url('dosen/Konsultasi_perwalian/biodata_mahasiswa/' . $row->nim) ?>');">
                                                    <i class="fa fa-user"></i>Biodata</a></li>
                                            <li><a href="#"
                                                   onclick="halaman('<?= site_url('dosen/Konsultasi_perwalian/krs_mahasiswa/' . $row->nim) ?>');"><i
                                                        class="fa fa-file-text"></i> KRS</a></li>
                                            <li><a href="#"
                                                   onclick="halaman('<?= site_url('dosen/Konsultasi_perwalian/khs_mahasiswa/' . $row->nim) ?>');"><i
                                                        class="fa fa-file-text"></i> KHS</a></li>
                                            <li><a href="#"
                                                   onclick="halaman('<?= site_url('dosen/Konsultasi_perwalian/petikan_nilai_mahasiswa/' . $row->nim) ?>');"><i
                                                        class="fa fa-clipboard"></i>Nilai</a></li>
                                        </ul>
                                    </div>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
<?php else: ?>
    <div class="callout callout-info flat">
        <p>Tidak ada data perwalian.</p>
    </div>
<?php endif; ?>

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

</script>
