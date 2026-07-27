<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/nilai/get_update_nilai'); ?>" class="btn btn btn-default flat"><i
                    class="fa fa-arrow-left"></i> Kembali</a>
        <!-- <a href="#" onclick="$('#modal-upload').modal('toggle')" class="btn btn btn-xs btn-primary flat"><i class="fa fa-file-excel-o"></i> Import</a> -->
        <!--        <a href="--><? //= site_url('admin/akademik/nilai/import');?>
        <!--" class="btn btn btn-xs btn-primary flat"><i class="fa fa-file-excel-o"></i> Import</a>-->

        &emsp;
        <?php if (!empty($data_kelas)) : ?>
            Filter Berdasarkan Kelas :
            <?php foreach ($data_kelas as $row) : ?>
                <a href="<?= site_url('admin/akademik/nilai/get_all_mahasiswa_for_update_nilai_matakuliah/0/' . $row->kelas_id) ?>"
                   class="btn btn-flat btn-<?= ($this->session->userdata('kelas_id') == $row->kelas_id) ? 'success' : 'warning' ?> "><b><?= e($row->nama_kelas) ?></b></a>
            <?php endforeach; ?>
            <a href="<?= site_url('admin/akademik/nilai/get_all_mahasiswa_for_update_nilai_matakuliah/0/01') ?>"
               class="btn btn-flat btn-<?= ($this->session->userdata('kelas_id') == 0) ? 'success' : 'warning' ?> "><b>Semua
                    Data (<?= e($jumlah_data) ?>)</b></a>
        <?php else : ?>
            Kelas pada Matakuliah belum dibagi (Jumlah Mahasiswa : <?= e($jumlah_data) ?>)
        <?php endif; ?>
    </div>
</div>
<?php if (!empty($dosen)) : ?>
    <div class="box box-solid flat">
        <div class="box-body">
            Dosen Pengajar :
            <?php foreach ($dosen as $row) :
                echo '<b>' . e($row->nama_dosen) . '</b> - ';
            endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<div class="box box-primary flat">
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table data-nilai" id="table-edit">
                <thead>
                    <tr style="text-align: center;">
                        <th class="no-sort" id="th">NO.</th>
                        <td style="display:none;">id</td>
                        <th id="th">NIM</th>
                        <th id="th">NAMA MAHASISWA</th>
                        <th id="th">STATUS MK</th>
                        <th class="no-sort" id="th">NILAI HARIAN</th>
                        <th class="no-sort" id="th">NILAI UTS</th>
                        <th class="no-sort" id="th">NILAI UAS</th>
                        <th class="no-sort" id="th">NILAI AKHIR</th>
                        <!-- <th class="no-sort" id="th">TIDAK BERHAK (TB)</th> -->
                    </tr>
                </thead>
                <tbody>
                <?php
                $i = 0 + $this->uri->segment(5);
                foreach ($nilai_matakuliah as $row) {
                    ?>
                    <tr class="dark">
                        <td><?php echo ++$i . '.'; ?></td>
                        <td style="display:none;"><?= e($row->kode_khs_detail) ?></td>
                        <td><?php echo e($row->nim); ?></td>
                        <td><?php echo e($row->nama_mahasiswa); ?></td>	
                        <td align="center"><?= e($row->status) ?></td>	
                        <td style="text-align:center;">
                            <?= e($row->nilai_harian) ?>
                        </td>
                        <td style="text-align:center;">
                            <?= e($row->nilai_uts) ?>
                        </td>
                        <td style="text-align:center;">
                           <?= e($row->nilai_uas) ?>
                        </td>
                        <td style="text-align:center;">
                            <?= e($row->nilai_akhir) ?>
                        </td>
                        <!-- 
						<td align="center">
                            <div class="form-group" style="margin: 0px">
                                <select onchange="tidak_berhak(<?= $row->kode_khs_detail ?>,this)" name="tidak_berhak" class="form-control" id="">
                                    <option value="">Pilih</option>
                                    <option value="N" <?= $row->tidak_berhak == 'N' ? 'selected' : '' ?> >N</option>
                                    <option value="A" <?= $row->tidak_berhak == 'A' ? 'selected' : '' ?> >A</option>
                                </select>
                            </div>
                        </td>
						-->
                    </tr>
                    <?php
                }
                ?>
              </tbody>
            </table>

        </div>
    </div>
