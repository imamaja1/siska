<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-body">
               <div class="form-group col-md-5 col-xs-12" style="margin-bottom: 0px">
                   <select class="form-control" name="kode_prodi" id="kode-prodi">
                       <option value="" selected disabled>Program Studi</option>
                       <?php foreach ($prodi as $row): ?>
                           <option value="<?= e($row->kode_program_studi) ?>"><?= e($row->nama_program_studi) ?></option>
                       <?php endforeach; ?>
                   </select>
               </div>
            </div>
        </div>
    </div>
    <div class="col-md-12" id="landing">
        <div class="box box-solid" style="height: 300px; border-radius: 20px; border: 2px solid #3c8dbc">
            <div class="box-body">
                <p style="text-align: center; font-weight: bold; font-size: 24pt"><i>"Landing..."</i></p>
            </div>
        </div>
    </div>
</div>

<script>
    var loading =  "<div class='text-center'><img src='https://assets.website-files.com/5c7fdbdd4e3feeee8dd96dd2/5ce46f8ffd710a2c22c15e48_cust_ami.gif'/></div>";
    var kode_program_studi;
    $(function () {
        $(".datepicker").datepicker({
            format: "yyyy-mm-dd",
            autoclose: 'ture',
        });

        $("#kode-prodi").change(function () {
            var val = $(this).val();
            kode_program_studi = val;
            var url = "<?= site_url('admin/akademik/status_perkuliahan/rekap') ?>/"+val;
            $.ajax({
                url : url,
                beforeSend : function () {
                    $("#landing").html(loading);
                },
                success : function (res) {
                    $("#landing").html(res);
                }
            })
        })
    });
</script>