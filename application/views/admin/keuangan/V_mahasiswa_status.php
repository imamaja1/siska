<div class="row">
    <div class="col-sm-12 col-md-12 col-xs-12">
        <div class="box box-solid">
            <div class="box-body">
                <div class="pull-right">
                    <a href="#" class="btn btn-sm btn-success btn-flat" onclick="window.location.href = '<?= site_url('admin/keuangan/status_perkuliahan')  ?>'"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12">
        <div class="box box-primary flat">
            <div class="box-body">
                <?php if (count($data) > 0): ?>
                    <table class="table demo-table" id="example1">
                        <thead>
                        <tr>
                            <th id="color">No.</th>
                            <th id="color">NIM</th>
                            <th id="color">Nama Mahasiswa</th>
                            <th id="color">Staus Perkuliahan</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=1;
                        foreach ($data as $row) { ?>
                            <tr>
                                <td width="3%"><?= $i++  ?></td>
                                <td><?= $row->nim  ?></td>
                                <td><?= $row->nama_mahasiswa  ?></td>
                                <?php if ($row->status_perkuliahan == 'A') : ?>
                                    <td id="edit-<?= $row->kode_status_perkuliahan ?>"><span class="badge bg-green"><?= $row->status_perkuliahan  ?></span> <span class="text-aqua" onclick="edit('<?= $row->kode_status_perkuliahan ?>')"><i class="fa fa-pencil"></i></span></td>
                                <?php elseif ($row->status_perkuliahan == 'C') : ?>
                                    <td id="edit-<?= $row->kode_status_perkuliahan ?>"><span class="badge bg-orange"><?= $row->status_perkuliahan  ?></span> <span class="text-aqua" onclick="edit('<?= $row->kode_status_perkuliahan ?>')"><i class="fa fa-pencil"></i></span></td>
                                <?php elseif ($row->status_perkuliahan == 'P') : ?>
                                    <td id="edit-<?= $row->kode_status_perkuliahan ?>"><span class="badge bg-aqua"><?= $row->status_perkuliahan  ?></span> <span class="text-aqua" onclick="edit('<?= $row->kode_status_perkuliahan ?>')"><i class="fa fa-pencil"></i></span></td>
                                <?php elseif ($row->status_perkuliahan == 'B') : ?>
                                    <td id="edit-<?= $row->kode_status_perkuliahan ?>"><span class="badge bg-red"><?= $row->status_perkuliahan  ?></span> <span class="text-aqua" onclick="edit('<?= $row->kode_status_perkuliahan ?>')"><i class="fa fa-pencil"></i></span></td>
                                <?php elseif ($row->status_perkuliahan == 'T') : ?>
                                    <td id="edit-<?= $row->kode_status_perkuliahan ?>"><span class="badge bg-navy"><?= $row->status_perkuliahan  ?></span> <span class="text-aqua" onclick="edit('<?= $row->kode_status_perkuliahan ?>')"><i class="fa fa-pencil"></i></span></td>
                                <?php elseif ($row->status_perkuliahan == 'L') : ?>
                                    <td id="edit-<?= $row->kode_status_perkuliahan ?>"><span class="badge bg-navy"><?= $row->status_perkuliahan  ?></span> <span class="text-aqua" onclick="edit('<?= $row->kode_status_perkuliahan ?>')"><i class="fa fa-pencil"></i></span></td>
                                <?php endif; ?>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p class="alert alert-warning">Data tidak ditemukan</p>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<script>
    function edit(id) {
        var url = "<?= site_url('admin/keuangan/status_perkuliahan/edit') ?>/"+id;
        $.ajax({
            url : url,
            success : function (res) {
                $("#edit-"+id).popover({
                    placement: 'left',
                    title: 'Edit Status Perkuliahan',
                    html:true,
                    content:  res
                });
            },
            error : function () {
                console.log('gagal load');
            }
        })
    }

</script>