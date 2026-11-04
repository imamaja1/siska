<?= $this->session->flashdata('pesan'); ?>

<div class="box box-primary flat">
    <div class="box-body">
        <form action="<?= site_url('admin/rbac/simpan_access') ?>" method="post">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <div class="clearfix" style="margin-bottom: 8px;">
                            <label style="margin-bottom: 0; padding-top: 5px;">Pilih Role</label>
                            <button type="button" class="btn btn-success btn-xs btn-flat pull-right" data-toggle="modal" data-target="#modalTambahRole">
                                <i class="fa fa-plus"></i> Tambah Role
                            </button>
                        </div>
                        <select name="id_role" onchange="ambil_role(this.value)" class="form-control">
                            <?php foreach ($role as $row) : ?>
                                <option <?= ($this->session->userdata('id_role') == $row->id_role) ? 'selected' : '' ?>
                                        value="<?= e($row->id_role) ?>"> <?= e($row->nama_role) ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-8" style="border-left: 2pt solid #578EF5">
                    <div id="tampil">
                        <?php
                        foreach (scanDirectories('./application/controllers') as $row => $value) :
                            $pecah = explode('/', $value);
                            $end = end($pecah);
                            ?>
                            <div class="form-group">
                                <label for="<?= str_replace('.php','',$end) ?>">
                                    <input type="checkbox" <?= (in_array(str_replace('.php','',$end), $controller)) ? 'checked' : '' ?>
                                           id="<?= e(str_replace('.php','',$end)) ?>" name="controller[]"
                                           value="<?= e(str_replace('.php','',$end)) ?>"> <?= e($value) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-check-square-o"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Role -->
<div class="modal fade" id="modalTambahRole" tabindex="-1" role="dialog" aria-labelledby="modalTambahRoleLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?= site_url('admin/rbac/tambah_role') ?>" method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalTambahRoleLabel"><i class="fa fa-shield"></i> Tambah Role Baru</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_role">Nama Role <span class="text-danger">*</span></label>
                        <input type="text" name="nama_role" id="nama_role" class="form-control" placeholder="Contoh: Staff Akademik, Kepegawaian, dll." required autocomplete="off">
                        <small class="text-muted">Masukkan nama peran (role) baru yang ingin dibuat.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-save"></i> Simpan Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function ambil_role(role) {
        $.ajax({
            type: 'get',
            url: '<?=site_url('admin/rbac/get_list_file')?>/' + role,
            success: function (data) {
                $('#tampil').html(data);
            }
        });
    }
</script>
