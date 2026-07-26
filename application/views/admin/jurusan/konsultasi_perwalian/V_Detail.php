<div class="box" style="border: 2px solid #3c8dbc; border-radius: 10px">
    <button class="btn btn-flat btn-danger" data-dismiss="modal" style="position: absolute; top: -10px; right: -10px"><i class="fa fa-times"></i></button>
    <div class="box-body" >
        <p style="margin-top: 20px; text-align: center"><b>KARTU BIMBINGAN AKADEMIK</b></p>
        <hr>
       
        <dl class="dl-horizontal">
            <dt>Nama :</dt>
            <dd><?= $perwalian->nama_mahasiswa ?></dd>
            <dt>NIM :</dt>
            <dd><?= $perwalian->nim ?></dd>
            <dt>No. HP/Email :</dt>
            <dd><?= $perwalian->telepon ?> / <?= $perwalian->email ?></dd>
            <dt>Dosen Wali :</dt>
            <dd><?= $perwalian->nama_dosen ?></dd>
            <?php if($dosen): ?>
                <button class="btn btn-small btn-primary pull-right" data-toggle="modal" data-target="#exampleModal">Tambah Konsultasi</button>
            <?php endif; ?>
            <br>
        </dl>
       
        <div class="table-responsive">
            <table class="table demo-table">
                <thead>
                <tr>
                    <th style="text-align: center">NO.</th>
                    <th style="text-align: center">Semester</th>
                    <th style="text-align: center">Tgl. Konsultasi</th>
                    <th style="text-align: center">Materi Konsultasi</th>
                    <th style="text-align: center">Solusi Konsultasi</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1; foreach ($data as $row) : ?>
                    <tr>
                        <td style="text-align: center"><?= $no++ ?>.</td>
                        <td style="text-align: center"><?= $row->semester ?></td>
                        <td><?= $row->date_created == null ? '(empty)' : tgl_indo($row->date_created ) ?></td>
                        <td><?= $row->isi_konsultasi ?></td>
                        <td><?= $row->tanggapan ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Tambah Konsultasi Perwalian</b></h4>
            </div>
            <div class="modal-body" style="padding: 0px;">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Isi Konsultasi :</label>
                        <textarea required class="form-control" cols="20" rows="4" name="isi_konsultasi" id="isi_konsultasi" placeholder="Isi konsultasi sesuai dengan keluhan/permintaan/pertanyaan dari mahasiswa"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Tanggapan :</label>
                        <textarea required class="form-control" cols="20" rows="4"
                                    name="tanggapan" id="tanggapan"
                                    placeholder="Tanggapan sesuai dengan saran/jawaban/nasihat dari dosen wali"></textarea>
                    </div>
                    <input type="hidden" value="P" name="jenis_konsultasi">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" onclick="tutup()"><i class="fa fa-remove"></i>
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-success flat " onclick="input(<?= $perwalian->nim ?>)"><i class="fa fa-check-circle"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function tutup() {
        $("#exampleModal").modal('hide');
    }
    function input(nim) {
        if ($('#isi_konsultasi').val() && $('#tanggapan').val()) {       
            $.ajax({
                url: "<?= base_url('dosen/Konsultasi_perwalian/tambah_konsultasi_krs_new') ?>/" + nim,
                type: 'POST',
                data: {
                    isi_konsultasi: $('#isi_konsultasi').val(),
                    tanggapan: $('#tanggapan').val()
                },
            });
            $("#exampleModal").modal('hide');
            $("#modal-view").modal('hide');
            view(nim);
        }
    }
</script>