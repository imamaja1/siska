
<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('dosen/konsultasi_perwalian') ?>" class="btn btn-xs btn-success flat"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>
<div <?= isset($hidden) ? $hidden : ''; ?>>
    <?php if (count($konsultasi_perwakilan) > 0): ?>
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
                                <th id="th">Isi Konsultasi</th>
                                <th id="th">Status Konsultasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($konsultasi_perwakilan as $row) :
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
                                            echo $dosen_perwakilan;
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
                                        <a href="#" class="btn btn-xs btn-info flat" onclick="halaman('<?= site_url('dosen/Konsultasi_perwalian/isi_konsultasi/' . $row->nim) ?>');"><i class="fa fa-eye"></i> Detail</a> 
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if ($row->status_cetak == "A") : ?>
                                            <a href="#!" onclick="konf_status('<?= site_url('dosen/konsultasi_perwalian/nonaktif_search/' . $row->kode_konsultasi_perwalian) ?>')" class="btn btn-success btn-xs flat"><i class="fa fa-check-circle-o"></i> aktif</a>
                                        <?php else: ?>
                                            <a href="#!" onclick="konf_status('<?= site_url('dosen/konsultasi_perwalian/aktif_search/' . $row->kode_konsultasi_perwalian) ?>')" class="btn btn-danger btn-xs flat"><i class="fa fa-times-circle"></i> aktifkan</a>
                                        <?php endif; ?>

                                    </td>   
                                </tr>
                            <div hidden
                                 id="nama_mahasiswa-<?= e($row->kode_konsultasi_perwalian) ?>"><?= e($row->nama_mahasiswa) ?></div>
                            <div hidden id="nama_dosen-<?= e($row->kode_konsultasi_perwalian) ?>"><?= e($row->nama_dosen) ?></div>
                            <div hidden id="kode_dosen-<?= e($row->kode_konsultasi_perwalian) ?>"><?= e($row->kode_dosen) ?></div>
                            <div hidden
                                 id="isi_konsultasi-<?= e($row->kode_konsultasi_perwalian) ?>"><?= e($row->isi_konsultasi) ?></div>
                            <div hidden id="tanggapan-<?= e($row->kode_konsultasi_perwalian) ?>"><?= e($row->tanggapan) ?></div>
                            <div hidden
                                 id="jenis_konsultasi-<?= e($row->kode_konsultasi_perwalian) ?>"><?= e($row->jenis_konsultasi) ?></div>
                             <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="callout callout-info flat">
            <p>Tidak ada data yang ditemukan untuk kata kunci NIM yang anda masukan.</p>
        </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="modal-konsultasi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <div><b style="font-size: 18px;">Form Konsultasi Perwalian</b><br>Dengan Dosen Wali Ibu/Bapak <b><input
                            readonly style="width: 350px; border: none; background-color: white;" type="text"
                            id="dosen_wali-edit"></b></div>

            </div>
            <form method="POST" action="<?= site_url('dosen/konsultasi_perwalian/ubah_konsultasi_perwalian_nim_nama'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="kode-edit" id="kode-edit">
                    <input type="hidden" name="kode_dosen-edit" id="kode_dosen-edit">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label>NIM :</label>
                                <input readonly type="text" required name="nim-edit" id="nim-edit" class="form-control">
                            </div>
                            <div class="col-sm-8">
                                <label>Nama Mahasiswa :</label>
                                <input readonly type="text" required name="nama_mahasiswa-edit" id="nama_mahasiswa-edit"
                                       class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Isi Konsultasi :</label>
                        <textarea required class="form-control" cols="20" rows="4" id="isi_konsultasi-edit"
                                  name="isi_konsultasi-edit"
                                  placeholder="Isi konsultasi sesuai dengan keluhan/permintaan/pertanyaan dari mahasiswa"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Tanggapan :</label>
                        <textarea required class="form-control" cols="20" rows="4" id="tanggapan-edit"
                                  name="tanggapan-edit"
                                  placeholder="Tanggapan sesuai dengan saran/jawaban/nasihat dari dosen wali"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Jenis Konsultasi :</label>
                        <select required class="form-control" name="jenis_konsultasi-edit" id="jenis_konsultasi-edit">
                            <option value="" disabled selected>Pilih Jenis Konsultasi</option>
                            <option value="U">Umum</option>
                            <option value="K">Khusus</option>
                            <option value="P">Pengaktifan KRS</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-remove"></i>
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-success flat "><i class="fa fa-check-circle"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="box box-primary flat">
    <div class="box-header">
        <h5><b>Pencarian Mahasiswa untuk konsultasi perwalian</b></h5>
        <hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" method="post" action="<?= site_url('dosen/konsultasi_perwalian/search_process'); ?>">
            <div class="form-group">
                <label class="control-label col-sm-3">Pencarian Berdasarkan <label class="text-danger">*</label>
                    :</label>
                <div class="col-sm-5">
                    <label><input type="radio" name="berdasarkan" value="nim" <?= set_radio('berdasarkan', 'nim') ?>>
                        NIM (Nomor Induk Mahasiswa)</label>
                    &nbsp;&nbsp;
                    <label><input type="radio" name="berdasarkan" value="nama" <?= set_radio('berdasarkan', 'nama') ?>>
                        Nama Mahasiswa </label>
                    <br>
                    <small class="text-danger"><?= form_error('berdasarkan'); ?></small>
                </div>

            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Masukan Kata Kunci <label class="text-danger">*</label> :</label>
                <div class="col-sm-4">
                    <input value="<?= set_value('kata_kunci') ?>" type="text" class="form-control"
                           placeholder="Ketik Kata Kunci disini" name="kata_kunci" id="kata_kunci">
                    <small class="text-danger"><?= form_error('kata_kunci') ?></small>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-cog"></i> Proses</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->session->flashdata('message') ?>
<script>

    function editkonsultasi(id) {

        var nim = $("#nim-" + id).html();
        var nama_mahasiswa = $("#nama_mahasiswa-" + id).html();
        var dosen_wali = $("#nama_dosen-" + id).html();
        var isi_konsultasi = $("#isi_konsultasi-" + id).html();
        var tanggapan = $("#tanggapan-" + id).html();
        var jenis_konsultasi = $("#jenis_konsultasi-" + id).html();
        var kode_dosen = $("#kode_dosen-" + id).html();

        $("#kode-edit").val(id);
        $("#nim-edit").val(nim);
        $("#nama_mahasiswa-edit").val(nama_mahasiswa);
        $("#dosen_wali-edit").val(dosen_wali);
        $("#isi_konsultasi-edit").val(isi_konsultasi);
        $("#tanggapan-edit").val(tanggapan);
        $("#jenis_konsultasi-edit").val(jenis_konsultasi);
        $("#kode_dosen-edit").val(kode_dosen);

        $("#modal-konsultasi").modal("show");
    }


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