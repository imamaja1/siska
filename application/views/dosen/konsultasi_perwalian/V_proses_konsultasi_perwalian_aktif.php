<?php if (count($data_aktif) > 0): ?>
    <div class="box box-solid flat">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table demo-table" id="example1">
                    <thead>
                        <tr>
                            <th id="th">No.</th>
                            <th id="th">NIM</th>
                            <th id="th">Nama Mahasiswa</th>
                            <th id="th">No. HP</th>
                            <th id="th">Perwalian</th>
                            <th id="th">Pengisian KRS</th>
                            <th id="th">Data Akademik</th>
                            <th id="th">Konsultasi Khusus</th>
                            <th id="th">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($data_aktif as $row) :
                            ?>
                            <tr>
                                <td align="center"><?= $i++ ?>.</td>
                                <td align="center" id="nim-<?= $row->kode_konsultasi_perwalian ?>"><?= $row->nim ?></td>
                                <td><?= $row->nama_mahasiswa ?> </td>
                                <td><?= $row->telepon ?> </td>
                                <td align="left"><?php
                                    if ($this->session->userdata['kode_dosen'] != $row->kode_dosen) {
                                        echo $row->nama_dosen;
                                    }
                                    ?>
                                </td>
                                <td align="center"><?= $row->kode_krs == '' ? "<span class='badge bg-red'>Belum  <i class='fa fa-times'></i></span>" : "<span class='badge bg-green'>Sudah <i class='fa fa-check'></i></span>" ?></td>
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
                                <td align="center">
                                    <a href="#" class="btn btn-xs btn-primary flat"
                                       onclick="halaman('<?= site_url('dosen/Konsultasi_perwalian/konsultasi_krs/' . $row->nim) ?>');"><i
                                            class="fa fa-file-text"></i> Aktif KRS</a>
                                <!--<a href="#" class="btn btn-xs btn-primary flat" onclick="halaman('<?= site_url('dosen/Konsultasi_perwalian/konsultasi_perwalian/' . $row->nim) ?>');"><i class="fa fa-user-secret"></i> Perwalian</a>-->
                                  <a href="#"  onclick="view('<?= $row->nim ?>')" class="btn btn-success btn-xs flat"><i class="fa fa-user-secret" aria-hidden="true"></i>History</a>&nbsp;
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($row->status_cetak == "A") : ?>
                                        <a href="#!"
                                           onclick="konf_status('<?= site_url('dosen/konsultasi_perwalian/nonaktif/' . $row->kode_konsultasi_perwalian) ?>')"
                                           class="btn btn-success btn-xs flat"><i class="fa fa-check-circle-o"></i>
                                            aktif</a>
                                    <?php else: ?>
                                        <a href="#!"
                                           onclick="konf_status('<?= site_url('dosen/konsultasi_perwalian/aktif/' . $row->kode_konsultasi_perwalian) ?>')"
                                           class="btn btn-danger btn-xs flat"><i class="fa fa-times-circle"></i>
                                            aktifkan</a>
                                    <?php endif; ?>

                                </td>
                            </tr>
                        <div hidden id="kode_dosen-<?= $row->kode_konsultasi_perwalian ?>"><?= $row->kode_dosen ?></div>
                        <div hidden
                             id="nama_mahasiswa-<?= $row->kode_konsultasi_perwalian ?>"><?= $row->nama_mahasiswa ?></div>
                        <div hidden id="nama_dosen-<?= $row->kode_konsultasi_perwalian ?>"><?= $row->nama_dosen ?></div>

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

<!--Modal Konsultasi-->
<div class="modal fade" id="modal-view" style="display: none;">
    <div class="modal-dialog" style="max-width: 80%; width: 100%">
        <div class="modal-content" id="landing-modal-view" style="border-radius: 10px">
        </div>
    </div>
</div>

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
	function view(id) {
        var url = "<?= site_url('dosen/Konsultasi_perwalian/detail') ?>/"+id;
        $.ajax({
            url : url,
            success : function (res) {
                $('#landing-modal-view').html(res);
                $("#modal-view").modal('show');
            },
        })
    }

</script>

