<?= $this->session->flashdata('info') ?>

<?php if (count($data) > 0) : ?>
    <?php $this->load->model('jurusan/m_dosen') ?>
<div class="box box-solid flat">
    <div class="box-body">
        <span class="badge bg-aqua pull-right">Perwalian <i class="fa fa-arrow-circle-o-right"></i> <b><?= e($nama_dosen) ?></b></span>
        <!--<a href="<?= site_url('admin/jurusan/perwalian') ?>" class="btn btn-danger btn-sm flat"><i class="fa fa-arrow-left"></i> Kembali</a>-->
    </div>
</div>
<form id="form-data-perwalian" action="<?= site_url('admin/jurusan/perwalian/pindah_perwalian') ?>" method="post">
<div class="box box-solid flat">
    <div class="box-body">
        <table class="table demo-table">
            <thead>
                <tr>
                    <th id="color" width="20" style="text-align: center;">No.</th>
                    <th id="color" width="20" style="text-align: center;">CEK</th>
                    <th id="color" style="text-align: center;">NIM</th>
                    <th id="color" style="text-align: center;">Nama Mahsaiswa</th>
                    <th id="color" style="text-align: center;">Dosen Perwakilan</th>
                    <th  id="color"style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php $i=1; foreach ($data as $row) : ?>
                <tr>
                    <td style="text-align: center;"><?=$i++ ?>.</td>
                    <td style="text-align: center;">
                        <input type="checkbox" class="cek" name="kode_perwalian[]" value="<?= e($row->kode_perwalian) ?>">
                    </td>
                    <td style="text-align: center;"><?= e($row->nim) ?></td>
                    <td ><?= e($row->nama_mahasiswa) ?></td>
                    <td class="dosen-perwakilan">
                        <?php if ($row->kode_dosen_perwakilan) : ?>
                            <?= e($this->m_dosen->get_nama($row->kode_dosen_perwakilan)) ?>&nbsp;
                            <a href="#" onclick="hapus_perwakilan('<?= e($row->kode_perwalian) ?>', this)"><span class="text-danger"><i class="fa fa-times"></i></span></a>
                        <?php endif; ?>
                    <td style="text-align: center;">
                        <a href="#" class="text-info" onclick="ubah('<?= e($row->kode_perwalian) ?>')"><i class="fa fa-edit"></i> Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="box box-solid flat">
    <div class="box-body">
        <div class="form-group">
            <label for="" class="control-label">Jenis Ubah</label>
            <div class="radio">
                <label>
                    <input type="radio" name="jenis_ubah" id="optionsRadios1" value="1" checked="">
                    Perwalian
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="jenis_ubah" id="optionsRadios2" value="0">
                    Perwakilan
                </label>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-xs-7">
                <select name="kode_dosen" class="form-control select2" required>
                    <option value="" selected disabled>Pilih</option>
                    <?php foreach ($dosen_perwalian as $row) : ?>
                        <option value="<?= e($row->kode_dosen) ?>"><?= e($row->nama_dosen) ?> (<?= e($row->singkatan_program_studi) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-xs-2">
                <button class="btn btn-success flat" type="submit"><i class="fa fa-arrow-circle-right"></i></button>
            </div>
        </div>
    </div>
</div>
</form>
    <script>
        $(document).ready(function () {
            $("#form-data-perwalian").submit(function (e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var data = $(this).serialize();
                $.ajax({
                    url : url,
                    data : data,
                    type : "post",
                    beforeSend : function () {
                        $("#landing").html("<div class='text-center'><img src='https://assets.website-files.com/5c7fdbdd4e3feeee8dd96dd2/5ce46f8ffd710a2c22c15e48_cust_ami.gif'/></div>");
                    },
                    success : function (res) {
                        $("#landing").html(res);
                    }
                })
            })
        })

        function hapus_perwakilan(id, contex) {
            var url = "<?= site_url('admin/jurusan/perwalian/hapus_perwakilan') ?>/"+id;
            swal({
                title: '',
                text: "Anda yaikin menghapus dosen perwakilan?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Tidak',
                confirmButtonText: 'Ya'
            }).then(function () {
                $.ajax({
                    url : url,
                    success : function (res) {
                        var obj = JSON.parse(res);
                        if (obj.status === true){
                            $(contex).closest(".dosen-perwakilan").empty();
                        }else {
                            swal("Gagal!","Data gagal di hapus","error");
                        }
                    }
                })
            });

        }
    </script>
<?php else: ?>
    <div class="alert alert-info alert-dismissible flat">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ã—</button>
        <h4><i class="icon fa fa-warning"></i> Info!</h4>
        Data Tidak ditemukan...
    </div>
<?php endif; ?>
<!--model-edit-->

<div class="modal fade" id="modal-ubah-perwalian" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modal-content">

        </div>
    </div>
</div>

<script>
    $(".select2").select2();
    function ubah(id) {
        var url = "<?= site_url('admin/jurusan/perwalian/edit_perwalian') ?>/"+id+"/filter";
        $.ajax({
            url : url,
            success : function (res) {
                $("#modal-content").html(res);
                $("#modal-ubah-perwalian").modal("show");
            }
        })
    }

    function simpan(contex, e) {
        e.preventDefault();
        var url = $(contex).attr('action');
        var data = $(contex).serialize();
        $.ajax({
            url : url,
            data : data,
            type : 'post',
            success : function (res) {
                $("#landing").html(res);
                $('.modal-backdrop').remove()
                $(document.body).removeClass("modal-open");
            }
        })
    }
</script>