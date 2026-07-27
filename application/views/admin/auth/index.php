
<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('auth/create_user') ?>" class="btn btn-primary flat"><i class="fa fa-plus-circle"></i> Tambah User</a>
        <a href="<?= site_url('auth/create_group') ?>" class="btn btn-success flat"><i class="fa fa-plus-circle"></i> Tambah Group</a>
    </div>
</div>
<div id="infoMessage"><?php echo e($message); ?></div>
<div class="box box-primary flat">
    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th bgcolor="#B0C4DE">Nama Depan</th>
                <th bgcolor="#B0C4DE">Nama Belakang</th>
                <th bgcolor="#B0C4DE"><?php echo lang('index_email_th'); ?></th>
                <th bgcolor="#B0C4DE"><?php echo lang('index_groups_th'); ?></th>
                <th bgcolor="#B0C4DE"><?php echo lang('index_status_th'); ?></th>
                <th bgcolor="#B0C4DE"><?php echo lang('index_action_th'); ?></th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <?php foreach ($user->groups as $group): ?>
                            <?php echo anchor("auth/edit_group/" . $group->id, htmlspecialchars($group->name, ENT_QUOTES, 'UTF-8')); ?><br />
                        <?php endforeach ?>
                    </td>
                    <td><?php echo ($user->active) ? anchor("auth/deactivate/" . $user->id, lang('index_active_link')) : anchor("auth/activate/" . $user->id, lang('index_inactive_link')); ?></td>
                    <td><?php echo anchor("auth/edit_user/" . $user->id, 'Edit'); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
