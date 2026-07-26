<div class="box box-solid flat">
    <div class="box-body">
        <h5>&nbsp;DATA KONSULTASI PENGAKTIFAN KRS, <b><?php
                foreach ($data as $row_title) {
                    echo "[" . $row_title->nim . "] ";
                    $nama = $row_title->nama_mahasiswa;
                    echo $nama;
                }
                ?></b></h5>
    </div>
</div>

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Update Data Konsultasi KRS</b></h4>
            </div>
            <div class="modal-body" style="padding: 0px;">

                <?php
                foreach ($data as $row) {
                    ?>
                    <form method="POST" action="<?= site_url('dosen/konsultasi_perwalian/tambah_konsultasi_krs'); ?>">
                        <div class="modal-body">
                            <input type="hidden" name="kode_konsultasi_perwalian" value="<?= e($row->kode_konsultasi_perwalian) ?>">
                            <input  type="hidden" required name="nim" value="<?= e($row->nim) ?>" class="form-control">
                            <input  type="hidden" required name="nama_mahasiswa" value="<?= e($row->nama_mahasiswa) ?>" class="form-control">

                            <div class="form-group">
                                <label>Isi Konsultasi :</label>
                                <textarea required class="form-control" cols="20" rows="4" name="isi_konsultasi" placeholder="Isi konsultasi sesuai dengan keluhan/permintaan/pertanyaan dari mahasiswa"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Tanggapan :</label>
                                <textarea required class="form-control" cols="20" rows="4"
                                          name="tanggapan"
                                          placeholder="Tanggapan sesuai dengan saran/jawaban/nasihat dari dosen wali"></textarea>
                            </div>
                            <input type="hidden" value="K" name="jenis_konsultasi">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-remove"></i>
                                Tutup
                            </button>
                            <button type="submit" class="btn btn-success flat "><i class="fa fa-check-circle"></i> Simpan
                            </button>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="box box-solid flat">
    <div class="box-body">
        <p>
            <button type="button" class="btn btn-xs btn-primary flat" data-toggle="modal" data-target="#modal-default">
                <i class="fa fa-edit"></i>&nbsp; Update Data Konsultasi KRS
            </button>
        </p>
        <div class="table-responsive">
            <?php if (count($data) > 0) : ?>
                <table class="demo-table table">
                    <thead>
                        <tr>
                            <th style="text-align: center;">No.</th>
                            <th style="text-align: center;">Isi Konsultasi</th>
                            <th style="text-align: center;">Tanggapan</th>
                            <th style="text-align: center;">Tanggal Konsultasi</th>
                        </tr>
                    </thead>
                    <?php
                    $no = 1;
                    foreach ($data as $krs_konsul) {
                        ?>
                        <tr>
                            <td align="center"><?= e($no++) ?>.</td>
                            <td id="isi_konsultasi_krs-<?= e($krs_konsul->kode_konsultasi_perwalian) ?>"><?= e($krs_konsul->isi_konsultasi) ?></td>
                            <td id="tanggapan_krs-<?= e($krs_konsul->kode_konsultasi_perwalian) ?>"><?= e($krs_konsul->tanggapan) ?></td>
                            <td align="center"><?= e($krs_konsul->date_created) ?></td>
                        <p hidden id="jenis_konsultasi_krs-<?= e($krs_konsul->kode_konsultasi_perwalian) ?>"><?= e($krs_konsul->jenis_konsultasi) ?></p>
                        <p hidden id="nim-<?= e($krs_konsul->kode_konsultasi_perwalian) ?>"><?= e($krs_konsul->nim) ?></p>

                        </tr>   
                    <?php } ?>
                </table>
            <?php else: ?>
                <div style="padding: 20px;">
                    Tidak ada data/isi Konsultasi Pengaktifan KRS, silahkan tambah data/isi konsultasi untuk mengaktifkan pencetakan KRS dari mahasiswa <b><?= e($nama) ?>.</b>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->session->flashdata('message') ?>

<script>
    function editkonsultasikrs(id) {

        var isikonsultasi_krs = $("#isi_konsultasi_krs-" + id).html();
        var tanggapan_krs = $("#tanggapan_krs-" + id).html();
        var jenis_konsultasi = $("#jenis_konsultasi_krs-" + id).html();
        var nim = $("#nim-" + id).html();

        $("#kode_krs-edit").val(id);
        $("#isi_konsultasi_krs-edit").val(isikonsultasi_krs);
        $("#tanggapan_krs-edit").val(tanggapan_krs);
        $("#jenis_konsultasi_krs-edit").val(jenis_konsultasi);
        $("#nim-edit").val(nim);

        $("#edit-konsultasi_krs").modal("show");
    }
</script>

<script>
    function hapus(url) {
        swal({
            title: '',
            text: "Anda yakin ingin menghapus data ini?",
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