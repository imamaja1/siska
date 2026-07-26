<div class="row">
    <div class="col-md-12">
        <div class="box box-solid flat">
            <div class="box-body">
                <a href="<?= site_url('admin/kuisioner/kelas') ?>" class="btn pull-right btn-danger btn-xs" title="Kembali"><i class="fa fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-md-4 col-xs-12">
                        <label for="">Tahun Kademik <span class="text-danger">*</span></label>
                        <div class="input-group">
                        <select name="kode_tahun_akademik" id="kode_tahun_akademik" required class="form-control select2">
                            <option value="" selected disabled>Pilih</option>
                            <?php foreach ($tahun_akademik as $row) : ?>
                                <option value="<?= $row->kode_tahun_akademik ?>" ><?= $row->tahun_akademik ?> - <?= $row->semester == '0' ? 'GENAP' : 'GANJIL' ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="input-group-btn">
                            <button class="btn pull-right btn-primary" onclick="filter()"><i class="fa fa-gear"></i> Proses</button>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12" id="landing">
</div>

    
<script>
    var loader = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
    var id;
    function filter() {
        id = document.getElementById("kode_tahun_akademik").value;
        if (id) {
            $.ajax({
                url : "<?= base_url("admin/kuisioner/kelas/tanggal_aktivasi"); ?>"+"/"+id,
                beforeSend : function () {
                    $("#landing").html(loader);
                },
                success : function (res) {
                    $("#landing").html(res);    
                },
            })
        }
        
    }
    function generit() {
        $.ajax({
            url : "<?= base_url("admin/kuisioner/kelas/generit_aktivasi"); ?>"+"/"+id,
            beforeSend : function () {
                $("#landing").html(loader);
            },
            success : function (res) {
                $("#landing").html(res);
            }
        })
    }
    function update_uts(){
        $.ajax({
            url : "<?= base_url("admin/kuisioner/kelas/update_aktivasi_uts"); ?>"+"/"+id,
            type: "POST",    
            data: {
                tgl_awal_uts: document.getElementById("tgl_awal_uts").value,
                tgl_akhir_uts: document.getElementById("tgl_akhir_uts").value,
            },
            success : function (res) {
                swal({
                    title: "Sukses",
                    text: "Tanggal Sudah Diperbaharui!",
                    icon: "success",
                });
            }
        })

    }
    function update_uas(){
        $.ajax({
            url : "<?= base_url("admin/kuisioner/kelas/update_aktivasi_uas"); ?>"+"/"+id,
            type: "POST",    
            data: {
                tgl_awal_uas: document.getElementById("tgl_awal_uas").value,
                tgl_akhir_uas: document.getElementById("tgl_akhir_uas").value,
            },
            success : function (res) {
                swal({
                    title: "Sukses",
                    text: "Tanggal Sudah Diperbaharui!",
                    icon: "success",
                });
            }
        })
    }
</script>