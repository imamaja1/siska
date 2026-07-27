<div class="box box-solid flat">
    <div class="box-body">
        <h5>&nbsp;DATA KONSULTASI UMUM, <b><?php
                foreach ($data as $row_title) {
                    echo "[" . e($row_title->nim) . "] ";
                    $nama = $row_title->nama_mahasiswa;
                    echo e($nama);
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
                <h4 class="modal-title"><b>Tambah Data Konsultasi Umum</b></h4>
            </div>
            <div class="modal-body" style="padding: 0px;">

                <?php
                foreach ($data as $row) {
                    ?>
                    <form method="POST" action="<?= site_url('dosen/konsultasi_perwalian/tambah_konsultasi_umum'); ?>">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
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
                            <input type="hidden" value="U" name="jenis_konsultasi">
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
                <i class="fa fa-edit"></i>&nbsp; Tambah Data
            </button>
        </p>
        <div class="table-responsive">
            <?php if (count($konsultasi_umum) > 0) : ?>
                <table class="demo-table table">
                    <thead>
                        <tr>
                            <th style="text-align: center;">No.</th>
                            <th style="text-align: center;">Isi Konsultasi</th>
                            <th style="text-align: center;">Tanggapan</th>
                            <th style="text-align: center;">Tanggal Konsultasi</th>
                            <th style="text-align: center;">Tindakan</th>
                        </tr>
                    </thead>
                    <?php
                    $no = 1;
                    foreach ($konsultasi_umum as $umum_konsul) {
                        ?>
                        <tr>
                            <td align="center"><?= e($no++) ?>.</td>
                            <td id="isi_konsultasi_umum-<?= e($umum_konsul->kode_konsultasi_perwalian_detail) ?>"><?= e($umum_konsul->isi_konsultasi) ?></td>
                            <td id="tanggapan_umum-<?= e($umum_konsul->kode_konsultasi_perwalian_detail) ?>"><?= e($umum_konsul->tanggapan) ?></td>
                            <td align="center"><?= e($umum_konsul->date_created) ?></td>
                        <p hidden id="jenis_konsultasi_umum-<?= e($umum_konsul->kode_konsultasi_perwalian_detail) ?>"><?= e($umum_konsul->jenis_konsultasi) ?></p>
                        <p hidden id="nim-<?= e($umum_konsul->kode_konsultasi_perwalian_detail) ?>"><?= e($umum_konsul->nim) ?></p>
                        <td align="center">
                            <a href="#" onclick="javascript:editkonsultasiumum(<?= e($umum_konsul->kode_konsultasi_perwalian_detail) ?>)" class="btn-xs btn-info flat"><i class="fa fa-edit"></i> Ubah</a>
                            <a href="#!" class="btn-xs btn-danger flat" onclick="hapus('<?= site_url('dosen/konsultasi_perwalian/hapus_umum/' . $umum_konsul->kode_konsultasi_perwalian_detail . $umum_konsul->nim) ?>')"><i class="fa fa-trash"></i> Hapus</a> 
                        </td>
                        </tr>   
                    <?php } ?>
                </table>
            <?php else: ?>
                <div style="padding: 20px;">
                    <b> Tidak ada data Konsultasi UMUM</b>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="modal fade" id="edit-konsultasi_umum">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Edit Konsultasi Umum</b></h4>
            </div>
            <div class="modal-body" style="padding: 0px;">


                <form method="POST" action="<?= site_url('dosen/konsultasi_perwalian/ubah_konsultasi_umum'); ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="modal-body">
                        <input type="hidden" name="nim" id="nim-edit">
                        <input type="hidden" name="kode_konsultasi_perwalian" id="kode_umum-edit">
                        <input type="hidden" name="jenis_konsultasi_umum" id="jenis_konsultasi_umum-edit">

                        <div class="form-group">
                            <label>Isi Konsultasi :</label>
                            <textarea required class="form-control" cols="20" rows="4" name="isi_konsultasi" id="isi_konsultasi_umum-edit" placeholder="Isi konsultasi sesuai dengan keluhan/permintaan/pertanyaan dari mahasiswa"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Tanggapan :</label>
                            <textarea required class="form-control" cols="20" rows="4" id="tanggapan_umum-edit" name="tanggapan" placeholder="Tanggapan sesuai dengan saran/jawaban/nasihat dari dosen wali"></textarea>
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
</div>

<?= $this->session->flashdata('message') ?>

<script>
    function editkonsultasiumum(id) {

        var isikonsultasi_umum = $("#isi_konsultasi_umum-" + id).html();
        var tanggapan_umum = $("#tanggapan_umum-" + id).html();
        var jenis_konsultasi = $("#jenis_konsultasi_umum-" + id).html();
        var nim = $("#nim-" + id).html();

        $("#kode_umum-edit").val(id);
        $("#isi_konsultasi_umum-edit").val(isikonsultasi_umum);
        $("#tanggapan_umum-edit").val(tanggapan_umum);
        $("#jenis_konsultasi_umum-edit").val(jenis_konsultasi);
        $("#nim-edit").val(nim);

        $("#edit-konsultasi_umum").modal("show");
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