</div>
<?= $this->session->flashdata('message') ?>
<?php
if (!empty($link)) {
    echo '<p id="bottom_link">';
    foreach ($link as $links) {
        echo e($links) . ' ';
    }
    echo '</p>';
}
?>
<div class="modal fade" id="modal-upload" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><span class="text-success"><i class="fa fa-file-excel-o"></i></span> Upload File Excel</h4>
            </div>
            <div class="modal-body">
                <form id="form-upload" action="<?= site_url('admin/akademik/nilai/upload'); ?>" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group">
                        <label for="">File Excel</label>
                        <input type="file" name="file">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Upload</button>
                        <a class="btn btn-default" href="<?= base_url('assets/excel/format.xlsx') ?>"><i class="fa fa-download"></i> Download format</a>
                    </div>
                </form>
                <hr>
                <div id="landing-upload">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script type="text/javascript">
    function nilai(id,contex) {
        var value = $(contex).val();
        var name = $(contex).attr('name');
        $(contex).click(function (e) {
            $(contex).closest(".form-group").tooltip("hide");
        });
        if (value)
        {
            if ($.isNumeric(value))
            {
                if (value >= 0 && value <= 100)
                {
                    $.ajax({
                        url : "<?= site_url('admin/akademik/nilai/update_nilai') ?>/"+id,
                        type : "post",
                        data : "input_name="+name+"&nilai="+value,
                        success : function (res) {
                            if (res)
                            {
                                $(contex).closest(".form-group").removeClass('has-error').addClass('has-success');
                            }else{
                                $(contex).closest(".form-group").removeClass('has-success').addClass('has-error');
                                $(contex).closest(".form-group").tooltip({
                                    title : 'Nilai gagal di ubah',
                                    trigger: "manual"
                                });
                                $(contex).closest(".form-group").tooltip("show");
                                $(contex).focus();
                            }
                        }
                    })
                }else{
                    $(contex).closest(".form-group").removeClass('has-success').addClass('has-error');
                    $(contex).closest(".form-group").tooltip({
                        title : 'Nilai harus 0 - 100',
                        trigger: "manual"
                    });
                    $(contex).closest(".form-group").tooltip("show");
                    $(contex).focus();
                }
            }else{
                $(contex).closest(".form-group").removeClass('has-success').addClass('has-error');
                $(contex).closest(".form-group").tooltip({
                    title : 'Nilai harus berupa angka',
                    trigger: "manual"
                });
                $(contex).closest(".form-group").tooltip("show");
                $(contex).focus();
            }
        }else{
            $.ajax({
                url : "<?= site_url('admin/akademik/nilai/update_nilai') ?>/"+id,
                type : "post",
                data : "input_name="+name+"&nilai="+value,
                success : function (res) {
                    if (res)
                    {
                        $(contex).closest(".form-group").removeClass('has-success');
                        $(contex).closest(".form-group").removeClass('has-error');
                    }else{
                        $(contex).closest(".form-group").removeClass('has-success').addClass('has-error');
                        $(contex).closest(".form-group").tooltip({
                            title : 'Nilai gagal di ubah',
                            trigger: "manual"
                        });
                        $(contex).closest(".form-group").tooltip("show");
                        $(contex).focus();
                    }
                }
            })

        }
    }

    function tidak_berhak(id,contex) {
        var tidak_berhak = $(contex).val();
        $.ajax({
            url : "<?= site_url('admin/akademik/nilai/update_tidak_berhak') ?>/"+id,
            type : "post",
            data : "tidak_berhak="+tidak_berhak,
            success : function (res) {
                if (res)
                {
                    $(contex).closest(".form-group").removeClass('has-error').addClass('has-success');
                }else{
                    $(contex).closest(".form-group").removeClass('has-success').addClass('has-error');
                    $(contex).closest(".form-group").tooltip({
                        title : 'Tidak behak gagal di ubah',
                        trigger: "manual"
                    });
                    $(contex).closest(".form-group").tooltip("show");
                    $(contex).focus();
                }
            }
        })
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#form-upload").submit(function (e) {
            e.preventDefault();
            var url = $(this).prop('action');
            $.ajax({
                url:url,
                type:"post",
                data: new FormData(this),
                dataType : "text",
                processData:false,
                contentType:false,
                cache:false,
//                async:false,
                beforeSend : function () {
                    var html = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
                    $("#landing-upload").html(html);
                },
                success: function(data){
                    var obj = JSON.parse(data);
                    if (!obj.status){
                        var html = '<div class="callout callout-danger"><h4>Gagal Upload!</h4>' +obj.error+
                            '</div>';
                        $("#landing-upload").html(html);
                    }else{
                        $("#landing-upload").html(obj.page);
                    }
                    console.log(obj.error);

                }
            });
        })

        $('#modal-upload').on('hidden.bs.modal', function () {
            $("#landing-upload").empty();
            $.ajax({
                url : "<?= site_url('admin/akademik/nilai/delete_file') ?>",
                success : function (res) {
                    window.location.reload();
                    console.log(res);
                }
            })
        })
    })
//    $('#table-edit').Tabledit({
//        url: "<?//= site_url('admin/akademik/nilai/get_all_mahasiswa_for_update_nilai_matakuliah_process') ?>//",
//        hideIdentifier: true,
//        deleteButton: false,
//        buttons: {
//            save: {
//                class: 'btn btn-sm btn-success',
//                html: 'Save'
//            }
//        },
//        columns: {
//            identifier: [1, 'kode_khs_detail'],
//            editable: [[5, 'nilai_harian'], [6, 'nilai_uts'], [7, 'nilai_uas'], [8, 'nilai_akhir'], [9, 'tidak_berhak', '{"1": "Berhak", "2": "Tidak Berhak"}']],
//        }
//    });
//
//    function ref() {
//        var url = window.location.href;
//        window.location.href = url;
//    }
</script>