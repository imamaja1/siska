<?= $this->session->flashdata('info'); ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a href="#" class="btn-primary btn-sm flat" onclick="$('#modal-add').modal('toggle')"><i class="fa fa-plus-circle"></i> Tambah</a>
    </div>
</div>
<div class="box box-info flat">
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table">
                <thead>
                    <tr>
                        <th style="width:3%; text-align:center">No</th>
                        <th style="text-align:center">Kode Fakultas</th>
                        <th style="text-align:center">Nama Fakultas</th>
                        <th style="text-align:center">Dekan</th>
                        <th style="text-align:center; width:150px">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($fakultas as $row) : ?>
                        <tr>
                            <td align="center"><?= $no++ ?></td>
                            <td align="center"><?= $row->kode_fakultas ?></td>
                            <td><?= $row->nama_fakultas ?></td>
                            <td><?= $row->dekan ?></td>
                            <td align="center">
                                <a href="#!" class="btn-xs btn-info flat" onclick="edit('<?= $row->kode_fakultas ?>')"><i class="fa fa-edit"></i> Ubah</a>&nbsp;
                                <a href="#!" class="btn-xs btn-danger flat" onclick="hapus('<?= site_url('admin/jurusan/universitas/fakultas/delete/' . $row->kode_fakultas) ?>')"><i class="fa fa-trash"></i> Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id="landing-add"></div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id="landing-edit"></div>
    </div>
</div>

<script>
    function edit(id) {
        var url = "<?= site_url('admin/jurusan/universitas/fakultas/edit') ?>/" + id;
        $.ajax({
            url: url,
            success: function (res) {
                $("#landing-edit").html(res);
                $("#modal-edit").modal('show');
            }
        });
    }

    function tambah() {
        var url = "<?= site_url('admin/jurusan/universitas/fakultas/tambah') ?>";
        $.ajax({
            url: url,
            success: function (res) {
                $("#landing-add").html(res);
                $("#modal-add").modal('show');
            }
        });
    }

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
