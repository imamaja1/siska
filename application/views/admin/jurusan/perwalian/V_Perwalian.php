<?= $this->session->flashdata('info') ?>

<div class="box box-solid flat">
    <div class="box-body">
        <a href="#" class="btn btn-primary btn-sm flat" onclick="$('#tambah-perwalian').modal('toggle')"><i class="fa fa-plus"></i> Tambah Perwalian</a>
        <div class="pull-right">
            <a href="<?= site_url('admin/jurusan/perwalian/quick_view') ?>" class="btn btn-danger btn-sm flat">Rekap</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3 col-xs-12">
        <div class="box box-primary" id="filter">
            <div class="box-header">
                <h4>Filter</h4>
            </div>
            <div class="box-body">
                <form id="form" method="POST" action="<?= site_url('admin/jurusan/perwalian/filter') ?>">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group">
                        <label class="control-label">Angkatan</label>
                        <select required class="form-control" name="tahun_angkatan">
                            <option value="" selected disabled>Pilih</option>
                            <?php foreach ($tahun_angkatan as $row) { ?>
                                <option value="<?= substr($row->tahun_akademik, 2, 2) ?>"><?= substr($row->tahun_akademik, 0, 4) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Jurusan</label>
                        <select required class="form-control" name="kode_program_studi">
                            <option value="" selected disabled>Pilih</option>
                            <?php foreach ($nama_jurusan as $row) { ?>
                                <option value="<?= e($row->kode_program_studi) ?>"><?= e($row->singkatan_program_studi) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Dosen</label>
                        <select required class="form-control select2" name="kode_dosen" style="width: 100%">
                            <option value="" selected disabled>Pilih</option>
                            <?php foreach ($dosen as $d) { ?>
                                <option value="<?= e($d->kode_dosen) ?>"><?= e($d->nama_dosen) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-primary flat pull-right"><i class="fa fa-gear"></i> Proses</button>
                    </div>
                </form>
                <br><br>
                <hr style="border: 0.6px solid #d2d6de">
                <h5>Perubahan Dosen Wali/Perwakilan per Mahasiswa</h5>
                <form id="form-cari-nim" action="<?= site_url('admin/jurusan/perwalian/edit_dosen_wali') ?>" method="post">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group">
                        <input type="text" autocomplete="off" placeholder="Masukan NIM" name="nim" id="search-box" class="form-control">
                        <div id="suggesstion-box"></div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success pull-right"><i class="fa fa-search"></i> Cari</button>
                    </div>
                </form>
                <br><br>
                <hr style="border: 0.6px solid #d2d6de">
                <h5>Perubahan Dosen Wali/Perwakilan per Dosen</h5>
                <form id="form-cari-dosen" action="#" method="post">
                    <div class="form-group">
                        <input type="hidden" name="kode_dosen_cari" id="kode-dosen-cari">
                        <input type="text" required autocomplete="off" placeholder="Masukan Nama Dosen" name="kode_dosen" id="search-box-dosen" class="form-control">
                        <div id="suggesstion-box-dosen"></div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-info pull-right"><i class="fa fa-search"></i> Cari</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-9 col-xs-12">
        <div id="landing">
            <div class="center-item" style="width: 100%;height: 300px; background-color: #7adddd; border-radius: 10px; padding: 10px">
                <p><i>"Landing page..."</i></p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambah-perwalian">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= site_url('admin/jurusan/perwalian/simpan_perwalian') ?>" method="post">
	<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Tambah Perwalian</h4>
                    <br>
                    <div class="form-group">
                        <div class="col-sm-6" style="padding: 0;">
                            <select required name="nama_jurusan" id="nama-program-studi" class="form-control">
                                <option value="" selected disabled>Program Studi</option>
                                <?php foreach ($nama_jurusan as $row) : ?>
                                    <option value="<?= e($row->kode_program_studi) ?>"><?= e($row->singkatan_program_studi) ?> - <?= e($row->nama_program_studi) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-body" id="data-mahasiswa">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm flat" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm flat"><i class="fa fa-check-square-o"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    var super_kode_dosen;
    $(document).ready(function () {
        $("#form").submit(function (e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var data = $(this).serialize();
            $.ajax({
                url : url, data : data, type : 'POST',
                beforeSend : function () { $("#landing").html("<div class='text-center'><img src='https://assets.website-files.com/5c7fdbdd4e3feeee8dd96dd2/5ce46f8ffd710a2c22c15e48_cust_ami.gif'/></div>"); },
                success : function (res) { $("#landing").html(res); },
                error : function () { console.log('gagaal load') }
            })
        });
        $("#form-cari-dosen").submit(function (e) { e.preventDefault(); perdosen(); });
        $("#form-cari-nim").submit(function (e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var data = $(this).serialize();
            $.ajax({
                url : url, data : data, type : 'POST',
                beforeSend : function () { $("#landing").html("<div class='text-center'><img src='https://assets.website-files.com/5c7fdbdd4e3feeee8dd96dd2/5ce46f8ffd710a2c22c15e48_cust_ami.gif'/></div>"); },
                success : function (res) { $("#landing").html(res); },
                error : function () { console.log('gagaal load') }
            })
        });
    });
    function perdosen(){
        var url = "<?= site_url('admin/jurusan/perwalian/edit_dosen_wali_perdosen') ?>/"+super_kode_dosen;
        $.ajax({
            url : url,
            beforeSend : function () { $("#landing").html("<div class='text-center'><img src='https://assets.website-files.com/5c7fdbdd4e3feeee8dd96dd2/5ce46f8ffd710a2c22c15e48_cust_ami.gif'/></div>"); },
            success : function (res) { $("#landing").html(res); }
        })
    }
</script>
<script>
    $('form select').on('change invalid', function () {
        var textfield = $(this).get(0);
        textfield.setCustomValidity('');
        if (!textfield.validity.valid) { textfield.setCustomValidity('Tidak boleh kosong!'); }
    });
</script>