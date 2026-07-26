<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/jurusan/dosen'); ?>" class="btn-sm btn-success flat"><i
                    class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="<?= isset($active_1_1) ? $active_1_1 : "" ?>"><a href="#tab_1" data-toggle="tab"
                                                                    aria-expanded="false">Ubah Biodata</a></li>
        <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Signature</a></li>
        <li class="<?= isset($active_2_1) ? $active_2_1 : "" ?>"><a href="#tab_2" data-toggle="tab"
                                                                    aria-expanded="false">Ubah Password</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane <?= isset($active_1_2) ? $active_1_2 : "" ?>" id="tab_1">
            <div class="box box-solid flat">
                <div class="box-body"><br>
                    <form class="form-horizontal" id="form" method="POST"
                          action="<?= site_url('admin/jurusan/dosen/ubah') ?>">
                        <input type="hidden" value="<?= $data_dosen->kode_dosen ?>" name="kode_dosen_biodata">
                        <div class="form-group">
                            <label class="control-label col-sm-2">NIK</label>
                            <div class="col-sm-3">
                                <input value="<?= $data_dosen->nik; ?>" type="text" name="nik" placeholder="NIK"
                                       class="form-control" title="Field NIK harus diisi">
                                <small style="color: red;"><?= form_error('nik') ?></small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Nama Dosen</label>
                            <div class="col-sm-4">
                                <input value="<?= $data_dosen->nama_dosen; ?>" type="text" name="nama_dosen"
                                       class="form-control" placeholder="Nama dosen">
                                <small style="color: red;"><?= form_error('nama_dosen') ?></small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Field Studi</label>
                            <div class="col-sm-4">
                                <input value="<?= $data_dosen->field_studi; ?>" type="text" name="field_studi"
                                       class="form-control" placeholder="Field studi">
                                <small style="color: red;"><?= form_error('field_studi') ?></small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Alumni</label>
                            <div class="col-sm-4">
                                <input value="<?= $data_dosen->alumni; ?>" type="text" name="alumni"
                                       class="form-control" placeholder="Alumni">
                                <small style="color: red;"><?= form_error('alumni') ?></small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Status Dosen</label>
                            <div class="col-sm-3">
                                <select class="form-control" name="status_dosen">
                                    <option disabled>Pilih Status</option>
                                    <?php
                                    $data_dosen->status_dosen;
                                    if ($data_dosen == 'T') {
                                        echo '<option selected value="T">Tetap</option>';
                                    } else {
                                        echo '<option value="T">Tetap</option>';
                                    }
                                    if ($data_dosen == 'L') {
                                        echo '<option selected value="L">Luar</option>';
                                    } else {
                                        echo '<option value="L">Luar</option>';
                                    }
                                    ?>
                                </select>
                                <small style="color: red;"><?= form_error('status_dosen') ?></small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Homebase</label>
                            <div class="col-sm-3">
                                <select class="form-control" name="homebase">
                                    <option disabled selected>Pilih Homebase</option>
                                    <?php foreach ($homebase as $row) : ?>
                                        <option <?php echo $data_dosen->homebase == $row->kode_program_studi ? "selected" : "" ?> <?= set_select('homebase', $row->kode_program_studi) ?>
                                                value="<?= $row->kode_program_studi ?>"><?= $row->singkatan_program_studi ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small style="color: red;"><?= form_error('homebase') ?></small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">No. Telepon</label>
                            <div class="col-sm-4">
                                <input value="<?= $data_dosen->no_telp; ?>" type="text" name="no_telp"
                                       class="form-control" placeholder="No. Telp">
                                <small style="color: red;"><?= form_error('no_telp') ?></small>
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Alamat email</label>
                            <div class="col-sm-4">
                                <input value="<?= $data_dosen->alamat_email; ?>" type="text" name="alamat_email"
                                       class="form-control" placeholder="Email">
                                <small style="color: red;"><?= form_error('alamat_email') ?></small>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2">Status Login</label>
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label><input <?= $data_dosen->status_login == "A" ? "checked" : "" ?> type="radio"
                                                                                                           name="status_login"
                                                                                                           value="A"
                                                                                                           id="login-aktif">Aktif</label>
                                    <label><input <?= $data_dosen->status_login == "N" ? "checked" : "" ?> type="radio"
                                                                                                           name="status_login"
                                                                                                           value="N"
                                                                                                           id="login-nonaktif">Tidak
                                        Aktif</label>
                                </div>
                                <small style="color: red;"><?= form_error('status_login') ?></small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2"></label>
                            <div class="col-sm-5">
                                <button type="submit" name="submit" class="btn btn-primary flat"><i
                                            class="fa fa-check-circle"></i> Simpan
                                </button>
                                <button type="reset" name="submit" class="btn btn-danger flat"><i
                                            class="fa fa-refresh"></i> Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="tab_3">
            <div class="box box-solid flat">
