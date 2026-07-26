<div class="box box-primary flat">
    <div class="box-body">
        <form action="<?= site_url('admin/rbac/simpan_access') ?>" method="post">
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label>Role</label>
                        <select name="id_role" onchange="ambil_role(this.value)" class="form-control ">
                            <?php foreach ($role as $row) : ?>
                                <option <?= ($this->session->userdata('id_role') == $row->id_role) ? 'selected' : '' ?>
                                        value="<?= $row->id_role ?>"> <?= $row->nama_role ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-8" style="border-left: 2pt solid #3c8dbc">
                    <div id="tampil">
                        <?php
                        foreach (scanDirectories('./application/controllers') as $row => $value) :
                            $pecah = explode('/', $value);
                            $end = end($pecah);
                            ?>
                            <div class="form-group">
                                <label for="<?= str_replace('.php','',$end) ?>">
                                    <input type="checkbox" <?= (in_array(str_replace('.php','',$end), $controller)) ? 'checked' : '' ?>
                                           id="<?= str_replace('.php','',$end) ?>" name="controller[]"
                                           value="<?= str_replace('.php','',$end) ?>"> <?= $value ?>
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

<script>
    function ambil_role(role) {
        $.ajax({
            type: 'get',
            url: '<?=site_url('admin/rbac/get_list_file')?>/' + role,
            success: function (data) {
//                alert('hore' + data);
                $('#tampil').html(data);
            }
        })
    }
</script>