<div class="box">
    <div class="box-header">
        Hasil pencarian untuk <i><b>"<?= e($nim) ?>"</b></i>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table">
                <thead>
                <tr>
                    <th style="text-align: center">NIM</th>
                    <th style="text-align: center">NAMA MAHASISWA</th>
                    <th style="text-align: center">DOSEN WALI</th>
                    <th style="text-align: center">DOSEN PERWAKILAN</th>
                    <th style="text-align: center">AKSI</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="text-align: center"><?= e($nim) ?></td>
                    <td ><?= e($nama_mahasiswa) ?></td>
                    <td id="dosen-wali"><?= e($nama_dosen) ?></td>
                    <td id="dosen-perwakilan">
                        <?php if ($nama_dosen_perwakilan) : ?>
                            <?= e($nama_dosen_perwakilan) ?>&nbsp;
                            <a href="#" onclick="hapus_perwakilan('<?= e($kode_perwalian) ?>', this)"><span class="text-danger"><i class="fa fa-times"></i></span></a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="#" onclick="ubah('<?= e($kode_perwalian) ?>')" class="text-info" title="Edit"><i class="fa fa-edit"></i> Edit</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-ubah-perwalian" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modal-content">

        </div>
    </div>
</div>
<script>
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
                        $("#dosen-perwakilan").empty();
                    }else {
                        swal("Gagal!","Data gagal dihapus","error");
                    }
                }
            })
        });

    }

    function ubah(id) {
        var url = "<?= site_url('admin/jurusan/perwalian/edit_perwalian') ?>/"+id+"/search";
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
                var obj = JSON.parse(res);
                $("#dosen-wali").html(obj.nama_dosen)
                html = obj.nama_dosen_perwakilan
                html += "&nbsp;<a href='#' onclick='hapus_perwakilan(\""+obj.kode_perwalian+"\", this)'>"
                html += "<span class='text-danger'>"
                html += "<i class='fa fa-times'></i>"
                html += "</span>"
                html += "</a>"
                $("#dosen-perwakilan").html(html)
//                $("#landing").html(res);
//                console.log(res);
                $("#modal-ubah-perwalian").modal('hide');
                $('.modal-backdrop').remove()
                $(document.body).removeClass("modal-open");
            }
        })
    }
</script>