<!--                <div class="box-header">-->
<!--                    <h5><b>Signature --><!--</b></h5>-->
<!--                    <hr>-->
<!--                </div>-->
                <div class="box-body">
                    <form id="form-file-<?= $data_dosen->kode_dosen ?>" action="" enctype="multipart/form-data" method="post">
                        <input id="upload-<?= $data_dosen->kode_dosen ?>" name="foto" type="file" style="display: none" onchange="readURL(this,<?= $data_dosen->kode_dosen ?>);">
                    </form>

                    <p style="text-align: center">
                        <?php if (empty($data_dosen->signature)) : ?>
                            <img src="<?= base_url('assets/dist/img/default-50x50.gif') ?>" class="img-bordered" onclick="cot(<?= $data_dosen->kode_dosen ?>)" id="upload_link-<?= $data_dosen->kode_dosen ?>"  style="height:150px" alt=""><br>
                            <?= $data_dosen->nama_dosen; ?>
                        <?php else: ?>
                            <img src="<?= base_url('assets/signature-dosen/'.$data_dosen->signature) ?>" class="img-bordered" onclick="cot(<?= $data_dosen->kode_dosen ?>)" id="upload_link-<?= $data_dosen->kode_dosen ?>"  style="height:150px" alt=""><br>
                            <?= $data_dosen->nama_dosen; ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="tab-pane <?= isset($active_2_2) ? $active_2_2 : "" ?>" id="tab_2">
            <div class="box box-solid flat">
                <div class="box-header">
                    <h5><b>Ubah Password Bapak/Ibu Dosen <?= $data_dosen->nama_dosen; ?></b></h5>
                    <hr>
                </div>
                <div class="box-body"><br>

                    <form class="form-horizontal" id="form" method="POST"
                          action="<?= site_url('admin/jurusan/dosen/ubah_password') ?>">
                        <input type="hidden" value="<?= $data_dosen->kode_dosen ?>" name="kode_dosen_password">
                        <div class="form-group" id="group-password">
                            <label class="control-label col-sm-2">Password Baru</label>
                            <div class="col-sm-4">
                                <input value="<?= set_value('password') ?>" type="password" name="password"
                                       class="form-control" placeholder="Password Baru">
                                <small style="color: red;"><?= form_error('password') ?></small>
                            </div>

                        </div>
                        <div class="form-group" id="group-ulangi-password">
                            <label class="control-label col-sm-2">Ulangi Password Baru</label>
                            <div class="col-sm-4">
                                <input value="<?= set_value('ulangi_password') ?>" name="ulangi_password"
                                       type="password" class="form-control" placeholder="Ulangi  Password Baru">
                                <small style="color: red;"><?= form_error('ulangi_password') ?></small>
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2"></label>
                            <div class="col-sm-5">
                                <button type="submit" name="submit" class="btn btn-primary flat"><i
                                            class="fa fa-check-circle"></i> Simpan
                                </button>
                                <button type="reset" name="submit" class="btn btn-danger flat"><i
                                            class="fa fa-refresh"></i> Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<!--upload signature-->
<script>
    function cot(id) {
        $("#upload-" + id + ":hidden").trigger('click');
    }
    function readURL(input,id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
            var action = "<?= site_url('admin/jurusan/dosen/upload_image') ?>/"+id;
            var form = $('#form-file-'+id)[0];
            $.ajax({
                url: action,
                type:"post",
                data: new FormData(form),
                processData:false,
                contentType:false,
                enctype: 'multipart/form-data',
                cache:false,
                async:false,
                success: function(res){
                    console.log(res);
                    var obj = JSON.parse(res)
                    if (obj.status == true)
                    {
                        alert('Berhasil upload gambar');
                        location.reload();
                    }else{
                        alert(obj.msg);
                    }
                },
                error : function () {
                    console.log('gagal');
                }
            });
        }
    }
</script>
