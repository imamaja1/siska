<div class="col-md-12">
    <div class="box box-danger">
        <div class="box-header">
            <h3 class="box-title"><span class="text-danger"><i class="fa fa-ban"></i></span> Error report</h3>
        </div>
        <div class="box-body">
            <ul>
                <?php if (count($error) > 0) : ?>
                    <ul>
                        <?php foreach ($error as $row) : ?>
                            <li><span class="text-danger"><?= $row['msg'] ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <div class="callout callout-info">
                        <h4>Information!</h4>

                        <p>Tidak di temukan error.</p>
                    </div>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>