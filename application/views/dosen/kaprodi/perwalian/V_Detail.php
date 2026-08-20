<div class="box" style="border: 2px solid #3c8dbc; border-radius: 10px">
    <button class="btn btn-flat btn-danger" data-dismiss="modal" style="position: absolute; top: -10px; right: -10px"><i
                class="fa fa-times"></i></button>
    <div class="box-body">
        <p style="margin-top: 20px; text-align: center"><b>KARTU BIMBINGAN AKADEMIK</b></p>
        <hr>
        <dl class="dl-horizontal">
            <dt>Nama :</dt>
            <dd><?= e($perwalian->nama_mahasiswa) ?></dd>
            <dt>NIM :</dt>
            <dd><?= e($perwalian->nim) ?></dd>
            <dt>No. HP/Email :</dt>
            <dd><?= e($perwalian->telepon) ?> / <?= e($perwalian->email) ?></dd>
            <dt>Dosen Wali :</dt>
            <dd><?= e($perwalian->nama_dosen) ?></dd>
        </dl>
        <div id="content-edit" style="display: none; border: 1px solid gray; padding: 20px; margin-bottom: 10px">
            <div class="row">
                <form id="form-update" action="#" method="post">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="">Materi Konsultasi</label>
                            <textarea name="isi_konsultasi" required class="form-control"
                                      id="materi-konsultasi"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Solusi Konsultasi</label>
                            <textarea name="tanggapan" required class="form-control"
                                      id="solusi-konsultasi"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table demo-table">
                <thead>
                <tr>
                    <th style="text-align: center">NO.</th>
                    <th style="text-align: center">Semester</th>
                    <th style="text-align: center">Tgl. Konsultasi</th>
                    <th style="text-align: center">Materi Konsultasi</th>
                    <th style="text-align: center">Solusi Konsultasi</th>
                    <th style="text-align: center">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1;
                foreach ($data as $row) : ?>
                    <tr>
                        <td style="text-align: center"><?= $no++ ?>.</td>
                        <td style="text-align: center"><?= e($row->semester) ?></td>
                        <td><?= e($row->date_created == null ? '(empty)' : tgl_indo($row->date_created)) ?></td>
                        <td><?= e($row->isi_konsultasi) ?></td>
                        <td><?= e($row->tanggapan) ?></td>
                        <td>
                            <a href="#" onclick="edit('<?= e($row->kode_konsultasi_perwalian) ?>')"
                               class="btn btn-primary btn-xs" title="Edit Konsultasi"><i class="fa fa-edit"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
<script>
    var kode_kunsultasi_perwalian = '';
    $("#form-update").submit(function (e){
        e.preventDefault();
        var url = "<?= site_url('dosen/kaprodi/konsultasi_perwalian/update_konsultasi') ?>/"+kode_kunsultasi_perwalian;
        var data = $(this).serialize();
        $.ajax({
            url : url,
            data : data,
            type : 'post',
            success : function (res){
                view(super_nim);
            },
            error : function (){
                alert('data gagal diubah.')
            }
        })
    })
    function edit(id) {
        kode_kunsultasi_perwalian = id;
        var url = "<?= site_url('dosen/kaprodi/konsultasi_perwalian/edit_konsultasi') ?>/"+id;
        $.ajax({
            url: url,
            success : function (res){
                // console.log(res)
                var obj = JSON.parse(res);
                $("#materi-konsultasi").val(obj.isi_konsultasi);
                $("#solusi-konsultasi").val(obj.tanggapan);
                $("#content-edit").fadeIn()
            },
            error : function (){
                alert('gagal mengambil data dari server.')
            }
        })
    }
</script>

