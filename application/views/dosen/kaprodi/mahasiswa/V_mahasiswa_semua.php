<div class="box box-solid flat">
    <div class="box-header">
        <button data-toggle="modal" data-target="#modal-semua" class="btn btn-info btn-xs"><i class="fa fa-table"></i> Semua</button>
        <button data-toggle="modal" data-target="#modal-aktif" class="btn btn-success btn-xs"><i class="fa fa-table"></i> Aktif</button>
        <button data-toggle="modal" data-target="#modal-tidak-aktif" class="btn btn-danger btn-xs"><i class="fa fa-table"></i> Tidak Aktif</button>
        <button data-toggle="modal" data-target="#modal-tabel-perangkatan" class="btn btn-success btn-xs"><i class="fa fa-table"></i> Lulus</button>
        <button data-toggle="modal" data-target="#modal-tabel-perangkatan" class="btn btn-danger btn-xs"><i class="fa fa-table"></i> Belum Lulus</button>
        <button data-toggle="modal" data-target="#modal-tabel-perangkatan" class="btn btn-primary btn-xs"><i class="fa fa-table"></i> Cuti</button>

    </div>

    <div class="modal fade" id="modal-semua">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Jumlah Mahasiswa</h4>
                </div>
                <div class="modal-body">
                    <table class="table demo-table">
                        <thead>
                            <tr>
                                <th id="th">Angkatan</th>
                                <th id="th">Jumlah</th>
                                <th id="th">Tindakan</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($jumlah_semua as $jml_semua) :
                                ?>
                                <tr>
                                    <td align="center">20<?= e($jml_semua->angkatan) ?></td>
                                    <td align="center"><?= e($jml_semua->jumlah) ?> </td>
                                    <td align="center"><a href="<?= site_url('dosen/kaprodi/mahasiswa/semua/' . $jml_semua->angkatan); ?>" class="btn btn-xs btn-success"><i class="fa fa-eye"></i> Lihat</a> </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-aktif">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Jumlah Mahasiswa Aktif</h4>
                </div>
                <div class="modal-body">
                    <table class="table demo-table">
                        <thead>
                            <tr>
                                <th id="th">Angkatan</th>
                                <th id="th">Jumlah</th>
                                <th id="th">Tindakan</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($jumlah_aktif as $jml_aktif) :
                                ?>
                                <tr>
                                    <td align="center">20<?= e($jml_aktif->angkatan) ?></td>
                                    <td align="center"><?= e($jml_aktif->jumlah) ?> </td>
                                    <td align="center"><a href="<?= site_url('dosen/kaprodi/mahasiswa/semua/' . $jml_aktif->angkatan); ?>" class="btn btn-xs btn-success"><i class="fa fa-eye"></i> Lihat</a> </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
      <div class="modal fade" id="modal-tidak-aktif">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Jumlah Mahasiswa Tidak Aktif</h4>
                </div>
                <div class="modal-body">
                    <table class="table demo-table">
                        <thead>
                            <tr>
                                <th id="th">Angkatan</th>
                                <th id="th">Jumlah</th>
                                <th id="th">Tindakan</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($jumlah_tidak_aktif as $jml_tidak_aktif) :
                                ?>
                                <tr>
                                    <td align="center">20<?= e($jml_tidak_aktif->angkatan) ?></td>
                                    <td align="center"><?= e($jml_tidak_aktif->jumlah) ?> </td>
                                    <td align="center"><a href="<?= site_url('dosen/kaprodi/mahasiswa/semua/' . $jml_tidak_aktif->angkatan); ?>" class="btn btn-xs btn-success"><i class="fa fa-eye"></i> Lihat</a> </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="box-body">
        <div class="table-responsive">
            <?php if ($status == "true"): ?>
                <table class="table demo-table" id="example1">
                    <thead>
                        <tr>
                            <th id="th">No.</th>
                            <th id="th">NIM</th>
                            <th id="th">Nama Mahasiswa</th>
                            <th id="th">Angkatan</th>
                            <th id="th">No. HP</th>
                            <th id="th">Data Akademik</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($data_mahasiswa as $row) :
                            ?>
                            <tr>
                                <td align="center"><?= $i++ ?>.</td>
                                <td align="center"><?= e($row->nim) ?></td>
                                <td><?= e($row->nama_mahasiswa) ?> </td>
                                <td align="center">20<?= e(substr($row->nim, 0, 2)) ?></td>
                                <td><?= e($row->telepon) ?> </td>


                                <td align="center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-sm">Detail</button>
                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#" onclick="halaman('<?= site_url('dosen/Konsultasi_perwalian/biodata_mahasiswa/' . $row->nim) ?>');">
                                                    <i class="fa fa-user"></i>Biodata</a></li>
                                            <li><a href="#" onclick="halaman('<?= site_url('dosen/Konsultasi_perwalian/krs_mahasiswa/' . $row->nim) ?>');"><i
                                                        class="fa fa-file-text"></i> KRS</a></li>
                                            <li><a href="#" onclick="halaman('<?= site_url('dosen/Konsultasi_perwalian/khs_mahasiswa/' . $row->nim) ?>');"><i
                                                        class="fa fa-file-text"></i> KHS</a></li>
                                            <li><a href="#" onclick="halaman('<?= site_url('dosen/Konsultasi_perwalian/petikan_nilai_mahasiswa/' . $row->nim) ?>');"><i
                                                        class="fa fa-clipboard"></i>Nilai</a></li>
                                        </ul>
                                    </div>
                                </td>


                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="callout callout-warning">

                    <p>Untuk menampilkan data, mohon klik salah satu tombol diatas dan klik tombol <b>Lihat</b></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function halaman(url) {
        var win = window.open(url, 'DUMET School', "height=600, width=1000, scrollbars=yes");
        win.focus();
    }
</script>

