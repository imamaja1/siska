<?= e($this->session->flashdata('info')) ?>
<!--<div class="box box-solid flat">-->
<!--<div class="box-body">-->
<div class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <!--<h4><i class="icon fa fa-info"></i> Perhatian!</h4>-->
    Anda dapat mengusulkan penambahan bidang ilmu yang belum tersedia dengan menghubungi pihak pustik.
</div>
<!--</div>-->
<!--</div>-->
<div class="row">
    <div class="col-md-6">
        <div class="box box-primary flat">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table demo-table data-nilai3 table-responsive">
                        <thead>
                            <tr>
                                <th id="th">No</th>
                                <th id="th">Bidang Ilmu</th>
                                <th id="th">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($data_bidang_ilmu as $dbi) {
                                ?>
                                <tr>
                                    <td align="center"><?= $i++ ?></td>
                                    <td><?= e($dbi->nama_bidang) ?></td>
                                    <td>
                                        <!--<input type="hidden" id="id_bidang_ilmu" name="id_bidang_ilmu" value="">-->
                                        <button type="button" class="btn btn-info btn-flat" onclick="add_bidang_ilmu('<?= e($dbi->id_bidang_ilmu) ?>')">Pilih</button>
                                    </td>

                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-primary flat">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bidang Ilmu Yang anda Pilih</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="tampil_data">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function add_bidang_ilmu(id_bidang) {
//        alert(id_bidang);
        $.ajax({
            url: "<?= site_url('dosen/bidang_ilmu/add_bidang_ilmu') ?>/" + id_bidang,
            type: "GET",
            async: false,
            dataType: "json",
            data: "id_bidang=" + id_bidang,
            success: function (data) {
                status = data;

            }
        });
        if (status === "false") {
            swal("Gagal!", "Data sudah pernah ditambahkan", "error");
        } else {
            location.reload();
            swal("Berhasil!", "Data ditambahkan", "success");

        }
    }
</script>

<script>
    $(function () {
        $.ajax({
            url: "<?= site_url('dosen/bidang_ilmu/show_bidang_ilmu_anda') ?>",
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                var nomor = 0;
                for (i = 0; i < data.length; i++) {
                    nomor++;
                    $('.tampil_data').append('<tr><td>' + nomor + '</td><td>' + data[i].nama_bidang + '</td><td><button onclick="hapus_bidang_ilmu_pilihan(' + data[i].id_bidang_ilmu_detail + ')" class="btn btn-danger btn-sm">Hapus</button> </td></tr>');

                }
            },
            error: function (data) {
                alert("Terjadi Kesalahan!");
            }
        });
    });
</script>

<script>
    function hapus_bidang_ilmu_pilihan(id_bidang_detail) {
        swal({
            title: "",
            text: "Anda yakin ingin menghapus data ini?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: 'red',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya',
            closeOnConfirm: false
        }).then(function () {
            $.ajax({
                url: "<?= site_url('dosen/bidang_ilmu/hapus_bidang_ilmu_detail') ?>/" + id_bidang_detail,
                type: "GET",
                data: "id_bidang_detail=" + id_bidang_detail,
                success: function (data) {
                    location.reload();
                }
            });
        });
    }
</script>



